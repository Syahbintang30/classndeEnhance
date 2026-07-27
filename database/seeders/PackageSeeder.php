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
            'image' => 'pictures/beginner.jpg',
            'benefits' => implode("\n", [
                'Akses Seumur Hidup Modul Pemula (Video HD Step-by-Step)',
                'Pustaka Teori Musik Dasar, Diagram Kunci & Finger Placement',
                'Practice Suite Interaktif (Guitar Tuner, Metronom, & Chord Finder)',
                '1x Tiket Gratis Sesi Coaching Live 1-on-1 Privat (60 Menit)',
                'Garansi Akses Lisensi Digital 100% Instan via Midtrans',
            ]),
        ]);

        Package::updateOrCreate(['slug' => 'intermediate'], [
            'name' => 'Intermediate',
            'price' => 250000,
            'description' => 'Akses seluruh modul pemula & lanjutan, pustaka Song TAB lengkap, dan prioritas support.',
            'image' => 'pictures/intermediate.jpg',
            'benefits' => implode("\n", [
                'Akses Seumur Hidup Modul Pemula & Lanjutan (Full Video HD)',
                'Pustaka Tutorial Lagu Populer & Interactive Song TAB',
                'Practice Suite Lengkap (Tuner, Metronom, Chords, & Scale Finder)',
                '1x Tiket Gratis Sesi Coaching Live 1-on-1 Privat (60 Menit)',
                'Dukungan Komunitas & Prioritas Tanya Jawab Langsung',
                'Garansi Akses Lisensi Digital 100% Instan via Midtrans',
            ]),
        ]);

        Package::updateOrCreate(['slug' => 'coaching-ticket'], [
            'name' => 'Coaching Ticket',
            'price' => 100000,
            'description' => 'Tiket Sesi Coaching Live 1-on-1 privat langsung bersama Nde.',
            'image' => 'pictures/coaching-ticket.jpg',
            'benefits' => implode("\n", [
                '1x Sesi Video Call Live 1-on-1 Privat dengan Nde (60 Menit)',
                'Review Teknik Bermain & Koreksi Posisi Jari Real-Time',
                'Penyusunan Rencana Latihan Kustom Sesuai Target Murid',
                'Fasilitas Reschedule Sesi 1x (Batas H-1 Jam Sesi)',
                'Ruang Video Call HD Langsung Tanpa Instalasi Software',
            ]),
        ]);
    }
}

