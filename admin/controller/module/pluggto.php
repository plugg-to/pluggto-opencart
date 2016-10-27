<?php

ini_set("display_errors", "1");
ini_set('max_execution_time', 0);
error_reporting(-1);


class ControllerModulePluggTo extends Controller {
  private $error = array();

  private $typesShippings = array(
    'sedex_sem_contrato' => 'Sedex sem Contrato',
    'sedex_com_contrato' => 'Sedex com Contrato',
    'esedex_com_contrato' => 'E-Sedex com Contrato',
    'pac_sem_contrato' => 'PAC sem Contrato',
    'pac_com_contrato' => 'PAC com Contrato',
    'sedex_10' => 'Sedex 10',
    'sedex_hoje' => 'Sedex HOJE',
    'sedex_a_cobrar' => 'Sedex a Cobrar',
    'pac_gf' => 'PAC GF',
    'carta_comercial' => 'Carta Comercial',
    'carta_comercial_registrada' => 'Carta Comercial Registrada',
    'free' => 'Frete Gratis',
    'frete_register' => 'Frete por carta registrada'
  );

  public function install() {
    $this->load->model('pluggto/pluggto');
    $this->model_pluggto_pluggto->install();
  }

  public function uninstall() {
    $this->load->model('pluggto/pluggto');
    $this->model_pluggto_pluggto->uninstall();
  }

