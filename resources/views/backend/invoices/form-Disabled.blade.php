<fieldset>
  <h6>Informações da Fatura</h6>
  <div class="form-row align-items-center">
    <div class="col-xs-12 col-md-6">
      <div class="form-group">
        <label for="customer" class="col-form-label">Cliente:</label>
        <select name="customer" id="customer" class="form-control">
          <option value="">#1 - Leonardo Augusto (InnSystem)</option>
        </select>
      </div><!-- form-group -->
    </div><!-- col -->
    <div class="col-xs-12 col-md-6">
      <div class="form-group">
        <label for="service" class="col-form-label">Serviço:</label>
        <select name="service" id="service" class="form-control">
          <option value="">#22 - Loja Virtual (luxfire.com.br)</option>
        </select>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-12 col-md-12">
      <div class="form-group">
        <label for="description" class="col-form-label">Descrição:</label>
        <input type="text" id="description" name="description" class="form-control" placeholder="Descrição" value="Loja Virtual - Plano mensal recorrente">
      </div><!-- form-group -->
    </div><!-- col -->
  </div><!-- form-row -->

<hr>

  <div class="form-row">
    <div class="col-xs-12 col-md-4">
      <div class="form-group">
        <label for="balance_positive" class="col-form-label">Valor Recorrente:</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">R$</span>
          </div>
          <input type="text" id="balance_positive" name="balance_positive" class="form-control formatedPrice" placeholder="100,00" value="100,00">
        </div>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-12 col-md-4">
      <div class="form-group">
        <label for="status" class="col-form-label">Status:</label>
        <select name="status" id="status" class="form-control">
          <option value="pago" selected>Pago</option>
          <option value="nao_pago">Não pago</option>
          <option value="cancelado">Cancelado</option>
        </select>
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-12 col-md-4">
      <div class="form-group">
        <label for="plan_method" class="col-form-label">Método:</label>
        <select name="plan_method" id="plan_method" class="form-control">
          <option value="mercadopago" selected>Mercado Pago</option>
          <option value="bancodobrasil">Banco do Brasil</option>
          <option value="bancodobradesco">Banco do Bradesco</option>
          <option value="bancodocaixa">Banco do Caixa</option>
        </select>
      </div><!-- form-group -->
    </div><!-- col -->
  </div><!-- form-row -->

  <div class="form-row">
    <div class="col-xs-12 col-md-4">
      <div class="form-group">
        <label for="date_invoice" class="col-form-label">Data Fatura:</label>
        <input type="text" id="date_invoice" name="date_invoice" class="form-control" placeholder="Data Fatura" value="21/01/2017">
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-12 col-md-4">
      <div class="form-group">
        <label for="date_end" class="col-form-label">Data Vencimento:</label>
        <input type="text" id="date_end" name="date_end" class="form-control" placeholder="Data Vencimento" value="21/07/2020">
      </div><!-- form-group -->
    </div><!-- col -->

    <div class="col-xs-12 col-md-4">
      <div class="form-group">
        <label for="date_payment" class="col-form-label">Data Pagamento:</label>
        <input type="text" id="date_payment" name="date_payment" class="form-control" placeholder="Data Pagamento" value="21/07/2020">
      </div><!-- form-group -->
    </div><!-- col -->
  </div><!-- form-row -->
</fieldset>

<fieldset>
  <div class="form-row">
    <div class="col-xs-12 col-md-6">
      <div class="card">
        <div class="card-body">
          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="sendmailinvoice">
            <label class="form-check-label" for="sendmailinvoice">Marque para Enviar Email de Confirmação</label>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Confirmar Pagamento</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</fieldset>