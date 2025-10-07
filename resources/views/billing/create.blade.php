@extends('company.dashboard')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
/* General invoice styling */
.invoice-preview {
    font-family: Arial, sans-serif;
    font-size: 9pt; /* smaller font */
    line-height: 1.2;
    color: #000;
}

/* Tables */
.invoice-preview table {
    width: 100%;
    border-collapse: collapse;
    font-size: 8.5pt; /* compact table font */
}

.invoice-preview table th,
.invoice-preview table td {
    border: 1px solid #000;
    padding: 3px 5px; /* smaller padding */
    text-align: center;
}

/* Totals section */
.invoice-preview .totals-table td {
    font-size: 8pt;
    padding: 3px 5px;
}
@media print {
    .btn-print,
    .remove-item,
    .remove-exchange .no-print {
        display: none !important;
    }
}
/* Exchange and other charges font smaller */
.invoice-preview #previewExchangeTable td,
.invoice-preview #previewExchangeTable th,
.invoice-preview #previewOtherTaxAmount,
.invoice-preview #previewExchangeTotal {
    font-size: 8pt;
}

/* Header and info section */
.invoice-preview .invoice-header div,
.invoice-preview .invoice-customer div {
    font-size: 8.5pt;
    margin-bottom: 2px;
}

/* Amount in words */
#amountInWords {
    font-size: 8pt;
}

/* Reduce spacing for signatures and footer */
.invoice-preview .d-flex > div {
    font-size: 8pt;
}

/* Print adjustments */
@media print {
  .btn-print,
  .remove-item,
  .remove-exchange,
  .no-print {
    display: none !important;
    visibility: hidden !important;
  }

  .table-container {
    height: auto !important;
    overflow: visible !important;
  }

  * {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }

  body {
    background: white !important;
  }

  table, th, td {
    border-color: #000 !important;
  }
}

#previewBillTable th:nth-child(1),
#previewBillTable td:nth-child(1) {
  width: 30px; /* Sl */
}

#previewBillTable th:nth-child(2),
#previewBillTable td:nth-child(2) {
  width: 30px; /* Item Name */
}

#previewBillTable th:nth-child(3),
#previewBillTable td:nth-child(3) {
  width: 50px; /* Purity */
}

#previewBillTable th:nth-child(4),
#previewBillTable td:nth-child(4) {
  width: 40px; /* Qty */
}

#previewBillTable th:nth-child(5),
#previewBillTable td:nth-child(5),
#previewBillTable th:nth-child(6),
#previewBillTable td:nth-child(6) {
  width: 40px; /* Net Wt, Gross Wt */
}

#previewBillTable th:nth-child(7),
#previewBillTable td:nth-child(7) {
  width: 45px; /* Rate/gm */
}

#previewBillTable th:nth-child(8),
#previewBillTable td:nth-child(8) {
  width: 74px; /* Gold Val */
}

#previewBillTable th:nth-child(9),
#previewBillTable td:nth-child(9) {
  width: 85px; /* Making/gm */
}

#previewBillTable th:nth-child(10),
#previewBillTable td:nth-child(10),
#previewBillTable th:nth-child(11),
#previewBillTable td:nth-child(11) {
  width: 55px; /* Disc %, GST % */
}

#previewBillTable th:nth-child(12),
#previewBillTable td:nth-child(12) {
  width: 90px; /* Total */
}

#previewBillTable th:nth-child(13),
#previewBillTable td:nth-child(13) {
  width: 50px; /* Action */
}
#previewBillTable {
  table-layout: fixed;
  width: 100%;
}
</style>


<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f5f6fa;
    font-size: 13px;
}

.container-flex {
    display: flex;
    gap: 15px;
    margin-top: 10px;
}

.left-panel, .right-panel {
    background: #fff;
    padding: 12px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.left-panel {
    width: 42%;
}

.right-panel {
    width: 58%;
    overflow-y: auto;
    max-height: 88vh;
}

label {
    font-weight: 600;
    font-size: 12px;
    margin-bottom: 2px;
}

input.form-control,
select.form-control {
    height: 30px !important;
    padding: 2px 6px;
    font-size: 12px;
}

h5, h6 {
    font-size: 14px;
    margin-bottom: 8px;
}

.mb-3, .mb-2 {
    margin-bottom: 6px !important;
}

.btn, .btn-add {
    font-size: 13px;
    padding: 4px 8px;
    height: 30px;
}

.btn-add {
    background: #198754;
    color: #fff;
    border: none;
    border-radius: 5px;
    width: 100%;
}

.invoice-container {
    background: #fff;
    border-radius: 8px;
    padding: 10px;
    font-size: 12px;
}

.invoice-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

.invoice-table th,
.invoice-table td {
    border: 1px solid #ccc;
    padding: 4px;
    text-align: center;
}

.totals-box {
    font-size: 12px;
    line-height: 1.5;
}

.totals-box .grand-total {
    font-size: 14px;
    font-weight: bold;
    color: #0d6efd;
}

.btn-print {
    background: #0d6efd;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 5px 10px;
    font-size: 13px;
    margin-top: 8px;
    width: 100%;
}

.select2-container--default .select2-selection--single {
    height: 30px;
    font-size: 12px;
    padding-top: 2px;
}

@media (max-width: 768px) {
    .container-flex { flex-direction: column; }
    .left-panel, .right-panel { width: 100%; }
}

.table-container {
  height: 200px;          /* fixed height */
  overflow-y: auto;       /* scroll when content exceeds */
  border: 1px solid #000; /* optional border */
}
@media print {
  .table-container {
    height: auto !important;     /* expand fully for print */
    overflow: visible !important;/* no scrollbars in print */
  }
}
.table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;    /* keeps column widths consistent */
}

