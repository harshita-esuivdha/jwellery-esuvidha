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
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Favicon -->
<link rel="icon" href="{{ url('public/Main logo/image.png') }}" type="image/x-icon">

<style>
/* === Body & Background === */
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    background: linear-gradient(to bottom, #1a1a1a, #121212);
    color: #f5deb3;
    overflow-x: hidden;
    min-height: 100vh;
}

/* Particle background */
#particles-bg {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    z-index: 0;
    opacity: 0.25;
}

/* === Sidebar === */
.sidebar {
    position: fixed;
    top: 0; left: 0;
    width: 260px;
    height: 100vh;
    background: rgba(30,30,30,0.95);
    backdrop-filter: blur(8px);
    border-right: 1px solid rgba(255,255,255,0.1);
    border-radius: 0 20px 20px 0;
    padding: 25px;
    z-index: 999;
    transition: 0.3s;
    box-shadow: 0 0 25px rgba(255,223,0,0.05);
}
.sidebar .logo img {
    max-width: 150px;
    border-radius: 12px;
    transition: transform 0.3s, box-shadow 0.3s;
}
.sidebar .logo img:hover {
    transform: scale(1.05);
    box-shadow: 0 0 15px #f5deb3, 0 0 25px #00ffff inset;
}
.sidebar a {
    display: flex;
    align-items: center;
    color: #f5deb3;
    padding: 12px 18px;
    margin: 10px 0;
    border-radius: 15px;
    font-weight: 500;
    text-decoration: none;
    font-size: 1rem;
    transition: 0.3s;
    position: relative;
}
.sidebar a::before {
    content: '';
    position: absolute;
    left: 0; top: 0;
    width: 0%; height: 100%;
    background: rgba(255,223,0,0.2);
    border-radius: 15px;
    transition: 0.3s;
}
.sidebar a:hover::before { width: 100%; }
.sidebar a i { font-size: 1.2rem; margin-right: 12px; transition: transform 0.3s; }
.sidebar a:hover i { transform: scale(1.2) rotate(5deg); }
.sidebar a.active-link {
    background: linear-gradient(90deg, #00ffff, #f5deb3);
    color: #000;
    box-shadow: 0 0 15px #f5deb3, 0 0 25px #00ffff inset;
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
    background: rgba(0,0,0,0.05);
    backdrop-filter: blur(6px);
    border-radius: 15px;
    padding: 15px 25px;
    box-shadow: 0 2px 15px rgba(255,223,0,0.1);
}
.navbar .navbar-brand {
    font-family: 'Orbitron', sans-serif;
    font-weight: 700;
    font-size: 1.2rem;
    background: linear-gradient(90deg, #00ffff, #f5deb3);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: gradientSlide 3s linear infinite;
}
@keyframes gradientSlide {
    0% { background-position: 0%; }
    50% { background-position: 100%; }
    100% { background-position: 0%; }
}

/* === Dashboard Cards === */


/* === Animations === */
@keyframes cardFadeIn {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}

/* === Mobile Sidebar === */
@media(max-width: 768px) {
    .sidebar { left: -280px; }
    .sidebar.active { left: 0; }
    .main-content { margin-left: 0 !important; }
    #sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.3); z-index: 998; }
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

        <h6 class="text-uppercase px-3 mt-3 mb-2" style="color:#bbb;">Profile</h6>
        <a href="{{ route('Company.profile') }}" class="{{ request()->routeIs('Company.profile') ? 'active-link' : '' }}"><i class="bi bi-person"></i> Edit Profile</a>

        <h6 class="text-uppercase px-3 mt-3 mb-2" style="color:#bbb;">Sale</h6>
        <a href="{{ url('admin/customers') }}"><i class="bi bi-person-plus-fill"></i> View Customer</a>
        <a href="{{ route('admin.customers.create') }}"><i class="bi bi-person-plus-fill"></i> Add Customer</a>
    @endif

    <a href="{{ route('company.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="d-flex align-items-center px-3 py-2 mt-4 text-white">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
    </a>
    <form id="logout-form" action="{{ route('company.logout') }}" method="POST" style="display:none;">@csrf</form>
</div>

<!-- Sidebar Overlay -->
<div id="sidebar-overlay"></div>

<!-- Main Content -->
<div class="main-content">
    <nav class="navbar d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-outline-light d-md-none" id="sidebarToggle">
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
        if(this.opacity>0.5||this.opacity<0.1) this.opacityChange=-this.opacityChange;
        if(this.x<0) this.x=canvas.width; if(this.x>canvas.width) this.x=0;
        if(this.y<0) this.y=canvas.height; if(this.y>canvas.height) this.y=0;
    }
    draw() {
        const gradient = ctx.createRadialGradient(this.x,this.y,0,this.x,this.y,this.size*2);
        gradient.addColorStop(0, `rgba(0,255,255, ${this.opacity})`);
        gradient.addColorStop(0.5, `rgba(245,222,179, ${this.opacity*0.3})`);
        gradient.addColorStop(1, 'rgba(0,255,255,0)');
        ctx.fillStyle = gradient;
        ctx.beginPath(); ctx.arc(this.x,this.y,this.size*2,0,Math.PI*2); ctx.fill();
    }
}

function initParticles() { particlesArray=[]; for(let i=0;i<150;i++) particlesArray.push(new Particle()); }
function animate() { ctx.clearRect(0,0,canvas.width,canvas.height); particlesArray.forEach(p=>{p.update();p.draw();}); requestAnimationFrame(animate); }
window.addEventListener('resize',()=>{resizeCanvas();initParticles();});
canvas.addEventListener('mousemove', (e)=>{
    particlesArray.forEach(p=>{
        const dx = p.x - e.clientX;
        const dy = p.y - e.clientY;
        const dist = Math.sqrt(dx*dx + dy*dy);
        if(dist < 100) { p.x += dx*0.01; p.y += dy*0.01; }
    });
});
initParticles(); animate();
</script>

</body>
</html>
