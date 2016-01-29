<?php
class ControllerModulePluggTo extends Controller {
  private $error = array();

  private $typesShippings = [
    'flatrate' => 'Fixed',
    'freeshipping' => 'Free',
    'tablerate_bestway' => 'Table Rate',
    'dhl_IE' => 'International Express',
    'dhl_E' => 'AT">Express Saturday',
    'dhl_E' => '0:30AM">Express 10:30 AM',
    'dhl_E' => 'Express',
    'dhl_N' => 'Next Afternoon',
    'dhl_S' => 'Second Day Service',
    'dhl_G' => 'Ground',
    'fedex_EUROPE_FIRST_INTERNATIONAL_PRIORITY' => 'Europe First Priority',
    'fedex_FEDEX_1_DAY_FREIGHT' => '1 Day Freight',
    'fedex_FEDEX_2_DAY_FREIGHT' => '2 Day Freight',
    'fedex_FEDEX_2_DAY' => '2 Day',
    'fedex_FEDEX_2_DAY_AM' => '2 Day AM',
    'fedex_FEDEX_3_DAY_FREIGHT' => '3 Day Freight',
    'fedex_FEDEX_EXPRESS_SAVER' => 'Express Saver',
    'fedex_FEDEX_GROUND' => 'Ground',
    'fedex_FIRST_OVERNIGHT' => 'First Overnight',
    'fedex_GROUND_HOME_DELIVERY' => 'Home Delivery',
    'fedex_INTERNATIONAL_ECONOMY' => 'International Economy',
    'fedex_INTERNATIONAL_ECONOMY_FREIGHT' => 'Intl Economy Freight',
    'fedex_INTERNATIONAL_FIRST' => 'International First',
    'fedex_INTERNATIONAL_GROUND' => 'International Ground',
    'fedex_INTERNATIONAL_PRIORITY' => 'International Priority',
    'fedex_INTERNATIONAL_PRIORITY_FREIGHT' => 'Intl Priority Freight',
    'fedex_PRIORITY_OVERNIGHT' => 'Priority Overnight',
    'fedex_SMART_POST' => 'Smart Post',
    'fedex_STANDARD_OVERNIGHT' => 'Standard Overnight',
    'fedex_FEDEX_FREIGHT' => 'Freight',
    'fedex_FEDEX_NATIONAL_FREIGHT' => 'National Freight',
    'ups_1DM' => 'Next Day Air Early AM',
    'ups_1DML' => 'Next Day Air Early AM Letter',
    'ups_1DA' => 'Next Day Air',
    'ups_1DAL' => 'Next Day Air Letter',
    'ups_1DAPI' => 'Next Day Air Intra (Puerto Rico)',
    'ups_1DP' => 'Next Day Air Saver',
    'ups_1DPL' => 'Next Day Air Saver Letter',
    'ups_2DM' => '2nd Day Air AM',
    'ups_2DML' => '2nd Day Air AM Letter',
    'ups_2DA' => '2nd Day Air',
    'ups_2DAL' => '2nd Day Air Letter',
    'ups_3DS' => '3 Day Select',
    'ups_GND' => 'Ground',
    'ups_GNDCOM' => 'Ground Commercial',
    'ups_GNDRES' => 'Ground Residential',
    'ups_STD' => 'Canada Standard',
    'ups_XPR' => 'Worldwide Express',
    'ups_WXS' => 'Worldwide Express Saver',
    'ups_XPRL' => 'Worldwide Express Letter',
    'ups_XDM' => 'Worldwide Express Plus',
    'ups_XDML' => 'Worldwide Express Plus Letter',
    'ups_XPD' => 'Worldwide Expedited',
    'usps_0_FCLE' => 'First-Class Mail Large Envelope',
    'usps_0_FCL' => 'First-Class Mail Letter',
    'usps_0_FCP' => 'First-Class Mail Parcel',
    'usps_1' => 'Priority Mail',
    'usps_2' => 'Priority Mail Express Hold For Pickup',
    'usps_3' => 'Priority Mail Express',
    'usps_4' => 'Standard Post',
    'usps_6' => 'Media Mail Parcel',
    'usps_7' => 'Library Mail Parcel',
    'usps_13' => 'Priority Mail Express Flat Rate Envelope',
    'usps_16' => 'Priority Mail Flat Rate Envelope',
    'usps_17' => 'Priority Mail Medium Flat Rate Box',
    'usps_22' => 'Priority Mail Large Flat Rate Box',
    'usps_23' => 'Priority Mail Express Sunday/Holiday Delivery',
    'usps_25' => 'Priority Mail Express Sunday/Holiday Delivery Flat Rate Envelope',
    'usps_27' => 'Priority Mail Express Flat Rate Envelope Hold For Pickup',
    'usps_28' => 'Priority Mail Small Flat Rate Box',
    'usps_33' => 'Priority Mail Hold For Pickup',
    'usps_34' => 'Priority Mail Large Flat Rate Box Hold For Pickup',
    'usps_35' => 'Priority Mail Medium Flat Rate Box Hold For Pickup',
    'usps_36' => 'Priority Mail Small Flat Rate Box Hold For Pickup',
    'usps_37' => 'Priority Mail Flat Rate Envelope Hold For Pickup',
    'usps_42' => 'Priority Mail Small Flat Rate Envelope',
    'usps_43' => 'Priority Mail Small Flat Rate Envelope Hold For Pickup',
    'usps_53' => 'First-Class Package Service Hold For Pickup',
    'usps_55' => 'Priority Mail Express Flat Rate Boxes',
    'usps_56' => 'Priority Mail Express Flat Rate Boxes Hold For Pickup',
    'usps_57' => 'Priority Mail Express Sunday/Holiday Delivery Flat Rate Boxes',
    'usps_61' => 'First-Class Package Service',
    'usps_INT_1' => 'Priority Mail Express International',
    'usps_INT_2' => 'Priority Mail International',
    'usps_INT_4' => 'Global Express Guaranteed (GXG)',
    'usps_INT_6' => 'Global Express Guaranteed Non-Document Rectangular',
    'usps_INT_7' => 'Global Express Guaranteed Non-Document Non-Rectangular',
    'usps_INT_8' => 'Priority Mail International Flat Rate Envelope',
    'usps_INT_9' => 'Priority Mail International Medium Flat Rate Box',
    'usps_INT_10' => 'Priority Mail Express International Flat Rate Envelope',
    'usps_INT_11' => 'Priority Mail International Large Flat Rate Box',
    'usps_INT_12' => 'USPS GXG Envelopes',
    'usps_INT_13' => 'First-Class Mail International Letter',
    'usps_INT_14' => 'First-Class Mail International Large Envelope',
    'usps_INT_15' => 'First-Class Package International Service',
    'usps_INT_16' => 'Priority Mail International Small Flat Rate Box',
    'usps_INT_20' => 'Priority Mail International Small Flat Rate Envelope',
    'usps_INT_26' => 'Priority Mail Express International Flat Rate Boxes',
    'dhlint_2' => 'Easy shop',
    'dhlint_5' => 'Sprintline',
    'dhlint_6' => 'Secureline',
    'dhlint_7' => 'Express easy',
    'dhlint_9' => 'Europack',
    'dhlint_B' => 'Break bulk express',
    'dhlint_C' => 'Medical express',
    'dhlint_D' => 'Express worldwide',
    'dhlint_U' => 'Express worldwide',
    'dhlint_K' => 'Express 9:00',
    'dhlint_L' => 'Express 10:30',
    'dhlint_G' => 'Domestic economy select',
    'dhlint_W' => 'Economy select',
    'dhlint_I' => 'Break bulk economy',
    'dhlint_N' => 'Domestic express',
    'dhlint_O' => 'Others',
    'dhlint_R' => 'Globalmail business',
    'dhlint_S' => 'Same day',
    'dhlint_T' => 'Express 12:00',
    'dhlint_X' => 'Express envelope',
    'pedroteixeira_correios' => 'Correios',
    'mercadolivre' => 'Meli Envios'
  ];

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

