@extends('company.dashboard')

@section('content')

<!-- Bootstrap JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="container">
    <h2 class="my-3 d-flex justify-content-between align-items-center">
        <span>Items</span>
        <a href="{{ route('items.create') }}" class="btn btn-success btn-sm">+ Add Item</a>
    </h2>

    {{-- Filter --}}
    <form method="GET" class="row mb-3 g-2">
        <div class="col-md-4">
            <input type="text" name="item_name" value="{{ request('item_name') }}" class="form-control" placeholder="Search by Item Name">
        </div>
        <div class="col-md-4">
            <input list="item_groups" name="item_group" value="{{ request('item_group') }}" class="form-control" placeholder="Search by Group">
            <datalist id="item_groups">
                <option value="Cart">
                <option value="Slider">
                <option value="Gold">
                <option value="Silver">
                <option value="Ring">
                <option value="Necklace">
                <option value="Earring">
                <option value="Bangle">
                <option value="Pendant">
                <option value="Bracelet">
            </datalist>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100 text-dark"   style=" background: linear-gradient(90deg, #f7d046, #ffe98a);">Filter</button>
        </div>
    </form>

    @php
        $latestRate = DB::table('metal_rates')->latest('rate_date')->first();
    @endphp

    {{-- Items Table --}}
    <table class="table table-bordered table-hover align-middle">
        <thead>
            <tr  style=" color: linear-gradient(90deg, #f7d046, #ffe98a);">
                <th>Image</th>
                <th>Item Name</th>
                <th>Group</th>
                <th>HSN</th>
                <th>Net Weight</th>
                <th>Op Qty</th>
                <th>Rate (₹)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
         @php
    $rate = 0;
    $netWeight = floatval($item->net_weight ?? 0);

    if($latestRate && $netWeight > 0) {
        $group = strtolower(trim($item->metal_type));

        if($group == 'gold') {
            // Determine the karat
            $karat = intval($item->gold_karat ?? 24); // default 24 if empty
            switch($karat) {
                case 24:
                    $rate = floatval($latestRate->gold_24) * $netWeight;
                    break;
                case 22:
                    $rate = floatval($latestRate->gold_22) * $netWeight;
                    break;
                case 18:
                    $rate = floatval($latestRate->gold_18) * $netWeight;
                    break;
                default:
                    $rate = floatval($latestRate->gold_24) * $netWeight; // fallback
            }
        } elseif($group == 'silver') {
            $rate = floatval($latestRate->silver_999) * $netWeight;
        } else {
            $rate = 0; // Cart, Slider, other groups
        }
    }
@endphp

         <tr>
    {{-- Image --}}
    <td style="width:80px;">
        @if($item->image)
            <img src="{{ url('public/items/'.$item->image) }}" class="rounded-circle" 
                 style="width:70px; height:70px; object-fit:cover; border:1px solid #ccc;">
        @else
            <span class="text-muted">No Image</span>
        @endif
    </td>

    {{-- Other columns --}}
    <td>{{ $item->item_name }}</td>
    <td>{{ $item->item_group }}</td>
    <td>{{ $item->hsn_code }}</td>
    <td>{{ $item->net_weight }}</td>
    <td>{{ $item->op_qty }}</td>
    <td>₹{{ number_format($rate, 2) }}</td>

    {{-- Actions --}}
  <td style="width:180px;">
    <div class="d-flex justify-content-between">
        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-warning">Update</a>

        <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="margin:0;">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
        </form>

        <button class="btn btn-sm btn-info" type="button" 
                data-bs-toggle="collapse" data-bs-target="#details-{{ $item->id }}" 
                aria-expanded="false" aria-controls="details-{{ $item->id }}">
            Details
        </button>
    </div>
</td>

</tr>


            {{-- Collapsible details row --}}
            <tr class="collapse" id="details-{{ $item->id }}">
                <td colspan="8">
                    <div class="card card-body bg-light shadow-sm">
                        <div class="row">
                            <div class="col-md-4 mb-2"><strong>Short Name:</strong> {{ $item->short_name }}</div>
                            <div class="col-md-4 mb-2"><strong>Tag Number:</strong> {{ $item->tag_number }}</div>
                            <div class="col-md-4 mb-2"><strong>HUID:</strong> {{ $item->huid }}</div>
                            <div class="col-md-4 mb-2"><strong>Gross Weight:</strong> {{ $item->gross_weight }}</div>
                            <div class="col-md-4 mb-2"><strong>Bead Weight:</strong> {{ $item->bead_weight }}</div>
                            <div class="col-md-4 mb-2"><strong>Diamond Weight:</strong> {{ $item->diamond_weight }}</div>
                            <div class="col-md-4 mb-2"><strong>Diamond Rate per Carat:</strong> {{ $item->diamond_rate_per_carat }}</div>
                            <div class="col-md-4 mb-2"><strong>Polish %:</strong> {{ $item->polish_percentage }}</div>
                            <div class="col-md-4 mb-2"><strong>Tax Slab:</strong> {{ $item->tax_slab }}%</div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="table-secondary">
            <tr>
                <th colspan="4">Totals</th>
                <th>{{ $totals['total_net_weight'] }}</th>
                <th>{{ $totals['total_op_qty'] }}</th>
                <th colspan="2">{{ $totals['total_tags'] }} Tags</th>
            </tr>
        </tfoot>
    </table>

    @if(!$latestRate)
        <div class="alert alert-warning mt-2">
            Metal rates not available. Please update rates to calculate item values.
        </div>
    @endif
</div>

@endsection
