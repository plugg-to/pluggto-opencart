<?php echo $header; ?>

<?php echo $column_left; ?>

<div id="content">
  <div class="page-header">

    <div class="alert alert-primary">
      <b><?php echo $alerts ?></b>
    </div>

    <div class="container-fluid">
      
      <div class="pull-right">
        <button type="submit" form="form-latest" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary">
          <i class="fa fa-save"></i>
        </button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default">
          <i class="fa fa-reply"></i>
        </a>
      </div>
      <h1><?php echo $heading_title; ?></h1>

      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>

    </div>
  </div>

  <div class="col-md-12 text-center" style="margin-bottom: 30px;">
    <img src="http://plugg.to/wp-content/uploads/2015/10/PluggTo-Header-Logo-Verde.png" alt="" /> 
  </div>

  <?php if (!empty($access_status)) { ?>
    <h3><?php echo $access_status ?></h3>
  <?php } ?>

    <div class="row" style="width: 97%;  margin-left: 15px;">
      <ul class="nav nav-tabs">
          <li class="nav active">
            <a href="#A" data-toggle="tab">Keys Integration</a>
          </li>
          <li class="nav">
            <a href="#B" data-toggle="tab">Settings</a>
          </li>
          <li>
            <a href="#C" data-toggle="tab">Setting Product</a>
          </li>
      </ul>
      <!-- Tab panes -->
      <div class="tab-content">
          
          <div class="tab-pane fade in active" id="A">
            <div class="row" style="width: 99%;">
              <div class="col-md-12" style="margin-left: 20px;">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">
                      <i class="fa fa-pencil"></i> Keys Integration
                    </h3>
                  </div>
                  <div class="panel-body">
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-banner" class="form-horizontal">
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">API User:</label>
                        <div class="col-sm-10">
                          <input type="text" name="api_user" id="api_user" value="<?php echo $credentials['api_user']; ?>" class="form-control" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">API Secret:</label>
                        <div class="col-sm-10">
                          <input type="text" name="api_secret" id="api_secret" value="<?php echo $credentials['api_secret']; ?>" class="form-control"/>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">Client ID:</label>
                        <div class="col-sm-10">
                          <input type="text" name="client_id" id="client_id" value="<?php echo $credentials['client_id']; ?>" class="form-control"/>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">Client Secret:</label>
                        <div class="col-sm-10">
                          <input type="text" name="client_secret" id="client_secret" value="<?php echo $credentials['client_secret']; ?>" class="form-control"/>
                        </div>
                      </div>
                      <div class="form-group" style="float: right; padding-right: 15px;">
                        <button type="submit" class="btn btn-success">Salvar</button>
                        <a href="javascript:validateDataInPluggTo();" class="btn btn-info">Verificar Dados</a>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane fade" id="C">            
            <div class="row" style="width: 99%;">
              <div class="col-md-12" style="margin-left: 20px;">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">
                      <i class="fa fa-pencil"></i> Atrelamento de Campos Basicos (Produto)
                    </h3>
                  </div>
                  <div class="panel-body">
                    <form action="<?php echo $action_basic_fields; ?>" method="post" enctype="multipart/form-data" id="form-banner" class="form-horizontal">
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">Largura:</label>
                        <div class="col-sm-10">
                          <select name="active" class="form-control">
                            <option value="1" <?php echo ($settingsProductsSynchronization->row['active'] == 1) ? 'selected' : ''; ?> >Sim</option>
                            <option value="0" <?php echo ($settingsProductsSynchronization->row['active'] == 0) ? 'selected' : ''; ?> >Não</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">Altura:</label>
                        <div class="col-sm-10">
                          <select name="active" class="form-control">
                            <option value="1" <?php echo ($settingsProductsSynchronization->row['active'] == 1) ? 'selected' : ''; ?> >Sim</option>
                            <option value="0" <?php echo ($settingsProductsSynchronization->row['active'] == 0) ? 'selected' : ''; ?> >Não</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">Comprimento:</label>
                        <div class="col-sm-10">
                          <select name="active" class="form-control">
                            <option value="1" <?php echo ($settingsProductsSynchronization->row['active'] == 1) ? 'selected' : ''; ?> >Sim</option>
                            <option value="0" <?php echo ($settingsProductsSynchronization->row['active'] == 0) ? 'selected' : ''; ?> >Não</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">Peso:</label>
                        <div class="col-sm-10">
                          <select name="active" class="form-control">
                            <option value="1" <?php echo ($settingsProductsSynchronization->row['active'] == 1) ? 'selected' : ''; ?> >Sim</option>
                            <option value="0" <?php echo ($settingsProductsSynchronization->row['active'] == 0) ? 'selected' : ''; ?> >Não</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">Marca/Fabricante:</label>
                        <div class="col-sm-10">
                          <select name="active" class="form-control">
                            <option value="1" <?php echo ($settingsProductsSynchronization->row['active'] == 1) ? 'selected' : ''; ?> >Sim</option>
                            <option value="0" <?php echo ($settingsProductsSynchronization->row['active'] == 0) ? 'selected' : ''; ?> >Não</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">EAN:</label>
                        <div class="col-sm-10">
                          <select name="active" class="form-control">
                            <option value="1" <?php echo ($settingsProductsSynchronization->row['active'] == 1) ? 'selected' : ''; ?> >Sim</option>
                            <option value="0" <?php echo ($settingsProductsSynchronization->row['active'] == 0) ? 'selected' : ''; ?> >Não</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">NBM:</label>
                        <div class="col-sm-10">
                          <select name="active" class="form-control">
                            <option value="1" <?php echo ($settingsProductsSynchronization->row['active'] == 1) ? 'selected' : ''; ?> >Sim</option>
                            <option value="0" <?php echo ($settingsProductsSynchronization->row['active'] == 0) ? 'selected' : ''; ?> >Não</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">NCM:</label>
                        <div class="col-sm-10">
                          <select name="active" class="form-control">
                            <option value="1" <?php echo ($settingsProductsSynchronization->row['active'] == 1) ? 'selected' : ''; ?> >Sim</option>
                            <option value="0" <?php echo ($settingsProductsSynchronization->row['active'] == 0) ? 'selected' : ''; ?> >Não</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">Descriçao:</label>
                        <div class="col-sm-10">
                          <select name="active" class="form-control">
                            <option value="1" <?php echo ($settingsProductsSynchronization->row['active'] == 1) ? 'selected' : ''; ?> >Sim</option>
                            <option value="0" <?php echo ($settingsProductsSynchronization->row['active'] == 0) ? 'selected' : ''; ?> >Não</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">ISBN:</label>
                        <div class="col-sm-10">
                          <select name="active" class="form-control">
                            <option value="1" <?php echo ($settingsProductsSynchronization->row['active'] == 1) ? 'selected' : ''; ?> >Sim</option>
                            <option value="0" <?php echo ($settingsProductsSynchronization->row['active'] == 0) ? 'selected' : ''; ?> >Não</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group" style="float: right; padding-right: 15px;">
                        <button type="submit" class="btn btn-success">Salvar</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane fade" id="B">            
            <div class="row" style="width: 99%;">
              <div class="col-md-12" style="margin-left: 20px;">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">
                      <i class="fa fa-pencil"></i> Products synchronization
                    </h3>
                  </div>
                  <div class="panel-body">
                    <form action="<?php echo $action_products; ?>" method="post" enctype="multipart/form-data" id="form-banner" class="form-horizontal">
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">Ativo:</label>
                        <div class="col-sm-10">
                          <select name="active" class="form-control">
                            <option value="1" <?php echo ($settingsProductsSynchronization->row['active'] == 1) ? 'selected' : ''; ?> >Sim</option>
                            <option value="0" <?php echo ($settingsProductsSynchronization->row['active'] == 0) ? 'selected' : ''; ?> >Não</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">Atualizar no OpenCart apenas estoque:</label>
                        <div class="col-sm-10">
                          <select name="refresh_only_stock" class="form-control">
                            <option value="1" <?php echo ($settingsProductsSynchronization->row['refresh_only_stock'] == 1) ? 'selected' : ''; ?> >Sim</option>
                            <option value="0" <?php echo ($settingsProductsSynchronization->row['refresh_only_stock'] == 0) ? 'selected' : ''; ?> >Não</option>
                          </select>
                          <br>
                          <small>Caso opção seja Sim, apenas estoque será gravado no OpenCart, demais atributos (Foto,Preço,Sku...) só será enviado do OpenCart para o Plugg.To</small><br>
                          <small><strong>OBS: Nao e possivel importar os produtos casos a opcao sim esteja ativa.</strong></small>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">Verificar divergencia de Estoque e Preço</label>
                        <div class="col-sm-10">
                          <a href="<?php echo $link_verify_stock_and_price_products; ?>" id="estoque-preco" class="btn btn-info">Verificar divergencia de Estoque e Preço</a>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">Importação de todos os produtos para OpenCart</label>
                        <div class="col-sm-10">
                          <a href="<?php echo $link_import_all_products_to_opencart; ?>" id="import-products" class="btn btn-warning">Importação de todos os produtos para OpenCart</a>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">Exportação de todos os produtos para o PluggTo</label>
                        <div class="col-sm-10">
                          <a href="<?php echo $link_export_all_products_to_pluggto; ?>" id="export-products" class="btn btn-danger">Exportação de todos os produtos para o PluggTo</a>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name">Desvincular todos produtos com o Plugg.To</label>
                        <div class="col-sm-10">
                          <a href="<?php echo $link_off_all_products_pluggto; ?>" id="off-all-products" class="btn btn-primary">Desvincular todos produtos com o Plugg.To</a>
                        </div>
                      </div>
                      </div>
                      <div class="form-group" style="float: right; padding-right: 15px;">
                        <button type="submit" class="btn btn-success">Salvar</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
 </div>
</div>

<script type="text/javascript">
  function validateDataInPluggTo()
  {
    var api_user      = $("#api_user").val()
    ,   api_secret    = $("#api_secret").val()
    ,   client_id     = $("#client_id").val()
    ,   client_secret = $("#client_secret").val()
    ;

    $.ajax({
      url: 'https://api.plugg.to/oauth/token',
      method: 'POST',
      dataType: 'json',
      data: {
        client_id: client_id,
        client_secret: client_secret,
        username: api_user,
        password: api_secret,
        grant_type: 'password'
      },
      statusCode: {
        200: function() {
          $("#api_user").css('border-color', 'green');
          $("#api_secret").css('border-color', 'green');
          $("#client_id").css('border-color', 'green');
          $("#client_secret").css('border-color', 'green');
        },
        400: function() {
          $("#api_user").css('border-color', 'red');
          $("#api_secret").css('border-color', 'red');
          $("#client_id").css('border-color', 'red');
          $("#client_secret").css('border-color', 'red');
        }
      }
    });
  }
</script>