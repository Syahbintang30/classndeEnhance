@extends('layouts.app')

@section('title', 'Song Tutorial')

@section('content')
<style>
    .song-index-wrapper {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 18px;
        color: #ffffff;
    }

    :root[data-theme="light"] .song-index-wrapper {
        color: #0f172a;
    }

    .song-index-wrapper h1 {
        font-size: 22px;
        margin: 0 0 20px;
        color: #ffffff;
    }

    :root[data-theme="light"] .song-index-wrapper h1 {
        color: #0f172a;
    }

    .song-index-wrapper > p {
        color: rgba(255,255,255,0.75);
        margin-bottom: 18px;
    }

    :root[data-theme="light"] .song-index-wrapper > p {
        color: #475569;
    }

    /* Topic grid */
    .topic-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 18px;
    }

    .topic-card {
        background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(0,0,0,0.25));
        border-radius: 12px;
        overflow: hidden;
        padding: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.6);
        border: 1px solid rgba(255,255,255,0.03);
        transition: transform .28s cubic-bezier(.2,.9,.2,1), box-shadow .28s, border-color .2s;
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .topic-card:hover, .topic-card:focus {
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 18px 50px rgba(0,0,0,0.7);
        border-color: rgba(100,220,255,0.12);
    }

    :root[data-theme="light"] .topic-card {
        background: #ffffff;
        border-color: rgba(15,23,42,0.08);
        box-shadow: 0 4px 20px rgba(15,23,42,0.06);
    }

    :root[data-theme="light"] .topic-card:hover,
    :root[data-theme="light"] .topic-card:focus {
        border-color: rgba(15,23,42,0.16);
        box-shadow: 0 12px 36px rgba(15,23,42,0.1);
        transform: translateY(-6px) scale(1.01);
    }

    .topic-thumb {
        background: #111;
        height: 140px;
        border-radius: 10px;
        overflow: hidden;
        display: block;
        position: relative;
    }

    .topic-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .4s, filter .3s;
    }

    .topic-card:hover .topic-thumb img {
        transform: scale(1.03);
        filter: brightness(.95) contrast(1.02);
    }

    :root[data-theme="light"] .topic-thumb {
        background: #e2e8f0;
    }

    .topic-title {
        padding-top: 12px;
        color: rgba(255,255,255,0.94);
        font-weight: 700;
        font-size: 15px;
    }

    :root[data-theme="light"] .topic-title {
        color: #0f172a;
    }

    /* Empty state */
    .song-empty-state {
        background: linear-gradient(180deg, #0f1724, #050607);
        padding: 28px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 18px;
    }

    :root[data-theme="light"] .song-empty-state {
        background: #ffffff;
        border: 1px solid rgba(15,23,42,0.08);
        box-shadow: 0 4px 20px rgba(15,23,42,0.06);
    }

    .song-empty-state h2 {
        margin: 0 0 8px 0;
        color: #fff;
        font-size: 18px;
    }

    :root[data-theme="light"] .song-empty-state h2 {
        color: #0f172a;
    }

    .song-empty-state p {
        margin: 0;
        color: rgba(255,255,255,0.78);
        font-size: 14px;
        line-height: 1.6;
    }

    :root[data-theme="light"] .song-empty-state p {
        color: #475569;
    }

    :root[data-theme="light"] .song-empty-state strong {
        color: #0f172a;
    }
</style>

<div class="song-index-wrapper">
    <h1>Song Tutorial</h1>

    @if($hasIntermediate)
        <p>Welcome to Song Tutorial. Select a song to start learning.</p>

        <div class="topic-grid">
            @foreach($topics as $topic)
                @php
                    $title = $topic->title ?: ($topic->lesson->title ?? 'Untitled Topic');
                    $thumb = !empty($topic->thumb) ? $topic->thumb : asset('compro/img/ndelogo.png');
                @endphp
                <a href="{{ route('song.tutorial.show', ['lesson' => $topic->lesson->id]) }}?topic={{ $topic->id }}" class="topic-card">
                    <div class="topic-thumb">
                        <img src="{{ $thumb }}" alt="{{ $title }}">
                    </div>
                    <div class="topic-title">{{ $title }}</div>
                </a>
            @endforeach
        </div>

    @else
        <div class="song-empty-state">
            <div style="flex:1">
                <h2>Song Tutorial is for Intermediate users</h2>
                <p>This feature is available only to users who have the <strong>intermediate</strong> package. You can purchase an upgrade on the class registration page.</p>
            </div>
            <div style="flex:0">
                @auth
                    <a href="{{ route('registerclass') }}" class="btn-ghost" style="display:inline-block">Go to Register Class</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost" style="display:inline-block">Login to Purchase</a>
                @endauth
            </div>
        </div>
    @endif
</div>
@endsection