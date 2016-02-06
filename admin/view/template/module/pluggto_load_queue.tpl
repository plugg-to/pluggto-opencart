<?php echo $header; ?>

<?php echo $column_left; ?>

<div id="content">
  <div class="page-header">

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

      <div class="col-md-12 text-center" style="margin-bottom: 30px;">
        <img src="http://plugg.to/wp-content/uploads/2015/10/PluggTo-Header-Logo-Verde.png" alt="" /> 
      </div>

      <div class="col-md-12 text-center" style="margin-bottom: 30px;">
        <div class="table-responsive">
            <table class="table table-striped">
                <th>ID</th>
                <th>Type</th>
                <th>Status</th>
                <th>Resource</th>
                <th>Action</th>
                <th>Created</th>
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
            </table>
        </div>
      </div>      

    </div>
  </div>