.table tbody {
  display: table-row-group; /* default — keeps full width */
}
.table-container::after {
  content: "";
  display: block;
  height: 200px; /* ensures visual height */
  min-height: 100%;
}
</style>
<style>
@media print {
  /* Expand everything, remove scrollbars */
  .table-container {
    height: auto !important;
    overflow: visible !important;
  }

  /* --- Ensure colors and backgrounds print --- */
  * {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
    color-adjust: exact !important;
  }

  /* Optional: remove print button from printed output */
  .btn-print {
    display: none !important;
  }

  /* Optional: tighten spacing for print clarity */
  body {
    background: white !important;
  }

  /* Optional: ensure tables look solid in print */
  table, th, td {
    border-color: #534e4e !important;
  }
}

</style>

@php
$metalRate = DB::table('metal_rates')->latest('rate_date')->first();
@endphp
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('success'))
<script>
    alert("{{ session('success') }}");
    document.querySelector('.btn-print').click();
</script>
@endif
<div class="container-flex">

     LEFT PANEL 
    <div class="left-panel">
        <h5 class="mb-3">🧾 Invoice Entry</h5>

         Customer 
        <label>Billed To:</label>
        <select name="customer_id" id="customer" class="form-control select2 mb-3">
            <option value="">Select Customer</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}"
                    data-name="{{ $customer->name }}"
                    data-phone="{{ $customer->phone }}"
                    data-email="{{ $customer->email ?? '' }}"
                    data-address="{{ $customer->address ?? '' }}">
                    {{ $customer->name }} - {{ $customer->phone }}
                </option>
            @endforeach
        </select>

<label>Payment Mode:</label>
<select id="paymentModeSelect" class="form-control select2 mb-3">
    <option value="">Select Payment Mode</option>
    <option value="Cash">Cash</option>
    <option value="Card">Card</option>
    <option value="UPI">UPI</option>
    <option value="Wallet">Wallet</option>
    <option value="Exchange">Exchange</option>
</select>



<!-- Include jQuery & Select2 JS if not already included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    const $paymentSelect = $('#paymentModeSelect');
    const $paymentInput = $('#inputPaymentMode');

    // Initialize Select2
    $paymentSelect.select2();

    // Set initial value
    $paymentInput.val($paymentSelect.val());

    // Listen to Select2 change event
    $paymentSelect.on('change', function() {
        $paymentInput.val($(this).val());
    });
});
</script>



<div style="margin-bottom: 20px;">
    <label for="dueDateInput">Due Date:</label>
    <input type="date" id="dueDateInput" class="form-control" placeholder="Enter Due Date">
</div>

<div style="margin-bottom: 20px;">
    <label for="billerNameInput">Biller Name:</label>
    <input type="text" id="billerNameInput" class="form-control" placeholder="Enter Biller Name">
</div>

        <label>Item:</label>
        <select id="itemSelect" class="form-control select2 mb-3">
            <option value="">Select Item</option>
            @foreach($items as $item)
                <option 
    value="{{ $item->id }}"
    data-rate="{{ $item->rate ?? 0 }}"
    data-gross_weight="{{ $item->gross_weight ?? 0 }}"
    data-net_weight="{{ $item->net_weight ?? 0 }}"
    data-item_group="{{ $item->item_group ?? '' }}"
    data-metal_type="{{ $item->metal_type ?? '' }}"
    data-making="{{ $item->making ?? 0 }}"
    data-discount="{{ $item->discount ?? 0 }}"
>
    {{ $item->item_name }}
</option>



            @endforeach
        </select>

         Rate Details 
        <div class="row g-2 mb-2">
            <div class="col-md-2">
                <label>Qty</label>
                <input type="number" id="qty" class="form-control" value="1" min="1">
            </div>
            <div class="col-md-3">
                <label>Gold Rate/gm</label>
                <input type="number" id="rate" class="form-control" value="0" readonly>
            </div>
            <div class="col-md-3">
                <label>Making/gm</label>
                <input type="number" id="making" class="form-control" value="0">
            </div>
            <div class="col-md-3">
                <label>Discount %</label>
                <input type="number" id="discount" class="form-control" value="0">
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-md-3"><label>Item Group</label><input type="text" id="itemGroup" class="form-control" readonly></div>
            <div class="col-md-3"><label>Metal Type</label><input type="text" id="metalType" class="form-control" readonly></div>
            <div class="col-md-3"><label>Gross Wt</label><input type="number" id="grossWeight" class="form-control" readonly></div>
            <div class="col-md-3"><label>Net Wt</label><input type="number" id="netWeight" class="form-control" readonly></div>
        </div>

        <div class="mb-3">
            <label class="fw-bold text-success">Total Amount</label>
            <input type="text" id="totalAmount" class="form-control text-success fw-bold" readonly>
        </div>

        <button type="button" class="btn btn-primary w-100 mb-3" id="addItem">Add Item</button>

         Exchange 
     <h6>💱 Exchange Item</h6>
<div class="mb-3">
    <input type="text" id="exchangeItemName" class="form-control mb-2" placeholder="Item Name">
    <div class="row g-2 mb-2">
        <div class="col"><input type="text" id="exchangePurity" class="form-control" placeholder="Purity (e.g., 22K)"></div>
        <div class="col"><input type="number" id="exchangeWeight" class="form-control" placeholder="Weight (gm)" step="0.01"></div>
    </div>
    <div class="row g-2">
        <div class="col"><input type="number" id="exchangeRate" class="form-control" placeholder="Rate/gm" step="0.01"></div>
        <div class="col"><input type="number" id="exchangeWastage" class="form-control" placeholder="Wastage %" value="0" step="0.01"></div>
    </div>
