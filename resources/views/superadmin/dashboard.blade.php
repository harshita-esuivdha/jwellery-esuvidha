@extends('company.dashboard')

@section('content')
<div class="container mt-5">
    <h3>Superadmin Dashboard</h3>

    {{-- Filter Dropdown --}}
 {{-- Filter Section --}}
<form method="GET" class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-bold">Filter by Expiry</label>
            <select name="expiry_filter" class="form-select">
                <option value="">All</option>
                <option value="expired" {{ $filter == 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="expiring_soon" {{ $filter == 'expiring_soon' ? 'selected' : '' }}>Expiring Soon (Next 30 Days)</option>
                <option value="active" {{ $filter == 'active' ? 'selected' : '' }}>Active</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">From Date</label>
            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">To Date</label>
            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
        </div>

        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
        </div>
    </div>
</form>


    @if($superadmins->isEmpty())
        <div class="alert alert-info mt-4">No superadmins found.</div>
    @else
        {{-- Success / Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <table class="table table-bordered mt-4 align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Company Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Expiry Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($superadmins as $superadmin)
                    @php
                        $isExpired = $superadmin->expiry_date->lt(now());
                        $isExpiringSoon = $superadmin->expiry_date->between(now(), now()->addDays(30));
                    @endphp

                    <tr 
                        @if($isExpired)
                            style="background-color: #ffe6e6;" {{-- Light red for expired --}}
                        @elseif($isExpiringSoon)
                            style="background-color: #fff3cd;" {{-- Light yellow for expiring soon --}}
                        @endif
                    >
                        <td>{{ $superadmin->id }}</td>
                        <td>{{ $superadmin->company_name }}</td>
                        <td>{{ $superadmin->email }}</td>
                        <td>
                            <span class="badge 
                                {{ $superadmin->status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $superadmin->status }}
                            </span>
                        </td>
                        <td>
                            {{ $superadmin->expiry_date->format('Y-m-d') }}
                            @if($isExpired)
                                <span class="text-danger fw-bold">(Expired)</span>
                            @elseif($isExpiringSoon)
                                <span class="text-warning fw-bold">(Expiring Soon)</span>
                            @endif
                        </td>
                        <td>
                            {{-- Edit Button --}}
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $superadmin->id }}">
                                <i class="bi bi-pencil"></i> Edit
                            </button>

                            {{-- Delete Button --}}
                            <form action="{{ route('superadmin.destroy', $superadmin->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this superadmin?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>

                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editModal{{ $superadmin->id }}" tabindex="-1"
                                 aria-labelledby="editModalLabel{{ $superadmin->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Superadmin</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('superadmin.update', $superadmin->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Company Name</label>
                                                    <input type="text" name="company_name" class="form-control"
                                                        value="{{ $superadmin->company_name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control"
                                                        value="{{ $superadmin->email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Address</label>
                                                    <textarea name="address" class="form-control" rows="2" required>{{ $superadmin->address }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Mobile</label>
                                                    <input type="text" name="mobile" class="form-control"
                                                        value="{{ $superadmin->mobile }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Expiry Date</label>
                                                    <input type="date" name="expiry_date" class="form-control"
                                                        value="{{ $superadmin->expiry_date->format('Y-m-d') }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="Active" {{ $superadmin->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                        <option value="Inactive" {{ $superadmin->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Change Password <small class="text-muted">(leave blank to keep current)</small></label>
                                                    <input type="password" name="password" class="form-control" placeholder="New Password">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
