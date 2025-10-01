@extends('company.dashboard')

@section('content')
<style>
    .action-buttons {
        text-align: center;
        margin-bottom: 30px;
    }

    .action-buttons a {
        margin: 0 10px;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-delete {
        background-color: #dc3545;
        color: #fff;
        border: none;
        padding: 5px 15px;
        border-radius: 5px;
        transition: 0.3s;
    }

    .btn-delete:hover {
        background-color: #c82333;
        color: #fff;
    }
</style>

<h3 class="text-center mb-4">Registered Companies</h3>

<div class="shadow-sm p-4">
    <div class="table-responsive">
        <!-- Login/Register Buttons -->
        <div class="action-buttons d-flex justify-content-center gap-3 mt-4">
            {{-- Logout --}}
            <a href="#" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
               class="btn btn-danger px-4 py-2">
                <i class="bi bi-box-arrow-right me-2"></i> Login
            </a>
            <form id="logout-form" action="{{ route('company.logout') }}" method="POST" class="d-none">
                @csrf
            </form>

            {{-- Register --}}
            <a href="{{ route('company.register') }}" class="btn btn-primary px-4 py-2">
                <i class="bi bi-person-plus me-2"></i> Register
            </a>
        </div>

        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Logo</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>District</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Website</th>
                    <th>Action</th> {{-- Added column for Delete --}}
                </tr>
            </thead>
            <tbody>
                @foreach($companies as $company)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <img src="{{ url('public/company/logo/' . $company->logo) }}" 
                                 style="width: 50px; height: 50px; object-fit: contain; border-radius: 8px;">
                        </td>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->address }}</td>
                        <td>{{ $company->city }}</td>
                        <td>{{ $company->district }}</td>
                        <td>{{ $company->mobile }}</td>
                        <td>{{ $company->email }}</td>
                        <td>{{ $company->website ?? '-' }}</td>
                    
                            {{-- Delete Button --}}
                            <td>
    {{-- Delete Button --}}
    <form action="{{ route('company.destroy', ['id' => $company->id]) }}" 
          method="POST" 
          onsubmit="return confirm('Are you sure you want to delete this company?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-delete">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</td>

                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
