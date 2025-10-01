<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
public function search(Request $request)
{
    $search = $request->get('q');

    $customers = Customer::when($search, function($query, $search){
                        return $query->where('name', 'like', "%{$search}%");
                    })
                    ->limit(50) // limit results for performance
                    ->get(['id', 'name']);

    return response()->json($customers);
}

public function analysis($customerId)
{
    $bills = DB::table('bills')->where('customer_id', $customerId)->get();

    $totalAmount = $bills->sum('total_amount');
    $totalRemaining = $bills->sum('remaining_amount');
    $totalBills = $bills->count();

    $products = [];

    foreach($bills as $bill){
        $items = json_decode($bill->product_id, true);
        if(is_array($items)){
            foreach($items as $item){
                $products[] = [
                    'name' => $item['name'] ?? '',
                    'qty' => $item['qty'] ?? 1,
                    'price' => $item['price'] ?? 0
                ];
            }
        }
    }

    return response()->json([
        'total_bills' => $totalBills,
        'total_amount' => $totalAmount,
        'total_remaining' => $totalRemaining,
        'products' => $products
    ]);
}




    // List Customers
    public function index(Request $request)
{
    $query = DB::table('customers');

    // Filters
    if ($request->filled('name')) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }
    if ($request->filled('customer_group')) {
        $query->where('customer_group', $request->customer_group);
    }
    if ($request->filled('city')) {
        $query->where('city', 'like', '%' . $request->city . '%');
    }
    if ($request->filled('phone')) {
        $query->where('phone', 'like', '%' . $request->phone . '%');
    }
    if ($request->filled('state')) {
        $query->where('state', 'like', '%' . $request->state . '%');
    }

    // Use paginate instead of get
    $customers = $query->orderBy('name', 'asc')->paginate(10); // 10 per page

    return view('company.customers.index', compact('customers'));
}

    // Show Create Form
    public function create()
    {
        return view('company.customers.create');
    }
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'nullable|email',
        'customer_group' => 'required|string|max:255',
        'other_group' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:500',
        'city' => 'nullable|string|max:255',
        'area' => 'nullable|string|max:255',
        'district' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:255',
        'pin_code' => 'nullable|string|max:10',

        // 🔹 Legal validation
        'pan_number' => ['nullable','regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
        'aadhaar_number' => ['nullable','digits:12'],
        'bank_name' => 'nullable|string|max:255',
        'bank_account' => ['nullable','digits_between:9,18'],
        'ifsc_code' => ['nullable','regex:/^[A-Z]{4}0[A-Z0-9]{6}$/'],
        'dob' => 'nullable|date',
    ], [
        'pan_number.regex' => 'PAN must be 10 characters in format: AAAAA9999A.',
        'aadhaar_number.digits' => 'Aadhaar number must be exactly 12 digits.',
        'bank_account.digits_between' => 'Bank account number must be between 9 and 18 digits.',
        'ifsc_code.regex' => 'IFSC code must be 11 characters, format: AAAA0XXXXXX.',
    ]);

    $group = $request->customer_group;

    // If "Other" is selected, use custom value
    if ($group === 'Other' && $request->filled('other_group')) {
        $group = $request->other_group;
    }

    // Store in groups table if it does not already exist
    $exists = DB::table('groups')->where('customer_group', $group)->exists();
    if (!$exists) {
        DB::table('groups')->insert([
            'customer_group' => $group,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Store in customers table
    Customer::create([
        'cid' => session('company_id'),
        'name' => $request->name,
        'customer_group' => $group,
        'address' => $request->address,
        'city' => $request->city,
        'area' => $request->area,
        'district' => $request->district,
        'state' => $request->state,
        'pin_code' => $request->pin_code,
        'pan_number' => $request->pan_number,
        'aadhaar_number' => $request->aadhaar_number,
        'phone' => $request->phone,
        'email' => $request->email,
        'dob' => $request->dob,
        'bank_name' => $request->bank_name,
        'bank_account' => $request->bank_account,
        'ifsc_code' => $request->ifsc_code,
    ]);

    return redirect()->route('admin.customers.index')
                     ->with('success', 'Customer created successfully!');
}


    // Edit Customer
// Edit Customer
public function edit($id)
{
    $customer = Customer::where('id', $id)
                        ->where('cid', session('company_id'))
                        ->firstOrFail();

    return view('company.customers.edit', compact('customer'));
}

// Update Customer
public function update(Request $request, $id)
{
    $customer = Customer::where('id', $id)
                        ->where('cid', session('company_id'))
                        ->firstOrFail();

    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'nullable|email',
    ]);

    $customer->update($request->all());

    return redirect()->route('admin.customers.index')
                     ->with('success', 'Customer updated successfully');
}

// Delete Customer
public function destroy($id)
{
    $customer = Customer::where('id', $id)
                        ->where('cid', session('company_id'))
                        ->firstOrFail();

    $customer->delete();

    return redirect()->route('admin.customers.index')
                     ->with('success', 'Customer deleted successfully');
}




}
