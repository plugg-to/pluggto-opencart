<?php

class ControllerExtensionFeedPluggTo extends Controller {
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
    'frete_register' => 'Frete por carta registrada',
	'transportadora' => 'Transportadora'
  );

  public function install() {
    $this->load->model('pluggto/pluggto');
	$this->model_pluggto_pluggto->install();
	
	    $this->load->model('setting/setting');
	    $this->model_setting_setting->editSetting('pluggto', ['pluggto_status'=>1]);

      $this->load->model('extension/event');
      $this->model_extension_event->addEvent('pluggto_add',  'admin/model/catalog/product/addProduct/after', 'extension/feed/pluggto/forceSyncProduct');
      $this->model_extension_event->addEvent('pluggto_edit',  'admin/model/catalog/product/editProduct/after', 'extension/feed/pluggto/forceSyncProduct');
      $this->model_extension_event->addEvent('pluggto_delete',  'admin/model/catalog/product/deleteProduct/before', 'extension/feed/pluggto/deleteProduct');

  }

  public function uninstall() {
    $this->load->model('pluggto/pluggto');
	$this->model_pluggto_pluggto->uninstall();
	
	$this->load->model('setting/setting');
	$this->model_setting_setting->deleteSetting('pluggto');
	
	$this->load->model('extension/event');
	$this->model_extension_event->deleteEvent('pluggto_add');
	$this->model_extension_event->deleteEvent('pluggto_edit');
	$this->model_extension_event->deleteEvent('pluggto_delete');
  }
  
  	public function forceSyncProduct($eventRoute, $product_update) {
		
		$this->load->model('catalog/product');
		$this->load->model('pluggto/pluggto');
		
		if(isset($product_update[0]["sku"]) && !empty($product_update[0]["sku"])){
			$produto_bruto = $product_update[0];
			$result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `sku` LIKE '".$produto_bruto["sku"]."'");		
			
			$product = array(
				'name' => $produto_bruto["product_description"][1]["name"],
				'sku' => $produto_bruto["sku"],
				'price' => $produto_bruto["price"],
				'quantity' => $produto_bruto ["quantity"],
				'product_id' => $result->row["product_id"],
				'description' => $produto_bruto["product_description"][1]["description"],
				'manufacturer' => $produto_bruto["manufacturer"],
				'model' => $produto_bruto["model"],
				'ean' => $produto_bruto["ean"],
				'nbm' => $produto_bruto["mpn"],
				'isbn' => $produto_bruto["isbn"],
				'status' => $produto_bruto["status"],
				'length' => $produto_bruto["length"],
				'width' => $produto_bruto["width"],
				'height' => $produto_bruto ["height"],
				'weight' => $produto_bruto["weight"],
				'image' => $produto_bruto["image"]
				);
		}else{
			$product_id = $product_update[0];
			$product = $this->model_catalog_product->getProduct($product_id);
			$productExist = $this->model_pluggto_pluggto->getRelactionProductPluggToAndOpenCartByProductIdOpenCart($product_id);		
		}
		
		$force = isset($this->request->get['force']) ? $this->request->get['force'] : true;

		if (isset($this->request->get['error'])) {
			$error = $this->request->get['error'];

			error_reporting($error);
		}
		

		if(isset($product['manufacturer_id']) && !empty($product['manufacturer_id'])){
			$dados_marca = $this->db->query("SELECT * FROM `" . DB_PREFIX . "manufacturer` WHERE `manufacturer_id` LIKE '".$product['manufacturer_id']."'");
			$marca = $dados_marca->row;
			$brand = $marca['name']; 			
		}else{
			$brand = isset($product['manufacturer']) ? $product['manufacturer'] : '';
		}
				
		//if ($force == true) {
			$data = array(
				'name' => $product['name'],
				'sku' => $product['sku'],
				'grant_type' => "authorization_code",
				'price' => $product['price'],
				'quantity' => $product['quantity'],
				'external' => $product['product_id'],
				'description' => html_entity_decode(str_replace('"', '\'', $product['description'])),
				'brand' => $brand,
				'model' => isset($product['model']) ? $product['model'] : '',
				'ean' => $product['ean'],
				'nbm' => isset($product['nbm']) ? $product['nbm'] : '',
				'isbn' => $product['isbn'],
				'available' => $product['status'],
				'dimension' => array(
					'length' => (float) $product['length'],
					'width' => (float) $product['width'],
					'height' => (float) $product['height'],
					'weight' => (float) $product['weight'],
				),
				'photos' => $this->getPhotosToSaveInOpenCart($product['product_id'], $product['image']),
				'link' => 'http://' . $_SERVER['SERVER_NAME'] . '/index.php?route=product/product&product_id=' . $product['product_id'],
				'variations' => $this->getVariationsToSaveInOpenCart($product['product_id']),
				'attributes' => $this->getAtrributesToSaveInOpenCart($product['product_id']),
				'special_price' => $this->getSpecialPriceProductToPluggTo($product_id),
				'categories' => $this->getCategoriesToPluggTo($product['product_id'])
			);

			/*$data['attributes'][] = array(
				'code' => 'model',
				'label' => 'model',
				'value' => array(
					'code' => $product['model'],
					'label' => $product['model'],
				),
			);
		} else {
			$data = array(
				'sku' => $product['sku'],
				'grant_type' => "authorization_code",
				'price' => $product['price'],
				'quantity' => $product['quantity'],
				'external' => $product['product_id'],
				'variations' => $this->getVariationsToSaveInOpenCart($product['product_id'], $force),
				'special_price' => $this->getSpecialPriceProductToPluggTo($product_id),
			);
		}

		if (!empty($productExist)) {
			if (!$this->model_pluggto_pluggto->getSync('sync_name')) {
				unset($data['name']);
			}

			if (!$this->model_pluggto_pluggto->getSync('sync_description')) {
				unset($data['description']);
			}

			if (!$this->model_pluggto_pluggto->getSync('sync_price')) {
				unset($data['price']);
			}

			if (!$this->model_pluggto_pluggto->getSync('sync_special_price')) {
				unset($data['special_price']);
			}

			if (!$this->model_pluggto_pluggto->getSync('sync_dimensions')) {
				unset($data['dimension']);
			}

			if (!$this->model_pluggto_pluggto->getSync('sync_quantity')) {
				unset($data['quantity']);
			}

			if (!$this->model_pluggto_pluggto->getSync('sync_photos')) {
				unset($data['photos']);
			}

			if (!$this->model_pluggto_pluggto->getSync('sync_categories')) {
				unset($data['categories']);
			}

			if (!$this->model_pluggto_pluggto->getSync('sync_attributes')) {
				unset($data['attributes']);
			}

			if (!$this->model_pluggto_pluggto->getSync('sync_brand')) {
				unset($data['brand']);
			}
		}*/
		
		$response = $this->model_pluggto_pluggto->sendToPluggTo($data, $product['sku']);

		//$this->model_pluggto_pluggto->createLog(print_r($response, 1), 'exportAllProductsToPluggTo');
		
		if(!isset($response->error) && isset($response->Product)){
			$this->model_pluggto_pluggto->createPluggToProductRelactionOpenCartPluggTo($response->Product->id, $product['product_id']);
		}

		/*if (!isset($response['response']->Product) && empty($response['response']->Product)) {
		}else{
			$status = 1;
			$description = 'O produto foi enviado para plugg.to';
			$date_created = date("Y-m-d H:i:s");		
			$type =  $response['method'];
			$action = '/skus/'.$product['sku'];
			$this->model_pluggto_pluggto->InsertItemsInQueues($status, $description, $date_created, $type, $action);
		}*/
		
		
	}
	
	public function deleteProduct($eventRoute, $product) {		
		$this->load->model('catalog/product');
		$this->load->model('pluggto/pluggto');
		
		$del_ativo = $this->model_pluggto_pluggto->getSettingsProductsSynchronization();
		$del_ativo = $del_ativo->row["del_sync"];
		
		if($del_ativo == 1){
		
		$product_id = $product[0];
		$product_data = $this->model_catalog_product->getProduct($product_id);
		
		$response = $this->model_pluggto_pluggto->deleteInPluggTo($product_data['sku']);
		
		}
	}
	
	public function getCategoriesToPluggTo($product_id) {
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		$categories = $query->rows;

		$response = array();
		foreach ($categories as $category) {
			$categoryData = $this->model_catalog_category->getCategory($category['category_id']);
			$firstParent = (isset($categoryData['parent_id']) && !empty($categoryData['parent_id'])) ? $this->model_catalog_category->getCategory($categoryData['parent_id']) : null;
			$secondParent = (isset($firstParent['parent_id']) && !empty($firstParent['parent_id'])) ? $this->model_catalog_category->getCategory($firstParent['parent_id']) : null;
			$thirdParent = (isset($secondParent['parent_id']) && !empty($secondParent['parent_id'])) ? $this->model_catalog_category->getCategory($secondParent['parent_id']) : null;

			$response[] = array(
				'name' => ((isset($categoryData['name'])) ? $categoryData['name'] : '')
				. ((isset($firstParent['name'])) ? ' > ' . $firstParent['name'] : '')
				. ((isset($secondParent['name'])) ? ' > ' . $secondParent['name'] : '')
				. ((isset($thirdParent['name'])) ? ' > ' . $thirdParent['name'] : ''),
			);
		}

		return $response;
	}

  /**
  * Salvar configuraçoes de sicronizacao
  **/
  public function saveSettingsProductsSynchronization() {
    $this->load->model('pluggto/pluggto');

    $data = array(
      'refresh_only_stock' => 1,
      'active' => $this->request->post['active'],
      'only_actives' => $this->request->post['only_actives'],
	  'del_sync' => $this->request->post['del_sync'],
	  
    );
        
    $this->session->data['alerts'] = 'Configurações salvas com sucesso!';
    
    $response = $this->model_pluggto_pluggto->saveSettingsProductsSynchronization($data);
    if (!$response)    
      $this->session->data['alerts'] = 'Ocorreu algum erro ao salvar as configurações de sicronização';

    $this->response->redirect($redirect = $this->url->link('extension/feed/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
  }

  /**
  * Desligar todos o relacionamento da opencart com o pluggto
  **/
  public function offAllProductsWithPluggTo() {
    $this->load->model('pluggto/pluggto');

    $this->model_pluggto_pluggto->offAllProductsWithPluggTo();

    $this->session->data['alerts'] = 'Todos os produtos foram desvinculados!';
    $this->response->redirect($redirect = $this->url->link('extension/feed/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
  }

  /**
  * Importar todos os produtos do pluggto para o opencart
  **/
  public function importAllProductsToOpenCart() {
    $this->load->model('pluggto/pluggto');

    $this->model_pluggto_pluggto->saveImportationQueue();

    $this->session->data['alerts'] = 'Importação agendada com sucesso!';
    $this->response->redirect($redirect = $this->url->link('extension/feed/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
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
    $this->response->redirect($redirect = $this->url->link('extension/feed/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function exportAllProductsToPluggTo() {
    $this->load->model('pluggto/pluggto');

    $this->model_pluggto_pluggto->saveExportationQueue();

    $this->session->data['alerts'] = 'Exportação agendada com sucesso!';
    $this->response->redirect($redirect = $this->url->link('extension/feed/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function getSpecialPriceProductToPluggTo($product_id) {
	$this->load->model('catalog/product');
    $specialPrice = $this->model_catalog_product->getProductSpecials($product_id);
    $special = $specialPrice[0]['price'];
    return $special;
  }

  public function getPhotosToSaveInOpenCart($product_id, $image_main) {
		$images = $this->model_catalog_product->getProductImages($product_id);

		$response = array();

		if (isset($image_main) && !empty($image_main)) {
			$response[] = array(
				'url' => 'http://' . $_SERVER['SERVER_NAME'] . '/image/' . $image_main,
				'title' => 'Imagem principal do produto',
				'order' => 1,
			);
		}

		foreach ($images as $i => $image) {
			$response[] = array(
				'url' => 'http://' . $_SERVER['SERVER_NAME'] . '/image/' . $image['image'],
				'title' => 'Imagem principal do produto',
				'order' => $image['sort_order'],
			);
		}

		return $response;
	}

  public function getVariationsToSaveInOpenCart($product_id, $force = true) {
		$product = $this->model_catalog_product->getProduct($product_id);
		$options = $this->model_catalog_product->getProductOptions($product_id);
		
		$response = array();
		foreach ($options as $i => $option) {
		  foreach ($option['product_option_value'] as $item) {

		  	if (isset($item['subtract']))
		  		return;

			/*$attributes = array();
			foreach ($attributesTemp as $value) {
				$attributes[] = $value;
			}*/

			$attributes[] = array(
				'code'  => $option['name'],
				'label' => $option['name'],
				'value'	=> array(
					'code' => $item['name'],
					'label'=> $item['name']
				)
			);

			if ($force === true) {
				$response[] = array(
					'name'     => $product['name'] . ' - ' . $item['name'],
					'external' => $option['product_option_id'],
					'quantity' => $item['quantity'],
					'special_price' => $this->getSpecialPriceProductToPluggTo($product_id),
					'price' => ($item['price_prefix'] == '+') ? $product['price'] + $item['price'] : $product['price'] - $item['price'] ,
					'sku' => $product['sku'] . '-' . $item['name'],
					'ean' => '',
					'photos' => array(),
					'attributes' => $attributes,
					'dimesion' => array(
						'length' => $product['length'],
						'width'  => $product['width'],
						'height' => $product['height'],
						'weight' => ($item['weight_prefix'] == '+') ? $item['weight'] + $product['weight'] : $item['weight'] - $product['weight'],
					)
				);
			} else {
				$response[] = array(
					'name'     => $product['name'] . ' - ' . $item['name'],
					'quantity' => $item['quantity'],
					'special_price' => $this->getSpecialPriceProductToPluggTo($product_id),
					'price' => ($item['price_prefix'] == '+') ? $product['price'] + $item['price'] : $product['price'] - $item['price'] ,
					'sku' => $product['sku'] . '-' . $item['name']
				);
			}
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
						'code'  => $attr['name'],
						'label' => $attr['name'],
						'value' => array(
							'code'  => $attr['text'],
							'label' => $attr['text'],
						)
					);
				}
			}
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

    $this->response->redirect($redirect = $this->url->link('extension/feed/pluggto', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function pluggTransparent() {
    $this->load->model('pluggto/pluggto');

    $url = 'https://core.plugg.to/users/autologin/'. $this->model_pluggto_pluggto->getAccesstoken() . '/mercadolivre';

    $this->children = array(
      'common/header',
      'common/footer'
    );

    $this->load->model('design/layout');

    $this->data['layouts'] = $this->model_design_layout->getLayouts();

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/feed/pluggto_transparent.tpl')) { //if file exists in your current template folder
       $this->template = $this->config->get('config_template') . '/template/extension/feed/pluggto_transparent.tpl'; //get it
    } else {
       $this->template = '/extension/feed/pluggto_transparent.tpl'; //or get the file from the default folder
    }

    $this->data['url'] = $url;

    $this->response->setOutput($this->render());
  }

  public function index() {
    $this->template = 'extension/feed/pluggto.tpl';
    
    $this->language->load('extension/feed/pluggto');

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
    
    $data['action_products'] = $this->url->link('extension/feed/pluggto/saveSettingsProductsSynchronization', 'token=' . $this->session->data['token'], 'SSL');
    $data['link_import_all_products_to_opencart'] = $this->url->link('extension/feed/pluggto/importAllProductsToOpenCart', 'token=' . $this->session->data['token'], 'SSL');
    $data['link_off_all_products_pluggto'] = $this->url->link('extension/feed/pluggto/offAllProductsWithPluggTo', 'token=' . $this->session->data['token'], 'SSL');
    $data['link_verify_stock_and_price_products'] = $this->url->link('extension/feed/pluggto/verifyStockAndPriceProducts', 'token=' . $this->session->data['token'], 'SSL');
    $data['link_export_all_products_to_pluggto'] = $this->url->link('extension/feed/pluggto/exportAllProductsToPluggTo', 'token=' . $this->session->data['token'], 'SSL');
    $data['action_basic_fields'] = $this->url->link('extension/feed/pluggto/saveFieldsLinkage', 'token=' . $this->session->data['token'], 'SSL');
    $data['link_basic_fields'] = $this->url->link('extension/feed/pluggto/settingsBasicFields', 'token=' . $this->session->data['token'], 'SSL');
    $data['load_queue'] = $this->url->link('extension/feed/pluggto/loadqueue', 'token=' . $this->session->data['token'], 'SSL');
    $data['link_log_queue'] = $this->url->link('extension/feed/pluggto/linkLogQueue', 'token=' . $this->session->data['token'], 'SSL');
    $data['force_sync_products'] = $this->url->link('extension/feed/pluggto/listAllProductsToForceSync', 'token=' . $this->session->data['token'], 'SSL');
    $data['linkPluggTransparent'] = $this->url->link('extension/feed/pluggto/pluggTransparent', 'token=' . $this->session->data['token'], 'SSL');

    $data['action'] = $this->url->link('extension/feed/pluggto', 'token=' . $this->session->data['token'], 'SSL');
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

    if (isset($this->session->data['alerts'])) {
      $data['alerts'] = $this->session->data['alerts'];
    } else {
      $data['alerts'] = '';
    }
    
    $this->session->data['alerts'] = '';

    $data['button_pull'] = $this->url->link('extension/feed/pluggto/pullModule', 'token=' . $this->session->data['token'], 'SSL');;
    $data['button_cancel'] = $this->language->get('button_cancel');

    $data['button_add_module'] = $this->language->get('button_add_module');
    $data['button_remove'] = $this->language->get('button_remove');

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/feed/pluggto.tpl', $data));
  }

  public function settingsBasicFields() {
    $this->template = 'extension/feed/pluggto.tpl';
    $this->language->load('extension/feed/pluggto');

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

    $data['action_basic_fields']   = $this->url->link('extension/feed/pluggto/saveFieldsLinkage', 'token=' . $this->session->data['token'], 'SSL');
    $data['cancel']                = $this->url->link('extension/feed/pluggto', 'token=' . $this->session->data['token'], 'SSL');

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

    $data['default_fields']  = $this->model_pluggto_pluggto->getAllDefaultsFields();

    $data['status_opencart'] = $this->model_pluggto_pluggto->getStatusOpenCart();
    
    $this->response->setOutput($this->load->view('extension/feed/pluggto_fields.tpl', $data)); // aqui
  }

  public function listAllProductsToForceSync(){
    $this->template = 'extension/feed/pluggto.tpl';
    $this->language->load('extension/feed/pluggto');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('pluggto/pluggto');
    $this->load->model('catalog/product');

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

    $data['action_basic_fields']   = $this->url->link('extension/feed/pluggto/saveFieldsLinkage', 'token=' . $this->session->data['token'], 'SSL');
    $data['cancel']                = $this->url->link('extension/feed/pluggto', 'token=' . $this->session->data['token'], 'SSL');

    $data['token'] = $this->session->data['token'];

    $data['alerts']                = $this->session->data['alerts'];
    $this->session->data['alerts'] = '';

    $data['header']      = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer']      = $this->load->controller('common/footer');

    $data['products']    = $this->model_catalog_product->getProducts();

    $this->response->setOutput($this->load->view('extension/feed/pluggto_products_sync.tpl', $data)); // aqui
  }

  public function linkLogQueue(){
    $this->template = 'extension/feed/pluggto.tpl';
    $this->language->load('extension/feed/pluggto');

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

    $data['action_basic_fields'] = $this->url->link('extension/feed/pluggto/saveFieldsLinkage', 'token=' . $this->session->data['token'], 'SSL');
    $data['cancel'] = $this->url->link('extension/feed/pluggto', 'token=' . $this->session->data['token'], 'SSL');

    $data['alerts'] = $this->session->data['alerts'];
    $this->session->data['alerts'] = '';

    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $data['queues'] = $this->model_pluggto_pluggto->getAllItemsInQueues();

    $this->response->setOutput($this->load->view('extension/feed/pluggto_queue.tpl', $data));
  }

  protected function validate() {
    if (!$this->user->hasPermission('modify', 'module/store')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    return !$this->error;
  }

  public function loadQueue(){
    $this->template = 'extension/feed/pluggto_load_queue.tpl';
    $this->language->load('extension/feed/pluggto');
    $this->load->model('pluggto/pluggto');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->session->data['alerts'] = '';

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

    $data['header']        = $this->load->controller('common/header');
    $data['heading_title'] = $this->language->get('heading_title');
    $data['button_save']   = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');
    $data['column_left']   = $this->load->controller('common/column_left');
    $data['alerts']        = $this->session->data['alerts'];
    $data['cancel']        = $this->url->link('extension/feed/pluggto', 'token=' . $this->session->data['token'], 'SSL');
    $data['footer']        = $this->load->controller('common/footer');
    $data['queue']         = $this->model_pluggto_pluggto->getNotifications(500, 'all');
    
    $this->response->setOutput($this->load->view('extension/feed/pluggto_load_queue.tpl', $data));
  }

}

?>