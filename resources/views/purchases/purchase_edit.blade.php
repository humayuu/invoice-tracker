@extends('layout.app')
@section('main')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Edit Purchase Page </h4><br><br>
                            <form method="post" action="{{ route('purchase.update') }}" id="myForm">
                                <input type="hidden" name="id" value="{{ $purchase->id }}">
                                @csrf
                                <div class="row mb-3">
                                    <label for="purchase-date" class="col-sm-2 col-form-label">Purchase Date</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="date" id="purchase-date" name="purchase_date" value="{{ $purchase->purchase_date }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Supplier Name</label>
                                    <div class="form-group col-sm-10">
                                        <select name="supplier_id" id="supplier-select" class="form-select select2" aria-label="Default select example">
                                            <option value="" selected disabled>Select Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" data-payment-cycle="{{ $supplier->supplier_payment_cycle }}" {{ ($purchase->supplier_id == $supplier->id) ? 'selected' : null  }}>
                                                    {{ $supplier->supplier_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="due-date" class="col-sm-2 col-form-label">Due Date</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="date" id="due-date" name="due_date" readonly value="{{ $purchase->due_date }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="po-no" class="col-sm-2 col-form-label">PO no #</label>
                                    <div class="form-group col-sm-10">
                                        <input name="po_no" class="form-control" type="text" value="{{ $purchase->po_no }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="purchase-invoice-no" class="col-sm-2 col-form-label">Purchase Invoice no #</label>
                                    <div class="form-group col-sm-10">
                                        <input name="purchase_invoice_no" id="purchase-invoice-no" class="form-control" type="text" value="{{ $purchase->purchase_invoice_no }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                                    <div class="form-group col-sm-10">
                                        <textarea name="description" id="description" class="form-control" rows="5">{{ $purchase->description }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                                    <div class="col-sm-10">
                                        <input name="amount" class="form-control" type="number" value="{{ round($purchase->amount) }}">
                                    </div>
                                </div>
                                <div style="margin-top: 100px">
                                    <input type="submit" class="btn btn-info waves-effect waves-light" value="Update">
                                    <a href="{{ route('purchase.all') }}" class="btn btn-danger waves-effect waves-light">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            function calculateDueDate(purchaseDate, paymentCycle) {
                if (!purchaseDate || !paymentCycle) return '';
                const date = new Date(purchaseDate);
                const days = parseInt(paymentCycle);
                if (isNaN(days)) return '';
                date.setDate(date.getDate() + days);
                return date.toISOString().split('T')[0];
            }
            $('#supplier-select, #purchase-date').on('change', function() {
                const selectedOption = $('#supplier-select option:selected');
                const paymentCycle = selectedOption.data('payment-cycle');
                const purchaseDate = $('#purchase-date').val();
                if (paymentCycle && purchaseDate) {
                    const dueDate = calculateDueDate(purchaseDate, paymentCycle);
                    $('#due-date').val(dueDate);
                } else {
                    $('#due-date').val('');
                }
            });
            if ($('#supplier-select').val()) {
                $('#supplier-select').trigger('change');
            }
            if ($('#purchase-date').val()) {
                $('#purchase-date').trigger('change');
            }
            $('.select2').select2();
            $('#myForm').validate({
                rules: {
                    purchase_date: {
                        required: true,
                    },
                    supplier_id: {
                        required: true,
                    },
                    due_date: {
                        required: true,
                    },
                    purchase_invoice_no: {
                        required: true,
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
                    purchase_date: {
                        required: 'Please select purchase date',
                    },
                    supplier_id: {
                        required: 'Please select a supplier',
                    },
                    due_date: {
                        required: 'Due date is required',
                    },
                    purchase_invoice_no: {
                        required: 'Please enter purchase invoice number',
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