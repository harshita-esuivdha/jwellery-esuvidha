@extends('company.dashboard')

@section('content')
<div class="container mt-4">
    <h3>Edit Customer</h3>

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

    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="form-control" required>
        </div>

        {{-- Customer Group --}}
        @php
            $groups = DB::table('groups')->pluck('customer_group');
        @endphp
        <div class="mb-3">
            <label>Customer Group</label>
            <select name="customer_group" id="customer_group" class="form-control">
                @foreach($groups as $group)
                    <option value="{{ $group }}" {{ old('customer_group', $customer->customer_group) == $group ? 'selected' : '' }}>
                        {{ $group }}
                    </option>
                @endforeach
                <option value="Other" {{ old('customer_group', $customer->customer_group) == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        {{-- Other Group --}}
        <div class="mb-3 d-none" id="other_group_div">
            <label>Please specify</label>
            <input type="text" name="other_group" id="other_group" value="{{ old('other_group', $customer->other_group) }}" class="form-control" placeholder="Enter group">
        </div>

        {{-- Address --}}
        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control">{{ old('address', $customer->address) }}</textarea>
        </div>

        {{-- Location --}}
        <div class="row">
            <div class="col-md-4 mb-3"><input type="text" name="city" placeholder="City" value="{{ old('city', $customer->city) }}" class="form-control"></div>
            <div class="col-md-4 mb-3"><input type="text" name="area" placeholder="Area" value="{{ old('area', $customer->area) }}" class="form-control"></div>
            <div class="col-md-4 mb-3"><input type="text" name="district" placeholder="District" value="{{ old('district', $customer->district) }}" class="form-control"></div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3"><input type="text" name="state" placeholder="State" value="{{ old('state', $customer->state) }}" class="form-control"></div>
            <div class="col-md-6 mb-3"><input type="text" name="pin_code" placeholder="Pin Code" value="{{ old('pin_code', $customer->pin_code) }}" class="form-control"></div>
        </div>

        {{-- Identity --}}
        <div class="row">
            <div class="col-md-6 mb-3"><input type="text" name="pan_number" placeholder="PAN Number" value="{{ old('pan_number', $customer->pan_number) }}" class="form-control"></div>
            <div class="col-md-6 mb-3"><input type="text" name="aadhaar_number" placeholder="Aadhaar Number" value="{{ old('aadhaar_number', $customer->aadhaar_number) }}" class="form-control"></div>
        </div>

        {{-- Contact --}}
        <div class="row">
            <div class="col-md-6 mb-3"><input type="text" name="phone" placeholder="Phone" value="{{ old('phone', $customer->phone) }}" class="form-control" required></div>
            <div class="col-md-6 mb-3"><input type="email" name="email" placeholder="Email" value="{{ old('email', $customer->email) }}" class="form-control"></div>
        </div>

        {{-- Date of Birth --}}
        <div class="mb-3">
            <label>Date of Birth</label>
            <input type="date" name="dob" value="{{ old('dob', $customer->dob) }}" class="form-control">
        </div>

        {{-- Bank Details --}}
        <h5>Bank Details</h5>
        <div class="mb-3"><input type="text" name="bank_name" placeholder="Bank Name" value="{{ old('bank_name', $customer->bank_name) }}" class="form-control"></div>
        <div class="mb-3"><input type="text" name="bank_account" placeholder="Account Number" value="{{ old('bank_account', $customer->bank_account) }}" class="form-control"></div>
        <div class="mb-3"><input type="text" name="ifsc_code" placeholder="IFSC Code" value="{{ old('ifsc_code', $customer->ifsc_code) }}" class="form-control"></div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

{{-- Script for "Other" group --}}
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

    // Keep visible if validation failed or customer has "Other"
    @if(old('customer_group', $customer->customer_group) === 'Other')
        otherGroupDiv.classList.remove('d-none');
    @endif
</script>
@endsection
