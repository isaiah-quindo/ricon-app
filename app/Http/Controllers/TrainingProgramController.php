<?php

namespace App\Http\Controllers;

use App\Mail\TrainingProgramLink;
use App\Models\TrainingSignup;
use App\Services\MailchimpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class TrainingProgramController extends Controller
{
    public function landing()
    {
        return view('training.landing', [
            'currentWeek' => TrainingSignup::currentProgramWeek(),
        ]);
    }

    public function signup(Request $request, MailchimpService $mailchimp)
    {
        $validated = $request->validate([
            'first_name'     => 'required|string|max:100',
            'email'          => 'required|email|max:255',
            'plan'           => 'required|in:tgc100k,tgc60k',
            'registered_tgc' => 'required|boolean',
        ]);

        $email = strtolower(trim($validated['email']));

        $existing = TrainingSignup::where('email', $email)->first();

        if ($existing) {
            // Never reset started_on or plan; never expose the URL to a
            // possibly-different person typing this email — re-send instead.
            $this->sendLink($existing);

            return response()->json(['status' => 'existing']);
        }

        $signup = TrainingSignup::create([
            'first_name'     => $validated['first_name'],
            'email'          => $email,
            'plan'           => $validated['plan'],
            'registered_tgc' => $validated['registered_tgc'],
        ]);

        $this->sendLink($signup);
        $mailchimp->subscribe($signup);

        return response()->json([
            'status' => 'created',
            'url'    => $signup->program_url,
        ]);
    }

    public function resend(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $signup = TrainingSignup::where('email', strtolower(trim($validated['email'])))->first();

        if ($signup) {
            $this->sendLink($signup);
        }

        // Always generic — don't reveal whether the email has a program
        return response()->json(['status' => 'ok']);
    }

    public function show(string $token)
    {
        $signup = TrainingSignup::where('token', $token)->first();

        if (! $signup) {
            return redirect()->route('training.landing', ['expired' => 1]);
        }

        $program = json_decode(File::get(resource_path('data/training_program.json')), true);

        return view('training.program', compact('signup', 'program'));
    }

    // A mail-provider outage must never block someone out of the program
    private function sendLink(TrainingSignup $signup): void
    {
        try {
            Mail::to($signup->email)->send(new TrainingProgramLink($signup));
            $signup->forceFill(['link_last_sent_at' => now()])->save();
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