</div>

<!-- Single input to show combined details -->


        <button type="button" class="btn-add" id="addExchangeItem">Add Exchange Item</button>

     <h6 class="mt-4">🧾 Other Tax</h6>
<div class="row g-2 mb-3">
    <div class="col">
        <input type="text" id="otherTaxName" class="form-control" placeholder="Tax Name">
    </div>
    <div class="col">
        <input type="number" id="otherTaxPercent" class="form-control" placeholder="Tax %" step="0.01">
    </div>
</div>

<!-- Preview span for Other Tax -->


  
        <h6>💰 Customer Payment</h6>
        <input type="number" id="customerPayment" class="form-control" placeholder="Amount Paid" min="0">


<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentInput = document.getElementById('customerPayment');
    const paymentCopyInput = document.getElementById('customerPaymentCopy');

    // Copy value as user types
    paymentInput.addEventListener('input', function() {
        paymentCopyInput.value = this.value;
    });

    // Initialize on page load
    paymentCopyInput.value = paymentInput.value;
});
</script>
    </div>

     RIGHT PANEL 
  <div class="right-panel invoice-preview">
    <h5 class="mb-2">🧾 Live Invoice Preview</h5>

    <div class="invoice-container" id="invoicePreview" style="border: 1px solid #000; padding: 8px; background: white; font-family: Arial, sans-serif; font-size: 8pt; line-height: 1.2;">
        
        <!-- Compact Header -->
        <div class="invoice-header" style="border-bottom: 2px solid #000; padding-bottom: 6px; margin-bottom: 6px; display: flex; justify-content: space-between; align-items: start;">
            <div style="flex: 0 0 25%;">
                <img src="{{ url('public/company/logo/' . $company->logo) }}" alt="LOGO" style="max-height: 70px; display: block;">
            </div>
            <div style="flex: 0 0 75%; display: grid; grid-template-columns: 1fr 1fr; gap: 3px 8px; font-size: 7.5pt;">
                <div><strong>Company:</strong> <span id="previewCompanyName">{{ session('company_name') ?? 'Company Name' }}</span></div>
                <div><strong>Bill No.:</strong> <span id="previewInvoiceNo">{{ $newBillNo }}</span></div>
                <div><strong>Address:</strong> {{ session('company_address') ?? $company->address ?? '' }}</div>
                <div><strong>Bill Date:</strong> <span id="previewDate">{{ date('d-M-Y') }}</span></div>
                <div><strong>Due Date:</strong> <span id="previewDueDate">-</span></div>
                <div><strong>Biller:</strong> <span id="previewSalesman">{{ auth()->user()->name ?? '-' }}</span></div>
            </div>
        </div>

        <!-- Compact Customer Info -->
        <div class="invoice-customer" style="border-bottom: 1px solid #000; padding-bottom: 4px; margin-bottom: 4px; display: grid; grid-template-columns: 1fr 1fr; gap: 2px 10px; font-size: 7.5pt;">
            <div><strong>Client:</strong> <span id="previewCustomerName">-</span></div>
            <div><strong>Bill Date:</strong> <span id="previewBillDate">{{ date('d-M-Y') }}</span></div>
            <div><strong>Email:</strong> <span id="previewCustomerEmail">-</span></div>
           
            <div><strong>Phone:</strong> <span id="previewCustomerPhone">-</span></div>
           
            <div style="grid-column: 1 / -1;"><strong>Address:</strong> <span id="previewCustomerAddress">-</span></div>
        </div>
        
        <!-- Section Header -->
        

        <!-- Compact Table -->
      <div class="table-container mb-2">
  <table class="table table-bordered " id="previewBillTable" style="width: 100%; border-collapse: collapse; font-size: 7pt;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid #000; padding: 2px; text-align: center;">Sl</th>
                    <th style="border: 1px solid #000; padding: 2px; text-align: center;">Item</th>
                    <th style="border: 1px solid #000; padding: 2px; text-align: center;">Purity</th>
                    <th style="border: 1px solid #000; padding: 2px; text-align: center;">Qty</th>
                    <th style="border: 1px solid #000; padding: 2px; text-align: center;">Net Wt</th>
                    <th style="border: 1px solid #000; padding: 2px; text-align: center;">Gross Wt</th>
                    <th style="border: 1px solid #000; padding: 2px; text-align: center;">Rate/gm</th>
                    <th style="border: 1px solid #000; padding: 2px; text-align: center;">Gold Val</th>
                    <th style="border: 1px solid #000; padding: 2px; text-align: center;">Making/gm</th>
                    <th style="border: 1px solid #000; padding: 2px; text-align: center;">Disc %</th>
                    <th style="border: 1px solid #000; padding: 2px; text-align: center;">GST %</th>
                    <th style="border: 1px solid #000; padding: 2px; text-align: center;">Total</th>
         <th class="no-print" style="border: 1px solid #000;">Action</th>


                </tr>
            </thead>
            <tbody ></tbody>
        </table>
    </div>
        <!-- Compact Totals -->
        <div style="display: flex; justify-content: flex-end; margin-bottom: 4px;">
            <div style="width: 45%;">
                <table style="width: 100%; border-collapse: collapse; font-size: 7.5pt;">
                    <tr style="background-color: #e0f7fa;">
                        <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">Sub-total (Gold)</td>
                        <td style="border: 1px solid #000; padding: 2px; text-align: right;">₹ <span id="previewSubTotal">0.00</span></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">Making Charges</td>
                        <td style="border: 1px solid #000; padding: 2px; text-align: right;">₹ <span id="previewMakingTotal">0.00</span></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">Total Gross Wt (gm)</td>
                        <td style="border: 1px solid #000; padding: 2px; text-align: right;"><span id="previewTotalGrossWeight">0.00</span> gm</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">Discount</td>
                        <td style="border: 1px solid #000; padding: 2px; text-align: right; color: red;">- ₹ <span id="previewDiscountTotal">0.00</span></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">GST @ 3%</td>
                        <td style="border: 1px solid #000; padding: 2px; text-align: right;">₹ <span id="previewGstTotal">0.00</span></td>
                    </tr>
                    <tr style="background-color: #a5d6a7;">
                        <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">New Purchase Total</td>
                        <td style="border: 1px solid #000; padding: 2px; text-align: right; font-weight: bold;">₹ <span id="previewNewPurchaseTotal">0.00</span></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Exchange Section -->
        <div id="exchangeContainer" style="display: none;">
            <div style="text-align: center; background-color: #000; color: #fff; padding: 2px 0; margin-bottom: 3px; margin-top: 4px; font-size: 8pt;">
                <strong>Old Gold Exchange</strong>
            </div>

            <table class="invoice-table exchange-table" id="previewExchangeTable" style="width: 100%; border-collapse: collapse; font-size: 7pt; margin-bottom: 4px;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="border: 1px solid #000; padding: 2px; text-align: center;">Sl</th>
                        <th style="border: 1px solid #000; padding: 2px; text-align: center;">Item</th>
                        <th style="border: 1px solid #000; padding: 2px; text-align: center;">Purity</th>
                        <th style="border: 1px solid #000; padding: 2px; text-align: center;">Net Wt (gm)</th>
                        <th style="border: 1px solid #000; padding: 2px; text-align: center;">Price/gm</th>
                        <th style="border: 1px solid #000; padding: 2px; text-align: center;">Wastage %</th>
                        <th style="border: 1px solid #000; padding: 2px; text-align: center;">Amount</th>
             <th class="no-print" style="border: 1px solid #000; padding: 2px; text-align: center;">Action</th>


                    </tr>
                </thead>
                 <tbody class="invoice-tbody" style="min-height: 80px;"></tbody>
            </table>
        </div>

        <!-- Final Totals Section -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 4px; gap: 8px;">
            <div style="flex: 0 0 35%;">
                <div style="border: 1px solid #000; padding: 3px; background-color: #f0f0f0; font-size: 7pt;">
                    <strong>Amount In Words:</strong>
                    <div id="amountInWords" style="min-height: 15px; margin-top: 2px;">-</div>
                </div>
            </div>
            <div style="flex: 0 0 63%;">
                <table style="width: 100%; border-collapse: collapse; font-size: 7.5pt;">
                    <tr>
                        <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">Exchange Sub Total</td>
                        <td style="border: 1px solid #000; padding: 2px; text-align: right; color: red;">- ₹ <span id="previewExchangeTotal">0.00</span></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">Other Charges (<span id="previewOtherTaxName">-</span>)</td>
                        <td style="border: 1px solid #000; padding: 2px; text-align: right; color: green;">+ ₹ <span id="previewOtherTaxAmount">0.00</span></td>
                    </tr>
                    <tr style="background-color: #ffcc80;">
                        <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">Grand Total:</td>
                        <td style="border: 1px solid #000; padding: 2px; text-align: right; font-weight: bold;">₹ <span id="previewGrandTotal">0.00</span></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">Amount Paid:</td>
                        <td style="border: 1px solid #000; padding: 2px; text-align: right;">₹ <span id="previewCustomerPaid">0.00</span></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">Balance Due:</td>
                        <td style="border: 1px solid #000; padding: 2px; text-align: right; font-weight: bold;">₹ <span id="previewBalanceDue">0.00</span></td>
                    </tr>
                    <script>
