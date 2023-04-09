@extends('backend.base')
@section('title', $titulo)

@section('content')
<section>
  <div class="">
    <div class="row">
      <div class="col-xs-12 col-md-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">@yield('title')</h5>

            <ul>
                <li>Quantidade Total de Clientes Ativos = {{ $getTotalCustomers }}</li>
                <li>Quantidade Total de Serviços Ativos = {{ $getTotalServices }}</li>
                <li>Quantidade Total de Recebiveis por Mês = {{ $getTotalMoneyMonth }}</li>
                <li>Quantidade Total de Recebiveis por Ano = {{ $getTotalMoneyYear }}</li>
                <li>Quantidade de Faturas Atrasadas = {{ $getInvoicesDues }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
@endsection

@section('cssPage')
@endsection

@section('jsPage')
@endsection