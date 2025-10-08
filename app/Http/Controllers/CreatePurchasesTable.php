<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreatePurchasesTable extends Controller
{
    public function index()
    {
        $purchases = DB::table('purchases')->orderBy('created_at', 'desc')->get();
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        return view('purchases.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'item_type' => 'required|string',
            'weight' => 'nullable|numeric',
            'rate' => 'nullable|numeric',
            'wastage_percent' => 'nullable|numeric',
            'labor_charge' => 'nullable|numeric',
        ]);

        $weight = $request->weight ?? 0;
        $rate = $request->rate ?? 0;
        $wastage = $request->wastage_percent ?? 0;
        $total = ($weight * $rate) * (1 - $wastage / 100) + ($request->labor_charge ?? 0);

        DB::table('purchases')->insert([
            'item_name' => $request->item_name,
            'item_type' => $request->item_type,
            'weight' => $weight,
            'rate' => $rate,
            'wastage_percent' => $wastage,
            'labor_charge' => $request->labor_charge ?? 0,
            'total' => $total,
            'notes' => $request->notes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('purchases.index')->with('success', 'Purchase recorded successfully!');
    }
    // Edit
public function edit($id)
{
    $purchase = DB::table('purchases')->where('id', $id)->first();
    return view('purchases.edit', compact('purchase'));
}

// Update
public function update(Request $request, $id)
{
    $request->validate([
        'item_name' => 'required|string|max:255',
        'item_type' => 'required|string',
        'weight' => 'nullable|numeric',
        'rate' => 'nullable|numeric',
        'wastage_percent' => 'nullable|numeric',
        'labor_charge' => 'nullable|numeric',
    ]);

    $weight = $request->weight ?? 0;
    $rate = $request->rate ?? 0;
    $wastage = $request->wastage_percent ?? 0;
    $total = ($weight * $rate) * (1 - $wastage / 100) + ($request->labor_charge ?? 0);

    DB::table('purchases')->where('id', $id)->update([
        'item_name' => $request->item_name,
        'item_type' => $request->item_type,
        'weight' => $weight,
        'rate' => $rate,
        'wastage_percent' => $wastage,
        'labor_charge' => $request->labor_charge ?? 0,
        'total' => $total,
        'notes' => $request->notes,
        'updated_at' => now(),
    ]);

    return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully!');
}

// Delete
public function destroy($id)
{
    DB::table('purchases')->where('id', $id)->delete();
    return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully!');
}

}
