@extends('company.dashboard')

@section('title', 'Daily Gold & Silver Rates')
@section('page-title', 'Daily Gold & Silver Rates')

@section('head')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">


<style>
    body {
        background-color: #f3b124;
        color: #fff;
        font-family: 'Segoe UI', sans-serif;
    }

    .rates-container {
        max-width: 760px;
        margin: 50px auto;
        padding: 35px 30px;
        background: linear-gradient(145deg, #1f1f33, #171728);
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.7);
        transition: 0.3s;
    }

    .rates-container:hover {
        box-shadow: 0 15px 40px rgba(255, 215, 0, 0.3);
    }

    .rates-container h2 {
        font-weight: 700;
        color: #ffd700;
        font-size: 2rem;
        margin-bottom: 5px;
        text-align: center;
    }

    .rates-container p {
        color: #ccc;
        font-size: 0.95rem;
        text-align: center;
        margin-bottom: 30px;
    }

    .date-section {
        text-align: center;
        margin-bottom: 30px;
    }

    .date-section input[type="date"] {
        padding: 10px 15px;
        font-size: 1rem;
        border-radius: 10px;
        border: 1px solid #444;
        background: #2c2c50;
        color: #fff;
        transition: all 0.3s;
    }

    .date-section input[type="date"]:focus {
        border-color: #ffd700;
        box-shadow: 0 0 12px rgba(255,215,0,0.6);
        outline: none;
    }

    .rates-grid {
        display: flex;
        gap: 25px;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .rate-card {
        flex: 1 1 48%;
        background: linear-gradient(135deg, #1a1a2e, #22223b);
        padding: 25px 20px;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.5);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .rate-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(255,215,0,0.4);
    }

    .rate-card h4 {
        font-weight: 600;
        color: #ffd700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.2rem;
    }

    .rate-input {
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
    }

    .rate-input label {
        font-size: 0.9rem;
        color: #ccc;
        margin-bottom: 6px;
        font-weight: 500;
    }

    .rate-input input {
        padding: 8px 12px;
        font-size: 0.95rem;
        color: #ffffff;
        background: #2c2c50;
        border: 1px solid #444;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .rate-input input:focus {
        border-color: #ffd700;
        box-shadow: 0 0 10px rgba(255,215,0,0.5);
        outline: none;
    }

    .save-btn {
        display: block;
        width: 100%;
        padding: 14px 0;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1.05rem;
        color: #0b0b17;
        background: linear-gradient(90deg, #ffd700, #ffaa00);
        border: none;
        transition: 0.4s;
    }

    .save-btn:hover {
        background: linear-gradient(90deg, #ffaa00, #ffd700);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255,215,0,0.6);
    }

    @media(max-width: 767px){
        .rates-grid { flex-direction: column; }
        .rate-card { flex: 1 1 100%; }
    }
</style>
@endsection

@section('content')
<script>
    var getRatesUrl = "{{ route('dashboard.analysis.get', ['date' => 'DATE_PLACEHOLDER']) }}";
</script>

<div class="rates-container">
   <style>
/* Heading alignment */
.rates-container h2 {
    font-weight: 700;
    color: #ffd700;
    font-size: 2rem;
    margin-bottom: 5px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px; /* space between icon and text */
}

/* Card titles alignment */
.card-title {
    display: flex;
    align-items: center;
    gap: 8px; /* space between icon and text */
    font-weight: 600;
}
</style>

<h2><i class="bi bi-currency-dollar"></i> Daily Gold & Silver Rates </h2>

    <p style="  margin-bottom: 5px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;">Update your rates quickly and accurately</p>

   <form action="{{ route('dashboard.analysis.store') }}" method="POST" class="rates-form p-4 rounded shadow-lg bg-dark text-white">
    @csrf

    {{-- Date Picker --}}
    <div class="mb-4">
        <label for="rate_date" class="form-label">Select Date</label>
        <input type="date" name="rate_date" id="rate_date" class="form-control bg-secondary text-white border-0" value="{{ date('Y-m-d') }}" required>
    </div>

    <div class="row g-4">
        {{-- Gold Rates --}}
        <div class="col-md-6">
            <div class="card  text-dark h-100 p-3" style="background: linear-gradient(135deg, #daa400, #FFC107);">
                <h5 class="card-title mb-3"><i class="bi bi-gem-fill"></i> Gold Rates</h5>

                <div class="mb-2">
                    <label for="gold24" class="form-label">24K</label>
                    <input type="number" step="0.01" id="gold24" name="gold[24]" class="form-control" placeholder="Enter 24K rate" required>
                </div>
                <div class="mb-2">
                    <label for="gold22" class="form-label">22K</label>
                    <input type="number" step="0.01" id="gold22" name="gold[22]" class="form-control" placeholder="Enter 22K rate" required>
                </div>
                <div class="mb-2">
                    <label for="gold18" class="form-label">18K</label>
                    <input type="number" step="0.01" id="gold18" name="gold[18]" class="form-control" placeholder="Enter 18K rate" required>
                </div>
            </div>
        </div>

        {{-- Silver Rates --}}
        <div class="col-md-6">
            <div class="card bg-gradient-silver text-dark h-100 p-3">
                <h5 class="card-title mb-3"><i class="bi bi-circle-half"></i> Silver Rates</h5>

                <div class="mb-2">
                    <label for="silver999" class="form-label">999</label>
                    <input type="number" style="background-color: #b4b3ae"step="0.01" id="silver999" name="silver[999]" class="form-control" placeholder="Enter 999 Silver rate" required>
                </div>
                <div class="mb-2">
                    <label for="silver925" class="form-label">925</label>
                    <input type="number"  style="background-color: #b4b3ae"step="0.01" id="silver925" name="silver[925]" class="form-control" placeholder="Enter 925 Silver rate" required>
                </div>
            </div>
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Save Rates</button>
    </div>
</form>

<style>
    .rates-form {
        max-width: 900px;
        margin: auto;
    }
    .bg-gradient-gold {
        background: linear-gradient(135deg, #FFD700, #FFC107);
        color: #000;
        border-radius: 12px;
    }
    .bg-gradient-silver {
       background: linear-gradient(135deg, #6c757d, #A9A9A9);
        color: #000;
        border-radius: 12px;
    }
    .form-control {
        background-color: #ffd553;
        color: #fff;
        border-radius: 8px;
        border: none;
    }
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    .btn-success {
        background-color: #28a745;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
    }
</style>
<!-- jQuery (needed for $.get) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

$('#rate_date').on('change', function(){
    let date = $(this).val();   // <-- define here
    if(date){
        let url = getRatesUrl.replace('DATE_PLACEHOLDER', date); // <-- now safe
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

</div>
@endsection
