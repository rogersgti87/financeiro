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
                                    <a class="nav-link active"
                                        href="{{ route('backend.customers.showServices', $result->id) }}"
                                        aria-controls="services" aria-selected="true">Serviços</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="{{ route('backend.customers.showInvoices', $result->id) }}"
                                        aria-controls="invoices" aria-selected="false">Faturas</a>
                                </li>
                            </ul>
                            <div class="tab-content pt-4" id="nav-tabContent">
                                <!-- Begin :: Tabe - MyServices -->
                                <!-- Begin :: Tabe - MyServices -->
                                <div class="tab-pane active" id="services" role="tabpanel" aria-labelledby="services-tab">
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
                                                <th><a href=""><i class="fa fa-sort"></i></a> Serviço</th>
                                                <th><a href=""><i class="fa fa-sort"></i></a> Domínio</th>
                                                <th><a href=""><i class="fa fa-sort"></i></a> Início</th>
                                                <th><a href=""><i class="fa fa-sort"></i></a> Vencimento</th>
                                                <th><a href=""><i class="fa fa-sort"></i></a> Valor</th>
                                                <th><a href=""><i class="fa fa-sort"></i></a> Ciclo</th>
                                                <th>Status</th>
                                                <th width="100px" class="text-center">Ações</th>
                                            </thead>
                                            <tbody>
                                                <form class="form" id="form-table-service">
                                                    {{ csrf_field() }}
                                                    @if ($myServices)
                                                        @foreach ($myServices as $myService)
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group form-check">
                                                                        <input type="checkbox" name="selectedServices[]"
                                                                            value="{{ $myService->id }}"
                                                                            class="form-check-input">
                                                                    </div>
                                                                </td>
                                                                <td>{{ $myService->id }}</td>
                                                                <td>{{ $myService->nameService }}</td>
                                                                <td>{{ $myService->dominio }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($myService->date_start)->format('d/m/Y') }}
                                                                </td>
                                                                <td>{{ \Carbon\Carbon::parse($myService->date_end)->format('d/m/Y') }}
                                                                </td>
                                                                <td>R$ {{ $myService->price }}</td>
                                                                <td>{{ $myService->period }}</td>
                                                                <td>
                                                                    @if ($myService->status == 'ativo')
                                                                        <span class="badge badge-success"><i
                                                                                class="fa fa-check"></i>
                                                                            {{ $myService->status }}</span>
                                                                    @elseif($myService->status == 'pendente')
                                                                        <span class="badge badge-info"><i
                                                                                class="fa fa-minus"></i>
                                                                            {{ $myService->status }}</span>
                                                                    @else
                                                                        <span class="badge badge-secondary"><i
                                                                                class="fa fa-remove"></i>
                                                                            {{ $myService->status }}</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="btn-group btn-group-toggle"
                                                                        data-toggle="buttons">
                                                                        <a href="#" id="button-edit-service"
                                                                            data-id="{{ $myService->id }}"
                                                                            data-type="edit"
                                                                            data-customer_id="{{ $result->id }}"
                                                                            class="btn btn-xs btn-info"
                                                                            data-toggle="tooltip" data-placement="bottom"
                                                                            title="Editar Serviço"><i
                                                                                class="fa fa-edit"></i></a>
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
                                        <div><a href="#" id="btn-delete-service" class="btn btn-xs btn-danger"><i
                                                    class="fa fa-times"></i> Remover selecionados</a></div>
                                        <div class="flex-grow-1">
                                        </div>
                                    </div><!-- d-flex -->
                                    <hr>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <a href="#" class="btn btn-sm btn-success" id="button-create-service"
                                                data-type="create" data-customer_id="{{ $result->id }}"><i
                                                    class="fa fa-plus"></i> Adicionar Serviço</a>
                                        </div>
                                    </div>

                                    <!-- Modal :: Form MyService -->
                                    <div class="modal fade" id="modalmyServices" tabindex="-1" role="dialog"
                                        aria-labelledby="modalmyServicesLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form action="" class="form-horizontal" id="form-request-service">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalmyServicesLabel">Editar Serviço
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body" id="form-content-service">
                                                        <!-- conteudo -->
                                                        <!-- conteudo -->
                                                    </div><!-- modal-body -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success"
                                                            id="btn-salvar-service"><i class="fa fa-check"></i>
                                                            Salvar</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                                                class="fa fa-times"></i> Cancelar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal :: Form MyService -->

                                    <!-- conteudo -->
                                </div><!-- tab-pane -->
                                <!-- End :: Tabe - MyServices -->
                                <!-- End :: Tabe - MyServices -->

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
    <script>
        // Open Modal - Create - Services
        $(document).on("click", "#button-create-service", function() {
            var customer_id = $(this).data('customer_id');
            $("#form-content-service").html('');
            $("#modalmyServices").modal('show');
            var url = `{{ url('/customerservices-create/${customer_id}') }}`;
            console.log(url);
            $.get(url,
                $(this)
                .addClass('modal-scrollfix')
                .find('#form-content-service')
                .html('Carregando...'),
                function(data) {
                    // console.log(data);
                    $("#form-content-service").html(data);
                    $("#btn-salvar-service").attr('data-type', 'create');
                    formatedDate();
                    formatedPrice();
                    formatedPhone();
                    // aqui quando selecionar um serviço, buscar qual o valor dele e atualizar o campo de preço.
                    $('#service_id').on('change', function() {
                        var service_id = $(this).val();
                        var service_price = $(this).find(':selected').data('price');
                        $('#price').val(service_price);
                    });
                });
        });

        // Open Modal - Edit - Services
        $(document).on("click", "#button-edit-service", function(e) {
            e.preventDefault();

            let id = $(this).data('id');
            var customer_id = $(this).data('customer_id');
            $("#form-content-service").html('');
            $("#modalmyServices").modal('show');
            var url = `{{ url('/customerservices-edit/${customer_id}/${id}/') }}`;
            $.get(url,
                $(this)
                .addClass('modal-scrollfix')
                .find('#form-content-service')
                .html('Carregando...'),
                function(data) {
                    // console.log(data);
                    $("#form-content-service").html(data);
                    $("#btn-salvar-service").attr('data-type', 'edit').attr('data-id', id);
                    formatedDate();
                    // formatedPrice();
                    formatedPhone();
                    $('#group-generate-invoice').addClass('d-none');
                    $('#service_id').attr("style", "pointer-events: none;");
                });
        });


        // Button Save Forms - Create and Edit - Services
        $(document).on('click', '#btn-salvar-service', function(e) {
            e.preventDefault();
            //disable the submit button
            $("form #btn-salvar-service").attr("disabled", true);
            $('form #btn-salvar-service').append('<i class="fa fa-spinner fa-spin ml-3"></i>');
            setTimeout(function() {
                $('form #btn-salvar-service').prop("disabled", false);
                $('form #btn-salvar-service').find('.fa-spinner').addClass('d-none');
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
                    var url = `{{ url('/customerservices-update/${id}') }}`;
                    var method = 'PUT';
                }
            } else {
                var url = "{{ url('/customerservices-store') }}";
                var method = 'POST';
            }
            var data = $('#form-request-service').serialize();
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


        // Button - Delete - Services
        $('#btn-delete-service').click(function(e) {
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
                        url: "{{ url('/customerservices-delete') }}",
                        method: 'DELETE',
                        data: $('#form-table-service').serialize(),
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
