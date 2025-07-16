@extends('layout.app')
@section('main')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Purchase Invoice Detail</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Purchase #{{ $purchase->purchase_invoice_no }}</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th>Supplier</th>
                                <td>{{ $purchase->supplier->supplier_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Purchase Date</th>
                                <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th>PO No</th>
                                <td>{{ $purchase->po_no }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $purchase->description }}</td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td>{{ number_format($purchase->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Due Date</th>
                                <td>{{ \Carbon\Carbon::parse($purchase->due_date)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td style="font-size: 30px;">
                                    @if($purchase->status === 'overdue')
                                        <span class="badge bg-danger">Overdue</span>
                                    @elseif($purchase->status === 'paid')
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
