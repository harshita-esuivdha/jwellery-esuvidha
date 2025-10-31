@extends('company.dashboard')

@section('content')
<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow-lg p-4" style="max-width: 800px; width: 100%; border-radius: 20px;">
        
        <h3 class="text-center mb-4">Profile</h3>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('superadmin.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Company Name</label>
                    <input type="text" name="company_name" class="form-control form-control-lg" value="{{ $superadmin->company_name }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control form-control-lg" value="{{ $superadmin->email }}" required>
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Mobile Number</label>
                    <input type="text" name="mobile" class="form-control form-control-lg" value="{{ $superadmin->mobile }}" required>
                </div>

                <div class="col-md-6">
    <label class="form-label fw-bold">
        Password <small class="text-muted">(leave blank to keep current)</small>
    </label>
    <div class="input-group">
        <input type="password" name="password" id="passwordField"
               class="form-control form-control-lg"
               placeholder="New Password">
        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
            <i class="fa fa-eye"></i>
        </button>
    </div>
</div>

<!-- Font Awesome (for the eye icon) -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordField = document.getElementById('passwordField');
    const icon = this.querySelector('i');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});
</script>

            </div>

            <div class="mb-3 mt-2">
                <label class="form-label fw-bold">Address</label>
                <textarea name="address" class="form-control form-control-lg" rows="2" required>{{ $superadmin->address }}</textarea>
            </div>

            <div class="d-flex justify-content-center mt-3">
                <button type="submit" class="btn btn-success btn-lg fw-bold px-5">Update Profile</button>
            </div>
        </form>
    </div>
</div>

{{-- Optional Custom Styling --}}
<style>
    .card {
        border: none;
    }

    .form-label {
        font-weight: 600;
    }

    .btn-success {
        background: linear-gradient(90deg, #28a745, #218838);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background: linear-gradient(90deg, #218838, #28a745);
        transform: scale(1.02);
    }

    .form-control {
        border-radius: 12px;
        transition: all 0.3s ease;
    }
</style>
@endsection
