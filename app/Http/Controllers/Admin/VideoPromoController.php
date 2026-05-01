<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class VideoPromoController extends Controller
{
    public function edit()
    {
        $guid = Setting::get('nde.promo_bunny_guid', null);
        $title = Setting::get('nde.promo_title', null);
        $thumbnail = Setting::get('nde.promo_thumbnail', null);
        return view('admin.videopromo', compact('guid', 'title', 'thumbnail'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'promo_bunny_guid' => ['nullable', 'string'],
            'promo_title' => ['nullable', 'string', 'max:255'],
            'promo_thumbnail' => ['nullable', 'url', 'max:500'],
        ]);
        Setting::set('nde.promo_bunny_guid', $data['promo_bunny_guid'] ?? '');
        Setting::set('nde.promo_title', $data['promo_title'] ?? '');
        Setting::set('nde.promo_thumbnail', $data['promo_thumbnail'] ?? '');
        return redirect()->route('admin.videopromo')->with('success', 'Video promo updated.');
    }
    }
