@extends('backend.base')
@section('title', 'Cliente - ' . $result->name)

@section('content')
    <section>
        <div class="">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><a href="{{ url('/customers') }}" class="mr-2"><i
                                        class="fa fa-angle-left"></i></a> @yield('title')</h5>
                            <div class="form-row">
                                <div class="col-xs-12 col-md-4">
                                    <p class="m-0">Selecione outro cliente:</p>
                                    <select name="select_customer" id="select_customer" class="form-control">
                                        <option value="{{ $result->id }}">#{{ $result->id }} - {{ $result->name }}
                                            ({{ $result->company }})</option>
                                        <optgroup label="Outros clientes">
                                            @foreach ($allCustomers as $allCustomer)
                                                <option value="{{ $allCustomer->id }}">#{{ $allCustomer->id }} -
                                                    {{ $allCustomer->name }} ({{ $allCustomer->company }})</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <hr>

                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="{{ route('backend.customers.show', $result->id) }}"
                                        aria-controls="resume" aria-selected="false">Resumo</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="{{ route('backend.customers.showServices', $result->id) }}"
                                        aria-controls="services" aria-selected="false">Serviços</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active"
                                        href="{{ route('backend.customers.showInvoices', $result->id) }}"
                                        aria-controls="invoices" aria-selected="true">Faturas</a>
                                </li>
                            </ul>
                            <div class="tab-content pt-4" id="nav-tabContent">
                                <!-- Begin :: Tabe - Myinvoices -->
                                <!-- Begin :: Tabe - Myinvoices -->
                                <div class="tab-pane active" id="invoices" role="tabpanel" aria-labelledby="invoices-tab">
                                    <!-- conteudo -->
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover mb-0">
                                            <thead>
                                                <th width="40px">
                                                    <div class="form-group form-check">
                                                        <input type="checkbox" id="checkAll" value=""
                                                            class="form-check-input">
                                                    </div>
                                                </th>
                                                <th width="50px"><a href=""><i class="fa fa-sort"></i></a> #</th>
                                                <th><a href=""><i class="fa fa-sort"></i></a> Data da Fatura</th>
                                                <th><a href=""><i class="fa fa-sort"></i></a> Vencimento</th>
                                                <th><a href=""><i class="fa fa-sort"></i></a> Pago em</th>
                                                <th><a href=""><i class="fa fa-sort"></i></a> Total</th>
                                                <th><a href=""><i class="fa fa-sort"></i></a> Método</th>
                                                <th>Status</th>
                                                <th width="100px" class="text-center">Ações</th>
                                            </thead>
                                            <tbody>
                                                <form class="form" id="form-table-invoice">
                                                    {{ csrf_field() }}
                                                    @if ($myInvoices)
                                                        @foreach ($myInvoices as $myInvoice)
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group form-check">
                                                                        <input type="checkbox" name="selectedInvoices[]"
                                                                            value="{{ $myInvoice->id }}"
                                                                            class="form-check-input">
                                                                    </div>
                                                                </td>
                                                                <td>{{ $myInvoice->id }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($myInvoice->date_invoice)->format('d/m/Y') }}
                                                                </td>
                                                                <td>{{ \Carbon\Carbon::parse($myInvoice->date_end)->format('d/m/Y') }}
                                                                </td>
                                                                <td>{{ $myInvoice->date_payment ? \Carbon\Carbon::parse($myInvoice->date_payment)->format('d/m/Y') : '-' }}
                                                                </td>
                                                                <td>R$ {{ $myInvoice->price }}</td>
                                                                <td><b>{{ $myInvoice->payment_method }}</b></td>
                                                                <td>
                                                                    @if ($myInvoice->status == 'pago')
                                                                        <span class="badge badge-success"><i
                                                                                class="fa fa-check"></i> Pago</span>
                                                                    @elseif($myInvoice->status == 'nao_pago')
                                                                        <span class="badge badge-danger"><i
                                                                                class="fa fa-minus"></i> Não Pago</span>
                                                                    @else
                                                                        <span class="badge badge-secondary"><i
                                                                                class="fa fa-remove"></i> Cancelado</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="btn-group btn-group-toggle"
                                                                        data-toggle="buttons">
                                                                        <a href="#" id="button-edit-invoice"
                                                                            data-id="{{ $myInvoice->id }}"
                                                                            data-type="edit"
                                                                            data-customer_id="{{ $result->id }}"
                                                                            data-status-invoice="{{$myInvoice->status}}"
                                                                            class="btn btn-xs btn-primary"
                                                                            data-toggle="tooltip" data-placement="bottom"
                                                                            title="Ver/Editar"><i
                                                                                class="fa fa-book"></i></a>
                                                                        <a href="#" id="button-notification-invoice"
                                                                            data-id="{{ $myInvoice->id }}"
                                                                            data-type="edit"
                                                                            data-customer_id="{{ $result->id }}"
                                                                            data-status-invoice="{{$myInvoice->status}}"
                                                                            class="btn btn-xs btn-warning"
                                                                            data-toggle="tooltip" data-placement="bottom"
                                                                            title="Notificações">
                                                                            <i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </form>
                                            </tbody>
                                        </table>
                                    </div><!-- table-responsive -->
                                    <div class="d-flex align-items-center mt-3">
                                        <div class="mr-2"><small>Com os Selecionados:</small></div>
                                        <div><a href="#" id="btn-delete-invoice" class="btn btn-xs btn-danger"><i
                                                    class="fa fa-times"></i> Remover selecionados</a></div>
                                        <div class="flex-grow-1">
                                        </div>
                                    </div><!-- d-flex -->
                                    <hr>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <a href="#" class="btn btn-sm btn-success" id="button-create-invoice"
                                                data-type="create" data-customer_id="{{ $result->id }}"><i
                                                    class="fa fa-plus"></i> Nova Fatura</a>
                                        </div>
                                    </div>

                                    <!-- Modal :: Form MyInvoice -->
                                    <div class="modal fade" id="modalmyInvoices" tabindex="-1" role="dialog"
                                        aria-labelledby="modalmyInvoicesLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form action="" class="form-horizontal" id="form-request-invoice">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalmyInvoicesLabel">Editar Fatura</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body" id="form-content-invoice">
                                                        <!-- conteudo -->
                                                        <!-- conteudo -->
                                                    </div><!-- modal-body -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success"
                                                            id="btn-salvar-invoice"><i class="fa fa-check"></i>
                                                            Salvar</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                                                class="fa fa-times"></i> Cancelar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal :: Form MyInvoice -->

                                      <!-- Modal :: Form Notification -->
                                      <div class="modal fade" id="modalNotificationInvoice" tabindex="-1" role="dialog"
                                      aria-labelledby="modalmyNotificationLabel" aria-hidden="true">
                                      <div class="modal-dialog modal-lg">
                                          <div class="modal-content">
                                              <form action="" class="form-horizontal" id="form-request-notification-invoice">
                                                  <div class="modal-header">
                                                      <h5 class="modal-title" id="modalmyInvoicesLabel">Notificações</h5>
                                                      <button type="button" class="close" data-dismiss="modal"
                                                          aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                      </button>
                                                  </div>
                                                  <div class="modal-body" id="form-content-notification-invoice">
                                                      <!-- conteudo -->
                                                      <!-- conteudo -->
                                                  </div><!-- modal-body -->
                                              </form>
                                          </div>
                                      </div>
                                  </div>
                                  <!-- Modal :: Form MyInvoice -->

                                    <!-- conteudo -->
                                </div><!-- tab-pane -->
                                <!-- end :: Tabe - Myinvoices -->
                                <!-- end :: Tabe - Myinvoices -->

                            </div><!-- tab-content -->

                        </div><!-- card-body -->
                    </div><!-- card -->
                </div><!-- cols -->
            </div><!-- row -->
        </div><!--  -->
    </section>
