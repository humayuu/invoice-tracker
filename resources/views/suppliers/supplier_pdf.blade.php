<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Purchase Statement - {{ $supplier->supplier_name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0 0 10px;
        }
        .header h2 {
            font-size: 18px;
            margin: 0 0 5px;
            color: #333;
        }
        .meta-info {
            margin-bottom: 20px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 11px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-end {
            text-align: right;
        }
        .text-danger {
            color: #dc3545;
        }
        .overdue {
            background-color: #fff3f3;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .totals {
            margin-top: 20px;
        }
        .totals table {
            width: 50%;
            margin-left: auto;
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
                <tr class="{{ ($purchase->status === 'overdue' || $overDueDays < 0) ? 'overdue' : '' }}">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}</td>
                    <td>{{ $purchase->purchase_invoice_no }}</td>
                    <td>{{ $purchase->po_no }}</td>
                    <td>{{ $purchase->description }}</td>
                    <td class="text-end">{{ number_format($purchase->amount, 0, '.', ',') }}</td>
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
                <td class="text-end">{{ number_format($totalAmount, 0, '.', ',') }}</td>
            </tr>
            <tr>
                <td><strong>Overdue Amount:</strong></td>
                <td class="text-end text-danger">{{ number_format($overdueAmount, 0, '.', ',') }}</td>
            </tr>
            <tr>
                <td><strong>Not Yet Due:</strong></td>
                <td class="text-end">{{ number_format($totalAmount - $overdueAmount, 0, '.', ',') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Generated on {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
