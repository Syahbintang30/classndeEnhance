<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Topic;

class MediaStreamController extends Controller
{
    public function topicStream($topic)
    {
        // Endpoint ini jadi jembatan antara player di frontend dan sumber video di Bunny/CDN.
        // Avoid implicit model binding 404s: resolve manually and always return JSON 200.
        $topicModel = Topic::find($topic);
        if (! $topicModel) {
            return response()->json(['url' => null]);
        }

        if ($topicModel->bunny_guid) {
            // If bunny_guid accidentally contains a full URL, return it directly.
            if (preg_match('#^https?://#i', $topicModel->bunny_guid)) {
                return response()->json(['url' => $topicModel->bunny_guid]);
            }

            $signed = BunnyController::signUrl($topicModel->bunny_guid, 300);
            if ($signed) {
                return response()->json(['url' => $signed]);
            }

            return response()->json(['url' => BunnyController::cdnUrl($topicModel->bunny_guid)]);
        }

        $path = $topicModel->video_url ?? null;
        if (! $path) {
            return response()->json(['url' => null]);
        }

        if (preg_match('#^https?://#i', $path)) {
            return response()->json(['url' => $path]);
        }

        $signed = BunnyController::signUrl($path, 300);
        if ($signed) {
            return response()->json(['url' => $signed]);
        }

        return response()->json(['url' => BunnyController::cdnUrl($path)]);
    }

    public function promoStream()
    {
        // Promo landing page juga ambil stream dari setting supaya bisa diganti tanpa deploy.
        $guid = Setting::get('nde.promo_bunny_guid', null);
        if (! $guid) {
            return response()->json(['url' => null]);
        }

        try {
            $signed = BunnyController::signStreamUrl($guid, 300);
            if ($signed) {
                return response()->json(['url' => $signed]);
            }

            return response()->json(['url' => BunnyController::cdnUrl($guid)]);
        } catch (\Throwable $e) {
            return response()->json(['url' => BunnyController::cdnUrl($guid)]);
        }
    }
}
