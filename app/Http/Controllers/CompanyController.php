<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Superadmin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class CompanyController extends Controller
{


   
public function subDashboard()
{
  $companyId = session('company_id'); // assuming company_id stored in session
        $company = $companyId ? Company::find($companyId) : null;
  if (!$company) {
        return redirect()->route('company.login')->with('error', 'comapny not found.');
    }
           // Get latest rate for pre-filling form
        $latest = DB::table('metal_rates')->latest('rate_date')->first();

        return view('sub.dashboard', [
            'latest' => $latest
        ]);

}




    /**
     * Show the registration form
     */
    public function create()
    {
        return view('regist'); // Blade file for registration form
    }
public function publicList()
{
    $query = Company::select('id','name', 'address', 'logo', 'city', 'district', 'mobile', 'email', 'website');

    // If superadmin is logged in, filter by parent_id
    if (session()->has('superadmin_id')) {
        $superadminId = session('superadmin_id');
        $query->where('parent_id', $superadminId);
    }

    $companies = $query->get();

    return view('companies.public', compact('companies'));
}

    // /**
    //  * Store the company registration
    //  *
 public function store(Request $request)
{
    // ✅ Get current superadmin ID from session
    $superadminId = session('superadmin_id');

    if (!$superadminId) {
        return redirect()->route('superadmin.login')->with('error', 'Please login first.');
    }


    // ✅ Fetch superadmin row to get company_limit
    $superadmin = Superadmin::find($superadminId);
    $companyLimit = $superadmin->company_limit ?? 5; // default to 5 if not set

    // ✅ Count how many companies this superadmin has already created
    $companyCount = Company::where('parent_id', $superadminId)->count();

    if ($companyCount >= $companyLimit) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['name' => "❌ You have already created the maximum of {$companyLimit} companies."]);
    }

    // ✅ Validate input
    $request->validate([
        'name'        => 'required|string|max:255',
        'address'     => 'required|string',
        'itstate'     => 'required|string|max:100',
        'city'        => 'required|string|max:100',
        'pincode'     => 'required|digits:6',
        'district'    => 'required|string|max:100',
        'mobile'      => 'required',
        'email'       => 'required|email|unique:companies',
        'website'     => 'nullable|url',
        'gst_no'      => 'nullable|string:15',
        'pan_no'      => 'nullable|string:10',
        'fy_start'    => 'required|date',
        'fy_end'      => 'required|date|after:fy_start',
        'bank_name'   => 'required|string|max:100',
        'account_no'  => 'required|string:18',
        'ifsc'        => 'required|string:11',
        'username'    => 'required|string|unique:companies',
        'password'    => 'required|string|min:6',
        'logo'        => 'nullable|image|max:2048',
    ]);

    $logoName = null;

    // ✅ Handle logo upload
    if ($request->hasFile('logo')) {
        $logo = $request->file('logo');
        $logoName = time() . '_' . $logo->getClientOriginalName();
        $logo->move(public_path('company/logo'), $logoName);
    }

    // ✅ Create company with parent_id
    $company = Company::create([
        'name'       => $request->name,
        'address'    => $request->address,
        'itstate'    => $request->itstate,
        'city'       => $request->city,
        'pincode'    => $request->pincode,
        'district'   => $request->district,
        'mobile'     => $request->mobile,
        'email'      => $request->email,
        'website'    => $request->website,
        'gst_no'     => $request->gst_no,
        'pan_no'     => $request->pan_no,
        'fy_start'   => $request->fy_start,
        'fy_end'     => $request->fy_end,
        'bank_name'  => $request->bank_name,
        'account_no' => $request->account_no,
        'ifsc'       => $request->ifsc,
        'username'   => $request->username,
        'password'   => bcrypt($request->password),
        'logo'       => $logoName,
        'parent_id'  => $superadminId,
    ]);

    return redirect()->route('dashboard')->with('success', 'Company registered successfully!');
}




      public function showLoginForm() {
        return view('company.login');
    }

    // Handle login
