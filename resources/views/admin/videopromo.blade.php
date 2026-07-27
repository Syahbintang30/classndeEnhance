@extends('layouts.admin')

@section('title', 'Video Promo - Landing Page')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="h4 font-weight-bold text-dark mb-1 flex items-center gap-2">
                <i class="fa-solid fa-video text-danger"></i> Landing Page Video Promo
            </h2>
            <p class="text-muted text-sm mb-0">Manage the hero promotional video displayed on the main landing page.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Form Settings Card -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title font-weight-bold text-dark mb-0 text-sm uppercase tracking-wider">Video Promo Configuration</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.videopromo.update') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label font-weight-bold text-xs text-uppercase text-secondary">Bunny Video Stream GUID</label>
                            <input type="text" name="promo_bunny_guid" class="form-control font-mono" value="{{ old('promo_bunny_guid', $guid) }}" placeholder="e.g. 47ccfe4d-ea85-4ec7-b9b5-fd499ceaa08f">
                            <small class="text-muted text-xs d-block mt-1">Paste the 36-character Bunny Stream Video GUID to enable stream playback.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-weight-bold text-xs text-uppercase text-secondary">Promo Title</label>
                            <input type="text" name="promo_title" class="form-control" value="{{ old('promo_title', $title) }}" placeholder="e.g. Sneak Peek Masterclass">
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-weight-bold text-xs text-uppercase text-secondary">Thumbnail Image URL (Optional)</label>
                            <input type="text" name="promo_thumbnail" class="form-control" value="{{ old('promo_thumbnail', $thumbnail) }}" placeholder="https://...">
                            <small class="text-muted text-xs d-block mt-1">Direct URL to the poster/thumbnail image for the video player.</small>
                        </div>

                        <div class="d-flex justify-content-end pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4 py-2.5 rounded-3 font-weight-bold d-inline-flex align-items-center gap-2">
                                <i class="fa-solid fa-floppy-disk"></i> Save Promo Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title font-weight-bold text-dark mb-0 text-sm uppercase tracking-wider">Live Media Preview</h5>
                </div>
                <div class="card-body p-4 bg-dark text-white text-center">
                    @if($thumbnail)
                        <div class="position-relative rounded-3 overflow-hidden shadow-lg border border-secondary mb-3">
                            <img src="{{ $thumbnail }}" alt="Promo Thumbnail" class="img-fluid w-100 object-fit-cover" style="max-height: 240px;">
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 54px; height: 54px;">
                                    <i class="fa-solid fa-play fs-4 ms-1"></i>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="py-5 text-muted">
                            <i class="fa-solid fa-video-slash fs-1 opacity-50 mb-3"></i>
                            <p class="mb-0 text-sm">No promo video thumbnail configured yet.</p>
                        </div>
                    @endif

                    <div class="text-start bg-secondary-dark p-3 rounded-3 border border-secondary text-xs space-y-1">
                        <div><strong class="text-white">Title:</strong> <span class="text-light">{{ $title ?? 'Untitled' }}</span></div>
                        <div><strong class="text-white">GUID:</strong> <code class="text-info">{{ $guid ?: 'Not set' }}</code></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection