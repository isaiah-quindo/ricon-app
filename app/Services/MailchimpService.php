<?php

namespace App\Services;

use App\Models\QuizLead;
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
        $synced = $this->upsert($signup->email, $signup->first_name, [
            $signup->plan,
            $signup->registered_tgc ? 'registered-tgc' : 'not-registered',
        ]);

        if ($synced) {
            $signup->forceFill(['mailchimp_synced_at' => now()])->save();
        }
    }

    // Same fire-and-forget contract as subscribe(), for readiness-quiz leads
    public function subscribeQuizLead(QuizLead $lead): void
    {
        $synced = $this->upsert($lead->email, $lead->first_name, [
            str_replace('_', '-', $lead->source),
        ]);

        if ($synced) {
            $lead->forceFill(['mailchimp_synced_at' => now()])->save();
        }
    }

    private function upsert(string $email, string $firstName, array $tags): bool
    {
        $key    = config('services.mailchimp.key');
        $listId = config('services.mailchimp.list_id');
        $dc     = config('services.mailchimp.dc');

        if (! $key || ! $listId || ! $dc) {
            return false;
        }

        try {
            $hash = md5($email);
            $base = "https://{$dc}.api.mailchimp.com/3.0/lists/{$listId}/members/{$hash}";

            // Idempotent upsert — safe on repeat signups
            $response = Http::withBasicAuth('anystring', $key)
                ->timeout(5)
                ->put($base, [
                    'email_address' => $email,
                    'status_if_new' => 'subscribed',
                    'status'        => 'subscribed',
                    'merge_fields'  => ['FNAME' => $firstName],
                ]);

            if (! $response->successful()) {
                report(new \RuntimeException("Mailchimp subscribe failed for {$email}: {$response->status()} {$response->body()}"));

                return false;
            }

            Http::withBasicAuth('anystring', $key)
                ->timeout(5)
                ->post("{$base}/tags", [
                    'tags' => collect($tags)
                        ->map(fn($tag) => ['name' => $tag, 'status' => 'active'])
                        ->values()
                        ->all(),
                ]);

            return true;
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }
}
