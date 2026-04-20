@extends('layouts.app')

@section('title', 'Akses LMS Belum Aktif')

@section('content')
<div style="min-height: calc(100vh - 120px); display:flex; align-items:center; justify-content:center; padding:40px 16px; background:#050505;">
    <div style="max-width:760px; width:100%; border:1px solid rgba(255,255,255,0.12); border-radius:18px; background:linear-gradient(180deg, rgba(17,17,17,0.98), rgba(10,10,10,0.98)); padding:28px; color:#f5f5f5; box-shadow:0 20px 40px rgba(0,0,0,0.45);">
        <div style="display:inline-flex; align-items:center; gap:8px; padding:6px 12px; border-radius:999px; border:1px solid rgba(255,255,255,0.18); background:rgba(255,255,255,0.06); font-size:12px; font-weight:700; letter-spacing:0.04em; text-transform:uppercase; margin-bottom:14px;">
            Status Akses
        </div>

        <h1 style="margin:0 0 10px; font-size:30px; line-height:1.2; font-weight:800; letter-spacing:-0.02em;">Akses LMS kamu belum aktif</h1>

        <p style="margin:0 0 20px; color:#b5b5b5; line-height:1.65; font-size:15px;">
            Akun sudah login, tapi sistem belum menemukan entitlement kelas yang valid.
            Biasanya ini karena pembayaran masih menunggu konfirmasi atau paket belum di-assign ke akun.
        </p>

        <div style="display:grid; gap:10px; margin: 0 0 22px;">
            <div style="padding:10px 12px; border-radius:10px; border:1px solid rgba(255,255,255,0.12); background:rgba(255,255,255,0.03); color:#d4d4d4; font-size:14px;">1. Jika baru bayar, tunggu 1-2 menit lalu cek ulang akses.</div>
            <div style="padding:10px 12px; border-radius:10px; border:1px solid rgba(255,255,255,0.12); background:rgba(255,255,255,0.03); color:#d4d4d4; font-size:14px;">2. Jika tetap belum aktif, hubungi admin untuk validasi paket akun kamu.</div>
        </div>

        <div style="display:flex; flex-wrap:wrap; gap:10px;">
            <a href="{{ route('lms.entry') }}" style="display:inline-flex; align-items:center; justify-content:center; padding:11px 16px; border-radius:10px; background:#ffffff; color:#050505; text-decoration:none; font-weight:700; border:1px solid rgba(255,255,255,0.25);">Cek Lagi Akses</a>
            <a href="{{ route('compro') }}" style="display:inline-flex; align-items:center; justify-content:center; padding:11px 16px; border-radius:10px; background:transparent; color:#f5f5f5; text-decoration:none; font-weight:700; border:1px solid rgba(255,255,255,0.22);">Kembali ke Landing</a>
            <a href="https://wa.me/" target="_blank" rel="noopener" style="display:inline-flex; align-items:center; justify-content:center; padding:11px 16px; border-radius:10px; background:transparent; color:#f5f5f5; text-decoration:none; font-weight:700; border:1px solid rgba(255,255,255,0.22);">Hubungi Admin</a>
        </div>
    </div>
</div>
@endsection
