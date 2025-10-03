@extends('company.dashboard')

@section('content')
<div class="container mt-4">

    <!-- jQuery (required for $) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS (for modal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Filter Section --}}
    <div class="p-3 mb-4 shadow-sm" style="background-color: #f8f9fa; border-radius: 5px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 c  text-dark">Filter Customers</h5>
            <a href="{{ route('admin.customers.create') }}" 
               class="btn btn-primary btn-sm d-flex align-items-center justify-content-center" 
               style="width: 36px; height: 36px; font-size: 18px;">
                <i class="bi bi-plus-lg"></i>
            </a>
        </div>

        <!-- Mobile filter toggle button -->
<div class="col-12 d-md-none mb-2">
    <button class="btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#mobileFilterFields"
            style="background: #0f2141; color:white;">
        Filter Customers
    </button>
</div>

<!-- Filter form -->
<form action="{{ route('admin.customers.index') }}" method="GET" class="row g-2 align-items-end">
    <!-- Mobile collapse wrapper -->
    <div class="collapse d-md-flex row g-2 w-100" id="mobileFilterFields">
        <div class="col-12 col-md-2">
            <label class="form-label small  text-dark">Name</label>
            <input type="text" name="name" class="form-control form-control-sm" 
                   placeholder="Customer Name" value="{{ request('name') }}">
        </div>

        <div class="col-12 col-md-2">
            <label class="form-label smal  text-dark">Group</label>
            <select name="customer_group" class="form-select form-select-sm">
                <option value="">-- Select Group --</option>
                @foreach(DB::table('groups')->pluck('customer_group') as $group)
                    <option value="{{ $group }}" {{ request('customer_group') == $group ? 'selected' : '' }}>
                        {{ $group }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-2">
            <label class="form-label small  text-dark">City</label>
            <input type="text" name="city" class="form-control form-control-sm" 
                   placeholder="City" value="{{ request('city') }}">
        </div>

        <div class="col-12 col-md-2">
            <label class="form-label small  text-dark">Phone</label>
            <input type="text" name="phone" class="form-control form-control-sm" 
                   placeholder="Phone" value="{{ request('phone') }}">
        </div>

        <div class="col-12 col-md-2">
            <label class="form-label small  text-dark">State</label>
            <input type="text" name="state" class="form-control form-control-sm" 
                   placeholder="State" value="{{ request('state') }}">
        </div>

     <div class="col-12 col-md-2 d-flex gap-2  mt-md-0 align-items-center ">
    <button type="submit" class="btn btn-primary flex-fill btn-xs">Apply</button>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary flex-fill btn-xs">Reset</a>
</div>

<style>
    .btn-xs {
        margin-top: 40px;
        padding: 0.25rem 0.5rem; /* uniform padding */
        font-size: 0.75rem;      /* text size */
        line-height: 1.2;        /* ensure vertical alignment */
    }
</style>

    </div>

    <!-- Desktop view: always show filters -->
    {{-- <div class="d-none d-md-flex row g-2 w-100">
        <div class="col-md-2">
            <label class="form-label small">Name</label>
            <input type="text" name="name" class="form-control form-control-sm" 
                   placeholder="Customer Name" value="{{ request('name') }}">
        </div>

        <div class="col-md-2">
            <label class="form-label small">Group</label>
            <select name="customer_group" class="form-select form-select-sm">
                <option value="">-- Select Group --</option>
                @foreach(DB::table('groups')->pluck('customer_group') as $group)
                    <option value="{{ $group }}" {{ request('customer_group') == $group ? 'selected' : '' }}>
                        {{ $group }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label small">City</label>
            <input type="text" name="city" class="form-control form-control-sm" 
                   placeholder="City" value="{{ request('city') }}">
        </div>

        <div class="col-md-2">
            <label class="form-label small">Phone</label>
            <input type="text" name="phone" class="form-control form-control-sm" 
                   placeholder="Phone" value="{{ request('phone') }}">
        </div>

        <div class="col-md-2">
            <label class="form-label small">State</label>
            <input type="text" name="state" class="form-control form-control-sm" 
                   placeholder="State" value="{{ request('state') }}">
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary">Apply</button>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-secondary">Reset</a>
        </div>
    </div> --}}
</form>

    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Customer Table --}}
   <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Group</th>
                <th>Phone</th>
                <th>Email</th>
                <th>City</th>
                <th>State</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($customers as $customer)
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->customer_group }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->city }}</td>
                <td>{{ $customer->state }}</td>
                <td class="d-flex flex-wrap gap-1">
                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-warning btn-sm" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                onclick="return confirm('Are you sure you want to delete {{ $customer->name }}?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>

                    <button type="button" class="btn btn-info btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#customerAnalysisModal" 
                            data-customerid="{{ $customer->id }}"
                            data-customername="{{ $customer->name }}"
                            title="Analysis">
                        <i class="bi bi-bar-chart text-white"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No customers found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>


    {{-- Pagination --}}
    <div>
        {{ $customers->appends(request()->query())->links() }}
    </div>

</div>

<!-- Customer Analysis Modal -->
<div class="modal fade" id="customerAnalysisModal" tabindex="-1" aria-labelledby="customerAnalysisLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="customerAnalysisLabel">Customer Analysis</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <div class="row text-center mb-4">
            <div class="col-md-4">
                <div class="p-2 shadow-sm rounded" style="background-color:#e3f2fd;">
                    <h6>Total Bills</h6>
                    <h4 id="totalBills">0</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-2 shadow-sm rounded" style="background-color:#e8f5e9;">
                    <h6>Total Purchase</h6>
                    <h4 id="totalAmount">₹0</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-2 shadow-sm rounded" style="background-color:#fff3e0;">
                    <h6>Total Remaining</h6>
                    <h4 id="totalRemaining">₹0</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h6 class="text-center">Products Amount</h6>
                <canvas id="productsAmountChart" height="200"></canvas>
            </div>
            <div class="col-md-6">
                <h6 class="text-center">Products Quantity</h6>
                <canvas id="productsQtyChart" height="200"></canvas>
            </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
    var amountChart, qtyChart;

    $('#customerAnalysisModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var customerId = button.data('customerid');
        var customerName = button.data('customername');

        var url = "{{ route('admin.customers.analysis', ':id') }}".replace(':id', customerId);

        // Reset totals
        $('#totalBills').text('Loading...');
        $('#totalAmount').text('Loading...');
        $('#totalRemaining').text('Loading...');

        // Destroy old charts
        if(amountChart) amountChart.destroy();
        if(qtyChart) qtyChart.destroy();

        $.ajax({
            url: url,
            method: 'GET',
            success: function(data){
                $('#totalBills').text(data.total_bills);
                $('#totalAmount').text(data.total_amount);
                $('#totalRemaining').text(data.total_remaining);

                var productMap = {};
                data.products.forEach(function(p){
                    if(productMap[p.name]){
                        productMap[p.name].qty += p.qty;
                        productMap[p.name].price += p.price * p.qty;
                    } else {
                        productMap[p.name] = { qty: p.qty, price: p.price * p.qty };
                    }
                });

                var labels = [], amountData = [], qtyData = [];
                for(var key in productMap){
                    labels.push(key);
                    amountData.push(productMap[key].price);
                    qtyData.push(productMap[key].qty);
                }

                // Products Amount Chart
                var ctx1 = document.getElementById('productsAmountChart').getContext('2d');
                amountChart = new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Amount (₹)',
                            data: amountData,
                            backgroundColor: 'rgba(33, 150, 243, 0.7)',
                            borderColor: 'rgba(33, 150, 243,1)',
                            borderWidth: 1
                        }]
                    },
                    options:{
                        animation:false,
                        plugins:{ legend:{display:false} },
                        scales:{ y:{ beginAtZero:true } }
                    }
                });

                // Products Quantity Chart
                var ctx2 = document.getElementById('productsQtyChart').getContext('2d');
                qtyChart = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Quantity',
                            data: qtyData,
                            backgroundColor: 'rgba(76, 175, 80, 0.7)',
                            borderColor: 'rgba(76, 175, 80,1)',
                            borderWidth: 1
                        }]
                    },
                    options:{
                        animation:false,
                        plugins:{ legend:{display:false} },
                        scales:{ y:{ beginAtZero:true } }
                    }
                });

            },
            error:function(){
                alert('Failed to load data.');
            }
        });
    });
});
</script>
@endsection
