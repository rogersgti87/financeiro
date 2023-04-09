@extends('backend.base')
@section('title', 'Faturas')

@section('content')
    <section>
        <div class="">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">@yield('title') ({{ count($results) }})</h5>
                            <p><small>1 item(s) encontrado(s), Página 1 de 1</small></p>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover mb-0">
                                    <thead>
                                        <th width="40px">
                                            <div class="form-group form-check">
                                                <input type="checkbox" id="checkAll" value="" class="form-check-input">
                                            </div>
                                        </th>
                                        <th width="50px"><a href=""><i class="fa fa-sort"></i></a> #</th>
                                        <th><a href=""><i class="fa fa-sort"></i></a> Cliente</th>
                                        <th><a href=""><i class="fa fa-sort"></i></a> Data da Fatura</th>
                                        <th><a href=""><i class="fa fa-sort"></i></a> Vencimento</th>
                                        <th><a href=""><i class="fa fa-sort"></i></a> Pago em</th>
                                        <th><a href=""><i class="fa fa-sort"></i></a> Total</th>
                                        <th><a href=""><i class="fa fa-sort"></i></a> Método</th>
                                        <th>Status</th>
                                        <th width="100px" class="text-center">Ações</th>
                                    </thead>
                                    <tbody>
                                        <form class="form" id="form-table">
                                            {{ csrf_field() }}
                                            @foreach ($results as $result)
                                                <tr>
                                                    <td>
                                                        <div class="form-group form-check">
                                                            <input type="checkbox" name="selectedInvoices[]"
                                                                value="{{ $result->id }}" class="form-check-input">
                                                        </div>
                                                    </td>
                                                    <td><span
                                                            class="d-inline d-sm-inline d-md-inline d-lg-none">#</span>{{ $result->id }}
                                                    </td>
                                                    <td><a
                                                            href="{{ url('admin/customers-details/' . $result->customer_id) }}">{{ $result->nameCustomer }}</a>
                                                    </td>
                                                    <td><span
                                                            class="d-inline d-sm-inline d-md-inline d-lg-none">Fatura:</span>
                                                        {{ \Carbon\Carbon::parse($result->date_invoice)->format('d/m/Y') }}
                                                    </td>
                                                    <td><span
                                                            class="d-inline d-sm-inline d-md-inline d-lg-none">Vencimento:</span>
                                                        {{ \Carbon\Carbon::parse($result->date_end)->format('d/m/Y') }}
                                                    </td>
                                                    <td><span class="d-inline d-sm-inline d-md-inline d-lg-none">Pago
                                                            em:</span>
                                                        {{ $result->date_payment ? \Carbon\Carbon::parse($result->date_payment)->format('d/m/Y') : '-' }}
                                                    </td>
                                                    <td><span
                                                            class="d-inline d-sm-inline d-md-inline d-lg-none">Total:</span>
                                                        R$ {{ $result->price }}</td>
                                                    <td><b>{{ $result->payment_method }}</b></td>
                                                    <td>
                                                        @if ($result->status == 'pago')
                                                            <span class="badge badge-success"><i class="fa fa-check"></i>
                                                                Pago</span>
                                                        @elseif($result->status == 'nao_pago')
                                                            <span class="badge badge-danger"><i class="fa fa-minus"></i> Não
                                                                Pago</span>
                                                        @else
                                                            <span class="badge badge-secondary"><i class="fa fa-remove"></i>
                                                                Cancelado</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <a href="#" id="button-edit" data-id="{{ $result->id }}"
                                                                data-customer_id="{{ $result->customer_id }}"
                                                                data-type="edit" class="btn btn-xs btn-info"
                                                                data-toggle="tooltip" data-placement="bottom"
                                                                title="Editar Fatura"><i class="fa fa-edit"></i> <span
                                                                    class="d-inline d-sm-inline d-md-inline d-lg-none">Editar
                                                                    Fatura</span> </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </form>
                                    </tbody>
                                </table>
                                <div class="d-flex align-items-center mt-3">
                                    <div class="mr-2"><small>Com os Selecionados:</small></div>
                                    <div><a href="#" id="btn-delete" class="btn btn-xs btn-danger"><i
                                                class="fa fa-times"></i> Remover selecionados</a></div>
                                    <div class="flex-grow-1">
                                        {{ $results->appends(request()->query())->links() }}
                                    </div>
                                </div><!-- d-flex -->
                            </div><!-- table-responsive -->
                            <hr>


                            <!-- Modal Create Invoice -->
                            <div class="modal fade" id="modalInvoices" tabindex="-1" role="dialog"
                                aria-labelledby="modalInvoicesLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="" class="form-horizontal" id="form-request">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalInvoicesLabel"></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" id="form-content">
                                                <!-- conteudo -->
                                                <!-- conteudo -->
                                            </div><!-- modal-body -->
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success" id="btn-salvar"><i
                                                        class="fa fa-check"></i> Salvar</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                                        class="fa fa-times"></i> Cancelar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div><!-- card-body -->
                    </div><!-- card -->
                </div><!-- cols -->
            </div><!-- row -->
        </div><!--  -->
    </section>
