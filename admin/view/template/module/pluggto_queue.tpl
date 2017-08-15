<?php echo $header; ?>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.0/css/font-awesome.min.css">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

<div id="content">
    <div class="page-header" style="margin-top: -40px;">

    <div class="alert alert-primary">
      <b><?php echo $alerts ?></b>
    </div>

    <div class="container-fluid">
      
      <div class="pull-right">
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

    <div class="col-md-12" style="width: 97%; 'margin-left: 20px;">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="fa fa-pencil"></i> Queue
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <th>ID</th>
                <th>Resource ID</th>
                <th>Type</th>
                <th>Action</th>
                <th>Date Created</th>
                <th>Status</th>
                <th>Ações</th>
              </thead>
              <tbody>
                <?php if(!empty($queues->rows)): ?>
                  <?php foreach ($queues->rows as $item): ?>
                    <tr>
                      <td><?php echo $item['id'] ?></td>
                      <td><?php echo $item['resource_id'] ?></td>
                      <td><?php echo $item['type'] ?></td>
                      <td><?php echo $item['action'] ?></td>
                      <td><?php echo $item['date_created'] ?></td>
                      <td><?php echo $item['status'] ?></td>
                      <td><a href="javascript:showLog('<?php echo $item['id'] ?>');" class="btn btn-info">Detalhes</a></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="7" align="center" style="padding-top: 25px;"><h1>Nenhum item a ser processado.</h1></td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    </div>
  </div>
 </div>
</div>

<?php echo $footer; ?>
