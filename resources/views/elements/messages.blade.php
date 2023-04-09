@if (Session::has('messages.error') || isset($messages['error']))
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-block alert-danger">
            <button type="button" class="close" data-dismiss="alert"></button>
            <h4 class="alert-heading">Erro!</h4>
            @if (Session::has('messages.error'))
                @foreach(Session::get('messages.error') as $erro)
                <p>
                    {!! $erro !!}
                </p>
                @endforeach
            @endif
            @if (isset($messages['error']))
                @foreach($messages['error'] as $erro)
                <p>
                    {!! $erro !!}
                </p>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endif

@if (Session::has('messages.success') || isset($messages['success']))
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-block alert-success">
            <button type="button" class="close" data-dismiss="alert"></button>
            <h4 class="alert-heading">Sucesso!</h4>
            @if (Session::has('messages.success'))
                @foreach(Session::get('messages.success') as $success)
                <p>
                    {!! $success !!}
                </p>
                @endforeach
            @endif
            @if (isset($messages['success']))
                @foreach($messages['success'] as $success)
                <p>
                    {!! $success !!}
                </p>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endif

@if (Session::has('messages.info') || isset($messages['info']))
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-block alert-info">
            <button type="button" class="close" data-dismiss="alert"></button>
            <h4 class="alert-heading">Informação</h4>
            @if (Session::has('messages.info'))
                @foreach(Session::get('messages.info') as $info)
                <p>
                    {!! $info !!}
                </p>
                @endforeach
            @endif
            @if (isset($messages['info']))
                @foreach($messages['info'] as $info)
                <p>
                    {!! $info !!}
                </p>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endif