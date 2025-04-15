@extends('layout.app')
@section('main')

    <!-- Include jQuery and jQuery Validation plugin -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Add Invoice Page </h4><br><br>

                            <form method="post" action="{{ route('invoice.store') }}" id="myForm">
                                @csrf

                                <div class="row mb-3">
                                    <label for="invoice-date" class="col-sm-2 col-form-label">Invoice Date</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="date" id="invoice-date" name="invoice_date"
                                            value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Client Name</label>
                                    <div class="form-group col-sm-10">
                                        <select name="client_id" id="client-select" class="form-select select2" aria-label="Default select example">
                                            <option value="" selected disabled>Select Client</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}" data-payment-cycle="{{ $client->client_payment_cycle }}">
                                                    {{ $client->client_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="due-date" class="col-sm-2 col-form-label">Due Date</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="date" id="due-date" name="due_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="po-no" class="col-sm-2 col-form-label">PO no #</label>
                                    <div class="form-group col-sm-10">
                                        <input name="po_no" class="form-control" type="text">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="invoice-no" class="col-sm-2 col-form-label">Invoice no #</label>
                                    <div class="form-group col-sm-10">
                                        <input name="invoice_no" id="invoice-no" class="form-control" type="text">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                                    <div class="form-group col-sm-10">
                                        <textarea name="description" id="description" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                                    <div class="col-sm-10">
                                        <input name="amount" class="form-control" type="number" id="amount" min="0" step="0.01">
                                    </div>
                                </div>

                                <div style="margin-top: 100px">
                                    <input type="submit" class="btn btn-info waves-effect waves-light" value="Add">
                                    <a href="{{ route('clients.all') }}"
                                        class="btn btn-danger waves-effect waves-light">Cancel</a>
                                </div>

                            </form>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div>

        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            // Function to calculate due date based on payment cycle
            function calculateDueDate(invoiceDate, paymentCycle) {
                if (!invoiceDate || !paymentCycle) return '';

                const date = new Date(invoiceDate);
                const days = parseInt(paymentCycle);

                if (isNaN(days)) return '';

                date.setDate(date.getDate() + days);
                return date.toISOString().split('T')[0];
            }

            // Update due date when client or invoice date changes
            $('#client-select, #invoice-date').on('change', function() {
                const selectedOption = $('#client-select option:selected');
                const paymentCycle = selectedOption.data('payment-cycle');
                const invoiceDate = $('#invoice-date').val();

                console.log('Selected Client:', selectedOption.val());
                console.log('Payment Cycle:', paymentCycle);
                console.log('Invoice Date:', invoiceDate);

                if (paymentCycle && invoiceDate) {
                    const dueDate = calculateDueDate(invoiceDate, paymentCycle);
                    console.log('Calculated Due Date:', dueDate);
                    $('#due-date').val(dueDate);
                } else {
                    $('#due-date').val('');
                }
            });

            // Initialize due date if client is pre-selected
            if ($('#client-select').val()) {
                $('#client-select').trigger('change');
            }

            // Also trigger change when page loads if invoice date is set
            if ($('#invoice-date').val()) {
                $('#invoice-date').trigger('change');
            }

            // Initialize Select2
            $('.select2').select2();

            $('#myForm').validate({
                rules: {
                    invoice_date: {
                        required: true,
                    },
                    client_id: {
                        required: true,
                    },
                    due_date: {
                        required: true,
                    },
                    invoice_no: {
                        required: true,
                        remote: {
                            url: "{{ route('invoice.check.duplicate') }}",
                            type: "get",
                            data: {
                                invoice_no: function() {
                                    return $("#invoice-no").val();
                                }
                            }
                        }
                    },
                    description: {
                        required: true,
                    },
                    amount: {
                        required: true,
                        min: 0.01,
                        number: true
                    }
                },
                messages: {
                    invoice_date: {
                        required: 'Please select invoice date',
                    },
                    client_id: {
                        required: 'Please select a client',
                    },
                    due_date: {
                        required: 'Due date is required',
                    },
                    invoice_no: {
                        required: 'Please enter invoice number',
                        remote: 'This invoice number already exists'
                    },
                    description: {
                        required: 'Please enter description',
                    },
                    amount: {
                        required: 'Please enter amount',
                        min: 'Amount must be greater than 0',
                        number: 'Please enter a valid number'
                    }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>

@endsection
