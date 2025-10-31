@extends('company.dashboard')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Consolidated all CSS into single optimized style block -->
<style>
/* General invoice styling */
.invoice-preview {
  font-family: Arial, sans-serif;
  font-size: 9pt;
  line-height: 1.2;
  color: #000;
}

/* Tables */
.invoice-preview table {
  width: 100%;
  border-collapse: collapse;
  font-size: 8.5pt;
}

.invoice-preview table th,
.invoice-preview table td {
  border: 1px solid #000;
  padding: 3px 5px;
  text-align: center;
}

.invoice-preview .totals-table td {
  font-size: 8pt;
  padding: 3px 5px;
}

.invoice-preview #previewExchangeTable td,
.invoice-preview #previewExchangeTable th,
.invoice-preview #previewOtherTaxAmount,
.invoice-preview #previewExchangeTotal {
  font-size: 8pt;
}

.invoice-preview .invoice-header div,
.invoice-preview .invoice-customer div {
  font-size: 8.5pt;
  margin-bottom: 2px;
}

#amountInWords {
  font-size: 8pt;
}

.invoice-preview .d-flex > div {
  font-size: 8pt;
}

#previewBillTable {
  table-layout: fixed;
  width: 100%;
}

/* Column widths */
#previewBillTable th:nth-child(1),
#previewBillTable td:nth-child(1) { width: 30px; }

#previewBillTable th:nth-child(2),
#previewBillTable td:nth-child(2) { width: 30px; }

#previewBillTable th:nth-child(3),
#previewBillTable td:nth-child(3) { width: 50px; }

#previewBillTable th:nth-child(4),
#previewBillTable td:nth-child(4) { width: 40px; }

#previewBillTable th:nth-child(5),
#previewBillTable td:nth-child(5),
#previewBillTable th:nth-child(6),
#previewBillTable td:nth-child(6) { width: 40px; }

#previewBillTable th:nth-child(7),
#previewBillTable td:nth-child(7) { width: 45px; }

#previewBillTable th:nth-child(8),
#previewBillTable td:nth-child(8) { width: 74px; }

#previewBillTable th:nth-child(9),
#previewBillTable td:nth-child(9) { width: 85px; }

#previewBillTable th:nth-child(10),
#previewBillTable td:nth-child(10),
#previewBillTable th:nth-child(11),
#previewBillTable td:nth-child(11) { width: 55px; }

#previewBillTable th:nth-child(13),
#previewBillTable td:nth-child(13) { width: 50px; }

/* Layout */
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

.left-panel,
.right-panel {
  background: #fff;
  padding: 12px;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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

h5,
h6 {
  font-size: 14px;
  margin-bottom: 8px;
}

.mb-3,
.mb-2 {
  margin-bottom: 6px !important;
}

.btn,
.btn-add {
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

.table-container {
  height: 200px;
  overflow-y: auto;
  border: 1px solid #000;
}

.table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}

.table tbody {
  display: table-row-group;
}

.btn-action-group {
  display: flex;
  gap: 8px;
  margin-top: 12px;
}

.btn-action-group .btn {
  flex: 1;
  padding: 8px 12px;
  font-size: 13px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 600;
}

.btn-save {
  background: #0d6efd;
  color: #fff;
}

.btn-save:hover {
  background: #0b5ed7;
}

.btn-cancel {
  background: #6c757d;
  color: #fff;
}

.btn-cancel:hover {
  background: #5c636a;
}

.alert-info {
  background: #d1ecf1;
  border: 1px solid #bee5eb;
  color: #0c5460;
  padding: 12px;
  border-radius: 5px;
  margin-bottom: 12px;
}

.invisible {
  visibility: hidden !important;
  pointer-events: none !important;
}

/* Print styles */
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
    color-adjust: exact !important;
  }

  body {
    background: white !important;
  }

  table,
  th,
  td {
    border-color: #534e4e !important;
  }
}

@media (max-width: 768px) {
  .container-flex {
    flex-direction: column;
  }

  .left-panel,
  .right-panel {
    width: 100%;
  }
}
</style>




