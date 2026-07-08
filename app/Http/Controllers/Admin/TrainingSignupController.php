<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TrainingProgramLink;
use App\Models\TrainingSignup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TrainingSignupController extends Controller
{
    // List all training program signups
    public function index(Request $request)
    {
        $signups = TrainingSignup::query()
            ->when($request->plan, fn($q) => $q->where('plan', $request->plan))
            ->when($request->filled('registered_tgc'), fn($q) => $q->where('registered_tgc', (bool) $request->registered_tgc))
            ->when($request->search, fn($q) => $q->search($request->search))
            ->when(true, function ($q) use ($request) {
                $sortable = ['first_name', 'email', 'started_on', 'created_at'];
                $sort = in_array($request->sort, $sortable) ? $request->sort : 'created_at';
                $dir = $request->direction === 'asc' ? 'asc' : 'desc';
                return $q->orderBy($sort, $dir);
            })
            ->paginate(20)
            ->withQueryString();

        $total = TrainingSignup::count();

        return view('admin.training_signups.index', compact('signups', 'total'));
    }

    // Export filtered signups to CSV
    public function export(Request $request)
    {
        $signups = TrainingSignup::query()
            ->when($request->plan, fn($q) => $q->where('plan', $request->plan))
            ->when($request->filled('registered_tgc'), fn($q) => $q->where('registered_tgc', (bool) $request->registered_tgc))
            ->when($request->search, fn($q) => $q->search($request->search))
            ->latest()
            ->get();

        $filename = 'training-signups-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($signups) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'id', 'first_name', 'email', 'plan', 'registered_tgc',
                'joined_on', 'program_url',
                'link_last_sent_at', 'mailchimp_synced_at', 'created_at',
            ]);

            foreach ($signups as $signup) {
                fputcsv($handle, [
                    $signup->id,
                    $signup->first_name,
                    $signup->email,
                    $signup->plan,
                    $signup->registered_tgc ? 'yes' : 'no',
                    $signup->started_on->format('Y-m-d'),
                    $signup->program_url,
                    $signup->link_last_sent_at?->toDateTimeString(),
                    $signup->mailchimp_synced_at?->toDateTimeString(),
                    $signup->created_at->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    // Re-send the magic-link email
    public function resendLink(TrainingSignup $trainingSignup)
    {
        Mail::to($trainingSignup->email)->send(new TrainingProgramLink($trainingSignup));
        $trainingSignup->forceFill(['link_last_sent_at' => now()])->save();

        return back()->with('success', "Program link resent to {$trainingSignup->email}.");
    }
}
