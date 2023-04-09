<input type="hidden" id="customer_id" name="customer_id" value="{{isset($customer_id) ? $customer_id : 0}}">

<fieldset>
  <h6>Dados do Serviço</h6>
  <div class="form-row align-items-center">
    <div class="col-xs-6 col-md-6">
      <div class="form-group">
        <label for="service_id" class="col-form-label">Produto/Serviço (<span class="text-danger">*</span>):</label>
        <select name="service_id" id="service_id" class="form-control">
          <option disabled selected>Selecione um Produto/Serviço</option>
          @if(isset($getServices))
          @foreach($getServices as $getService)
          @if(isset($result->service_id))
          @if($getService->id == $result->service_id)
          <option value="{{ $getService->id }}" data-price="{{ $getService->price }}" selected>{{ $getService->name }}</option>
          @else
          <option value="{{ $getService->id }}" data-price="{{ $getService->price }}">{{ $getService->name }}</option>
          @endif
          @else
          <option value="{{ $getService->id }}" data-price="{{ $getService->price }}">{{ $getService->name }}</option>
          @endif
          @endforeach
          @endif
        </select>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-6 col-md-4">
      <div class="form-group">
        <label for="dominio" class="col-form-label">Domínio:</label>
        <input type="text" id="dominio" name="dominio" class="form-control" placeholder="Domínio" value="{{isset($result->dominio) ? $result->dominio : ''}}">
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-6 col-md-2">
      <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control">
          @if(isset($result->status))
          <option value="ativo" {{ $result->status == "ativo" ? 'selected' : ''}}>Ativo</option>
          <option value="pendente" {{ $result->status == "pendente" ? 'selected' : ''}}>Pendente</option>
          <option value="cancelado" {{ $result->status == "cancelado" ? 'selected' : ''}}>Cancelado</option>
          @else
          <option value="ativo" selected>Ativo</option>
          <option value="pendente">Pendente</option>
          <option value="cancelado">Cancelado</option>
          @endif
        </select>
      </div>
    </div><!-- col -->
  </div><!-- form-row -->

  <div class="form-row">
    <div class="col-xs-3 col-md-3">
      <div class="form-group">
        <label for="date_start" class="col-form-label">Data Início (<span class="text-danger">*</span>):</label>
        <input type="text" id="date_start" name="date_start" class="form-control formatedDate" placeholder="Data Início" value="{{isset($result->date_start) ? date('d/m/Y',strtotime($result->date_start)) : \Carbon\Carbon::now()->format('d/m/Y') }}" required>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-3 col-md-3">
      <div class="form-group">
        <label for="date_end" class="col-form-label">Data Vencimento (<span class="text-danger">*</span>):</label>
        <input type="text" id="date_end" name="date_end" class="form-control formatedDate" placeholder="Data Vencimento" value="{{isset($result->date_end) ? date('d/m/Y',strtotime($result->date_end)) : \Carbon\Carbon::now()->format('d/m/Y') }}" required>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-3 col-md-3">
      <div class="form-group">
        <label for="price" class="col-form-label">Valor Recorrente (<span class="text-danger">*</span>):</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">R$</span>
          </div>
          <input type="text" id="price" name="price" class="form-control formatedPrice" placeholder="100,00" value="{{isset($result->price) ? $result->price : ''}}" required>
        </div>

      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-3 col-md-3">
      <div class="form-group">
        <label for="period" class="col-form-label">Ciclo:</label>
        <select name="period" id="period" class="form-control">
          <option value="unico" selected>Único</option>
          <option value="mensal" selected>Mensal</option>
          <option value="trimestral">Trimestral</option>
          <option value="anual">Anual</option>
        </select>
      </div><!-- form-group -->
    </div><!-- col -->
  </div><!-- form-row -->

  <div id="group-generate-invoice">
    <div class="form-row">
      <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" name="generate_invoice" id="generate_invoice" value="1">
        <label class="form-check-label" for="generate_invoice">Marque para <b>Gerar Fatura</b> do serviço</label>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" name="send_invoice" id="send_invoice" value="1">
        <label class="form-check-label" for="send_invoice">Marque para <b>Enviar Fatura</b> do serviço</label>
      </div>
    </div>
  </div>
</fieldset>



<p><small><b>Campos obrigatórios (<span class="text-danger">*</span>)</b></small></p>