document.addEventListener('DOMContentLoaded', function() {
    const balanceSpan = document.getElementById('previewBalanceDue');
    const balanceInput = document.getElementById('inputBalanceDue');

    function syncBalance() {
        balanceInput.value = balanceSpan.textContent.trim();
    }

    // Initial sync
    syncBalance();

    // If the table value updates dynamically, observe changes
    const observer = new MutationObserver(syncBalance);
    observer.observe(balanceSpan, { childList: true, characterData: true, subtree: true });
});
</script>
                </table>
            </div>
        </div>

        <!-- Declaration -->
        <div style="border-top: 1px solid #000; padding-top: 3px; margin-bottom: 3px;">
            <strong style="display: block; margin-bottom: 2px; font-size: 7.5pt;">Declaration:</strong>
            <div style="min-height: 25px; border: 1px dashed #ccc; padding: 3px; font-size: 7pt;">-</div>
        </div>
        
        <!-- Signatures -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
            <div style="width: 45%; text-align: center; border-top: 1px solid #000; padding-top: 3px; font-size: 7.5pt;">Client's Signature</div>
            <div style="width: 45%; text-align: center; border-top: 1px solid #000; padding-top: 3px; font-size: 7.5pt;">Business Signature</div>
        </div>
        
        <!-- Thank You Message -->
        <div style="text-align: center; margin-bottom: 4px; font-size: 7.5pt;">Thanks for business with us! Please visit again!</div>

        <!-- Contact Details -->
        <div style="border: 1px solid #000; display: flex; font-size: 7pt;">
            <div style="width: 100%; text-align: center; background-color: #f2f2f2; padding: 2px; font-weight: bold;">Contact Details</div>
        </div>
        <div style="border: 1px solid #000; border-top: none; display: flex; font-size: 7pt;">
            <div style="width: 50%; padding: 3px; border-right: 1px solid #000;">Phone: <span id="previewCompanyPhone">{{ $company->mobile ?? '-' }}</span></div>
            <div style="width: 50%; padding: 3px;">Email: <span id="previewCompanyEmail">{{ $company->email ?? '-' }}</span></div>
        </div>
        
        <!-- Footer -->
        <div style="text-align: right; font-size: 6pt; margin-top: 3px;">Powered By <span style="color: red; font-weight: bold;">e-Suvidha</span></div>

        <!-- Print Button -->
    </div>
            <button class="btn-print mt-2" style="display: block; font-weight: bold; width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer; font-size: 8pt; margin-top: 6px; font-size: large!important;">🖨️ Print / Save  Invoice</button>

