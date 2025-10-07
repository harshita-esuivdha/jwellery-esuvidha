<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard')</title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Favicon -->
<link rel="icon" href="{{ url('public/Main logo/image.png') }}" type="image/x-icon">

<style>
/* === Body & Background === */
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    background: linear-gradient(to bottom right, #fffdf3, #fff7d6);
    color: #3c2f0d;
    overflow-x: hidden;
    min-height: 100vh;
}

/* Animated subtle background (golden sparks) */
#particles-bg {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    z-index: 0;
    opacity: 0.35;
}

/* === Sidebar === */
.sidebar {
    position: fixed;
    top: 0; left: 0;
    width: 260px;
    height: 100vh;
    background: linear-gradient(180deg, #fff8dc, #fceabb);
    border-right: 2px solid #e6c200;
    border-radius: 0 20px 20px 0;
    padding: 25px;
    z-index: 999;
    transition: 0.3s;
    box-shadow: 0 0 20px rgba(230, 194, 0, 0.2);
}
.sidebar .logo img {
    max-width: 150px;
    border-radius: 12px;
    margin-bottom: 20px;
}
.sidebar a {
    display: flex;
    align-items: center;
    color: #3c2f0d;
    padding: 12px 18px;
    margin: 10px 0;
    border-radius: 15px;
    font-weight: 500;
    text-decoration: none;
    font-size: 1rem;
    transition: 0.3s;
    position: relative;
}
.sidebar a:hover {
    background: rgba(230, 194, 0, 0.15);
    color: #c49a00;
}
.sidebar a i {
    font-size: 1.2rem;
    margin-right: 12px;
    transition: transform 0.3s;
}
.sidebar a:hover i {
    transform: scale(1.2) rotate(5deg);
}
.sidebar a.active-link {
    background: linear-gradient(90deg, #f7d046, #ffe98a);
    color: #3c2f0d;
    font-weight: 600;
    box-shadow: 0 0 15px rgba(230, 194, 0, 0.5);
}

/* === Main Content === */
.main-content {
    margin-left: 260px;
    padding: 30px 35px;
    position: relative;
    transition: margin-left 0.3s;
}

/* === Navbar === */
.navbar {
    background: #fffef9;
    border-radius: 15px;
    padding: 15px 25px;
    border: 1px solid rgba(194, 150, 0, 0.15);
    box-shadow: 0 2px 12px rgba(194, 150, 0, 0.15);
}
.navbar .navbar-brand {
    font-family: 'Orbitron', sans-serif;
    font-weight: 700;
    font-size: 1.2rem;
    color: #c49a00;
    text-shadow: 0 0 8px rgba(230,194,0,0.6);
}

/* === Mobile Sidebar === */
@media(max-width: 768px) {
    .sidebar { left: -280px; }
    .sidebar.active { left: 0; }
    .main-content { margin-left: 0 !important; }
    #sidebar-overlay {
        display: none;
        position: fixed; top: 0; left: 0;
        width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.3);
        z-index: 998;
    }
    #sidebar-overlay.active { display: block; }
}
</style>
</head>
<body>

<!-- Particle Background -->
<canvas id="particles-bg"></canvas>

@php
    $superadmin = session('superadmin_id') ? \App\Models\Superadmin::find(session('superadmin_id')) : null;
    $company = session('company_id') ? \App\Models\Company::find(session('company_id')) : null;
@endphp

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="logo">
        @if($superadmin)
            <img src="{{ url('public/Main logo/Reliable Logo (2).png') }}" alt="Logo">
        @elseif($company)
            <img src="{{ url('public/company/logo/'.$company->logo) }}" alt="{{ $company->name }}">
        @endif
    </div>

    @if($superadmin)
        @if($superadmin->role === 'SuperAdmin')
            <a href="{{ route('superadmin.register.login') }}"><i class="bi bi-person-plus-fill"></i> Register</a>
            <a href="{{ route('superadmin.dashboard') }}"><i class="bi bi-speedometer2"></i> Show Admin</a>
            <a href="{{ route('companies.public') }}"><i class="bi bi-building"></i> View Companies</a>
            <a href="{{ route('superadmin.profile') }}"><i class="bi bi-person"></i> Profile</a>
        @endif
        @if($superadmin->role === 'Admin')
            <a href="{{ route('companies.public') }}"><i class="bi bi-building"></i> View Companies</a>
            <a href="{{ route('superadmin.profile') }}"><i class="bi bi-person"></i> Profile</a>
        @endif
    @endif

    @if($company)
        <a href="{{ route('sub.dashboard') }}" class="{{ request()->routeIs('sub.dashboard') ? 'active-link' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>

        <h6 class="text-uppercase px-3 mt-3 mb-2" style="color:#8a6d00;">Profile</h6>
        <a href="{{ route('Company.profile') }}" class="{{ request()->routeIs('Company.profile') ? 'active-link' : '' }}"><i class="bi bi-person"></i> Edit Profile</a>

        <h6 class="text-uppercase px-3 mt-3 mb-2" style="color:#8a6d00;">Sale</h6>
   <a href="{{ url('admin/customers') }}" 
   class="{{ request()->is('admin/customers') ? 'active-link' : '' }}">
   <i class="bi bi-people"></i> View Customer
