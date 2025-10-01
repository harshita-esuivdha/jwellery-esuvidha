@extends('company.dashboard')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">
                <h3 class="text-center mb-4 fw-bold text-primary">Admin Registration</h3>

                {{-- Success / Error Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('superadmin.register.store') }}">
                    @csrf

                    <div class="row g-3">
                        {{-- Company Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" required>
                            @error('company_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Mobile Number --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mobile Number</label>
                            <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}" required>
                            @error('mobile')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Address --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="address" class="form-control" rows="2" required>{{ old('address') }}</textarea>
                            @error('address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Number of Companies Allowed --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Number of Companies Allowed</label>
                            <input type="number" name="company_limit" class="form-control" value="{{ old('company_limit', 5) }}" min="1" required>
                            @error('company_limit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Expiry Date --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}" required>
                            @error('expiry_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Auto-generate password notice --}}
                    <div class="alert alert-info mt-4 small">
                        Password will be <strong>auto-generated</strong> and sent to the registered email.
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill">Register Superadmin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
