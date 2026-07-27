<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run()
    {
        Package::updateOrCreate(['slug' => 'beginner'], [
            'name' => 'Beginner',
            'price' => 200000,
            'description' => 'Akses lengkap modul dasar pemula gitar dari nol hingga mahir bermain lagu.',
            'benefits' => implode("\n", [
                'Akses Seumur Hidup Modul Pemula',
                'Pustaka Kunci & Teori Musik Dasar',
                'Practice Suite (Tuner, Metronom, Chords)',
                '1x Gratis Tiket Sesi Coaching Live 1-on-1',
            ]),
        ]);

        Package::updateOrCreate(['slug' => 'intermediate'], [
            'name' => 'Intermediate',
            'price' => 250000,
            'description' => 'Akses seluruh modul pemula & lanjutan, pustaka Song TAB lengkap, dan prioritas support.',
            'benefits' => implode("\n", [
                'Akses Seumur Hidup Modul Pemula & Lanjutan',
                'Pustaka Tutorial Lagu & Full Song TAB',
                'Practice Suite Lengkap & Latihan Interaktif',
                '1x Gratis Tiket Sesi Coaching Live 1-on-1',
                'Dukungan Komunitas & Prioritas Tanya Jawab',
            ]),
        ]);

        Package::updateOrCreate(['slug' => 'coaching-ticket'], [
            'name' => 'Coaching Ticket',
            'price' => 100000,
            'description' => 'Tiket Sesi Coaching Live 1-on-1 privat langsung bersama Nde.',
            'benefits' => implode("\n", [
                '1x Sesi Video Call Live 1-on-1 dengan Nde (60 Menit)',
                'Review Teknik & Koreksi Posisi Jari',
                'Rencana Latihan Kustom Sesuai Target',
                'Bisa Reschedule Sesi 1x',
            ]),
        ]);
    }
}