  /**
  * Salvar configuraçoes de sicronizacao
  **/
  public function saveSettingsProductsSynchronization() {
    $this->load->model('pluggto/pluggto');

    $data = array(
      'refresh_only_stock' => 1,
      'active' => $this->request->post['active'],
      'only_actives' => $this->request->post['only_actives']
    );

    $this->session->data['alerts'] = 'Configurações salvas com sucesso!';

    $response = $this->model_pluggto_pluggto->saveSettingsProductsSynchronization($data);
    if (!$response)
      $this->session->data['alerts'] = 'Ocorreu algum erro ao salvar as configurações de sicronização';

    $this->redirect($this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
  }

  /**
  * Desligar todos o relacionamento da opencart com o pluggto
  **/
  public function offAllProductsWithPluggTo() {
    $this->load->model('pluggto/pluggto');

    $this->model_pluggto_pluggto->offAllProductsWithPluggTo();

    $this->session->data['alerts'] = 'Todos os produtos foram desvinculados!';
    $this->redirect($this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
  }

  /**
  * Importar todos os produtos do pluggto para o opencart
  **/
  public function importAllProductsToOpenCart() {
    $this->load->model('pluggto/pluggto');

    $this->model_pluggto_pluggto->saveImportationQueue();

    $this->session->data['alerts'] = 'Importação agendada com sucesso!';
    $this->redirect($this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function saveProducts($result)
  {
    foreach ($result->result as $i => $product) {
      $this->model_pluggto_pluggto->prepareToSaveInOpenCart($product);
    }

    return true;
  }

  /**
  * Verificar divegencia de estoque e preços dos produtos
  * Caso esteja divergente os valores do opencart sao considerados e enviados para o pluggto
  **/
  public function verifyStockAndPriceProducts() {
    $this->load->model('pluggto/pluggto');
    $this->load->model('catalog/product');

    $products_pluggto_relations = $this->model_pluggto_pluggto->getAllPluggToProductRelactionsOpenCart();

    foreach ($products_pluggto_relations->rows as $i => $product){
      $pluggto_product_response = $this->model_pluggto_pluggto->getProduct($product['pluggto_product_id']);
      $opencart_product_response = $this->model_catalog_product->getProduct($product['opencart_product_id']);

      if (isset($pluggto_product_response) && $pluggto_product_response->Product->quantity != $opencart_product_response['quantity']){
        $data = array(
          'action' => 'update',
          'quantity' => $opencart_product_response['quantity']
        );

        $response = $this->model_pluggto_pluggto->updateStockPluggTo($data, $product['pluggto_product_id']);
      }

      if (isset($pluggto_product_response) && $pluggto_product_response->Product->price != $opencart_product_response['price']){
        $data = array(
          'price' => $opencart_product_response['price']
        );

        $response = $this->model_pluggto_pluggto->updateTo($data, $product['pluggto_product_id']);
      }
    }

    $this->session->data['alerts'] = 'Verificação feita com sucesso!';
    $this->redirect($this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function exportAllProductsToPluggTo() {
    $this->load->model('pluggto/pluggto');

    $this->model_pluggto_pluggto->saveExportationQueue();

    $this->session->data['alerts'] = 'Exportação agendada com sucesso!';
    $this->redirect($this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function getSpecialPriceProductToPluggTo($product_id) {
    $specialPrice = $this->model_catalog_product->getProductSpecials($product_id);
    $special = $specialPrice['special'];
    return end($special);
  }

  public function getPhotosToSaveInOpenCart($product_id, $image_main) {
    $images = $this->model_catalog_product->getProductImages($product_id);

    $response = array(
      array(
        'url' =>  'http://' . $_SERVER['SERVER_NAME'] . '/image/cache/' . $image_main,
        'remove' => true
      ),
      array(
        'url'   => 'http://' . $_SERVER['SERVER_NAME'] . '/image/cache/' . $image_main,
        'title' => 'Imagem principal do produto',
        'order' => 0
      )
    );

    return $response;
  }

  public function getVariationsToSaveInOpenCart($product_id) {
    $product = $this->model_catalog_product->getProduct($product_id);
    $options = $this->model_catalog_product->getProductOptions($product_id);

    $response = array();
    foreach ($options as $i => $option) {
      foreach ($option['product_option_value'] as $item) {
        $response[] = array(
          'name'     => $product['name'],
          'external' => $option['product_option_id'],
          'quantity' => $item['quantity'],
          'special_price' => $this->getSpecialPriceProductToPluggTo($product_id),
          'price' => ($item['price_prefix'] == '+') ? $product['price'] + $item['price'] : $product['price'] - $item['price'] ,
          'sku' => 'sku-' . $option['product_option_id'],
          'ean' => '',
          'photos' => array(),
          'attributes' => array(),
          'dimesion' => array(
            'length' => $product['length'],
            'width'  => $product['width'],
            'height' => $product['height'],
            'weight' => ($item['weight_prefix'] == '+') ? $item['weight'] + $product['weight'] : $item['weight'] - $product['weight'],
          )
        );
      }
    }

    return $response;
  }

  public function getAtrributesToSaveInOpenCart($product_id) {
    $this->load->model('catalog/product');

    $product    = $this->model_catalog_product->getProduct($product_id);
    $attributes = $this->model_catalog_product->getProductAttributes($product_id);

    $response = array();

    foreach ($attributes as $i => $attribute) {
      if (isset($attribute['attribute']) && !empty($attribute['attribute']))
      {
        foreach ($attribute['attribute'] as $i => $attr) {
          $response[] = array(
            'code'  => $attr['attribute_id'],
            'label' => $attr['text'],
            'value' => array(
              'code'  => $attr['attribute_id'],
              'label' => $attr['text'],
            )
          );
        }

        continue;
      }

      $response[] = array(
        'code'  => $attribute['attribute_id'],
        'label' => $attribute['product_attribute_description'][1]['text'],
        'value' => array(
          'code'  => $attribute['attribute_id'],
          'label' => $attribute['product_attribute_description'][1]['text'],
        )
      );
    }

    return $response;
  }

  public function saveFieldsLinkage(){
    $this->load->model('pluggto/pluggto');

    $fields = $this->request->post['fields'];

    foreach ($fields as $key => $value) {
      $this->model_pluggto_pluggto->saveField($key, $value);
    }

    $this->session->data['alerts'] = 'Atrelamento salvo com sucesso!';

    $this->redirect($this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function pluggTransparent()  {

    $this->load->model('pluggto/pluggto');

    $url = 'https://core.plugg.to/users/autologin/'. $this->model_pluggto_pluggto->getAccesstoken() . '/mercadolivre';

    $this->children = array(
      'common/header',
      'common/footer'
    );

    $this->load->model('design/layout');

    $this->data['layouts'] = $this->model_design_layout->getLayouts();

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/pluggto_transparent.tpl')) { //if file exists in your current template folder
       $this->template = $this->config->get('config_template') . '/template/module/pluggto_transparent.tpl'; //get it
    } else {
       $this->template = '/module/pluggto_transparent.tpl'; //or get the file from the default folder
    }

    $this->data['url'] = $url;

    $this->response->setOutput($this->render());
  }

  public function index() {
    $this->template = 'module/pluggto.tpl';
    $this->language->load('module/pluggto');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('pluggto/pluggto');

    if (isset($this->request->post['checkCredentials'])) {

      if ($this->model_pluggto_pluggto->getAccesstoken()) {
        $this->data['access_status'] = "Credencials logadas com sucesso.";
      } else {
        $this->data['access_status'] = "Credencias incorretas.";
      }
    }

    if (isset($this->request->post['api_user'])) {
      $this->model_pluggto_pluggto->setCredentials($this->request->post['api_user'], $this->request->post['api_secret'], $this->request->post['client_id'], $this->request->post['client_secret']);
    }

    $credentials = $this->model_pluggto_pluggto->getCredentials();
    if (empty($credentials)) {
      $credentials['api_user'] = "";
      $credentials['api_secret'] = "";
      $credentials['client_id'] = "";
      $credentials['client_secret'] = "";
    }

    $settingsProductsSynchronization = $this->model_pluggto_pluggto->getSettingsProductsSynchronization();
    $this->data['settingsProductsSynchronization'] = $settingsProductsSynchronization;

    $this->data['heading_title'] = $this->language->get('heading_title');

    $this->data['credentials'] = $credentials;

    $this->data['breadcrumbs'] = array();
    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_module'),
      'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('module/bestseller', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $this->data['action_products'] = $this->url->link('module/pluggto/saveSettingsProductsSynchronization', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['link_import_all_products_to_opencart'] = $this->url->link('module/pluggto/importAllProductsToOpenCart', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['link_off_all_products_pluggto'] = $this->url->link('module/pluggto/offAllProductsWithPluggTo', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['link_verify_stock_and_price_products'] = $this->url->link('module/pluggto/verifyStockAndPriceProducts', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['link_export_all_products_to_pluggto'] = $this->url->link('module/pluggto/exportAllProductsToPluggTo', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['action_basic_fields'] = $this->url->link('module/pluggto/saveFieldsLinkage', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['link_basic_fields'] = $this->url->link('module/pluggto/settingsBasicFields', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['load_queue'] = $this->url->link('module/pluggto/loadqueue', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['link_log_queue'] = $this->url->link('module/pluggto/linkLogQueue', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['force_sync_products'] = $this->url->link('module/pluggto/listAllProductsToForceSync', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['linkPluggTransparent'] = $this->url->link('module/pluggto/pluggTransparent', 'token=' . $this->session->data['token'], 'SSL');

    $this->data['action'] = $this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

    if (isset($this->request->post['store_admin'])) {
      $this->data['store_admin'] = $this->request->post['store_admin'];
    } else {
      $this->data['store_admin'] = $this->config->get('store_admin');
    }

    if (isset($this->request->post['store_status'])) {
      $this->data['store_status'] = $this->request->post['store_status'];
    } else {
      $this->data['store_status'] = $this->config->get('store_status');
    }

    $this->data['alerts'] = $this->session->data['alerts'];
    $this->session->data['alerts'] = '';

    $this->data['button_pull'] = $this->url->link('module/pluggto/pullModule', 'token=' . $this->session->data['token'], 'SSL');;
    $this->data['button_cancel'] = $this->language->get('button_cancel');

    $this->data['button_add_module'] = $this->language->get('button_add_module');
    $this->data['button_remove'] = $this->language->get('button_remove');

  	$this->children = array(
  		'common/header',
  		'common/footer'
  	);

  	$this->load->model('design/layout');

  	$this->data['layouts'] = $this->model_design_layout->getLayouts();

  	$this->response->setOutput($this->render());
  }

  public function settingsBasicFields() {
    $this->template = 'module/pluggto.tpl';
    $this->language->load('module/pluggto');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('pluggto/pluggto');

    $this->data['heading_title'] = $this->language->get('heading_title');

    $this->data['breadcrumbs'] = array();
    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_module'),
      'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('module/bestseller', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $this->data['action_basic_fields']   = $this->url->link('module/pluggto/saveFieldsLinkage', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['cancel']                = $this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL');

    $this->data['alerts']                = $this->session->data['alerts'];
    $this->session->data['alerts'] = '';

    $this->data['button_save']   = $this->language->get('button_save');
    $this->data['button_cancel'] = $this->language->get('button_cancel');

    $this->data['types_shippings'] = $this->typesShippings;

    $this->data['default_fields']  = $this->model_pluggto_pluggto->getAllDefaultsFields();

    $this->data['status_opencart'] = $this->model_pluggto_pluggto->getStatusOpenCart();

    $this->children = array(
      'common/header',
      'common/footer'
    );

    $this->load->model('design/layout');

    $this->data['layouts'] = $this->model_design_layout->getLayouts();

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/pluggto_fields.tpl')) { //if file exists in your current template folder
       $this->template = $this->config->get('config_template') . '/template/module/pluggto_fields.tpl'; //get it
    } else {
       $this->template = '/module/pluggto_fields.tpl'; //or get the file from the default folder
    }

    $this->response->setOutput($this->render());
  }

  public function listAllProductsToForceSync(){
    $this->template = 'module/pluggto.tpl';
    $this->language->load('module/pluggto');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('pluggto/pluggto');
    $this->load->model('catalog/product');

    $this->data['heading_title'] = $this->language->get('heading_title');

    $this->data['breadcrumbs'] = array();
    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_module'),
      'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('module/bestseller', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $this->data['action_basic_fields']   = $this->url->link('module/pluggto/saveFieldsLinkage', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['cancel']                = $this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL');

    $this->data['token'] = $this->session->data['token'];

    $this->data['alerts']                = $this->session->data['alerts'];
    $this->session->data['alerts'] = '';

    $this->data['products']    = $this->model_catalog_product->getProducts(array('enabled' => 1));

    $this->children = array(
      'common/header',
      'common/footer'
    );

    $this->load->model('design/layout');

    $this->data['layouts'] = $this->model_design_layout->getLayouts();

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/pluggto_products_sync.tpl')) { //if file exists in your current template folder
       $this->template = $this->config->get('config_template') . '/template/module/pluggto_products_sync.tpl'; //get it
    } else {
       $this->template = '/module/pluggto_products_sync.tpl'; //or get the file from the default folder
    }

    $this->response->setOutput($this->render());
  }

  public function linkLogQueue(){
    $this->template = 'module/pluggto.tpl';
    $this->language->load('module/pluggto');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('pluggto/pluggto');

    $this->data['heading_title'] = $this->language->get('heading_title');

    $this->data['breadcrumbs'] = array();
    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_module'),
      'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('module/bestseller', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $this->data['action_basic_fields'] = $this->url->link('module/pluggto/saveFieldsLinkage', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['cancel'] = $this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL');

    $this->data['alerts'] = $this->session->data['alerts'];
    $this->session->data['alerts'] = '';

    $this->data['button_save'] = $this->language->get('button_save');
    $this->data['button_cancel'] = $this->language->get('button_cancel');

    $this->data['queues'] = $this->model_pluggto_pluggto->getAllItemsInQueues();

    $this->children = array(
      'common/header',
      'common/footer'
    );

    $this->load->model('design/layout');

    $this->data['layouts'] = $this->model_design_layout->getLayouts();
    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/pluggto_queue.tpl')) { //if file exists in your current template folder
       $this->template = $this->config->get('config_template') . '/template/module/pluggto_queue.tpl'; //get it
    } else {
       $this->template = '/module/pluggto_queue.tpl'; //or get the file from the default folder
    }

    $this->response->setOutput($this->render());
  }

  protected function validate() {
    if (!$this->user->hasPermission('modify', 'module/store')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    return !$this->error;
  }

  public function loadQueue(){
    $this->template = 'module/pluggto_load_queue.tpl';
    $this->language->load('module/pluggto');
    $this->load->model('pluggto/pluggto');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->session->data['alerts'] = '';

    $this->data['breadcrumbs'] = array();
    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_module'),
      'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('module/bestseller', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $this->data['heading_title'] = $this->language->get('heading_title');
    $this->data['button_save']   = $this->language->get('button_save');
    $this->data['button_cancel'] = $this->language->get('button_cancel');
    $this->data['alerts']        = $this->session->data['alerts'];
    $this->data['cancel']        = $this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['queue']         = $this->model_pluggto_pluggto->getNotifications();

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/pluggto_load_queue.tpl')) { //if file exists in your current template folder
       $this->template = $this->config->get('config_template') . '/template/module/pluggto_load_queue.tpl'; //get it
    } else {
       $this->template = '/module/pluggto_load_queue.tpl'; //or get the file from the default folder
    }

    $this->children = array(
      'common/header',
      'common/footer'
    );

    $this->load->model('design/layout');

    $this->data['layouts'] = $this->model_design_layout->getLayouts();

    $this->response->setOutput($this->render());
  }

}

?>