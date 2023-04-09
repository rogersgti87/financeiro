<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <base href="{{Request::url()}}" />
  <title>Painel de Controle - @yield('title')</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="{{ asset('galerias/favicon.ico?2') }}" />
  <!-- Include Fontawesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="{{ asset('general/css/bootstrap.css?1') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('backend/css/template.css?1') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('general/plugins/datepicker/css/bootstrap-datepicker.css?1') }}" type="text/css" />
  @yield('cssPage')
</head>

<body>
  <div class="boxed">
    <header>
      <div class="">
        <div class="d-flex">
          <div class="mx-3">
            <div id="logo">
              <a href="javascript:;">
                <img src="{{ asset('galerias/logo.png') }}" alt="Painel de Controle" class="img-fluid" style="width:100px;">
              </a>
            </div><!-- logo -->
          </div><!-- cols -->
          <div class="flex-fill">
            <div class="header-toolbar float-right">
              <ul>
                <li><a href="" data-toggle="tooltip" data-placement="bottom" title="Notificações"><i class="fa fa-bell"></i></a></li>
                <li><a href="" data-toggle="tooltip" data-placement="bottom" title="Meu Perfil"><i class="fa fa-user"></i></a></li>
                <li><a href="{{ route('backend.configs') }}" data-toggle="tooltip" data-placement="bottom" title="Configurações"><i class="fa fa-cog"></i></a></li>
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" data-toggle="tooltip" data-placement="bottom" title="Deslogar-se"><i class="fa fa-sign-out"></i></a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
                </form>
              </ul>
            </div>
          </div>
        </div><!-- d-flex -->
      </div><!-- container -->
    </header>
    <nav>
      <div class="">
        <div class="row">
          <div class="col-sm-12 col-md-12">
            @include('backend.includes.menuprincipal')
          </div>
        </div>
      </div><!-- container -->
    </nav>

    @yield('content')

    <footer>
      <p>Todos os Direitos Reservados</p>
    </footer>
  </div><!-- boxed -->

  <script type="text/javascript" src="{{ asset('general/js/jquery-3.5.1.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('general/js/bootstrap.bundle.js') }}"></script>
  <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
  <script type="text/javascript" src="{{ asset('general/js/jquery.maskedinput.js?1') }}"></script>
  <script type="text/javascript" src="{{ asset('general/plugins/datepicker/js/bootstrap-datepicker.js') }}"></script>
  <script type="text/javascript" src="{{ asset('general/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.min.js') }}"></script>
  @yield('jsPage')
  <script type="text/javascript" src="{{ asset('backend/js/functions.js?2') }}"></script>
</body>

</html>