    $data = [
      'refresh_only_stock' => $this->request->post['refresh_only_stock'],
      'active' => $this->request->post['active']
    ];
        
    $this->session->data['alerts'] = 'Configurações salvas com sucesso!';
    
    $response = $this->model_pluggto_pluggto->saveSettingsProductsSynchronization($data);
    if (!$response)    
      $this->session->data['alerts'] = 'Ocorreu algum erro ao salvar as configurações de sicronização';

    $this->response->redirect($redirect = $this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
  }

  /**
  * Desligar todos o relacionamento da opencart com o pluggto
  **/
  public function offAllProductsWithPluggTo() {
    $this->load->model('pluggto/pluggto');

    $this->model_pluggto_pluggto->offAllProductsWithPluggTo();

    $this->session->data['alerts'] = 'Todos os produtos foram desvinculados!';
    $this->response->redirect($redirect = $this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
  }

  /**
  * Importar todos os produtos do pluggto para o opencart
  **/
  public function importAllProductsToOpenCart() {
    error_reporting(0);

    $this->load->model('pluggto/pluggto');
    $result = $this->model_pluggto_pluggto->getProducts();

    foreach ($result->result as $i => $product) {
      $this->model_pluggto_pluggto->prepareToSaveInOpenCart($product);
    }

    $this->session->data['alerts'] = 'Importação feita com sucesso!';
    $this->response->redirect($redirect = $this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
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
        $data = [
          'action' => 'update',
          'quantity' => $opencart_product_response['quantity']
        ];

        $response = $this->model_pluggto_pluggto->updateStockPluggTo($data, $product['pluggto_product_id']);
      }
      
      if (isset($pluggto_product_response) && $pluggto_product_response->Product->price != $opencart_product_response['price']){
        $data = [
          'price' => $opencart_product_response['price']
        ];

        $response = $this->model_pluggto_pluggto->updateTo($data, $product['pluggto_product_id']);
      }
    }

    $this->session->data['alerts'] = 'Verificação feita com sucesso!';
    $this->response->redirect($redirect = $this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function exportAllProductsToPluggTo() {
    $this->load->model('catalog/product');
    $this->load->model('pluggto/pluggto');
    
    $products_opencart = $this->model_catalog_product->getProducts();

    $productPrepare = [];
    $data = [];

    foreach ($products_opencart as $product) {
      $data = [
        'name'       => $product['name'],
        'sku'        => $product['sku'],
        'price'      => $product['price'],
        'quantity'   => $product['quantity'],
        'variations' => $this->getVariationsToSaveInOpenCart($product['product_id'])
      ];
      
      $existProduct = $this->model_pluggto_pluggto->getRelactionProductPluggToAndOpenCartByProductIdOpenCart($product['product_id']);
      
      if ($existProduct->num_rows > 0) {
        $response = $this->model_pluggto_pluggto->updateTo($data, $existProduct->row['pluggto_product_id']);
        continue;
      }

      $response = $this->model_pluggto_pluggto->createTo($data);

      if (isset($response->Product->id)) {
        $this->model_pluggto_pluggto->createPluggToProductRelactionOpenCartPluggTo($response->Product->id, $product['product_id']);
      }
    }

    $this->session->data['alerts'] = 'Exportação feita com sucesso!';
    $this->response->redirect($redirect = $this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function getVariationsToSaveInOpenCart($product_id) {
    $options = $this->model_catalog_product->getProductOptions($product_id);

    $response = [];
    foreach ($options as $i => $option) {
      $response[] = [
        ''
      ];
    }
  }


  public function saveFieldsLinkage(){
    $this->load->model('pluggto/pluggto');

    $fields = $this->request->post['fields'];

    foreach ($fields as $key => $value) {
      $this->model_pluggto_pluggto->saveField($key, $value);
    }

    $this->session->data['alerts'] = 'Atrelamento salvo com sucesso!';

    $this->response->redirect($redirect = $this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
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
    $data['settingsProductsSynchronization'] = $settingsProductsSynchronization;

    $data['heading_title'] = $this->language->get('heading_title');

    $data['credentials'] = $credentials;

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_module'),
      'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('module/bestseller', 'token=' . $this->session->data['token'], 'SSL'),
    );
    
    $data['action_products'] = $this->url->link('module/pluggto/saveSettingsProductsSynchronization', 'token=' . $this->session->data['token'], 'SSL');
    $data['link_import_all_products_to_opencart'] = $this->url->link('module/pluggto/importAllProductsToOpenCart', 'token=' . $this->session->data['token'], 'SSL');
    $data['link_off_all_products_pluggto'] = $this->url->link('module/pluggto/offAllProductsWithPluggTo', 'token=' . $this->session->data['token'], 'SSL');
    $data['link_verify_stock_and_price_products'] = $this->url->link('module/pluggto/verifyStockAndPriceProducts', 'token=' . $this->session->data['token'], 'SSL');
    $data['link_export_all_products_to_pluggto'] = $this->url->link('module/pluggto/exportAllProductsToPluggTo', 'token=' . $this->session->data['token'], 'SSL');
    $data['action_basic_fields'] = $this->url->link('module/pluggto/saveFieldsLinkage', 'token=' . $this->session->data['token'], 'SSL');
    $data['link_basic_fields'] = $this->url->link('module/pluggto/settingsBasicFields', 'token=' . $this->session->data['token'], 'SSL');

    $data['action'] = $this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL');
    $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

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

    $data['alerts'] = $this->session->data['alerts'];
    $this->session->data['alerts'] = '';

    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');

    $data['button_add_module'] = $this->language->get('button_add_module');
    $data['button_remove'] = $this->language->get('button_remove');

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('module/pluggto.tpl', $data));
  }

  public function settingsBasicFields() {
    $this->template = 'module/pluggto.tpl';
    $this->language->load('module/pluggto');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('pluggto/pluggto');

    $data['heading_title'] = $this->language->get('heading_title');

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_module'),
      'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('module/bestseller', 'token=' . $this->session->data['token'], 'SSL'),
    );

    $data['action_basic_fields'] = $this->url->link('module/pluggto/saveFieldsLinkage', 'token=' . $this->session->data['token'], 'SSL');
    $data['cancel'] = $this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL');

    $data['alerts'] = $this->session->data['alerts'];
    $this->session->data['alerts'] = '';

    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $data['types_shippings'] = $this->typesShippings;

    $data['default_fields'] = $this->model_pluggto_pluggto->getAllDefaultsFields();

    $this->response->setOutput($this->load->view('module/pluggto_fields.tpl', $data));
  }

  protected function validate() {
    if (!$this->user->hasPermission('modify', 'module/store')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    return !$this->error;
  }

}
?>