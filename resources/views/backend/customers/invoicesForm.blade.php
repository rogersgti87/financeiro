<p><small><b>Campos obrigatórios (<span class="text-danger">*</span>)</b></small></p>


{{-- <input type="hidden" id="customer_service_id" name="customer_service_id" value="{{ $result->customer_service_id }}"> --}}




<fieldset>
  <h6>Informações da Fatura</h6>
  <div class="form-row align-items-center">

    <div class="col-xs-12 col-md-12">
        <div class="form-group">
          <label for="customer_service_id" class="col-form-label">Serviço:</label>
          <select name="customer_service_id" id="customer_service_id" class="form-control" {{ isset($result->status) && $result->status != 'nao_pago' ? 'disabled' : ''}}>
            @foreach($customer_services as $cs)
                <option value="{{ $cs->id }}" {{isset($result->customer_service_id) && $result->customer_service_id == $cs->id ? 'selected' : ''}}>{{ $cs->service_name }}</option>
            @endforeach
          </select>
        </div><!-- form-group -->
      </div><!-- col -->

  </div><!-- form-row -->

  <div class="form-row align-items-center">
    <div class="col-xs-4 col-md-4">
      <div class="form-group">
        <label for="price" class="col-form-label">Valor (<span class="text-danger">*</span>):</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">R$</span>
          </div>
          <input type="text" id="price" name="price" class="form-control formatedPrice" placeholder="100,00" value="{{isset($result->price) ? $result->price : ''}}" {{ isset($result->status) && $result->status != 'nao_pago' ? 'disabled' : ''}}>
        </div>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-4 col-md-4">
      <div class="form-group">
        <label for="payment_method" class="col-form-label">Método:</label>
        <select name="payment_method" id="payment_method" class="form-control" {{ isset($result->status) && $result->status != 'nao_pago' ? 'disabled' : ''}}>
          <option value="Dinheiro" {{isset($result->payment_method) && $result->payment_method == 'Dinheiro' ? 'selected' : ''}}>Dinheiro</option>
          <option value="Pix" {{isset($result->payment_method) && $result->payment_method == 'Pix' ? 'selected' : ''}}>Pix</option>
          <option value="Boleto" {{isset($result->payment_method) && $result->payment_method == 'Boleto' ? 'selected' : ''}}>Boleto</option>
        </select>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-4 col-md-4">
      <div class="form-group">
        <label for="status" class="col-form-label">Status:</label>
        <select name="status" id="status" class="form-control" {{ isset($result->status) && $result->status != 'nao_pago' ? 'disabled' : ''}}>
          <option value="pago" {{ isset($result->status) && $result->status == "pago" ? 'selected' : ''}}>Pago</option>
          <option value="nao_pago" {{ isset($result->status) && $result->status == "nao_pago" ? 'selected' : ''}}>Não pago</option>
          <option value="cancelado" {{ isset($result->status) && $result->status == "cancelado" ? 'selected' : ''}}>Cancelado</option>
        </select>
      </div><!-- form-group -->
    </div><!-- col -->
  </div><!-- form-row -->

  <div class="form-row">
    <div class="col-xs-4 col-md-4">
      <div class="form-group">
        <label for="date_invoice" class="col-form-label">Data Fatura (<span class="text-danger">*</span>):</label>
        <input type="text" id="date_invoice" name="date_invoice" class="form-control formatedDate" placeholder="Data Fatura" value="{{isset($result->date_invoice) ? date('d/m/Y',strtotime($result->date_invoice)) : \Carbon\Carbon::now()->format('d/m/Y') }}" {{ isset($result->status) && $result->status != 'nao_pago' ? 'disabled' : ''}}>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-4 col-md-4">
      <div class="form-group">
        <label for="date_end" class="col-form-label">Data Vencimento (<span class="text-danger">*</span>):</label>
        <input type="text" id="date_end" name="date_end" class="form-control formatedDate" placeholder="Data Vencimento" value="{{isset($result->date_end) ? date('d/m/Y',strtotime($result->date_end)) : \Carbon\Carbon::now()->format('d/m/Y') }}" {{ isset($result->status) && $result->status != 'nao_pago' ? 'disabled' : ''}}>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-4 col-md-4">
      <div class="form-group">
        <label for="date_payment" class="col-form-label">Data Pagamento:</label>
        <input type="text" id="date_payment" name="date_payment" class="form-control formatedDate" placeholder="Data Pagamento" value="{{isset($result->date_payment) ? date('d/m/Y',strtotime($result->date_payment)) : ''}}" {{ isset($result->status) && $result->status != 'nao_pago' ? 'disabled' : ''}}>
      </div><!-- form-group -->
    </div><!-- col -->
  </div><!-- form-row -->

  @if(!isset($result->price))
  <div id="group-generate-invoice">
    <div class="form-row">
      <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" name="send_invoice" id="send_invoice" value="1">
        <label class="form-check-label" for="send_invoice">Marque para <b>Enviar a fatura por e-mail.</b></label>
      </div>
    </div>
  </div>
  @endif
</fieldset>


<fieldset>
  <div class="form-row">
    <div class="col-xs-12 col-md-12">
      <div class="card">
        <div class="card-body text-center" id="form-request-invoice-confirm">

            @if(isset($result->status) && $result->status == 'pago')
                <div class="form-group">
                    <button type="button" id="button-confirm-payment" class="btn btn-sm btn-success" data-id-invoice="{{ isset($result->id) ? $result->id : '' }}"><i class="fa fa-check"></i> Reenviar Email de Confirmação de pagamento</button>
                  </div>
            @else
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="sendmailinvoice" id="sendmailinvoice" value="1">
                <label class="form-check-label" for="sendmailinvoice">Marque para <b>Enviar Email de Confirmação de pagamento.</b></label>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</fieldset>


