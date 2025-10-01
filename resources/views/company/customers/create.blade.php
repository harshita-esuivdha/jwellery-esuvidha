@extends('company.dashboard')

@section('content')
<div class="container mt-4">
    <h3>Add Customer</h3>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<style>
    /* Highlight required field labels */
    label.required::after {
        content: " *";
        color: red;
    }

    /* Highlight empty required fields on focus */
    select:required:invalid {
        border-color: red;
        background-color: #ffe6e6;
    }
</style>

<form action="{{ route('admin.customers.store') }}" method="POST">
    @csrf

    {{-- Basic Details --}}
    <div class="mb-3">
        <label class="required">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    {{-- Group Selection --}}
    @php
        $groups = DB::table('groups')->pluck('customer_group');
    @endphp

    <div class="mb-3">
        <label class="required">Group</label>
        <select name="customer_group" id="customer_group" class="form-control" required>
            @foreach($groups as $group)
                <option value="{{ $group }}" {{ old('customer_group') == $group ? 'selected' : '' }}>
                    {{ $group }}
                </option>
            @endforeach
            <option value="Other" {{ old('customer_group') == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
    </div>

    {{-- Other Group Input (hidden by default) --}}
    <div class="mb-3 d-none" id="other_group_div">
        <label class="required">Please specify</label>
        <input type="text" name="other_group" id="other_group" class="form-control" 
               value="{{ old('other_group') }}" placeholder="Enter group">
    </div>

    {{-- Address --}}
    <div class="mb-3">
        <label class="required">Address</label>
        <textarea name="address" class="form-control" required>{{ old('address') }}</textarea>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <input type="text" name="city" value="{{ old('city') }}" placeholder="City" class="form-control" required>
        </div>
        <div class="col-md-4 mb-3">
            <input type="text" name="area" value="{{ old('area') }}" placeholder="Area" class="form-control">
        </div>
        <div class="col-md-4 mb-3">
            <input type="text" name="district" value="{{ old('district') }}" placeholder="District" class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <input type="text" name="state" value="{{ old('state') }}" placeholder="State" class="form-control">
        </div>
        <div class="col-md-6 mb-3">
            <input type="text" name="pin_code" value="{{ old('pin_code') }}" placeholder="Pin Code" class="form-control">
        </div>
    </div>

    {{-- Identity --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <input type="text" name="pan_number" value="{{ old('pan_number') }}" placeholder="PAN Number" class="form-control">
        </div>
        <div class="col-md-6 mb-3">
            <input type="text" name="aadhaar_number" value="{{ old('aadhaar_number') }}" placeholder="Aadhaar Number" class="form-control">
        </div>
    </div>

    {{-- Contact --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="form-control">
        </div>
    </div>

    {{-- Date of Birth --}}
    <div class="mb-3">
        <label>Date of Birth</label>
        <input type="date" name="dob" value="{{ old('dob') }}" class="form-control">
    </div>

    {{-- Bank Details --}}
    <h5>Bank Details</h5>
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
    <button type="submit" class="btn btn-success">Save</button>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Cancel</a>
</form>

<script>
    // Show/hide "Other Group" field
    document.getElementById('customer_group').addEventListener('change', function() {
        const otherDiv = document.getElementById('other_group_div');
        otherDiv.classList.toggle('d-none', this.value !== 'Other');
        if(this.value !== 'Other') {
            document.getElementById('other_group').value = '';
        }
    });
</script>

</div>

{{-- Script to handle "Other" group --}}
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
