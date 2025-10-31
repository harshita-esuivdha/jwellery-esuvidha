<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Company Login | Jewelry e-Suvidha</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
/* ===== General ===== */
body {
    margin: 0;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: radial-gradient(circle at top, #1b1b1b, #0d0d0d);
    font-family: 'Poppins', sans-serif;
    color: #fff;
    overflow: hidden;
}

/* ===== Particle Background ===== */
canvas.particles {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 0;
    width: 100%;
    height: 100%;
}

/* ===== Card Container ===== */
.login-card {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 420px;
    background: rgba(25, 25, 25, 0.92);
    border-radius: 20px;
    border: 1px solid rgba(212,175,55,0.4);
    box-shadow: 0 0 35px rgba(212,175,55,0.15);
    padding: 50px 40px;
    backdrop-filter: blur(10px);
    animation: fadeIn 1.2s ease-in-out forwards;
    opacity: 0;
}

@keyframes fadeIn { to { opacity: 1; } }

/* ===== Logo / Title ===== */
.brand-title {
    font-family: 'Playfair Display', serif;
    color: #f5deb3;
    font-size: 2.3rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 0.8rem;
    letter-spacing: 1px;
}
.brand-sub {
    font-size: 0.9rem;
    color: #d4af37;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 3px;
    margin-bottom: 30px;
}

/* ===== Input Fields ===== */
.form-control {
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid #d4af37;
    font-weight: 500;
    transition: all 0.3s ease;
}
.form-control:focus {
    border-color: #f5d76e;
    box-shadow: 0 0 10px rgba(212,175,55,0.5);
}

/* ===== Button ===== */
.btn-login {
    background: linear-gradient(45deg, #d4af37, #f5deb3);
    color: #111;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 50px;
    padding: 10px 0;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(212,175,55,0.3);
}
.btn-login:hover {
    background: linear-gradient(45deg, #f8d77b, #fff2be);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(212,175,55,0.5);
}

/* ===== Footer Link ===== */
a.text-gold {
    color: #d4af37;
    text-decoration: none;
    font-weight: 500;
}
a.text-gold:hover {
    color: #fff4ce;
}

/* ===== Small Fade Text ===== */
.footer-note {
    color: #aaa;
    font-size: 0.85rem;
    text-align: center;
    margin-top: 25px;
}

/* ===== Responsive ===== */
@media (max-width: 576px) {
    .login-card { padding: 40px 25px; }
    .brand-title { font-size: 1.8rem; }
}
</style>
</head>
<body>

<canvas class="particles"></canvas>

<div class="login-card">
    <h1 class="brand-title">Jewelry e-Suvidha</h1>
    <div class="brand-sub">Company Login</div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('company.login.submit') }}">
        @csrf

        @if(session('error'))
            <div class="alert alert-danger py-2">{{ session('error') }}</div>
        @endif

        <div class="mb-3">
            <input type="text" name="login"
                class="form-control form-control-lg @error('login') is-invalid @enderror"
                placeholder="Username or Email" required>
            @error('login')
                <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <input type="password" name="password"
                class="form-control form-control-lg @error('password') is-invalid @enderror"
                placeholder="Password" required>
            @error('password')
                <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-login w-100">Login</button>
    </form>

    <div class="footer-note mt-3">
        <a href="#" class="text-gold">Forgot Password?</a>
    </div>
</div>

<!-- Particle Script -->
<script>
const canvas = document.querySelector('.particles');
const ctx = canvas.getContext('2d');
let particlesArray = [];

function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
}
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

class Particle {
    constructor() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.size = Math.random() * 2 + 0.5;
        this.speedX = (Math.random() - 0.5) * 0.3;
        this.speedY = (Math.random() - 0.5) * 0.3;
        this.opacity = Math.random() * 0.4 + 0.3;
    }
    update() {
        this.x += this.speedX;
        this.y += this.speedY;
        if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
        if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
    }
    draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(212,175,55,${this.opacity})`;
        ctx.fill();
    }
}

function initParticles() {
    particlesArray = [];
    for (let i = 0; i < 120; i++) particlesArray.push(new Particle());
}

function connectParticles() {
    for (let a = 0; a < particlesArray.length; a++) {
        for (let b = a; b < particlesArray.length; b++) {
            let dx = particlesArray[a].x - particlesArray[b].x;
            let dy = particlesArray[a].y - particlesArray[b].y;
            let distance = Math.sqrt(dx * dx + dy * dy);
            if (distance < 100) {
                ctx.strokeStyle = `rgba(212,175,55,${1 - distance / 100})`;
                ctx.lineWidth = 0.6;
                ctx.beginPath();
                ctx.moveTo(particlesArray[a].x, particlesArray[a].y);
                ctx.lineTo(particlesArray[b].x, particlesArray[b].y);
                ctx.stroke();
            }
        }
    }
}

function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    particlesArray.forEach(p => { p.update(); p.draw(); });
    connectParticles();
    requestAnimationFrame(animate);
}

initParticles();
animate();
</script>

</body>
</html>
