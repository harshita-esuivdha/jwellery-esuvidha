@extends('company.dashboard')

@section('content')

@php
use Illuminate\Support\Facades\DB;
$cin = session('company_id'); 
// Fetch latest metal rates directly from DB
$latestRates = DB::table('metal_rates')->where('cin', $cin)->orderBy('rate_date', 'desc')->first();

// Fallback if table empty
$latestRates = $latestRates ?? (object)[
    'gold_24' => 0,
    'gold_22' => 0,
    'gold_18' => 0,
    'silver_999' => 0,
    'silver_925' => 0
];
@endphp

<div class="container">
    <h2 class="my-3">{{ isset($item) ? 'Update Item' : 'Add Item' }}</h2>

    <form method="POST" action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($item)) @method('PUT') @endif

        {{-- Row 1 --}}
        <div class="row g-3">
            <div class="col-md-4">
                <label>Item Name</label>
                <input type="text" name="item_name" class="form-control" value="{{ old('item_name', $item->item_name ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label>Item Prefix</label>
                <input type="text" name="item_prefix" class="form-control" value="{{ old('item_prefix', $item->item_prefix ?? '') }}">
            </div>
            <div class="col-md-4">
                <label>HSN Code</label>
                <input type="text" name="hsn_code" class="form-control" value="{{ old('hsn_code', $item->hsn_code ?? '') }}">
            </div>
        </div>

        {{-- Row 2 --}}
        <div class="row g-3 mt-2">
            <div class="col-md-3">
                <label>Short Name</label>
                <input type="text" name="short_name" class="form-control" value="{{ old('short_name', $item->short_name ?? '') }}">
            </div>
            <div class="col-md-3">
                <label>Category</label>
                <select name="item_group" class="form-control" required>
                    <option value="">Select Category</option>
                    @foreach(['Ring','Necklace','Earring','Bangle','Pendant','Bracelet','Slider','Cart'] as $cat)
                        <option value="{{ $cat }}" {{ old('item_group', $item->item_group ?? '') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Metal Type</label>
                <select name="metal_type" class="form-control" required>
                    <option value="">Select Metal</option>
                    @foreach(['Gold','Silver','Platinum','Diamond','Mixed'] as $metal)
                        <option value="{{ $metal }}" {{ old('metal_type', $item->metal_type ?? '') == $metal ? 'selected' : '' }}>{{ $metal }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Gold Karat</label>
                <select name="gold_karat" class="form-control">
                    <option value="">Select Karat</option>
                    @foreach([24,22,18,14] as $karat)
                        <option value="{{ $karat }}" {{ old('gold_karat', $item->gold_karat ?? '') == $karat ? 'selected' : '' }}>{{ $karat }}K</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Row 3 --}}
        <div class="row g-3 mt-2">
            <div class="col-md-2">
                <label>Tag Number</label>
                <input type="text" name="tag_number" class="form-control" value="{{ old('tag_number', $item->tag_number ?? '') }}">
            </div>
            <div class="col-md-2">
                <label>HUID</label>
                <input type="text" name="huid" class="form-control" value="{{ old('huid', $item->huid ?? '') }}">
            </div>
            <div class="col-md-2">
                <label>Gross Weight (g)</label>
                <input type="number" name="gross_weight" step="0.001" class="form-control" value="{{ old('gross_weight', $item->gross_weight ?? '') }}">
            </div>
            <div class="col-md-2">
                <label>Bead Weight (g)</label>
                <input type="number" name="bead_weight" step="0.001" class="form-control" value="{{ old('bead_weight', $item->bead_weight ?? '') }}">
            </div>
            <div class="col-md-2">
                <label>Diamond Weight (carat)</label>
                <input type="number" name="diamond_weight" step="0.001" class="form-control" value="{{ old('diamond_weight', $item->diamond_weight ?? '') }}">
            </div>
            <div class="col-md-2">
                <label>Diamond Rate / Carat</label>
                <input type="number" name="diamond_rate_per_carat" step="0.01" class="form-control" value="{{ old('diamond_rate_per_carat', $item->diamond_rate_per_carat ?? '') }}">
            </div>
            <div class="col-md-2">
                <label>Stock</label>
                <input type="number" name="stock" step="1" class="form-control" value="{{ old('stock', $item->stock ?? 0) }}" min="0">
            </div>
        </div>

        {{-- Row 4 --}}
        <div class="row g-3 mt-2">
            <div class="col-md-3">
                <label>Net Weight (g)</label>
                <input type="number" name="net_weight" step="0.001" class="form-control" value="{{ old('net_weight', $item->net_weight ?? '') }}">
            </div>
            <div class="col-md-3">
                <label>Op Qty</label>
                <select name="op_qty" class="form-control">
                    @foreach(['Single','Pair','Set'] as $op)
                        <option value="{{ $op }}" {{ old('op_qty', $item->op_qty ?? '') == $op ? 'selected' : '' }}>{{ $op }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Polish %</label>
                <input type="number" name="polish_percentage" step="0.01" class="form-control" value="{{ old('polish_percentage', $item->polish_percentage ?? '') }}">
            </div>
            <div class="col-md-3">
                <label>Tax Slab (%)</label>
                <input type="number" name="tax_slab" step="0.01" class="form-control" value="{{ old('tax_slab', $item->tax_slab ?? '') }}">
            </div>
        </div>

        {{-- Row 5 --}}
        <div class="row g-3 mt-2">
            <div class="col-md-3">
                <label>Image</label>
                <input type="file" name="image" class="form-control">
                @if(isset($item) && $item->image)
                    <img src="{{ url('public/items/'.$item->image) }}" alt="Item Image" class="mt-2" style="width:80px; height:80px; object-fit:cover;">
                @endif
            </div>

<div class="col-md-3">
    <label>Making Charges</label>
    <input type="number" 
           name="making" 
           id="making" 
           class="form-control" 
           value="{{ old('making', $item->making ?? 0) }}">
</div>

<div class="col-md-3">
    <label>Discount %</label>
    <input type="number" 
           name="discount" 
           id="discount" 
           class="form-control" 
           value="{{ old('discount', $item->discount ?? 0) }}">
</div>

            <div class="col-md-3">
                <label>Price</label>
                <input type="number" name="price" id="price" class="form-control" readonly>
            </div>
        </div>

        {{-- Submit --}}
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Update Item' : 'Add Item' }}</button>
            <button type="reset" class="btn btn-secondary">Clear</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
const latestRates = {
    gold_24: {{ $latestRates->gold_24 }},
    gold_22: {{ $latestRates->gold_22 }},
    gold_18: {{ $latestRates->gold_18 }},
    silver_999: {{ $latestRates->silver_999 }},
    silver_925: {{ $latestRates->silver_925 }}
};

document.addEventListener('DOMContentLoaded', function() {
    const metalTypeEl = document.querySelector('select[name="metal_type"]');
    const goldKaratEl = document.querySelector('select[name="gold_karat"]');
    const netWeightEl = document.querySelector('input[name="net_weight"]');
    const grossWeightEl = document.querySelector('input[name="gross_weight"]');
    const beadWeightEl = document.querySelector('input[name="bead_weight"]');
    const diamondWeightEl = document.querySelector('input[name="diamond_weight"]');
    const diamondRateEl = document.querySelector('input[name="diamond_rate_per_carat"]');
    const makingEl = document.getElementById('making');
    const discountEl = document.getElementById('discount');
    const priceEl = document.getElementById('price');

    function calculatePrice() {
        let netWeight = parseFloat(netWeightEl.value) || 0;
        const gross = parseFloat(grossWeightEl.value) || 0;
        const bead = parseFloat(beadWeightEl.value) || 0;
        const diamond = parseFloat(diamondWeightEl.value) || 0;

        if(gross>0) netWeight = gross - bead - diamond;
        netWeightEl.value = netWeight.toFixed(3);

        const making = parseFloat(makingEl.value) || 0;
        const discount = parseFloat(discountEl.value) || 0;
        const metalType = (metalTypeEl.value || '').toLowerCase();
        const goldKarat = parseInt(goldKaratEl.value) || 24;

        if(netWeight <=0){ priceEl.value=0; return; }

        let rate = 0;

        if(metalType==='gold'){
            switch(goldKarat){
                case 24: rate = latestRates.gold_24 * netWeight; break;
                case 22: rate = latestRates.gold_22 * netWeight; break;
                case 18: rate = latestRates.gold_18 * netWeight; break;
                default: rate = latestRates.gold_24 * netWeight;
            }
        } else if(metalType==='silver'){
            rate = latestRates.silver_999 * netWeight;
        }

        rate += making;
        const diamondRate = parseFloat(diamondRateEl.value) || 0;
        rate += diamond * diamondRate;

        rate -= rate * (discount / 100);

        priceEl.value = rate.toFixed(2);
    }

    [metalTypeEl, goldKaratEl, netWeightEl, grossWeightEl, beadWeightEl, diamondWeightEl, diamondRateEl, makingEl, discountEl].forEach(el=>{
        if(el){
            el.addEventListener('input', calculatePrice);
            el.addEventListener('change', calculatePrice);
        }
    });

    calculatePrice();
});
</script>

@endsection
