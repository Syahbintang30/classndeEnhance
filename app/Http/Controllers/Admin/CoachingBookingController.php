<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoachingBooking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\TwilioService;

class CoachingBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = CoachingBooking::with(['user','coach','ticket']);

        $search = trim((string) $request->input('q', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                })
                ->orWhereHas('ticket', function ($ticketQuery) use ($search) {
                    $ticketQuery->where('source', 'like', '%' . $search . '%');
                })
                ->orWhere('notes', 'like', '%' . $search . '%')
                ->orWhere('admin_note', 'like', '%' . $search . '%')
                ->orWhere('twilio_room_sid', 'like', '%' . $search . '%');
            });
        }

        $status = strtolower((string) $request->input('status', ''));
        if ($status === 'approved') {
            $status = 'accepted';
        }
        if (in_array($status, ['pending', 'accepted', 'rejected'], true)) {
            $query->where('status', $status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('booking_time', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('booking_time', '<=', $request->input('date_to'));
        }

        $bookings = $query
            ->orderByDesc('booking_time')
            ->orderByDesc('created_at')
            ->paginate(50)
            ->withQueryString();

        // Compute aggregated "Taken" counts per slot for the dates shown on this page to avoid N+1 queries.
        // Use safe database-agnostic approach instead of raw SQL expressions
        $dates = $bookings->getCollection()->pluck('booking_time')
            ->map(fn($t) => Carbon::parse($t)->toDateString())
            ->unique()->values()->all();

        $slotCounts = [];
        if (!empty($dates)) {
            // Use safer approach with whereDate for each date
            $bookingsQuery = CoachingBooking::query();
            foreach ($dates as $date) {
                $bookingsQuery->orWhereDate('booking_time', $date);
            }
            $bookingsForCounts = $bookingsQuery->get();
            
            foreach ($bookingsForCounts as $booking) {
                // Only count active bookings towards "Taken": pending or accepted
                if (! in_array(strtolower($booking->status), ['pending','accepted'])) {
                    continue;
                }
                $carbon = Carbon::parse($booking->booking_time);
                $day = $carbon->toDateString();
                $time = $carbon->format('H:i');
                $key = $day . ' ' . $time;
                $slotCounts[$key] = ($slotCounts[$key] ?? 0) + 1;
            }
        }

        return view('admin.coaching.bookings', compact('bookings', 'slotCounts'));
    }

    /**
     * Create Twilio room for the booking on-demand and persist sid.
     */
    public function createRoom(CoachingBooking $booking, TwilioService $twilio)
    {
        if (! $twilio->isConfigured()) {
            return redirect()->back()->with('error', 'Twilio not configured');
        }

        $roomName = 'coaching-' . $booking->id;
        try {
            $room = $twilio->createOrFetchRoom($roomName);
            if ($room && isset($room->sid)) {
                $booking->twilio_room_sid = $room->sid;
                $booking->save();
                return redirect()->back()->with('success', 'Twilio room created');
            }
            return redirect()->back()->with('error', 'Failed to create Twilio room');
        } catch (\Throwable $e) {
            logger()->error('Admin createRoom failed: ' . $e->getMessage(), ['booking' => $booking->id]);
            return redirect()->back()->with('error', 'Twilio API error: ' . $e->getMessage());
        }
    }

    /**
     * End (complete) a Twilio room so participants are disconnected and room closed.
     */
    public function endRoom(CoachingBooking $booking, TwilioService $twilio)
    {
        if (! $twilio->isConfigured()) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'error' => 'Twilio not configured'], 422);
            }
            return redirect()->back()->with('error', 'Twilio not configured');
        }

        try {
            $client = $twilio->getClient();
            if (! $client) throw new \RuntimeException('Twilio client not available');

            $sid = $booking->twilio_room_sid;
            if (! $sid) {
                $roomName = 'coaching-' . $booking->id;
                $existing = $client->video->v1->rooms->read(['uniqueName' => $roomName], 1);
                if (count($existing) > 0) {
                    $sid = $existing[0]->sid;
                    $booking->twilio_room_sid = $sid;
                    $booking->save();
                }
            }

            if (! $sid) {
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json(['success' => false, 'error' => 'No active Twilio room attached to booking'], 404);
                }
                return redirect()->back()->with('error', 'No active Twilio room attached to booking');
            }

            $roomNotFound = false;
            try {
                // This SDK version expects the room status string directly, not an attributes array.
                $client->video->v1->rooms($sid)->update('completed');
            } catch (\Twilio\Exceptions\RestException $e) {
                if ((int) $e->getStatusCode() === 404) {
                    $roomNotFound = true;
                    logger()->warning('Twilio room not found on endRoom; continuing', ['booking' => $booking->id, 'sid' => $sid]);
                } else {
                    throw $e;
                }
            }

            $warrantyMinutes = request()->input('warranty_minutes');
            if ($warrantyMinutes !== null && $warrantyMinutes !== '') {
                $warrantyMinutes = (int) $warrantyMinutes;
                if ($warrantyMinutes < 0) {
                    $warrantyMinutes = 0;
                }
                app(\App\Services\CoachingWarrantyService::class)
                    ->issueFromBooking($booking, $warrantyMinutes, 'admin_end');
            }

            // Persist ended status so users cannot rejoin after admin ends the room.
            $booking->status = 'ended';
            $booking->save();

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'room_sid' => $sid,
                    'warning' => $roomNotFound ? 'Twilio room not found; warranty still issued.' : null,
                ]);
            }
            $flashKey = $roomNotFound ? 'warning' : 'success';
            $flashMessage = $roomNotFound ? 'Room not found; warranty still issued.' : 'Room ended';
            return redirect()->back()->with($flashKey, $flashMessage);
        } catch (\Throwable $e) {
            logger()->error('Failed to end Twilio room: ' . $e->getMessage(), ['booking' => $booking->id]);
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'error' => 'Failed to end room: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Failed to end room: ' . $e->getMessage());
        }
    }

    public function accept(CoachingBooking $booking)
    {
        $booking->status = 'accepted';
        $booking->save();
        // create Twilio room upon admin acceptance so only accepted bookings get rooms
        try {
            if (app()->bound(\App\Services\TwilioService::class)) {
                $twilio = app(\App\Services\TwilioService::class);
                if ($twilio->isConfigured() && empty($booking->twilio_room_sid)) {
                    // Standardize room uniqueName across the app to avoid mismatches
                    $roomName = 'coaching-' . $booking->id;
                    $room = $twilio->createOrFetchRoom($roomName);
                    if ($room && isset($room->sid)) {
                        $booking->twilio_room_sid = $room->sid;
                        $booking->save();
                    }
                }
            }
        } catch (\Throwable $e) {
            logger()->warning('Failed to create Twilio room on accept: ' . $e->getMessage(), ['booking' => $booking->id]);
        }
        try {
            if ($booking->user) $booking->user->notify(new \App\Notifications\BookingStatusChanged($booking, 'accepted'));
        } catch (\Exception $e) { logger()->warning('Failed to notify user about acceptance: ' . $e->getMessage()); }
        return redirect()->back()->with('success', 'Booking accepted');
    }

    public function reject(CoachingBooking $booking, Request $request)
    {
        // release ticket back to user
        if ($booking->ticket) {
            $booking->ticket->is_used = false;
            $booking->ticket->save();
        }

        // set status to rejected and save admin note (reason)
        $booking->status = 'rejected';
        $booking->admin_note = $request->input('reason') ?? 'Admin not available, please reschedule';
        $booking->save();

        // Invalidate cached availability for this date so the freed slot is visible immediately
        try {
            $dt = \Carbon\Carbon::parse($booking->booking_time);
            $key = 'coaching_avail_range:' . $dt->toDateString() . ':' . $dt->toDateString();
            \Illuminate\Support\Facades\Cache::forget($key);
        } catch (\Throwable $e) { /* ignore cache errors */ }

        // notify user about rejection and reason
        try {
            if ($booking->user) $booking->user->notify(new \App\Notifications\BookingStatusChanged($booking, 'rejected'));
        } catch (\Exception $e) { logger()->warning('Failed to notify user about rejection: ' . $e->getMessage()); }

        return redirect()->back()->with('success', 'Booking marked rejected and ticket returned to user');
    }
}