@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container-flex">

     <!-- LEFT PANEL -->
    <div class="left-panel">
        <h5 class="mb-3">🧾 Invoice Entry</h5>

        @php
       

        // Fetch customers
        $customers = DB::table('customers')
            ->where('cid', session('company_id'))
            ->get();

        // Fetch all items
        $items = DB::table('items')
            ->where('cin', session('company_id'))
            ->get();
        @endphp

        <!-- Customer Dropdown -->
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

        <!-- Payment Mode -->
        <label>Payment Mode:</label>
        <select id="paymentModeSelect" name="payment_mode" class="form-control select2 mb-3">
            <option value="">Select Payment Mode</option>
            <option value="Cash">Cash</option>
            <option value="Card">Card</option>
            <option value="UPI">UPI</option>
            <option value="Wallet">Wallet</option>
            <option value="Exchange">Exchange</option>
        </select>
<div class="mb-3">
  <label for="bill_no" class="form-label fw-bold">Bill No.</label>
  <input type="text" name="bill_no" id="bill_no" class="form-control" 
         placeholder="Enter Bill No." value="{{ $newBillNo ?? '' }}">
</div>
        <div style="margin-bottom: 20px;">
            <label for="dueDateInput">Due Date:</label>
            <input type="date" id="dueDateInput" class="form-control" placeholder="Enter Due Date">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="billerNameInput">Biller Name:</label>
            <input type="text" id="billerNameInput" class="form-control" placeholder="Enter Biller Name">
        </div>

        <label>Item:</label>
        <select id="itemSelect" class="form-control select2 mb-3" multiple>
            @foreach(($items ?? []) as $item)
                <option 
                    value="{{ $item->id }}"
                    data-rate="{{ $item->price ?? 0 }}"
                     data-gold_karat="{{ $item->gold_karat ?? 0 }}"
                    data-gross_weight="{{ $item->gross_weight ?? 0 }}"
                    data-net_weight="{{ $item->net_weight ?? 0 }}"
                    data-item_group="{{ $item->item_group ?? '' }}"
                    data-metal_type="{{ $item->metal_type ?? '' }}"
                    data-making="{{ $item->making ?? 0 }}"
                    data-discount="{{ $item->discount ?? 0 }}">
                    {{ $item->item_name ?? 'Unnamed Item' }}
                </option>
            @endforeach
        </select>

        <!-- Rate & Item Details -->
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

        <!-- Exchange -->
        <h6>💱 Exchange Items</h6>

        <div class="mb-3 border p-2" id="newExchangeItemForm" style="display: none;">
            <input type="text" id="exchangeItemName" class="form-control mb-2" placeholder="Item Name">
            <div class="row g-2 mb-2">
                <div class="col">
                    <input type="text" id="exchangePurity" class="form-control" placeholder="Purity (e.g., 22K)">
                </div>
                <div class="col">
                    <input type="number" id="exchangeWeight" class="form-control" placeholder="Weight (gm)" step="0.01">
                </div>
            </div>
            <div class="row g-2">
                <div class="col">
                    <input type="number" id="exchangeRate" class="form-control" placeholder="Rate/gm" step="0.01">
                </div>
                <div class="col">
                    <input type="number" id="exchangeWastage" class="form-control" placeholder="Wastage %" step="0.01">
                </div>
            </div>
        </div>

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

        <h6>💰 Customer Payment</h6>
        <input type="number" 
               id="customerPayment" 
               class="form-control" 
               placeholder="Amount Paid" 
               min="0">

        <div class="btn-action-group">
            <button type="button" class="btn btn-save" id="saveBtn">💾 Save</button>
            <button type="button" class="btn btn-cancel" id="cancelBtn">❌ Cancel</button>
        </div>

    </div>

     <!-- RIGHT PANEL -->
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
                   <div>
  <strong>Bill No.:</strong>
  <span id="previewInvoiceNo">{{ $newBillNo ?? '-' }}</span>
</div>
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

            <div class="table-container mb-2">
                <table class="table table-bordered" id="previewBillTable" style="width: 100%; border-collapse: collapse; font-size: 7pt;">
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
                            <th style="border: 1px solid #000; padding: 2px;    width: 100px; text-align: center;">Total</th>
                            <th class="no-print" style="border: 1px solid #000;">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
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
    <td style="border: 1px solid #000; padding: 2px; text-align: right; font-weight: bold;">
        ₹ <span id="previewGrandTotal">0.00</span>
    </td>
