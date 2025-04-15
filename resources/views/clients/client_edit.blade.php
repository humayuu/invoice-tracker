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
                            <h4 class="card-title">Edit Client Page </h4><br><br>

                            <form method="post" action="{{ route('clients.update') }}" id="myForm">
                                <input type="hidden" name="id" value="{{ $client->id }}">
                                @csrf

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Client Name </label>
                                    <div class="form-group col-sm-10">
                                        <input name="client_name" class="form-control" type="text" value="{{ $client->client_name }}">
                                    </div>
                                </div>
                                <!-- end row -->
                                <div class="row mb-5">
                                    <label class="col-sm-2 col-form-label">Client Payment Cycle </label>
                                    <div class="form-group col-sm-10">
                                        <select name="client_payment_cycle" class="form-select"
                                            aria-label="Default select example">
                                            <option value="30" {{ ($client->client_payment_cycle == 30) ? 'selected' : null }}>30 Days</option>
                                            <option value="45" {{ ($client->client_payment_cycle == 45) ? 'selected' : null }}>45 Days</option>
                                            <option value="60" {{ ($client->client_payment_cycle == 60) ? 'selected' : null }}>60 Days</option>
                                            <option value="90" {{ ($client->client_payment_cycle == 90) ? 'selected' : null }}>90 Days</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- end row -->

                                <div style="margin-top: 100px">
                                    <input type="submit" class="btn btn-info waves-effect waves-light" value="Update">
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
            $('#myForm').validate({
                rules: {
                    client_name: {
                        required: true,
                    },
                    client_payment_cycle: {
                        required: true,
                    }
                },
                messages: {
                    client_name: {
                        required: 'Please Enter a Client Name',
                    },
                    client_payment_cycle: {
                        required: 'Please Enter Client Payment Cycle',
                    },
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
                    form.submit();  // Allow the form to submit after validation
                }
            });
        });
    </script>

@endsection
