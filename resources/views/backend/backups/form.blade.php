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
          <option {{ isset($result->status) && $result->status == 'Ativo' ? 'selected' : ''}} value="Ativo">Ativo</option>
          <option {{ isset($result->status) && $result->status == 'Inativo' ? 'selected' : ''}} value="Inativo">Inativo</option>
        </select>
      </div>
    </div><!-- col -->

    <div class="col-xs-12 col-md-4">
      <div class="form-group">
        <label for="google_drive_folder_sql" class="col-form-label">Pasta Google Drive SQL (<span class="text-danger">*</span>):</label>
        <input type="text" id="google_drive_folder_sql" name="google_drive_folder_sql" class="form-control" placeholder="Pasta Google Drive SQL" value="{{isset($result->google_drive_folder_sql) ? $result->google_drive_folder_sql : ''}}">
      </div><!-- form-group -->
    </div><!-- col -->

        <div class="col-xs-12 col-md-4">
          <div class="form-group">
            <label for="google_drive_folder_file" class="col-form-label">Pasta Google Drive Arquivos (<span class="text-danger">*</span>):</label>
            <input type="text" id="google_drive_folder_file" name="google_drive_folder_file" class="form-control" placeholder="Pasta Google Drive SQL" value="{{isset($result->google_drive_folder_file) ? $result->google_drive_folder_file : ''}}">
          </div><!-- form-group -->
        </div><!-- col -->

        <div class="col-xs-12 col-md-4">
            <div class="form-group">
            <label for="folder_path" class="col-form-label">Caminho Pasta Arquivos Local (<span class="text-danger">*</span>):</label>
            <input type="text" id="folder_path" name="folder_path" class="form-control" placeholder="Caminho Pasta Arquivos Local" value="{{isset($result->folder_path) ? $result->folder_path : ''}}">
            </div><!-- form-group -->
        </div><!-- col -->

        <div class="col-xs-12 col-md-3">
            <div class="form-group">
            <label for="database" class="col-form-label">Banco de Dados (<span class="text-danger">*</span>):</label>
            <input type="text" id="database" name="database" class="form-control" placeholder="Banco de Dados" value="{{isset($result->database) ? $result->database : ''}}">
            </div><!-- form-group -->
        </div><!-- col -->

        <div class="col-xs-12 col-md-3">
            <div class="form-group">
            <label for="host" class="col-form-label">Host (<span class="text-danger">*</span>):</label>
            <input type="text" id="host" name="host" class="form-control" placeholder="Host" value="{{isset($result->host) ? $result->host : ''}}">
            </div><!-- form-group -->
        </div><!-- col -->

        <div class="col-xs-12 col-md-3">
            <div class="form-group">
            <label for="user" class="col-form-label">Usuário (<span class="text-danger">*</span>):</label>
            <input type="text" id="user" name="user" class="form-control" placeholder="user" value="{{isset($result->user) ? $result->user : ''}}">
            </div><!-- form-group -->
        </div><!-- col -->

        <div class="col-xs-12 col-md-3">
            <div class="form-group">
            <label for="password" class="col-form-label">Senha (<span class="text-danger">*</span>):</label>
            <input type="text" id="password" name="password" class="form-control" placeholder="Senha" value="{{isset($result->password) ? $result->password : ''}}">
            </div><!-- form-group -->
    </div><!-- col -->

        <div class="col-xs-12 col-md-3">
            <div class="form-group">
            <label for="port" class="col-form-label">Porta (<span class="text-danger">*</span>):</label>
            <input type="text" id="port" name="port" class="form-control" placeholder="Porta" value="{{isset($result->port) ? $result->port : ''}}">
            </div><!-- form-group -->
    </div><!-- col -->

</div><!-- form-row -->
</fieldset>
