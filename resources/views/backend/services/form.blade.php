<p><small><b>Campos obrigatórios (<span class="text-danger">*</span>)</b></small></p>

<fieldset>
  <div class="form-row align-items-center">
    <div class="col-xs-12 col-md-9">
      <div class="form-group">
        <label for="name" class="col-form-label">Nome (<span class="text-danger">*</span>):</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Nome" value="{{isset($result->name) ? $result->name : ''}}">
      </div><!-- form-group -->
    </div><!-- col -->


    <div class="col-xs-12 col-md-3">
      <div class="form-group">
        <label for="status">Status:</label>
        <select name="status" id="status" class="form-control">
          @if(isset($result->status))
          <option value="ativo" {{ $result->status == "ativo" ? 'selected' : ''}}>Ativo</option>
          <option value="pendente" {{ $result->status == "pendente" ? 'selected' : ''}}>Pendente</option>
          <option value="cancelado" {{ $result->status == "cancelado" ? 'selected' : ''}}>Cancelado</option>
          @else
          <option value="ativo">Ativo</option>
          <option value="pendente" selected>Pendente</option>
          <option value="cancelado">Cancelado</option>
          @endif
        </select>
      </div>
    </div><!-- col -->
  </div><!-- form-row -->

  <div class="form-row">
    <div class="col-xs-12 col-md-12">
      <div class="form-group">
        <label for="description" class="col-form-label">Descrição:</label>
        <textarea id="description" name="description" class="form-control" placeholder="Faça uma descrição sobre o serviço. (opcional)" rows="3">{{isset($result->description) ? $result->description : ''}}</textarea>
      </div><!-- form-group -->
    </div><!-- col -->
  </div><!-- form-row -->
</fieldset>

<fieldset>

  <div class="form-row">

    <div class="col-xs-3 col-md-3">
      <div class="form-group">
        <label for="period" class="col-form-label">Ciclo:</label>
        <select name="period" id="period" class="form-control">
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

    <div class="col-xs-3 col-md-3">
      <div class="form-group">
        <label for="price" class="col-form-label">Valor Único/Mensal (<span class="text-danger">*</span>):</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">R$</span>
          </div>
          <input type="text" id="price" name="price" class="form-control formatedPrice" placeholder="100,00" value="{{isset($result->price) ? $result->price : ''}}">
        </div>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-3 col-md-3 price_trimestral">
      <div class="form-group">
        <label for="price_trimestral" class="col-form-label">Valor Trimestral:</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">R$</span>
          </div>
          <input type="text" id="price_trimestral" name="price_trimestral" class="form-control formatedPrice" placeholder="300,00" value="{{isset($result->price_trimestral) ? $result->price_trimestral : ''}}">
        </div>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-3 col-md-3 price_anual">
      <div class="form-group">
        <label for="price_anual" class="col-form-label">Valor Anual:</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">R$</span>
          </div>
          <input type="text" id="price_anual" name="price_anual" class="form-control formatedPrice" placeholder="1200,00" value="{{isset($result->price_anual) ? $result->price_anual : ''}}">
        </div>
      </div><!-- form-group -->
    </div><!-- col -->

  </div><!-- form-row -->
</fieldset>