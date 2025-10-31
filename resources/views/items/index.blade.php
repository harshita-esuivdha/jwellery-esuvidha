@extends('company.dashboard')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
    .table-danger.bg-opacity-50 {
        background-color: #ffe5e5 !important;
    }
</style>

<style>
    :root {
        --gold: #d4af37;
        --gold-light: #f5e7b2;
        --text-dark: #212529;
        --gray-bg: #f8f9fa;
        --gray-border: #dee2e6;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background-color: white;
        border: 1px solid var(--gray-border);
        border-left: 5px solid var(--gold);
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .page-header h2 {
        font-weight: 600;
        color: var(--text-dark);
    }

    .gold-btn {
        background-color: var(--gold);
        border: none;
        color: #fff;
        font-weight: 500;
        padding: 0.45rem 1rem;
        border-radius: 6px;
        transition: all 0.2s ease-in-out;
    }
    .gold-btn:hover {
        background-color: #b9962e;
        color: #fff;
    }

    .filter-card {
        border: 1px solid var(--gray-border);
        border-radius: 8px;
        background: #fff;
    }

    .table {
        border-radius: 8px;
        overflow: hidden;
        background-color: white;
        border: 1px solid var(--gray-border);
    }

    .table thead th {
        background-color: var(--gold-light);
        color: #333;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    .table td {
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .item-image {
        width: 55px;
        height: 55px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid var(--gray-border);
    }

    .details-card {
        background: #fffdf5;
        border-left: 4px solid var(--gold);
    }

    tfoot {
        background: var(--gray-bg);
        font-weight: 600;
    }

    .alert-gold {
        background-color: #fff8e1;
        color: #5c4a00;
        border-left: 4px solid var(--gold);
    }

    .btn-sm i {
        font-size: 0.85rem;
    }

    /* Icon color tweaks */
    .btn-warning i { color: #fff; }
    .btn-danger i { color: #fff; }
    .btn-info i { color: #fff; }
</style>

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="page-header mb-4">
        <h2><i class="bi bi-box-seam me-2 text-gold"></i> Item Inventory</h2>
        <a href="{{ route('items.create') }}" class="btn gold-btn">
            <i class="bi bi-plus-circle me-1"></i> Add Item
        </a>
    </div>

    {{-- Filter --}}
    <div class="filter-card mb-4 p-3">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Item Name</label>
                <input type="text" name="item_name" value="{{ request('item_name') }}" class="form-control form-control-sm" placeholder="Enter item name">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Item Group</label>
                <input list="item_groups" name="item_group" value="{{ request('item_group') }}" class="form-control form-control-sm" placeholder="Select group">
                <datalist id="item_groups">
                    <option value="Gold"><option value="Silver"><option value="Ring"><option value="Necklace">
                    <option value="Earring"><option value="Bangle"><option value="Pendant"><option value="Bracelet">
                </datalist>
            </div>
            <div class="col-md-2">
                <button class="btn gold-btn w-100"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
        </form>
    </div>

    @php
        $latestRate = DB::table('metal_rates')->latest('rate_date')->first();
    @endphp

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
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
                $group = strtolower(trim($item->metal_type ?? ''));
                if($group == 'gold') {
                    $karat = intval($item->gold_karat ?? 24);
                    $rate = floatval($latestRate->{'gold_'.$karat} ?? $latestRate->gold_24) * $netWeight;
                } elseif($group == 'silver') {
                    $rate = floatval($latestRate->silver_999 ?? 0) * $netWeight;
                }
            }
        @endphp

        {{-- Apply red background if stock is 0 --}}
        <tr class="{{ $item->stock == 0 ? 'table-danger bg-opacity-50' : '' }}">
            <td>
                @if($item->image)
                    <img src="{{ url('public/items/'.$item->image) }}" class="item-image" alt="Item Image">
                @else
                    <span class="text-muted small">No Image</span>
                @endif
            </td>
            <td class="fw-semibold">{{ $item->item_name }}</td>
            <td>{{ $item->item_group }}</td>
            <td>{{ $item->hsn_code }}</td>
            <td>{{ $item->net_weight }} g</td>
            <td>{{ $item->op_qty }}</td>
            <td>₹{{ number_format($rate, 2) }}</td>
            <td>
                <div class="d-flex gap-2">
                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <form action="{{ route('items.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>

                    <button class="btn btn-sm btn-info" type="button"
                        data-bs-toggle="collapse" data-bs-target="#details-{{ $item->id }}"
                        aria-expanded="false" aria-controls="details-{{ $item->id }}" title="Details">
                        <i class="bi bi-info-circle"></i>
                    </button>
                </div>
            </td>
        </tr>

        {{-- Collapsible details --}}
        <tr class="collapse" id="details-{{ $item->id }}">
            <td colspan="8" class="p-0">
                <div class="details-card p-3 small text-secondary">
                    <h6 class="fw-semibold mb-2 text-dark">Additional Details</h6>
                    <div class="row">
                        <div class="col-md-4"><strong>Short Name:</strong> {{ $item->short_name }}</div>
                        <div class="col-md-4"><strong>Stock:</strong> {{ $item->stock }}</div>
                        <div class="col-md-4"><strong>Tag No:</strong> {{ $item->tag_number }}</div>
                        <div class="col-md-4"><strong>HUID:</strong> {{ $item->huid }}</div>
                        <div class="col-md-4"><strong>Gross Weight:</strong> {{ $item->gross_weight }} g</div>
                        <div class="col-md-4"><strong>Bead Weight:</strong> {{ $item->bead_weight }} g</div>
                        <div class="col-md-4"><strong>Diamond Weight:</strong> {{ $item->diamond_weight }} ct</div>
                        <div class="col-md-4"><strong>Diamond Rate:</strong> ₹{{ number_format($item->diamond_rate_per_carat, 2) }}</div>
                        <div class="col-md-4"><strong>Polish %:</strong> {{ $item->polish_percentage }}%</div>
                        <div class="col-md-4"><strong>Tax Slab:</strong> {{ $item->tax_slab }}%</div>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
</tbody>


            <tfoot>
                <tr>
                    <td colspan="4" class="text-end">TOTALS:</td>
                    <td>{{ $totals['total_net_weight'] }} g</td>
                    <td>{{ $totals['total_op_qty'] }}</td>
                    <td colspan="2">{{ $totals['total_tags'] }} Tags</td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if(!$latestRate)
        <div class="alert alert-gold mt-4">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Metal rates not available. Please update them to calculate item values accurately.
        </div>
    @endif
</div>

@endsection
