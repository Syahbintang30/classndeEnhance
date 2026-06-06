<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;

class LookupController extends Controller
{
    public function referralValidate(Request $request)
    {
        // Endpoint ini dipakai checkout untuk cek apakah kode referral valid dan berapa diskonnya.
        $code = $request->input('code');

        if (! $code) {
            return response()->json(['valid' => false, 'message' => 'No code provided'], 200);
        }

        $user = User::where('referral_code', $code)->first();

        if (! $user) {
            return response()->json(['valid' => false, 'message' => 'Code not found'], 200);
        }

        $dbVal = Setting::get('referral.discount_percent', null);
        $discount = $dbVal !== null ? (int) $dbVal : (int) config('referral.discount_percent', 2);

        return response()->json([
            'valid' => true,
            'discount_percent' => $discount,
            'referrer' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
        ]);
    }

    public function voucherValidate(Request $request)
    {
        // Endpoint ini dipakai checkout untuk validasi kode voucher sebelum harga dihitung.
        $code = trim($request->input('code', ''));

        if (! $code) {
            return response()->json(['valid' => false, 'message' => 'No code provided']);
        }

        $voucher = Voucher::where('code', $code)->first();

        if (! $voucher) {
            return response()->json(['valid' => false, 'message' => 'Voucher not found']);
        }

        if (! $voucher->isValid()) {
            return response()->json(['valid' => false, 'message' => 'Voucher is not valid']);
        }

        return response()->json([
            'valid' => true,
            'discount_percent' => $voucher->discount_percent,
            'voucher_id' => $voucher->id,
        ]);
    }
}
