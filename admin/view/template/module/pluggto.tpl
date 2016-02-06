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
            <a href="<?php echo $link_basic_fields ?>">Setting Product</a> <!-- Aqui -->
          </li>

          <li>
            <a href="<?php echo $load_queue ?>">Queue</a> <!-- Aqui -->
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

          <div class="tab-pane fade" id="C">            
            <div class="row" style="width: 99%;">
              <form action="<?php echo $action_basic_fields; ?>" method="post" enctype="multipart/form-data" id="form-banner" class="form-horizontal">
                <div class="col-md-12" style="margin-left: 20px;">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h3 class="panel-title">
                        <i class="fa fa-pencil"></i> Atrelamento de Campos Basicos (Produto)
                      </h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Largura:</label>
                          <div class="col-sm-10">
                            <select name="fields[width]" class="form-control">
                              <option value="width">Largura</option>
                              <option value="height">Altura</option>
                              <option value="length">Comprimento</option>
                              <option value="weight">Peso</option>
                              <option value="brand">Fabricante</option>
                              <option value="ean">EAN</option>
                              <option value="nbm">NBM</option>
                              <option value="ncm">NCM</option>
                              <option value="description">Descriçao</option>
                              <option value="isbn">ISBN</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Altura:</label>
                          <div class="col-sm-10">
                            <select name="fields[height]" class="form-control">
                              <option value="width">Largura</option>
                              <option value="height">Altura</option>
                              <option value="length">Comprimento</option>
                              <option value="weight">Peso</option>
                              <option value="brand">Fabricante</option>
                              <option value="ean">EAN</option>
                              <option value="nbm">NBM</option>
                              <option value="ncm">NCM</option>
                              <option value="description">Descriçao</option>
                              <option value="isbn">ISBN</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Comprimento:</label>
                          <div class="col-sm-10">
                            <select name="fields[length]" class="form-control">
                              <option value="width">Largura</option>
                              <option value="height">Altura</option>
                              <option value="length">Comprimento</option>
                              <option value="weight">Peso</option>
                              <option value="brand">Fabricante</option>
                              <option value="ean">EAN</option>
                              <option value="nbm">NBM</option>
                              <option value="ncm">NCM</option>
                              <option value="description">Descriçao</option>
                              <option value="isbn">ISBN</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Peso:</label>
                          <div class="col-sm-10">
                            <select name="fields[weight]" class="form-control">
                              <option value="width">Largura</option>
                              <option value="height">Altura</option>
                              <option value="length">Comprimento</option>
                              <option value="weight">Peso</option>
                              <option value="brand">Fabricante</option>
                              <option value="ean">EAN</option>
                              <option value="nbm">NBM</option>
                              <option value="ncm">NCM</option>
                              <option value="description">Descriçao</option>
                              <option value="isbn">ISBN</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Marca/Fabricante:</label>
                          <div class="col-sm-10">
                            <select name="fields[brand]" class="form-control">
                              <option value="width">Largura</option>
                              <option value="height">Altura</option>
                              <option value="length">Comprimento</option>
                              <option value="weight">Peso</option>
                              <option value="brand">Fabricante</option>
                              <option value="ean">EAN</option>
                              <option value="nbm">NBM</option>
                              <option value="ncm">NCM</option>
                              <option value="description">Descriçao</option>
                              <option value="isbn">ISBN</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">EAN:</label>
                          <div class="col-sm-10">
                            <select name="fields[ean]" class="form-control">
                              <option value="width">Largura</option>
                              <option value="height">Altura</option>
                              <option value="length">Comprimento</option>
                              <option value="weight">Peso</option>
                              <option value="brand">Fabricante</option>
                              <option value="ean">EAN</option>
                              <option value="nbm">NBM</option>
                              <option value="ncm">NCM</option>
                              <option value="description">Descriçao</option>
                              <option value="isbn">ISBN</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">NBM:</label>
                          <div class="col-sm-10">
                            <select name="fields[nbm]" class="form-control">
                              <option value="width">Largura</option>
                              <option value="height">Altura</option>
                              <option value="length">Comprimento</option>
                              <option value="weight">Peso</option>
                              <option value="brand">Fabricante</option>
                              <option value="ean">EAN</option>
                              <option value="nbm">NBM</option>
                              <option value="ncm">NCM</option>
                              <option value="description">Descriçao</option>
                              <option value="isbn">ISBN</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">NCM:</label>
                          <div class="col-sm-10">
                            <select name="fields[ncm]" class="form-control">
                              <option value="width">Largura</option>
                              <option value="height">Altura</option>
                              <option value="length">Comprimento</option>
                              <option value="weight">Peso</option>
                              <option value="brand">Fabricante</option>
                              <option value="ean">EAN</option>
                              <option value="nbm">NBM</option>
                              <option value="ncm">NCM</option>
                              <option value="description">Descriçao</option>
                              <option value="isbn">ISBN</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Descriçao:</label>
                          <div class="col-sm-10">
                            <select name="fields[description]" class="form-control">
                              <option value="width">Largura</option>
                              <option value="height">Altura</option>
                              <option value="length">Comprimento</option>
                              <option value="weight">Peso</option>
                              <option value="brand">Fabricante</option>
                              <option value="ean">EAN</option>
                              <option value="nbm">NBM</option>
                              <option value="ncm">NCM</option>
                              <option value="description">Descriçao</option>
                              <option value="isbn">ISBN</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">ISBN:</label>
                          <div class="col-sm-10">
                            <select name="fields[isbn]" class="form-control">
                              <option value="width">Largura</option>
                              <option value="height">Altura</option>
                              <option value="length">Comprimento</option>
                              <option value="weight">Peso</option>
                              <option value="brand">Fabricante</option>
                              <option value="ean">EAN</option>
                              <option value="nbm">NBM</option>
                              <option value="ncm">NCM</option>
                              <option value="description">Descriçao</option>
                              <option value="isbn">ISBN</option>
                            </select>
                          </div>
                        </div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h3 class="panel-title">
                        <i class="fa fa-pencil"></i> Atrelamento de Status de Pagamento
                      </h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Pendentes:</label>
                          <div class="col-sm-10">
                            <select name="fields[payment_pendent]" class="form-control">
                              <option value="pending">Pendente</option>
                              <option value="processing">Processando</option>
                              <option value="complete">Completar</option>
                              <option value="closed">Encerrado</option>
                              <option value="canceled">Cancelado</option>
                              <option value="holded">On Hold</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Aprovados:</label>
                          <div class="col-sm-10">
                            <select name="fields[payment_approved]" class="form-control">
                              <option value="pending">Pendente</option>
                              <option value="processing">Processando</option>
                              <option value="complete">Completar</option>
                              <option value="closed">Encerrado</option>
                              <option value="canceled">Cancelado</option>
                              <option value="holded">On Hold</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Faturados:</label>
                          <div class="col-sm-10">
                            <select name="fields[payment_invoiced]" class="form-control">
                              <option value="pending">Pendente</option>
                              <option value="processing">Processando</option>
                              <option value="complete">Completar</option>
                              <option value="closed">Encerrado</option>
                              <option value="canceled">Cancelado</option>
                              <option value="holded">On Hold</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos a serem enviados:</label>
                          <div class="col-sm-10">
                            <select name="fields[payment_shipping]" class="form-control">
                              <option value="pending">Pendente</option>
                              <option value="processing">Processando</option>
                              <option value="complete">Completar</option>
                              <option value="closed">Encerrado</option>
                              <option value="canceled">Cancelado</option>
                              <option value="holded">On Hold</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para pagamentos enviados:</label>
                          <div class="col-sm-10">
                            <select name="fields[payment_delivered]" class="form-control">
                              <option value="pending">Pendente</option>
                              <option value="processing">Processando</option>
                              <option value="complete">Completar</option>
                              <option value="closed">Encerrado</option>
                              <option value="canceled">Cancelado</option>
                              <option value="holded">On Hold</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Cancelados:</label>
                          <div class="col-sm-10">
                            <select name="fields[payment_canceled]" class="form-control">
                              <option value="pending">Pendente</option>
                              <option value="processing">Processando</option>
                              <option value="complete">Completar</option>
                              <option value="closed">Encerrado</option>
                              <option value="canceled">Cancelado</option>
                              <option value="holded">On Hold</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para pagamento em Revisão:</label>
                          <div class="col-sm-10">
                            <select name="fields[payment_review]" class="form-control">
                              <option value="pending">Pendente</option>
                              <option value="processing">Processando</option>
                              <option value="complete">Completar</option>
                              <option value="closed">Encerrado</option>
                              <option value="canceled">Cancelado</option>
                              <option value="holded">On Hold</option>
                            </select>
                          </div>
                        </div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h3 class="panel-title">
                        <i class="fa fa-pencil"></i> Atrelamento de Metodos de Entrega
                      </h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Entrega padrão:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_default]" class="form-control">
                              <option value="flatrate">Fixed</option>
                              <option value="freeshipping">Free</option>
                              <option value="tablerate_bestway">Table Rate</option>
                              <option value="dhl_IE">International Express</option>
                              <option value="dhl_E SAT">Express Saturday</option>
                              <option value="dhl_E 10:30AM">Express 10:30 AM</option>
                              <option value="dhl_E">Express</option>
                              <option value="dhl_N">Next Afternoon</option>
                              <option value="dhl_S">Second Day Service</option>
                              <option value="dhl_G">Ground</option>
                              <option value="fedex_EUROPE_FIRST_INTERNATIONAL_PRIORITY">Europe First Priority</option>
                              <option value="fedex_FEDEX_1_DAY_FREIGHT">1 Day Freight</option>
                              <option value="fedex_FEDEX_2_DAY_FREIGHT">2 Day Freight</option>
                              <option value="fedex_FEDEX_2_DAY">2 Day</option>
                              <option value="fedex_FEDEX_2_DAY_AM">2 Day AM</option>
                              <option value="fedex_FEDEX_3_DAY_FREIGHT">3 Day Freight</option>
                              <option value="fedex_FEDEX_EXPRESS_SAVER">Express Saver</option>
                              <option value="fedex_FEDEX_GROUND">Ground</option>
                              <option value="fedex_FIRST_OVERNIGHT">First Overnight</option>
                              <option value="fedex_GROUND_HOME_DELIVERY">Home Delivery</option>
                              <option value="fedex_INTERNATIONAL_ECONOMY">International Economy</option>
                              <option value="fedex_INTERNATIONAL_ECONOMY_FREIGHT">Intl Economy Freight</option>
                              <option value="fedex_INTERNATIONAL_FIRST">International First</option>
                              <option value="fedex_INTERNATIONAL_GROUND">International Ground</option>
                              <option value="fedex_INTERNATIONAL_PRIORITY">International Priority</option>
                              <option value="fedex_INTERNATIONAL_PRIORITY_FREIGHT">Intl Priority Freight</option>
                              <option value="fedex_PRIORITY_OVERNIGHT">Priority Overnight</option>
                              <option value="fedex_SMART_POST">Smart Post</option>
                              <option value="fedex_STANDARD_OVERNIGHT">Standard Overnight</option>
                              <option value="fedex_FEDEX_FREIGHT">Freight</option>
                              <option value="fedex_FEDEX_NATIONAL_FREIGHT">National Freight</option>
                              <option value="ups_1DM">Next Day Air Early AM</option>
                              <option value="ups_1DML">Next Day Air Early AM Letter</option>
                              <option value="ups_1DA">Next Day Air</option>
                              <option value="ups_1DAL">Next Day Air Letter</option>
                              <option value="ups_1DAPI">Next Day Air Intra (Puerto Rico)</option>
                              <option value="ups_1DP">Next Day Air Saver</option>
                              <option value="ups_1DPL">Next Day Air Saver Letter</option>
                              <option value="ups_2DM">2nd Day Air AM</option>
                              <option value="ups_2DML">2nd Day Air AM Letter</option>
                              <option value="ups_2DA">2nd Day Air</option>
                              <option value="ups_2DAL">2nd Day Air Letter</option>
                              <option value="ups_3DS">3 Day Select</option>
                              <option value="ups_GND">Ground</option>
                              <option value="ups_GNDCOM">Ground Commercial</option>
                              <option value="ups_GNDRES">Ground Residential</option>
                              <option value="ups_STD">Canada Standard</option>
                              <option value="ups_XPR">Worldwide Express</option>
                              <option value="ups_WXS">Worldwide Express Saver</option>
                              <option value="ups_XPRL">Worldwide Express Letter</option>
                              <option value="ups_XDM">Worldwide Express Plus</option>
                              <option value="ups_XDML">Worldwide Express Plus Letter</option>
                              <option value="ups_XPD">Worldwide Expedited</option>
                              <option value="usps_0_FCLE">First-Class Mail Large Envelope</option>
                              <option value="usps_0_FCL">First-Class Mail Letter</option>
                              <option value="usps_0_FCP">First-Class Mail Parcel</option>
                              <option value="usps_1">Priority Mail</option>
                              <option value="usps_2">Priority Mail Express Hold For Pickup</option>
                              <option value="usps_3">Priority Mail Express</option>
                              <option value="usps_4">Standard Post</option>
                              <option value="usps_6">Media Mail Parcel</option>
                              <option value="usps_7">Library Mail Parcel</option>
                              <option value="usps_13">Priority Mail Express Flat Rate Envelope</option>
                              <option value="usps_16">Priority Mail Flat Rate Envelope</option>
                              <option value="usps_17">Priority Mail Medium Flat Rate Box</option>
                              <option value="usps_22">Priority Mail Large Flat Rate Box</option>
                              <option value="usps_23">Priority Mail Express Sunday/Holiday Delivery</option>
                              <option value="usps_25">Priority Mail Express Sunday/Holiday Delivery Flat Rate Envelope</option>
                              <option value="usps_27">Priority Mail Express Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_28">Priority Mail Small Flat Rate Box</option>
                              <option value="usps_33">Priority Mail Hold For Pickup</option>
                              <option value="usps_34">Priority Mail Large Flat Rate Box Hold For Pickup</option>
                              <option value="usps_35">Priority Mail Medium Flat Rate Box Hold For Pickup</option>
                              <option value="usps_36">Priority Mail Small Flat Rate Box Hold For Pickup</option>
                              <option value="usps_37">Priority Mail Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_42">Priority Mail Small Flat Rate Envelope</option>
                              <option value="usps_43">Priority Mail Small Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_53">First-Class Package Service Hold For Pickup</option>
                              <option value="usps_55">Priority Mail Express Flat Rate Boxes</option>
                              <option value="usps_56">Priority Mail Express Flat Rate Boxes Hold For Pickup</option>
                              <option value="usps_57">Priority Mail Express Sunday/Holiday Delivery Flat Rate Boxes
                              </option>
                              <option value="usps_61">First-Class Package Service</option>
                              <option value="usps_INT_1">Priority Mail Express International</option>
                              <option value="usps_INT_2">Priority Mail International</option>
                              <option value="usps_INT_4">Global Express Guaranteed (GXG)</option>
                              <option value="usps_INT_6">Global Express Guaranteed Non-Document Rectangular</option>
                              <option value="usps_INT_7">Global Express Guaranteed Non-Document Non-Rectangular
                              </option>
                              <option value="usps_INT_8">Priority Mail International Flat Rate Envelope</option>
                              <option value="usps_INT_9">Priority Mail International Medium Flat Rate Box</option>
                              <option value="usps_INT_10">Priority Mail Express International Flat Rate Envelope
                              </option>
                              <option value="usps_INT_11">Priority Mail International Large Flat Rate Box</option>
                              <option value="usps_INT_12">USPS GXG Envelopes</option>
                              <option value="usps_INT_13">First-Class Mail International Letter</option>
                              <option value="usps_INT_14">First-Class Mail International Large Envelope</option>
                              <option value="usps_INT_15">First-Class Package International Service</option>
                              <option value="usps_INT_16">Priority Mail International Small Flat Rate Box</option>
                              <option value="usps_INT_20">Priority Mail International Small Flat Rate Envelope</option>
                              <option value="usps_INT_26">Priority Mail Express International Flat Rate Boxes</option>
                              <option value="dhlint_2">Easy shop</option>
                              <option value="dhlint_5">Sprintline</option>
                              <option value="dhlint_6">Secureline</option>
                              <option value="dhlint_7">Express easy</option>
                              <option value="dhlint_9">Europack</option>
                              <option value="dhlint_B">Break bulk express</option>
                              <option value="dhlint_C">Medical express</option>
                              <option value="dhlint_D">Express worldwide</option>
                              <option value="dhlint_U">Express worldwide</option>
                              <option value="dhlint_K">Express 9:00</option>
                              <option value="dhlint_L">Express 10:30</option>
                              <option value="dhlint_G">Domestic economy select</option>
                              <option value="dhlint_W">Economy select</option>
                              <option value="dhlint_I">Break bulk economy</option>
                              <option value="dhlint_N">Domestic express</option>
                              <option value="dhlint_O">Others</option>
                              <option value="dhlint_R">Globalmail business</option>
                              <option value="dhlint_S">Same day</option>
                              <option value="dhlint_T">Express 12:00</option>
                              <option value="dhlint_X">Express envelope</option>
                              <option value="pedroteixeira_correios">Correios</option>
                              <option value="mercadolivre">Meli Envios</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Entrega Expressa:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_express]" class="form-control">
                              <option value="flatrate">Fixed</option>
                              <option value="freeshipping">Free</option>
                              <option value="tablerate_bestway">Table Rate</option>
                              <option value="dhl_IE">International Express</option>
                              <option value="dhl_E SAT">Express Saturday</option>
                              <option value="dhl_E 10:30AM">Express 10:30 AM</option>
                              <option value="dhl_E">Express</option>
                              <option value="dhl_N">Next Afternoon</option>
                              <option value="dhl_S">Second Day Service</option>
                              <option value="dhl_G">Ground</option>
                              <option value="fedex_EUROPE_FIRST_INTERNATIONAL_PRIORITY">Europe First Priority</option>
                              <option value="fedex_FEDEX_1_DAY_FREIGHT">1 Day Freight</option>
                              <option value="fedex_FEDEX_2_DAY_FREIGHT">2 Day Freight</option>
                              <option value="fedex_FEDEX_2_DAY">2 Day</option>
                              <option value="fedex_FEDEX_2_DAY_AM">2 Day AM</option>
                              <option value="fedex_FEDEX_3_DAY_FREIGHT">3 Day Freight</option>
                              <option value="fedex_FEDEX_EXPRESS_SAVER">Express Saver</option>
                              <option value="fedex_FEDEX_GROUND">Ground</option>
                              <option value="fedex_FIRST_OVERNIGHT">First Overnight</option>
                              <option value="fedex_GROUND_HOME_DELIVERY">Home Delivery</option>
                              <option value="fedex_INTERNATIONAL_ECONOMY">International Economy</option>
                              <option value="fedex_INTERNATIONAL_ECONOMY_FREIGHT">Intl Economy Freight</option>
                              <option value="fedex_INTERNATIONAL_FIRST">International First</option>
                              <option value="fedex_INTERNATIONAL_GROUND">International Ground</option>
                              <option value="fedex_INTERNATIONAL_PRIORITY">International Priority</option>
                              <option value="fedex_INTERNATIONAL_PRIORITY_FREIGHT">Intl Priority Freight</option>
                              <option value="fedex_PRIORITY_OVERNIGHT">Priority Overnight</option>
                              <option value="fedex_SMART_POST">Smart Post</option>
                              <option value="fedex_STANDARD_OVERNIGHT">Standard Overnight</option>
                              <option value="fedex_FEDEX_FREIGHT">Freight</option>
                              <option value="fedex_FEDEX_NATIONAL_FREIGHT">National Freight</option>
                              <option value="ups_1DM">Next Day Air Early AM</option>
                              <option value="ups_1DML">Next Day Air Early AM Letter</option>
                              <option value="ups_1DA">Next Day Air</option>
                              <option value="ups_1DAL">Next Day Air Letter</option>
                              <option value="ups_1DAPI">Next Day Air Intra (Puerto Rico)</option>
                              <option value="ups_1DP">Next Day Air Saver</option>
                              <option value="ups_1DPL">Next Day Air Saver Letter</option>
                              <option value="ups_2DM">2nd Day Air AM</option>
                              <option value="ups_2DML">2nd Day Air AM Letter</option>
                              <option value="ups_2DA">2nd Day Air</option>
                              <option value="ups_2DAL">2nd Day Air Letter</option>
                              <option value="ups_3DS">3 Day Select</option>
                              <option value="ups_GND">Ground</option>
                              <option value="ups_GNDCOM">Ground Commercial</option>
                              <option value="ups_GNDRES">Ground Residential</option>
                              <option value="ups_STD">Canada Standard</option>
                              <option value="ups_XPR">Worldwide Express</option>
                              <option value="ups_WXS">Worldwide Express Saver</option>
                              <option value="ups_XPRL">Worldwide Express Letter</option>
                              <option value="ups_XDM">Worldwide Express Plus</option>
                              <option value="ups_XDML">Worldwide Express Plus Letter</option>
                              <option value="ups_XPD">Worldwide Expedited</option>
                              <option value="usps_0_FCLE">First-Class Mail Large Envelope</option>
                              <option value="usps_0_FCL">First-Class Mail Letter</option>
                              <option value="usps_0_FCP">First-Class Mail Parcel</option>
                              <option value="usps_1">Priority Mail</option>
                              <option value="usps_2">Priority Mail Express Hold For Pickup</option>
                              <option value="usps_3">Priority Mail Express</option>
                              <option value="usps_4">Standard Post</option>
                              <option value="usps_6">Media Mail Parcel</option>
                              <option value="usps_7">Library Mail Parcel</option>
                              <option value="usps_13">Priority Mail Express Flat Rate Envelope</option>
                              <option value="usps_16">Priority Mail Flat Rate Envelope</option>
                              <option value="usps_17">Priority Mail Medium Flat Rate Box</option>
                              <option value="usps_22">Priority Mail Large Flat Rate Box</option>
                              <option value="usps_23">Priority Mail Express Sunday/Holiday Delivery</option>
                              <option value="usps_25">Priority Mail Express Sunday/Holiday Delivery Flat Rate Envelope</option>
                              <option value="usps_27">Priority Mail Express Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_28">Priority Mail Small Flat Rate Box</option>
                              <option value="usps_33">Priority Mail Hold For Pickup</option>
                              <option value="usps_34">Priority Mail Large Flat Rate Box Hold For Pickup</option>
                              <option value="usps_35">Priority Mail Medium Flat Rate Box Hold For Pickup</option>
                              <option value="usps_36">Priority Mail Small Flat Rate Box Hold For Pickup</option>
                              <option value="usps_37">Priority Mail Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_42">Priority Mail Small Flat Rate Envelope</option>
                              <option value="usps_43">Priority Mail Small Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_53">First-Class Package Service Hold For Pickup</option>
                              <option value="usps_55">Priority Mail Express Flat Rate Boxes</option>
                              <option value="usps_56">Priority Mail Express Flat Rate Boxes Hold For Pickup</option>
                              <option value="usps_57">Priority Mail Express Sunday/Holiday Delivery Flat Rate Boxes
                              </option>
                              <option value="usps_61">First-Class Package Service</option>
                              <option value="usps_INT_1">Priority Mail Express International</option>
                              <option value="usps_INT_2">Priority Mail International</option>
                              <option value="usps_INT_4">Global Express Guaranteed (GXG)</option>
                              <option value="usps_INT_6">Global Express Guaranteed Non-Document Rectangular</option>
                              <option value="usps_INT_7">Global Express Guaranteed Non-Document Non-Rectangular
                              </option>
                              <option value="usps_INT_8">Priority Mail International Flat Rate Envelope</option>
                              <option value="usps_INT_9">Priority Mail International Medium Flat Rate Box</option>
                              <option value="usps_INT_10">Priority Mail Express International Flat Rate Envelope
                              </option>
                              <option value="usps_INT_11">Priority Mail International Large Flat Rate Box</option>
                              <option value="usps_INT_12">USPS GXG Envelopes</option>
                              <option value="usps_INT_13">First-Class Mail International Letter</option>
                              <option value="usps_INT_14">First-Class Mail International Large Envelope</option>
                              <option value="usps_INT_15">First-Class Package International Service</option>
                              <option value="usps_INT_16">Priority Mail International Small Flat Rate Box</option>
                              <option value="usps_INT_20">Priority Mail International Small Flat Rate Envelope</option>
                              <option value="usps_INT_26">Priority Mail Express International Flat Rate Boxes</option>
                              <option value="dhlint_2">Easy shop</option>
                              <option value="dhlint_5">Sprintline</option>
                              <option value="dhlint_6">Secureline</option>
                              <option value="dhlint_7">Express easy</option>
                              <option value="dhlint_9">Europack</option>
                              <option value="dhlint_B">Break bulk express</option>
                              <option value="dhlint_C">Medical express</option>
                              <option value="dhlint_D">Express worldwide</option>
                              <option value="dhlint_U">Express worldwide</option>
                              <option value="dhlint_K">Express 9:00</option>
                              <option value="dhlint_L">Express 10:30</option>
                              <option value="dhlint_G">Domestic economy select</option>
                              <option value="dhlint_W">Economy select</option>
                              <option value="dhlint_I">Break bulk economy</option>
                              <option value="dhlint_N">Domestic express</option>
                              <option value="dhlint_O">Others</option>
                              <option value="dhlint_R">Globalmail business</option>
                              <option value="dhlint_S">Same day</option>
                              <option value="dhlint_T">Express 12:00</option>
                              <option value="dhlint_X">Express envelope</option>
                              <option value="pedroteixeira_correios">Correios</option>
                              <option value="mercadolivre">Meli Envios</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Entrega Economica:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_economic]" class="form-control">
                              <option value="flatrate">Fixed</option>
                              <option value="freeshipping">Free</option>
                              <option value="tablerate_bestway">Table Rate</option>
                              <option value="dhl_IE">International Express</option>
                              <option value="dhl_E SAT">Express Saturday</option>
                              <option value="dhl_E 10:30AM">Express 10:30 AM</option>
                              <option value="dhl_E">Express</option>
                              <option value="dhl_N">Next Afternoon</option>
                              <option value="dhl_S">Second Day Service</option>
                              <option value="dhl_G">Ground</option>
                              <option value="fedex_EUROPE_FIRST_INTERNATIONAL_PRIORITY">Europe First Priority</option>
                              <option value="fedex_FEDEX_1_DAY_FREIGHT">1 Day Freight</option>
                              <option value="fedex_FEDEX_2_DAY_FREIGHT">2 Day Freight</option>
                              <option value="fedex_FEDEX_2_DAY">2 Day</option>
                              <option value="fedex_FEDEX_2_DAY_AM">2 Day AM</option>
                              <option value="fedex_FEDEX_3_DAY_FREIGHT">3 Day Freight</option>
                              <option value="fedex_FEDEX_EXPRESS_SAVER">Express Saver</option>
                              <option value="fedex_FEDEX_GROUND">Ground</option>
                              <option value="fedex_FIRST_OVERNIGHT">First Overnight</option>
                              <option value="fedex_GROUND_HOME_DELIVERY">Home Delivery</option>
                              <option value="fedex_INTERNATIONAL_ECONOMY">International Economy</option>
                              <option value="fedex_INTERNATIONAL_ECONOMY_FREIGHT">Intl Economy Freight</option>
                              <option value="fedex_INTERNATIONAL_FIRST">International First</option>
                              <option value="fedex_INTERNATIONAL_GROUND">International Ground</option>
                              <option value="fedex_INTERNATIONAL_PRIORITY">International Priority</option>
                              <option value="fedex_INTERNATIONAL_PRIORITY_FREIGHT">Intl Priority Freight</option>
                              <option value="fedex_PRIORITY_OVERNIGHT">Priority Overnight</option>
                              <option value="fedex_SMART_POST">Smart Post</option>
                              <option value="fedex_STANDARD_OVERNIGHT">Standard Overnight</option>
                              <option value="fedex_FEDEX_FREIGHT">Freight</option>
                              <option value="fedex_FEDEX_NATIONAL_FREIGHT">National Freight</option>
                              <option value="ups_1DM">Next Day Air Early AM</option>
                              <option value="ups_1DML">Next Day Air Early AM Letter</option>
                              <option value="ups_1DA">Next Day Air</option>
                              <option value="ups_1DAL">Next Day Air Letter</option>
                              <option value="ups_1DAPI">Next Day Air Intra (Puerto Rico)</option>
                              <option value="ups_1DP">Next Day Air Saver</option>
                              <option value="ups_1DPL">Next Day Air Saver Letter</option>
                              <option value="ups_2DM">2nd Day Air AM</option>
                              <option value="ups_2DML">2nd Day Air AM Letter</option>
                              <option value="ups_2DA">2nd Day Air</option>
                              <option value="ups_2DAL">2nd Day Air Letter</option>
                              <option value="ups_3DS">3 Day Select</option>
                              <option value="ups_GND">Ground</option>
                              <option value="ups_GNDCOM">Ground Commercial</option>
                              <option value="ups_GNDRES">Ground Residential</option>
                              <option value="ups_STD">Canada Standard</option>
                              <option value="ups_XPR">Worldwide Express</option>
                              <option value="ups_WXS">Worldwide Express Saver</option>
                              <option value="ups_XPRL">Worldwide Express Letter</option>
                              <option value="ups_XDM">Worldwide Express Plus</option>
                              <option value="ups_XDML">Worldwide Express Plus Letter</option>
                              <option value="ups_XPD">Worldwide Expedited</option>
                              <option value="usps_0_FCLE">First-Class Mail Large Envelope</option>
                              <option value="usps_0_FCL">First-Class Mail Letter</option>
                              <option value="usps_0_FCP">First-Class Mail Parcel</option>
                              <option value="usps_1">Priority Mail</option>
                              <option value="usps_2">Priority Mail Express Hold For Pickup</option>
                              <option value="usps_3">Priority Mail Express</option>
                              <option value="usps_4">Standard Post</option>
                              <option value="usps_6">Media Mail Parcel</option>
                              <option value="usps_7">Library Mail Parcel</option>
                              <option value="usps_13">Priority Mail Express Flat Rate Envelope</option>
                              <option value="usps_16">Priority Mail Flat Rate Envelope</option>
                              <option value="usps_17">Priority Mail Medium Flat Rate Box</option>
                              <option value="usps_22">Priority Mail Large Flat Rate Box</option>
                              <option value="usps_23">Priority Mail Express Sunday/Holiday Delivery</option>
                              <option value="usps_25">Priority Mail Express Sunday/Holiday Delivery Flat Rate Envelope</option>
                              <option value="usps_27">Priority Mail Express Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_28">Priority Mail Small Flat Rate Box</option>
                              <option value="usps_33">Priority Mail Hold For Pickup</option>
                              <option value="usps_34">Priority Mail Large Flat Rate Box Hold For Pickup</option>
                              <option value="usps_35">Priority Mail Medium Flat Rate Box Hold For Pickup</option>
                              <option value="usps_36">Priority Mail Small Flat Rate Box Hold For Pickup</option>
                              <option value="usps_37">Priority Mail Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_42">Priority Mail Small Flat Rate Envelope</option>
                              <option value="usps_43">Priority Mail Small Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_53">First-Class Package Service Hold For Pickup</option>
                              <option value="usps_55">Priority Mail Express Flat Rate Boxes</option>
                              <option value="usps_56">Priority Mail Express Flat Rate Boxes Hold For Pickup</option>
                              <option value="usps_57">Priority Mail Express Sunday/Holiday Delivery Flat Rate Boxes</option>
                              <option value="usps_61">First-Class Package Service</option>
                              <option value="usps_INT_1">Priority Mail Express International</option>
                              <option value="usps_INT_2">Priority Mail International</option>
                              <option value="usps_INT_4">Global Express Guaranteed (GXG)</option>
                              <option value="usps_INT_6">Global Express Guaranteed Non-Document Rectangular</option>
                              <option value="usps_INT_7">Global Express Guaranteed Non-Document Non-Rectangular
                              </option>
                              <option value="usps_INT_8">Priority Mail International Flat Rate Envelope</option>
                              <option value="usps_INT_9">Priority Mail International Medium Flat Rate Box</option>
                              <option value="usps_INT_10">Priority Mail Express International Flat Rate Envelope
                              </option>
                              <option value="usps_INT_11">Priority Mail International Large Flat Rate Box</option>
                              <option value="usps_INT_12">USPS GXG Envelopes</option>
                              <option value="usps_INT_13">First-Class Mail International Letter</option>
                              <option value="usps_INT_14">First-Class Mail International Large Envelope</option>
                              <option value="usps_INT_15">First-Class Package International Service</option>
                              <option value="usps_INT_16">Priority Mail International Small Flat Rate Box</option>
                              <option value="usps_INT_20">Priority Mail International Small Flat Rate Envelope</option>
                              <option value="usps_INT_26">Priority Mail Express International Flat Rate Boxes</option>
                              <option value="dhlint_2">Easy shop</option>
                              <option value="dhlint_5">Sprintline</option>
                              <option value="dhlint_6">Secureline</option>
                              <option value="dhlint_7">Express easy</option>
                              <option value="dhlint_9">Europack</option>
                              <option value="dhlint_B">Break bulk express</option>
                              <option value="dhlint_C">Medical express</option>
                              <option value="dhlint_D">Express worldwide</option>
                              <option value="dhlint_U">Express worldwide</option>
                              <option value="dhlint_K">Express 9:00</option>
                              <option value="dhlint_L">Express 10:30</option>
                              <option value="dhlint_G">Domestic economy select</option>
                              <option value="dhlint_W">Economy select</option>
                              <option value="dhlint_I">Break bulk economy</option>
                              <option value="dhlint_N">Domestic express</option>
                              <option value="dhlint_O">Others</option>
                              <option value="dhlint_R">Globalmail business</option>
                              <option value="dhlint_S">Same day</option>
                              <option value="dhlint_T">Express 12:00</option>
                              <option value="dhlint_X">Express envelope</option>
                              <option value="pedroteixeira_correios">Correios</option>
                              <option value="mercadolivre">Meli Envios</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Entrega Agendada:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_scheduled]" class="form-control">
                              <option value="flatrate">Fixed</option>
                              <option value="freeshipping">Free</option>
                              <option value="tablerate_bestway">Table Rate</option>
                              <option value="dhl_IE">International Express</option>
                              <option value="dhl_E SAT">Express Saturday</option>
                              <option value="dhl_E 10:30AM">Express 10:30 AM</option>
                              <option value="dhl_E">Express</option>
                              <option value="dhl_N">Next Afternoon</option>
                              <option value="dhl_S">Second Day Service</option>
                              <option value="dhl_G">Ground</option>
                              <option value="fedex_EUROPE_FIRST_INTERNATIONAL_PRIORITY">Europe First Priority</option>
                              <option value="fedex_FEDEX_1_DAY_FREIGHT">1 Day Freight</option>
                              <option value="fedex_FEDEX_2_DAY_FREIGHT">2 Day Freight</option>
                              <option value="fedex_FEDEX_2_DAY">2 Day</option>
                              <option value="fedex_FEDEX_2_DAY_AM">2 Day AM</option>
                              <option value="fedex_FEDEX_3_DAY_FREIGHT">3 Day Freight</option>
                              <option value="fedex_FEDEX_EXPRESS_SAVER">Express Saver</option>
                              <option value="fedex_FEDEX_GROUND">Ground</option>
                              <option value="fedex_FIRST_OVERNIGHT">First Overnight</option>
                              <option value="fedex_GROUND_HOME_DELIVERY">Home Delivery</option>
                              <option value="fedex_INTERNATIONAL_ECONOMY">International Economy</option>
                              <option value="fedex_INTERNATIONAL_ECONOMY_FREIGHT">Intl Economy Freight</option>
                              <option value="fedex_INTERNATIONAL_FIRST">International First</option>
                              <option value="fedex_INTERNATIONAL_GROUND">International Ground</option>
                              <option value="fedex_INTERNATIONAL_PRIORITY">International Priority</option>
                              <option value="fedex_INTERNATIONAL_PRIORITY_FREIGHT">Intl Priority Freight</option>
                              <option value="fedex_PRIORITY_OVERNIGHT">Priority Overnight</option>
                              <option value="fedex_SMART_POST">Smart Post</option>
                              <option value="fedex_STANDARD_OVERNIGHT">Standard Overnight</option>
                              <option value="fedex_FEDEX_FREIGHT">Freight</option>
                              <option value="fedex_FEDEX_NATIONAL_FREIGHT">National Freight</option>
                              <option value="ups_1DM">Next Day Air Early AM</option>
                              <option value="ups_1DML">Next Day Air Early AM Letter</option>
                              <option value="ups_1DA">Next Day Air</option>
                              <option value="ups_1DAL">Next Day Air Letter</option>
                              <option value="ups_1DAPI">Next Day Air Intra (Puerto Rico)</option>
                              <option value="ups_1DP">Next Day Air Saver</option>
                              <option value="ups_1DPL">Next Day Air Saver Letter</option>
                              <option value="ups_2DM">2nd Day Air AM</option>
                              <option value="ups_2DML">2nd Day Air AM Letter</option>
                              <option value="ups_2DA">2nd Day Air</option>
                              <option value="ups_2DAL">2nd Day Air Letter</option>
                              <option value="ups_3DS">3 Day Select</option>
                              <option value="ups_GND">Ground</option>
                              <option value="ups_GNDCOM">Ground Commercial</option>
                              <option value="ups_GNDRES">Ground Residential</option>
                              <option value="ups_STD">Canada Standard</option>
                              <option value="ups_XPR">Worldwide Express</option>
                              <option value="ups_WXS">Worldwide Express Saver</option>
                              <option value="ups_XPRL">Worldwide Express Letter</option>
                              <option value="ups_XDM">Worldwide Express Plus</option>
                              <option value="ups_XDML">Worldwide Express Plus Letter</option>
                              <option value="ups_XPD">Worldwide Expedited</option>
                              <option value="usps_0_FCLE">First-Class Mail Large Envelope</option>
                              <option value="usps_0_FCL">First-Class Mail Letter</option>
                              <option value="usps_0_FCP">First-Class Mail Parcel</option>
                              <option value="usps_1">Priority Mail</option>
                              <option value="usps_2">Priority Mail Express Hold For Pickup</option>
                              <option value="usps_3">Priority Mail Express</option>
                              <option value="usps_4">Standard Post</option>
                              <option value="usps_6">Media Mail Parcel</option>
                              <option value="usps_7">Library Mail Parcel</option>
                              <option value="usps_13">Priority Mail Express Flat Rate Envelope</option>
                              <option value="usps_16">Priority Mail Flat Rate Envelope</option>
                              <option value="usps_17">Priority Mail Medium Flat Rate Box</option>
                              <option value="usps_22">Priority Mail Large Flat Rate Box</option>
                              <option value="usps_23">Priority Mail Express Sunday/Holiday Delivery</option>
                              <option value="usps_25">Priority Mail Express Sunday/Holiday Delivery Flat Rate Envelope</option>
                              <option value="usps_27">Priority Mail Express Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_28">Priority Mail Small Flat Rate Box</option>
                              <option value="usps_33">Priority Mail Hold For Pickup</option>
                              <option value="usps_34">Priority Mail Large Flat Rate Box Hold For Pickup</option>
                              <option value="usps_35">Priority Mail Medium Flat Rate Box Hold For Pickup</option>
                              <option value="usps_36">Priority Mail Small Flat Rate Box Hold For Pickup</option>
                              <option value="usps_37">Priority Mail Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_42">Priority Mail Small Flat Rate Envelope</option>
                              <option value="usps_43">Priority Mail Small Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_53">First-Class Package Service Hold For Pickup</option>
                              <option value="usps_55">Priority Mail Express Flat Rate Boxes</option>
                              <option value="usps_56">Priority Mail Express Flat Rate Boxes Hold For Pickup</option>
                              <option value="usps_57">Priority Mail Express Sunday/Holiday Delivery Flat Rate Boxes
                              </option>
                              <option value="usps_61">First-Class Package Service</option>
                              <option value="usps_INT_1">Priority Mail Express International</option>
                              <option value="usps_INT_2">Priority Mail International</option>
                              <option value="usps_INT_4">Global Express Guaranteed (GXG)</option>
                              <option value="usps_INT_6">Global Express Guaranteed Non-Document Rectangular</option>
                              <option value="usps_INT_7">Global Express Guaranteed Non-Document Non-Rectangular
                              </option>
                              <option value="usps_INT_8">Priority Mail International Flat Rate Envelope</option>
                              <option value="usps_INT_9">Priority Mail International Medium Flat Rate Box</option>
                              <option value="usps_INT_10">Priority Mail Express International Flat Rate Envelope
                              </option>
                              <option value="usps_INT_11">Priority Mail International Large Flat Rate Box</option>
                              <option value="usps_INT_12">USPS GXG Envelopes</option>
                              <option value="usps_INT_13">First-Class Mail International Letter</option>
                              <option value="usps_INT_14">First-Class Mail International Large Envelope</option>
                              <option value="usps_INT_15">First-Class Package International Service</option>
                              <option value="usps_INT_16">Priority Mail International Small Flat Rate Box</option>
                              <option value="usps_INT_20">Priority Mail International Small Flat Rate Envelope</option>
                              <option value="usps_INT_26">Priority Mail Express International Flat Rate Boxes</option>
                              <option value="dhlint_2">Easy shop</option>
                              <option value="dhlint_5">Sprintline</option>
                              <option value="dhlint_6">Secureline</option>
                              <option value="dhlint_7">Express easy</option>
                              <option value="dhlint_9">Europack</option>
                              <option value="dhlint_B">Break bulk express</option>
                              <option value="dhlint_C">Medical express</option>
                              <option value="dhlint_D">Express worldwide</option>
                              <option value="dhlint_U">Express worldwide</option>
                              <option value="dhlint_K">Express 9:00</option>
                              <option value="dhlint_L">Express 10:30</option>
                              <option value="dhlint_G">Domestic economy select</option>
                              <option value="dhlint_W">Economy select</option>
                              <option value="dhlint_I">Break bulk economy</option>
                              <option value="dhlint_N">Domestic express</option>
                              <option value="dhlint_O">Others</option>
                              <option value="dhlint_R">Globalmail business</option>
                              <option value="dhlint_S">Same day</option>
                              <option value="dhlint_T">Express 12:00</option>
                              <option value="dhlint_X">Express envelope</option>
                              <option value="pedroteixeira_correios">Correios</option>
                              <option value="mercadolivre">Meli Envios</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Retirar na loja:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_shop]" class="form-control">
                              <option value="flatrate">Fixed</option>
                              <option value="freeshipping">Free</option>
                              <option value="tablerate_bestway">Table Rate</option>
                              <option value="dhl_IE">International Express</option>
                              <option value="dhl_E SAT">Express Saturday</option>
                              <option value="dhl_E 10:30AM">Express 10:30 AM</option>
                              <option value="dhl_E">Express</option>
                              <option value="dhl_N">Next Afternoon</option>
                              <option value="dhl_S">Second Day Service</option>
                              <option value="dhl_G">Ground</option>
                              <option value="fedex_EUROPE_FIRST_INTERNATIONAL_PRIORITY">Europe First Priority</option>
                              <option value="fedex_FEDEX_1_DAY_FREIGHT">1 Day Freight</option>
                              <option value="fedex_FEDEX_2_DAY_FREIGHT">2 Day Freight</option>
                              <option value="fedex_FEDEX_2_DAY">2 Day</option>
                              <option value="fedex_FEDEX_2_DAY_AM">2 Day AM</option>
                              <option value="fedex_FEDEX_3_DAY_FREIGHT">3 Day Freight</option>
                              <option value="fedex_FEDEX_EXPRESS_SAVER">Express Saver</option>
                              <option value="fedex_FEDEX_GROUND">Ground</option>
                              <option value="fedex_FIRST_OVERNIGHT">First Overnight</option>
                              <option value="fedex_GROUND_HOME_DELIVERY">Home Delivery</option>
                              <option value="fedex_INTERNATIONAL_ECONOMY">International Economy</option>
                              <option value="fedex_INTERNATIONAL_ECONOMY_FREIGHT">Intl Economy Freight</option>
                              <option value="fedex_INTERNATIONAL_FIRST">International First</option>
                              <option value="fedex_INTERNATIONAL_GROUND">International Ground</option>
                              <option value="fedex_INTERNATIONAL_PRIORITY">International Priority</option>
                              <option value="fedex_INTERNATIONAL_PRIORITY_FREIGHT">Intl Priority Freight</option>
                              <option value="fedex_PRIORITY_OVERNIGHT">Priority Overnight</option>
                              <option value="fedex_SMART_POST">Smart Post</option>
                              <option value="fedex_STANDARD_OVERNIGHT">Standard Overnight</option>
                              <option value="fedex_FEDEX_FREIGHT">Freight</option>
                              <option value="fedex_FEDEX_NATIONAL_FREIGHT">National Freight</option>
                              <option value="ups_1DM">Next Day Air Early AM</option>
                              <option value="ups_1DML">Next Day Air Early AM Letter</option>
                              <option value="ups_1DA">Next Day Air</option>
                              <option value="ups_1DAL">Next Day Air Letter</option>
                              <option value="ups_1DAPI">Next Day Air Intra (Puerto Rico)</option>
                              <option value="ups_1DP">Next Day Air Saver</option>
                              <option value="ups_1DPL">Next Day Air Saver Letter</option>
                              <option value="ups_2DM">2nd Day Air AM</option>
                              <option value="ups_2DML">2nd Day Air AM Letter</option>
                              <option value="ups_2DA">2nd Day Air</option>
                              <option value="ups_2DAL">2nd Day Air Letter</option>
                              <option value="ups_3DS">3 Day Select</option>
                              <option value="ups_GND">Ground</option>
                              <option value="ups_GNDCOM">Ground Commercial</option>
                              <option value="ups_GNDRES">Ground Residential</option>
                              <option value="ups_STD">Canada Standard</option>
                              <option value="ups_XPR">Worldwide Express</option>
                              <option value="ups_WXS">Worldwide Express Saver</option>
                              <option value="ups_XPRL">Worldwide Express Letter</option>
                              <option value="ups_XDM">Worldwide Express Plus</option>
                              <option value="ups_XDML">Worldwide Express Plus Letter</option>
                              <option value="ups_XPD">Worldwide Expedited</option>
                              <option value="usps_0_FCLE">First-Class Mail Large Envelope</option>
                              <option value="usps_0_FCL">First-Class Mail Letter</option>
                              <option value="usps_0_FCP">First-Class Mail Parcel</option>
                              <option value="usps_1">Priority Mail</option>
                              <option value="usps_2">Priority Mail Express Hold For Pickup</option>
                              <option value="usps_3">Priority Mail Express</option>
                              <option value="usps_4">Standard Post</option>
                              <option value="usps_6">Media Mail Parcel</option>
                              <option value="usps_7">Library Mail Parcel</option>
                              <option value="usps_13">Priority Mail Express Flat Rate Envelope</option>
                              <option value="usps_16">Priority Mail Flat Rate Envelope</option>
                              <option value="usps_17">Priority Mail Medium Flat Rate Box</option>
                              <option value="usps_22">Priority Mail Large Flat Rate Box</option>
                              <option value="usps_23">Priority Mail Express Sunday/Holiday Delivery</option>
                              <option value="usps_25">Priority Mail Express Sunday/Holiday Delivery Flat Rate Envelope</option>
                              <option value="usps_27">Priority Mail Express Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_28">Priority Mail Small Flat Rate Box</option>
                              <option value="usps_33">Priority Mail Hold For Pickup</option>
                              <option value="usps_34">Priority Mail Large Flat Rate Box Hold For Pickup</option>
                              <option value="usps_35">Priority Mail Medium Flat Rate Box Hold For Pickup</option>
                              <option value="usps_36">Priority Mail Small Flat Rate Box Hold For Pickup</option>
                              <option value="usps_37">Priority Mail Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_42">Priority Mail Small Flat Rate Envelope</option>
                              <option value="usps_43">Priority Mail Small Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_53">First-Class Package Service Hold For Pickup</option>
                              <option value="usps_55">Priority Mail Express Flat Rate Boxes</option>
                              <option value="usps_56">Priority Mail Express Flat Rate Boxes Hold For Pickup</option>
                              <option value="usps_57">Priority Mail Express Sunday/Holiday Delivery Flat Rate Boxes
                              </option>
                              <option value="usps_61">First-Class Package Service</option>
                              <option value="usps_INT_1">Priority Mail Express International</option>
                              <option value="usps_INT_2">Priority Mail International</option>
                              <option value="usps_INT_4">Global Express Guaranteed (GXG)</option>
                              <option value="usps_INT_6">Global Express Guaranteed Non-Document Rectangular</option>
                              <option value="usps_INT_7">Global Express Guaranteed Non-Document Non-Rectangular
                              </option>
                              <option value="usps_INT_8">Priority Mail International Flat Rate Envelope</option>
                              <option value="usps_INT_9">Priority Mail International Medium Flat Rate Box</option>
                              <option value="usps_INT_10">Priority Mail Express International Flat Rate Envelope
                              </option>
                              <option value="usps_INT_11">Priority Mail International Large Flat Rate Box</option>
                              <option value="usps_INT_12">USPS GXG Envelopes</option>
                              <option value="usps_INT_13">First-Class Mail International Letter</option>
                              <option value="usps_INT_14">First-Class Mail International Large Envelope</option>
                              <option value="usps_INT_15">First-Class Package International Service</option>
                              <option value="usps_INT_16">Priority Mail International Small Flat Rate Box</option>
                              <option value="usps_INT_20">Priority Mail International Small Flat Rate Envelope</option>
                              <option value="usps_INT_26">Priority Mail Express International Flat Rate Boxes</option>
                              <option value="dhlint_2">Easy shop</option>
                              <option value="dhlint_5">Sprintline</option>
                              <option value="dhlint_6">Secureline</option>
                              <option value="dhlint_7">Express easy</option>
                              <option value="dhlint_9">Europack</option>
                              <option value="dhlint_B">Break bulk express</option>
                              <option value="dhlint_C">Medical express</option>
                              <option value="dhlint_D">Express worldwide</option>
                              <option value="dhlint_U">Express worldwide</option>
                              <option value="dhlint_K">Express 9:00</option>
                              <option value="dhlint_L">Express 10:30</option>
                              <option value="dhlint_G">Domestic economy select</option>
                              <option value="dhlint_W">Economy select</option>
                              <option value="dhlint_I">Break bulk economy</option>
                              <option value="dhlint_N">Domestic express</option>
                              <option value="dhlint_O">Others</option>
                              <option value="dhlint_R">Globalmail business</option>
                              <option value="dhlint_S">Same day</option>
                              <option value="dhlint_T">Express 12:00</option>
                              <option value="dhlint_X">Express envelope</option>
                              <option value="pedroteixeira_correios">Correios</option>
                              <option value="mercadolivre">Meli Envios</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Entrega Garantida:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_guaranteed]" class="form-control">
                              <option value="flatrate">Fixed</option>
                              <option value="freeshipping">Free</option>
                              <option value="tablerate_bestway">Table Rate</option>
                              <option value="dhl_IE">International Express</option>
                              <option value="dhl_E SAT">Express Saturday</option>
                              <option value="dhl_E 10:30AM">Express 10:30 AM</option>
                              <option value="dhl_E">Express</option>
                              <option value="dhl_N">Next Afternoon</option>
                              <option value="dhl_S">Second Day Service</option>
                              <option value="dhl_G">Ground</option>
                              <option value="fedex_EUROPE_FIRST_INTERNATIONAL_PRIORITY">Europe First Priority</option>
                              <option value="fedex_FEDEX_1_DAY_FREIGHT">1 Day Freight</option>
                              <option value="fedex_FEDEX_2_DAY_FREIGHT">2 Day Freight</option>
                              <option value="fedex_FEDEX_2_DAY">2 Day</option>
                              <option value="fedex_FEDEX_2_DAY_AM">2 Day AM</option>
                              <option value="fedex_FEDEX_3_DAY_FREIGHT">3 Day Freight</option>
                              <option value="fedex_FEDEX_EXPRESS_SAVER">Express Saver</option>
                              <option value="fedex_FEDEX_GROUND">Ground</option>
                              <option value="fedex_FIRST_OVERNIGHT">First Overnight</option>
                              <option value="fedex_GROUND_HOME_DELIVERY">Home Delivery</option>
                              <option value="fedex_INTERNATIONAL_ECONOMY">International Economy</option>
                              <option value="fedex_INTERNATIONAL_ECONOMY_FREIGHT">Intl Economy Freight</option>
                              <option value="fedex_INTERNATIONAL_FIRST">International First</option>
                              <option value="fedex_INTERNATIONAL_GROUND">International Ground</option>
                              <option value="fedex_INTERNATIONAL_PRIORITY">International Priority</option>
                              <option value="fedex_INTERNATIONAL_PRIORITY_FREIGHT">Intl Priority Freight</option>
                              <option value="fedex_PRIORITY_OVERNIGHT">Priority Overnight</option>
                              <option value="fedex_SMART_POST">Smart Post</option>
                              <option value="fedex_STANDARD_OVERNIGHT">Standard Overnight</option>
                              <option value="fedex_FEDEX_FREIGHT">Freight</option>
                              <option value="fedex_FEDEX_NATIONAL_FREIGHT">National Freight</option>
                              <option value="ups_1DM">Next Day Air Early AM</option>
                              <option value="ups_1DML">Next Day Air Early AM Letter</option>
                              <option value="ups_1DA">Next Day Air</option>
                              <option value="ups_1DAL">Next Day Air Letter</option>
                              <option value="ups_1DAPI">Next Day Air Intra (Puerto Rico)</option>
                              <option value="ups_1DP">Next Day Air Saver</option>
                              <option value="ups_1DPL">Next Day Air Saver Letter</option>
                              <option value="ups_2DM">2nd Day Air AM</option>
                              <option value="ups_2DML">2nd Day Air AM Letter</option>
                              <option value="ups_2DA">2nd Day Air</option>
                              <option value="ups_2DAL">2nd Day Air Letter</option>
                              <option value="ups_3DS">3 Day Select</option>
                              <option value="ups_GND">Ground</option>
                              <option value="ups_GNDCOM">Ground Commercial</option>
                              <option value="ups_GNDRES">Ground Residential</option>
                              <option value="ups_STD">Canada Standard</option>
                              <option value="ups_XPR">Worldwide Express</option>
                              <option value="ups_WXS">Worldwide Express Saver</option>
                              <option value="ups_XPRL">Worldwide Express Letter</option>
                              <option value="ups_XDM">Worldwide Express Plus</option>
                              <option value="ups_XDML">Worldwide Express Plus Letter</option>
                              <option value="ups_XPD">Worldwide Expedited</option>
                              <option value="usps_0_FCLE">First-Class Mail Large Envelope</option>
                              <option value="usps_0_FCL">First-Class Mail Letter</option>
                              <option value="usps_0_FCP">First-Class Mail Parcel</option>
                              <option value="usps_1">Priority Mail</option>
                              <option value="usps_2">Priority Mail Express Hold For Pickup</option>
                              <option value="usps_3">Priority Mail Express</option>
                              <option value="usps_4">Standard Post</option>
                              <option value="usps_6">Media Mail Parcel</option>
                              <option value="usps_7">Library Mail Parcel</option>
                              <option value="usps_13">Priority Mail Express Flat Rate Envelope</option>
                              <option value="usps_16">Priority Mail Flat Rate Envelope</option>
                              <option value="usps_17">Priority Mail Medium Flat Rate Box</option>
                              <option value="usps_22">Priority Mail Large Flat Rate Box</option>
                              <option value="usps_23">Priority Mail Express Sunday/Holiday Delivery</option>
                              <option value="usps_25">Priority Mail Express Sunday/Holiday Delivery Flat Rate Envelope</option>
                              <option value="usps_27">Priority Mail Express Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_28">Priority Mail Small Flat Rate Box</option>
                              <option value="usps_33">Priority Mail Hold For Pickup</option>
                              <option value="usps_34">Priority Mail Large Flat Rate Box Hold For Pickup</option>
                              <option value="usps_35">Priority Mail Medium Flat Rate Box Hold For Pickup</option>
                              <option value="usps_36">Priority Mail Small Flat Rate Box Hold For Pickup</option>
                              <option value="usps_37">Priority Mail Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_42">Priority Mail Small Flat Rate Envelope</option>
                              <option value="usps_43">Priority Mail Small Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_53">First-Class Package Service Hold For Pickup</option>
                              <option value="usps_55">Priority Mail Express Flat Rate Boxes</option>
                              <option value="usps_56">Priority Mail Express Flat Rate Boxes Hold For Pickup</option>
                              <option value="usps_57">Priority Mail Express Sunday/Holiday Delivery Flat Rate Boxes
                              </option>
                              <option value="usps_61">First-Class Package Service</option>
                              <option value="usps_INT_1">Priority Mail Express International</option>
                              <option value="usps_INT_2">Priority Mail International</option>
                              <option value="usps_INT_4">Global Express Guaranteed (GXG)</option>
                              <option value="usps_INT_6">Global Express Guaranteed Non-Document Rectangular</option>
                              <option value="usps_INT_7">Global Express Guaranteed Non-Document Non-Rectangular
                              </option>
                              <option value="usps_INT_8">Priority Mail International Flat Rate Envelope</option>
                              <option value="usps_INT_9">Priority Mail International Medium Flat Rate Box</option>
                              <option value="usps_INT_10">Priority Mail Express International Flat Rate Envelope
                              </option>
                              <option value="usps_INT_11">Priority Mail International Large Flat Rate Box</option>
                              <option value="usps_INT_12">USPS GXG Envelopes</option>
                              <option value="usps_INT_13">First-Class Mail International Letter</option>
                              <option value="usps_INT_14">First-Class Mail International Large Envelope</option>
                              <option value="usps_INT_15">First-Class Package International Service</option>
                              <option value="usps_INT_16">Priority Mail International Small Flat Rate Box</option>
                              <option value="usps_INT_20">Priority Mail International Small Flat Rate Envelope</option>
                              <option value="usps_INT_26">Priority Mail Express International Flat Rate Boxes</option>
                              <option value="dhlint_2">Easy shop</option>
                              <option value="dhlint_5">Sprintline</option>
                              <option value="dhlint_6">Secureline</option>
                              <option value="dhlint_7">Express easy</option>
                              <option value="dhlint_9">Europack</option>
                              <option value="dhlint_B">Break bulk express</option>
                              <option value="dhlint_C">Medical express</option>
                              <option value="dhlint_D">Express worldwide</option>
                              <option value="dhlint_U">Express worldwide</option>
                              <option value="dhlint_K">Express 9:00</option>
                              <option value="dhlint_L">Express 10:30</option>
                              <option value="dhlint_G">Domestic economy select</option>
                              <option value="dhlint_W">Economy select</option>
                              <option value="dhlint_I">Break bulk economy</option>
                              <option value="dhlint_N">Domestic express</option>
                              <option value="dhlint_O">Others</option>
                              <option value="dhlint_R">Globalmail business</option>
                              <option value="dhlint_S">Same day</option>
                              <option value="dhlint_T">Express 12:00</option>
                              <option value="dhlint_X">Express envelope</option>
                              <option value="pedroteixeira_correios">Correios</option>
                              <option value="mercadolivre">Meli Envios</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Entrega na hora:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_hour]" class="form-control">
                              <option value="flatrate">Fixed</option>
                              <option value="freeshipping">Free</option>
                              <option value="tablerate_bestway">Table Rate</option>
                              <option value="dhl_IE">International Express</option>
                              <option value="dhl_E SAT">Express Saturday</option>
                              <option value="dhl_E 10:30AM">Express 10:30 AM</option>
                              <option value="dhl_E">Express</option>
                              <option value="dhl_N">Next Afternoon</option>
                              <option value="dhl_S">Second Day Service</option>
                              <option value="dhl_G">Ground</option>
                              <option value="fedex_EUROPE_FIRST_INTERNATIONAL_PRIORITY">Europe First Priority</option>
                              <option value="fedex_FEDEX_1_DAY_FREIGHT">1 Day Freight</option>
                              <option value="fedex_FEDEX_2_DAY_FREIGHT">2 Day Freight</option>
                              <option value="fedex_FEDEX_2_DAY">2 Day</option>
                              <option value="fedex_FEDEX_2_DAY_AM">2 Day AM</option>
                              <option value="fedex_FEDEX_3_DAY_FREIGHT">3 Day Freight</option>
                              <option value="fedex_FEDEX_EXPRESS_SAVER">Express Saver</option>
                              <option value="fedex_FEDEX_GROUND">Ground</option>
                              <option value="fedex_FIRST_OVERNIGHT">First Overnight</option>
                              <option value="fedex_GROUND_HOME_DELIVERY">Home Delivery</option>
                              <option value="fedex_INTERNATIONAL_ECONOMY">International Economy</option>
                              <option value="fedex_INTERNATIONAL_ECONOMY_FREIGHT">Intl Economy Freight</option>
                              <option value="fedex_INTERNATIONAL_FIRST">International First</option>
                              <option value="fedex_INTERNATIONAL_GROUND">International Ground</option>
                              <option value="fedex_INTERNATIONAL_PRIORITY">International Priority</option>
                              <option value="fedex_INTERNATIONAL_PRIORITY_FREIGHT">Intl Priority Freight</option>
                              <option value="fedex_PRIORITY_OVERNIGHT">Priority Overnight</option>
                              <option value="fedex_SMART_POST">Smart Post</option>
                              <option value="fedex_STANDARD_OVERNIGHT">Standard Overnight</option>
                              <option value="fedex_FEDEX_FREIGHT">Freight</option>
                              <option value="fedex_FEDEX_NATIONAL_FREIGHT">National Freight</option>
                              <option value="ups_1DM">Next Day Air Early AM</option>
                              <option value="ups_1DML">Next Day Air Early AM Letter</option>
                              <option value="ups_1DA">Next Day Air</option>
                              <option value="ups_1DAL">Next Day Air Letter</option>
                              <option value="ups_1DAPI">Next Day Air Intra (Puerto Rico)</option>
                              <option value="ups_1DP">Next Day Air Saver</option>
                              <option value="ups_1DPL">Next Day Air Saver Letter</option>
                              <option value="ups_2DM">2nd Day Air AM</option>
                              <option value="ups_2DML">2nd Day Air AM Letter</option>
                              <option value="ups_2DA">2nd Day Air</option>
                              <option value="ups_2DAL">2nd Day Air Letter</option>
                              <option value="ups_3DS">3 Day Select</option>
                              <option value="ups_GND">Ground</option>
                              <option value="ups_GNDCOM">Ground Commercial</option>
                              <option value="ups_GNDRES">Ground Residential</option>
                              <option value="ups_STD">Canada Standard</option>
                              <option value="ups_XPR">Worldwide Express</option>
                              <option value="ups_WXS">Worldwide Express Saver</option>
                              <option value="ups_XPRL">Worldwide Express Letter</option>
                              <option value="ups_XDM">Worldwide Express Plus</option>
                              <option value="ups_XDML">Worldwide Express Plus Letter</option>
                              <option value="ups_XPD">Worldwide Expedited</option>
                              <option value="usps_0_FCLE">First-Class Mail Large Envelope</option>
                              <option value="usps_0_FCL">First-Class Mail Letter</option>
                              <option value="usps_0_FCP">First-Class Mail Parcel</option>
                              <option value="usps_1">Priority Mail</option>
                              <option value="usps_2">Priority Mail Express Hold For Pickup</option>
                              <option value="usps_3">Priority Mail Express</option>
                              <option value="usps_4">Standard Post</option>
                              <option value="usps_6">Media Mail Parcel</option>
                              <option value="usps_7">Library Mail Parcel</option>
                              <option value="usps_13">Priority Mail Express Flat Rate Envelope</option>
                              <option value="usps_16">Priority Mail Flat Rate Envelope</option>
                              <option value="usps_17">Priority Mail Medium Flat Rate Box</option>
                              <option value="usps_22">Priority Mail Large Flat Rate Box</option>
                              <option value="usps_23">Priority Mail Express Sunday/Holiday Delivery</option>
                              <option value="usps_25">Priority Mail Express Sunday/Holiday Delivery Flat Rate Envelope</option>
                              <option value="usps_27">Priority Mail Express Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_28">Priority Mail Small Flat Rate Box</option>
                              <option value="usps_33">Priority Mail Hold For Pickup</option>
                              <option value="usps_34">Priority Mail Large Flat Rate Box Hold For Pickup</option>
                              <option value="usps_35">Priority Mail Medium Flat Rate Box Hold For Pickup</option>
                              <option value="usps_36">Priority Mail Small Flat Rate Box Hold For Pickup</option>
                              <option value="usps_37">Priority Mail Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_42">Priority Mail Small Flat Rate Envelope</option>
                              <option value="usps_43">Priority Mail Small Flat Rate Envelope Hold For Pickup</option>
                              <option value="usps_53">First-Class Package Service Hold For Pickup</option>
                              <option value="usps_55">Priority Mail Express Flat Rate Boxes</option>
                              <option value="usps_56">Priority Mail Express Flat Rate Boxes Hold For Pickup</option>
                              <option value="usps_57">Priority Mail Express Sunday/Holiday Delivery Flat Rate Boxes</option>
                              <option value="usps_61">First-Class Package Service</option>
                              <option value="usps_INT_1">Priority Mail Express International</option>
                              <option value="usps_INT_2">Priority Mail International</option>
                              <option value="usps_INT_4">Global Express Guaranteed (GXG)</option>
                              <option value="usps_INT_6">Global Express Guaranteed Non-Document Rectangular</option>
                              <option value="usps_INT_7">Global Express Guaranteed Non-Document Non-Rectangular</option>
                              <option value="usps_INT_8">Priority Mail International Flat Rate Envelope</option>
                              <option value="usps_INT_9">Priority Mail International Medium Flat Rate Box</option>
                              <option value="usps_INT_10">Priority Mail Express International Flat Rate Envelope</option>
                              <option value="usps_INT_11">Priority Mail International Large Flat Rate Box</option>
                              <option value="usps_INT_12">USPS GXG Envelopes</option>
                              <option value="usps_INT_13">First-Class Mail International Letter</option>
                              <option value="usps_INT_14">First-Class Mail International Large Envelope</option>
                              <option value="usps_INT_15">First-Class Package International Service</option>
                              <option value="usps_INT_16">Priority Mail International Small Flat Rate Box</option>
                              <option value="usps_INT_20">Priority Mail International Small Flat Rate Envelope</option>
                              <option value="usps_INT_26">Priority Mail Express International Flat Rate Boxes</option>
                              <option value="dhlint_2">Easy shop</option>
                              <option value="dhlint_5">Sprintline</option>
                              <option value="dhlint_6">Secureline</option>
                              <option value="dhlint_7">Express easy</option>
                              <option value="dhlint_9">Europack</option>
                              <option value="dhlint_B">Break bulk express</option>
                              <option value="dhlint_C">Medical express</option>
                              <option value="dhlint_D">Express worldwide</option>
                              <option value="dhlint_U">Express worldwide</option>
                              <option value="dhlint_K">Express 9:00</option>
                              <option value="dhlint_L">Express 10:30</option>
                              <option value="dhlint_G">Domestic economy select</option>
                              <option value="dhlint_W">Economy select</option>
                              <option value="dhlint_I">Break bulk economy</option>
                              <option value="dhlint_N">Domestic express</option>
                              <option value="dhlint_O">Others</option>
                              <option value="dhlint_R">Globalmail business</option>
                              <option value="dhlint_S">Same day</option>
                              <option value="dhlint_T">Express 12:00</option>
                              <option value="dhlint_X">Express envelope</option>
                              <option value="pedroteixeira_correios">Correios</option>
                              <option value="mercadolivre">Meli Envios</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group" style="float: right; padding-right: 15px;">
                    <button type="submit" class="btn btn-success">Salvar</button>
                  </div>
                </div>
              </form>
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