<?php

namespace App\Http\Controllers;

use App\Models\FaqItem;
use App\Models\Package;
use App\Models\Setting;

class LandingController extends Controller
{
    public function index()
    {
        // Landing page / compro butuh data marketing yang bisa diubah dari setting tanpa edit view.
        $promoGuid = Setting::get('nde.promo_bunny_guid', null);
        $promoTitle = Setting::get('nde.promo_title', null);
        $promoThumbnail = Setting::get('nde.promo_thumbnail', null);

        // Paket dipakai untuk section pricing, sedangkan FAQ dipakai untuk section tanya-jawab.
        $packages = Package::orderBy('price')->get();
        $faqItems = FaqItem::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

        // Semua data ini dikirim ke view compro supaya halaman depan tetap dinamis.
        return view('compro', [
            'promo_bunny_guid' => $promoGuid,
            'promo_title' => $promoTitle,
            'promo_thumbnail_url' => $promoThumbnail,
            'packages' => $packages,
            'faq_items' => $faqItems,
        ]);
    }
}