@endsection

@section('cssPage')
    <link rel="stylesheet" href="{{ asset('/general/plugins/sweetalert/sweetalert2.min.css') }}">
@endsection

@section('jsPage')
    <script src="{{ asset('/general/plugins/sweetalert/sweetalert2.min.js') }}"></script>
    <script>
        // Open Modal - Create
        $(document).on("click", "#button-create", function() {
            $("#form-content").html('');
            $("#modalInvoices").modal('show');
            var url = `{{ url($url_action . '-create') }}`;
            $.get(url,
                $(this)
                .addClass('modal-scrollfix')
                .find('#form-content')
                .html('Carregando...'),
                function(data) {
                    // console.log(data);
                    $("#form-content").html(data);
                    $("#btn-salvar").attr('data-type', 'create');
                    formatedDate();
                    formatedPrice();
                    formatedPhone();
                });
        });

        // Open Modal - Edit
        $(document).on("click", "#button-edit", function(e) {
            e.preventDefault();

            let id = $(this).data('id');
            let customer_id = $(this).data('customer_id');
            $("#form-content").html('');
            $("#modalInvoices .modal-title").html('Editar Fatura #' + id);
            $("#modalInvoices").modal('show');
            var url = `{{ url($url_action . '-edit/${customer_id}/${id}/') }}`;
            $.get(url,
                $(this)
                .addClass('modal-scrollfix')
                .find('#form-content')
                .html('Carregando...'),
                function(data) {
                    // console.log(data);
                    $("#form-content").html(data);
                    $("#btn-salvar").attr('data-type', 'edit').attr('data-id', id);
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
                            var url = `{{ url('admin/invoices-confirm/${id_invoice}') }}`;
                            var method = 'PUT';
                        }

                        var data = $('#form-request-invoice-confirm').find('input:checked');
                        console.log(data);

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


        // Button Save Forms - Create and Edit
        $(document).on('click', '#btn-salvar', function(e) {
            e.preventDefault();
            //disable the submit button
            $("form #btn-salvar").attr("disabled", true);
            $('form #btn-salvar').append('<i class="fa fa-spinner fa-spin ml-3"></i>');
            setTimeout(function() {
                $('form #btn-salvar').prop("disabled", false);
                $('form #btn-salvar').find('.fa-spinner').addClass('d-none');
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
                    var url = `{{ url($url_action . '-update/${id}') }}`;
                    var method = 'PUT';
                }
            } else {
                var url = "{{ url($url_action . '-store') }}";
                var method = 'POST';
            }
            var data = $('#form-request').serialize();
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
                            location.href = "{{ url($url_action) }}";
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


        // Button - Delete - Not action Waiting
        $('#btn-delete').click(function(e) {
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
                        url: "{{ url($url_action . '-delete') }}",
                        method: 'DELETE',
                        data: $('#form-table').serialize(),
                        success: function(data) {
                            // Loading page listagem
                            location.href = "{{ url($url_action) }}";
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
