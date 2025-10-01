<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E-Suvidh Billing | Company Registration</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
/* Background & Fonts */
body {
    background: linear-gradient(135deg, #fdfbfb, #ebedee);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Card Styling */
.card {
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    overflow: hidden;
    background: #ffffff;
    padding: 30px;
}

/* Step Animations */
.step {
    display: none;
    animation: fadeIn 0.5s ease-in-out;
}
.step.active {
    display: block;
}
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(20px);}
    to {opacity: 1; transform: translateY(0);}
}

/* Input Styling */
.form-control {
    border-radius: 12px;
    border: 1px solid #cbd5e0;
    padding: 10px 15px;
    transition: all 0.3s ease-in-out;
}
.form-control:focus {
    box-shadow: 0 0 8px rgba(100, 149, 237, 0.3);
    border-color: #6495ed;
}

/* Invalid Input */
.form-control.is-invalid {
    border-color: red;
    box-shadow: none;
}

/* Labels */
.form-label {
    font-weight: 600;
    color: #333333;
}

/* Required Asterisk */
.required:after {
    content: " *";
    color: red;
    font-weight: bold;
}

/* Error message styling */
.error {
    color: red;
    font-size: 0.85em;
    margin-top: 4px;
    display: none;
}

/* Buttons */
.btn-next, .btn-prev {
    min-width: 120px;
    border-radius: 50px;
    transition: all 0.3s ease;
}
.btn-next {
    background: #a8dadc;
    color: #1d3557;
    border: none;
}
.btn-next:hover {
    background: #89c2d9;
    color: #1d3557;
}
.btn-prev {
    background: #f1faee;
    color: #457b9d;
    border: none;
}
.btn-prev:hover {
    background: #e5f0f4;
    color: #457b9d;
}