</tr>

                        <tr>
                            <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">Amount Paid:</td>
                            <td style="border: 1px solid #000; padding: 2px; text-align: right;">₹ <span id="previewCustomerPaid">0.00</span></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 2px; font-weight: bold;">Balance Due:</td>
                            <td style="border: 1px solid #000; padding: 2px; text-align: right; font-weight: bold;">₹ <span id="previewBalanceDue">0.00</span></td>
                        </tr>
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

        </div>

        <button type="button"
                onclick="printInvoice()"
                class="btn-print mt-2"
                style="display:block;font-weight:bold;width:100%;padding:10px;background-color:#4CAF50;color:white;border:none;cursor:pointer;font-size:large!important;">
            🖨️ Print / Save Invoice
        </button>

    </div>

</div>

<!-- Hidden form for submission -->
<form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
    @csrf

    <input type="hidden" name="cin_id" value="{{ session('company_id') }}">
    <input type="hidden" name="customer_id" id="inputCustomerId" value="">
    <input type="hidden" name="due_date" id="duedate" value="">
    <input type="hidden" name="bill_name" id="inputBillName" value="">
    <input type="hidden" name="payment_mode" id="inputPaymentMode" value="">
    <input type="hidden" name="bill_no" value="{{ $newBillNo ?? '' }}">
    <input type="hidden" id="inputItemId" name="items" value="">
    <input type="hidden" id="inputOtherTax" name="other_charges" value="">
    <input type="hidden" id="exchangeSummary" name="exchange_summary" value="">
    <input type="hidden" id="customerPaymentCopy" name="paid_amount" value="">
    <input type="hidden" id="inputBalanceDue" name="due_amount" value="">
    <input type="hidden" id="inputGrandTotal" name="grand_total" value="">
</form>

<!-- Consolidated script includes - removed duplicates -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
@php

$metalRate = DB::table('metal_rates')
    ->where('cin', session('company_id'))
    ->orderBy('rate_date', 'desc')
    ->first();
@endphp
<script>
function updateGrandTotal(total) {
    // Format total with 2 decimals
    const formattedTotal = parseFloat(total || 0).toFixed(2);

    // Update preview text
    document.getElementById('previewGrandTotal').textContent = formattedTotal;

    // Update hidden input
    document.getElementById('inputGrandTotal').value = formattedTotal;
}

// Example usage:
// Whenever you calculate the total somewhere in your script, call:
updateGrandTotal(12500.75);
</script>

<script>
const metalRates = {
    gold_24: {{ $metalRate->gold_24 ?? 0 }},
    gold_22: {{ $metalRate->gold_22 ?? 0 }},
    gold_18: {{ $metalRate->gold_18 ?? 0 }},
    silver_999: {{ $metalRate->silver_999 ?? 0 }},
    silver_925: {{ $metalRate->silver_925 ?? 0 }}
};

function getRatePerGram(metalType, goldKarat) {
    metalType = (metalType || '').toLowerCase();
    goldKarat = (goldKarat || '').toString();

    let rate = 0;

    if (metalType === 'gold') {
        if (goldKarat === '24') rate = metalRates.gold_24;
        else if (goldKarat === '22') rate = metalRates.gold_22;
        else if (goldKarat === '18') rate = metalRates.gold_18;
    } 
    else if (metalType === 'silver') {
        if (goldKarat === '925') rate = metalRates.silver_925;
        else if (goldKarat === '999') rate = metalRates.silver_999;
    }

    return rate || 0;
}






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