</div>

<!-- All JavaScript remains unchanged -->
<script>
    const dueDateInput = document.getElementById('dueDateInput');
    const billerNameInput = document.getElementById('billerNameInput');
    const previewDueDate = document.getElementById('previewDueDate');
    const previewSalesman = document.getElementById('previewSalesman');

    dueDateInput.addEventListener('input', function() {
        previewDueDate.textContent = this.value || '-';
    });

    billerNameInput.addEventListener('input', function() {
        previewSalesman.textContent = this.value || '{{ auth()->user()->name ?? "-" }}';
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otherTaxNameSpan = document.getElementById('previewOtherTaxName');
    const otherTaxAmountSpan = document.getElementById('previewOtherTaxAmount');
    const otherTaxInput = document.getElementById('inputOtherTax');

    function syncOtherTax() {
        const name = otherTaxNameSpan.textContent.trim();
        const amount = otherTaxAmountSpan.textContent.trim();
        otherTaxInput.value = name + ' - ₹ ' + amount;
    }

    syncOtherTax();

    const observer = new MutationObserver(syncOtherTax);
    observer.observe(otherTaxNameSpan, { childList: true, characterData: true, subtree: true });
    observer.observe(otherTaxAmountSpan, { childList: true, characterData: true, subtree: true });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const grandTotalSpan = document.getElementById('previewGrandTotal');
    const grandTotalInput = document.getElementById('inputGrandTotal');

    function syncGrandTotal() {
        grandTotalInput.value = grandTotalSpan.textContent.trim();
    }

    syncGrandTotal();

    const observer = new MutationObserver(syncGrandTotal);
    observer.observe(grandTotalSpan, { childList: true, characterData: true, subtree: true });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const balanceSpan = document.getElementById('previewBalanceDue');
    const balanceInput = document.getElementById('inputBalanceDue');

    function syncBalance() {
        balanceInput.value = balanceSpan.textContent.trim();
    }

    syncBalance();

    const observer = new MutationObserver(syncBalance);
    observer.observe(balanceSpan, { childList: true, characterData: true, subtree: true });
});
</script>
</div>




<form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
    @csrf

    <!-- Hidden Inputs to store values -->
    
<input type="hidden" name="cin_id" value={{ session('company_id') }}>
<input type="hidden" name="customer_id" id="inputCustomerId" value="">
<input type="hidden" name="due_date" id="duedate" value="">
<input type="hidden" name="bill_name" id="inputBillName" value="">
<input type="hidden" name="payment_mode" id="inputPaymentMode" class="form-control" placeholder="Selected Payment Mode" readonly>
<input type="hidden" name="bill_no" value="{{ $newBillNo }}">
<input type="hidden" id="inputItemId" name="items" class="form-control" placeholder="Selected Item ID" readonly>

<input type="hidden" id="inputOtherTax" name="other_charges" placeholder="Other Tax" readonly class="form-control mb-2">
<input type="hidden" id="exchangeSummary" name="exchange_summary" readonly class="form-control mb-2" placeholder="Exchange Item Details">
<input type="hidden" id="customerPaymentCopy" name="paid_amount" class="form-control mb-2" placeholder="Amount Paid (Copy)" readonly>
<input type="hidden" id="inputBalanceDue" name="due_amount" class="form-control mb-2" placeholder="Balance Due" readonly>
<input type="hidden" id="inputGrandTotal" name="grand_total" class="form-control mb-2" placeholder="Grand Total" readonly>


</form>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const dueDateInput = document.getElementById('dueDateInput');
    const billerNameInput = document.getElementById('billerNameInput');

    const previewDueDate = document.getElementById('previewDueDate');
    const previewSalesman = document.getElementById('previewSalesman');

    const hiddenDueDate = document.getElementById('duedate');
    const hiddenBillName = document.getElementById('inputBillName');

    // Update preview + hidden input for Due Date
    dueDateInput.addEventListener('input', function() {
        previewDueDate.textContent = this.value || '-';
        hiddenDueDate.value = this.value;
    });

    // Update preview + hidden input for Biller Name
    billerNameInput.addEventListener('input', function() {
        previewSalesman.textContent = this.value || '-';
        hiddenBillName.value = this.value;
    });

    // Initialize hidden fields with default values
    hiddenDueDate.value = dueDateInput.value;
    hiddenBillName.value = billerNameInput.value;
});
</script>













