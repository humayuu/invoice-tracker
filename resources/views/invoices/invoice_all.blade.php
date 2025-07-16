@extends('layout.app')
@section('main')

<script>
function checkOverdueInvoices() {
    // Check if browser supports notifications
    if (!("Notification" in window)) {
        console.log("This browser does not support desktop notification");
        return;
    }

    // Check notification permission
    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    }

    // Function to check overdue invoices
    function checkInvoices() {
        const rows = document.querySelectorAll('#datatable tbody tr');
        rows.forEach(row => {
            const statusCell = row.querySelector('td:nth-child(6)');
            const invoiceNo = row.querySelector('td:nth-child(3)').textContent;
            const clientName = row.querySelector('td:nth-child(4)').textContent;
            const amount = row.querySelector('td:nth-child(5)').textContent;

            if (statusCell && statusCell.textContent.toLowerCase().includes('overdue')) {
                if (Notification.permission === "granted") {
                    const notification = new Notification("Overdue Invoice Alert!", {
                        body: `Invoice #${invoiceNo} for ${clientName}\nAmount: ${amount} is overdue`,
                        icon: "/favicon.ico"
                    });

                    notification.onclick = function() {
                        window.focus();
                    };
                }
            }
        });
    }

    // Check every 5 minutes
    setInterval(checkInvoices, 300000);

    // Initial check
    checkInvoices();
}

// Run when page loads
document.addEventListener('DOMContentLoaded', checkOverdueInvoices);
</script>

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Invoice All</h4>



                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Invoice All Data </h4>


                            <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th width='5%'>Sl</th>
                                        <th  width='10%'>Invoice Date</th>
                                        <th width='15%'>Invoice No #</th>
                                        <th width='25%'>Client Name</th>
                                        <th >Amount</th>
                                        <th >Status</th>
                                        <th width='15%'>Action</th>

                                </thead>


                                <tbody>

                                      @foreach ($invoices as $key => $invoice )
                                      <tr class="text-black fw-bold">
                                        <td>{{ $key+1 }}</td>
                                        <td >
                                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}
                                          </td>

                                        <td>{{ $invoice->invoice_no }}</td>
                                        <td>{{ $invoice->client->client_name }}</td>
                                        <td>{{ number_format(round($invoice->amount)) }}</td>

                                        @php
                                        // Using Carbon to get the current time
                                        $now = \Carbon\Carbon::now();
                                        // Check if the invoice is overdue: due date is in the past and not paid
                                        $isOverdue = ($invoice->due_date < $now && $invoice->status !== 'paid');
                                    @endphp

                                    <td>
                                        @if($isOverdue)
                                            <span class="flex items-center space-x-1 text-red-500 hover:text-red-600 transition-colors cursor-pointer group">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <span>Overdue</span>
                                            </span>
                                        @else
                                            @switch($invoice->status)
                                                @case('pending')
                                                    <span class="flex items-center space-x-1 text-yellow-500 hover:text-yellow-600 transition-colors cursor-pointer group">
                                                        <i class="fas fa-hourglass-half animate-spin-slow"></i>
                                                        <span>Pending</span>
                                                    </span>
                                                    @break

                                                @case('paid')
                                                    <span class="flex items-center space-x-1 text-green-500 hover:text-green-600 transition-colors cursor-pointer group">
                                                        <i class="fas fa-check-circle"></i>
                                                        <span>Paid</span>
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="flex items-center space-x-1 text-gray-500 hover:text-gray-600 transition-colors cursor-pointer group">
                                                        <i class="fas fa-info-circle"></i>
                                                        <span>{{ ucfirst($invoice->status) }}</span>
                                                    </span>
                                            @endswitch
                                        @endif
                                    </td>

                                        <td>
                                            @if($invoice->status !== 'paid')
                                            <a href="{{ route('invoice.paid', $invoice->id) }}" class="btn btn-warning sm" title="Invoice Paid">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                            <a href="{{ route('invoice.edit', $invoice->id) }}" class="btn btn-info sm"
                                                title="Edit Invoice"> <i class="fas fa-edit"></i> </a>
                                            @endif
                                            <a href="{{ route('invoice.delete', $invoice->id) }}" class="btn btn-danger sm"
                                                title="Delete Invoice" id="delete"> <i class="fas fa-trash-alt"></i> </a>
                                            <a href="{{ route('invoice.detail', $invoice->id) }}" class="btn btn-dark sm" title="Invoice Detail">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                            </a>
                                        </td>

                                    </tr>
                                      @endforeach

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->



        </div> <!-- container-fluid -->
    </div>


@endsection
