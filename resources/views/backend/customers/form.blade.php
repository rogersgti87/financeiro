<p><small><b>Campos obrigatórios (<span class="text-danger">*</span>)</b></small></p>

<fieldset>
  <h6>Dados Pessoais</h6>
  <div class="form-row align-items-center">
    <div class="col-xs-6 col-md-6">
      <div class="form-group">
        <label for="name" class="col-form-label">Nome <small>(<span class="text-danger">*</span>)</small>:</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Nome" value="{{isset($result->name) ? $result->name : ''}}" required>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-6 col-md-6">
      <div class="form-group">
        <label for="company" class="col-form-label">Empresa (<span class="text-danger">*</span>):</label>
        <input type="text" id="company" name="company" class="form-control" placeholder="Empresa" value="{{isset($result->company) ? $result->company : ''}}" required>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-6 col-md-6">
      <div class="form-group">
        <label for="email" class="col-form-label">E-mail (<span class="text-danger">*</span>):</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="E-mail" value="{{isset($result->email) ? $result->email : ''}}" required>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-6 col-md-6">
        <div class="form-group">
          <label for="email2" class="col-form-label">E-mail 2</label>
          <input type="email" id="email2" name="email2" class="form-control" placeholder="E-mail2" value="{{isset($result->email2) ? $result->email2 : ''}}">
        </div><!-- form-group -->
      </div><!-- col -->

    <div class="col-xs-6 col-md-6">
      <div class="form-group">
        <label for="document" class="col-form-label">Documento (<span class="text-danger">*</span>):</label>
        <input type="text" id="document" name="document" class="form-control" placeholder="Documento" value="{{isset($result->document) ? $result->document : ''}}" required>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-6 col-md-2">
      <div class="form-group">
        <label for="status">Status:</label>
        @if(isset($result->status))
        @if($result->status == 'ativo')
        <div class="custom-control custom-switch checked-success">
          <input type="checkbox" class="custom-control-input" name="status" id="status" value="ativo" checked>
          <label class="custom-control-label" for="status" checked>Ativo</label>
        </div>
        @elseif($result->status == 'pendente')
        <div class="custom-control custom-switch checked-info">
          <input type="checkbox" class="custom-control-input" name="status" id="status" value="pendente">
          <label class="custom-control-label" for="status">Pendente</label>
        </div>
        @else
        <div class="custom-control custom-switch checked-danger">
          <input type="checkbox" class="custom-control-input" name="status" id="status" value="cancelado">
          <label class="custom-control-label" for="status">Cancelado</label>
        </div>
        @endif
        @else
        <div class="custom-control custom-switch checked-success">
          <input type="checkbox" class="custom-control-input" name="status" id="status" value="ativo" checked>
          <label class="custom-control-label" for="status" checked>Ativo</label>
        </div>
        @endif
      </div>
    </div><!-- col -->
  </div><!-- form-row -->
</fieldset>

<fieldset>

  <h6>Dados de Endereço</h6>
  <div class="form-row">
    <div class="col-xs-3 col-md-3">
      <div class="form-group">
        <label for="cep" class="col-form-label">CEP (<span class="text-danger">*</span>):</label>
        <input type="text" id="cep" name="cep" class="form-control" placeholder="CEP" value="{{isset($result->cep) ? $result->cep : ''}}" required>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-6 col-md-6">
      <div class="form-group">
        <label for="address" class="col-form-label">Endereço (<span class="text-danger">*</span>):</label>
        <input type="text" id="address" name="address" class="form-control" placeholder="Endereço" value="{{isset($result->address) ? $result->address : ''}}" required>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-3 col-md-3">
      <div class="form-group">
        <label for="number" class="col-form-label">Número (<span class="text-danger">*</span>):</label>
        <input type="text" id="number" name="number" class="form-control" placeholder="Número" value="{{isset($result->number) ? $result->number : ''}}" required>
      </div><!-- form-group -->
    </div><!-- col -->
  </div><!-- form-row -->

  <div class="form-row">
    <div class="col-xs-12 col-md-12">
      <div class="form-group">
        <label for="complement" class="col-form-label">Complemento:</label>
        <input type="text" id="complement" name="complement" class="form-control" placeholder="Complemento" value="{{isset($result->complement) ? $result->complement : ''}}">
      </div><!-- form-group -->
    </div><!-- col -->
  </div><!-- form-row -->

  <div class="form-row">
    <div class="col-xs-4 col-md-4">
      <div class="form-group">
        <label for="city" class="col-form-label">Cidade (<span class="text-danger">*</span>):</label>
        <input type="text" id="city" name="city" class="form-control" placeholder="Cidade" value="{{isset($result->city) ? $result->city : ''}}" required>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-4 col-md-4">
      <div class="form-group">
        <label for="state" class="col-form-label">Estado (<span class="text-danger">*</span>):</label>
        <input type="text" id="state" name="state" class="form-control" placeholder="Estado" value="{{isset($result->state) ? $result->state : ''}}" required>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-4 col-md-4">
      <div class="form-group">
        <label for="phone" class="col-form-label">Telefone (<span class="text-danger">*</span>):</label>
        <input type="text" id="phone" name="phone" class="form-control formatPhone" placeholder="Telefone" value="{{isset($result->phone) ? $result->phone : ''}}" required>
      </div><!-- form-group -->
    </div><!-- col -->
  </div><!-- form-row -->
</fieldset>


<fieldset>
  <h6>Formas de Pagamentos</h6>
  <div class="form-row">
    <div class="col-xs-4 col-md-4">
      <div class="form-group">
        <label for="payment_method" class="col-form-label">Método de Pagamento:</label>
        <select name="payment_method" id="payment_method" class="form-control">
          @if(isset($result->payment_method))
          <optgroup label="Opção selecionada">
            <option value="{{ $result->payment_method }}" selected>{{ $result->payment_method }}</option>
          </optgroup>
          @endif
          <optgroup label="Outros meios">
          <option value="Pix">PIX</option>
          <option value="Boleto">Boleto</option>
          <option value="Dinheiro">Dinheiro</option>
          </optgroup>
        </select>
      </div><!-- form-group -->
    </div><!-- col -->
  </div><!-- form-row -->
</fieldset>
