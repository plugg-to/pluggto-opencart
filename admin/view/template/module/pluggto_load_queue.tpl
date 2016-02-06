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
        <?php foreach ($queue as $info) {
                echo $info['id'].'<br>';
                echo $info['type'].'<br>';
                echo $info['status'].'<br>';
                echo $info['resource_id'].'<br>';
                echo $info['action'].'<br>';
                echo $info['date_created'].'<br>';
          } 
        ?>
      </div>      

    </div>
  </div>
