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

  <link rel="stylesheet" href="{{ asset('general/css/bootstrap.css?').rand() }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('backend/css/template.css?').rand() }}" type="text/css" />
  @yield('cssPage')
</head>

<body class="page-auth">
  <div class="boxed">

    <div class="d-flex align-content-center justify-content-center">
      <div class="box-login">

        <div id="logo" class="text-center">
          <a href="javascript:;">
            <img src="{{ asset('galerias/logo.png') }}" alt="Painel de Controle" class="img-fluid" style="width:250px;">
          </a>
        </div><!-- logo -->

        @yield('content')
      </div>
    </div>
    <footer>
      <p>Todos os Direitos Reservados</p>
    </footer>
  </div><!-- boxed -->

  <script type="text/javascript" src="{{ asset('general/js/jquery-3.5.1.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('general/js/bootstrap.bundle.js') }}"></script>
  <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
  <script type="text/javascript" src="{{ asset('general/js/jquery.maskedinput.js?1') }}"></script>
  @yield('jsPage')
  <script type="text/javascript" src="{{ asset('backend/js/functions.js?').rand() }}"></script>
</body>

</html>