<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function() {
    $('.select2').select2({ width: '100%' });

    // <CHANGE> Metal rates from database
    const metalRates = {
        gold_24: {{ $metalRate->gold_24 ?? 0 }},
        gold_22: {{ $metalRate->gold_22 ?? 0 }},
        gold_18: {{ $metalRate->gold_18 ?? 0 }},
        silver_999: {{ $metalRate->silver_999 ?? 0 }},
        silver_925: {{ $metalRate->silver_925 ?? 0 }}
    };

    let billItems = [], exchangeItems = [];
    const GST_RATE = 3; // %

    // <CHANGE> Improved rate calculation based on metal type and purity
    function getRatePerGram(metalType, itemGroup) {
        metalType = (metalType || '').toLowerCase();
        itemGroup = (itemGroup || '').toLowerCase();

        if (metalType.includes('gold')) {
            if (itemGroup.includes('24') || itemGroup.includes('24k')) return metalRates.gold_24;
            if (itemGroup.includes('22') || itemGroup.includes('22k')) return metalRates.gold_22;
            if (itemGroup.includes('18') || itemGroup.includes('18k')) return metalRates.gold_18;
            return metalRates.gold_22; // default to 22K
        }
        if (metalType.includes('silver')) {
            if (itemGroup.includes('925')) return metalRates.silver_925;
            return metalRates.silver_999; // default to 999
        }
        return 0;
    }

    // Calculate item values
function calculateItemValues() {
    const $selected = $('#itemSelect option:selected');

    const itemId = $selected.val() || ''; // Item ID
    const itemName = $selected.text().trim(); // Item name
    const itemGroup = $('#itemGroup').val() || $selected.data('item_group') || '';
    const metalType = $('#metalType').val() || $selected.data('metal_type') || '';
    const netWeight = parseFloat($('#netWeight').val()) || parseFloat($selected.data('net_weight')) || 0;
    const grossWeight = parseFloat($('#grossWeight').val()) || parseFloat($selected.data('gross_weight')) || 0;
    const qty = parseFloat($('#qty').val()) || 1;
    const makingPerGram = parseFloat($('#making').val()) || 0;
    const discountPercent = parseFloat($('#discount').val()) || 0;

    // Calculate rate per gram based on metal type and purity
    const ratePerGram = getRatePerGram(metalType, itemGroup);

    // Gold value = rate per gram × net weight × quantity
    const goldValue = ratePerGram * netWeight * qty;

    // Making charges = making per gram × net weight × quantity
    const makingValue = makingPerGram * netWeight * qty;

    // Base amount before discount
    const baseAmount = goldValue + makingValue;

    // Discount amount
    const discountAmount = baseAmount * (discountPercent / 100);

    // Amount after discount
    const amountAfterDiscount = baseAmount - discountAmount;

    // GST calculation
    const gstAmount = amountAfterDiscount * (GST_RATE / 100);

    // Final total
    const total = amountAfterDiscount + gstAmount;

    // Update UI fields if needed
    $('#rate').val(ratePerGram.toFixed(2));
    $('#totalAmount').val(total.toFixed(2));

    return {
        id: itemId,
        item: itemName,
        itemGroup,
        metalType,
        qty,
        netWeight,
        grossWeight,
        goldRate: ratePerGram,
        goldValue,
        makingPerGram,
        makingValue,
        discountPercent,
        discountAmount,
        gstAmount,
        total
    };
}

// Render bill table
function renderBillTable() {
    const $tbody = $('#previewBillTable tbody').empty();

    // Collect all item IDs
    const itemIds = [];
const billItemsData = []; // Array to store {id, qty}

billItems.forEach((i, idx) => {
    $tbody.append(`
        <tr data-index="${idx}">
            <td>${idx + 1}</td>
            <td>${i.item}</td>
            <td>${i.itemGroup}</td>
            <td>${i.qty}</td>
            <td>${i.netWeight.toFixed(2)}</td>
            <td>${i.grossWeight.toFixed(2)}</td>
            <td>₹${i.goldRate.toFixed(2)}</td>
            <td>₹${i.goldValue.toFixed(2)}</td>
            <td>₹${i.makingPerGram.toFixed(2)}</td>
            <td>${i.discountPercent}%</td>
            <td>${GST_RATE}%</td>
            <td>₹${i.total.toFixed(2)}</td>
            <td><button class="btn btn-sm btn-danger remove-item">❌</button></td>
        </tr>
    `);

    // Push {id, qty} to billItemsData array
    billItemsData.push({ id: i.id, qty: i.qty });
});
    // Update hidden/readonly input with comma-separated IDs
    $('#inputItemId').val(JSON.stringify(billItemsData));
}


    // <CHANGE> Render exchange table with wastage calculation
  function renderExchangeTable() {
    const $tbody = $('#previewExchangeTable tbody').empty();

    if (exchangeItems.length === 0) {
        $('#exchangeContainer').hide(); // hide table if no items
        $('#exchangeSummary').val(''); // clear input
        return;
    }

    $('#exchangeContainer').show(); // show table if items exist

    // Array to store all exchange item details
    const exchangeDetails = [];

    exchangeItems.forEach((i, idx) => {
        $tbody.append(`
            <tr data-index="${idx}">
                <td>${idx + 1}</td>
                <td>${i.name}</td>
                <td>${i.purity}</td>
                <td>${i.weight.toFixed(2)}</td>
                <td>₹${i.rate.toFixed(2)}</td>
                <td>${i.wastagePercent}%</td>
                <td>₹${i.total.toFixed(2)}</td>
                <td><button class="btn btn-sm btn-danger remove-exchange">❌</button></td>
            </tr>
        `);

        // Prepare a string representation of this item
        const detailString = `${i.name} (Purity: ${i.purity}, Wt: ${i.weight.toFixed(2)}gm, Rate: ₹${i.rate.toFixed(2)}, Wastage: ${i.wastagePercent}%, Total: ₹${i.total.toFixed(2)})`;

        // Add to array
        exchangeDetails.push(detailString);
    });

    // Join all items with semicolon or comma
    $('#exchangeSummary').val(exchangeDetails.join(' | '));
}


    // <CHANGE> Complete totals calculation with all fields
 function calculateTotals() {
    let subTotal = 0, makingTotal = 0, discountTotal = 0, gstTotal = 0, totalGrossWeight = 0;

    // Calculate totals from bill items
    billItems.forEach(i => {
        subTotal += i.goldValue;
        makingTotal += i.makingValue;
        discountTotal += i.discountAmount;
        gstTotal += i.gstAmount;
        totalGrossWeight += i.grossWeight * i.qty;
    });

    // New purchase total
    const newPurchaseTotal = subTotal + makingTotal - discountTotal + gstTotal;

    // Exchange total
    const exchangeTotal = exchangeItems.reduce((sum, i) => sum + i.total, 0);

    // Grand total before other charges
    let grandTotal = newPurchaseTotal - exchangeTotal;

    // Other Charges / Tax
    const otherTaxPercent = parseFloat($('#otherTaxPercent').val()) || 0;
    const otherTaxName = $('#otherTaxName').val().trim() || '-';
    const otherTaxAmount = (grandTotal * otherTaxPercent) / 100;
    grandTotal += otherTaxAmount;

    // Customer payment
    const paid = parseFloat($('#customerPayment').val()) || 0;
    const balance = grandTotal - paid;

    // Update preview fields
    $('#previewSubTotal').text(subTotal.toFixed(2));
    $('#previewMakingTotal').text(makingTotal.toFixed(2));
    $('#previewDiscountTotal').text(discountTotal.toFixed(2));
    $('#previewGstTotal').text(gstTotal.toFixed(2));
    $('#previewTotalGrossWeight').text(totalGrossWeight.toFixed(2));
    $('#previewNewPurchaseTotal').text(newPurchaseTotal.toFixed(2));
    $('#previewExchangeTotal').text(exchangeTotal.toFixed(2));
    $('#previewOtherTaxName').text(otherTaxName);
    $('#previewOtherTaxAmount').text(otherTaxAmount.toFixed(2));
    $('#previewGrandTotal').text(grandTotal.toFixed(2));
    $('#previewCustomerPaid').text(paid.toFixed(2));
    $('#previewBalanceDue').text(balance.toFixed(2));

    // Convert amount to words
    $('#amountInWords').text(numberToWords(grandTotal));
}

// Recalculate totals whenever Other Tax changes
$('#otherTaxPercent, #otherTaxName').on('input', calculateTotals);


    // <CHANGE> Simple number to words converter
    function numberToWords(num) {
        if (num === 0) return 'Zero Rupees Only';
        const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        const teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        
        let words = '';
        const crore = Math.floor(num / 10000000);
        const lakh = Math.floor((num % 10000000) / 100000);
        const thousand = Math.floor((num % 100000) / 1000);
        const hundred = Math.floor((num % 1000) / 100);
        const remainder = Math.floor(num % 100);
        
        if (crore > 0) words += ones[crore] + ' Crore ';
        if (lakh > 0) words += (lakh < 10 ? ones[lakh] : tens[Math.floor(lakh / 10)] + ' ' + ones[lakh % 10]) + ' Lakh ';
        if (thousand > 0) words += (thousand < 10 ? ones[thousand] : tens[Math.floor(thousand / 10)] + ' ' + ones[thousand % 10]) + ' Thousand ';
        if (hundred > 0) words += ones[hundred] + ' Hundred ';
        if (remainder >= 10 && remainder < 20) words += teens[remainder - 10] + ' ';
        else if (remainder >= 20) words += tens[Math.floor(remainder / 10)] + ' ' + ones[remainder % 10] + ' ';
        else if (remainder > 0) words += ones[remainder] + ' ';
        
        return words.trim() + ' Rupees Only';
    }

    // <CHANGE> Customer selection handler
 // When customer is selected, fetch ID and update hidden input + preview
$('#customer').on('change', function() {
    const selected = $(this).find('option:selected');
    const customerId = selected.val(); // ✅ Fetch customer ID
    const name = selected.data('name') || '-';
    const phone = selected.data('phone') || '-';
    const email = selected.data('email') || '-';
    const address = selected.data('address') || '-';

    // Set hidden input for form submission
    $('#inputCustomerId').val(customerId);

    // Update invoice preview section live
    $('#previewCustomerName').text(name);
    $('#previewCustomerPhone').text(phone);
    $('#previewCustomerEmail').text(email);
    $('#previewCustomerAddress').text(address);
});


    // Item selection handler
  $('#itemSelect').on('change', function() {
    const $selected = $(this).find(':selected');

    $('#netWeight').val($selected.data('net_weight') || 0);
    $('#grossWeight').val($selected.data('gross_weight') || 0);
    $('#itemGroup').val($selected.data('item_group') || '');
    $('#metalType').val($selected.data('metal_type') || '');
    
    // NEW: Populate making and discount fields
    $('#making').val($selected.data('making') || 0);
    $('#discount').val($selected.data('discount') || 0);

    calculateItemValues();
});

    // Recalculate on input changes
    $('#qty, #netWeight, #making, #discount').on('input', calculateItemValues);

    // Add item to bill
    $('#addItem').on('click', function() {
        if (!$('#itemSelect').val()) {
            alert('Please select an item');
            return;
        }
        const vals = calculateItemValues();
        billItems.push(vals);
        renderBillTable();
        calculateTotals();
        
        // Reset form
        $('#itemSelect').val('').trigger('change');
        $('#qty').val(1);
        $('#making').val(0);
        $('#discount').val(0);
        $('#netWeight, #grossWeight, #itemGroup, #metalType, #rate, #totalAmount').val('');
    });

    // Remove item from bill
    $('#previewBillTable').on('click', '.remove-item', function() {
        const idx = $(this).closest('tr').data('index');
        billItems.splice(idx, 1);
        renderBillTable();
        calculateTotals();
    });

    // <CHANGE> Add exchange item with wastage calculation
    $('#addExchangeItem').on('click', function() {
        const name = $('#exchangeItemName').val().trim();
        const purity = $('#exchangePurity').val().trim();
        const weight = parseFloat($('#exchangeWeight').val()) || 0;
        const rate = parseFloat($('#exchangeRate').val()) || 0;
        const wastagePercent = parseFloat($('#exchangeWastage').val()) || 0;
        
        if (!name || weight <= 0 || rate <= 0) {
            alert('Please enter valid exchange item details');
            return;
        }
        
        // Calculate total with wastage deduction
        const wastageAmount = (weight * rate) * (wastagePercent / 100);
        const total = (weight * rate) - wastageAmount;
        
        exchangeItems.push({ name, purity, weight, rate, wastagePercent, total });
        renderExchangeTable();
        calculateTotals();
        
        // Reset form
        $('#exchangeItemName, #exchangePurity, #exchangeWeight, #exchangeRate, #exchangeWastage').val('');
    });

    // Remove exchange item
    $('#previewExchangeTable').on('click', '.remove-exchange', function() {
        const idx = $(this).closest('tr').data('index');
        exchangeItems.splice(idx, 1);
        renderExchangeTable();
        calculateTotals();
    });

    // Update totals when customer payment changes
    $('#customerPayment').on('input', calculateTotals);

    // Initialize
    calculateItemValues();
    calculateTotals();
});