function printInvoice() {
    const invoicePreview = document.getElementById('invoicePreview');
    if (!invoicePreview) {
        alert("Invoice content not found!");
        return;
    }

    html2canvas(invoicePreview, { scale: 2 }).then(canvas => {
        const imageData = canvas.toDataURL('image/png');
        const invoiceId = $('#inputInvoiceId').val() || 0;

        fetch('/invoices/save-image', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                invoice_image: imageData,
                invoice_id: invoiceId
            })
        })
        .then(res => res.json())
        .then(data => {
            console.log('✅ Invoice image saved:', data);
        })
        .catch(err => console.error('❌ Error saving image:', err));
    });

    const iframe = document.createElement('iframe');
    iframe.style.position = 'fixed';
    iframe.style.right = '0';
    iframe.style.bottom = '0';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = '0';
    document.body.appendChild(iframe);

    const doc = iframe.contentWindow.document;
    doc.open();
    doc.write('<!DOCTYPE html><html><head><title>Invoice</title>');

    document.querySelectorAll('link[rel="stylesheet"], style').forEach(styleEl => {
        doc.write(styleEl.outerHTML);
    });

    doc.write('</head><body>');
    doc.write(invoicePreview.outerHTML);
    doc.write('</body></html>');
    doc.close();

    iframe.onload = function() {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
        setTimeout(() => iframe.remove(), 2000);
    };
}
</script>

<!-- Consolidated main invoice logic - removed duplicate item handling -->
<script>
let billItems = [];
let exchangeItems = [];
const GST_RATE = 3;

