@extends('company.dashboard')

@section('content')
<div class="container">
    <h4 class="mb-4 d-print-none">All Purchases</h4>

    @if(session('success'))
        <div class="alert alert-success d-print-none">{{ session('success') }}</div>
    @endif

    <!-- Print button -->
    <button class="btn btn-info mb-2 d-print-none" onclick="printSelected();">
        <i class="bi bi-printer"></i> Print Selected
    </button>

    <!-- Table container -->
    <div id="printableTable">
        <table class="table table-bordered" id="purchaseTable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Type</th>
                    <th>Weight</th>
                    <th>Rate</th>
                    <th>Wastage %</th>
                    <th>Labor</th>
                    <th>Total</th>
                    <th>Notes</th>
                    <th>Date</th>
                    <th class="d-print-none">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $p)
                    <tr>
                        <td><input type="checkbox" class="rowCheckbox"></td>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->item_name }}</td>
                        <td>{{ $p->item_type }}</td>
                        <td>{{ $p->weight }}</td>
                        <td>{{ $p->rate }}</td>
                        <td>{{ $p->wastage_percent }}</td>
                        <td>{{ $p->labor_charge }}</td>
                        <td>{{ $p->total }}</td>
                        <td>{{ $p->notes }}</td>
                        <td>{{ $p->created_at }}</td>
                        <td class="d-print-none">
                            <a href="{{ route('purchases.edit', $p->id) }}" class="btn btn-sm btn-warning mb-1">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form action="{{ route('purchases.destroy', $p->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    // Select/Deselect all checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.rowCheckbox').forEach(cb => cb.checked = this.checked);
    });

    function printSelected() {
        let rows = document.querySelectorAll('#purchaseTable tbody tr');
        rows.forEach(row => {
            let checkbox = row.querySelector('.rowCheckbox');
            row.style.display = checkbox.checked ? '' : 'none';
        });

        // Hide action column
        document.querySelectorAll('.d-print-none').forEach(el => el.style.display = 'none');

        // Print only the table
        window.print();

        // Reset table after printing
        rows.forEach(row => row.style.display = '');
        document.querySelectorAll('.d-print-none').forEach(el => el.style.display = '');
    }
</script>

<style>
@media print {
    body * {
        visibility: hidden; /* hide everything */
    }
    #printableTable, #printableTable * {
        visibility: visible; /* show only table */
    }
    #printableTable {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
@endsection
