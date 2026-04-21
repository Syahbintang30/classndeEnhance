<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\CoachingTicket;
use Illuminate\Support\Str;
use App\Services\OrderIdGenerator;
use App\Services\CoachingPricingService;

class CoachingCheckoutController extends Controller
{
    public function checkoutForm(Request $request)
    {
        $user = Auth::user();
        if (! $user) return redirect()->route('login');

        // find coaching package configured by admin
        $slug = config('coaching.coaching_package_slug', 'coaching-ticket');
        $package = Package::where('slug', $slug)->first();

        // prepare order summary from selected schedule params
        $schedule = $request->query('schedule'); // expected format: YYYY-MM-DD HH:MM:SS
        $scheduleValue = is_string($schedule) && trim($schedule) !== '' ? trim($schedule) : null;
        $scheduleDisplay = $scheduleValue ?: null;

        $isCoachingMember = CoachingPricingService::isCoachingMember($user);
        $displayPrice = $package ? CoachingPricingService::resolveStandaloneTicketUnitPrice($package, $user) : 0;

        // midtrans client key config
        $midtrans = config('services.midtrans');

        return view('coaching.checkout', compact('package', 'scheduleDisplay', 'scheduleValue', 'midtrans', 'isCoachingMember', 'displayPrice'));
    }

    public function createOrder(Request $request)
    {
        $user = Auth::user();
        if (! $user) return response()->json(['error' => 'Authentication required'], 401);

        $data = $request->validate([
            'schedule' => 'nullable|string',
            'package_id' => 'required|integer|exists:packages,id',
        ]);

        // schedule is optional on ticket purchase flow; validate only if provided
        if (! empty($data['schedule'])) {
            try {
                $dt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data['schedule']);
            } catch (\Throwable $e) {
                return response()->json(['error' => 'schedule must be in format YYYY-MM-DD HH:MM:SS'], 422);
            }

            if ($dt->gt(now()->addMonths(6))) {
                return response()->json(['error' => 'Selected schedule is too far in the future'], 422);
            }
        }

        $package = Package::find($data['package_id']);
        $coachingSlug = config('coaching.coaching_package_slug', 'coaching-ticket');
        if (! $package || ($package->slug ?? null) !== $coachingSlug) {
            return response()->json(['error' => 'Only coaching ticket package is allowed in this checkout flow.'], 422);
        }

        $gross = CoachingPricingService::resolveStandaloneTicketUnitPrice($package, $user);

        // create a local Transaction record (pending) - reuse Transaction model
        $external = OrderIdGenerator::generate('nde');
        $txn = Transaction::create([
            'order_id' => $external,
            'user_id' => $user->id,
            'package_id' => $package->id,
            'method' => 'midtrans',
            'amount' => $gross,
            'status' => 'pending',
            'midtrans_response' => null,
        ]);

        // Return order info for client to call Midtrans Snap (client will call /api/midtrans/create)
        return response()->json(['order_id' => $external, 'gross_amount' => $gross, 'package_id' => $package->id]);
    }

    public function finalizeOrder(Request $request)
    {
        $user = Auth::user();
        if (! $user) return response()->json(['error' => 'Authentication required'], 401);

        $data = $request->validate([
            'order_id' => 'required|string',
            'transaction_status' => 'nullable|string',
            'result' => 'nullable|array',
        ]);

        $orderId = (string) $data['order_id'];
        $statusRaw = strtolower((string) ($data['transaction_status'] ?? ($data['result']['transaction_status'] ?? '')));

        $txn = Transaction::where('order_id', $orderId)
            ->where('user_id', $user->id)
            ->latest('id')
            ->first();

        if (! $txn) {
            return response()->json(['error' => 'order_not_found'], 404);
        }

        $successfulStatuses = ['settlement', 'capture', 'success', 'paid', 'settled', 'completed'];
        $isSettled = in_array($statusRaw, $successfulStatuses, true);
        $txn->status = $isSettled ? 'settlement' : ($statusRaw ?: ($txn->status ?? 'pending'));

        $existingResponse = $txn->midtrans_response;
        if (is_string($existingResponse)) {
            $existingResponse = json_decode($existingResponse, true) ?: [];
        }
        if (! is_array($existingResponse)) {
            $existingResponse = [];
        }
        $txn->midtrans_response = array_merge($existingResponse, ['finalize_payload' => $data]);
        $txn->save();

        $granted = false;
        if ($isSettled) {
            $coachingSlug = config('coaching.coaching_package_slug', 'coaching-ticket');
            $pkg = $txn->package_id ? Package::find($txn->package_id) : null;
            if ($pkg && ($pkg->slug ?? null) === $coachingSlug) {
                $source = 'midtrans:' . $orderId;
                $exists = CoachingTicket::where('user_id', $user->id)
                    ->where('source', $source)
                    ->exists();

                if (! $exists) {
                    CoachingTicket::create([
                        'user_id' => $user->id,
                        'is_used' => false,
                        'source' => $source,
                    ]);
                    $granted = true;
                }
            }
        }

        $availableTickets = CoachingTicket::where('user_id', $user->id)
            ->where('is_used', false)
            ->count();

        return response()->json([
            'ok' => true,
            'status' => $txn->status,
            'granted' => $granted,
            'available_tickets' => $availableTickets,
        ]);
    }
}