$(function() {
    $('.select2').select2({ width: '100%' });

    // Calculate item values
    function calculateItemValues() {
        const $selected = $('#itemSelect option:selected');

        const itemId = $selected.val() || '';
        const itemName = $selected.text().trim();
        const itemGroup = $('#itemGroup').val() || $selected.data('item_group') || '';
        const goldKarat = $('#goldKarat').val() || $selected.data('gold_karat') || '';
        const metalType = $('#metalType').val() || $selected.data('metal_type') || '';
        const netWeight = parseFloat($('#netWeight').val()) || parseFloat($selected.data('net_weight')) || 0;
        const grossWeight = parseFloat($('#grossWeight').val()) || parseFloat($selected.data('gross_weight')) || 0;

        const qty = parseFloat($('#qty').val()) || 1;
        const makingPerGram = parseFloat($('#making').val()) || 0;
        const discountPercent = parseFloat($('#discount').val()) || 0;

        const ratePerGram = getRatePerGram(metalType, goldKarat, itemGroup);
        const goldValue = (ratePerGram * netWeight * qty) || 0;
        const makingValue = (makingPerGram * netWeight * qty) || 0;
        const baseAmount = goldValue + makingValue;
        const discountAmount = baseAmount * (discountPercent / 100);
        const amountAfterDiscount = baseAmount - discountAmount;
        const gstAmount = amountAfterDiscount * (GST_RATE / 100);
        const total = amountAfterDiscount + gstAmount;

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

    function renderBillTable() {
        const $tbody = $('#previewBillTable tbody').empty();
        const billItemsData = [];

        billItems.forEach((i, idx) => {
            const ratePerGram = i.goldRate || getRatePerGram(i.metalType, i.itemGroup, i.goldKarat);    
            const goldValue = ratePerGram * i.netWeight * i.qty;
            const makingValue = i.makingPerGram * i.netWeight * i.qty;
            const baseAmount = goldValue + makingValue;
            const discountAmount = baseAmount * (i.discountPercent / 100);
            const amountAfterDiscount = baseAmount - discountAmount;
            const gstAmount = amountAfterDiscount * (GST_RATE / 100);
            const total = amountAfterDiscount + gstAmount;

            i.goldRate = ratePerGram;
            i.goldValue = goldValue;
            i.makingValue = makingValue;
            i.discountAmount = discountAmount;
            i.gstAmount = gstAmount;
            i.total = total;

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

            billItemsData.push({ id: i.id, qty: i.qty });
        });

        $('#inputItemId').val(JSON.stringify(billItemsData));
    }

    renderBillTable();

    $(document).on('click', '.remove-item', function() {
        const idx = $(this).closest('tr').data('index');
        const removedItem = billItems.splice(idx, 1)[0];
        $('#itemSelect option[value="' + removedItem.id + '"]').prop('selected', false);
        $('#itemSelect').trigger('change');
        renderBillTable();
        calculateTotals();
    });

    $('#itemSelect').on('change', function() {
        const $selected = $(this).find(':selected');

        $('#netWeight').val($selected.data('net_weight') || 0);
        $('#grossWeight').val($selected.data('gross_weight') || 0);
        $('#itemGroup').val($selected.data('item_group') || '');
        $('#metalType').val($selected.data('metal_type') || '');
        $('#making').val($selected.data('making') || 0);
        $('#discount').val($selected.data('discount') || 0);

        calculateItemValues();
    });

    $('#qty, #netWeight, #making, #discount').on('input', calculateItemValues);

    $('#addItem').on('click', function() {
        if (!$('#itemSelect').val()) {
            alert('Please select an item');
            return;
        }
        const vals = calculateItemValues();
        billItems.push(vals);
        renderBillTable();
        calculateTotals();
        
        $('#itemSelect').val('').trigger('change');
        $('#qty').val(1);
        $('#making').val(0);
        $('#discount').val(0);
        $('#netWeight, #grossWeight, #itemGroup, #metalType, #rate, #totalAmount').val('');
    });

    function calculateTotal(weight, rate, wastagePercent) {
        const goldValue = weight * rate;
        const wastageValue = goldValue * (wastagePercent / 100);
        return goldValue + wastageValue;
    }

    function renderExchangeTable() {
        const $tbody = $('#previewExchangeTable tbody').empty();

        if (exchangeItems.length === 0) {
            $('#exchangeContainer').hide();
            $('#exchangeSummary').val('');
            return;
        }

        $('#exchangeContainer').show();
        const exchangeDetails = [];

        exchangeItems.forEach((item, idx) => {
            item.total = calculateTotal(item.weight, item.rate, item.wastagePercent);

            $tbody.append(`
                <tr data-index="${idx}">
                    <td>${idx + 1}</td>
                    <td>${item.name}</td>
                    <td>${item.purity}</td>
                    <td>${item.weight.toFixed(2)}</td>
                    <td>₹${item.rate.toFixed(2)}</td>
                    <td>${item.wastagePercent}%</td>
                    <td>₹${item.total.toFixed(2)}</td>
                    <td><button class="btn btn-sm btn-danger remove-exchange">❌</button></td>
                </tr>
            `);

            exchangeDetails.push(`${item.name} (Purity: ${item.purity}, Wt: ${item.weight.toFixed(2)}gm, Rate: ₹${item.rate.toFixed(2)}, Wastage: ${item.wastagePercent}%, Total: ₹${item.total.toFixed(2)})`);
        });

        $('#exchangeSummary').val(exchangeDetails.join(' | '));
    }

    renderExchangeTable();

    $('#addExchangeItem').on('click', function() {
        const form = $('#newExchangeItemForm');
        if (form.is(':visible')) {
            const name = $('#exchangeItemName').val().trim();
            const purity = $('#exchangePurity').val().trim();
            const weight = parseFloat($('#exchangeWeight').val()) || 0;
            const rate = parseFloat($('#exchangeRate').val()) || 0;
            const wastagePercent = parseFloat($('#exchangeWastage').val()) || 0;
            
            if (!name || weight <= 0 || rate <= 0) {
                alert('Please enter valid exchange item details');
                return;
            }
            
            exchangeItems.push({ name, purity, weight, rate, wastagePercent, total: 0 });
            renderExchangeTable();
            calculateTotals();
            
            $('#exchangeItemName, #exchangePurity, #exchangeWeight, #exchangeRate, #exchangeWastage').val('');
            form.hide();
            $('#addExchangeItem').text('Add Exchange Item');
        } else {
            form.show();
            $('#addExchangeItem').text('Save Exchange Item');
        }
    });

    $('#previewExchangeTable').on('click', '.remove-exchange', function() {
        const idx = $(this).closest('tr').data('index');
        exchangeItems.splice(idx, 1);
        renderExchangeTable();
        calculateTotals();
    });

    function calculateTotals() {
        let subTotal = 0, makingTotal = 0, discountTotal = 0, gstTotal = 0, totalGrossWeight = 0;

        billItems.forEach(i => {
            subTotal += i.goldValue;
            makingTotal += i.makingValue;
            discountTotal += i.discountAmount;
            gstTotal += i.gstAmount;
            totalGrossWeight += i.grossWeight * i.qty;
        });

        const newPurchaseTotal = subTotal + makingTotal - discountTotal + gstTotal;
        const exchangeTotal = exchangeItems.reduce((sum, i) => sum + i.total, 0);

        let grandTotal = newPurchaseTotal - exchangeTotal;

        const otherTaxPercent = parseFloat($('#otherTaxPercent').val()) || 0;
        const otherTaxName = $('#otherTaxName').val().trim() || '-';
        const otherTaxAmount = (grandTotal * otherTaxPercent) / 100;
        grandTotal += otherTaxAmount;

        const paid = parseFloat($('#customerPayment').val()) || 0;
        const balance = grandTotal - paid;

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

        $('#amountInWords').text(numberToWords(grandTotal));
    }

    function updateCustomerDetails() {
        const selected = $('#customer').find('option:selected');
        const customerId = selected.val();
        const name = selected.data('name') || '-';
        const phone = selected.data('phone') || '-';
        const email = selected.data('email') || '-';
        const address = selected.data('address') || '-';

        $('#inputCustomerId').val(customerId);
        $('#previewCustomerName').text(name);
        $('#previewCustomerPhone').text(phone);
        $('#previewCustomerEmail').text(email);
        $('#previewCustomerAddress').text(address);
    }

    $('#customer').on('change', updateCustomerDetails);

    if ($('#customer').val()) {
        updateCustomerDetails();
    }

    $('#customerPayment').on('input', calculateTotals);
    $('#otherTaxPercent, #otherTaxName').on('input', calculateTotals);

    calculateItemValues();
    calculateTotals();
});
</script>

<!-- Handle Due Date and Biller Name -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dueDateInput = document.getElementById('dueDateInput');
    const billerNameInput = document.getElementById('billerNameInput');
    const previewDueDate = document.getElementById('previewDueDate');
    const previewSalesman = document.getElementById('previewSalesman');
    const hiddenDueDate = document.getElementById('duedate');
    const hiddenBillName = document.getElementById('inputBillName');

    dueDateInput.addEventListener('input', function() {
        previewDueDate.textContent = this.value || '-';
        hiddenDueDate.value = this.value;
    });

    billerNameInput.addEventListener('input', function() {
        previewSalesman.textContent = this.value || '-';
        hiddenBillName.value = this.value;
    });

    hiddenDueDate.value = dueDateInput.value;
    hiddenBillName.value = billerNameInput.value;
});
</script>

