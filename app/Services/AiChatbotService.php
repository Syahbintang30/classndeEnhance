<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatbotService
{
    /**
     * Send user message & chat history to Gemini API (or intelligent fallback if key absent).
     */
    public function ask(string $userMessage, array $chatHistory = []): string
    {
        $apiKey = config('services.gemini.api_key') ?: env('GEMINI_API_KEY');

        if (! empty($apiKey)) {
            try {
                $response = $this->callGeminiApi($userMessage, $chatHistory, $apiKey);
                if (! empty($response)) {
                    return $response;
                }
            } catch (\Throwable $e) {
                Log::warning('Gemini API call failed, using intelligent fallback', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Intelligent local fallback if API key is not configured or fails
        return $this->resolveFallbackResponse($userMessage);
    }

    /**
     * Call Google Gemini 1.5 Flash API
     */
    protected function callGeminiApi(string $userMessage, array $chatHistory, string $apiKey): ?string
    {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey;

        $systemInstruction = $this->getSystemPrompt();

        $contents = [];

        // Attach chat history
        foreach ($chatHistory as $msg) {
            $role = ($msg['role'] ?? 'user') === 'user' ? 'user' : 'model';
            $text = trim((string) ($msg['content'] ?? ''));
            if (! empty($text)) {
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => $text]],
                ];
            }
        }

        // Attach current user message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $userMessage]],
        ];

        $payload = [
            'system_instruction' => [
                'parts' => [
                    ['text' => $systemInstruction]
                ]
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 800,
            ]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, $payload);

        if ($response->successful()) {
            $data = $response->json();
            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            if ($reply) {
                return trim($reply);
            }
        } else {
            Log::error('Gemini API HTTP Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        return null;
    }

    /**
     * Comprehensive System Prompt for Engkoy AI (Your Learning Assistant)
     */
    protected function getSystemPrompt(): string
    {
        return <<<PROMPT
You are **Engkoy AI**, your dedicated personal learning assistant for **Guitarclassbynde**, mentored by **Nde**.

CRITICAL DIRECTIVE: ALWAYS RESPOND IN ENGLISH.

Your Persona & Capabilities:
- Name: **Engkoy AI** (*Your Learning Assistant*).
- Tone: Friendly, encouraging, smart, structured, and helpful.
- What you can do for students & visitors:
  1. 📅 **Coaching Schedule Guidance**: Assist students in checking open coaching slots, understanding how 1-on-1 private video sessions work inside our built-in video room, and navigating to booking.
  2. 📊 **Progress & Learning Path Tracking**: Guide students on what to practice next, how to structure daily routines, and how to track course progress.
  3. 🛍️ **Package & Upgrade Advice**: Explain the differences between Beginner (Rp 200k), Intermediate (Rp 250k with Practice Tools & Song Tutorials), and the Special Rp 150k Upgrade deal for Beginner members.
  4. 🎸 **Interactive Practice Coach**: Provide custom 15-minute daily practice plans, chord transition drills, metronome tempo recommendations, and scale guides.
  5. 🎵 **Song Tutorials & Practice Tools Support**: Explain how to use the Tuner, Metronome, Chord Library, Scales, and Fretboard Trainer.
  6. 🎟️ **Referral Rewards**: Explain how students can get 1x FREE Coaching Session by inviting 4 friends.

---
### OFFICIAL PACKAGES & PRICING:
1. **Beginner Package (Rp 200,000 / lifetime)**:
   - Full access to beginner video modules.
   - Includes **1x Free 1-on-1 Private Coaching Session** with Nde (conducted inside our built-in Video Room).

2. **Intermediate Package (Rp 250,000 / lifetime) - RECOMMENDED**:
   - Full access to all intermediate video modules & advanced techniques.
   - Includes **2x Free 1-on-1 Private Coaching Sessions** with Nde (conducted inside our built-in Video Room).
   - Exclusive access to **Practice Tools** (Tuner, Metronome, Chord Library, Scale Finder, Fretboard Trainer).
   - Access to **Song Tutorials & Song Library**.
   - 50% discount on extra coaching ticket re-checkouts.

3. **Special Upgrade Deal (Rp 150,000)**:
   - Existing Beginner members can upgrade to Intermediate for only **Rp 150,000** to instantly unlock Practice Tools & Song Tutorials!

4. **Coaching Ticket Standalone (Rp 100,000 / 1x)**:
   - 1x Interactive 1-on-1 Private Coaching session with Nde inside our built-in Video Room.

---
### RESPONSE GUIDELINES:
- Introduce yourself as **Engkoy AI**, your learning assistant.
- ALWAYS RESPOND IN ENGLISH.
- Keep answers concise, structured, and encouraging.
- Note: Video coaching takes place directly inside our built-in interactive Video Call Room (no external Google Meet required).
PROMPT;
    }

    /**
     * Intelligent local fallback engine in English for Engkoy AI.
     */
    protected function resolveFallbackResponse(string $userMessage): string
    {
        $lower = strtolower($userMessage);

        if (str_contains($lower, 'schedule') || str_contains($lower, 'slot') || str_contains($lower, 'jadwal') || str_contains($lower, 'time') || str_contains($lower, 'available')) {
            return "📅 **Checking Coaching Schedules with Engkoy AI**:\n\n" .
                "- **How to Check Slots**: Log in to your account and navigate to **1-on-1 Coaching** in the top navigation.\n" .
                "- **Booking Process**: Select any open date and time slot that fits your schedule.\n" .
                "- **Session Format**: 1-on-1 live video session with Nde directly inside our platform's built-in Video Call Room.\n\n" .
                "Need a coaching ticket? Intermediate package includes **2x Free Sessions**, or you can get single tickets for **Rp 100,000**!";
        }

        if (str_contains($lower, 'progress') || str_contains($lower, 'track') || str_contains($lower, 'learn') || str_contains($lower, 'next') || str_contains($lower, 'path')) {
            return "📊 **Track Your Progress with Engkoy AI**:\n\n" .
                "1. **Module Completion**: Check your completed topics on your LMS Dashboard.\n" .
                "2. **Daily Practice Metric**: Combine video lessons with 15 minutes of daily practice on the **Practice Tools** (Metronome & Scales).\n" .
                "3. **Skill Milestones**: Master basic chord changes first (C - G - Am - F), then move to strumming patterns and song tutorials!";
        }

        if (str_contains($lower, 'package') || str_contains($lower, 'price') || str_contains($lower, 'cost') || str_contains($lower, 'paket') || str_contains($lower, 'harga')) {
            return "🛍️ **Guitarclassbynde Course Packages**:\n\n" .
                "1. **Intermediate (Rp 250,000 / lifetime) — *BEST VALUE***:\n" .
                "   - Full Access to Intermediate Video Modules\n" .
                "   - **2x 1-on-1 Private Coaching Sessions** with Nde\n" .
                "   - Exclusive Access to **Practice Tools** (Tuner, Metronome, Chords, Scales, Trainer)\n" .
                "   - Access to **Song Tutorials**\n\n" .
                "2. **Beginner (Rp 200,000 / lifetime)**:\n" .
                "   - Full Access to Beginner Video Modules\n" .
                "   - **1x 1-on-1 Private Coaching Session** with Nde\n\n" .
                "⚡ *Already own Beginner?* **Upgrade to Intermediate for only Rp 150,000**!";
        }

        if (str_contains($lower, 'coaching') || str_contains($lower, 'video') || str_contains($lower, 'session')) {
            return "🎟️ **1-on-1 Private Coaching with Nde**:\n\n" .
                "- **Platform Built-in Video Call**: Join directly inside your dashboard when your session starts (no external app downloads needed!).\n" .
                "- **Personalized Feedback**: Nde will analyze your technique, posture, and rhythm in real-time.\n" .
                "- **Flexible Scheduling**: Choose your own date and time.\n\n" .
                "Single tickets available for **Rp 100,000 / session**.";
        }

        if (str_contains($lower, 'practice') || str_contains($lower, 'routine') || str_contains($lower, 'tip') || str_contains($lower, 'chord')) {
            return "🎸 **Engkoy AI 15-Minute Daily Routine**:\n\n" .
                "1. **5 Mins Warm-Up**: Finger stretching & spider drill on frets 1-4.\n" .
                "2. **5 Mins Chord Transitions**: Switch between C - G - Am - F slowly with clean fingertip pressure.\n" .
                "3. **5 Mins Metronome Practice**: Set Metronome in Practice Tools to 70 BPM and practice quarter-note strumming.";
        }

        return "Hi! I am **Engkoy AI**, your personal learning assistant 🎸\n\n" .
            "Here is how I can help you today:\n" .
            "- 📅 **Check Available Coaching Schedules** & Booking Info\n" .
            "- 📊 **Track Progress** & Recommended Next Steps\n" .
            "- 🛍️ **Compare Course Packages** (Beginner vs Intermediate vs Rp 150k Upgrade)\n" .
            "- 🎸 **Custom 15-Min Daily Practice Routines**\n" .
            "- 🎁 **Referral Rewards** Info";
    }
}
