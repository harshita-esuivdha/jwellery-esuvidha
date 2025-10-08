@extends('company.dashboard')

@section('content')
<div class="container">
    <h4 class="mb-4">New Purchase</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('purchases.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="item_name" class="form-label">Item Name</label>
            <input type="text" name="item_name" id="item_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="item_type" class="form-label">Item Type</label>
            <select name="item_type" id="item_type" class="form-select" required>
                <option value="Gold">Gold</option>
                <option value="Diamond">Diamond</option>
                <option value="Stone">Stone</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="row g-3 mb-3">
            <div class="col">
                <label for="weight" class="form-label">Weight (g/carat)</label>
                <input type="number" step="0.01" name="weight" id="weight" class="form-control">
            </div>
            <div class="col">
                <label for="rate" class="form-label">Rate</label>
                <input type="number" step="0.01" name="rate" id="rate" class="form-control">
            </div>
            <div class="col">
                <label for="wastage_percent" class="form-label">Wastage (%)</label>
                <input type="number" step="0.01" name="wastage_percent" id="wastage_percent" class="form-control" value="0">
            </div>
            <div class="col">
                <label for="labor_charge" class="form-label">Labor Charge</label>
                <input type="number" step="0.01" name="labor_charge" id="labor_charge" class="form-control" value="0">
            </div>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Purchase</button>
    </form>
</div>
@endsection
