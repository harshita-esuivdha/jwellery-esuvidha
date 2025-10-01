{{-- resources/views/company/form.blade.php --}}
@extends('company.dashboard')

@section('title', 'Company Profile')
@section('page-title', 'Edit Company Profile')

@section('content')
<div class="container mt-4">

    {{-- Success / Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ❌ {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Profile Form --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Company Profile</h5>
        </div>
        <div class="card-body">
           <form action="{{ route('company.storeprofile') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="id" value="{{ $company->id ?? '' }}">

    <div class="row g-3">

        {{-- Bill Number --}}
        <div class="col-md-4">
            <label class="form-label">Bill Number <span class="text-danger">*</span></label>
           <input type="text" name="bill_no" class="form-control" 
       value="{{ old('bill_no', $company->bill_no ?? '') }}" 
       placeholder="e.g., INV-2025-001" required>

            <small class="text-muted">Format: PREFIX-YEAR-SEQ</small>
        </div>

        {{-- Basic Details --}}
        <div class="col-md-6">
            <label class="form-label">Company Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control form-control-lg" 
                   value="{{ old('name', $company->name ?? '') }}" placeholder="Enter company name" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Address <span class="text-danger">*</span></label>
            <input type="text" name="address" class="form-control form-control-lg" 
                   value="{{ old('address', $company->address ?? '') }}" placeholder="Enter company address" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">State <span class="text-danger">*</span></label>
            <input type="text" name="itstate" class="form-control" 
                   value="{{ old('itstate', $company->itstate ?? '') }}" placeholder="Enter state" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">City <span class="text-danger">*</span></label>
            <input type="text" name="city" class="form-control" 
                   value="{{ old('city', $company->city ?? '') }}" placeholder="Enter city" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Pincode</label>
            <input type="text" name="pincode" class="form-control" 
                   value="{{ old('pincode', $company->pincode ?? '') }}" placeholder="e.g., 110001">
        </div>

        <div class="col-md-4">
            <label class="form-label">District</label>
            <input type="text" name="district" class="form-control" 
                   value="{{ old('district', $company->district ?? '') }}" placeholder="Enter district">
        </div>
        <div class="col-md-4">
            <label class="form-label">Mobile <span class="text-danger">*</span></label>
            <input type="tel" name="mobile" class="form-control" 
                   value="{{ old('mobile', $company->mobile ?? '') }}" placeholder="Enter mobile number" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" 
                   value="{{ old('email', $company->email ?? '') }}" placeholder="Enter email" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Website</label>
            <input type="url" name="website" class="form-control" 
                   value="{{ old('website', $company->website ?? '') }}" placeholder="https://example.com">
        </div>
        <div class="col-md-6">
            <label class="form-label">Company Logo</label>
            <input type="file" name="logo" class="form-control" accept="image/*">
            @if(!empty($company->logo))
                <small class="d-block mt-1">Current: <img src="{{ url('public/company/logo/'.$company->logo) }}" height="40"></small>
            @endif
        </div>

        {{-- Registration Details --}}
        <div class="col-md-6">
            <label class="form-label">GST Number</label>
            <input type="text" name="gst_no" class="form-control" 
                   value="{{ old('gst_no', $company->gst_no ?? '') }}" placeholder="Enter GST number">
        </div>
        <div class="col-md-6">
            <label class="form-label">PAN Number</label>
            <input type="text" name="pan_no" class="form-control" 
                   value="{{ old('pan_no', $company->pan_no ?? '') }}" placeholder="Enter PAN number">
        </div>

        {{-- Financial Year --}}
        <div class="col-md-6">
            <label class="form-label">Financial Year Start</label>
            <input type="date" name="fy_start" class="form-control" value="{{ old('fy_start', $company->fy_start ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Financial Year End</label>
            <input type="date" name="fy_end" class="form-control" value="{{ old('fy_end', $company->fy_end ?? '') }}">
        </div>

        {{-- Bank Details --}}
        <div class="col-md-4">
            <label class="form-label">Bank Name</label>
            <input type="text" name="bank_name" class="form-control" placeholder="Enter bank name" value="{{ old('bank_name', $company->bank_name ?? '') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Account Number</label>
            <input type="text" name="account_no" class="form-control" placeholder="Enter account number" value="{{ old('account_no', $company->account_no ?? '') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">IFSC Code</label>
            <input type="text" name="ifsc" class="form-control" placeholder="Enter IFSC code" value="{{ old('ifsc', $company->ifsc ?? '') }}">
        </div>

        {{-- Login Credentials --}}
        <div class="col-md-6">
            <label class="form-label">Username <span class="text-danger">*</span></label>
            <input type="text" name="username" class="form-control" placeholder="Enter username" value="{{ old('username', $company->username ?? '') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-success btn-lg">
            💾 Save Profile
        </button>
    </div>
</form>

        </div>
    </div>
</div>
@endsection
