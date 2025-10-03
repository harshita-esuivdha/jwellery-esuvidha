<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Company Login - Jewelry Portal</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    height: 100vh;
    overflow: hidden;
    background: #0e0e0e;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    position: relative;
}

/* Particle canvas */
.particles {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: 0;
}

/* Card */
.card {
    position: relative;
    z-index: 1;
    border-radius: 18px;
    border: 1px solid rgba(255, 215, 0, 0.4);
    box-shadow: 0 10px 40px rgba(255, 215, 0, 0.1);
    padding: 40px;
    background: rgba(20, 20, 20, 0.85);
    backdrop-filter: blur(8px);
    animation: fadeIn 1.5s forwards;
    opacity: 0;
}

@keyframes fadeIn { to { opacity: 1; } }

/* Animated text */
.floating-text {
    position: absolute;
    font-family: 'Playfair Display', serif;
    font-size: 3rem;
    font-weight: 700;
    color: #f5deb3;
    text-align: center;
    z-index: 2;
    width: 100%;
    top: 10%;
}

.floating-text span {
    display: inline-block;
    opacity: 0;
}

/* Animations for each direction */
@keyframes fromLeft { 0% { transform: translateX(-100%); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }
@keyframes fromRight { 0% { transform: translateX(100%); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }
@keyframes fromTop { 0% { transform: translateY(-100%); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }

.floating-text .left { animation: fromLeft 1s forwards; }
.floating-text .top { animation: fromTop 1s forwards 0.3s; }
.floating-text .right { animation: fromRight 1s forwards 0.6s; }

/* Form Inputs */
.form-control {
    border-radius: 10px;
    background: rgba(255,255,255,0.95);
    border: 1px solid #d4af37;
    color: #333;
    font-weight: 500;
}

.form-control:focus {
    border-color: #b8860b;
    box-shadow: 0 0 10px rgba(212,175,55,0.5);
}

/* Button */
.btn-login {
    background: linear-gradient(45deg, #d4af37, #f5deb3);
    color: #111;
    border-radius: 50px;
    font-weight: 600;
    font-size: 16px;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(212,175,55,0.3);
}

.btn-login:hover {
    background: linear-gradient(45deg, #f0c75e, #fff5d7);
    color: #000;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(212,175,55,0.5);
}

/* Links */
a.text-primary {
    color: #d4af37;
    font-weight: 600;
    text-decoration: none;
}
a.text-primary:hover {
    color: #f5deb3;
}
</style>
</head>
<body>

<!-- Animated Text -->
<!-- Particle canvas -->
<canvas class="particles"></canvas>

<div class="container h-100 d-flex align-items-center justify-content-center">
    <div class="col-md-5">
        <div class="card text-center shadow-lg" style="padding: 50px; border-radius: 20px; background: rgba(25,25,25,0.85); backdrop-filter: blur(10px); border: 1px solid rgba(255, 215, 0, 0.4);">

            <!-- Floating Title Inside Card -->
            <div class="floating-text mb-4" style="position: relative; top: 0; width: 100%; font-size: 2.5rem;">
                <span class="left" style="display:inline-block; color:#f5deb3; animation: fromLeft 1s forwards;">Jewelry </span>
                <span class="top" style="display:inline-block; color:#f5deb3; animation: fromTop 1s forwards 0.3s;">e-</span>
                <span class="right" style="display:inline-block; color:#f5deb3; animation: fromRight 1s forwards 0.6s;">Suvidha</span>
            </div>

            <!-- Logo -->
            <div class="mb-3">
                <img src="{{ url('public/Main logo/Reliable Logo (2).png') }}" alt="Company Logo" style="max-width:50%;">
            </div>

         

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('company.login.submit') }}">
                @csrf
                <div class="mb-3">
                    <input type="text" name="login" class="form-control form-control-lg" placeholder="Username or Email" required style="border-radius:12px; border:1px solid #d4af37; box-shadow: 0 0 8px rgba(212,175,55,0.3);">
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required style="border-radius:12px; border:1px solid #d4af37; box-shadow: 0 0 8px rgba(212,175,55,0.3);">
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-login btn-lg w-75" style="border-radius:50px; background: linear-gradient(45deg, #d4af37, #f5deb3); color:#111; box-shadow:0 5px 15px rgba(212,175,55,0.3); transition: all 0.3s ease;">Login</button>
                </div>
            </form>

           
        </div>
    </div>
</div>


<script>
const canvas = document.querySelector('.particles');
const ctx = canvas.getContext('2d');
let particlesArray = [];
let mouse = { x: null, y: null };

function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
}
resizeCanvas();

window.addEventListener('mousemove', (e) => {
    mouse.x = e.x;
    mouse.y = e.y;
});

class Particle {
    constructor() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.size = Math.random() * 2 + 1;
        this.speedX = (Math.random() - 0.5) * 0.5;
        this.speedY = (Math.random() - 0.5) * 0.5;
        this.opacity = Math.random() * 0.5 + 0.3;
    }
    update() {
        this.x += this.speedX;
        this.y += this.speedY;

        // bounce edges
        if(this.x < 0 || this.x > canvas.width) this.speedX *= -1;
        if(this.y < 0 || this.y > canvas.height) this.speedY *= -1;
    }
    draw() {
        ctx.fillStyle = `rgba(255, 223, 0, ${this.opacity})`;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fill();
    }
}

function initParticles() {
    particlesArray = [];
    for (let i = 0; i < 150; i++) particlesArray.push(new Particle());
}

function connectParticles() {
    for (let a = 0; a < particlesArray.length; a++) {
        for (let b = a; b < particlesArray.length; b++) {
            let dx = particlesArray[a].x - particlesArray[b].x;
            let dy = particlesArray[a].y - particlesArray[b].y;
            let distance = Math.sqrt(dx*dx + dy*dy);
            if(distance < 120){
                ctx.strokeStyle = `rgba(255,223,0,${1 - distance/120})`;
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(particlesArray[a].x, particlesArray[a].y);
                ctx.lineTo(particlesArray[b].x, particlesArray[b].y);
                ctx.stroke();
            }
        }

        // mouse interaction
        if(mouse.x && mouse.y){
            let dxm = particlesArray[a].x - mouse.x;
            let dym = particlesArray[a].y - mouse.y;
            let distMouse = Math.sqrt(dxm*dxm + dym*dym);
            if(distMouse < 150){
                ctx.strokeStyle = `rgba(255,223,0,${1 - distMouse/150})`;
                ctx.lineWidth = 1.2;
                ctx.beginPath();
                ctx.moveTo(particlesArray[a].x, particlesArray[a].y);
                ctx.lineTo(mouse.x, mouse.y);
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

window.addEventListener('resize', resizeCanvas);

initParticles();
animate();
</script>

</body>
</html>
