<?php echo $header; ?>

<?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
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

  <div class="row" style="width: 99%;">
    <div class="col-md-12" style="margin-left: 20px;">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="fa fa-pencil"></i> Configurações
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

  <?php if (!empty($access_status)) { ?>
    <h3><?php echo $access_status ?></h3>
  <?php } ?>

  <?php if (!empty($result)) { ?>
    <table id="module" class="list">
      <thead>
      <tr>
          <td class="left">Produtos</td>
          <td class="left">OP para o Pluggto</td>
          <td class="left">Pluggto para o OP</td>
          <td class="left">Novos Produtos do Pluggto para o OP</td>
          <td class="left">Novos Produtos do OP para o PLuggto</td>
      </tr>
      </thead>
      <tbody>
      <tr>
          <td class="left"><?php echo $result['all'] ?></td>
          <td class="left"><?php echo $result['update_to'] ?></td>
          <td class="left"><?php echo $result['update_from'] ?></td>
          <td class="left"><?php echo $result['create_from'] ?></td>
          <td class="left"><?php echo $result['create_to'] ?></td>
      </tr>
      </tbody>
    </table>
  <?php } ?>

  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="checkProducts">
    <input type="hidden" name="checkCredentials" value="1" />
  </form>

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