@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold text-gray-800">Songsterr TAB Management</h1>
            <p class="text-muted small">Manage interactive 6-string guitar TABs, song tracks, and BPM for the student player.</p>
        </div>
        <a href="{{ route('admin.song-tabs.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus me-1"></i> Add New Song TAB
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Song Title</th>
                            <th>Artist</th>
                            <th>BPM</th>
                            <th>Difficulty</th>
                            <th>Track Name</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($songTabs as $tab)
                            <tr>
                                <td>#{{ $tab->id }}</td>
                                <td class="fw-bold text-dark">{{ $tab->title }}</td>
                                <td><span class="badge bg-secondary">{{ $tab->artist }}</span></td>
                                <td><code>{{ $tab->bpm }} BPM</code></td>
                                <td>
                                    <span class="badge bg-{{ $tab->difficulty === 'Hard' ? 'danger' : ($tab->difficulty === 'Medium' ? 'warning' : 'success') }}">
                                        {{ $tab->difficulty }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $tab->track_name }}</td>
                                <td class="text-end">
                                    <form action="{{ route('admin.song-tabs.destroy', $tab->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this Song TAB?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No Song TABs found. Click "Add New Song TAB" to create one!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
