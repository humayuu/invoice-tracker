@extends('layout.app')
@section('main')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Purchase All</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Purchase All Data </h4>
                            <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th width='5%'>Sl</th>
                                        <th width='10%'>Purchase Date</th>
                                        <th width='15%'>Purchase Invoice No #</th>
                                        <th width='25%'>Supplier Name</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th width='15%'>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchases as $key => $purchase)
                                    <tr class="text-black fw-bold">
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d-m-Y') }}</td>
                                        <td>{{ $purchase->purchase_invoice_no }}</td>
                                        <td>{{ $purchase->supplier->supplier_name }}</td>
                                        <td>{{ number_format(round($purchase->amount)) }}</td>
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $isOverdue = ($purchase->due_date < $now);
                                        @endphp
                                        <td>
                                            @if($purchase->status === 'overdue')
                                                <span class="flex items-center space-x-1 text-red-500 hover:text-red-600 transition-colors cursor-pointer group">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    <span>Overdue</span>
                                                </span>
                                            @elseif($purchase->status === 'paid')
                                                <span class="flex items-center space-x-1 text-green-500 hover:text-green-600 transition-colors cursor-pointer group">
                                                    <i class="fas fa-check-circle"></i>
                                                    <span>Paid</span>
                                                </span>
                                            @else
                                                <span class="flex items-center space-x-1 text-yellow-500 hover:text-yellow-600 transition-colors cursor-pointer group">
                                                    <i class="fas fa-hourglass-half animate-spin-slow"></i>
                                                    <span>Pending</span>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($purchase->status !== 'paid')
                                                <a href="{{ route('purchase.paid', $purchase->id) }}" class="btn btn-warning sm" title="Mark as Paid">
                                                    <i class="fas fa-check-circle"></i>
                                                </a>
                                                <a href="{{ route('purchase.edit', $purchase->id) }}" class="btn btn-info sm" title="Edit Purchase">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('purchase.delete', $purchase->id) }}" class="btn btn-danger sm" title="Delete Purchase" id="delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            <a href="{{ route('purchase.detail', $purchase->id) }}" class="btn btn-dark sm" title="Invoice Detail">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