public function login(Request $request)
{
    $request->validate([
        'login'    => 'required|string', // username or email
        'password' => 'required|string|min:6',
    ]);

    // 1. Try Company login (by username)
    $company = Company::where('username', $request->login)->first();

    if ($company && Hash::check($request->password, $company->password)) {
        session([
            'company_id'   => $company->id,
            'company_name' => $company->name,
        ]);
        return redirect()->route('sub.dashboard'); // Company dashboard
    }

    // 2. Try SuperAdmin login (by email)
    $superadmin = Superadmin::where('email', $request->login)->first();

    if ($superadmin && Hash::check($request->password, $superadmin->password)) {
        // Check expiry date
        $today = now()->format('Y-m-d');
        if ($superadmin->expiry_date < $today) {
            return back()->with('error', 'Your account has expired. Please contact the administrator.');
        }

        session([
            'superadmin_id'   => $superadmin->id,
            'superadmin_name' => $superadmin->company_name,
            'superadmin_role' => $superadmin->role ?? 'SuperAdmin',
        ]);

        return redirect()->route('superadmin.profile'); // SuperAdmin dashboard
    }

    // 3. If nothing matched
    return back()->with('error', 'Invalid username/email or password');
}


    // Logout
    public function logout(Request $request)
{
    // Clear company session
    $request->session()->forget(['company_id', 'company_name', 'company_email']);
    $request->session()->flush();

    return redirect()->route('company.login')->with('success', 'Logged out successfully.');
}

    public function dashboard()
{
    // Example stats (replace with DB queries as needed)
    $totalCompanies =Company::count();
    $totalInvoices = 125; // example
    $currentFinancialYear = '2025-2026';
      $companyId = session('company_id'); // assuming company_id stored in session
        $company = $companyId ? Company::find($companyId) : null;
  if (!$company) {
        return redirect()->route('company.login')->with('error', 'Superadmin not found.');
    }
    return view('company.dashboard', compact('totalCompanies', 'totalInvoices', 'currentFinancialYear'));
}
public function createprofile()
    {
        
        // If logged-in company, fetch details
        $companyId = session('company_id'); // assuming company_id stored in session
        $company = $companyId ? Company::find($companyId) : null;
  if (!$company) {
        return redirect()->route('company.login')->with('error', 'company not found.');
    }
        return view('company.form', compact('company'));
    }

    // Store new company
public function storeprofile(Request $request)
{

    // Validate inputs
    $request->validate([
        'name'        => 'required|string|max:255',
        'address'     => 'required|string',
        'itstate'     => 'required|string|max:100',
        'city'        => 'required|string|max:100',
        'pincode'     => 'required|digits:6',
        'district'    => 'required|string|max:100',
        'bill_no'     => 'string|max:20|unique:companies,bill_no,' . $request->id,
        'mobile'      => 'required|digits:10',
        'email'       => 'required|email|unique:companies,email,' . $request->id,
        'website'     => 'nullable|url',
        'gst_no'      => 'required|string|max:20',
        'pan_no'      => 'required|string|max:20',
        'fy_start'    => 'required|date',
        'fy_end'      => 'required|date|after:fy_start',
        'bank_name'   => 'required|string|max:100',
        'account_no'  => 'required|string|max:30',
        'ifsc'        => 'required|string|max:15',
        'username'    => 'required|string|unique:companies,username,' . $request->id,
        'password'    => $request->id ? 'nullable|string|min:6' : 'required|string|min:6',
        'logo'        => 'nullable|image|max:2048',
    ]);

    // Handle logo upload
    $logoName = $request->hasFile('logo') 
                ? time() . '_' . $request->file('logo')->getClientOriginalName() 
                : null;

    if ($request->id) {
        // Update existing company
        $company = Company::findOrFail($request->id);

        $company->update([
            'name'       => $request->name,
            'address'    => $request->address,
            'itstate'    => $request->itstate,
            'city'       => $request->city,
            'pincode'    => $request->pincode,
            'district'   => $request->district,
            'bill_no'    => $request->bill_no,
            'mobile'     => $request->mobile,
            'email'      => $request->email,
            'website'    => $request->website,
            'gst_no'     => $request->gst_no,
            'pan_no'     => $request->pan_no,
            'fy_start'   => $request->fy_start,
            'fy_end'     => $request->fy_end,
            'bank_name'  => $request->bank_name,
            'account_no' => $request->account_no,
            'ifsc'       => $request->ifsc,
            'username'   => $request->username,
            'logo'       => $logoName ?? $company->logo, // keep old if not updated
        ]);

        // Update password only if provided
        if ($request->filled('password')) {
            $company->update([
                'password' => bcrypt($request->password),
            ]);
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    } else {
        // Create new company
        Company::create([
            'name'       => $request->name,
            'address'    => $request->address,
            'itstate'    => $request->itstate,
            'city'       => $request->city,
            'pincode'    => $request->pincode,
            'district'   => $request->district,
            'bill_no'    => $request->bill_no,
            'mobile'     => $request->mobile,
            'email'      => $request->email,
            'website'    => $request->website,
            'gst_no'     => $request->gst_no,
            'pan_no'     => $request->pan_no,
            'fy_start'   => $request->fy_start,
            'fy_end'     => $request->fy_end,
            'bank_name'  => $request->bank_name,
            'account_no' => $request->account_no,
            'ifsc'       => $request->ifsc,
            'username'   => $request->username,
            'password'   => bcrypt($request->password),
            'logo'       => $logoName,
        ]);

        return redirect()->back()->with('success', 'Profile created successfully.');
    }
}

public function destroy($id)
{
    $company = Company::findOrFail($id);
    if ($company->logo) {
        @unlink(public_path('company/logo/' . $company->logo));
    }
    $company->delete();

    return redirect()->back()->with('success', 'Company deleted successfully!');
}

}
