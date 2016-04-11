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
<!--         <button type="submit" form="form-latest" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary">
            <i class="fa fa-save"></i>
        </button> -->
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

      <div class="col-md-12 text-center" style="margin-bottom: 30px;">
        <img src="http://plugg.to/wp-content/uploads/2015/10/PluggTo-Header-Logo-Verde.png" alt="" /> 
      </div>

      <div class="col-md-12 text-center" style="margin-bottom: 30px;">
      <div class="panel panel-default">
          <div class="panel-heading">
              <h3 class="panel-title">
                <i class="fa fa-pencil"></i> Queue
              </h3>
          </div>
          <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="tablequeue">
                      <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Resource</th>
                            <th>Action</th>
                            <th>Created</th>
                        </tr>
                      </thead>
                      <tbody>
                    <?php foreach ($queue as $info) { ?>
                        <tr>
                            <td><?php echo $info['id']; ?></td>
                            <td><?php echo $info['type']; ?></td>
                            <td><?php echo $info['status']; ?></td>
                            <td><?php echo $info['resource_id']; ?></td>
                            <td><?php echo $info['action']; ?></td>
                            <td><?php echo $info['date_created']; ?></td>
                        </tr>
                    <?php } ?>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>      
    </div>
  </div>
  <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
       $(document).ready(function() {
           $('#tablequeue').DataTable({
             "order": [[ 3, "desc" ]]
           });
       });
  </script>

  
<?php echo $footer; ?>
