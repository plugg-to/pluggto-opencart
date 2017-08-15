<?php echo $header; ?>

<?php echo $column_left; ?>

<div id="content">
    <div class="page-header" style="margin-top: 5px;">
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
                  <i class="fa fa-pencil"></i> Atrelamento de Campos Customizaveis
                </h3>
              </div>
              <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-name">Endereço Complemento:</label>
                  <div class="col-sm-10">
                    <select name="fields[complement]" class="form-control">
                      <option value="">Não disponivel</option>
                      <?php foreach ($custom_fields as $key => $value): ?>
                        <option
                        value="<?php echo $value['custom_field_id']; ?>"
                        <?php if(@$default_fields['complement'] == $value['custom_field_id']): ?>
                          selected
                        <?php endif; ?>
                        >
                          <?php echo $value['name']; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-name">Cliente CPF:</label>
                  <div class="col-sm-10">
                    <select name="fields[cpf]" class="form-control">
                      <option value="">Não disponivel</option>
                      <?php foreach ($custom_fields as $key => $value): ?>
                        <option
                        value="<?php echo $value['custom_field_id']; ?>"
                        <?php if(@$default_fields['cpf'] == $value['custom_field_id']): ?>
                          selected
                        <?php endif; ?>
                        >
                          <?php echo $value['name']; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-name">Endereço Nº:</label>
                  <div class="col-sm-10">
                    <select name="fields[number]" class="form-control">
                      <option value="">Não disponivel</option>
                      <?php foreach ($custom_fields as $key => $value): ?>
                        <option
                        value="<?php echo $value['custom_field_id']; ?>"
                        <?php if(@$default_fields['number'] == $value['custom_field_id']): ?>
                          selected
                        <?php endif; ?>
                        >
                          <?php echo $value['name']; ?>
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
