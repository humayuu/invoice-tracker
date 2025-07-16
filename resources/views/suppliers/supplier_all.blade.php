@extends('layout.app')
@section('main')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Supplier All</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spapse: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th width="30%">Supplier Name</th>
                                    <th width="30%">Payment Cycle</th>
                                    <th width="30%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $key => $supplier)
                                @php
                                  $cycleClass = '';
                                    $icon = '';
                                    if($supplier->supplier_payment_cycle == 30) {
                                        $cycleClass = 'bg-info text-white';
                                        $icon = '<i class="fas fa-calendar-week mr-2"></i>';
                                    } elseif($supplier->supplier_payment_cycle == 45) {
                                        $cycleClass = 'bg-warning text-dark';
                                        $icon = '<i class="fas fa-calendar-alt mr-2"></i>';
                                    }else{
                                        $cycleClass = 'bg-warning text-dark';
                                        $icon = '<i class="fas fa-calendar-alt mr-2"></i>';
                                    }
                                @endphp
                                <tr data-cycle="{{ $supplier->supplier_payment_cycle }}" class="text-black fw-bold">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $supplier->supplier_name }}</td>
                                    <td>
                                        <span class="badge {{ $cycleClass }} p-2 font-size-14" style="font-weight: 600;">
                                            {!! $icon !!}
                                            {{ $supplier->supplier_payment_cycle }} Days
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('suppliers.purchases.report', $supplier->id) }}" target="_blank" class="btn btn-dark sm" title="Supplier Wise Purchases">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-info sm" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('suppliers.delete', $supplier->id) }}" class="btn btn-danger sm" title="Delete Data" id="delete">
                                            <i class="fas fa-trash-alt"></i>
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