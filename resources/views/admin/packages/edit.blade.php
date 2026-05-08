@extends('layouts.admin')

@section('title','Edit Package')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-3">Edit Package</h3>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.packages.update', $package->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="label">Name</label>
                                <input name="name" value="{{ old('name',$package->name) }}" class="form-control input" />
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Price (Rupiah ex:125000)</label>
                                <input name="price" value="{{ old('price',$package->price) }}" type="text" inputmode="numeric" autocomplete="off" data-currency-input class="form-control" />
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Member Price (optional)</label>
                                <input name="member_price" value="{{ old('member_price',$package->member_price) }}" type="text" inputmode="numeric" autocomplete="off" data-currency-input class="form-control" />
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Non-Member Price (optional)</label>
                                <input name="non_member_price" value="{{ old('non_member_price',$package->non_member_price) }}" type="text" inputmode="numeric" autocomplete="off" data-currency-input class="form-control" />
                            </div>

                            <div class="mb-3 col-md-12">
                                <label class="form-label">Slug (e.g. beginner/intermediate/coaching-ticket)</label>
                                <input name="slug" value="{{ old('slug',$package->slug) }}" class="form-control" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-text text-muted mb-2">For the `coaching-ticket` package, fill in both prices so checkout can automatically distinguish member vs non-member pricing. If left empty, the system will fall back to the main Price.</div>

                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="5">{{ old('description', $package->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Benefits (one per line)</label>
                            <textarea name="benefits" class="form-control" rows="5" placeholder="Write each benefit on its own line">{{ old('benefits', $package->benefits) }}</textarea>
                            <div class="form-text">Benefits will be shown as a list on the class cards. Use one benefit per line.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image (optional)</label>
                            @if(!empty($package->image))
                                <div class="mb-2"><img src="{{ asset('storage/'.$package->image) }}" alt="{{ $package->name }}" style="height:96px;object-fit:cover;border-radius:6px"></div>
                            @endif
                            <input type="file" name="image" accept="image/*" class="form-control" />
                            <div class="form-text">Upload a new image to replace the existing one. Max 2MB.</div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary me-2">Back</a>
                            <button type="submit" class="btn btn-primary">Save</button>
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