</script>
<script>
$(document).ready(function() {

    // Click on Print button triggers form submission
    $('.btn-print').on('click', function() {
        $('#invoiceForm').submit();
    });

    $('#invoiceForm').on('submit', function(e) {
        e.preventDefault(); // prevent normal submit

        // --- CUSTOMER & BILL INFO ---
        $('#inputBillName').val($('#previewCustomerName').text().trim());
        $('#inputDueDate').val($('#previewDueDate').text().trim());
        $('#inputCustomerId').val($('#customer').val() || '');

        // --- BILL ITEMS (New Purchase) ---
        let safeBillItems = typeof billItems !== 'undefined' ? billItems : [];
        $('#inputItems').val(JSON.stringify(safeBillItems));

        // --- EXCHANGE ITEMS ---
        let safeExchangeItems = (typeof exchangeItems !== 'undefined' && exchangeItems.length > 0) ? exchangeItems : [];

        // --- OTHER CHARGES + EXCHANGE INFO ---
        let otherCharges = {
            name: $('#otherTaxName').val()?.trim() || '-',
            percent: parseFloat($('#otherTaxPercent').val()) || 0,
            amount: parseFloat($('#previewOtherTaxAmount').text()) || 0,
            exchange: safeExchangeItems
        };
        $('#inputOtherCharges').val(JSON.stringify(otherCharges));

        // --- PAYMENT & TOTALS ---
        $('#inputPaidAmount').val(parseFloat($('#customerPayment').val()) || 0);
        $('#inputDueAmount').val(parseFloat($('#previewBalanceDue').text()) || 0);
        $('#inputGrandTotal').val(parseFloat($('#previewGrandTotal').text()) || 0);

        // --- Submit form via AJAX ---
        let form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                // ✅ Show alert first
                alert('Invoice saved successfully!');

                // Then trigger print
                printInvoice();
            },
            error: function(err) {
                alert('Form submission failed!');
                console.error(err);
            }
        });
    });

    // --- Print Function ---
    function printInvoice() {
        const invoicePreview = document.getElementById('invoicePreview');
        if (!invoicePreview) {
            alert("Invoice content not found!");
            return;
        }

        const iframe = document.createElement('iframe');
        iframe.style.position = 'absolute';
        iframe.style.width = '0';
        iframe.style.height = '0';
        iframe.style.border = '0';
        document.body.appendChild(iframe);

        const doc = iframe.contentWindow.document;
        doc.open();
        doc.write('<!DOCTYPE html><html><head><title>Invoice</title>');

        // Copy styles
        Array.from(document.querySelectorAll('link[rel="stylesheet"], style')).forEach(styleEl => {
            doc.write(styleEl.outerHTML);
        });

        // Optional print-specific styles
        doc.write(`<style>
            @page { size: A4; margin: 20mm; }
            body { margin: 0; padding: 0; font-family: Arial, sans-serif; }
            #invoicePreview { display: flex; flex-direction: column; justify-content: flex-start; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        </style>`);

        doc.write('</head><body>');
        doc.write(invoicePreview.outerHTML);
        doc.write('</body></html>');
        doc.close();

        iframe.contentWindow.focus();
        iframe.contentWindow.print();

        setTimeout(() => {
            document.body.removeChild(iframe);
        }, 1000);
    }

});
</script>





@endsection