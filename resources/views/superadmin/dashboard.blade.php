@extends('company.dashboard')

@section('content')
<div class="container mt-5">
    <h3>Superadmin Dashboard</h3>

    @if($superadmins->isEmpty())
        <div class="alert alert-info">No superadmins found.</div>
    @else
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Company Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Expiry Date</th>
                    <th>Actions</th> {{-- New Action Column --}}
                </tr>
            </thead>
            <tbody>
                @foreach($superadmins as $superadmin)
                    <tr>
                        <td>{{ $superadmin->id }}</td>
                        <td>{{ $superadmin->company_name }}</td>
                        <td>{{ $superadmin->email }}</td>
                        <td>{{ $superadmin->status }}</td>
                        <td>{{ $superadmin->expiry_date->format('Y-m-d') }}</td>
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
                          {{-- Edit Modal --}}
<div class="modal fade" id="editModal{{ $superadmin->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $superadmin->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel{{ $superadmin->id }}">Edit Superadmin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('superadmin.update', $superadmin->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label">Company Name</label>
                  <input type="text" name="company_name" class="form-control" value="{{ $superadmin->company_name }}" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" value="{{ $superadmin->email }}" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Address</label>
                  <textarea name="address" class="form-control" rows="2" required>{{ $superadmin->address }}</textarea>
              </div>
              <div class="mb-3">
                  <label class="form-label">Mobile</label>
                  <input type="text" name="mobile" class="form-control" value="{{ $superadmin->mobile }}" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Expiry Date</label>
                  <input type="date" name="expiry_date" class="form-control" value="{{ $superadmin->expiry_date->format('Y-m-d') }}" required>
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
