@extends('backend.base')
@section('title', 'Fatura #'.$result->id)

@section('content')
<section>
  <div class="">
    <div class="row">
      <div class="col-xs-12 col-md-12">
        <div class="card">
          <div class="card-body">
            <h2 class="card-title">@yield('title') 
              @if($result->status == 'pago')
              <span class="badge badge-success">Pago</span>
              @elseif($result->status == 'nao_pago')
              <span class="badge badge-danger">Em aberto</span>
              @else
              <span class="badge badge-dark">Cancelado</span>
              @endif
            </h2>

            <ul>
              <li>Data da Fatura: {{ \Carbon\Carbon::parse($result->date_invoice)->format('d/m/Y')}}</li>
              <li>Forma de Pagamento: {{$result->payment_method}}</li>
            </ul>
            <hr>
            <div class="row">
              <div class="col-xs-12 col-md-6">
                <p><b>Pagar a:</b></p>
                <ul>
                  <li>InnSystem Inovação em Sistemas</li>
                  <li>contato@innsystem.com.br</li>
                  <li>CNPJ: 21.635.247/0001-54</li>
                </ul>
              </div><!-- cols -->
              <div class="col-xs-12 col-md-6">
                <p><b>Faturado para:</b></p>
                <ul>
                  <li>{{$getCustomer->name}} ({{$getCustomer->company}})</li>
                  <li>{{$getCustomer->address}}, N° {{$getCustomer->number}}</li>
                  <li>{{$getCustomer->city}} - {{$getCustomer->state}}</li>
                  <li>{{$getCustomer->phone}}</li>
                </ul>
              </div><!-- cols -->
            </div>

            <div class="row">
              <div class="col-xs-12 col-md-12">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <th>Descrição</th>
                    <th>Valor</th>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{$result->description}}</td>
                      <td>R$ {{$result->price}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            
            @if($result->status == 'nao_pago')
            <hr>

            <h4>Formas de Pagamentos</h4>
            <blockquote>Detalhar meios de pagamentos</blockquote>
            @endif
          </div><!-- card-body -->
        </div><!-- card -->
      </div><!-- cols -->
    </div><!-- row -->
  </div><!--  -->
</section>
@endsection

@section('cssPage')
@endsection

@section('jsPage')
@endsection