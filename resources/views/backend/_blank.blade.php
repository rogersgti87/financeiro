@extends('backend.base')
@section('title', 'Em Branco')

@section('content')
<section>
  <div class="">
    <div class="row">
      <div class="col-xs-12 col-md-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">@yield('title')</h5>
            <hr>

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