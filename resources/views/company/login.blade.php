<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Company Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
/* Background animation */
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    height: 100vh;
    overflow: hidden;
    background: linear-gradient(270deg, #172b33, #203a43, #2c5364);
    background-size: 600% 600%;
    animation: gradientBG 20s ease infinite;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
}

@keyframes gradientBG {
    0% {background-position: 0% 50%;}
    50% {background-position: 100% 50%;}
    100% {background-position: 0% 50%;}
}

/* Particle background */
.particles {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: 0;
}

/* Card animation */
.card {
    position: relative;
    z-index: 1;
    border-radius: 20px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.1);
    padding: 50px;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
    transform: translateY(-20px);
    animation: floatCard 2s ease-in-out infinite alternate, fadeIn 1s forwards;
    opacity: 0;
    transition: transform 0.3s ease;
}

@keyframes floatCard {
    0% {transform: translateY(-20px);}
    100% {transform: translateY(0);}
}

@keyframes fadeIn {
    to {opacity: 1;}
}

/* Heading animation */
h3 {
    font-weight: 700;
    color: #172b33;
    text-shadow: 0 0 5px #b0c4de, 0 0 10px #b0c4de;
    position: relative;
    animation: fadeIn 1.5s ease forwards;
}

/* Form inputs */
.form-control {
    border-radius: 12px;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.9);
    border: 1px solid #b0c4de;
    color: #172b33;
}

.form-control::placeholder {
    color: #6c757d;
}

/* Button */
.btn-login {
    background: #172b33;
    color: #fff;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 0 10px rgba(0,123,255,0.5);
}

.btn-login:hover {
    background: #0056b3;
    color:#fff;
    transform: scale(1.05);
    box-shadow: 0 0 20px rgba(0,123,255,0.5);
}

/* Links */
a.text-primary {
    position: relative;
    text-decoration: none;
    font-weight: 600;
    color: #007bff;
    transition: 0.3s ease;
}

a.text-primary:hover::after {
    width: 100%;
    left: 0;
    background: #007bff;
}
</style>
</head>
<body>

<!-- Particle canvas -->
<canvas class="particles"></canvas>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">

                <!-- Logo Section -->
                <div class="text-center mb-3">
                    <img src="{{ url('public/Main logo/Reliable Logo (2).png') }}" alt="Company Logo" style="max-width:80%;">
                </div>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('company.login.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="login" class="form-control" placeholder="Username or Email" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-login" style="width:50%;">Login</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Particles JS -->
<script>
const canvas = document.querySelector('.particles');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let particlesArray = [];

class Particle {
    constructor(x, y, size, speedX, speedY) {
        this.x = x;
        this.y = y;
        this.size = size;
        this.speedX = speedX;
        this.speedY = speedY;
    }
    update() {
        this.x += this.speedX;
        this.y += this.speedY;
        if(this.x < 0 || this.x > canvas.width) this.speedX = -this.speedX;
        if(this.y < 0 || this.y > canvas.height) this.speedY = -this.speedY;
    }
    draw() {
        ctx.fillStyle = '#007bff'; // bright blue particles
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI*2);
        ctx.fill();
    }
}

function init() {
    particlesArray = [];
    for(let i=0;i<100;i++){
        let size = Math.random()*2+1;
        let x = Math.random()*canvas.width;
        let y = Math.random()*canvas.height;
        let speedX = (Math.random()-0.5)*1;
        let speedY = (Math.random()-0.5)*1;
        particlesArray.push(new Particle(x,y,size,speedX,speedY));
    }
}

function animate() {
    ctx.clearRect(0,0,canvas.width,canvas.height);
    particlesArray.forEach(p=>{p.update(); p.draw();});
    requestAnimationFrame(animate);
}

window.addEventListener('resize', function(){
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    init();
});

init();
animate();
</script>

</body>
</html>
