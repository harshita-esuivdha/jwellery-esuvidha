@extends('company.dashboard')

@section('content')
<div class="container mt-5">

    <div class="card shadow-sm border-0">
        <div class="card-header text-dark" style="background: linear-gradient(90deg, #00ffff, #f5deb3);">
            <h4 class="mb-0">Add Customer</h4>
        </div>
        <div class="card-body">

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.customers.store') }}" method="POST">
                @csrf

                <style>
                    label.required::after {
                        content: " *";
                       
                    }
                    select:required:invalid, input:required:invalid, textarea:required:invalid {
                       
                        background-color: #ffe6e6;
                    }
                </style>

                {{-- Basic Details --}}
                <div class="mb-3">
                    <label class="required form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                {{-- Group Selection --}}
                @php
                    $groups = DB::table('groups')->pluck('customer_group');
                @endphp
                <div class="mb-3">
                    <label class="required form-label">Group</label>
                    <select name="customer_group" id="customer_group" class="form-select" required>
                        @foreach($groups as $group)
                            <option value="{{ $group }}" {{ old('customer_group') == $group ? 'selected' : '' }}>
                                {{ $group }}
                            </option>
                        @endforeach
                        <option value="Other" {{ old('customer_group') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                {{-- Other Group Input --}}
                <div class="mb-3 d-none" id="other_group_div">
                    <label class="required form-label">Please specify</label>
                    <input type="text" name="other_group" id="other_group" class="form-control" 
                           value="{{ old('other_group') }}" placeholder="Enter group">
                </div>

                {{-- Address --}}
                <div class="mb-3">
                    <label class="required form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2" required>{{ old('address') }}</textarea>
                </div>

                {{-- City / Area / District --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" name="city" value="{{ old('city') }}" placeholder="City" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="area" value="{{ old('area') }}" placeholder="Area" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="district" value="{{ old('district') }}" placeholder="District" class="form-control">
                    </div>
                </div>

                {{-- State / Pin --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" name="state" value="{{ old('state') }}" placeholder="State" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="pin_code" value="{{ old('pin_code') }}" placeholder="Pin Code" class="form-control">
                    </div>
                </div>

                {{-- PAN / Aadhaar --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" name="pan_number" value="{{ old('pan_number') }}" placeholder="PAN Number" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="aadhaar_number" value="{{ old('aadhaar_number') }}" placeholder="Aadhaar Number" class="form-control">
                    </div>
                </div>

                {{-- Phone / Email --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="form-control">
                    </div>
                </div>

                {{-- DOB --}}
                <div class="mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob') }}" class="form-control">
                </div>

                {{-- Bank Details --}}
                <h5 class="mt-4 mb-3">Bank Details</h5>
                <div class="mb-3">
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="Bank Name" class="form-control">
                </div>
                <div class="mb-3">
                    <input type="text" name="bank_account" value="{{ old('bank_account') }}" placeholder="Account Number" class="form-control">
                </div>
                <div class="mb-3">
                    <input type="text" name="ifsc_code" value="{{ old('ifsc_code') }}" placeholder="IFSC Code" class="form-control">
                </div>

                {{-- Buttons --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script to toggle "Other" group --}}
<script>
    const selectGroup = document.getElementById('customer_group');
    const otherGroupDiv = document.getElementById('other_group_div');

    function toggleOtherGroup() {
        if (selectGroup.value === 'Other') {
            otherGroupDiv.classList.remove('d-none');
        } else {
            otherGroupDiv.classList.add('d-none');
            document.getElementById('other_group').value = '';
        }
    }

    selectGroup.addEventListener('change', toggleOtherGroup);

    // Keep visible if validation failed and "Other" was selected
    @if(old('customer_group') == 'Other')
        otherGroupDiv.classList.remove('d-none');
    @endif
</script>
@endsection
