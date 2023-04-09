@extends('backend.base')
@section('title', 'Cliente - '.$result->name)

@section('content')
<section>
  <div class="">
    <div class="row">
      <div class="col-xs-12 col-md-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title"><a href="{{ url('/customers') }}" class="mr-2"><i class="fa fa-angle-left"></i></a> @yield('title')</h5>
            <div class="form-row">
              <div class="col-xs-12 col-md-4">
                <p class="m-0">Selecione outro cliente:</p>
                <select name="select_customer" id="select_customer" class="form-control">
                  <option value="{{$result->id }}">#{{$result->id }} - {{$result->name }} ({{$result->company }})</option>
                  <optgroup label="Outros clientes">
                    @foreach($allCustomers as $allCustomer)
                    <option value="{{$allCustomer->id }}">#{{$allCustomer->id }} - {{$allCustomer->name }} ({{$allCustomer->company}})</option>
                    @endforeach
                  </optgroup>
                </select>
              </div>
            </div>
            <hr>

            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" href="{{ route('backend.customers.show', $result->id) }}" aria-controls="resume" aria-selected="true">Resumo</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('backend.customers.showServices', $result->id) }}" aria-controls="services" aria-selected="false">Serviços</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('backend.customers.showInvoices', $result->id) }}" aria-controls="invoices" aria-selected="false">Faturas</a>
              </li>
            </ul>
            <div class="tab-content pt-4" id="nav-tabContent">
              <div class="tab-pane active" id="resume" role="tabpanel" aria-labelledby="resume-tab">
                <!-- conteudo -->
                <div class="row">
                  <div class="col-xs-12 col-md-4">
                    <div class="card">
                      <div class="card-body">
                        <h5>Informações do Cliente</h5>
                        <ul class="list-unstyled list-details">
                          <li>Nome: {{$result->name }}</li>
                          <li>Empresa: {{$result->company }}</li>
                          <li>Telefone: {{$result->phone }}</li>
                          <li>E-mail: {{$result->email }}</li>
                          <li>Endereço: {{$result->address }} ({{$result->number }})</li>
                          <li>CEP: {{$result->cep }}</li>
                          <li>Cidade: {{$result->city }}</li>
                          <li>Estado: {{$result->state }}</li>
                          <li>Status: <span class="btn btn-xs btn-success">{{$result->status }}</span></li>
                          <li>Criação: {{ \Carbon\Carbon::parse($result->created_at)->format('d/m/Y H:i') }}</li>
                          <li>Cliente {{ $result->created_at->diffForHumans() }}</li>
                        </ul>
                      </div><!-- card-body -->
                    </div><!-- card -->
                  </div><!-- cols -->

                  <div class="col-xs-12 col-md-4">
                    <div class="card">
                      <div class="card-body">
                        <h5>Detalhes de Pagamento</h5>
                        <ul class="list-unstyled list-details">
                          <li>Método: {{$result->payment_method }}</li>
                        </ul>
                        <li class="divider"></li>
                        <h5>Relatório de Faturas</h5>
                        <ul class="list-unstyled list-details">
                          <li>Pago: {{$myInvoicesPayCount}} <span class="badge badge-success">R$ {{$myInvoicesPayValue}}</span></li>
                          <li>Não Pago: {{$myInvoicesNotPayCount}} <span class="badge badge-danger">R$ {{$myInvoicesNotPayValue}}</span></li>
                          <li>Cancelado: {{$myInvoicesCanceledCount}} <span class="badge badge-secondary">R$ {{$myInvoicesCanceledValue}}</span></li>
                        </ul>
                      </div><!-- card-body -->
                    </div><!-- card -->
                  </div><!-- cols -->

                  <div class="col-xs-12 col-md-4">
                    <div class="card mb-3">
                      <div class="card-body">
                        <h5>Serviços ({{count($myServices)}})</h5>
                        <div class="widget box-rounded d-flex mt-3">
                          <div class="flex-fill">
                            <div class="icon success">{{$myServicesActives}}</div>
                            <div class="content">
                              <p>Ativos</p>
                            </div>
                          </div><!-- -->
                          <div class="flex-fill">
                            <div class="icon primary">{{$myServicesSuspended}}</div>
                            <div class="content">
                              <p>Suspensos</p>
                            </div>
                          </div><!-- -->
                          <div class="flex-fill">
                            <div class="icon danger">{{$myServicesCanceled}}</div>
                            <div class="content">
                              <p>Cancelados</p>
                            </div>
                          </div><!-- -->
                        </div><!-- widget -->
                      </div><!-- card-body -->
                    </div><!-- card -->

                  </div><!-- cols -->
                </div><!-- row -->
                <!-- conteudo -->
              </div><!-- tab-pane -->


              <!-- Begin :: Tabe - MyServices -->
              <!-- Begin :: Tabe - MyServices -->
              <div class="tab-pane" id="services" role="tabpanel" aria-labelledby="services-tab">
                <!-- conteudo -->
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover mb-0">
                    <thead>
                      <th width="40px">
                        <div class="form-group form-check">
                          <input type="checkbox" id="checkAll" value="" class="form-check-input">
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
                        @if($myServices)
                        @foreach($myServices as $myService)
                        <tr>
                          <td>
                            <div class="form-group form-check">
                              <input type="checkbox" name="selectedServices[]" value="{{ $myService->id }}" class="form-check-input">
                            </div>
                          </td>
                          <td>{{ $myService->id }}</td>
                          <td>{{ $myService->nameService }}</td>
                          <td>{{ $myService->dominio }}</td>
                          <td>{{ \Carbon\Carbon::parse($myService->date_start)->format('d/m/Y') }}</td>
                          <td>{{ \Carbon\Carbon::parse($myService->date_end)->format('d/m/Y') }}</td>
                          <td>R$ {{ $myService->price }}</td>
                          <td>{{ $myService->period }}</td>
                          <td>
                            @if($myService->status == 'ativo')
                            <span class="badge badge-success"><i class="fa fa-check"></i> {{$myService->status}}</span>
                            @elseif($myService->status == 'pendente')
                            <span class="badge badge-info"><i class="fa fa-minus"></i> {{$myService->status}}</span>
                            @else
                            <span class="badge badge-secondary"><i class="fa fa-remove"></i> {{$myService->status}}</span>
                            @endif
                          </td>
                          <td class="text-center">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                              <a href="#" id="button-edit-service" data-id="{{ $myService->id }}" data-type="edit" data-customer_id="{{$result->id }}" class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="bottom" title="Editar Serviço"><i class="fa fa-edit"></i></a>
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
                  <div><a href="#" id="btn-delete-service" class="btn btn-xs btn-danger"><i class="fa fa-times"></i> Remover selecionados</a></div>
                  <div class="flex-grow-1">
                  </div>
                </div><!-- d-flex -->
                <hr>
                <div class="row">
                  <div class="col-xs-12">
                    <a href="#" class="btn btn-sm btn-success" id="button-create-service" data-type="create" data-customer_id="{{$result->id }}"><i class="fa fa-plus"></i> Adicionar Serviço</a>
                  </div>
                </div>

                <!-- Modal :: Form MyService -->
                <div class="modal fade" id="modalmyServices" tabindex="-1" role="dialog" aria-labelledby="modalmyServicesLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <form action="" class="form-horizontal" id="form-request-service">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalmyServicesLabel">Editar Serviço</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body" id="form-content-service">
                          <!-- conteudo -->
                          <!-- conteudo -->
                        </div><!-- modal-body -->
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-success" id="btn-salvar-service"><i class="fa fa-check"></i> Salvar</button>
                          <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
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


              <!-- Begin :: Tabe - Myinvoices -->
              <!-- Begin :: Tabe - Myinvoices -->
              <div class="tab-pane" id="invoices" role="tabpanel" aria-labelledby="invoices-tab">
                <!-- conteudo -->
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover mb-0">
                    <thead>
                      <th width="40px">
                        <div class="form-group form-check">
                          <input type="checkbox" id="checkAll" value="" class="form-check-input">
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
                        @if($myInvoices)
                        @foreach($myInvoices as $myInvoice)
                        <tr>
                          <td>
                            <div class="form-group form-check">
                              <input type="checkbox" name="selectedInvoices[]" value="{{ $myInvoice->id }}" class="form-check-input">
                            </div>
                          </td>
                          <td>{{ $myInvoice->id }}</td>
                          <td>{{ \Carbon\Carbon::parse($myInvoice->date_invoice)->format('d/m/Y') }}</td>
                          <td>{{ \Carbon\Carbon::parse($myInvoice->date_end)->format('d/m/Y') }}</td>
                          <td>{{ $myInvoice->date_payment ? \Carbon\Carbon::parse($myInvoice->date_payment)->format('d/m/Y') : '-'}}</td>
                          <td>R$ {{ $myInvoice->price }}</td>
                          <td><b>{{ $myInvoice->payment_method }}</b></td>
                          <td>
                            @if($myInvoice->status == 'pago')
                            <span class="badge badge-success"><i class="fa fa-check"></i> Pago</span>
                            @elseif($myInvoice->status == 'nao_pago')
                            <span class="badge badge-danger"><i class="fa fa-minus"></i> Não Pago</span>
                            @else
                            <span class="badge badge-secondary"><i class="fa fa-remove"></i> Cancelado</span>
                            @endif
                          </td>
                          <td class="text-center">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                              <a href="#" id="button-edit-invoice" data-id="{{ $myInvoice->id }}" data-type="edit" data-customer_id="{{$result->id }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Ver/Editar"><i class="fa fa-book"></i></a>
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
                  <div><a href="#" id="btn-delete-service" class="btn btn-xs btn-danger"><i class="fa fa-times"></i> Remover selecionados</a></div>
                  <div class="flex-grow-1">
                  </div>
                </div><!-- d-flex -->
                <hr>
                <div class="row">
                  <div class="col-xs-12">
                    <a href="#" class="btn btn-sm btn-success" id="button-create-invoice" data-type="create" data-customer_id="{{$result->id }}"><i class="fa fa-plus"></i> Nova Fatura</a>
                  </div>
                </div>

                <!-- Modal :: Form MyInvoice -->
                <div class="modal fade" id="modalmyInvoices" tabindex="-1" role="dialog" aria-labelledby="modalmyInvoicesLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <form action="" class="form-horizontal" id="form-request-invoice">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalmyInvoicesLabel">Editar Fatura</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body" id="form-content-invoice">
                          <!-- conteudo -->
                          <!-- conteudo -->
                        </div><!-- modal-body -->
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-success" id="btn-salvar-invoice"><i class="fa fa-check"></i> Salvar</button>
                          <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                        </div>
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
    var url = `{{url('/customerservices-create/${customer_id}')}}`;
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
    var url = `{{url('/customerservices-edit/${customer_id}/${id}/')}}`;
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
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': "{{csrf_token()}}"
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
            // Loading page listagem
            // location.href = "{{url($url_action)}}";
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
          url: "{{url('/customerservices-delete')}}",
          method: 'DELETE',
          data: $('#form-table-service').serialize(),
          success: function(data) {
            // Loading page listagem
            // location.href = "{{url($url_action)}}";
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


<script>
  // Open Modal - Create - Invoices
  $(document).on("click", "#button-create-invoice", function() {
    var customer_id = $(this).data('customer_id');
    $("#form-content-invoice").html('');
    $("#modalmyInvoices").modal('show');
    var url = `{{url('/invoices-create/${customer_id}')}}`;
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
    $("#form-content-invoice").html('');
    $("#modalmyInvoices").modal('show');
    var url = `{{url('/invoices-edit/${customer_id}/${id}/')}}`;
    $.get(url,
      $(this)
      .addClass('modal-scrollfix')
      .find('#form-content-invoice')
      .html('Carregando...'),
      function(data) {
        // console.log(data);
        $("#form-content-invoice").html(data);
        $("#btn-salvar-invoice").attr('data-type', 'edit').attr('data-id', id);
        formatedDate();
        // formatedPrice();
        formatedPhone();

        // Button Confirm Payment Invoice
        $(document).on('click', '#button-confirm-payment', function(e) {
          e.preventDefault();
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': "{{csrf_token()}}"
            }
          });

          let id_invoice = $(this).data('id-invoice');


          if (id_invoice) {
            var url = `{{ url('/invoices-confirm/${id_invoice}') }}`;
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
                  // location.href = "{{url($url_action)}}";
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
        // end button Confirm Payment Service

      });
  });


  // Button Save Forms - Create and Edit - Invoices
  $(document).on('click', '#btn-salvar-invoice', function(e) {
    e.preventDefault();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': "{{csrf_token()}}"
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
            // location.href = "{{url($url_action)}}";
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
          url: "{{url('/invoices-delete')}}",
          method: 'DELETE',
          data: $('#form-table-invoice').serialize(),
          success: function(data) {
            // Loading page listagem
            // location.href = "{{url($url_action)}}";
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
