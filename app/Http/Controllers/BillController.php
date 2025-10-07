<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
public function create()
{
    $cin = session('company_id');  // Logged-in company ID

    // Fetch company including bill format
    $company = DB::table('companies')
        ->where('id', $cin)
        ->first();

    // Get the bill format stored in the company table
    $billFormat = $company->bill_no ?? 'JWE-001BN'; // default if empty

    // Extract numeric part using regex
    if (preg_match('/(\d+)/', $billFormat, $matches)) {
        $numberLength = strlen($matches[0]);      // number of digits
        $number = rand(1, 999);                   // generate a random number
        $formattedNumber = str_pad($number, $numberLength, '0', STR_PAD_LEFT); // pad with zeros
        $newBillNo = preg_replace('/\d+/', $formattedNumber, $billFormat);
    } else {
        // If no numeric part found, just append 001
        $newBillNo = $billFormat . '-001';
    }

    // Fetch customers
    $customers = DB::table('customers')
        ->where('cid', $cin)
        ->orderBy('id', 'desc')
        ->get();

    // Fetch items
    $items = DB::table('items')
        ->where('cin', $cin)
        ->orderBy('id', 'desc')
        ->get();

    // Fetch latest metal rates
    $latestRates = DB::table('metal_rates')
        ->where('cin', $cin)
        ->orderBy('rate_date', 'desc')
        ->first();

    return view('billing.create', compact('customers', 'items', 'latestRates', 'company', 'newBillNo'));
}



    public function getItemRate($id)
    {
        // Fetch item
        $item = DB::table('items')->where('id', $id)->first();

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        // Fetch latest rate
        $rate = DB::table('metal_rates')->orderBy('rate_date', 'desc')->first();

        $metalRate = 0;
        if ($item->metal_type == 'Gold') {
            if($item->gold_karat == 24) $metalRate = $rate->gold_24;
            elseif($item->gold_karat == 22) $metalRate = $rate->gold_22;
            elseif($item->gold_karat == 18) $metalRate = $rate->gold_18;
        } elseif ($item->metal_type == 'Silver') {
            $metalRate = $rate->silver_999; // example
        }

        return response()->json([
            'metal_rate' => $metalRate,
            'net_weight' => $item->net_weight,
            'diamond_weight' => $item->diamond_weight,
            'diamond_rate_per_carat' => $item->diamond_rate_per_carat
        ]);
    }




public function history(Request $request)
{
    // Base query: invoices + customer info
  $companyId = session('company_id'); // get company ID from session

$query = DB::table('invoices')
    ->join('customers', 'invoices.customer_id', '=', 'customers.id')
    ->select(
        'invoices.*',
        'customers.name as customer_name',
        'customers.phone as customer_phone'
    )
    ->where('invoices.cin_id', $companyId) // filter by company
    ->orderBy('invoices.id', 'desc');

    // Filters
    if ($request->filled('customer_name')) {
        $query->where('customers.name', 'like', '%' . $request->customer_name . '%');
    }

    if ($request->filled('bill_no')) {
        $query->where('invoices.bill_no', 'like', '%' . $request->bill_no . '%');
    }

    if ($request->filled('mobile')) {
        $query->where('customers.phone', 'like', '%' . $request->mobile . '%');
    }

    $invoices = $query->paginate(15);

    // Fetch all item details for these invoices
    $invoiceItems = [];
    foreach ($invoices as $invoice) {
        $itemsArray = json_decode($invoice->items, true);
        if (is_array($itemsArray)) {
            $itemIds = collect($itemsArray)->pluck('id')->toArray();
            $items = DB::table('items')->whereIn('id', $itemIds)->get();
            
            // Map quantity from invoice JSON
            foreach ($items as $i) {
                $qty = collect($itemsArray)->firstWhere('id', $i->id)['qty'] ?? 0;
                $invoiceItems[$invoice->id][] = [
                    'name' => $i->item_name,
                    'qty' => $qty,
                    'metal_type' => $i->metal_type,
                    'net_weight' => $i->net_weight,
                    'price' => $i->price,
                ];
            }
        } else {
            $invoiceItems[$invoice->id] = [];
        }
    }

    return view('invoices.history', compact('invoices', 'invoiceItems'));
}

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'cin_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'bill_name' => 'required|string',
            'bill_no' => 'required|string',
            'due_date' => 'required|date',
            'items' => 'required',
            'exchange_summary' => 'nullable|string',
            'other_charges' => 'nullable|string',
            'paid_amount' => 'nullable|numeric',
            'due_amount' => 'nullable|numeric',
            'grand_total' => 'required|numeric',
            'payment_mode' => 'nullable|string',
        ]);
        // Insert into table
        $id = DB::table('invoices')->insertGetId([
            'cin_id' => $request->cin_id,
            'customer_id' => $request->customer_id,
            'bill_name' => $request->bill_name,
            'bill_no' => $request->bill_no,
            'due_date' => $request->due_date,
            'items' => $request->items,
            'exchange_summary' => $request->exchange_summary,
            'other_charges' => $request->other_charges,
            'paid_amount' => $request->paid_amount ?? 0,
            'due_amount' => $request->due_amount ?? 0,
            'grand_total' => $request->grand_total,
            'payment_mode' => $request->payment_mode,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

      return redirect()->back()->with('success', 'Invoice generated successfully!');
    }
public function destroy($id)
    {
        // Delete the invoice
        DB::table('invoices')->where('id', $id)->delete();

        // Optionally, redirect back with a success message
        return redirect()->route('invoices.history')
                         ->with('success', 'Invoice deleted successfully.');
    }

}
