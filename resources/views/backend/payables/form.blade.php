<p><small><b>Campos obrigatórios (<span class="text-danger">*</span>)</b></small></p>

<fieldset>
  <div class="form-row align-items-center">

    <div class="col-xs-12 col-md-3">
        <div class="form-group">
          <label for="category_id">Categoria:</label>
          <select name="category_id" id="category_id" class="form-control"  {{ isset($result->status) && $result->status != 'Nao pago' ? 'disabled' : ''}}>
            @if(isset($categories))
            @foreach($categories as $category)
            <option value="{{ $category->id}}" {{isset($result->category_id) && $result->category_id == $category->id ? 'selected': '' }}>{{ $category->name }}</option>
            @endforeach
            @endif
          </select>
        </div>
      </div><!-- col -->

    <div class="col-xs-12 col-md-6">
      <div class="form-group">
        <label for="name" class="col-form-label">Descrição (<span class="text-danger">*</span>):</label>
        <input type="text" id="description" name="description" class="form-control" placeholder="Descrição" value="{{isset($result->description) ? $result->description : ''}}"  {{ isset($result->status) && $result->status != 'Nao pago' ? 'disabled' : ''}}>
      </div><!-- form-group -->
    </div><!-- col -->


    <div class="col-xs-12 col-md-3">
      <div class="form-group">
        <label for="status">Status:</label>
        <select name="status" id="status" class="form-control"  {{ isset($result->status) && $result->status != 'Nao pago' ? 'disabled' : ''}}>
          <option value="Nao pago">Não Pago</option>
          <option value="Pago">Pago</option>
          <option value="Cancelado">Cancelado</option>
        </select>
      </div>
    </div><!-- col -->
  </div><!-- form-row -->


</fieldset>

<fieldset>

  <div class="form-row">

    <div class="col-xs-3 col-md-3">
      <div class="form-group">
        <label for="period" class="col-form-label">Ciclo:</label>
        <select name="period" id="period" class="form-control"  {{ isset($result->status) && $result->status != 'Nao pago' ? 'disabled' : ''}}>
          @if(isset($result->period))
          <optgroup label="Opção selecionada">
            <option value="{{ $result->period }}" selected>{{ $result->period }}</option>
          </optgroup>
          @endif
          <optgroup label="Outras opções">
            <option value="unico">Único</option>
            <option value="recorrente">Recorrente</option>
          </optgroup>

        </select>
      </div><!-- form-group -->
    </div><!-- col -->


    <div class="col-xs-4 col-md-3">
        <div class="form-group">
          <label for="date_payable" class="col-form-label">Data Fatura (<span class="text-danger">*</span>):</label>
          <input type="text" id="date_payable" name="date_payable" class="form-control formatedDate" placeholder="Data Fatura" value="{{isset($result->date_payable) ? date('d/m/Y',strtotime($result->date_payable)) : \Carbon\Carbon::now()->format('d/m/Y') }}" {{ isset($result->status) && $result->status != 'Nao pago' ? 'disabled' : ''}}>
        </div><!-- form-group -->
      </div><!-- col -->

      <div class="col-xs-4 col-md-3">
        <div class="form-group">
          <label for="date_end" class="col-form-label">Data Vencimento (<span class="text-danger">*</span>):</label>
          <input type="text" id="date_end" name="date_end" class="form-control formatedDate" placeholder="Data Vencimento" value="{{isset($result->date_end) ? date('d/m/Y',strtotime($result->date_end)) : \Carbon\Carbon::now()->format('d/m/Y') }}" {{ isset($result->status) && $result->status != 'Nao pago' ? 'disabled' : ''}}>
        </div><!-- form-group -->
      </div><!-- col -->

      <div class="col-xs-4 col-md-3">
        <div class="form-group">
          <label for="date_payment" class="col-form-label">Data Pagamento:</label>
          <input type="text" id="date_payment" name="date_payment" class="form-control formatedDate" placeholder="Data Pagamento" value="{{isset($result->date_payment) ? date('d/m/Y',strtotime($result->date_payment)) : ''}}" {{ isset($result->status) && $result->status != 'Nao pago' ? 'disabled' : ''}}>
        </div><!-- form-group -->
      </div><!-- col -->

    <div class="col-xs-4 col-md-3">
        <div class="form-group">
          <label for="payment_method" class="col-form-label">Método:</label>
          <select name="payment_method" id="payment_method" class="form-control" {{ isset($result->status) && $result->status != 'Nao pago' ? 'disabled' : ''}}>
            <option value="Dinheiro" {{isset($result->payment_method) && $result->payment_method == 'Dinheiro' ? 'selected' : ''}}>Dinheiro</option>
            <option value="Pix" {{isset($result->payment_method) && $result->payment_method == 'Pix' ? 'selected' : ''}}>Pix</option>
            <option value="Boleto" {{isset($result->payment_method) && $result->payment_method == 'Boleto' ? 'selected' : ''}}>Boleto</option>
            <option value="Cartao" {{isset($result->payment_method) && $result->payment_method == 'Cartao' ? 'selected' : ''}}>Cartão</option>
          </select>
        </div><!-- form-group -->
      </div><!-- col -->

    <div class="col-xs-3 col-md-3">
      <div class="form-group">
        <label for="price" class="col-form-label">Valor Único/Mensal (<span class="text-danger">*</span>):</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">R$</span>
          </div>
          <input type="text" id="price" name="price" class="form-control formatedPrice" placeholder="100,00" value="{{isset($result->price) ? number_format($result->price,2,',','.') : ''}}"  {{ isset($result->status) && $result->status != 'Nao pago' ? 'disabled' : ''}}>
        </div>
      </div><!-- form-group -->
    </div><!-- col -->

  </div><!-- form-row -->
</fieldset>