<!-- Handle Save and Cancel Buttons -->
<script>
$(document).ready(function() {
    $('#cancelBtn').on('click', () => {
        if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
            window.history.back();
        }
    });

    $('#saveBtn').on('click', function() {
        if (!$('#customer').val()) {
            alert('Please select a customer');
            return;
        }

        if (billItems.length === 0) {
            alert('Please add at least one item to the invoice');
            return;
        }

        if (!$('#paymentModeSelect').val()) {
            alert('Please select a payment mode');
            return;
        }

        $('#inputCustomerId').val($('#customer').val());
        $('#inputBillName').val($('#billerNameInput').val());
        $('#duedate').val($('#dueDateInput').val());
        $('#inputPaymentMode').val($('#paymentModeSelect').val());
        $('#customerPaymentCopy').val($('#customerPayment').val());

        const form = $('#invoiceForm');
        const formData = form.serialize();
        
        console.log("[v0] Submitting form with data:", formData);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log("[v0] Success response:", response);
                alert('Invoice saved successfully!');
                  window.location.href = "{{ route('invoices.history') }}";
            },
            error: function(xhr, status, error) {
                console.error("[v0] Error:", xhr, status, error);
                
                let errorMessage = 'Form submission failed!';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('n');
                } else if (xhr.responseText) {
                    errorMessage = xhr.responseText;
                }
                
                alert('Error: ' + errorMessage);
                console.error("[v0] Full error response:", xhr.responseJSON || xhr.responseText);
            }
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const billInput = document.getElementById('bill_no');
    const previewSpan = document.getElementById('previewInvoiceNo');

    // Update preview live as the user types
    billInput.addEventListener('input', function () {
        const value = billInput.value.trim();
        previewSpan.textContent = value || '-';
    });
});
</script>
@endsection
