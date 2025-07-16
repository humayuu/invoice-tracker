<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Purchase Summary Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0 0 10px;
            color: #333;
        }
        .header h2 {
            font-size: 16px;
            margin: 0 0 5px;
            color: #666;
        }
        .meta-info {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .meta-info p {
            margin: 5px 0;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 11px;
        }
        th {
            background-color: #000;
            color: white;
            font-weight: bold;
        }
        .text-end {
            text-align: right;
        }
        .amount {
            font-family: 'DejaVu Sans Mono', monospace;
        }
        .summary-section {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .summary-box {
            float: right;
            width: 300px;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }
        .summary-box p {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            padding: 5px 0;
            border-bottom: 1px dotted #ddd;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding: 10px 0;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Purchase Summary Report</h1>
        <h2>Invoice Tracker System</h2>
    </div>

    <div class="meta-info">
        <p><strong>Report Generated:</strong> {{ now()->format('d F Y, h:i A') }}</p>
        <p><strong>Total Suppliers:</strong> {{ $suppliers->count() }}</p>
        <p><strong>Report Type:</strong> Pending & Overdue Purchases Summary</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>SL No</th>
                <th>Supplier Name</th>
                <th>Total Pending Amount</th>
                <th>Over Due Amount</th>
                <th>Not Due Yet</th>
                <th>Payment Cycle</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPending = 0;
                $totalOverdue = 0;
                $totalNotDue = 0;
            @endphp
            @foreach($suppliers as $index => $supplier)
                @php
                    $totalPending += $supplier->total_pending_amount;
                    $totalOverdue += $supplier->overdue_amount;
                    $notDueAmount = $supplier->total_pending_amount - $supplier->overdue_amount;
                    $totalNotDue += $notDueAmount;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $supplier->supplier_name }}</td>
                    <td class="text-end amount">{{ number_format($supplier->total_pending_amount, 2) }}</td>
                    <td class="text-end amount">{{ number_format($supplier->overdue_amount, 2) }}</td>
                    <td class="text-end amount">{{ number_format($notDueAmount, 2) }}</td>
                    <td class="text-end">{{ $supplier->supplier_payment_cycle }} Days</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-section">
        <div class="summary-box">
            <h3 style="margin-top: 0;">Overall Summary</h3>
            <p>
                <span>Total Pending Amount:</span>
                <strong>{{ number_format($totalPending, 2) }}</strong>
            </p>
            <p>
                <span>Total Overdue Amount:</span>
                <strong style="color: #dc3545;">{{ number_format($totalOverdue, 2) }}</strong>
            </p>
            <p>
                <span>Total Not Due Yet:</span>
                <strong style="color: #28a745;">{{ number_format($totalNotDue, 2) }}</strong>
            </p>
            <p>
                <span>Overdue Percentage:</span>
                <strong>{{ $totalPending > 0 ? number_format(($totalOverdue / $totalPending) * 100, 1) : 0 }}%</strong>
            </p>
        </div>
    </div>

    <div class="footer">
        <p>Generated by Invoice Tracker System | {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>This is a computer-generated document. No signature is required.</p>
    </div>
</body>
</html> 