</a>
        <a href="{{ route('admin.customers.create') }}"class="{{ request()->routeIs('admin.customers.create') ? 'active-link' : '' }}"><i class="bi bi-person-plus-fill"></i> Add Customer</a>
         <h6 class="text-uppercase px-3 mt-3 mb-2" style="color:#8a6d00;">Items</h6>
<a href="{{ route('items.index') }}" 
   class="{{ request()->routeIs('items.index') ? 'active-link' : '' }}">
   <i class="bi bi-people"></i> View Items
</a>

<a href="{{ route('items.create') }}" 
   class="{{ request()->routeIs('items.create') ? 'active-link' : '' }}">
   <i class="bi bi-plus-circle"></i> Add Item
</a>

<h6 class="text-uppercase px-3 mt-3 mb-2" style="color:#8a6d00;">Bill</h6>

<!-- Create Bill -->
<a href="{{ route('billing.create') }}" 
   class="{{ request()->routeIs('billing.create') ? 'active-link' : '' }}">
   <i class="bi bi-people"></i> Create Bill
</a>

<!-- View Bill -->
<a href="{{ route('invoices.history') }}" 
   class="{{ request()->routeIs('invoices.history') ? 'active-link' : '' }}">
   <i class="bi bi-plus-circle"></i> View Bill
</a>



    @endif












    <a href="{{ route('company.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="d-flex align-items-center px-3 py-2 mt-4 text-dark">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
    </a>
    <form id="logout-form" action="{{ route('company.logout') }}" method="POST" style="display:none;">@csrf</form>
</div>

<!-- Sidebar Overlay -->
<div id="sidebar-overlay"></div>

<!-- Main Content -->
<div class="main-content">
    <nav class="navbar d-flex justify-content-between align-items-center mb-4"style=" background: linear-gradient(180deg, #fff8dc, #fceabb);
    border-right: 2px solid #e6c200;">
        <button class="btn btn-outline-warning d-md-none" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <span class="navbar-brand mb-0">Welcome, {{ session('company_name') }}</span>
    </nav>

    <div class="row g-4">
        @yield('content')
    </div>
</div>

<!-- Scripts -->
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

/* Particle background */
const canvas = document.getElementById('particles-bg');
const ctx = canvas.getContext('2d');

function resizeCanvas() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
resizeCanvas();

let particlesArray = [];
class Particle {
    constructor() { this.reset(); }
    reset() {
        this.x = Math.random()*canvas.width;
        this.y = Math.random()*canvas.height;
        this.size = Math.random()*1.5 + 1;
        this.speedX = (Math.random()-0.5)*0.3;
        this.speedY = (Math.random()-0.5)*0.3;
        this.opacity = Math.random()*0.3 + 0.1;
        this.opacityChange = (Math.random()*0.005)+0.002;
    }
    update() {
        this.x += this.speedX; this.y += this.speedY;
        this.opacity += this.opacityChange;
        if(this.opacity>0.6||this.opacity<0.1) this.opacityChange=-this.opacityChange;
        if(this.x<0) this.x=canvas.width; if(this.x>canvas.width) this.x=0;
        if(this.y<0) this.y=canvas.height; if(this.y>canvas.height) this.y=0;
    }
    draw() {
        const gradient = ctx.createRadialGradient(this.x,this.y,0,this.x,this.y,this.size*3);
        gradient.addColorStop(0, `rgba(255,215,0, ${this.opacity})`);
        gradient.addColorStop(0.5, `rgba(255,255,255, ${this.opacity*0.3})`);
        gradient.addColorStop(1, 'rgba(255,215,0,0)');
        ctx.fillStyle = gradient;
        ctx.beginPath(); ctx.arc(this.x,this.y,this.size*2,0,Math.PI*2); ctx.fill();
    }
}
function initParticles() { particlesArray=[]; for(let i=0;i<150;i++) particlesArray.push(new Particle()); }
function animate() { ctx.clearRect(0,0,canvas.width,canvas.height); particlesArray.forEach(p=>{p.update();p.draw();}); requestAnimationFrame(animate); }
window.addEventListener('resize',()=>{resizeCanvas();initParticles();});
initParticles(); animate();
</script>
<script>
/* Existing particles code remains as-is */

class FloatingStar {
    constructor() {
        this.reset();
    }
    reset() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.size = Math.random() * 1.5 + 0.5;
        this.speedX = (Math.random() - 0.5) * 0.2;
        this.speedY = -0.2 - Math.random() * 0.3; // slowly rising
        this.opacity = Math.random() * 0.5 + 0.2;
    }
    update() {
        this.x += this.speedX;
        this.y += this.speedY;
        if (this.y < 0) this.reset(); // respawn at bottom
        if (this.x < 0) this.x = canvas.width;
        if (this.x > canvas.width) this.x = 0;
    }
    draw() {
        ctx.fillStyle = `rgba(255, 223, 0, ${this.opacity})`;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fill();
    }
}

let starsArray = [];
function initStars() {
    starsArray = [];
    for (let i = 0; i < 50; i++) starsArray.push(new FloatingStar());
}

// Overwrite animate function to include stars
function animateEnhanced() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Draw particles
    particlesArray.forEach(p => { p.update(); p.draw(); });

    // Draw floating stars
    starsArray.forEach(s => { s.update(); s.draw(); });

    requestAnimationFrame(animateEnhanced);
}

initStars();
animateEnhanced();
</script>

</body>
</html>
