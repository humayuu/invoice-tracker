@extends('layout.app')
@section('main')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0"> Client All</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spapse: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th width="30%">Client Name</th>
                                    <th width="30%">Payment Cycle</th>
                                    <th width="30%">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($clients as $key => $client)
                                @php
                                  $cycleClass = '';
                                    $icon = '';
                                    if($client->client_payment_cycle == 30) {
                                        $cycleClass = 'bg-info text-white';
                                        $icon = '<i class="fas fa-calendar-week mr-2"></i>';
                                    } elseif($client->client_payment_cycle == 45) {
                                        $cycleClass = 'bg-warning text-dark';
                                        $icon = '<i class="fas fa-calendar-alt mr-2"></i>';
                                    }else{
                                        $cycleClass = 'bg-warning text-dark';
                                        $icon = '<i class="fas fa-calendar-alt mr-2"></i>';
                                    }
                                @endphp

                                <tr data-cycle="{{ $client->client_payment_cycle }}" class="text-black fw-bold">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $client->client_name }}</td>
                                    <td>
                                        <span class="badge {{ $cycleClass }} p-2 font-size-14" style="font-weight: 600;">
                                            {!! $icon !!}
                                            {{ $client->client_payment_cycle }} Days
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('clients.client.wise.view', $client->id) }}" target="_blank" class="btn btn-dark sm" title="Client Wise Invoice">
                                            <i class="fas fa-user"></i>
                                        </a>
                                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-info sm" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('clients.delete', $client->id) }}" class="btn btn-danger sm" title="Delete Data" id="delete">
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

@section('scripts')
<script>
$(document).ready(function() {
    // Filter functionality
    $('.filter-btn').click(function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');

        const cycle = $(this).data('cycle');
        if(cycle === 'all') {
            $('tbody tr').show();
        } else {
            $('tbody tr').hide();
            $(`tbody tr[data-cycle="${cycle}"]`).show();
        }
    });
});
</script>
@endsection

@endsection
