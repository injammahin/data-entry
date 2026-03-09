@extends('layouts.admin')

@section('title', 'Upload CSV')

@section('content')
    <h1 class="mb-4">Upload CSV</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.imports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Select State</label>
                    <select name="state_id" class="form-select @error('state_id') is-invalid @enderror">
                        <option value="">Choose State</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                {{ $state->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('state_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">CSV File</label>
                    <input
                        type="file"
                        name="csv_file"
                        class="form-control @error('csv_file') is-invalid @enderror"
                        accept=".csv,.txt"
                    >
                    @error('csv_file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted d-block mt-2">
                        Upload a CSV file with the first row as header.
                    </small>
                </div>

                <button type="submit" class="btn btn-success" onclick="this.disabled=true; this.form.submit();">
                    Upload
                </button>
                <a href="{{ route('admin.imports.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
@endsection