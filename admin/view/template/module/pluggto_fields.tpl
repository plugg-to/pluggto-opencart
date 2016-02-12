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
                                <?php if ($default_fields['width'] ==  "Largura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if ($default_fields['width'] ==  "Altura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if ($default_fields['width'] ==  "Comprimento"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if ($default_fields['width'] ==  "Peso"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if ($default_fields['width'] ==  "Fabricante"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if ($default_fields['width'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if ($default_fields['width'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if ($default_fields['width'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if ($default_fields['width'] ==  "Descriçao"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if ($default_fields['width'] ==  "ISBN"): ?>
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
                                <?php if ($default_fields['height'] ==  "Largura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if ($default_fields['height'] ==  "Altura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if ($default_fields['height'] ==  "Comprimento"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if ($default_fields['height'] ==  "Peso"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if ($default_fields['height'] ==  "Fabricante"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if ($default_fields['height'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if ($default_fields['height'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if ($default_fields['height'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if ($default_fields['height'] ==  "Descriçao"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if ($default_fields['height'] ==  "ISBN"): ?>
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
                                <?php if ($default_fields['length'] ==  "Largura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if ($default_fields['length'] ==  "Altura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if ($default_fields['length'] ==  "Comprimento"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if ($default_fields['length'] ==  "Peso"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if ($default_fields['length'] ==  "Fabricante"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if ($default_fields['length'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if ($default_fields['length'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if ($default_fields['length'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if ($default_fields['length'] ==  "Descriçao"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if ($default_fields['length'] ==  "ISBN"): ?>
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
                                <?php if ($default_fields['weight'] ==  "Largura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if ($default_fields['weight'] ==  "Altura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if ($default_fields['weight'] ==  "Comprimento"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if ($default_fields['weight'] ==  "Peso"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if ($default_fields['weight'] ==  "Fabricante"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if ($default_fields['weight'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if ($default_fields['weight'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if ($default_fields['weight'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if ($default_fields['weight'] ==  "Descriçao"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if ($default_fields['weight'] ==  "ISBN"): ?>
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
                                <?php if ($default_fields['brand'] ==  "Largura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if ($default_fields['brand'] ==  "Altura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if ($default_fields['brand'] ==  "Comprimento"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if ($default_fields['brand'] ==  "Peso"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if ($default_fields['brand'] ==  "Fabricante"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if ($default_fields['brand'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if ($default_fields['brand'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if ($default_fields['brand'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if ($default_fields['brand'] ==  "Descriçao"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if ($default_fields['brand'] ==  "ISBN"): ?>
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
                                <?php if ($default_fields['ean'] ==  "Largura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if ($default_fields['ean'] ==  "Altura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if ($default_fields['ean'] ==  "Comprimento"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if ($default_fields['ean'] ==  "Peso"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if ($default_fields['ean'] ==  "Fabricante"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if ($default_fields['ean'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if ($default_fields['ean'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if ($default_fields['ean'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if ($default_fields['ean'] ==  "Descriçao"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if ($default_fields['ean'] ==  "ISBN"): ?>
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
                                <?php if ($default_fields['nbm'] ==  "Largura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if ($default_fields['nbm'] ==  "Altura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if ($default_fields['nbm'] ==  "Comprimento"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if ($default_fields['nbm'] ==  "Peso"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if ($default_fields['nbm'] ==  "Fabricante"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if ($default_fields['nbm'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if ($default_fields['nbm'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if ($default_fields['nbm'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if ($default_fields['nbm'] ==  "Descriçao"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if ($default_fields['nbm'] ==  "ISBN"): ?>
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
                                <?php if ($default_fields['ncm'] ==  "Largura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if ($default_fields['ncm'] ==  "Altura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if ($default_fields['ncm'] ==  "Comprimento"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if ($default_fields['ncm'] ==  "Peso"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if ($default_fields['ncm'] ==  "Fabricante"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if ($default_fields['ncm'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if ($default_fields['ncm'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if ($default_fields['ncm'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if ($default_fields['ncm'] ==  "Descriçao"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if ($default_fields['ncm'] ==  "ISBN"): ?>
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
                                <?php if ($default_fields['description'] ==  "Largura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if ($default_fields['description'] ==  "Altura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if ($default_fields['description'] ==  "Comprimento"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if ($default_fields['description'] ==  "Peso"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if ($default_fields['description'] ==  "Fabricante"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if ($default_fields['description'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if ($default_fields['description'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if ($default_fields['description'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if ($default_fields['description'] ==  "Descriçao"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if ($default_fields['description'] ==  "ISBN"): ?>
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
                                <?php if ($default_fields['isbn'] ==  "Largura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="width"
                              >
                                Largura
                              </option>
                              <option 
                                <?php if ($default_fields['isbn'] ==  "Altura"): ?>
                                  selected
                                <?php endif; ?> 
                                value="height"
                              >
                                Altura
                              </option>
                              <option 
                                <?php if ($default_fields['isbn'] ==  "Comprimento"): ?>
                                  selected
                                <?php endif; ?> 
                                value="length"
                              >
                                Comprimento
                              </option>
                              <option 
                                <?php if ($default_fields['isbn'] ==  "Peso"): ?>
                                  selected
                                <?php endif; ?> 
                                value="weight"
                              >
                                Peso
                              </option>
                              <option 
                                <?php if ($default_fields['isbn'] ==  "Fabricante"): ?>
                                  selected
                                <?php endif; ?> 
                                value="brand"
                              >
                                Fabricante
                              </option>
                              <option 
                                <?php if ($default_fields['isbn'] ==  "ean"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ean"
                              >
                                EAN
                              </option>
                              <option 
                                <?php if ($default_fields['isbn'] ==  "nbm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="nbm"
                              >
                                NBM
                              </option>
                              <option 
                                <?php if ($default_fields['isbn'] ==  "ncm"): ?>
                                  selected
                                <?php endif; ?> 
                                value="ncm"
                              >
                                NCM
                              </option>
                              <option 
                                <?php if ($default_fields['isbn'] ==  "Descriçao"): ?>
                                  selected
                                <?php endif; ?> 
                                value="description"
                              >
                                Descriçao
                              </option>
                              <option 
                                <?php if ($default_fields['isbn'] ==  "ISBN"): ?>
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
                            <select name="fields[payment_pendent]" class="form-control">
                              <option 
                                value="pending"
                                <?php if($default_fields['payment_pendent'] == "pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pendente
                              </option>
                              <option 
                                value="processing"
                                <?php if($default_fields['payment_pendent'] == "processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processando
                              </option>
                              <option 
                                value="complete"
                                <?php if($default_fields['payment_pendent'] == "complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Completar
                              </option>
                              <option 
                                value="closed"
                                <?php if($default_fields['payment_pendent'] == "closed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Encerrado
                              </option>
                              <option 
                                value="canceled"
                                <?php if($default_fields['payment_pendent'] == "canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Cancelado
                              </option>
                              <option 
                                value="holded"
                                <?php if($default_fields['payment_pendent'] == "holded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                On Hold
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Aprovados:</label>
                          <div class="col-sm-10">
                            <select name="fields[payment_approved]" class="form-control">
                              <option 
                                value="pending"
                                <?php if($default_fields['payment_approved'] == "pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pendente
                              </option>
                              <option 
                                value="processing"
                                <?php if($default_fields['payment_approved'] == "processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processando
                              </option>
                              <option 
                                value="complete"
                                <?php if($default_fields['payment_approved'] == "complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Completar
                              </option>
                              <option 
                                value="closed"
                                <?php if($default_fields['payment_approved'] == "closed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Encerrado
                              </option>
                              <option 
                                value="canceled"
                                <?php if($default_fields['payment_approved'] == "canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Cancelado
                              </option>
                              <option 
                                value="holded"
                                <?php if($default_fields['payment_approved'] == "holded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                On Hold
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Faturados:</label>
                          <div class="col-sm-10">
                            <select name="fields[payment_invoiced]" class="form-control">
                              <option 
                                value="pending"
                                <?php if($default_fields['payment_invoiced'] == "pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pendente
                              </option>
                              <option 
                                value="processing"
                                <?php if($default_fields['payment_invoiced'] == "processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processando
                              </option>
                              <option 
                                value="complete"
                                <?php if($default_fields['payment_invoiced'] == "complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Completar
                              </option>
                              <option 
                                value="closed"
                                <?php if($default_fields['payment_invoiced'] == "closed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Encerrado
                              </option>
                              <option 
                                value="canceled"
                                <?php if($default_fields['payment_invoiced'] == "canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Cancelado
                              </option>
                              <option 
                                value="holded"
                                <?php if($default_fields['payment_invoiced'] == "holded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                On Hold
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos a serem enviados:</label>
                          <div class="col-sm-10">
                            <select name="fields[payment_shipping]" class="form-control">
                              <option 
                                value="pending"
                                <?php if($default_fields['payment_shipping'] == "pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pendente
                              </option>
                              <option 
                                value="processing"
                                <?php if($default_fields['payment_shipping'] == "processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processando
                              </option>
                              <option 
                                value="complete"
                                <?php if($default_fields['payment_shipping'] == "complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Completar
                              </option>
                              <option 
                                value="closed"
                                <?php if($default_fields['payment_shipping'] == "closed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Encerrado
                              </option>
                              <option 
                                value="canceled"
                                <?php if($default_fields['payment_shipping'] == "canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Cancelado
                              </option>
                              <option 
                                value="holded"
                                <?php if($default_fields['payment_shipping'] == "holded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                On Hold
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para pagamentos enviados:</label>
                          <div class="col-sm-10">
                            <select name="fields[payment_delivered]" class="form-control">
                              <option 
                                value="pending"
                                <?php if($default_fields['payment_delivered'] == "pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pendente
                              </option>
                              <option 
                                value="processing"
                                <?php if($default_fields['payment_delivered'] == "processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processando
                              </option>
                              <option 
                                value="complete"
                                <?php if($default_fields['payment_delivered'] == "complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Completar
                              </option>
                              <option 
                                value="closed"
                                <?php if($default_fields['payment_delivered'] == "closed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Encerrado
                              </option>
                              <option 
                                value="canceled"
                                <?php if($default_fields['payment_delivered'] == "canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Cancelado
                              </option>
                              <option 
                                value="holded"
                                <?php if($default_fields['payment_delivered'] == "holded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                On Hold
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para Pagamentos Cancelados:</label>
                          <div class="col-sm-10">
                            <select name="fields[payment_canceled]" class="form-control">
                              <option 
                                value="pending"
                                <?php if($default_fields['payment_canceled'] == "pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pendente
                              </option>
                              <option 
                                value="processing"
                                <?php if($default_fields['payment_canceled'] == "processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processando
                              </option>
                              <option 
                                value="complete"
                                <?php if($default_fields['payment_canceled'] == "complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Completar
                              </option>
                              <option 
                                value="closed"
                                <?php if($default_fields['payment_canceled'] == "closed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Encerrado
                              </option>
                              <option 
                                value="canceled"
                                <?php if($default_fields['payment_canceled'] == "canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Cancelado
                              </option>
                              <option 
                                value="holded"
                                <?php if($default_fields['payment_canceled'] == "holded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                On Hold
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-name">Status para pagamento em Revisão:</label>
                          <div class="col-sm-10">
                            <select name="fields[payment_review]" class="form-control">
                              <option 
                                value="pending"
                                <?php if($default_fields['payment_review'] == "pending"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Pendente
                              </option>
                              <option 
                                value="processing"
                                <?php if($default_fields['payment_review'] == "processing"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Processando
                              </option>
                              <option 
                                value="complete"
                                <?php if($default_fields['payment_review'] == "complete"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Completar
                              </option>
                              <option 
                                value="closed"
                                <?php if($default_fields['payment_review'] == "closed"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Encerrado
                              </option>
                              <option 
                                value="canceled"
                                <?php if($default_fields['payment_review'] == "canceled"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                Cancelado
                              </option>
                              <option 
                                value="holded"
                                <?php if($default_fields['payment_review'] == "holded"): ?>
                                  selected
                                <?php endif; ?>
                              >
                                On Hold
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
                                <?php if($default_fields['shipping_default'] == $key): ?>
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
                                <?php if($default_fields['shipping_express'] == $key): ?>
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
                                <?php if($default_fields['shipping_economic'] == $key): ?>
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
                                <?php if($default_fields['shipping_scheduled'] == $key): ?>
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
                                <?php if($default_fields['shipping_shop'] == $key): ?>
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
                                <?php if($default_fields['shipping_guaranteed'] == $key): ?>
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
                                <?php if($default_fields['shipping_hour'] == $key): ?>
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
