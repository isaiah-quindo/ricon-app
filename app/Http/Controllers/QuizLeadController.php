<?php

namespace App\Http\Controllers;

use App\Models\QuizLead;
use App\Services\MailchimpService;
use Illuminate\Http\Request;

class QuizLeadController extends Controller
{
    public function store(Request $request, MailchimpService $mailchimp)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'email'      => 'required|email|max:255',
            'score'      => 'required|integer|min:5|max:15',
        ]);

        $score = (int) $validated['score'];

        // Same thresholds as the on-page quiz UI
        $result = $score >= 13 ? 'a' : ($score >= 8 ? 'b' : 'c');

        // One row per email — a retake just refreshes the latest answers
        $lead = QuizLead::updateOrCreate(
            ['email' => strtolower(trim($validated['email']))],
            [
                'first_name' => $validated['first_name'],
                'source'     => QuizLead::SOURCE_21K_QUIZ,
                'score'      => $score,
                'result'     => $result,
            ]
        );

        $mailchimp->subscribeQuizLead($lead);

        return response()->json(['status' => 'ok', 'result' => $result]);
    }
}
