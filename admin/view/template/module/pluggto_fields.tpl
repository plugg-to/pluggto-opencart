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

    </div>
  </div>

  <div class="col-md-12 text-center" style="margin-bottom: 30px;">
    <img src="http://plugg.to/wp-content/uploads/2015/10/PluggTo-Header-Logo-Verde.png" alt="" /> 
  </div>
          <div class="tab-pane fade in" id="A">            
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
                              <option 
                                <?php if (@$default_fields['width'] ==  "width"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if (@$default_fields['width'] ==  "height"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if (@$default_fields['width'] ==  "length"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if (@$default_fields['width'] ==  "weight"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if (@$default_fields['width'] ==  "brand"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if (@$default_fields['width'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if (@$default_fields['width'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if (@$default_fields['width'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if (@$default_fields['width'] ==  "description"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if (@$default_fields['width'] ==  "isbn"): ?>
                                  selected
                                <?php endif; ?>                                 
                                value="isbn"
                              >
                                ISBN
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Altura:</label>
                          <div class="col-sm-10">
                            <select name="fields[height]" class="form-control">
                              <option 
                                <?php if (@$default_fields['height'] ==  "width"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if (@$default_fields['height'] ==  "height"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if (@$default_fields['height'] ==  "length"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if (@$default_fields['height'] ==  "weight"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if (@$default_fields['height'] ==  "brand"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if (@$default_fields['height'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if (@$default_fields['height'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if (@$default_fields['height'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if (@$default_fields['height'] ==  "description"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if (@$default_fields['height'] ==  "isbn"): ?>
                                  selected
                                <?php endif; ?>                                 
                                value="isbn"
                              >
                                ISBN
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Comprimento:</label>
                          <div class="col-sm-10">
                            <select name="fields[length]" class="form-control">
                              <option 
                                <?php if (@$default_fields['length'] ==  "width"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if (@$default_fields['length'] ==  "height"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if (@$default_fields['length'] ==  "length"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if (@$default_fields['length'] ==  "weight"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if (@$default_fields['length'] ==  "brand"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if (@$default_fields['length'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if (@$default_fields['length'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if (@$default_fields['length'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if (@$default_fields['length'] ==  "description"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if (@$default_fields['length'] ==  "isbn"): ?>
                                  selected
                                <?php endif; ?>                                 
                                value="isbn"
                              >
                                ISBN
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Peso:</label>
                          <div class="col-sm-10">
                            <select name="fields[weight]" class="form-control">
                              <option 
                                <?php if (@$default_fields['weight'] ==  "width"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if (@$default_fields['weight'] ==  "height"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if (@$default_fields['weight'] ==  "length"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if (@$default_fields['weight'] ==  "weight"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if (@$default_fields['weight'] ==  "brand"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if (@$default_fields['weight'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if (@$default_fields['weight'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if (@$default_fields['weight'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if (@$default_fields['weight'] ==  "description"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if (@$default_fields['weight'] ==  "isbn"): ?>
                                  selected
                                <?php endif; ?>                                 
                                value="isbn"
                              >
                                ISBN
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Marca/Fabricante:</label>
                          <div class="col-sm-10">
                            <select name="fields[brand]" class="form-control">
                              <option 
                                <?php if (@$default_fields['brand'] ==  "width"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if (@$default_fields['brand'] ==  "height"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if (@$default_fields['brand'] ==  "length"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if (@$default_fields['brand'] ==  "weight"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if (@$default_fields['brand'] ==  "brand"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if (@$default_fields['brand'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if (@$default_fields['brand'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if (@$default_fields['brand'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if (@$default_fields['brand'] ==  "description"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if (@$default_fields['brand'] ==  "isbn"): ?>
                                  selected
                                <?php endif; ?>                                 
                                value="isbn"
                              >
                                ISBN
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">EAN:</label>
                          <div class="col-sm-10">
                            <select name="fields[ean]" class="form-control">
                              <option 
                                <?php if (@$default_fields['ean'] ==  "width"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if (@$default_fields['ean'] ==  "height"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if (@$default_fields['ean'] ==  "length"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if (@$default_fields['ean'] ==  "weight"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if (@$default_fields['ean'] ==  "brand"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if (@$default_fields['ean'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if (@$default_fields['ean'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if (@$default_fields['ean'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if (@$default_fields['ean'] ==  "description"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if (@$default_fields['ean'] ==  "isbn"): ?>
                                  selected
                                <?php endif; ?>                                 
                                value="isbn"
                              >
                                ISBN
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">NBM:</label>
                          <div class="col-sm-10">
                            <select name="fields[nbm]" class="form-control">
                              <option 
                                <?php if (@$default_fields['nbm'] ==  "width"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if (@$default_fields['nbm'] ==  "height"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if (@$default_fields['nbm'] ==  "length"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if (@$default_fields['nbm'] ==  "weight"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if (@$default_fields['nbm'] ==  "brand"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if (@$default_fields['nbm'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if (@$default_fields['nbm'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if (@$default_fields['nbm'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if (@$default_fields['nbm'] ==  "description"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if (@$default_fields['nbm'] ==  "isbn"): ?>
                                  selected
                                <?php endif; ?>                                 
                                value="isbn"
                              >
                                ISBN
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">NCM:</label>
                          <div class="col-sm-10">
                            <select name="fields[ncm]" class="form-control">
                              <option 
                                <?php if (@$default_fields['ncm'] ==  "width"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if (@$default_fields['ncm'] ==  "height"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if (@$default_fields['ncm'] ==  "length"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if (@$default_fields['ncm'] ==  "weight"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if (@$default_fields['ncm'] ==  "brand"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if (@$default_fields['ncm'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if (@$default_fields['ncm'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if (@$default_fields['ncm'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if (@$default_fields['ncm'] ==  "description"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if (@$default_fields['ncm'] ==  "isbn"): ?>
                                  selected
                                <?php endif; ?>                                 
                                value="isbn"
                              >
                                ISBN
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Descriçao:</label>
                          <div class="col-sm-10">
                            <select name="fields[description]" class="form-control">
                              <option 
                                <?php if (@$default_fields['description'] ==  "width"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if (@$default_fields['description'] ==  "height"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if (@$default_fields['description'] ==  "length"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if (@$default_fields['description'] ==  "weight"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if (@$default_fields['description'] ==  "brand"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if (@$default_fields['description'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if (@$default_fields['description'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if (@$default_fields['description'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if (@$default_fields['description'] ==  "description"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if (@$default_fields['description'] ==  "isbn"): ?>
                                  selected
                                <?php endif; ?>                                 
                                value="isbn"
                              >
                                ISBN
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">ISBN:</label>
                          <div class="col-sm-10">
                            <select name="fields[isbn]" class="form-control">
                              <option 
                                <?php if (@$default_fields['isbn'] ==  "width"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if (@$default_fields['isbn'] ==  "height"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if (@$default_fields['isbn'] ==  "length"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if (@$default_fields['isbn'] ==  "weight"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if (@$default_fields['isbn'] ==  "brand"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if (@$default_fields['isbn'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if (@$default_fields['isbn'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if (@$default_fields['isbn'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if (@$default_fields['isbn'] ==  "description"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if (@$default_fields['isbn'] ==  "isbn"): ?>
                                  selected
                                <?php endif; ?>                                 
                                value="isbn"
                              >
                                ISBN
                              </option>
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
                            <select name="fields[pending]" class="form-control">
                              <option 
                                value="Processing"
                                <?php if(@$default_fields['pending'] == "Processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processing
                              </option>
                              <option 
                                value="Shipped"
                                <?php if(@$default_fields['pending'] == "Shipped"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Shipped
                              </option>
                              <option 
                                value="Canceled"
                                <?php if(@$default_fields['pending'] == "Canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled
                              </option>
                              <option 
                                value="Complete"
                                <?php if(@$default_fields['pending'] == "Complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Complete
                              </option>
                              <option 
                                value="Denied"
                                <?php if(@$default_fields['pending'] == "Denied"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Denied
                              </option>
                              <option 
                                value="Canceled Reversal"
                                <?php if(@$default_fields['pending'] == "Canceled Reversal"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled Reversal
                              </option>
                              <option 
                                value="Failed"
                                <?php if(@$default_fields['pending'] == "Failed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Failed
                              </option>
                              <option 
                                value="Refunded"
                                <?php if(@$default_fields['pending'] == "Refunded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Refunded
                              </option>
                              <option 
                                value="Reversed"
                                <?php if(@$default_fields['pending'] == "Reversed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Reversed
                              </option>
                              <option 
                                value="Chargeback"
                                <?php if(@$default_fields['pending'] == "Chargeback"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Chargeback
                              </option>
                              <option 
                                value="Pending"
                                <?php if(@$default_fields['pending'] == "Pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pending
                              </option>
                              <option 
                                value="Voided"
                                <?php if(@$default_fields['pending'] == "Voided"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Voided
                              </option>
                              <option 
                                value="Processed"
                                <?php if(@$default_fields['pending'] == "Processed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processed
                              </option>
                              <option 
                                value="Expired"
                                <?php if(@$default_fields['pending'] == "Expired"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Expired
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Pagos:</label>
                          <div class="col-sm-10">
                            <select name="fields[paid]" class="form-control">
                              <option 
                                value="Processing"
                                <?php if(@$default_fields['paid'] == "Processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processing
                              </option>
                              <option 
                                value="Shipped"
                                <?php if(@$default_fields['paid'] == "Shipped"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Shipped
                              </option>
                              <option 
                                value="Canceled"
                                <?php if(@$default_fields['paid'] == "Canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled
                              </option>
                              <option 
                                value="Complete"
                                <?php if(@$default_fields['paid'] == "Complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Complete
                              </option>
                              <option 
                                value="Denied"
                                <?php if(@$default_fields['paid'] == "Denied"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Denied
                              </option>
                              <option 
                                value="Canceled Reversal"
                                <?php if(@$default_fields['paid'] == "Canceled Reversal"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled Reversal
                              </option>
                              <option 
                                value="Failed"
                                <?php if(@$default_fields['paid'] == "Failed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Failed
                              </option>
                              <option 
                                value="Refunded"
                                <?php if(@$default_fields['paid'] == "Refunded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Refunded
                              </option>
                              <option 
                                value="Reversed"
                                <?php if(@$default_fields['paid'] == "Reversed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Reversed
                              </option>
                              <option 
                                value="Chargeback"
                                <?php if(@$default_fields['paid'] == "Chargeback"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Chargeback
                              </option>
                              <option 
                                value="Pending"
                                <?php if(@$default_fields['paid'] == "Pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pending
                              </option>
                              <option 
                                value="Voided"
                                <?php if(@$default_fields['paid'] == "Voided"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Voided
                              </option>
                              <option 
                                value="Processed"
                                <?php if(@$default_fields['paid'] == "Processed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processed
                              </option>
                              <option 
                                value="Expired"
                                <?php if(@$default_fields['paid'] == "Expired"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Expired
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Aprovados:</label>
                          <div class="col-sm-10">
                            <select name="fields[approved]" class="form-control">
                              <option 
                                value="Processing"
                                <?php if(@$default_fields['approved'] == "Processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processing
                              </option>
                              <option 
                                value="Shipped"
                                <?php if(@$default_fields['approved'] == "Shipped"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Shipped
                              </option>
                              <option 
                                value="Canceled"
                                <?php if(@$default_fields['approved'] == "Canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled
                              </option>
                              <option 
                                value="Complete"
                                <?php if(@$default_fields['approved'] == "Complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Complete
                              </option>
                              <option 
                                value="Denied"
                                <?php if(@$default_fields['approved'] == "Denied"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Denied
                              </option>
                              <option 
                                value="Canceled Reversal"
                                <?php if(@$default_fields['approved'] == "Canceled Reversal"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled Reversal
                              </option>
                              <option 
                                value="Failed"
                                <?php if(@$default_fields['approved'] == "Failed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Failed
                              </option>
                              <option 
                                value="Refunded"
                                <?php if(@$default_fields['approved'] == "Refunded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Refunded
                              </option>
                              <option 
                                value="Reversed"
                                <?php if(@$default_fields['approved'] == "Reversed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Reversed
                              </option>
                              <option 
                                value="Chargeback"
                                <?php if(@$default_fields['approved'] == "Chargeback"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Chargeback
                              </option>
                              <option 
                                value="Pending"
                                <?php if(@$default_fields['approved'] == "Pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pending
                              </option>
                              <option 
                                value="Voided"
                                <?php if(@$default_fields['approved'] == "Voided"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Voided
                              </option>
                              <option 
                                value="Processed"
                                <?php if(@$default_fields['approved'] == "Processed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processed
                              </option>
                              <option 
                                value="Expired"
                                <?php if(@$default_fields['approved'] == "Expired"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Expired
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Aguardando Fatura:</label>
                          <div class="col-sm-10">
                            <select name="fields[waiting_invoice]" class="form-control">
                              <option 
                                value="Processing"
                                <?php if(@$default_fields['waiting_invoice'] == "Processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processing
                              </option>
                              <option 
                                value="Shipped"
                                <?php if(@$default_fields['waiting_invoice'] == "Shipped"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Shipped
                              </option>
                              <option 
                                value="Canceled"
                                <?php if(@$default_fields['waiting_invoice'] == "Canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled
                              </option>
                              <option 
                                value="Complete"
                                <?php if(@$default_fields['waiting_invoice'] == "Complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Complete
                              </option>
                              <option 
                                value="Denied"
                                <?php if(@$default_fields['waiting_invoice'] == "Denied"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Denied
                              </option>
                              <option 
                                value="Canceled Reversal"
                                <?php if(@$default_fields['waiting_invoice'] == "Canceled Reversal"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled Reversal
                              </option>
                              <option 
                                value="Failed"
                                <?php if(@$default_fields['waiting_invoice'] == "Failed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Failed
                              </option>
                              <option 
                                value="Refunded"
                                <?php if(@$default_fields['waiting_invoice'] == "Refunded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Refunded
                              </option>
                              <option 
                                value="Reversed"
                                <?php if(@$default_fields['waiting_invoice'] == "Reversed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Reversed
                              </option>
                              <option 
                                value="Chargeback"
                                <?php if(@$default_fields['waiting_invoice'] == "Chargeback"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Chargeback
                              </option>
                              <option 
                                value="Pending"
                                <?php if(@$default_fields['waiting_invoice'] == "Pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pending
                              </option>
                              <option 
                                value="Voided"
                                <?php if(@$default_fields['waiting_invoice'] == "Voided"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Voided
                              </option>
                              <option 
                                value="Processed"
                                <?php if(@$default_fields['waiting_invoice'] == "Processed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processed
                              </option>
                              <option 
                                value="Expired"
                                <?php if(@$default_fields['waiting_invoice'] == "Expired"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Expired
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Faturada:</label>
                          <div class="col-sm-10">
                            <select name="fields[invoiced]" class="form-control">
                              <option 
                                value="Processing"
                                <?php if(@$default_fields['invoiced'] == "Processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processing
                              </option>
                              <option 
                                value="Shipped"
                                <?php if(@$default_fields['invoiced'] == "Shipped"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Shipped
                              </option>
                              <option 
                                value="Canceled"
                                <?php if(@$default_fields['invoiced'] == "Canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled
                              </option>
                              <option 
                                value="Complete"
                                <?php if(@$default_fields['invoiced'] == "Complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Complete
                              </option>
                              <option 
                                value="Denied"
                                <?php if(@$default_fields['invoiced'] == "Denied"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Denied
                              </option>
                              <option 
                                value="Canceled Reversal"
                                <?php if(@$default_fields['invoiced'] == "Canceled Reversal"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled Reversal
                              </option>
                              <option 
                                value="Failed"
                                <?php if(@$default_fields['invoiced'] == "Failed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Failed
                              </option>
                              <option 
                                value="Refunded"
                                <?php if(@$default_fields['invoiced'] == "Refunded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Refunded
                              </option>
                              <option 
                                value="Reversed"
                                <?php if(@$default_fields['invoiced'] == "Reversed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Reversed
                              </option>
                              <option 
                                value="Chargeback"
                                <?php if(@$default_fields['invoiced'] == "Chargeback"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Chargeback
                              </option>
                              <option 
                                value="Pending"
                                <?php if(@$default_fields['invoiced'] == "Pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pending
                              </option>
                              <option 
                                value="Voided"
                                <?php if(@$default_fields['invoiced'] == "Voided"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Voided
                              </option>
                              <option 
                                value="Processed"
                                <?php if(@$default_fields['invoiced'] == "Processed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processed
                              </option>
                              <option 
                                value="Expired"
                                <?php if(@$default_fields['invoiced'] == "Expired"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Expired
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Erro na Fatura:</label>
                          <div class="col-sm-10">
                            <select name="fields[invoice_error]" class="form-control">
                              <option 
                                value="Processing"
                                <?php if(@$default_fields['invoice_error'] == "Processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processing
                              </option>
                              <option 
                                value="Shipped"
                                <?php if(@$default_fields['invoice_error'] == "Shipped"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Shipped
                              </option>
                              <option 
                                value="Canceled"
                                <?php if(@$default_fields['invoice_error'] == "Canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled
                              </option>
                              <option 
                                value="Complete"
                                <?php if(@$default_fields['invoice_error'] == "Complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Complete
                              </option>
                              <option 
                                value="Denied"
                                <?php if(@$default_fields['invoice_error'] == "Denied"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Denied
                              </option>
                              <option 
                                value="Canceled Reversal"
                                <?php if(@$default_fields['invoice_error'] == "Canceled Reversal"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled Reversal
                              </option>
                              <option 
                                value="Failed"
                                <?php if(@$default_fields['invoice_error'] == "Failed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Failed
                              </option>
                              <option 
                                value="Refunded"
                                <?php if(@$default_fields['invoice_error'] == "Refunded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Refunded
                              </option>
                              <option 
                                value="Reversed"
                                <?php if(@$default_fields['invoice_error'] == "Reversed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Reversed
                              </option>
                              <option 
                                value="Chargeback"
                                <?php if(@$default_fields['invoice_error'] == "Chargeback"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Chargeback
                              </option>
                              <option 
                                value="Pending"
                                <?php if(@$default_fields['invoice_error'] == "Pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pending
                              </option>
                              <option 
                                value="Voided"
                                <?php if(@$default_fields['invoice_error'] == "Voided"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Voided
                              </option>
                              <option 
                                value="Processed"
                                <?php if(@$default_fields['invoice_error'] == "Processed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processed
                              </option>
                              <option 
                                value="Expired"
                                <?php if(@$default_fields['invoice_error'] == "Expired"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Expired
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Entrega Informada:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_informed]" class="form-control">
                              <option 
                                value="Processing"
                                <?php if(@$default_fields['shipping_informed'] == "Processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processing
                              </option>
                              <option 
                                value="Shipped"
                                <?php if(@$default_fields['shipping_informed'] == "Shipped"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Shipped
                              </option>
                              <option 
                                value="Canceled"
                                <?php if(@$default_fields['shipping_informed'] == "Canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled
                              </option>
                              <option 
                                value="Complete"
                                <?php if(@$default_fields['shipping_informed'] == "Complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Complete
                              </option>
                              <option 
                                value="Denied"
                                <?php if(@$default_fields['shipping_informed'] == "Denied"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Denied
                              </option>
                              <option 
                                value="Canceled Reversal"
                                <?php if(@$default_fields['shipping_informed'] == "Canceled Reversal"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled Reversal
                              </option>
                              <option 
                                value="Failed"
                                <?php if(@$default_fields['shipping_informed'] == "Failed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Failed
                              </option>
                              <option 
                                value="Refunded"
                                <?php if(@$default_fields['shipping_informed'] == "Refunded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Refunded
                              </option>
                              <option 
                                value="Reversed"
                                <?php if(@$default_fields['shipping_informed'] == "Reversed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Reversed
                              </option>
                              <option 
                                value="Chargeback"
                                <?php if(@$default_fields['shipping_informed'] == "Chargeback"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Chargeback
                              </option>
                              <option 
                                value="Pending"
                                <?php if(@$default_fields['shipping_informed'] == "Pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pending
                              </option>
                              <option 
                                value="Voided"
                                <?php if(@$default_fields['shipping_informed'] == "Voided"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Voided
                              </option>
                              <option 
                                value="Processed"
                                <?php if(@$default_fields['shipping_informed'] == "Processed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processed
                              </option>
                              <option 
                                value="Expired"
                                <?php if(@$default_fields['shipping_informed'] == "Expired"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Expired
                              </option>
                            </select>
                          </div>
                        </div>                        
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Entregue:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipped]" class="form-control">
                              <option 
                                value="Processing"
                                <?php if(@$default_fields['shipped'] == "Processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processing
                              </option>
                              <option 
                                value="Shipped"
                                <?php if(@$default_fields['shipped'] == "Shipped"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Shipped
                              </option>
                              <option 
                                value="Canceled"
                                <?php if(@$default_fields['shipped'] == "Canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled
                              </option>
                              <option 
                                value="Complete"
                                <?php if(@$default_fields['shipped'] == "Complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Complete
                              </option>
                              <option 
                                value="Denied"
                                <?php if(@$default_fields['shipped'] == "Denied"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Denied
                              </option>
                              <option 
                                value="Canceled Reversal"
                                <?php if(@$default_fields['shipped'] == "Canceled Reversal"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled Reversal
                              </option>
                              <option 
                                value="Failed"
                                <?php if(@$default_fields['shipped'] == "Failed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Failed
                              </option>
                              <option 
                                value="Refunded"
                                <?php if(@$default_fields['shipped'] == "Refunded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Refunded
                              </option>
                              <option 
                                value="Reversed"
                                <?php if(@$default_fields['shipped'] == "Reversed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Reversed
                              </option>
                              <option 
                                value="Chargeback"
                                <?php if(@$default_fields['shipped'] == "Chargeback"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Chargeback
                              </option>
                              <option 
                                value="Pending"
                                <?php if(@$default_fields['shipped'] == "Pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pending
                              </option>
                              <option 
                                value="Voided"
                                <?php if(@$default_fields['shipped'] == "Voided"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Voided
                              </option>
                              <option 
                                value="Processed"
                                <?php if(@$default_fields['shipped'] == "Processed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processed
                              </option>
                              <option 
                                value="Expired"
                                <?php if(@$default_fields['shipped'] == "Expired"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Expired
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Erro na entrega:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_error]" class="form-control">
                              <option 
                                value="Processing"
                                <?php if(@$default_fields['shipping_error'] == "Processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processing
                              </option>
                              <option 
                                value="Shipped"
                                <?php if(@$default_fields['shipping_error'] == "Shipped"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Shipped
                              </option>
                              <option 
                                value="Canceled"
                                <?php if(@$default_fields['shipping_error'] == "Canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled
                              </option>
                              <option 
                                value="Complete"
                                <?php if(@$default_fields['shipping_error'] == "Complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Complete
                              </option>
                              <option 
                                value="Denied"
                                <?php if(@$default_fields['shipping_error'] == "Denied"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Denied
                              </option>
                              <option 
                                value="Canceled Reversal"
                                <?php if(@$default_fields['shipping_error'] == "Canceled Reversal"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled Reversal
                              </option>
                              <option 
                                value="Failed"
                                <?php if(@$default_fields['shipping_error'] == "Failed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Failed
                              </option>
                              <option 
                                value="Refunded"
                                <?php if(@$default_fields['shipping_error'] == "Refunded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Refunded
                              </option>
                              <option 
                                value="Reversed"
                                <?php if(@$default_fields['shipping_error'] == "Reversed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Reversed
                              </option>
                              <option 
                                value="Chargeback"
                                <?php if(@$default_fields['shipping_error'] == "Chargeback"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Chargeback
                              </option>
                              <option 
                                value="Pending"
                                <?php if(@$default_fields['shipping_error'] == "Pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pending
                              </option>
                              <option 
                                value="Voided"
                                <?php if(@$default_fields['shipping_error'] == "Voided"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Voided
                              </option>
                              <option 
                                value="Processed"
                                <?php if(@$default_fields['shipping_error'] == "Processed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processed
                              </option>
                              <option 
                                value="Expired"
                                <?php if(@$default_fields['shipping_error'] == "Expired"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Expired
                              </option>
                            </select>
                          </div>
                        </div>                        
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Entregues:</label>
                          <div class="col-sm-10">
                            <select name="fields[delivered]" class="form-control">
                              <option 
                                value="Processing"
                                <?php if(@$default_fields['delivered'] == "Processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processing
                              </option>
                              <option 
                                value="Shipped"
                                <?php if(@$default_fields['delivered'] == "Shipped"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Shipped
                              </option>
                              <option 
                                value="Canceled"
                                <?php if(@$default_fields['delivered'] == "Canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled
                              </option>
                              <option 
                                value="Complete"
                                <?php if(@$default_fields['delivered'] == "Complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Complete
                              </option>
                              <option 
                                value="Denied"
                                <?php if(@$default_fields['delivered'] == "Denied"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Denied
                              </option>
                              <option 
                                value="Canceled Reversal"
                                <?php if(@$default_fields['delivered'] == "Canceled Reversal"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled Reversal
                              </option>
                              <option 
                                value="Failed"
                                <?php if(@$default_fields['delivered'] == "Failed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Failed
                              </option>
                              <option 
                                value="Refunded"
                                <?php if(@$default_fields['delivered'] == "Refunded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Refunded
                              </option>
                              <option 
                                value="Reversed"
                                <?php if(@$default_fields['delivered'] == "Reversed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Reversed
                              </option>
                              <option 
                                value="Chargeback"
                                <?php if(@$default_fields['delivered'] == "Chargeback"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Chargeback
                              </option>
                              <option 
                                value="Pending"
                                <?php if(@$default_fields['delivered'] == "Pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pending
                              </option>
                              <option 
                                value="Voided"
                                <?php if(@$default_fields['delivered'] == "Voided"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Voided
                              </option>
                              <option 
                                value="Processed"
                                <?php if(@$default_fields['delivered'] == "Processed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processed
                              </option>
                              <option 
                                value="Expired"
                                <?php if(@$default_fields['delivered'] == "Expired"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Expired
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Cancelados:</label>
                          <div class="col-sm-10">
                            <select name="fields[canceled]" class="form-control">
                              <option 
                                value="Processing"
                                <?php if(@$default_fields['canceled'] == "Processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processing
                              </option>
                              <option 
                                value="Shipped"
                                <?php if(@$default_fields['canceled'] == "Shipped"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Shipped
                              </option>
                              <option 
                                value="Canceled"
                                <?php if(@$default_fields['canceled'] == "Canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled
                              </option>
                              <option 
                                value="Complete"
                                <?php if(@$default_fields['canceled'] == "Complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Complete
                              </option>
                              <option 
                                value="Denied"
                                <?php if(@$default_fields['canceled'] == "Denied"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Denied
                              </option>
                              <option 
                                value="Canceled Reversal"
                                <?php if(@$default_fields['canceled'] == "Canceled Reversal"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Canceled Reversal
                              </option>
                              <option 
                                value="Failed"
                                <?php if(@$default_fields['canceled'] == "Failed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Failed
                              </option>
                              <option 
                                value="Refunded"
                                <?php if(@$default_fields['canceled'] == "Refunded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Refunded
                              </option>
                              <option 
                                value="Reversed"
                                <?php if(@$default_fields['canceled'] == "Reversed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Reversed
                              </option>
                              <option 
                                value="Chargeback"
                                <?php if(@$default_fields['canceled'] == "Chargeback"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Chargeback
                              </option>
                              <option 
                                value="Pending"
                                <?php if(@$default_fields['canceled'] == "Pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pending
                              </option>
                              <option 
                                value="Voided"
                                <?php if(@$default_fields['canceled'] == "Voided"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Voided
                              </option>
                              <option 
                                value="Processed"
                                <?php if(@$default_fields['canceled'] == "Processed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processed
                              </option>
                              <option 
                                value="Expired"
                                <?php if(@$default_fields['canceled'] == "Expired"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Expired
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos que precisam ser revisados:</label>
                          <div class="col-sm-10">
                            <select name="fields[under_review]" class="form-control">
                              <option 
                                value="Processing"
                                <?php if(@$default_fields['under_review'] == "Processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processing
                              </option>
                              <option 
                                value="Shipped"
                                <?php if(@$default_fields['under_review'] == "Shipped"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Shipped
                              </option>
                              <option 
                                value="under_review"
                                <?php if(@$default_fields['under_review'] == "under_review"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                under_review
                              </option>
                              <option 
                                value="Complete"
                                <?php if(@$default_fields['under_review'] == "Complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Complete
                              </option>
                              <option 
                                value="Denied"
                                <?php if(@$default_fields['under_review'] == "Denied"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Denied
                              </option>
                              <option 
                                value="under_review Reversal"
                                <?php if(@$default_fields['under_review'] == "under_review Reversal"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                under_review Reversal
                              </option>
                              <option 
                                value="Failed"
                                <?php if(@$default_fields['under_review'] == "Failed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Failed
                              </option>
                              <option 
                                value="Refunded"
                                <?php if(@$default_fields['under_review'] == "Refunded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Refunded
                              </option>
                              <option 
                                value="Reversed"
                                <?php if(@$default_fields['under_review'] == "Reversed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Reversed
                              </option>
                              <option 
                                value="Chargeback"
                                <?php if(@$default_fields['under_review'] == "Chargeback"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Chargeback
                              </option>
                              <option 
                                value="Pending"
                                <?php if(@$default_fields['under_review'] == "Pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pending
                              </option>
                              <option 
                                value="Voided"
                                <?php if(@$default_fields['under_review'] == "Voided"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Voided
                              </option>
                              <option 
                                value="Processed"
                                <?php if(@$default_fields['under_review'] == "Processed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processed
                              </option>
                              <option 
                                value="Expired"
                                <?php if(@$default_fields['under_review'] == "Expired"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Expired
                              </option>
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
                              <?php foreach ($types_shippings as $key => $value): ?>
                                <option 
                                value="<?php echo $key; ?>"
                                <?php if(@$default_fields['shipping_default'] == $key): ?>
                                  selected
                                <?php endif; ?>
                                >
                                  <?php echo $value; ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Entrega Expressa:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_express]" class="form-control">              
                              <?php foreach ($types_shippings as $key => $value): ?>
                                <option 
                                value="<?php echo $key; ?>"
                                <?php if(@$default_fields['shipping_express'] == $key): ?>
                                  selected
                                <?php endif; ?>
                                >
                                  <?php echo $value; ?>
                                </option>
                              <?php endforeach; ?>                              
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Entrega Economica:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_economic]" class="form-control">              
                              <?php foreach ($types_shippings as $key => $value): ?>
                                <option 
                                value="<?php echo $key; ?>"
                                <?php if(@$default_fields['shipping_economic'] == $key): ?>
                                  selected
                                <?php endif; ?>
                                >
                                  <?php echo $value; ?>
                                </option>
                              <?php endforeach; ?>                              
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Entrega Agendada:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_scheduled]" class="form-control">              
                              <?php foreach ($types_shippings as $key => $value): ?>
                                <option 
                                value="<?php echo $key; ?>"
                                <?php if(@$default_fields['shipping_scheduled'] == $key): ?>
                                  selected
                                <?php endif; ?>
                                >
                                  <?php echo $value; ?>
                                </option>
                              <?php endforeach; ?>                              
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Retirar na loja:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_shop]" class="form-control">              
                              <?php foreach ($types_shippings as $key => $value): ?>
                                <option 
                                value="<?php echo $key; ?>"
                                <?php if(@$default_fields['shipping_shop'] == $key): ?>
                                  selected
                                <?php endif; ?>
                                >
                                  <?php echo $value; ?>
                                </option>
                              <?php endforeach; ?>                              
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Entrega Garantida:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_guaranteed]" class="form-control">              
                              <?php foreach ($types_shippings as $key => $value): ?>
                                <option 
                                value="<?php echo $key; ?>"
                                <?php if(@$default_fields['shipping_guaranteed'] == $key): ?>
                                  selected
                                <?php endif; ?>
                                >
                                  <?php echo $value; ?>
                                </option>
                              <?php endforeach; ?>                              
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Entrega na hora:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_hour]" class="form-control">              
                              <?php foreach ($types_shippings as $key => $value): ?>
                                <option 
                                value="<?php echo $key; ?>"
                                <?php if(@$default_fields['shipping_hour'] == $key): ?>
                                  selected
                                <?php endif; ?>
                                >
                                  <?php echo $value; ?>
                                </option>
                              <?php endforeach; ?>                              
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
