<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoachingWarrantyTicket;
use Illuminate\Http\Request;

class CoachingWarrantyTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = CoachingWarrantyTicket::query()
            ->with(['user', 'booking', 'ticket'])
            ->latest();

        if ($request->filled('q')) {
            $q = trim((string) $request->input('q'));
            $query->where(function ($inner) use ($q) {
                $inner->where('id', $q)
                    ->orWhere('booking_id', $q)
                    ->orWhere('ticket_id', $q)
                    ->orWhereHas('user', function ($user) use ($q) {
                        $user->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $tickets = $query->paginate(30)->appends($request->query());

        return view('admin.coaching.warranty_tickets', [
            'tickets' => $tickets,
        ]);
    }
}
