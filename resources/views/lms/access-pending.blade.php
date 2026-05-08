@extends('layouts.app')

@section('title', 'Payment Required')

@section('content')
<div style="min-height: calc(100vh - 120px); display:flex; align-items:center; justify-content:center; padding:40px 16px; background:var(--lms-bg);">
    <div style="max-width:760px; width:100%; border:1px solid var(--lms-border); border-radius:18px; background:var(--lms-card); padding:28px; color:var(--lms-text); box-shadow:0 20px 40px rgba(0,0,0,0.45);">
        <div style="display:inline-flex; align-items:center; gap:8px; padding:6px 12px; border-radius:999px; border:1px solid var(--lms-pill-border); background:var(--lms-pill-bg); font-size:12px; font-weight:700; letter-spacing:0.04em; text-transform:uppercase; margin-bottom:14px;">
            Class Access
        </div>

        <h1 style="margin:0 0 10px; font-size:30px; line-height:1.2; font-weight:800; letter-spacing:-0.02em;">You have not paid for class access yet</h1>

        <p style="margin:0 0 20px; color:var(--lms-muted); line-height:1.65; font-size:15px;">
            Your account is already active, but there is no valid learning package attached yet. To access the class materials,
            you need to complete the payment first. Once the payment is completed, class access will be activated automatically.
        </p>

        <div style="display:grid; gap:10px; margin: 0 0 22px;">
            <div style="padding:10px 12px; border-radius:10px; border:1px solid var(--lms-border); background:rgba(15, 23, 42, 0.04); color:var(--lms-text); font-size:14px;">✓ Choose the learning package that fits your needs</div>
            <div style="padding:10px 12px; border-radius:10px; border:1px solid var(--lms-border); background:rgba(15, 23, 42, 0.04); color:var(--lms-text); font-size:14px;">✓ Complete the payment process</div>
            <div style="padding:10px 12px; border-radius:10px; border:1px solid var(--lms-border); background:rgba(15, 23, 42, 0.04); color:var(--lms-text); font-size:14px;">✓ Your class access will be activated automatically</div>
        </div>

        <div style="display:flex; flex-wrap:wrap; gap:10px;">
            <a href="{{ route('registerclass') }}" style="display:inline-flex; align-items:center; justify-content:center; padding:12px 20px; border-radius:10px; background:var(--lms-btn-bg); color:var(--lms-btn-text); text-decoration:none; font-weight:700; border:none;">Choose Package & Pay</a>
            <a href="{{ route('compro') }}" style="display:inline-flex; align-items:center; justify-content:center; padding:12px 20px; border-radius:10px; background:transparent; color:var(--lms-text); text-decoration:none; font-weight:700; border:1px solid var(--lms-border);">Back to Landing Page</a>
        </div>
    </div>
</div>
@endsection
