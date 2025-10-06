{{-- resources/views/company/form.blade.php --}}
@extends('company.dashboard')

@section('title', 'Company Profile')
@section('page-title', 'Edit Company Profile')

@section('content')
<div class="container mt-5">

    {{-- Alerts --}}
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

    {{-- Advanced Profile Form --}}
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header text-white rounded-top-4 " style="  background: linear-gradient(135deg, #fdeb86, #ffb347);">
            <h4 class="mb-0 text-dark"><i class="bi bi-building text-dark"></i> Company Profile</h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('company.storeprofile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $company->id ?? '' }}">

                <div class="row g-4">

                    {{-- Bill Number --}}
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="bill_no" class="form-control" 
                                value="{{ old('bill_no', $company->bill_no ?? '') }}" 
                                placeholder="Bill Number" required>
                            <label>Bill Number <span class="text-danger">*</span></label>
                        </div>
                        <small class="text-muted">Format: PREFIX-YEAR-SEQ</small>
                    </div>

                    {{-- Company Name --}}
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $company->name ?? '') }}" placeholder="Company Name" required>
                            <label>Company Name <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="address" class="form-control" 
                                   value="{{ old('address', $company->address ?? '') }}" placeholder="Address" required>
                            <label>Address <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    {{-- State --}}
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="itstate" class="form-control" 
                                   value="{{ old('itstate', $company->itstate ?? '') }}" placeholder="State" required>
                            <label>State <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    {{-- City --}}
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="city" class="form-control" 
                                   value="{{ old('city', $company->city ?? '') }}" placeholder="City" required>
                            <label>City <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    {{-- Pincode --}}
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="pincode" class="form-control" 
                                   value="{{ old('pincode', $company->pincode ?? '') }}" placeholder="Pincode">
                            <label>Pincode</label>
                        </div>
                    </div>

                    {{-- District --}}
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="district" class="form-control" 
                                   value="{{ old('district', $company->district ?? '') }}" placeholder="District">
                            <label>District</label>
                        </div>
                    </div>

                    {{-- Mobile --}}
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="tel" name="mobile" class="form-control" 
                                   value="{{ old('mobile', $company->mobile ?? '') }}" placeholder="Mobile" required>
                            <label>Mobile <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="email" name="email" class="form-control" 
                                   value="{{ old('email', $company->email ?? '') }}" placeholder="Email" required>
                            <label>Email <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    {{-- Website --}}
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="url" name="website" class="form-control" 
                                   value="{{ old('website', $company->website ?? '') }}" placeholder="Website">
                            <label>Website</label>
                        </div>
                    </div>

                    {{-- Logo Upload --}}
                    <div class="col-md-6">
                        <label class="form-label">Company Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        @if(!empty($company->logo))
                            <div class="mt-2">
                                <img src="{{ url('public/company/logo/'.$company->logo) }}" height="60" class="rounded shadow-sm">
                            </div>
                        @endif
                    </div>

                    {{-- GST & PAN --}}
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="gst_no" class="form-control" 
                                   value="{{ old('gst_no', $company->gst_no ?? '') }}" placeholder="GST Number">
                            <label>GST Number</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="pan_no" class="form-control" 
                                   value="{{ old('pan_no', $company->pan_no ?? '') }}" placeholder="PAN Number">
                            <label>PAN Number</label>
                        </div>
                    </div>

                    {{-- Financial Year --}}
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" name="fy_start" class="form-control" value="{{ old('fy_start', $company->fy_start ?? '') }}">
                            <label>Financial Year Start</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" name="fy_end" class="form-control" value="{{ old('fy_end', $company->fy_end ?? '') }}">
                            <label>Financial Year End</label>
                        </div>
                    </div>

                    {{-- Bank Details --}}
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="bank_name" class="form-control" placeholder="Bank Name" value="{{ old('bank_name', $company->bank_name ?? '') }}">
                            <label>Bank Name</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="account_no" class="form-control" placeholder="Account Number" value="{{ old('account_no', $company->account_no ?? '') }}">
                            <label>Account Number</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="ifsc" class="form-control" placeholder="IFSC" value="{{ old('ifsc', $company->ifsc ?? '') }}">
                            <label>IFSC Code</label>
                        </div>
                    </div>

                    {{-- Login Credentials --}}
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="username" class="form-control" placeholder="Username" value="{{ old('username', $company->username ?? '') }}" required>
                            <label>Username <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                            <label>Password</label>
                        </div>
                    </div>

                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm text-dark" style=" background: linear-gradient(135deg, #fdeb86, #ffb347);">
                        <i class="bi bi-save"></i> Save Profile
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- Optional Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

{{-- Custom CSS --}}
<style>
    .form-floating > label {
        font-weight: 500;
    }
    .card-header h4 i {
        margin-right: 8px;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
</style>
@endsection
