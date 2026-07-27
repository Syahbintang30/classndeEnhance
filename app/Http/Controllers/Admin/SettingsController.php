<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Package;

class SettingsController extends Controller
{
    public function index()
    {
        // Get all current settings
        $settings = Setting::all()->keyBy('key');
        $packages = Package::orderBy('name')->get();
        
        return view('admin.settings.index', compact('settings', 'packages'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_number' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:100',
            'referral.discount_percent' => 'nullable|numeric|min:0|max:100',
            'coaching.max_booking_days_ahead' => 'nullable|integer|min:1|max:365',
            'coaching.session_duration_minutes' => 'nullable|integer|min:15|max:240',
            'coaching.buffer_minutes_before' => 'nullable|integer|min:0|max:60',
            'coaching.buffer_minutes_after' => 'nullable|integer|min:0|max:120',
            'notifications.admin_booking_enabled' => 'nullable|boolean',
            'notifications.user_booking_status_enabled' => 'nullable|boolean',
        ]);

        // Convert boolean values to string for database storage
        foreach (['notifications.admin_booking_enabled', 'notifications.user_booking_status_enabled'] as $boolKey) {
            $validated[$boolKey] = $request->has($boolKey) ? 'true' : 'false';
        }

        // Update settings
        foreach ($validated as $key => $value) {
            if ($value !== null) {
                Setting::set($key, (string) $value);
            }
        }

        return redirect()->back()->with('success', 'System settings updated successfully.');
    }

    public function reset()
    {
        // Reset to default useful values
        Setting::set('whatsapp_number', '6281234567890');
        Setting::set('contact_email', 'support@guitarclassbynde.com');
        Setting::set('referral.discount_percent', '10');
        Setting::set('coaching.max_booking_days_ahead', '30');
        Setting::set('coaching.session_duration_minutes', '60');
        Setting::set('coaching.buffer_minutes_before', '10');
        Setting::set('coaching.buffer_minutes_after', '60');
        Setting::set('notifications.admin_booking_enabled', 'true');
        Setting::set('notifications.user_booking_status_enabled', 'true');

        return redirect()->back()->with('success', 'Settings reset to default values.');
    }
}