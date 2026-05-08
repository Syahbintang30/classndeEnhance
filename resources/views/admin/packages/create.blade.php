@extends('layouts.admin')

@section('title','Create Package')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm card-table">
                <div class="card-body">
                    <h3 class="card-title mb-3">Create Package</h3>
                    <form method="POST" action="{{ route('admin.packages.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="mb-3 col-12">
                                <label class="form-label">Name</label>
                                <input name="name" value="{{ old('name') }}" class="form-control" />
                            </div>

                            <div class="mb-3 col-12">
                                <label class="form-label">Slug (e.g. beginner/intermediate/coaching-ticket)</label>
                                <input name="slug" value="{{ old('slug') }}" class="form-control" />
                            </div>

                            <div class="mb-3 col-12">
                                <label class="form-label">Price (Rupiah ex:125000)</label>
                                <input name="price" value="{{ old('price') }}" type="text" inputmode="numeric" autocomplete="off" data-currency-input class="form-control" />
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Member Price (optional)</label>
                                <input name="member_price" value="{{ old('member_price') }}" type="text" inputmode="numeric" autocomplete="off" data-currency-input class="form-control" />
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Non-Member Price (optional)</label>
                                <input name="non_member_price" value="{{ old('non_member_price') }}" type="text" inputmode="numeric" autocomplete="off" data-currency-input class="form-control" />
                            </div>

                            <div class="mb-2 col-12">
                                <div class="form-text text-muted">For the `coaching-ticket` package, fill in both prices so checkout can automatically distinguish member vs non-member pricing. If left empty, the system will fall back to the main Price.</div>
                            </div>

                            <div class="mb-3 col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3 col-12">
                                <label class="form-label">Benefits (one per line)</label>
                                <textarea name="benefits" class="form-control" rows="5" placeholder="Write each benefit on its own line">{{ old('benefits') }}</textarea>
                                <div class="form-text text-muted">Benefits will be shown as a list on the class cards. Use one benefit per line.</div>
                            </div>

                            <div class="mb-3 col-12">
                                <label class="form-label">Image (optional)</label>
                                <input type="file" name="image" accept="image/*" class="form-control" />
                                <div class="form-text text-muted">Optional image shown on package cards. Max 2MB.</div>
                            </div>

                            <div class="col-12 text-end mt-2">
                                <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary me-2">Back</a>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-currency-input]').forEach(function (input) {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/\D+/g, '');
        });
    });
});
</script>
@endpush
