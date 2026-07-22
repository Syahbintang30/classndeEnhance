@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4 max-w-4xl">
    <div class="mb-4">
        <a href="{{ route('admin.song-tabs.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="fas fa-arrow-left me-1"></i> Back to Song TABs
        </a>
        <h1 class="h3 font-weight-bold text-gray-800">Add New Song TAB</h1>
        <p class="text-muted small">Create a new interactive Songsterr guitar TAB track for your students.</p>
    </div>

    <div class="card shadow border-0 rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.song-tabs.store') }}" method="POST">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Song Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Sweet Child O' Mine" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Artist / Band</label>
                        <input type="text" name="artist" class="form-control" value="Nde Guitar" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Tempo (BPM)</label>
                        <input type="number" name="bpm" class="form-control" value="120" min="30" max="300" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Difficulty</label>
                        <select name="difficulty" class="form-select" required>
                            <option value="Easy">Easy (Beginner Riff)</option>
                            <option value="Medium" selected>Medium (Melodic Lead)</option>
                            <option value="Hard">Hard (Shred Solo)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Track Name</label>
                        <input type="text" name="track_name" class="form-control" value="Electric Guitar Lead Solo" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Songsterr TAB JSON Notations (Optional / Advanced)</label>
                    <textarea name="tab_json" class="form-control font-monospace" rows="8" placeholder='[
  [
    {"string": 3, "fret": "12", "note": "D", "freq": 146.83, "beat": 0},
    {"string": 1, "fret": "15", "note": "D", "freq": 293.66, "beat": 1}
  ]
]'></textarea>
                    <small class="text-muted">Leave blank to generate a standard 4-beat guitar TAB measure automatically.</small>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('admin.song-tabs.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                        <i class="fas fa-save me-1"></i> Save Song TAB
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
