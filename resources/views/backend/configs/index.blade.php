@extends('backend.base')
@section('title', 'Configurações')

@section('content')
<section>
  <div class="">
    <div class="row">
      <div class="col-xs-12 col-md-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">@yield('title')</h5>
            <hr>
            <form action="" class="form-horizontal" id="form-request">
              <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item"><a href="#config_geral" class="nav-link active" aria-controls="config_geral" role="tab" data-toggle="tab">API</a></li>
                  <li class="nav-item"><a href="#config_site" class="nav-link" aria-controls="config_site" role="tab" data-toggle="tab">E-mail</a></li>
                </ul>


                <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active" id="config_geral">
                    <!-- conteudo -->
                    <div class="row">
                    <div class="form-group col-md-6">
                      <label for="api_brasil_host" class="form-label">Host API BRASIL</label>
                      <input type="text" class="form-control" id="api_brasil_host" name="api_brasil_host" value="{{ $config->api_brasil_host ?? old('api_brasil_host') }}" placeholder="Host API Brasil">
                    </div><!-- form-group -->

                    <div class="form-group col-md-6">
                        <label for="api_brasil_secret_key" class="form-label">Secret API BRASIL</label>
                        <input type="text" class="form-control" id="api_brasil_secret_key" name="api_brasil_secret_key" value="{{ $config->api_brasil_secret_key ?? old('api_brasil_secret_key') }}" placeholder="Secret API Brasil">
                      </div><!-- form-group -->

                      <div class="form-group col-md-6">
                        <label for="api_brasil_device_token" class="form-label">Device Token API BRASIL</label>
                        <input type="text" class="form-control" id="api_brasil_device_token" name="api_brasil_device_token" value="{{ $config->api_brasil_device_token ?? old('api_brasil_device_token') }}" placeholder="Device Token API Brasil">
                      </div><!-- form-group -->

                      <div class="form-group col-md-6">
                        <label for="api_brasil_public_token" class="form-label">Public Token API BRASIL</label>
                        <input type="text" class="form-control" id="api_brasil_public_token" name="api_brasil_public_token" value="{{ $config->api_brasil_public_token ?? old('api_brasil_public_token') }}" placeholder="Public Token API Brasil">
                      </div><!-- form-group -->

                      <div class="form-group col-md-12">
                        <label for="api_brasil_bearer_token" class="form-label">Bearer Token API BRASIL</label>
                        <input type="text" class="form-control" id="api_brasil_bearer_token" name="api_brasil_bearer_token" value="{{ $config->api_brasil_bearer_token ?? old('api_brasil_bearer_token') }}" placeholder="Bearer Token API Brasil">
                      </div><!-- form-group -->

                      <div class="form-group col-md-12">
                        <label for="sendpulse_token" class="form-label">Token SenPulse</label>
                        <input type="text" class="form-control" id="sendpulse_token" name="sendpulse_token" value="{{ $config->sendpulse_token ?? old('sendpulse_token') }}" placeholder="Token SenPulse">
                      </div><!-- form-group -->

                      <div class="form-group col-md-12">
                        <label for="sendpulse_secret" class="form-label">Secret SenPulse</label>
                        <input type="text" class="form-control" id="sendpulse_secret" name="sendpulse_secret" value="{{ $config->sendpulse_secret ?? old('sendpulse_secret') }}" placeholder="Secret SenPulse">
                      </div><!-- form-group -->

                    </div> <!-- Fim row -->

                    <!-- conteudo -->
                  </div><!-- tabpanel -->

                  <div role="tabpanel" class="tab-pane" id="config_site">
                    <!-- conteudo -->
                    <div class="row">

                    <div class="form-group col-md-4">
                      <label for="smtp_host" class="form-label">SMTP Host</label>
                      <input type="text" class="form-control" id="smtp_host" name="smtp_host" value="{{ $config->smtp_host ?? old('smtp_host') }}" placeholder="SMTP Host">
                    </div><!-- form-group -->


                    <div class="form-group col-md-4">
                        <label for="smtp_user" class="form-label">SMTP Usuário</label>
                        <input type="text" class="form-control" id="smtp_user" name="smtp_user" value="{{ $config->smtp_user ?? old('smtp_user') }}" placeholder="SMTP Usuário">
                      </div><!-- form-group -->

                      <div class="form-group col-md-4">
                        <label for="smtp_password" class="form-label">SMTP Senha</label>
                        <input type="text" class="form-control" id="smtp_password" name="smtp_password" value="{{ $config->smtp_password ?? old('smtp_password') }}" placeholder="SMTP Senha">
                      </div><!-- form-group -->

                      <div class="form-group col-md-6">
                        <label for="smtp_port" class="form-label">SMTP Porta</label>
                        <input type="text" class="form-control" id="smtp_port" name="smtp_port" value="{{ $config->smtp_port ?? old('smtp_port') }}" placeholder="SMTP Porta">
                      </div><!-- form-group -->

                      <div class="form-group col-md-6">
                        <label for="smtp_security" class="form-label">SMTP SSL/TLS</label>
                        <input type="text" class="form-control" id="smtp_security" name="smtp_security" value="{{ $config->smtp_security ?? old('smtp_security') }}" placeholder="SMTP Secutiry">
                      </div><!-- form-group -->


                    </div><!-- Fim row -->

                    <!-- conteudo -->
                  </div><!-- tabpanel -->


                </div><!-- tab-content -->

              </div><!-- role tabpanel -->
              <hr>
              <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-success" id="btn-salvar"><i
                        class="fa fa-check"></i> Salvar</button>
                </div>
              </div><!-- row -->
            </form>

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

            var data = $('#form-request').serialize();
            $.ajax({
                url: `{{ url($url_action . '-update') }}`,
                data: data,
                method: 'PUT',
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


</script>


@endsection