/* Progress Bar */
.progress {
    height: 10px;
    border-radius: 10px;
    background-color: #e0e0e0;
    margin-bottom: 30px;
}
.progress-bar {
    background: linear-gradient(90deg, #a8dadc, #457b9d);
    border-radius: 10px;
    transition: width 0.4s ease;
}

/* Step Headings */
h5.text-primary {
    color: #457b9d;
    margin-bottom: 15px;
    font-weight: 600;
}

/* File Input */
input[type="file"] {
    padding: 5px;
}

/* Responsive tweaks */
@media(max-width:768px){
    .btn-next, .btn-prev{
        width: 48%;
    }
}

/* Header animation */
h3.text-center {
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    color: #1d3557;
    animation: slideFadeBounce 1s ease-out;
}
@keyframes slideFadeBounce {
    0% { opacity: 0; transform: translateY(-30px); }
    80% { opacity: 1; transform: translateY(5px); }
    100% { transform: translateY(0); }
}
</style>
</head>
<body>

<div class="container mt-5">
    <div class="card">
       <h3 class="text-center mb-4">Company Registration</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <!-- Progress Bar -->
        <div class="progress mb-4">
            <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 20%"></div>
        </div>

        <form id="regForm" action="{{ route('company.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Step 1: Basic Details -->
            <div class="step active">
                <h5 class="text-primary">Basic Details</h5>
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label required">Company Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Company Name" required>
                        <div class="error">Company Name is required</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Address</label>
                        <input type="text" name="address" class="form-control" placeholder="Address" required>
                        <div class="error">Address is required</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">State (IT State)</label>
                        <input type="text" name="itstate" class="form-control" placeholder="State" required>
                        <div class="error">State is required</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">City</label>
                        <input type="text" name="city" class="form-control" placeholder="City" required>
                        <div class="error">City is required</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Pincode</label>
                        <input type="text" name="pincode" class="form-control" placeholder="Pincode" required>
                        <div class="error">Pincode is required</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">District</label>
                        <input type="text" name="district" class="form-control" placeholder="District" required>
                        <div class="error">District is required</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Mobile</label>
                        <input type="text" name="mobile" class="form-control" placeholder="Mobile" required>
                        <div class="error">Mobile number is required</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                        <div class="error">Email is required</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Website (Optional)</label>
                        <input type="url" name="website" class="form-control" placeholder="Website">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company Logo (Optional)</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>

            <!-- Step 2: Registration Details -->
         <!-- Step 2: Registration Details -->
<div class="step">
    <h5 class="text-primary">Registration Details</h5>
    <div class="row g-3 mt-2">
        <div class="col-md-6">
            <label class="form-label">GST Number</label>
            <input type="text" name="gst_no" class="form-control" placeholder="GST Number">
            <div class="error">GST Number is required</div> <!-- You can remove this if you want -->
        </div>
        <div class="col-md-6">
            <label class="form-label">PAN Number</label>
            <input type="text" name="pan_no" class="form-control" placeholder="PAN Number">
            <div class="error">PAN Number is required</div> <!-- Remove this too if optional -->
        </div>
    </div>
</div>

            <!-- Step 3: Financial Year -->
            <div class="step">
                <h5 class="text-primary">Financial Year</h5>
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label required">Financial Year Start</label>
                        <input type="date" name="fy_start" class="form-control" required>
                        <div class="error">Start date is required</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Financial Year End</label>
                        <input type="date" name="fy_end" class="form-control" required>
                        <div class="error">End date is required</div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Bank Details -->
            <div class="step">
                <h5 class="text-primary">Bank Details</h5>
                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <label class="form-label required">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" required>
                        <div class="error">Bank Name is required</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Account Number</label>
                        <input type="text" name="account_no" class="form-control" required>
                        <div class="error">Account Number is required</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">IFSC Code</label>
                        <input type="text" name="ifsc" class="form-control" required>
                        <div class="error">IFSC Code is required</div>
                    </div>
                </div>
            </div>

            <!-- Step 5: Login Credentials -->
            <div class="step">
                <h5 class="text-primary">Login Credentials</h5>
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label required">Username</label>
                        <input type="text" name="username" class="form-control" required>
                        <div class="error">Username is required</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <div class="error">Password is required</div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-prev" onclick="nextPrev(-1)">Previous</button>
                <button type="button" class="btn btn-next" onclick="nextPrev(1)">Next</button>
            </div>

        </form>
    </div>
</div>

<div class="text-center mt-3">
    <span class="text-muted">Already have an account? </span>
    <a href="{{ route('company.login') }}" class="text-primary fw-semibold">Login Here</a>
</div>

<script>
let currentStep = 0;
showStep(currentStep);

function showStep(n) {
    const steps = document.getElementsByClassName("step");
    steps[n].classList.add("active");
    document.querySelector(".btn-prev").style.display = n == 0 ? "none" : "inline-block";
    document.querySelector(".btn-next").innerHTML = n == (steps.length - 1) ? "Submit" : "Next";

    const progress = ((n + 1) / steps.length) * 100;
    document.getElementById("progress-bar").style.width = progress + "%";
}

function validateStep(stepIndex) {
    let valid = true;
    const step = document.getElementsByClassName("step")[stepIndex];
    const inputs = step.querySelectorAll("input[required], select[required]");
    
    inputs.forEach(input => {
        const errorDiv = input.nextElementSibling;
        if (input.value.trim() === "") {
            input.classList.add("is-invalid");
            if (errorDiv && errorDiv.classList.contains("error")) errorDiv.style.display = "block";
            valid = false;
        } else {
            input.classList.remove("is-invalid");
            if (errorDiv && errorDiv.classList.contains("error")) errorDiv.style.display = "none";
        }
    });
    return valid;
}

function nextPrev(n) {
    const steps = document.getElementsByClassName("step");
    if (n === 1 && !validateStep(currentStep)) return false;

    steps[currentStep].classList.remove("active");
    currentStep += n;

    if (currentStep >= steps.length) {
        document.getElementById("regForm").submit();
        return false;
    }
    showStep(currentStep);
}
</script>

</body>
</html>
