<!-- resources/views/layouts/company.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard')</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Favicon -->
<link rel="icon" href="{{ url('public/Main logo/image.png') }}" type="image/x-icon">
<style>
body { font-family: 'Poppins', sans-serif; background: #f4f6f9; margin:0; }

/* Sidebar */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100vh;
    background: #457b9d;
    color: #fff;
    overflow-y: auto;
    transition: all 0.3s;
    z-index: 999;
}
.sidebar a {
    color: #fff;
    text-decoration: none;
    display: block;
    padding: 12px;
    border-radius: 8px;
    margin: 4px 0;
    transition: all 0.3s;
}
.sidebar a:hover, .sidebar a.active-link { background: #1d3557; }

/* Main content */
.main-content {
    margin-left: 250px;
    flex-grow: 1;
    padding: 20px;
}

/* Navbar */
.navbar { background: #a8dadc; border-radius: 10px; }
.navbar-brand { color: #1d3557 !important; font-weight: 600; }

/* Mobile Sidebar */
@media(max-width: 768px) {
    .sidebar { left: -260px; }
    .sidebar.active { left: 0; }
    .main-content { margin-left: 0 !important; }

    #sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 100vw;
        background: rgba(0,0,0,0.4);
        z-index: 998;
    }
    #sidebar-overlay.active { display: block; }
}
</style>
</head>
<body>

@php
    $superadmin = session('superadmin_id') ? \App\Models\Superadmin::find(session('superadmin_id')) : null;
    $company = session('company_id') ? \App\Models\Company::find(session('company_id')) : null;
@endphp

<!-- Sidebar -->
<div class="sidebar p-3" id="sidebar">
    @if($superadmin)
        <div class="text-center mb-3">
            <img src="{{ url('public/Main logo/Reliable Logo (2).png') }}" alt="Company Logo" style="max-width:200px;">
        </div>
    @endif
    @if($company)
        <div class="text-center mb-3">
            <img src="{{ url('public/company/logo/'.$company->logo) }}" alt="{{ $company->name }}" style="max-width:200px;">
        </div>
    @endif

    <!-- Sidebar links (same as before) -->
    @if($superadmin)
        @if($superadmin->role === 'SuperAdmin')
            <a href="{{ route('superadmin.register.login') }}"><i class="bi bi-person-plus-fill me-2"></i> Register</a>
            <a href="{{ route('superadmin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Show Admin</a>
            <a href="{{ route('companies.public') }}"><i class="bi bi-building me-2"></i> View Companies</a>
            <a href="{{ route('superadmin.profile') }}"><i class="bi bi-person me-2"></i> Profile</a>
        @endif
        @if($superadmin->role === 'Admin')
            <a href="{{ route('companies.public') }}"><i class="bi bi-building me-2"></i> View Companies</a>
            <a href="{{ route('superadmin.profile') }}"><i class="bi bi-person me-2"></i> Profile</a>
        @endif
    @endif

    @if($company)
        <a class="{{ request()->routeIs('sub.dashboard') ? 'active-link' : '' }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>

        <h6 class="text-white text-uppercase px-3 mb-2 mt-3">Profile</h6>
        <a href="{{ route('Company.profile') }}" class="{{ request()->routeIs('Company.profile') ? 'active-link' : '' }}"><i class="bi bi-person me-2"></i> Edit Profile</a>

        

        <h6 class="text-white text-uppercase px-3 mb-2 mt-3">Sale</h6>
        <a href="{{ url('admin/customers') }}"><i class="bi bi-person-plus-fill me-2"></i> View Customer</a>
        <a href="{{ route('admin.customers.create') }}"><i class="bi bi-person-plus-fill me-2"></i> Add Customer</a>
      
    @endif

    <a href="{{ route('company.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="d-flex align-items-center px-3 py-2 mt-4 text-white">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
    </a>
    <form id="logout-form" action="{{ route('company.logout') }}" method="POST" style="display: none;">@csrf</form>
</div>

<!-- Overlay for mobile -->
<div id="sidebar-overlay"></div>

<!-- Main content -->
<div class="main-content flex-grow-1 p-2">
    <!-- Top navbar -->
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm rounded-3 p-2 d-flex justify-content-between align-items-center">
        <!-- Mobile sidebar toggle -->
        <button class="btn  d-md-none me-2" id="sidebarToggle" >
            <i class="bi bi-list"></i>
        </button>

        <!-- Page title -->
        <span class="navbar-brand mb-0 flex-grow-1 text-truncate" style="font-weight: 600; font-size: 1.1rem;">
             Welcome, {{ session('company_name') }}
        </span>

        <!-- Welcome text (hidden on very small screens if needed) -->
       
    </nav>

    <!-- Content -->
    <div class="mt-3">
        @yield('content')
    </div>
</div>

<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebar-overlay');
const toggleBtn = document.getElementById('sidebarToggle');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
});
overlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
});
</script>

</body>
</html>
