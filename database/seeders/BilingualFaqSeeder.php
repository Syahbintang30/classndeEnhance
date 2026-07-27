<?php

namespace Database\Seeders;

use App\Models\FaqItem;
use Illuminate\Database\Seeder;

class BilingualFaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question_id' => 'Apa itu Guitarclassbynde?',
                'answer_id' => 'Guitarclassbynde adalah platform belajar gitar online elit terstruktur yang didirikan oleh Nde. Menggabungkan kelas video bertahap, tools latihan interaktif (Tuner, Metronom, Chord & Scale visualizer), tutorial lagu, dan sesi coaching live 1-on-1.',
                'question_en' => 'What is Guitarclassbynde?',
                'answer_en' => 'Guitarclassbynde is an elite, structured online guitar learning platform founded by Nde. It combines step-by-step video courses, interactive practice tools (Tuner, Metronome, Chord & Scale visualizers), song library tutorials, and live 1-on-1 coaching sessions.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question_id' => 'Berapa lama saya mendapatkan akses kelas yang sudah dibeli?',
                'answer_id' => 'Setelah mendaftar, kamu mendapatkan akses seumur hidup (lifetime access) ke seluruh modul kelas, materi, dan tools latihan yang ada pada paketmu. Kamu bisa belajar kapan saja sesuai kecepatanmu sendiri.',
                'question_en' => 'How long do I get access to my purchased courses?',
                'answer_en' => 'Once enrolled, you get lifetime access to all course modules, materials, and practice suite tools included in your package. You can learn at your own pace whenever you want.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question_id' => 'Apakah saya butuh pengalaman main gitar sebelumnya?',
                'answer_id' => 'Tidak perlu pengalaman sama sekali! Kelas kami dimulai dari dasar paling awal (cara memegang gitar, chord open, strumming dasar) hingga teknik solo, picking cepat, dan teori fretboard.',
                'question_en' => 'Do I need prior guitar experience to get started?',
                'answer_en' => 'No experience needed! Our courses start from absolute beginner fundamentals (holding the guitar, open chords, basic strumming) all the way up to advanced soloing, speed picking, and fretboard theory.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'question_id' => 'Bagaimana cara kerja Sesi Coaching 1-on-1?',
                'answer_id' => 'Sesi coaching dilakukan secara live langsung di dalam website kami via Video Call Room interaktif! Kamu tinggal pilih jadwal yang tersedia di dashboard, dan saat sesi dimulai, klik "Masuk Sesi Video" untuk bertatap muka langsung dengan Nde.',
                'question_en' => 'How do 1-on-1 Coaching Sessions work?',
                'answer_en' => 'Coaching sessions are conducted live directly inside our platform’s built-in interactive Video Call Room! Pick an open time slot in your dashboard, and when your session starts, click "Join Video Session" to meet live with Nde.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'question_id' => 'Metode pembayaran apa saja yang didukung?',
                'answer_id' => 'Kami menerima pembayaran otomatis serba instan via Midtrans meliputi Transfer Bank (Virtual Account BCA, Mandiri, BNI, BRI), QRIS, GoPay, ShopeePay, dan Kartu Kredit.',
                'question_en' => 'What payment channels are supported?',
                'answer_en' => 'We accept instant, automated payments via Midtrans including Bank Transfer (Virtual Accounts for BCA, Mandiri, BNI, BRI), QRIS, GoPay, ShopeePay, and major Credit Cards.',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $f) {
            FaqItem::updateOrCreate(
                ['question_id' => $f['question_id']],
                [
                    'question' => $f['question_id'],
                    'answer' => $f['answer_id'],
                    'question_id' => $f['question_id'],
                    'answer_id' => $f['answer_id'],
                    'question_en' => $f['question_en'],
                    'answer_en' => $f['answer_en'],
                    'sort_order' => $f['sort_order'],
                    'is_active' => $f['is_active'],
                ]
            );
        }

        // Also sync any other FaqItem that has null question_id / question_en
        $existingItems = FaqItem::all();
        foreach ($existingItems as $item) {
            if (empty($item->question_id)) {
                $item->question_id = $item->question;
            }
            if (empty($item->answer_id)) {
                $item->answer_id = $item->answer;
            }
            if (empty($item->question_en)) {
                $item->question_en = $item->question;
            }
            if (empty($item->answer_en)) {
                $item->answer_en = $item->answer;
            }
            $item->save();
        }
    }
}
