@extends('layout.app')
@section('main')

<div class="page-content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ $client->client_name ?? 'N/A' }} - Pending & Overdue Invoices</h4>
                        <p class="text-muted mb-0">
                            Payment Cycle: {{ $client->client_payment_cycle }} Days |
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary print-button">
                            <i class="fas fa-print me-1"></i> Print
                        </button>
                        <a href="{{ route('clients.client.invoice.pdf', $client->id) }}" class="btn btn-outline-danger">
                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                        </a>
                        <button class="btn btn-outline-success export-button">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12" id="printArea">
                <!-- Print Header - Only visible when printing -->
                <div class="print-header d-none">
                    <div class="text-center mb-4">
                        <h2 class="mb-1">Invoice Statement</h2>
                        <h4 class="mb-3">{{ $client->client_name }}</h4>
                        <div class="mb-2">Payment Cycle: {{ $client->client_payment_cycle }} Days</div>
                        <div class="mb-2">Statement Date: {{ now()->format('d/m/Y') }}</div>
                        <div>Status: Pending & Overdue Invoices Only</div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover invoice-table mb-0" id="invoiceTable">
                                <thead>
                                    <tr class="table-light">
                                        <th class="px-3">Sl No</th>
                                        <th>Date</th>
                                        <th>Invoice No</th>
                                        <th>PO#</th>
                                        <th>Particular</th>
                                        <th class="text-end">Amount</th>
                                        <th>Due Date</th>
                                        <th>Over Due Days</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $totalAmount = 0;
                                        $overdueAmount = 0;
                                    @endphp

                                    @foreach($invoices as $key => $invoice)
                                    @php
                                        $totalAmount += $invoice->amount;

                                        $dueDate = \Carbon\Carbon::parse($invoice->due_date);
                                        $today = \Carbon\Carbon::now();
                                        $overDueDays = $today->diffInDays($dueDate, false);
                                        $overDueText = $overDueDays < 0 ? abs($overDueDays) . ' Days' : ($overDueDays == 0 ? '1 Day' : 'Yet To Due');

                                        if($invoice->status === 'overdue' || $overDueDays < 0) {
                                            $overdueAmount += $invoice->amount;
                                        }
                                    @endphp
                                    <tr class="invoice-row {{ ($invoice->status === 'overdue' || $overDueDays < 0) ? 'table-danger' : '' }}">
                                        <td class="px-3">{{ $key + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                                        <td>{{ $invoice->invoice_no }}</td>
                                        <td>{{ $invoice->po_no }}</td>
                                        <td>{{ $invoice->description }}</td>
                                        <td class="text-end">{{ number_format($invoice->amount, 0, '.', ',') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</td>
                                        <td class="{{ ($invoice->status === 'overdue' || $overDueDays < 0) ? 'text-danger fw-bold' : '' }}">
                                            {{ $overDueText }}

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="border-top">
                                        <td colspan="5" class="text-end fw-bold">Total Pending Amount:</td>
                                        <td class="text-end fw-bold">{{ number_format($totalAmount, 0, '.', ',') }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Overdue Amount:</td>
                                        <td class="text-end fw-bold text-danger">{{ number_format($overdueAmount, 0, '.', ',') }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Not Yet Due:</td>
                                        <td class="text-end fw-bold text-success">{{ number_format($totalAmount - $overdueAmount, 0, '.', ',') }}</td>
                                        <td colspan="2"></td>
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

        /* Footer styles for print */
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
        let fileName = `${date}_{{ $client->client_name }}_pending_invoices.xls`;

        // Get the table
        let table = document.getElementById('invoiceTable');

        // Convert table to Excel format
        let wb = XLSX.utils.table_to_book(table, {sheet: "Invoices"});

        // Save the file
        XLSX.writeFile(wb, fileName);
    });
});
</script>
@endpush

@endsection
