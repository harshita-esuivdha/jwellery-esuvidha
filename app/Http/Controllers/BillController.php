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


}
