<?php echo $header; ?>

<?php echo $column_left; ?>

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
          <div class="tab-pane fade in" id="A">
            <div class="row" style="width: 99%;">
              <form action="<?php echo $action_basic_fields; ?>" method="post" enctype="multipart/form-data" id="form-banner" class="form-horizontal">
                <div class="col-md-12" style="margin-left: 20px;">
                  <!-- <div class="panel panel-default">
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
                              <option value="">Não disponivel</option>
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
                              <option value="">Não disponivel</option>
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
                              <option value="">Não disponivel</option>
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
                              <option value="">Não disponivel</option>
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
                              <option value="">Não disponivel</option>
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
                              <option value="">Não disponivel</option>
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
                              <option value="">Não disponivel</option>
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
                              <option value="">Não disponivel</option>
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
                              <option value="">Não disponivel</option>
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
                              <option value="">Não disponivel</option>
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
                  </div> -->
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
                              <option value="">Não disponivel</option>
                              <?php foreach ($status_opencart as $status): ?>
                                <option
                                  value="<?php echo $status['name']; ?>"
                                  <?php if(@$default_fields['pending'] == $status['name']): ?>
                                    selected
                                  <?php endif; ?>
                                >
                                  <?php echo $status['name']; ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Pagos:</label>
                          <div class="col-sm-10">
                            <select name="fields[paid]" class="form-control">
                              <option value="">Não disponivel</option>
                              <?php foreach ($status_opencart as $status): ?>
                                <option
                                  value="<?php echo $status['name']; ?>"
                                  <?php if(@$default_fields['paid'] == $status['name']): ?>
                                    selected
                                  <?php endif; ?>
                                >
                                  <?php echo $status['name']; ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Aprovados:</label>
                          <div class="col-sm-10">
                            <select name="fields[approved]" class="form-control">
                              <option value="">Não disponivel</option>
                              <?php foreach ($status_opencart as $status): ?>
                                <option
                                  value="<?php echo $status['name']; ?>"
                                  <?php if(@$default_fields['approved'] == $status['name']): ?>
                                    selected
                                  <?php endif; ?>
                                >
                                  <?php echo $status['name']; ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Aguardando Fatura:</label>
                          <div class="col-sm-10">
                            <select name="fields[waiting_invoice]" class="form-control">
                              <option value="">Não disponivel</option>
                              <?php foreach ($status_opencart as $status): ?>
                                <option
                                  value="<?php echo $status['name']; ?>"
                                  <?php if(@$default_fields['waiting_invoice'] == $status['name']): ?>
                                    selected
                                  <?php endif; ?>
                                >
                                  <?php echo $status['name']; ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Faturada:</label>
                          <div class="col-sm-10">
                            <select name="fields[invoiced]" class="form-control">
                              <option value="">Não disponivel</option>
                              <?php foreach ($status_opencart as $status): ?>
                                <option
                                  value="<?php echo $status['name']; ?>"
                                  <?php if(@$default_fields['invoiced'] == $status['name']): ?>
                                    selected
                                  <?php endif; ?>
                                >
                                  <?php echo $status['name']; ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Erro na Fatura:</label>
                          <div class="col-sm-10">
                            <select name="fields[invoice_error]" class="form-control">
                              <option value="">Não disponivel</option>
                              <?php foreach ($status_opencart as $status): ?>
                                <option
                                  value="<?php echo $status['name']; ?>"
                                  <?php if(@$default_fields['invoice_error'] == $status['name']): ?>
                                    selected
                                  <?php endif; ?>
                                >
                                  <?php echo $status['name']; ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Entrega Informada:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_informed]" class="form-control">
                              <option value="">Não disponivel</option>
                              <?php foreach ($status_opencart as $status): ?>
                                <option
                                  value="<?php echo $status['name']; ?>"
                                  <?php if(@$default_fields['shipping_informed'] == $status['name']): ?>
                                    selected
                                  <?php endif; ?>
                                >
                                  <?php echo $status['name']; ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Entregue:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipped]" class="form-control">
                              <option value="">Não disponivel</option>
                              <?php foreach ($status_opencart as $status): ?>
                                <option
                                  value="<?php echo $status['name']; ?>"
                                  <?php if(@$default_fields['shipped'] == $status['name']): ?>
                                    selected
                                  <?php endif; ?>
                                >
                                  <?php echo $status['name']; ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Erro na entrega:</label>
                          <div class="col-sm-10">
                            <select name="fields[shipping_error]" class="form-control">
                              <option value="">Não disponivel</option>
                              <?php foreach ($status_opencart as $status): ?>
                                <option
                                  value="<?php echo $status['name']; ?>"
                                  <?php if(@$default_fields['shipping_error'] == $status['name']): ?>
                                    selected
                                  <?php endif; ?>
                                >
                                  <?php echo $status['name']; ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Entregues:</label>
                          <div class="col-sm-10">
                            <select name="fields[delivered]" class="form-control">
                              <option value="">Não disponivel</option>
                              <?php foreach ($status_opencart as $status): ?>
                                <option
                                  value="<?php echo $status['name']; ?>"
                                  <?php if(@$default_fields['delivered'] == $status['name']): ?>
                                    selected
                                  <?php endif; ?>
                                >
                                  <?php echo $status['name']; ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Cancelados:</label>
                          <div class="col-sm-10">
                            <select name="fields[canceled]" class="form-control">
                              <option value="">Não disponivel</option>
                              <?php foreach ($status_opencart as $status): ?>
                                <option
                                  value="<?php echo $status['name']; ?>"
                                  <?php if(@$default_fields['canceled'] == $status['name']): ?>
                                    selected
                                  <?php endif; ?>
                                >
                                  <?php echo $status['name']; ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos que precisam ser revisados:</label>
                          <div class="col-sm-10">
                            <select name="fields[under_review]" class="form-control">
                              <option value="">Não disponivel</option>
                              <?php foreach ($status_opencart as $status): ?>
                                <option
                                  value="<?php echo $status['name']; ?>"
                                  <?php if(@$default_fields['under_review'] == $status['name']): ?>
                                    selected
                                  <?php endif; ?>
                                >
                                  <?php echo $status['name']; ?>
                                </option>
                              <?php endforeach; ?>
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
                              <option value="">Não disponivel</option>
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
                              <option value="">Não disponivel</option>
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
                              <option value="">Não disponivel</option>
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
                              <option value="">Não disponivel</option>
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
                              <option value="">Não disponivel</option>
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
                              <option value="">Não disponivel</option>
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
                              <option value="">Não disponivel</option>
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


<?php echo $footer; ?>