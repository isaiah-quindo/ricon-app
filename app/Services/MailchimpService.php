<?php

namespace App\Services;

use App\Models\TrainingSignup;
use Illuminate\Support\Facades\Http;

class MailchimpService
{
    /**
     * Subscribe a training signup to the Mailchimp audience (single opt-in —
     * the form submission is the opt-in). Fire-and-forget: never throws, a
     * Mailchimp outage must not block signup. No-op when keys aren't configured.
     */
    public function subscribe(TrainingSignup $signup): void
    {
        $key    = config('services.mailchimp.key');
        $listId = config('services.mailchimp.list_id');
        $dc     = config('services.mailchimp.dc');

        if (! $key || ! $listId || ! $dc) {
            return;
        }

        try {
            $hash = md5($signup->email);
            $base = "https://{$dc}.api.mailchimp.com/3.0/lists/{$listId}/members/{$hash}";

            // Idempotent upsert — safe on repeat signups
            $response = Http::withBasicAuth('anystring', $key)
                ->timeout(5)
                ->put($base, [
                    'email_address' => $signup->email,
                    'status_if_new' => 'subscribed',
                    'status'        => 'subscribed',
                    'merge_fields'  => ['FNAME' => $signup->first_name],
                ]);

            if (! $response->successful()) {
                report(new \RuntimeException("Mailchimp subscribe failed for {$signup->email}: {$response->status()} {$response->body()}"));

                return;
            }

            Http::withBasicAuth('anystring', $key)
                ->timeout(5)
                ->post("{$base}/tags", [
                    'tags' => [
                        ['name' => $signup->plan, 'status' => 'active'],
                        ['name' => $signup->registered_tgc ? 'registered-tgc' : 'not-registered', 'status' => 'active'],
                    ],
                ]);

            $signup->forceFill(['mailchimp_synced_at' => now()])->save();
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
