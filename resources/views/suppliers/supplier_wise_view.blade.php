@extends('layout.app')
@section('main')

<div class="page-content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ $supplier->supplier_name ?? 'N/A' }} - Pending & Overdue Purchases</h4>
                        <p class="text-muted mb-0">
                            Payment Cycle: {{ $supplier->supplier_payment_cycle }} Days |
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary print-button">
                            <i class="fas fa-print me-1"></i> Print
                        </button>
                        <a href="{{ route('suppliers.purchase.pdf', $supplier->id) }}" class="btn btn-outline-danger">
                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                        </a>
                        <button class="btn btn-outline-success export-button">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Payment Summary</h5>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Total Due</span>
                                <span class="fw-bold">{{ number_format($total_due, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Amount Paid</span>
                                <span class="fw-bold text-success">{{ number_format($amount_paid, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Remaining Balance</span>
                                <span class="fw-bold text-danger">{{ number_format($remaining_balance, 2) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Add Payment</h5>
                        <form method="POST" action="{{ route('suppliers.payments.store', $supplier->id) }}">
                            @csrf
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" min="0.01" name="amount" id="amount" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="purchase_ids" class="form-label">Apply To Purchases</label>
                                <select name="purchase_ids[]" id="purchase_ids" class="form-select" multiple required>
                                    @foreach($purchases as $purchase)
                                        <option value="{{ $purchase->id }}">
                                            #{{ $purchase->purchase_invoice_no }} - Due: {{ number_format($purchase->remaining_balance, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Select one or more purchases to apply this payment.</small>
                            </div>
                            <div class="mb-3">
                                <label for="note" class="form-label">Note (optional)</label>
                                <textarea name="note" id="note" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5>Payment History</h5>
                            <form method="POST" action="{{ route('suppliers.payments.clear', $supplier->id) }}" onsubmit="return confirm('Are you sure you want to clear all payment history? This cannot be undone.');">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Clear Payment History</button>
                            </form>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Purchases</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payment_history->where('hidden', false) as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('d-m-Y') }}</td>
                                        <td>{{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                            @foreach($payment->purchases as $pur)
                                                <span class="badge bg-info">#{{ $pur->purchase_invoice_no }} ({{ number_format($pur->pivot->amount_applied, 2) }})</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $payment->note }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12" id="printArea">
                <!-- Print Header - Only visible when printing -->
                <div class="print-header d-none">
                    <div class="text-center mb-4">
                        <h2 class="mb-1">Purchase Statement</h2>
                        <h4 class="mb-3">{{ $supplier->supplier_name }}</h4>
                        <div class="mb-2">Payment Cycle: {{ $supplier->supplier_payment_cycle }} Days</div>
                        <div class="mb-2">Statement Date: {{ now()->format('d/m/Y') }}</div>
                        <div>Status: Pending & Overdue Purchases Only</div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover invoice-table mb-0" id="purchaseTable">
                                <thead>
                                    <tr class="table-light">
                                        <th class="px-3">Sl No</th>
                                        <th>Date</th>
                                        <th>Purchase Invoice No</th>
                                        <th>PO#</th>
                                        <th>Particular</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-end">Amount Paid</th>
                                        <th class="text-end">Remaining Balance</th>
                                        <th>Due Date</th>
                                        <th>Over Due Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalAmount = 0;
                                        $overdueAmount = 0;
                                    @endphp
                                    @foreach($purchases as $key => $purchase)
                                    @php
                                        $totalAmount += $purchase->amount;
                                        $dueDate = \Carbon\Carbon::parse($purchase->due_date);
                                        $today = \Carbon\Carbon::now();
                                        $overDueDays = $today->diffInDays($dueDate, false);
                                        $overDueText = $overDueDays < 0 ? abs($overDueDays) . ' Days' : ($overDueDays == 0 ? '1 Day' : 'Yet To Due');
                                        if($purchase->status === 'overdue' || $overDueDays < 0) {
                                            $overdueAmount += $purchase->amount;
                                        }
                                    @endphp
                                    <tr class="invoice-row {{ ($purchase->status === 'overdue' || $overDueDays < 0) ? 'table-danger' : '' }}">
                                        <td class="px-3">{{ $key + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}</td>
                                        <td>{{ $purchase->purchase_invoice_no }}</td>
                                        <td>{{ $purchase->po_no }}</td>
                                        <td>{{ $purchase->description }}</td>
                                        <td class="text-end">{{ number_format($purchase->amount, 0, '.', ',') }}</td>
                                        <td class="text-end text-success">{{ number_format($purchase->amount_paid, 0, '.', ',') }}</td>
                                        <td class="text-end text-danger">{{ number_format($purchase->remaining_balance, 0, '.', ',') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($purchase->due_date)->format('d/m/Y') }}</td>
                                        <td class="{{ ($purchase->status === 'overdue' || $overDueDays < 0) ? 'text-danger fw-bold' : '' }}">
                                            {{ $overDueText }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    @php
                                        $totalPending = 0;
                                        $overduePending = 0;
                                    @endphp
                                    @foreach($purchases as $purchase)
                                        @php
                                            $totalPending += $purchase->remaining_balance;
                                            $dueDate = \Carbon\Carbon::parse($purchase->due_date);
                                            $today = \Carbon\Carbon::now();
                                            $overDueDays = $today->diffInDays($dueDate, false);
                                            if($purchase->status === 'overdue' || $overDueDays < 0) {
                                                $overduePending += $purchase->remaining_balance;
                                            }
                                        @endphp
                                    @endforeach
                                    <tr class="border-top">
                                        <td colspan="5" class="text-end fw-bold">Total Pending Amount:</td>
                                        <td class="text-end fw-bold">{{ number_format($totalPending, 0, '.', ',') }}</td>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Overdue Amount:</td>
                                        <td class="text-end fw-bold text-danger">{{ number_format($overduePending, 0, '.', ',') }}</td>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Not Yet Due:</td>
                                        <td class="text-end fw-bold text-success">{{ number_format($totalPending - $overduePending, 0, '.', ',') }}</td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .invoice-table {
        font-size: 0.95rem;
    }
    .invoice-table thead th {
        font-weight: 600;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    .invoice-row {
        transition: all 0.2s ease;
    }
    .invoice-row:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .table-danger {
        background-color: #fff3f3 !important;
    }
    .table-danger:hover {
        background-color: #ffe9e9 !important;
    }
    @media print {
        @page {
            size: landscape;
            margin: 15mm;
        }
        body * {
            visibility: hidden;
        }
        #printArea, #printArea * {
            visibility: visible;
        }
        #printArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .print-header.d-none {
            display: block !important;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
        }
        .card-body {
            padding: 0 !important;
        }
        .invoice-row:hover {
            transform: none !important;
            box-shadow: none !important;
        }
        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        .table td, .table th {
            padding: 0.5rem !important;
            border: 1px solid #dee2e6 !important;
        }
        .table-danger {
            background-color: #fff3f3 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        #printArea::after {
            content: "Generated on {{ now()->format('d/m/Y H:i:s') }}";
            position: fixed;
            bottom: 0;
            left: 0;
            font-size: 10px;
            width: 100%;
            text-align: center;
            padding: 10px 0;
        }
    }
</style>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
<script>
$(document).ready(function() {
    // Print functionality
    $('.print-button').on('click', function() {
        window.print();
    });
    // Export to Excel functionality
    $('.export-button').on('click', function() {
        let date = new Date().toISOString().split('T')[0];
        let fileName = `${date}_{{ $supplier->supplier_name }}_pending_purchases.xls`;
        // Get the table
        let table = document.getElementById('purchaseTable');
        // Convert table to Excel format
        let wb = XLSX.utils.table_to_book(table, {sheet: "Purchases"});
        // Save the file
        XLSX.writeFile(wb, fileName);
    });
});
</script>
@endpush

@endsection 