<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // Get CIN from session
        $cin = session('company_id'); 
        if (!$cin) {
            return redirect()->route('company.login')->with('error', 'Company not found.');
        }

        $query = DB::table('items')->where('cin', $cin); // Filter by CIN

        // Filter by item name
        if ($request->filled('item_name')) {
            $query->where('item_name', 'like', '%' . $request->item_name . '%');
        }

        // Filter by item group
        if ($request->filled('item_group')) {
            $query->where('item_group', $request->item_group);
        }

        $items = $query->get();

        // Totals
        $totals = [
            'total_tags' => $items->count(),
            'total_net_weight' => $items->sum('net_weight'),
            'total_op_qty' => $items->whereIn('op_qty', ['Pair','Single'])->count()
        ];

        return view('items.index', compact('items', 'totals'));
    }

    public function create()
    {
        return view('items.create');
    }

   

    public function edit($id)
    {
        $cin = session('company_id'); 
        if (!$cin) {
            return redirect()->route('company.login')->with('error', 'Company not found.');
        }

        // Fetch only item belonging to this CIN
        $item = DB::table('items')->where('id', $id)->where('cin', $cin)->first();

        if (!$item) {
            return redirect()->route('items.index')->with('error', 'Item not found.');
        }

        return view('items.create', compact('item')); // Reuse create form
    }

public function store(Request $request)
{
    $cin = session('company_id'); 
    if (!$cin) {
        return redirect()->route('company.login')->with('error', 'Company not found.');
    }

    $data = $request->validate([
        'stock'=>'nullable|integer|min:0',
        'item_name' => 'required|string',
        'item_prefix' => 'nullable|string',
        'hsn_code' => 'nullable|string',
        'short_name' => 'nullable|string',
        'item_group' => 'required|string',
        'metal_type' => 'required|string',
        'gold_karat' => 'nullable|string',
        'tax_slab' => 'nullable|numeric',
        'image' => 'nullable|image|max:2048',
        'tag_number' => 'nullable|string',
        'huid' => 'nullable|string',
        'gross_weight' => 'nullable|numeric',
        'bead_weight' => 'nullable|numeric',
        'diamond_weight' => 'nullable|numeric',
        'diamond_rate_per_carat' => 'nullable|numeric',
        'net_weight' => 'nullable|numeric',
        'op_qty' => 'nullable|string',
        'polish_percentage' => 'nullable|numeric',
        'making' => 'nullable|numeric',
        'discount' => 'nullable|numeric',
        'price' => 'nullable|numeric',
    ]);

    $data['cin'] = $cin; 
    $data['making'] = $request->input('making', 0);     // Default 0
    $data['discount'] = $request->input('discount', 0); // Default 0
    $data['price'] = $request->input('price', 0);       // Default 0

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $filename = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('items'), $filename);
        $data['image'] = $filename;
    }

    DB::table('items')->insert($data);

    return redirect()->route('items.index')->with('success', 'Item added successfully.');
}


public function update(Request $request, $id)
{
    $cin = session('company_id'); 
    if (!$cin) {
        return redirect()->route('company.login')->with('error', 'Company not found.');
    }

    $data = $request->validate([
        'stock'=>'nullable|integer|min:0',
        'item_name' => 'required|string',
        'item_prefix' => 'nullable|string',
        'hsn_code' => 'nullable|string',
        'short_name' => 'nullable|string',
        'item_group' => 'required|string',
        'metal_type' => 'required|string',
        'gold_karat' => 'nullable|string',
        'tax_slab' => 'nullable|numeric',
        'image' => 'nullable|image|max:2048',
        'tag_number' => 'nullable|string',
        'huid' => 'nullable|string',
        'gross_weight' => 'nullable|numeric',
        'bead_weight' => 'nullable|numeric',
        'diamond_weight' => 'nullable|numeric',
        'diamond_rate_per_carat' => 'nullable|numeric',
        'net_weight' => 'nullable|numeric',
        'op_qty' => 'nullable|string',
        'polish_percentage' => 'nullable|numeric',
        'making' => 'nullable|numeric',
        'discount' => 'nullable|numeric',
        'price' => 'nullable|numeric',
    ]);

    $data['cin'] = $cin; 
    $data['making'] = $request->input('making', 0);    
    $data['discount'] = $request->input('discount', 0);
    $data['price'] = $request->input('price', 0);

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $filename = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('items'), $filename);
        $data['image'] = $filename;
    }

    DB::table('items')->where('id', $id)->where('cin', $cin)->update($data);

    return redirect()->route('items.index')->with('success', 'Item updated successfully.');
}


    public function destroy($id)
    {
        $cin = session('company_id'); 
        if (!$cin) {
            return redirect()->route('company.login')->with('error', 'Company not found.');
        }

        DB::table('items')->where('id', $id)->where('cin', $cin)->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
