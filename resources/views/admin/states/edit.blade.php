@extends('layouts.admin')

@section('title', 'Edit State')

@section('content')
    <h1 class="mb-4">Edit State</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.states.update', $state) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">State Name</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $state->name) }}"
                        placeholder="Enter state name"
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary">Update</button>
                <a href="{{ route('admin.states.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
@endsection