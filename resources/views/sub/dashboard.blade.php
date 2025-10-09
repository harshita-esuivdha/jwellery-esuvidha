@extends('company.dashboard')

@section('title', 'Daily Gold & Silver Rates')
@section('page-title', 'Daily Gold & Silver Rates')

@section('head')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body { background: linear-gradient(to bottom right, #ffffff, #fefae0); color: #2c2c2c; font-family: 'Poppins', sans-serif; }

    .rates-container { max-width: 900px; margin: 50px auto; padding: 35px 30px; background: #fff; border-radius: 20px; box-shadow: 0 12px 30px rgba(0,0,0,0.15); transition: 0.3s; }
    .rates-container:hover { box-shadow: 0 15px 40px rgba(255, 215, 0, 0.35); }
    .rates-container h2 { font-weight: 700; color: #d4af37; font-size: 2rem; margin-bottom: 5px; text-align: center; display: flex; align-items: center; justify-content: center; gap: 10px; }
    .rates-container p { color: #666; font-size: 0.95rem; text-align: center; margin-bottom: 30px; }
    .rates-form { max-width: 850px; margin: auto; }
    .card-title { display: flex; align-items: center; gap: 8px; font-weight: 600; }
    .bg-gradient-gold { background: linear-gradient(135deg, #ffd700, #ffb347); border-radius: 15px; color: #111; }
    .bg-gradient-silver { background: linear-gradient(135deg, #d3d3d3, #f8f9fa); border-radius: 15px; color: #111; }
    .form-control { background-color: #f8f9fa; color: #111; border-radius: 8px; border: 1px solid #ccc; transition: all 0.3s; }
    .form-control:focus { border-color: #ffd700; box-shadow: 0 0 8px rgba(255,215,0,0.5); }
    .form-control::placeholder { color: #888; }
    .btn-save { background: linear-gradient(90deg, #ffd700, #ffaa00); border: none; padding: 0.6rem 1.2rem; border-radius: 10px; font-weight: 600; transition: 0.4s; }
    .btn-save:hover { background: linear-gradient(90deg, #ffaa00, #ffd700); color: #fff; transform: translateY(-2px); box-shadow: 0 6px 18px rgba(255,215,0,0.6); }

    /* Analytics tables */
    .analytics-container { max-width: 1200px; margin: 50px auto; }
    .analytics-card { background: #fff; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .analytics-card h4 { font-weight: 700; color: #d4af37; margin-bottom: 20px; }
</style>
@endsection

@section('content')
<script>
    var getRatesUrl = "{{ route('dashboard.analysis.get', ['date' => 'DATE_PLACEHOLDER']) }}";
</script>

{{-- Daily Rates Form --}}
<div class="rates-container">
    <h2><i class="bi bi-currency-dollar"></i> Daily Gold & Silver Rates </h2>
    <p>Update your rates quickly and accurately</p>

    <form action="{{ route('dashboard.analysis.store') }}" method="POST" class="rates-form p-4">
        @csrf
        <div class="mb-4">
            <label for="rate_date" class="form-label fw-semibold">Select Date</label>
            <input type="date" name="rate_date" id="rate_date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="row g-4">
            {{-- Gold --}}
            <div class="col-md-6">
                <div class="card bg-gradient-gold h-100 p-3 shadow-sm">
                    <h5 class="card-title mb-3"><i class="bi bi-gem"></i> Gold Rates</h5>
                    <div class="mb-3"><label for="gold24" class="form-label">24K</label><input type="number" step="0.01" id="gold24" name="gold[24]" class="form-control" placeholder="Enter 24K rate" required></div>
                    <div class="mb-3"><label for="gold22" class="form-label">22K</label><input type="number" step="0.01" id="gold22" name="gold[22]" class="form-control" placeholder="Enter 22K rate" required></div>
                    <div class="mb-3"><label for="gold18" class="form-label">18K</label><input type="number" step="0.01" id="gold18" name="gold[18]" class="form-control" placeholder="Enter 18K rate" required></div>
                </div>
            </div>
            {{-- Silver --}}
            <div class="col-md-6">
                <div class="card bg-gradient-silver h-100 p-3 shadow-sm">
                    <h5 class="card-title mb-3"><i class="bi bi-circle-half"></i> Silver Rates</h5>
                    <div class="mb-3"><label for="silver999" class="form-label">999</label><input type="number" step="0.01" id="silver999" name="silver[999]" class="form-control" placeholder="Enter 999 Silver rate" required></div>
                    <div class="mb-3"><label for="silver925" class="form-label">925</label><input type="number" step="0.01" id="silver925" name="silver[925]" class="form-control" placeholder="Enter 925 Silver rate" required></div>
                </div>
            </div>
        </div>
        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-save"><i class="bi bi-save"></i> Save Rates</button>
        </div>
    </form>
</div>

{{-- Last 7 Sales Chart --}}
<div class="rates-container mt-5">
    <h2><i class="bi bi-bar-chart-line"></i> Last 7 Sales</h2>
    <p>Track your latest company invoices</p>
    <canvas id="salesChart" height="100"></canvas>
</div>

{{-- Analytics Section --}}
<div class="analytics-container">
    {{-- Total Revenue --}}
    <div class="analytics-card text-center">
        <h4><i class="bi bi-cash-stack"></i> Total Revenue</h4>
        <h3>₹ {{ number_format($totalRevenue, 2) }}</h3>
    </div>

    {{-- Customer-wise --}}
    <div class="analytics-card">
        <h4><i class="bi bi-people"></i> Customer-wise Revenue</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Customer Name</th>
                        <th>Total Sales (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customerAnalysis as $customer)
                        <tr>
                            <td>{{ $customer['customer_name'] }}</td>
                            <td>{{ number_format($customer['total_sales'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center">No data found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Product-wise --}}
    <div class="analytics-card">
        <h4><i class="bi bi-gem"></i> Product-wise Sales</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity Sold</th>
                        <th>Total Value (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productAnalysis as $product)
                        <tr>
                            <td>{{ $product['name'] }}</td>
                            <td>{{ $product['qty'] }}</td>
                            <td>{{ number_format($product['total_value'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center">No data found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Grand Total',
            data: @json($chartData),
            backgroundColor: 'rgba(255, 215, 0, 0.6)',
            borderColor: 'rgba(255, 215, 0, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: { y: { beginAtZero: true } },
        plugins: { legend: { display: false }, tooltip: { mode: 'index' } }
    }
});
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#rate_date').on('change', function(){
    let date = $(this).val();
    if(date){
        let url = getRatesUrl.replace('DATE_PLACEHOLDER', date);
        $.get(url, function(res){
            if(res.success){
                $('#gold24').val(res.gold_24);
                $('#gold22').val(res.gold_22);
                $('#gold18').val(res.gold_18);
                $('#silver999').val(res.silver_999);
                $('#silver925').val(res.silver_925);
            } else {
                $('#gold24,#gold22,#gold18,#silver999,#silver925').val('');
            }
        });
    }
});
</script>
@endsection
