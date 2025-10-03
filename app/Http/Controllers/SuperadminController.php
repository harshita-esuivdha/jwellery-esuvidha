<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Superadmin; // assuming you have this model
use Carbon\Carbon;
use App\Models\Company;
use Illuminate\Support\Facades\Mail;
use App\Mail\SuperadminCredentialsMail;
class SuperadminController extends Controller
{
    // Show the form
public function showRegisterForm()
{
    // Check if a superadmin is logged in
    if (!session('superadmin_id')) {
        return redirect()->route('company.login') // redirect to login if not logged in
                         ->with('error', 'You must be logged in as a SuperAdmin to access this page.');
    }

    // Show the registration form
    return view('superadmin.register');
}

    // Handle form submission
public function register(Request $request)
{
    // ✅ Validate input
    $request->validate([
        'company_name'  => 'required|string|max:255',
        'address'       => 'required|string|max:500',
        'mobile'        => 'required|string|digits:10',
        'company_limit' => 'required|integer|min:1',
        'email'         => 'required|email|unique:superadmins,email',
        'expiry_date'   => 'required|date|after_or_equal:today',
        'status'        => 'required|in:Active,Inactive',
    ]);

    // ✅ Generate random password
    $plainPassword = Str::random(10);

    // ✅ Save data in DB
    $superadmin = Superadmin::create([
        'company_name'  => $request->company_name,
        'address'       => $request->address,
        'mobile'        => $request->mobile,
        'company_limit' => $request->company_limit,
        'email'         => $request->email,
        'password'      => Hash::make($plainPassword),
        'expiry_date'   => $request->expiry_date,
        'status'        => $request->status,
    ]);

    // ✅ Send the credentials via email
    Mail::to($superadmin->email)->send(
        new SuperadminCredentialsMail(
            $superadmin->company_name,
            $superadmin->email,
            $plainPassword,
            $superadmin->expiry_date
        )
    );

    return back()->with('success', "Superadmin registered successfully. Login credentials have been sent to {$superadmin->email}.");
}
public function dashboard()
{
    // Count total companies
    $totalCompanies = Company::count();

    // Count total invoices (example)
    $totalInvoices = 125; 

    // Current financial year
    $currentFinancialYear = '2025-2026';

    // Fetch all superadmins
    $superadmins = Superadmin::all(); // or add conditions if needed

    // Pass everything to the view
    return view('superadmin.dashboard', compact(
        'totalCompanies', 
        'totalInvoices', 
        'currentFinancialYear',
        'superadmins' // <-- this fixes the undefined variable
    ));
}
public function updateExpiry(Request $request, $id)
{
    $request->validate([
        'expiry_date' => 'required|date',
    ]);

    $superadmin = Superadmin::findOrFail($id);
    $superadmin->expiry_date = $request->expiry_date;
    $superadmin->save();

    return back()->with('success', 'Expiry date updated successfully!');
}

public function updatePassword(Request $request, $id)
{
    $request->validate([
        'password' => 'required|string|min:6',
    ]);

    $superadmin = Superadmin::findOrFail($id);
    $superadmin->password = Hash::make($request->password);
    $superadmin->save();

    return back()->with('success', 'Password updated successfully!');
}
public function update(Request $request, $id)
{
    $superadmin = Superadmin::findOrFail($id);

    $request->validate([
        'company_name' => 'required|string|max:255',
        'address'      => 'required|string|max:500',
        'mobile'       => 'required|string|digits:10',
        'email'        => 'required|email|unique:superadmins,email,' . $id,
        'expiry_date'  => 'required|date|after_or_equal:today',
        'status'       => 'required|in:Active,Inactive',
        'password'     => 'nullable|string|min:6',
    ]);

    $data = $request->only(['company_name', 'address', 'mobile', 'email', 'expiry_date', 'status']);

    // Update password if provided
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $superadmin->update($data);

    return back()->with('success', "Superadmin updated successfully.");
}

    // ✅ Delete superadmin
    public function destroy($id)
    {
        $superadmin = Superadmin::findOrFail($id);
        $superadmin->delete();

        return back()->with('success', "Superadmin deleted successfully.");
    }
    // Show profile of logged-in superadmin
public function profile()
{
    // Get the ID of the logged-in superadmin from the session / auth guard
    $superadminId = session('superadmin_id');

    // Fetch the superadmin from the database using the model
    $superadmin = Superadmin::find($superadminId);

    if (!$superadmin) {
        return redirect()->route('company.login')->with('error', 'Superadmin not found.');
    }

    return view('superadmin.profile', compact('superadmin'));
}
// Update profile of logged-in superadmin
public function updateProfile(Request $request)
{
    // Use the 'superadmin' guard to get logged-in superadmin
     $superadminId = session('superadmin_id');

    if (!$superadminId) {
        return redirect()->route('company.login')->with('error', 'Please login first.');
    }

    $request->validate([
        'company_name' => 'required|string|max:255',
        'address'      => 'required|string|max:500',
        'mobile'       => 'required|string|digits:10',
        'email'        => 'required|email|unique:superadmins,email,' . $superadminId,
        'password'     => 'nullable|string|min:6',
    ]);

    $data = $request->only(['company_name', 'address', 'mobile', 'email']);

    // Update password if provided
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    Superadmin::where('id', $superadminId)->update($data);

    return back()->with('success', 'Profile updated successfully.');
}

}
