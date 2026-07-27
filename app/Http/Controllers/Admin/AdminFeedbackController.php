<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoachingBooking;

class AdminFeedbackController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
    $items = CoachingBooking::with('user')->whereNotNull('notes')->orderByDesc('updated_at')->paginate(30);
    return view('admin.coaching_feedbacks.index', compact('items'));
    }

    public function update(Request $request, \App\Models\CoachingBooking $booking)
    {
        $this->authorize('admin');
        $adminActionMaxLength = config('constants.business_logic.admin_action_max_length', 5000);
        
        $data = $request->validate([
            'admin_action' => "nullable|string|max:{$adminActionMaxLength}",
            'review_video_url' => "nullable|string|max:255",
            'review_title' => "nullable|string|max:255",
            'review_tag' => "nullable|string|max:100",
        ]);

        try {
            if (array_key_exists('admin_action', $data)) {
                $booking->admin_note = $data['admin_action'];
            }
            if (array_key_exists('review_video_url', $data)) {
                $booking->review_video_url = $data['review_video_url'];
            }
            if (array_key_exists('review_title', $data)) {
                $booking->review_title = $data['review_title'];
            }
            if (array_key_exists('review_tag', $data)) {
                $booking->review_tag = $data['review_tag'];
            }
            $booking->save();
        } catch (\Throwable $e) {
            logger()->warning('Failed to save admin action to booking', ['err' => $e->getMessage()]);
        }
        return redirect()->back()->with('success', 'Mentor Video Review saved successfully!');
    }
}
