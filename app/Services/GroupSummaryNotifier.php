<?php

namespace App\Services;

use App\Mail\GroupRegistrationSummary;
use App\Models\RegistrationGroup;
use Illuminate\Support\Facades\Mail;

/**
 * Sends the organizer their aggregated recap.
 *
 * Approval can finish through either the bulk action on the group page or the
 * per-registration button, so the trigger lives here rather than in one controller.
 */
class GroupSummaryNotifier
{
    /**
     * Sends only once every member has a final outcome, and only once overall.
     *
     * @return bool whether an email went out
     */
    public static function sendIfComplete(RegistrationGroup $group): bool
    {
        $group->refresh()->load('registrations');

        if ($group->organizerNotified() || ! $group->allMembersResolved()) {
            return false;
        }

        return static::send($group);
    }

    /** Sends regardless of state, for the admin resend action. */
    public static function send(RegistrationGroup $group): bool
    {
        // Groups booked before organizer capture have no address to send to.
        if (! $group->organizer_email) {
            return false;
        }

        Mail::to($group->organizer_email)->send(new GroupRegistrationSummary($group));

        $group->forceFill(['organizer_notified_at' => now()])->save();

        return true;
    }
}
