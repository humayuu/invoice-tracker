<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice Statement - {{ $client->client_name }}</title>
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
        <h1>Invoice Statement</h1>
        <h2>{{ $client->client_name }}</h2>
        <div class="meta-info">
            Payment Cycle: {{ $client->client_payment_cycle }} Days<br>
            Statement Date: {{ now()->format('d/m/Y') }}<br>
            Status: Pending & Overdue Invoices Only
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

            @foreach($invoices as $key => $invoice)
                @php
                    $totalAmount += $invoice->amount;
                    $dueDate = \Carbon\Carbon::parse($invoice->due_date);
                    $today = \Carbon\Carbon::now();
                    $overDueDays = $today->diffInDays($dueDate, false);
                    $overDueText = $overDueDays < 0 ? abs($overDueDays) . ' Days' : ($overDueDays == 0 ? '1 Day' : 'YTD');

                    if($invoice->status === 'overdue' || $overDueDays < 0) {
                        $overdueAmount += $invoice->amount;
                    }
                @endphp
                <tr class="{{ ($invoice->status === 'overdue' || $overDueDays < 0) ? 'overdue' : '' }}">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                    <td>{{ $invoice->invoice_no }}</td>
                    <td>{{ $invoice->po_no }}</td>
                    <td>{{ $invoice->description }}</td>
                    <td class="text-end">{{ number_format($invoice->amount, 0, '.', ',') }}</td>
                    <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</td>
                    <td class="{{ ($invoice->status === 'overdue' || $overDueDays < 0) ? 'text-danger' : '' }}">
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
