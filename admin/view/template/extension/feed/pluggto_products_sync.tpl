<?php echo $header; ?>

<?php echo $column_left; ?>

<div id="content">
  <div class="page-header">

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
                <i class="fa fa-pencil"></i> Produtos
              </h3>
          </div>
          <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="tablequeue">
                      <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome do Produto</th>
                            <th>SKU/Modelo</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Ações</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product['product_id']; ?></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['sku']; ?></td>
                                <td>R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td>
                                  <a href="/index.php?route=api/pluggto/forceSyncProduct&product_id=<?php echo $product['product_id']; ?>&token=<?php echo $token; ?>" class="btn btn-info" target="_blank" alt="Forçar sicronização"><i class="fa fa-check-square-o"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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
