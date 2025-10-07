<!-- resources/views/invoices/history.blade.php -->
@extends('company.dashboard')

@section('content')
<h3>Invoice History</h3>
<style>
    .w-5{
        width:20px !important;

    }
    </style>
<form method="GET" class="mb-3">
    <div class="row g-2">
        <div class="col-md-3">
            <input type="text" name="customer_name" value="{{ request('customer_name') }}" class="form-control" placeholder="Customer Name">
        </div>
        <div class="col-md-3">
            <input type="text" name="bill_no" value="{{ request('bill_no') }}" class="form-control" placeholder="Bill Number">
        </div>
        <div class="col-md-3">
            <input type="text" name="mobile" value="{{ request('mobile') }}" class="form-control" placeholder="Mobile Number">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Bill No</th>
            <th>Mobile</th>
            <th>Payment Mode</th>
            <th>Grand Total</th>
            <th>Due Amount</th>
            <th>Items</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoices as $invoice)
        <tr>
            <td>{{ $invoice->id }}</td>
            <td>{{ $invoice->customer_name }}</td>
            <td>{{ $invoice->bill_no }}</td>
            <td>{{ $invoice->customer_phone }}</td>
            <td>{{ $invoice->payment_mode }}</td>
            <td>{{ $invoice->grand_total }}</td>
            <td>{{ $invoice->due_amount }}</td>
            <td>
                @foreach($invoiceItems[$invoice->id] ?? [] as $item)
                    {{ $item['name'] }} × {{ $item['qty'] }} 
                    ({{ $item['metal_type'] ?? '' }}, {{ $item['net_weight'] ?? '' }}g, ₹{{ $item['price'] ?? '' }})<br>
                @endforeach
            </td>
            <td>
    <!-- Edit Button -->
    <a href="{{ route('billing.index', $invoice->id) }}" class="btn btn-sm btn-warning">Edit</a>

    <!-- Delete Button -->
    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline-block">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
    </form>

    <!-- WhatsApp Button -->
@php
$company = DB::table('companies')->where('id', session('company_id'))->first();
$customer = DB::table('customers')->where('id', $invoice->customer_id)->first();
$customerPhone = $invoice->customer_phone ?? '';
$customerAddress = $customer->address ?? 'N/A';

if($customerPhone) {
    $message = "";
    $message .= "🏢 {$company->name}\n";
    $message .= "{$company->address}, {$company->city}, {$company->itstate}, PIN: {$company->pincode}\n";
    $message .= "GSTIN: {$company->gst_no}\n";
    if($company->website) $message .= "Website: {$company->website}\n";
    if($company->mobile) $message .= "📞: {$company->mobile} | ✉: {$company->email}\n\n";

    $message .= "🧾 Invoice / बिल विवरण\n";
    $message .= "Invoice No: {$invoice->bill_no}\n";
    $message .= "Date: " . date('d M Y', strtotime($invoice->created_at)) . "\n";
    $message .= "Customer: {$invoice->customer_name}\n";
    $message .= "Contact: {$invoice->customer_phone}\n";
    $message .= "Address: {$customerAddress}\n\n";

    $message .= "📦 Products / उत्पाद:\n";
    foreach($invoiceItems[$invoice->id] ?? [] as $item) {
        $totalPrice = $item['qty'] * $item['price'];
        $message .= "{$item['name']} - Qty: {$item['qty']} x ₹{$item['price']} = ₹" . number_format($totalPrice, 2) . "\n";
    }

    $message .= "\n💰 Other Charges: (0%)\n";
    $message .= "Payment Mode: {$invoice->payment_mode}\n";
    $message .= "Amount Paid: ₹" . number_format($invoice->grand_total - $invoice->due_amount, 2) . "\n";
    $message .= "Remaining Amount: ₹" . number_format($invoice->due_amount, 2) . "\n";
    $message .= "Total Amount: ₹" . number_format($invoice->grand_total, 2) . "\n\n";

    $message .= "🙏 Thank you for your purchase! / आपके सहयोग के लिए धन्यवाद!";

    $whatsappUrl = "https://wa.me/{$customerPhone}?text=" . urlencode($message);
}
@endphp

@if($customerPhone)
    <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-success btn-sm p-1" title="Share Invoice on WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>
@endif


</td>

            
        </tr>
        @endforeach
    </tbody>
</table>





<div>
    {{ $invoices->withQueryString()->links() }}
</div>
@endsection
