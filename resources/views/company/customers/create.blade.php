@extends('company.dashboard')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Main Card --}}
            <div class="card shadow-lg border-0 rounded-3">

                {{-- Card Header: Custom Yellow/Amber Gradient with specified border --}}
                <div class="card-header text-dark p-4 rounded-top-3" style="
                    /* Vibrant Yellow/Amber Gradient */
                    background: linear-gradient(135deg, #FFD700, #FFA500);
                    /* Using the requested color for a distinct bottom border */
                    border-bottom: 2px solid #e6c200; 
                ">
                    <h3 class="mb-0 fw-bold">⭐ Add New Customer</h3>
                    <p class="mb-0 opacity-75">Fill in the required details to create a new customer record.</p>
                </div>

                <div class="card-body p-4 p-md-5">

                    {{-- Error Messages --}}
                    @if ($errors->any())
                        <div class="alert alert-warning alert-dismissible fade show border-0 rounded-2" role="alert" style="background-color: #fff3cd; border-color: #ffc107;">
                            <h5 class="alert-heading fs-6 text-dark"><i class="bi bi-exclamation-triangle-fill me-2"></i>**Validation Errors**</h5>
                            <ul class="mb-0 list-unstyled text-dark">
                                @foreach ($errors->all() as $error)
                                    <li class="small">{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Customer Form --}}
                    <form action="{{ route('admin.customers.store') }}" method="POST">
                        @csrf

                        {{-- Custom Styles for required fields and theme elements --}}
                        <style>
                            /* Required Field Indicator (Red/Danger for contrast) */
                            label.required::after { content: " *"; color: #dc3545; font-weight: bold; }

                            /* Highlight required inputs on focus using the specific color */
                            .form-control:required:focus {
                                border-color: #e6c200; 
                                box-shadow: 0 0 0 0.25rem rgba(230, 194, 0, 0.25); /* Adjusted glow for the specific color */
                            }
                            .form-select:required:focus {
                                border-color: #e6c200;
                                box-shadow: 0 0 0 0.25rem rgba(230, 194, 0, 0.25);
                            }
                        </style>

                        {{-- Section 1: Basic Information --}}
                        <h4 class="mb-4 pb-2 fw-bold" style="color: #e6c200; border-bottom: 2px solid #e6c200;">Personal & Group Details</h4>

                        <div class="mb-3">
                            <label for="name" class="required form-label">Customer Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g., Jane Doe">
                        </div>

                        {{-- Group Selection --}}
                        @php
                            $groups = DB::table('groups')->pluck('customer_group');
                        @endphp
                        <div class="mb-3">
                            <label for="customer_group" class="required form-label">Customer Group</label>
                            <select name="customer_group" id="customer_group" class="form-select" required>
                                <option value="" disabled selected>Select a group</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group }}" {{ old('customer_group') == $group ? 'selected' : '' }}>
                                        {{ $group }}
                                    </option>
                                @endforeach
                                <option value="Other" {{ old('customer_group') == 'Other' ? 'selected' : '' }}>Other (Specify below)</option>
                            </select>
                        </div>

                        {{-- Other Group Input --}}
                        <div class="mb-3 {{ old('customer_group') == 'Other' ? '' : 'd-none' }}" id="other_group_div">
                            <label for="other_group" class="required form-label">Specify Other Group Name</label>
                            <input type="text" name="other_group" id="other_group" class="form-control"
                                   value="{{ old('other_group') }}" placeholder="Enter new group name">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="required form-label">Phone Number</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Phone" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address (Optional)</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Email" class="form-control">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="dob" class="form-label">Date of Birth (Optional)</label>
                            <input type="date" name="dob" id="dob" value="{{ old('dob') }}" class="form-control">
                        </div>

                        <hr style="border-color: #e6c200; opacity: 0.5;">

                        {{-- Section 2: Address Details --}}
                        <h4 class="mb-4 mt-4 pb-2 fw-bold" style="color: #e6c200; border-bottom: 2px solid #e6c200;">Address Details</h4>

                        <div class="mb-3">
                            <label for="address" class="required form-label">Full Address</label>
                            <textarea name="address" id="address" class="form-control" rows="3" required placeholder="Street Address, Building Name, etc.">{{ old('address') }}</textarea>
                        </div>

                        <div class="row mb-3 g-3">
                            <div class="col-md-4">
                                <input type="text" name="city" value="{{ old('city') }}" placeholder="City *" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="area" value="{{ old('area') }}" placeholder="Area (Optional)" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="district" value="{{ old('district') }}" placeholder="District (Optional)" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-4 g-3">
                            <div class="col-md-6">
                                <input type="text" name="state" value="{{ old('state') }}" placeholder="State (Optional)" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="pin_code" value="{{ old('pin_code') }}" placeholder="Pin Code (Optional)" class="form-control">
                            </div>
                        </div>

                        <hr style="border-color: #e6c200; opacity: 0.5;">

                        {{-- Section 3: Identity & Bank Details --}}
                        <h4 class="mb-4 mt-4 pb-2 fw-bold" style="color: #e6c200; border-bottom: 2px solid #e6c200;">Identity & Bank Details (Optional)</h4>

                        <div class="row mb-3 g-3">
                            <div class="col-md-6">
                                <input type="text" name="pan_number" value="{{ old('pan_number') }}" placeholder="PAN Number" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="aadhaar_number" value="{{ old('aadhaar_number') }}" placeholder="Aadhaar Number" class="form-control">
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3 text-secondary">Bank Account Information</h5>
                        <div class="mb-3">
                            <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="Bank Name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <input type="text" name="bank_account" value="{{ old('bank_account') }}" placeholder="Account Number" class="form-control">
                        </div>
                        <div class="mb-4">
                            <input type="text" name="ifsc_code" value="{{ old('ifsc_code') }}" placeholder="IFSC Code" class="form-control">
                        </div>

                        <hr style="border-color: #e6c200; opacity: 0.5;">

                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-end gap-3 pt-3">
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                            {{-- Save button with the specific color background --}}
                            <button type="submit" class="btn text-dark px-4 fw-bold" style="background-color: #e6c200; border-color: #e6c200;">
                                Save Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script to toggle "Other" group (Kept unchanged) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectGroup = document.getElementById('customer_group');
        const otherGroupDiv = document.getElementById('other_group_div');
        const otherGroupInput = document.getElementById('other_group');

        function toggleOtherGroup() {
            if (selectGroup.value === 'Other') {
                otherGroupDiv.classList.remove('d-none');
                otherGroupInput.setAttribute('required', 'required');
            } else {
                otherGroupDiv.classList.add('d-none');
                otherGroupInput.value = '';
                otherGroupInput.removeAttribute('required');
            }
        }

        toggleOtherGroup();
        selectGroup.addEventListener('change', toggleOtherGroup);
    });
</script>
@endsection