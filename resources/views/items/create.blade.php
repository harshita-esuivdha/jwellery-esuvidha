@extends('company.dashboard')

@section('content')



<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-primary">
            <i class="fas fa-box-open me-2"></i> {{ isset($item) ? 'Update Item' : 'Add New Item' }}
        </h2>
        <a href="{{ route('items.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-list me-1"></i> Back to Items
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST"
                action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}"
                enctype="multipart/form-data">
                @csrf
                @if(isset($item)) @method('PUT') @endif

                <h4 class="mb-3 text-secondary border-bottom pb-2">Basic Details</h4>
                <div class="row g-4">
                    <div class="col-md-4">
                        <label for="itemName" class="form-label required">Item Name</label>
                        <input type="text" name="item_name" id="itemName" class="form-control"
                            value="{{ old('item_name', $item->item_name ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="itemPrefix" class="form-label">Item Prefix</label>
                        <input type="text" name="item_prefix" id="itemPrefix" class="form-control"
                            value="{{ old('item_prefix', $item->item_prefix ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="hsnCode" class="form-label">HSN Code</label>
                        <input type="text" name="hsn_code" id="hsnCode" class="form-control"
                            value="{{ old('hsn_code', $item->hsn_code ?? '') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="shortName" class="form-label">Short Name</label>
                        <input type="text" name="short_name" id="shortName" class="form-control"
                            value="{{ old('short_name', $item->short_name ?? '') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="category" class="form-label required">Category</label>
                        <input list="categories" name="item_group" id="category" class="form-control"
                            value="{{ old('item_group', $item->item_group ?? '') }}" required>
                        <datalist id="categories">
                            @foreach(['Ring','Necklace','Earring','Bangle','Pendant','Bracelet','Slider','Cart'] as $cat)
                            <option value="{{ $cat }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-md-3">
                        <label for="metalType" class="form-label required">Metal Type</label>
                        <select name="metal_type" id="metalType" class="form-select" required>
                            <option value="">Select Metal</option>
                            @foreach(['Gold','Silver','Platinum','Diamond','Mixed'] as $metal)
                            <option value="{{ $metal }}"
                                {{ old('metal_type', $item->metal_type ?? '') == $metal ? 'selected' : '' }}>
                                {{ $metal }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                 @php
use Illuminate\Support\Facades\DB;

$cin = session('company_id');
$latestRates = DB::table('metal_rates')
    ->where('cin', $cin)
    ->orderBy('rate_date', 'desc')
    ->first() ?? (object)[
        'gold_24' => 0,
        'gold_22' => 0,
        'gold_18' => 0,
        'silver_999' => 0,
        'silver_925' => 0,
    ];
@endphp


<div class="col-md-3">
    <label for="goldKarat" class="form-label">Karat / Purity</label>
    <select name="gold_karat" id="goldKarat" class="form-select">
        <option value="">Select Karat</option>
        {{-- Default options for page load --}}
        @foreach([24,22,18,14] as $karat)
        <option value="{{ $karat }}" {{ old('gold_karat', $item->gold_karat ?? '') == $karat ? 'selected' : '' }}>
            {{ $karat }}K
        </option>
        @endforeach
    </select>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const metalTypeSelect = document.getElementById('metalType');
    const karatSelect = document.getElementById('goldKarat');

    const goldOptions = [24, 22, 18, 14].map(k => `<option value="${k}">${k}K</option>`).join('');
    const silverOptions = [999, 925].map(k => `<option value="${k}">${k}</option>`).join('');

    // Function to refresh karat list based on metal type
    function updateKaratOptions() {
        const metal = (metalTypeSelect.value || '').toLowerCase();
        karatSelect.innerHTML = '<option value="">Select Karat</option>';

        if (metal === 'gold') {
            karatSelect.innerHTML += goldOptions;
        } else if (metal === 'silver') {
            karatSelect.innerHTML += silverOptions;
        }
    }

    // Initialize on load
    updateKaratOptions();

    // Update when metal changes
    metalTypeSelect.addEventListener('change', updateKaratOptions);
});
</script>

                </div>

                <hr class="my-4">

                <h4 class="mb-3 text-secondary border-bottom pb-2">Inventory & Weights</h4>
                <div class="row g-4">
                    <div class="col-md-2">
                        <label for="tagNumber" class="form-label">Tag Number</label>
                        <input type="text" name="tag_number" id="tagNumber" class="form-control"
                            value="{{ old('tag_number', $item->tag_number ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="huid" class="form-label">HUID</label>
                        <input type="text" name="huid" id="huid" class="form-control"
                            value="{{ old('huid', $item->huid ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="grossWeight" class="form-label">Gross Weight (g)</label>
                        <input type="number" name="gross_weight" id="grossWeight" step="0.001" class="form-control"
                            value="{{ old('gross_weight', $item->gross_weight ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="beadWeight" class="form-label">Bead Weight (g)</label>
                        <input type="number" name="bead_weight" id="beadWeight" step="0.001" class="form-control"
                            value="{{ old('bead_weight', $item->bead_weight ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="netWeight" class="form-label">Net Weight (g)</label>
                        <input type="number" name="net_weight" id="netWeight" step="0.001" class="form-control bg-light"
                            value="{{ old('net_weight', $item->net_weight ?? '') }}" readonly>
                        <small class="form-text text-muted">Gross - Bead - Diamond</small>
                    </div>
                    <div class="col-md-2">
                        <label for="stock" class="form-label">Stock Qty</label>
                        <input type="number" name="stock" id="stock" step="1" class="form-control"
                            value="{{ old('stock', $item->stock ?? 0) }}" min="0">
                    </div>
                </div>

                <hr class="my-4">

                <h4 class="mb-3 text-secondary border-bottom pb-2">Pricing & Miscellaneous</h4>
                <div class="row g-4">
                    <div class="col-md-3">
                        <label for="diamondWeight" class="form-label">Diamond Weight (carat)</label>
                        <input type="number" name="diamond_weight" id="diamondWeight" step="0.001" class="form-control"
                            value="{{ old('diamond_weight', $item->diamond_weight ?? '') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="diamondRate" class="form-label">Diamond Rate / Carat</label>
                        <input type="number" name="diamond_rate_per_carat" id="diamondRate" step="0.01"
                            class="form-control"
                            value="{{ old('diamond_rate_per_carat', $item->diamond_rate_per_carat ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="opQty" class="form-label">Op Qty</label>
                        <select name="op_qty" id="opQty" class="form-select">
                            @foreach(['Single','Pair','Set'] as $op)
                            <option value="{{ $op }}"
                                {{ old('op_qty', $item->op_qty ?? '') == $op ? 'selected' : '' }}>
                                {{ $op }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="polishPercentage" class="form-label">Polish %</label>
                        <input type="number" name="polish_percentage" id="polishPercentage" step="0.01"
                            class="form-control" value="{{ old('polish_percentage', $item->polish_percentage ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="taxSlab" class="form-label">Tax Slab (%)</label>
                        <input type="number" name="tax_slab" id="taxSlab" step="0.01" class="form-control"
                            value="{{ old('tax_slab', $item->tax_slab ?? '') }}">
                    </div>
                </div>

                <hr class="my-4">

                <h4 class="mb-3 text-secondary border-bottom pb-2">Financials & Media</h4>
                <div class="row g-4">
                    <div class="col-md-3">
                        <label for="making" class="form-label">Making Charges</label>
                        <input type="number" name="making" id="making" class="form-control" step="0.01"
                            value="{{ old('making', $item->making ?? 0) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="discount" class="form-label">Discount %</label>
                        <input type="number" name="discount" id="discount" class="form-control" step="0.01"
                            value="{{ old('discount', $item->discount ?? 0) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="price" class="form-label">Final Price</label>
                        <input type="text" name="price" id="price" class="form-control form-control-lg bg-info-subtle"
                            value="0.00" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="image" class="form-label">Item Image</label>
                        <input type="file" name="image" id="image" class="form-control">
                        @if(isset($item) && $item->image)
                        <div class="mt-2">
                            <small class="d-block text-muted">Current Image:</small>
                            <img src="{{ url('public/items/'.$item->image) }}" alt="Item Image"
                                class="img-thumbnail rounded" style="width:80px; height:80px; object-fit:cover;">
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-5 pt-3 border-top text-end">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="fas fa-redo me-1"></i> Clear Form
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-1"></i> {{ isset($item) ? 'Update Item' : 'Add Item' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Note: Assuming Font Awesome is available for the icons (e.g., fas fa-box-open)

    const latestRates = {
        gold_24: {{ $latestRates->gold_24 }},
        gold_22: {{ $latestRates->gold_22 }},
        gold_18: {{ $latestRates->gold_18 }},
        silver_999: {{ $latestRates->silver_999 }},
        silver_925: {{ $latestRates->silver_925 }}
    };

    document.addEventListener('DOMContentLoaded', function() {
        const metalTypeEl = document.getElementById('metalType');
        const goldKaratEl = document.getElementById('goldKarat');
        const netWeightEl = document.getElementById('netWeight');
        const grossWeightEl = document.getElementById('grossWeight');
        const beadWeightEl = document.getElementById('beadWeight');
        const diamondWeightEl = document.getElementById('diamondWeight');
        const diamondRateEl = document.getElementById('diamondRate');
        const makingEl = document.getElementById('making');
        const discountEl = document.getElementById('discount');
        const priceEl = document.getElementById('price');

        function calculatePrice() {
            let gross = parseFloat(grossWeightEl.value) || 0;
            let bead = parseFloat(beadWeightEl.value) || 0;
            let diamond = parseFloat(diamondWeightEl.value) || 0;
            let netWeight = parseFloat(netWeightEl.value) || 0; // Read existing value if calculation fails

            // 1. Calculate Net Weight (if gross weight is provided)
            if (gross > 0) {
                netWeight = gross - bead - diamond;
                netWeightEl.value = netWeight.toFixed(3);
            } else {
                // If only net weight is entered, use that directly
                grossWeightEl.value = (netWeight + bead + diamond).toFixed(3);
            }

            const making = parseFloat(makingEl.value) || 0;
            const discount = parseFloat(discountEl.value) || 0;
            const metalType = (metalTypeEl.value || '').toLowerCase();
            const goldKarat = parseInt(goldKaratEl.value) || 24;
            const diamondRate = parseFloat(diamondRateEl.value) || 0;

            if (netWeight <= 0 && diamond <= 0) {
                priceEl.value = making.toFixed(2); // If only making charges apply
                return;
            }

            let metalCost = 0;

            // 2. Calculate Metal Cost
            if (metalType === 'gold') {
                switch (goldKarat) {
                    case 24: metalCost = latestRates.gold_24 * netWeight; break;
                    case 22: metalCost = latestRates.gold_22 * netWeight; break;
                    case 18: metalCost = latestRates.gold_18 * netWeight; break;
                    default: metalCost = latestRates.gold_24 * netWeight; // Fallback
                }
            } else if (metalType === 'silver') {
                // Assuming latestRates.silver_999 is the base rate per gram
                metalCost = latestRates.silver_999 * netWeight;
            }
            // Add other metals (Platinum, etc.) if applicable

            // 3. Calculate Diamond Cost
            const diamondCost = diamond * diamondRate;

            // 4. Total Base Cost (Metal + Diamond)
            let totalCost = metalCost + diamondCost;

            // 5. Add Making Charges
            totalCost += making;

            // 6. Apply Discount
            totalCost -= totalCost * (discount / 100);

            // 7. Update Price Field
            priceEl.value = totalCost.toFixed(2);
        }

        // Attach listeners to all relevant fields
        [metalTypeEl, goldKaratEl, netWeightEl, grossWeightEl, beadWeightEl, diamondWeightEl, diamondRateEl, makingEl, discountEl].forEach(el => {
            if (el) {
                el.addEventListener('input', calculatePrice);
                el.addEventListener('change', calculatePrice);
            }
        });

        // Run on load to populate the price field
        calculatePrice();
    });
</script>

<style>
    /* Basic custom CSS for required field indicator (assuming no specific error display logic yet) */
    .form-label.required:after {
        content: " *";
        color: red;
    }
</style>

@endsection