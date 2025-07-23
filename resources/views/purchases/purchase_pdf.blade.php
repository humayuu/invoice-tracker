<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Purchase Statement - {{ $supplier->supplier_name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            line-height: 1.5;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 28px;
            margin: 0 0 12px;
            font-weight: bold;
        }
        .header h2 {
            font-size: 20px;
            margin: 0 0 8px;
            color: #333;
            font-weight: bold;
        }
        .meta-info {
            margin-bottom: 28px;
            font-size: 13px;
        }
        table {
            width: 1000px;
            border-collapse: collapse;
            margin-bottom: 32px;
        }
        th, td {
            border: 1.5px solid #bbb;
            padding: 12px 10px;
            font-size: 14px;
        }
        th {
            background-color: #f4f6fa;
            font-weight: bold;
            font-size: 15px;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-end {
            text-align: right;
        }
        .text-danger {
            color: #dc3545;
            font-weight: bold;
        }
        .text-success {
            color: #28a745;
            font-weight: bold;
        }
        .overdue {
            background-color: #fff3f3 !important;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .totals {
            margin-top: 32px;
        }
        .totals table {
            width: 100%;
            margin-left: auto;
            border: 1.5px solid #bbb;
        }
        .totals td {
            padding: 12px 10px;
            font-size: 15px;
        }
        .totals tr td:first-child {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Purchase Invoice Statement</h1>
        <h2>{{ $supplier->supplier_name }}</h2>
        <div class="meta-info">
            Payment Cycle: {{ $supplier->supplier_payment_cycle }} Days<br>
            Statement Date: {{ now()->format('d/m/Y') }}<br>
            Status: Pending & Overdue Purchases Only
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Date</th>
                <th>Invoice No</th>
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
                $totalPending = 0;
                $overduePending = 0;
            @endphp

            @foreach($purchases as $key => $purchase)
                @php
                    $totalPending += $purchase->remaining_balance;
                    $dueDate = \Carbon\Carbon::parse($purchase->due_date);
                    $today = \Carbon\Carbon::now();
                    $overDueDays = $today->diffInDays($dueDate, false);
                    $overDueText = $overDueDays < 0 ? abs($overDueDays) . ' Days' : ($overDueDays == 0 ? '1 Day' : 'Yet To Due');

                    if($purchase->status === 'overdue' || $overDueDays < 0) {
                        $overduePending += $purchase->remaining_balance;
                    }
                @endphp
                <tr class="{{ ($purchase->status === 'overdue' || $overDueDays < 0) ? 'overdue' : '' }}">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}</td>
                    <td>{{ $purchase->purchase_invoice_no }}</td>
                    <td>{{ $purchase->po_no }}</td>
                    <td>{{ $purchase->description }}</td>
                    <td class="text-end">{{ number_format($purchase->amount, 0, '.', ',') }}</td>
                    <td class="text-end text-success">{{ number_format($purchase->amount_paid, 0, '.', ',') }}</td>
                    <td class="text-end text-danger">{{ number_format($purchase->remaining_balance, 0, '.', ',') }}</td>
                    <td>{{ \Carbon\Carbon::parse($purchase->due_date)->format('d/m/Y') }}</td>
                    <td class="{{ ($purchase->status === 'overdue' || $overDueDays < 0) ? 'text-danger' : '' }}">
                        {{ $overDueText }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td><strong>Total Pending Amount:</strong></td>
                <td class="text-end">{{ number_format($totalPending, 0, '.', ',') }}</td>
            </tr>
            <tr>
                <td><strong>Overdue Amount:</strong></td>
                <td class="text-end text-danger">{{ number_format($overduePending, 0, '.', ',') }}</td>
            </tr>
            <tr>
                <td><strong>Not Yet Due:</strong></td>
                <td class="text-end">{{ number_format($totalPending - $overduePending, 0, '.', ',') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Generated on {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