@endsection

@section('cssPage')
    <link rel="stylesheet" href="{{ asset('/general/plugins/sweetalert/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
@endsection

@section('jsPage')
    <script>
        // Change Details User - Select Change
        $('#select_customer').on('change', function() {
            var data_id = $(this).val();
            location.href = `${data_id}`;
        });
    </script>

    <script src="{{ asset('/general/plugins/sweetalert/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>


    <script>
        // Open Modal - Create - Invoices
        $(document).on("click", "#button-create-invoice", function() {
            var customer_id = $(this).data('customer_id');
            $("#form-content-invoice").html('');
            $("#modalmyInvoices").modal('show');
            var url = `{{ url('/invoices-create/${customer_id}') }}`;
            console.log(url);
            $.get(url,
                $(this)
                .addClass('modal-scrollfix')
                .find('#form-content-invoice')
                .html('Carregando...'),
                function(data) {
                    // console.log(data);
                    $("#form-content-invoice").html(data);
                    $("#btn-salvar-invoice").attr('data-type', 'create');
                    formatedDate();
                    formatedPrice();
                    formatedPhone();
                });
        });

        // Open Modal - Edit - Invoices
        $(document).on("click", "#button-edit-invoice", function(e) {
            e.preventDefault();

            let id = $(this).data('id');
            var customer_id = $(this).data('customer_id');
            var status_invoice = $(this).data('status-invoice');
            $("#form-content-invoice").html('');
            $("#modalmyInvoices").modal('show');
            var url = `{{ url('/invoices-edit/${customer_id}/${id}/') }}`;
            $.get(url,
                $(this)
                .addClass('modal-scrollfix')
                .find('#form-content-invoice')
                .html('Carregando...'),
                function(data) {
                    // console.log(data);
                    $("#form-content-invoice").html(data);
                    if(status_invoice == 'nao_pago'){
                        $("#btn-salvar-invoice").attr('data-type', 'edit').attr('data-id', id);
                    }else{
                        $("#btn-salvar-invoice").prop('disabled', true);
                    }

                    formatedDate();
                    // formatedPrice();
                    formatedPhone();

                    // Button Confirm Payment Invoice
                    $(document).on('click', '#button-confirm-payment', function(e) {
                        e.preventDefault();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            }
                        });

                        let id_invoice = $(this).data('id-invoice');


                        if (id_invoice) {
                            var url = `{{ url('/invoices-confirm/${id_invoice}') }}`;
                            var method = 'PUT';
                        }

                        $.ajax({
                            url: url,
                            method: method,
                            success: function(data) {
                                Swal.fire({
                                    text: data,
                                    icon: 'success',
                                    showClass: {
                                        popup: 'animate_animated animate_backInUp'
                                    },
                                    onClose: () => {
                                        // Loading page listagem
                                        // location.href = "{{ url($url_action) }}";
                                        location.reload();
                                    }
                                });
                            },
                            error: function(xhr) {
                                if (xhr.status === 422) {
                                    Swal.fire({
                                        text: 'Validação: ' + xhr.responseJSON,
                                        icon: 'warning',
                                        showClass: {
                                            popup: 'animate_animated animate_wobble'
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        text: 'Erro interno, informe ao suporte: ' +
                                            xhr.responseJSON,
                                        icon: 'error',
                                        showClass: {
                                            popup: 'animate_animated animate_wobble'
                                        }
                                    });
                                }
                            }
                        });
                    });
                    // end button Confirm Payment Service

                });
        });



         // Open Modal - Notification - Invoice
         $(document).on("click", "#button-notification-invoice", function() {
            var invoice_id = $(this).data('id');
            $("#form-content-notification-invoice").html('');
            $("#modalNotificationInvoice").modal('show');
            var url = `{{ url('/invoices-notification/${invoice_id}') }}`;
            console.log(url);
            $.get(url,
                $(this)
                .addClass('modal-scrollfix')
                .find('#form-content-notification-invoice')
                .html('Carregando...'),
                function(data) {
                    // console.log(data);
                    $("#form-content-notification-invoice").html(data);
                });
        });



        // Button Save Forms - Create and Edit - Invoices
        $(document).on('click', '#btn-salvar-invoice', function(e) {
            e.preventDefault();
            //disable the submit button
            $("form #btn-salvar-invoice").attr("disabled", true);
            $('form #btn-salvar-invoice').append('<i class="fa fa-spinner fa-spin ml-3"></i>');
            setTimeout(function() {
                $('form #btn-salvar-invoice').prop("disabled", false);
                $('form #btn-salvar-invoice').find('.fa-spinner').addClass('d-none');
            }, 5000);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            let id = $(this).data('id');
            var type = $(this).data('type');

            if (type == 'edit') {
                if (id) {
                    var url = `{{ url('/invoices-update/${id}') }}`;
                    var method = 'PUT';
                }
            } else {
                var url = "{{ url('/invoices-store') }}";
                var method = 'POST';
            }
            var data = $('#form-request-invoice').serialize();
            $.ajax({
                url: url,
                data: data,
                method: method,
                success: function(data) {
                    Swal.fire({
                        text: data,
                        icon: 'success',
                        showClass: {
                            popup: 'animate_animated animate_backInUp'
                        },
                        onClose: () => {
                            // Loading page listagem
                            // location.href = "{{ url($url_action) }}";
                            location.reload();
                        }
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        Swal.fire({
                            text: 'Validação: ' + xhr.responseJSON,
                            icon: 'warning',
                            showClass: {
                                popup: 'animate_animated animate_wobble'
                            }
                        });
                    } else {
                        Swal.fire({
                            text: 'Erro interno, informe ao suporte: ' + xhr.responseJSON,
                            icon: 'error',
                            showClass: {
                                popup: 'animate_animated animate_wobble'
                            }
                        });
                    }
                }
            });
        });


        // Button - Delete - Invoices
        $('#btn-delete-invoice').click(function(e) {
            Swal.fire({
                title: 'Deseja remover este registro?',
                text: "Você não poderá reverter isso!",
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, deletar!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('/invoices-delete') }}",
                        method: 'DELETE',
                        data: $('#form-table-invoice').serialize(),
                        success: function(data) {
                            // Loading page listagem
                            // location.href = "{{ url($url_action) }}";
                            location.reload();
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                Swal.fire({
                                    text: xhr.responseJSON,
                                    icon: 'warning',
                                    showClass: {
                                        popup: 'animate_animated animate_wobble'
                                    }
                                });
                            } else {
                                Swal.fire({
                                    text: xhr.responseJSON,
                                    icon: 'error',
                                    showClass: {
                                        popup: 'animate_animated animate_wobble'
                                    }
                                });
                            }
                        }
                    });
                }
            });

        });
    </script>



@endsection
