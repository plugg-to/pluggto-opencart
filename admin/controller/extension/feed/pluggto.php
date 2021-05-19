<?php

ini_set("display_errors", "1");
ini_set('max_execution_time', 0);
error_reporting(-1);

class ControllerExtensionFeedPluggto extends Controller {
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
    $this->load->model('extension/feed/pluggto');

    $this->model_extension_feed_pluggto->install();
  }

  public function uninstall() {
    $this->load->model('extension/feed/pluggto');

    $this->model_extension_feed_pluggto->uninstall();
  }

  /**
  * Salvar configuraçoes de sicronizacao
  **/
  public function saveSettingsProductsSynchronization() {
    $this->load->model('extension/feed/pluggto');

    $data = array(
			'refresh_only_stock' => $this->request->post['refresh_only_stock'],
			'active' => $this->request->post['active'],
		);
        
    $this->session->data['alerts'] = 'Configurações salvas com sucesso!';
    
    $response = $this->model_extension_feed_pluggto->saveSettingsProductsSynchronization($data);
    if (!$response)    
      $this->session->data['alerts'] = 'Ocorreu algum erro ao salvar as configurações de sicronização';

    $this->response->redirect($redirect = $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true));
  }

  /**
  * Desligar todos o relacionamento da opencart com o pluggto
  **/
  public function offAllProductsWithPluggTo() {
    $this->load->model('extension/feed/pluggto');

    $this->model_extension_feed_pluggto->offAllProductsWithPluggTo();

    $this->session->data['alerts'] = 'Todos os produtos foram desvinculados!';
    $this->response->redirect($redirect = $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true));
  }

  /**
  * Importar todos os produtos do pluggto para o opencart
  **/
  public function importAllProductsToOpenCart() {
    $this->load->model('extension/feed/pluggto');

    $this->model_extension_feed_pluggto->saveImportationQueue();

    $this->session->data['alerts'] = 'Importação agendada com sucesso!';
    $this->response->redirect($redirect = $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true));
  }

  public function saveProducts($result)
  {
    foreach ($result->result as $i => $product) {
      $this->model_extension_feed_pluggto->prepareToSaveInOpenCart($product);
    }

    return true;
  }

  /**
  * Verificar divegencia de estoque e preços dos produtos
  * Caso esteja divergente os valores do opencart sao considerados e enviados para o pluggto
  **/
  public function verifyStockAndPriceProducts() {
    $this->load->model('extension/feed/pluggto');
    $this->load->model('catalog/product');

    $products_pluggto_relations = $this->model_extension_feed_pluggto->getAllPluggToProductRelactionsOpenCart();

    foreach ($products_pluggto_relations->rows as $i => $product){
      $pluggto_product_response = $this->model_extension_feed_pluggto->getProduct($product['pluggto_product_id']);
      $opencart_product_response = $this->model_catalog_product->getProduct($product['opencart_product_id']);

      if (isset($pluggto_product_response) && $pluggto_product_response->result[$i]->Product->quantity != $opencart_product_response['quantity']){
        $data = array(
          'action' => 'update',
          'quantity' => $opencart_product_response['quantity']
        );

        $response = $this->model_extension_feed_pluggto->updateStockPluggTo($data, $product['pluggto_product_id']);
      }
      
      if (isset($pluggto_product_response) && $pluggto_product_response->result[$i]->Product->price != $opencart_product_response['price']){
        $data = array(
          'price' => $opencart_product_response['price']
        );

        $response = $this->model_extension_feed_pluggto->updateTo($data, $product['pluggto_product_id']);
      }
    }

    $this->session->data['alerts'] = 'Verificação feita com sucesso!';
    $this->response->redirect($redirect = $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true));
  }

  public function exportAllProductsToPluggTo() {
    $this->load->model('extension/feed/pluggto');

    $this->model_extension_feed_pluggto->saveExportationQueue();

    $this->session->data['alerts'] = 'Exportação agendada com sucesso!';
    $this->response->redirect($redirect = $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true));
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
    $this->load->model('extension/feed/pluggto');

    $fields = $this->request->post['fields'];

    foreach ($fields as $key => $value) {
      $this->model_extension_feed_pluggto->saveField($key, $value);
    }

    $this->session->data['alerts'] = 'Atrelamento salvo com sucesso!';

    $this->response->redirect($redirect = $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true));
  }

  public function pluggTransparent() {
    $this->load->model('extension/feed/pluggto');

    $url = 'https://core.plugg.to/users/autologin/'. $this->model_extension_feed_pluggto->getAccesstoken() . '/mercadolivre';

    $this->children = array(
      'common/header',
      'common/footer'
    );

    $this->load->model('design/layout');

    $this->data['layouts'] = $this->model_design_layout->getLayouts();

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/feed/pluggto_transparent')) { //if file exists in your current template folder
       $this->template = $this->config->get('config_template') . '/template/extension/feed/pluggto_transparent'; //get it
    } else {
       $this->template = '/extension/feed/pluggto_transparent'; //or get the file from the default folder
    }

    $this->data['url'] = $url;

    $this->response->setOutput($this->render());
  }

  public function index() {
    $this->template = 'extension/feed/pluggto';
    
    $this->language->load('extension/feed/pluggto');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('extension/feed/pluggto');

    if (isset($this->request->post['checkCredentials'])) {

      if ($this->model_extension_feed_pluggto->getAccesstoken()) {
        $this->data['access_status'] = "Credencials logadas com sucesso.";
      } else {
        $this->data['access_status'] = "Credencias incorretas.";
      }
    }

    if (isset($this->request->post['api_user'])) {
      $this->model_extension_feed_pluggto->setCredentials($this->request->post['api_user'], $this->request->post['api_secret'], $this->request->post['client_id'], $this->request->post['client_secret']);
    }

    $credentials = $this->model_extension_feed_pluggto->getCredentials();
    if (empty($credentials)) {
      $credentials['api_user'] = "";
      $credentials['api_secret'] = "";
      $credentials['client_id'] = "";
      $credentials['client_secret'] = "";
    }

    $settingsProductsSynchronization = $this->model_extension_feed_pluggto->getSettingsProductsSynchronization();
    $data['settingsProductsSynchronization'] = $settingsProductsSynchronization;

    $data['heading_title'] = $this->language->get('heading_title');

    $data['credentials'] = $credentials;

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']. '&type=module', true),
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_module'),
      'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']. '&type=module', true),
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true),
    );
    
    $data['action_products'] = $this->url->link('extension/feed/pluggto/saveSettingsProductsSynchronization', 'user_token=' . $this->session->data['user_token']. '&type=module', true);
    $data['link_import_all_products_to_opencart'] = $this->url->link('extension/feed/pluggto/importAllProductsToOpenCart', 'user_token=' . $this->session->data['user_token']. '&type=module', true);
    $data['link_off_all_products_pluggto'] = $this->url->link('extension/feed/pluggto/offAllProductsWithPluggTo', 'user_token=' . $this->session->data['user_token']. '&type=module', true);
    $data['link_verify_stock_and_price_products'] = $this->url->link('extension/feed/pluggto/verifyStockAndPriceProducts', 'user_token=' . $this->session->data['user_token']. '&type=module', true);
    $data['link_export_all_products_to_pluggto'] = $this->url->link('extension/feed/pluggto/exportAllProductsToPluggTo', 'user_token=' . $this->session->data['user_token']. '&type=module', true);
    $data['action_basic_fields'] = $this->url->link('extension/feed/pluggto/saveFieldsLinkage', 'user_token=' . $this->session->data['user_token']. '&type=module', true);
    $data['link_basic_fields'] = $this->url->link('extension/feed/pluggto/settingsBasicFields', 'user_token=' . $this->session->data['user_token']. '&type=module', true);
    $data['load_queue'] = $this->url->link('extension/feed/pluggto/loadqueue', 'user_token=' . $this->session->data['user_token']. '&type=module', true);
    $data['link_log_queue'] = $this->url->link('extension/feed/pluggto/linkLogQueue', 'user_token=' . $this->session->data['user_token']. '&type=module', true);
    $data['force_sync_products'] = $this->url->link('extension/feed/pluggto/listAllProductsToForceSync', 'user_token=' . $this->session->data['user_token']. '&type=module', true);
    $data['linkPluggTransparent'] = $this->url->link('extension/feed/pluggto/pluggTransparent', 'user_token=' . $this->session->data['user_token']. '&type=module', true);

    $data['action'] = $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true);
    $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']. '&type=module', true);

    if (isset($this->request->post['store_admin'])) {
      $data['store_admin'] = $this->request->post['store_admin'];
    } else {
      $data['store_admin'] = $this->config->get('store_admin');
    }

    if (isset($this->request->post['store_status'])) {
      $data['store_status'] = $this->request->post['store_status'];
    } else {
      $data['store_status'] = $this->config->get('store_status');
    }

    if (isset($this->session->data['alerts'])) {
      $data['alerts'] = $this->session->data['alerts'];
    } else {
      $data['alerts'] = '';
    }
    
    $this->session->data['alerts'] = '';

    $data['button_pull'] = $this->url->link('extension/feed/pluggto/pullModule', 'user_token=' . $this->session->data['user_token']. '&type=module', true);;
    $data['button_cancel'] = $this->language->get('button_cancel');

    $data['button_add_module'] = $this->language->get('button_add_module');
    $data['button_remove'] = $this->language->get('button_remove');

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/feed/pluggto', $data));
  }

  public function settingsBasicFields() {
    $this->template = 'extension/feed/pluggto';
    $this->language->load('extension/feed/pluggto');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('extension/feed/pluggto');

    $data['heading_title'] = $this->language->get('heading_title');

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']. '&type=module', true),
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_module'),
      'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']. '&type=module', true),
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true),
    );

    $data['action_basic_fields']   = $this->url->link('extension/feed/pluggto/saveFieldsLinkage', 'user_token=' . $this->session->data['user_token']. '&type=module', true);
    $data['cancel']                = $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true);

    $data['alerts']                = $this->session->data['alerts'];
    $this->session->data['alerts'] = '';

    $data['button_save']   = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');

    $data['header']      = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer']      = $this->load->controller('common/footer');

    $data['types_shippings'] = $this->typesShippings;

    $this->load->model('customer/custom_field');
    
    $data['custom_fields'] = $this->model_customer_custom_field->getCustomFields();

    $data['default_fields']  = $this->model_extension_feed_pluggto->getAllDefaultsFields();

    $data['status_opencart'] = $this->model_extension_feed_pluggto->getStatusOpenCart();
    
    $this->response->setOutput($this->load->view('extension/feed/pluggto_fields', $data)); // aqui
  }

  public function listAllProductsToForceSync(){
    $this->template = 'extension/feed/pluggto';
    $this->language->load('extension/feed/pluggto');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('extension/feed/pluggto');
    $this->load->model('catalog/product');

    $data['heading_title'] = $this->language->get('heading_title');

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']. '&type=module', true),
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_module'),
      'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']. '&type=module', true),
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true),
    );

    $data['action_basic_fields']   = $this->url->link('extension/feed/pluggto/saveFieldsLinkage', 'user_token=' . $this->session->data['user_token']. '&type=module', true);
    $data['cancel']                = $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true);

    $data['user_token'] = $this->session->data['user_token'];
	
	// API login
    $this->load->model('user/api');

    $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

    if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
      $session = new Session($this->config->get('session_engine'), $this->registry);
      
      $session->start();
          
      $this->model_user_api->deleteApiSessionBySessonId($session->getId());
      
      $this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
      
      $session->data['api_id'] = $api_info['api_id'];

      $data['api_token'] = $session->getId();
    } else {
      $data['api_token'] = '';
    }

    $data['alerts']                = $this->session->data['alerts'];
    $this->session->data['alerts'] = '';

    $data['header']      = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer']      = $this->load->controller('common/footer');

    $data['products']    = $this->model_catalog_product->getProducts();

    $this->response->setOutput($this->load->view('extension/feed/pluggto_products_sync', $data)); // aqui
  }

  public function linkLogQueue(){
    $this->template = 'extension/feed/pluggto';
    $this->language->load('extension/feed/pluggto');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('extension/feed/pluggto');

    $data['heading_title'] = $this->language->get('heading_title');

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']. '&type=module', true),
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_module'),
      'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']. '&type=module', true),
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true),
    );

    $data['action_basic_fields'] = $this->url->link('extension/feed/pluggto/saveFieldsLinkage', 'user_token=' . $this->session->data['user_token']. '&type=module', true);
    $data['cancel'] = $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true);

    $data['alerts'] = $this->session->data['alerts'];
    $this->session->data['alerts'] = '';

    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $data['queues'] = $this->model_extension_feed_pluggto->getAllItemsInQueues();

    $this->response->setOutput($this->load->view('extension/feed/pluggto_queue', $data));
  }

  protected function validate() {
    if (!$this->user->hasPermission('modify', 'extension/module/store')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    return !$this->error;
  }

  public function loadQueue(){
    $this->template = 'extension/feed/pluggto_load_queue';
    $this->language->load('extension/feed/pluggto');
    $this->load->model('extension/feed/pluggto');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->session->data['alerts'] = '';

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']. '&type=module', true),
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_module'),
      'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']. '&type=module', true),
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true),
    );

    $data['header']        = $this->load->controller('common/header');
    $data['heading_title'] = $this->language->get('heading_title');
    $data['button_save']   = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');
    $data['column_left']   = $this->load->controller('common/column_left');
    $data['alerts']        = $this->session->data['alerts'];
    $data['cancel']        = $this->url->link('extension/feed/pluggto', 'user_token=' . $this->session->data['user_token']. '&type=module', true);
    $data['footer']        = $this->load->controller('common/footer');
    $data['queue']         = $this->model_extension_feed_pluggto->getNotifications(500, 'all');
    
    $this->response->setOutput($this->load->view('extension/feed/pluggto_load_queue', $data));
  }

}

?>