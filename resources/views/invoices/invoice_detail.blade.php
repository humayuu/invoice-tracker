@extends('layout.app')
@section('main')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Invoice Detail</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Invoice #{{ $invoice->invoice_no }}</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th>Client</th>
                                <td>{{ $invoice->client->client_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Invoice Date</th>
                                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th>PO No</th>
                                <td>{{ $invoice->po_no }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $invoice->description }}</td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td>{{ number_format($invoice->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Due Date</th>
                                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td style="font-size: 30px">
                                    @if($invoice->status === 'overdue')
                                        <span class="badge bg-danger">Overdue</span>
                                    @elseif($invoice->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
