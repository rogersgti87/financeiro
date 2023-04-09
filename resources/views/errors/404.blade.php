@extends('frontend.base')

@section('title', 'Página não encontrada')
@section('content')
<section class="padding-100-0 position-relative">
  <div class="container">
    <div class="page-404-styles text-center">
      <img src="{{ asset('frontend/img/header/404.png') }}" alt="Página não Encontrada">
      <h6 class="megssage-error">Ooops! Desculpe, não encontramos esta página.</h6>
      <a class="btn-order-default-nuhost" href="{{ url('/') }}">Volte ao Início</a>
    </div>
  </div>
</section>
<section>
  <div class="container">
    <h5 class="title-default-coodiv-two">Conheça nossos serviços</h5>
    <div class="row justify-content-start futures-version-2 mt-5">

      <div class="col-md-4">
        <div class="futures-version-2-box">
          <i class="fa fa-cart-arrow-down"></i>
          <h5>Loja Virtual <small>(E-commerce)</small></h5>
          <p>Ofereça seus produtos e serviços para o mundo, através de uma loja totalmente exclusiva, feita sob medida, com segurança, suporte e de fácil administração.</p>
          <a class="btn btn-sm btn-primary btn-icon-green" href="{{ url('/loja-virtual') }}">conheça mais</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="futures-version-2-box">
          <i class="fa fa-magic"></i>
          <h5>Design Gráfico</h5>
          <p>A identidade visual aumenta significativamente a visibilidade e qualidade dos serviços ou produtos oferecidos aos consumidores.</p>
          <a class="btn btn-sm btn-primary btn-icon-purple" href="{{ url('/design-grafico') }}">conheça mais</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="futures-version-2-box">
          <i class="fa fa-desktop"></i>
          <h5>Criação de Site</h5>
          <p>Um site bem elaborado, criativo traz uma experiência incrível para os clientes, utilizando ferramentas e funcionalidades que aumentam a visibilidade do seu negócio.</p>
          <a class="btn btn-sm btn-primary btn-icon-blue" href="{{ url('/criacao-sites') }}">conheça mais</a>
        </div>
      </div>
      <div class="col-md-12 text-center">
        <a class="btn-order-default-nuhost my-5" href="https://api.whatsapp.com/send?phone=5516992747526">Entre em Contato</a>
      </div>
    </div>
  </div>
</section>
@endsection