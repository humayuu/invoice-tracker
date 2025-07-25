<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Purchase Statement - {{ $supplier->supplier_name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 30px 1px;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 4px;
        }
        .header h2 {
            font-size: 14px;
            margin: 0;
        }
        .meta-info {
            font-size: 10px;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
            word-wrap: break-word;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            font-size: 10px;
            vertical-align: top;
        }
        th {
            background-color: #f1f1f1;
        }
        .text-end {
            text-align: right;
        }
        .text-danger {
            color: #dc3545;
        }
        .text-success {
            color: #28a745;
        }
        .overdue {
            background-color: #fff1f1;
        }
        .totals {
            margin-top: 30px;
        }
        .totals table {
            width: 60%;
            margin-left: auto;
        }
        .footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #888;
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
                <th style="width:5%">Sl.No</th>
                <th style="width:9%">Date</th>
                <th style="width:12%">Invoice No</th>
                <th style="width:7%">PO#</th>
                <th style="width:33%">Particular</th>
                <th style="width:10%" class="text-end">Amount</th>
                <th style="width:10%" class="text-end">Amount Paid</th>
                <th style="width:10%" class="text-end">Remaining Balance</th>
                <th style="width:7%">Due Date</th>
                <th style="width:7%">Overdue Days</th>
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
                    <td class="{{ ($purchase->status === 'overdue' || $overDueDays < 0) ? 'text-danger' : '' }}">{{ $overDueText }}</td>
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
