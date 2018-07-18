<?php

ini_set('memory_limit', '-1');
error_reporting(0);

class ControllerApiPluggto extends Controller {

	protected $estados = array("AC"=>"Acre", "AL"=>"Alagoas", "AM"=>"Amazonas", "AP"=>"Amapá","BA"=>"Bahia","CE"=>"Ceará","DF"=>"Distrito Federal","ES"=>"Espírito Santo","GO"=>"Goiás","MA"=>"Maranhão","MT"=>"Mato Grosso","MS"=>"Mato Grosso do Sul","MG"=>"Minas Gerais","PA"=>"Pará","PB"=>"Paraíba","PR"=>"Paraná","PE"=>"Pernambuco","PI"=>"Piauí","RJ"=>"Rio de Janeiro","RN"=>"Rio Grande do Norte","RO"=>"Rondônia","RS"=>"Rio Grande do Sul","RR"=>"Roraima","SC"=>"Santa Catarina","SE"=>"Sergipe","SP"=>"São Paulo","TO"=>"Tocantins");

	public function index(){
		$json = array('status' => 'operational', 'HTTPcode' => 200);


		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
		
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		
			$this->response->addHeader('Access-Control-Max-Age: 1000');
		
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');		
		}

		$this->response->addHeader('Content-Type: application/json');
			
		$this->response->setOutput(json_encode('$json'));
	}

	public function cronOrders() {
		$this->load->model('extension/feed/pluggto');

		$num_orders_opencart = $this->saveOrdersInOpenCart($this->existNewOrdersPluggTo());
        
        $response = array(
            'orders_created_or_updated_opencart' => $num_orders_opencart,
        );

        $this->model_extension_feed_pluggto->createLog(print_r($response, 1), 'cronOrders');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($response));
	}

	public function cronUpdateOrders() {
		$this->load->model('extension/feed/pluggto');

		$num_orders_pluggto = $this->saveOrdersInPluggTo($this->model_extension_feed_pluggto->getOrders(array('start' => 0, 'limit' => 999999999)));
	        
        $response = array(
            'orders_created_or_updated_pluggto' => $num_orders_pluggto,
        );

        $this->model_extension_feed_pluggto->createLog(print_r($response, 1), 'cronUpdateOrders');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($response));
	}
	
	public function processQueue(){
		$this->load->model('catalog/product');
		$this->load->model('extension/feed/pluggto');

		$productsQueue = $this->model_extension_feed_pluggto->getQueuesProducts('opencart');

        $response = array(
            'action' => "import products from pluggto",
        );

		if (!empty($productsQueue))
		{
			foreach ($productsQueue as $product) {
				try {
			        $product = $this->model_catalog_product->getProduct($product['product_id']);

		            $return = $this->exportAllProductsToPluggTo($product);
					
					$response[$product['product_id']]['status']  = $return;
					$response[$product['product_id']]['message'] = $return !== null ? "Product '{$product['product_id']}' imported successfully" : "Produts Could not be imported";

					$this->model_extension_feed_pluggto->processedQueueProduct($product['product_id'], "opencart");
				} catch (Exception $e) {
					continue;
				}
			}
		}

		$productsQueue = $this->model_extension_feed_pluggto->getQueuesProducts('pluggto');
		
		if (!empty($productsQueue))
		{
			foreach ($productsQueue as $product) {
				try {
			        $product = $this->model_extension_feed_pluggto->getProduct($product['product_id_pluggto']);
		            $return = $this->model_extension_feed_pluggto->prepareToSaveInOpenCart($product);
					
					$response[$product->Product->id]['status']  = $return;
					$response[$product->Product->id]['message'] = $return !== 'Problem SKU' ? "Product '$productId' imported successfully" : "Produts Could not be imported";

					$this->model_extension_feed_pluggto->processedQueueProduct($product->Product->id, "pluggto");
				} catch (Exception $e) {
					continue;
				}
			}
		}

        $this->model_extension_feed_pluggto->createLog(print_r($response, 1), 'processQueue');

		$this->response->setOutput(json_encode($response));
	}

	public function existNewOrdersPluggTo() {
		$this->load->model('extension/feed/pluggto');

		$response = array();

		$notifications = $this->model_extension_feed_pluggto->getNotifications(10, 'orders');
		
		foreach ($notifications as $notification) {
			$order = $this->model_extension_feed_pluggto->getOrderPluggTo($notification['resource_id']);

			if (!isset($order->Order) && empty($order->Order)) {
				$this->model_extension_feed_pluggto->createLog(print_r($order, 1), 'existNewOrdersPluggTo');
				continue;
			}

			$response[$notification['resource_id']] = $order;
		}
		
		return $response;
	}

	public function saveOrdersInOpenCart($orders) {
		$i = 0;

		$this->load->model('account/customer');

		$this->load->model('checkout/order');
		
		$currency = $this->model_extension_feed_pluggto->getCurrencyMain();
		
		foreach ($orders as $id_pluggto => $order) {
			try {

				$email = null;

				if (!isset($order->Order->receiver_email) && empty($order->Order->receiver_email))
					$email = sha1($order->Order->id) . '@plugg.to';
				else 
					$email = $order->Order->receiver_email;

				$customer    = $this->model_extension_feed_pluggto->getCustomerByEmail($email);
				$customer_id =  $customer['customer_id'];

				$cpf_custom_field = $this->model_extension_feed_pluggto->getIdCustomFieldByName('cpf');
				$number_custom_field = $this->model_extension_feed_pluggto->getIdCustomFieldByName('number');
				$complement_custom_field = $this->model_extension_feed_pluggto->getIdCustomFieldByName('complement');
				$add_infor = $this->model_extension_feed_pluggto->getIdCustomFieldByName('information_adds');

				$customer = array(
					'customer_group_id'  => 1,
					'firstname' 		 => (isset($order->Order->payer_name) ? $order->Order->payer_name : null),
					'lastname' 			 => (isset($order->Order->payer_lastname) ? $order->Order->payer_lastname : null),
					'email' 			 => $email,
					'telephone' 		 => (isset($order->Order->receiver_phone) ? $order->Order->receiver_phone : null),
					'fax' 				 => (isset($order->Order->receiver_phone) ? $order->Order->receiver_phone : null),
					'payment_firstname'  => (isset($order->Order->payer_name) ? $order->Order->payer_name : null),
					'company'      		 => '',
					'address'      		 => (isset($order->Order->payer_address) ? $order->Order->payer_address : null),
					'address_1'    		 => (isset($order->Order->payer_address) ? $order->Order->payer_address : null),
					'address_2'    		 => (isset($order->Order->payer_neighborhood) ? $order->Order->payer_neighborhood : null),
					'postcode'     		 => (isset($order->Order->payer_zipcode) ? $order->Order->payer_zipcode : null),
					'city'         		 => (isset($order->Order->payer_city) ? $order->Order->payer_city : null),
					'zone_id'      		 => $this->getPaymentZoneIDByState((isset($order->Order->payer_state) ? $order->Order->payer_state : null)),
					'country_id'   		 => 30,
					'custom_field'		 => array(
						$cpf_custom_field => (isset($order->Order->payer_cpf) ? $order->Order->payer_cpf : null),
						'address' => array(
							$number_custom_field => (isset($order->Order->receiver_address_number) ? $order->Order->receiver_address_number : ""),
							$complement_custom_field => (isset($order->Order->receiver_address_complement) ? $order->Order->receiver_address_complement : ""),
							$add_infor => (isset($order->Order->receiver_additional_info) ? $order->Order->receiver_additional_info : "")
						)
					)
				);

				$customer['address'] = $address;

				if (empty($customer_id)) {
					$customer_id = $this->model_account_customer->addCustomer($customer);
				}

				if (!empty($customer_id)) {
					$this->model_extension_feed_pluggto->editCustomer($customer, $customer_id);
				}

				$shipping = (isset($order->Order->shipments[0]->shipping_method) ? $order->Order->shipments[0]->shipping_method : null);
				$shipping = explode(' ', $shipping);

				$shippingMethod = $shipping[1];
				$shippingCompany = $shipping[0];
				
				$data = array(
					'invoice_prefix' 	 => (isset($order->Order->id) ? $order->Order->id : null),
					'store_id'			 => 0,
					'store_name' 		 => $this->config->get('config_name'),
					'store_url' 		 => HTTP_SERVER,
					'customer_id' 		 => $customer_id,
					'customer_group_id'  => 1,
					'firstname' 		 => (isset($order->Order->payer_name) ? $order->Order->payer_name : null),
					'lastname' 			 => (isset($order->Order->payer_lastname) ? $order->Order->payer_lastname : null),
					'email' 			 => $email,
					'telephone' 		 => (isset($order->Order->receiver_phone) ? $order->Order->receiver_phone : null),
					'fax' 				 => (isset($order->Order->receiver_phone) ? $order->Order->receiver_phone : null),
					'payment_firstname'  => (isset($order->Order->payer_name) ? $order->Order->payer_name : null),
					'payment_lastname' 	 => (isset($order->Order->payer_lastname) ? $order->Order->payer_lastname : null),
					'payment_company' 	 => (isset($order->Order->payer_razao_social) ? $order->Order->payer_razao_social : null),
					'payment_address_1'  => (isset($order->Order->payer_address) ? $order->Order->payer_address : null),
					'payment_address_2'  => (isset($order->Order->payer_neighborhood) ? $order->Order->payer_neighborhood : null),
					'payment_city' 		 => (isset($order->Order->payer_city) ? $order->Order->payer_city : null),
					'payment_postcode' 	 => (isset($order->Order->payer_zipcode) ? $order->Order->payer_zipcode : null),
					'payment_country' 	 => (isset($order->Order->payer_country) ? $order->Order->payer_country : null),
					'payment_country_id' => 30,
					'payment_zone' 		 => (isset($order->Order->payer_state) ? $order->Order->payer_state : null),
					'payment_zone_id' 	 => $this->getPaymentZoneIDByState((isset($order->Order->payer_state) ? $order->Order->payer_state : null)),
					'payment_method' 	 => $this->getPaymentMethodByOrderPluggTo($order->Order),
					'payment_code'		 => $this->getPaymentCodeByOrderPluggTo($order->Order),
					'shipping_firstname' => (isset($order->Order->receiver_name) ? $order->Order->receiver_name : null),
					'shipping_lastname'  => (isset($order->Order->receiver_lastname) ? $order->Order->receiver_lastname : null),
					'shipping_company' 	 => '',
					'shipping_address_1' => (isset($order->Order->receiver_address) ? $order->Order->receiver_address : null),
					'shipping_address_2' => (isset($order->Order->payer_neighborhood) ? $order->Order->payer_neighborhood : null),
					'shipping_city' 	 => (isset($order->Order->receiver_city) ? $order->Order->receiver_city : null),
					'shipping_postcode'  => (isset($order->Order->receiver_zipcode) ? $order->Order->receiver_zipcode : null),
					'shipping_country' 	 => (isset($order->Order->payer_country) ? $order->Order->payer_country : null),
					'shipping_country_id'=> 30,
					'shipping_zone' 	 => (isset($order->Order->payer_state) ? $order->Order->payer_state : null),
					'shipping_zone_id' 	 => $this->getPaymentZoneIDByState((isset($order->Order->payer_state) ? $order->Order->payer_state : null)),
					'shipping_method' 	 => $shippingMethod,
					'shipping_code' 	 => (isset($order->Order->shipments[0]->track_code) ? $order->Order->shipments[0]->track_code : null),
					'comment' 			 => '',
					'total' 			 => (isset($order->Order->total) ? $order->Order->total : null),
					'totals'			 => array(
						array(
							"code" => "sub_total",
							"title" => "Sub-Total",
							"text" => "R$ " . (isset($order->Order->subtotal) ? number_format($order->Order->subtotal, 2, ",", ".") : "0.00"),
							"value" => (isset($order->Order->subtotal) ? $order->Order->subtotal : 0.00),
							"sort_order" => 1
						),
						array(
							"code" => "shipping",
							"title" => "Taxa fixa de frete",
							"text" => "R$ " . (isset($order->Order->shipping) ? number_format($order->Order->shipping, 2, ",", ".") : "0.00"),
							"value" => (isset($order->Order->shipping) ? $order->Order->shipping : 0.00),
							"sort_order" => 1
						),
						array(
							"code" => "total",
							"title" => "Total",
							"text" => "R$ " . (isset($order->Order->total) ? number_format($order->Order->total, 2, ",", ".") : "0.00"),
							"value" => (isset($order->Order->total) ? $order->Order->total : 0.00),
							"sort_order" => 1
						),
					),
					'currency_id' 		 => $currency['currency_id'],
					'currency_code' 	 => $currency['currency_code'],
					'currency_value' 	 => $currency['currency_value'],
					'order_product'		 => $this->getProductsToSaveOpenCart($order),
					'products'		 	 => $this->getProductsToSaveOpenCart($order),
					'language_id'		 => 2,
					'custom_field'		 => array(
						$cpf_custom_field => (isset($order->Order->payer_cpf) ? $order->Order->payer_cpf : null),
					),
					'shipping_custom_field' => array(
						$number_custom_field => (isset($order->Order->receiver_address_number) ? $order->Order->receiver_address_number : ""),
						$complement_custom_field => (isset($order->Order->receiver_address_complement) ? $order->Order->receiver_address_complement : "")
					),
					'payment_custom_field' => array(
						$number_custom_field => (isset($order->Order->receiver_address_number) ? $order->Order->receiver_address_number : ""),
						$complement_custom_field => (isset($order->Order->receiver_address_complement) ? $order->Order->receiver_address_complement : "")
					)
				);
				
				$existOrderID = $this->model_extension_feed_pluggto->orderExistInPluggTo($id_pluggto);
				
				$response_id  = $existOrderID;

				if ($response_id) {					
					$this->model_checkout_order->addOrderHistory($response_id, $this->model_extension_feed_pluggto->getStatusSaleByHistory($order->Order->status));
				} else {
					$response_id = $this->model_checkout_order->addOrder($data);

					if ($response_id <= 0)
					{
						$this->model_extension_feed_pluggto->updateStatusNotification($id_pluggto, json_encode(array('success' => false, 'message' => 'Pedido nao foi criado')));
						continue;
					}

					$this->model_extension_feed_pluggto->createRelationOrder($order->Order->id, $response_id);
					
					$this->model_checkout_order->addOrderHistory($response_id, $this->model_extension_feed_pluggto->getStatusSaleByHistory($order->Order->status));

				}
				
				$this->model_extension_feed_pluggto->updateStatusNotification($id_pluggto, json_encode(array('success' => true, 'message' => 'OK')));
			} catch (Exception $e) {
				$this->model_extension_feed_pluggto->updateStatusNotification($id_pluggto, json_encode(array('success' => false, 'message' => $e->getMessage())));
			}

			$i++;
		}

		return $i;
	}

	public function getLastIdOrder() {
		$response = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` ORDER BY order_id DESC LIMIT 1");

		return $response->row['order_id'] + 1;
	}

	public function getProductsToSaveOpenCart($order) {
		if (!isset($order->Order->items) && empty($order->Order->items)) {
			return false;
		}

		$response = array();
		foreach ($order->Order->items as $key => $item) {

			$nameExplode = explode('-', $item->sku);

			if (!isset($nameExplode[1]) || empty($nameExplode[1])) {
				$nameExplode = explode('-', $item->variation->sku);

				if (isset($nameExplode[1]) && !empty($nameExplode[1])) {
					$nameVar = $nameExplode[1];
				}
			}

			$skuOriginal = $nameExplode[0];
			
			if (empty($skuOriginal))
				$skuOriginal = $item->sku;

			$response[] = array(
				'product_id' => $this->model_extension_feed_pluggto->getIDItemBySKU($skuOriginal),
				'name'       => $item->name,
				'model'	     => $this->model_extension_feed_pluggto->getModelItemBySKU($skuOriginal),
				'quantity'   => $item->quantity,
				'price'		 => $item->price,
				'total'		 => $item->total,
				'tax'		 => 0,
				'reward'	 => 0,	
				'option'     => $this->loadOptionsToSaveOpenCart($item),
				'download'   => array()
			);
		}
		
		return $response;
	}

	public function loadOptionsToSaveOpenCart($item) {
		$this->load->model('extension/feed/pluggto');

		$nameExplode = explode('-', $item->sku);

		if (!isset($nameExplode[1]) || empty($nameExplode[1])) {
			$nameExplode = explode('-', $item->variation->sku);

			if (isset($nameExplode[1]) && !empty($nameExplode[1])) {
				$nameVar = $nameExplode[1];
			} else {
				$nameVar = $nameExplode[1];
			}
		} else {
			$nameVar = $nameExplode[1];
		}
		
		if (!isset($nameVar) || empty($nameVar))
			return array();

		$skuOriginal = $nameExplode[0];

		$product_id 	= $this->model_extension_feed_pluggto->getIDItemBySKU($skuOriginal);
		$optionId 		= $this->model_extension_feed_pluggto->getOptionIdByName($nameVar);
		$optionValueId 	= $this->model_extension_feed_pluggto->getOptionValueIdByNameNew($nameVar);
		$optionValueId 	= $this->model_extension_feed_pluggto->getProductOptionValueId($optionId, $product_id, $optionValueId)->row['product_option_value_id'];

		$response = array();

		$response[] = array(
			'product_option_value_id' => $optionValueId,
			'product_option_id'		  => $optionId,
			'value'					  => $nameVar,
			'type'					  => 'radio'
		);

		return $response;
	}

	public function getPaymentZoneIDByState($state) {
		$response = $this->model_extension_feed_pluggto->getPaymentZoneIDByState($this->estados[$state]);

		if (!empty($response->row)) {
			return $response->row['zone_id'];
		}

		return null;
	}

	public function getPaymentMethodByOrderPluggTo($order) {
		return 'PluggTo';
	}

	public function getPaymentCodeByOrderPluggTo($order) {
		return 'free_checkout';
	}

	public function saveOrdersInPluggTo($orders) {
		$this->load->model('checkout/order');
    	$this->load->model('extension/feed/pluggto');
    	
    	$cont = 0;
    	$return = array();
    	foreach ($orders as $order) {
    		$params = array(
    			'external' 			  => $order['order_id'],
    			'status' 			  => $this->model_extension_feed_pluggto->getStatusToPluggToByStatusOpenCart($order['order_status_id']),
    			'total' 			  => $this->model_extension_feed_pluggto->getOrderTotalByCode($order['order_id'], 'total'),
    			'subtotal' 			  => $this->model_extension_feed_pluggto->getOrderTotalByCode($order['order_id'], 'sub_total'),
    			'shipping' 			  => $this->model_extension_feed_pluggto->getOrderTotalByCode($order['order_id'], 'shipping'),
    			'discount' 			  => $this->model_extension_feed_pluggto->getOrderTotalByCode($order['order_id'], 'discount'),
    			'receiver_name'       => $order['shipping_firstname'],
    			'receiver_lastname'   => $order['shipping_lastname'],
    			'receiver_address'    => $order['shipping_address_1'],
    			'receiver_zipcode'    => $order['shipping_postcode'],
    			'receiver_city'       => $order['shipping_city'],
    			'receiver_state'      => '',
    			'receiver_country'    => 'Brasil',
    			'receiver_phone_area' => '',
    			'receiver_phone'      => $order['telephone'],
    			'receiver_email'      => $order['email'],
    			'delivery_type'       => $this->model_extension_feed_pluggto->getShippingMethodToPluggByOpenCart($order['shipping_method']),
    			'payer_name'          => $order['shipping_firstname'],
    			'payer_lastname'      => $order['shipping_lastname'],
    			'payer_address'       => $order['shipping_address_1'],
    			'payer_zipcode'       => $order['shipping_postcode'],
    			'payer_city'          => $order['shipping_city'],
    			'payer_state'         => '',
    			'payer_country'       => 'Brasil',
    			'payer_phone_area'    => '',
    			'payer_phone'         => $order['telephone'],
    			'payer_email'         => $order['email'],
    			'payer_cpf' 		  => '',
    			'payer_cnpj' 		  => '',
    			'payer_razao_social'  => '',
    			'payer_ie'			  => '',
    			'payer_gender'        => 'n/a',
    			'items'				  => $this->getItemsToOrderPluggTo($order),
    			'shipments'           => $this->getDataShipmentsByOrder($order),
     		);
			
     		$response = $this->model_extension_feed_pluggto->getRelactionOrder($order['order_id']);

			$return[$response['order_id_pluggto']] = 'Não editado, pedido criado direto no PluggTo';

     		if (!empty($response))
     		{
     			$responsePluggTo = $this->model_extension_feed_pluggto->editOrder($params, $response['order_id_pluggto']);
     			
     			$return[$response['order_id_pluggto']] = print_r($responsePluggTo, 1);
     		}
    	}

        $this->model_extension_feed_pluggto->createLog(print_r($return, 1), 'saveOrdersInPluggTo');
        
    	return $return;
	}

	public function getDataShipmentsByOrder($order){
		$response = array(
			array(
				'external' 		   => $order['order_id'],
				'shipping_company' => $order['shipping_company'],
				'shipping_method'  => $order['shipping_method']
			)
		);
		
		return $response;
	}

	public function getItemsToOrderPluggTo($order){
		$this->load->model('extension/feed/pluggto');
		$this->load->model('account/order');
		$this->load->model('catalog/product');

		$items = $this->model_account_order->getOrderProducts($order['order_id']);
		
		$response = array();
		foreach ($items as $item) {
			$product = $this->model_catalog_product->getProduct($item['product_id']);
			
			$response[] = array(
				'sku' => $product['sku'],
				'price' => $item['price'],
				'discount' => null,
				'quantity' => $item['quantity'],
				'total' => $item['total'],
				'external' => $item['product_id'],
				'variation' => array()
			);
		}

		return $response;
	}

  	public function exportAllProductsToPluggTo($product) {
	    $productPrepare = array();
	    $data = array();




    	if (empty($product['sku'])) {
			$this->model_extension_feed_pluggto->createLog(print_r(array('message' => 'not exist sku', 'sku' => $product['sku']), 1), 'exportAllProductsToPluggTo');
			return 'Problem SKU' . $product['name'];
    	}

		//$responseRemove = $this->model_extension_feed_pluggto->removeProduct($product['sku']);
		
		$data = array(
			'name'       => $product['name'],
			'sku'        => $product['sku'],
			'grant_type' => "authorization_code",
			'price'      => $product['price'],
			'quantity'   => $product['quantity'],
			'external'   => $product['product_id'],
			'description'=> html_entity_decode($product['description']),
			'brand'      => isset($product['manufacturer']) ? $product['manufacturer'] : '',
			'ean'        => $product['ean'],
			'nbm'        => isset($product['nbm']) ? $product['nbm'] : '',
			'isbn'       => $product['isbn'],
			'available'  => $product['status'],
			'dimension'  => array(
				'length' => (float) $product['length'],
				'width'  => (float) $product['width'],
				'height' => (float) $product['height'],
				'weight' => (float) $product['weight']
			),
			'photos'     => $this->getPhotosToSaveInOpenCart($product['product_id'], $product['image']),
			'link'       => 'http://' . $_SERVER['SERVER_NAME'] . '/index.php?route=product/product&product_id=' . $product['product_id'],
			'variations' => $this->getVariationsToSaveInOpenCart($product['product_id']),
			'attributes' => $this->getAtrributesToSaveInOpenCart($product['product_id']),
			'special_price' => isset($product['special']) ? $product['special'] : 0,
			'categories' => $this->getCategoriesToPluggTo($product['product_id'])
		);



		$response = $this->model_extension_feed_pluggto->sendToPluggTo($data, $product['sku']);

		$this->model_extension_feed_pluggto->createLog(print_r($response, 1), 'exportAllProductsToPluggTo');

	    return $response;
	}

	public function saveProductsInPluggto(){
		$this->load->model('catalog/product');
        $this->load->model('extension/feed/pluggto');

        $json = array(
            'action' => "export products to pluggto",
        );
        
        $products_opencart = $this->model_catalog_product->getProducts();
        $productPrepare = array();
        $data = array();

        $messageIndex = 0;
        foreach ($products_opencart as $product) {
            $data = array(
                'name'       => isset($product['name']) ? $product['name'] : '',
                'sku'        => isset($product['sku']) ? $product['sku'] : '',
                'model'      => isset($product['sku']) ? $product['sku'] : '',
                'price'      => isset($product['price']) ? $product['price'] : '',
                'quantity'   => isset($product['quantity']) ? $product['quantity'] : '',
                'external'   => isset($product['product_id']) ? $product['product_id'] : '',
                'description'=> isset($product['description']) ? $product['description'] : '',
                'brand'      => isset($product['brand']) ? $product['brand'] : '',
                'ean'        => isset($product['ean']) ? $product['ean'] : '',
                'nbm'        => isset($product['nbm']) ? $product['nbm'] : '',
                'isbn'       => isset($product['isbn']) ? $product['isbn'] : '',
                'available'  => isset($product['status']) ? $product['status'] : '',
                'dimension'  => array(
                  'length' => $product['length'],
                  'width'  => $product['width'],
                  'height' => $product['height']
                ),
                'photos'     => $this->model_extension_feed_pluggto->getPhotosToSaveInOpenCart($product['product_id'], $product['image']),
                'link'       => $_SERVER['SERVER_NAME'] . '/index.php?route=product/product&product_id=' . $product['product_id'],
                'variations' => $this->model_extension_feed_pluggto->getVariationsToSaveInOpenCart($product['product_id']),
                'attributes' => $this->model_extension_feed_pluggto->getAtrributesToSaveInOpenCart($product['product_id']),
            );
			
            $existProduct = $this->model_extension_feed_pluggto->getRelactionProductPluggToAndOpenCartByProductIdOpenCart($product['product_id']);

            if ($existProduct->num_rows > 0) {
                $response = $this->model_extension_feed_pluggto->updateTo($data, $existProduct->row['pluggto_product_id']);
                
                if (isset($response->Product)) {
                    $productId = $response->Product->id;

                    $json[$messageIndex]['status']  = true;
                    $json[$messageIndex]['message'] = "Product '$productId' updated successfully";
                } else {
                    $productId = $existProduct->row['pluggto_product_id'];
                    
                    $json[$messageIndex]['status']  = false;
                    $json[$messageIndex]['message'] = "Could not update product $productId ";
                }                

                continue;
            }

            $response = $this->model_extension_feed_pluggto->createTo($data);
            
            if (isset($response->Product->id)) {
                $json[$messageIndex]['status']  = $this->model_extension_feed_pluggto->createPluggToProductRelactionOpenCartPluggTo($response->Product->id, $product['product_id']);
                $json[$messageIndex]['message'] = "Products created successfully";
            } else {
                $json[$messageIndex]['status']  = false;
                $json[$messageIndex]['message'] = "Products could not be created";
            }

            $messageIndex++;
        }

		$this->model_extension_feed_pluggto->createLog(json_encode($json), 'saveProductsInPluggto');
        
        return json_encode($json);
    }

    public function forceSyncProduct() {
		$this->load->model('catalog/product');
        $this->load->model('extension/feed/pluggto');

		$product_id = $this->request->get['product_id'];
		$force = isset($this->request->get['force']) ? $this->request->get['force'] : true;
		
		if (isset($this->request->get['error'])) {
			$error = $this->request->get['error'];
		
			error_reporting($error);
		}

        $product = $this->model_catalog_product->getProduct($product_id);
        
	    $brand = isset($product['manufacturer']) ? $product['manufacturer'] : '';
		if (empty($brand)) {
			$brand = isset($product['model']) ? $product['model'] : '';
		}

		$productExist = $this->model_extension_feed_pluggto->getRelactionProductPluggToAndOpenCartByProductIdOpenCart($product_id);
		
		if ($force == true) {
			$data = array(
				'name'       => $product['name'],
				'sku'        => $product['sku'],
				'grant_type' => "authorization_code",
				'price'      => $product['price'],
				'quantity'   => $product['quantity'],
				'external'   => $product['product_id'],
				'description'=> html_entity_decode(str_replace('"', '\'', $product['description'])),
				'brand'      => isset($product['manufacturer']) ? $product['manufacturer'] : '',
				'ean'        => $product['ean'],
				'nbm'        => isset($product['nbm']) ? $product['nbm'] : '',
				'isbn'       => $product['isbn'],
				'available'  => $product['status'],
				'dimension'  => array(
					'length' => (float) $product['length'],
					'width'  => (float) $product['width'],
					'height' => (float) $product['height'],
					'weight' => (float) $product['weight']
				),
				'photos'     => $this->getPhotosToSaveInOpenCart($product['product_id'], $product['image']),
				'link'       => 'http://' . $_SERVER['SERVER_NAME'] . '/index.php?route=product/product&product_id=' . $product['product_id'],
				'variations' => $this->getVariationsToSaveInOpenCart($product['product_id']),
				'attributes' => $this->getAtrributesToSaveInOpenCart($product['product_id']),
				'special_price' => isset($product['special']) ? $product['special'] : 0,
				'categories' => $this->getCategoriesToPluggTo($product['product_id'])
			);

			$data['attributes'][] = array(
				'code'  => 'model',
				'label' => 'model',
				'value'	=> array(
					'code' => $product['model'],
					'label'=> $product['model']
				)
			);
		} else {
			$data = array(
				'sku'        => $product['sku'],
				'grant_type' => "authorization_code",
				'price'      => $product['price'],
				'quantity'   => $product['quantity'],
				'external'   => $product['product_id'],
				'variations' => $this->getVariationsToSaveInOpenCart($product['product_id'], $force),
				'special_price' => isset($product['special']) ? $product['special'] : 0
			);
		}

		if (!empty($productExist)) {
			if (!$this->model_extension_feed_pluggto->getSync('sync_name')) {
				unset($data['name']);
			}

			if (!$this->model_extension_feed_pluggto->getSync('sync_description')) {
				unset($data['description']);
			}

			if (!$this->model_extension_feed_pluggto->getSync('sync_price')) {
				unset($data['price']);
			}

			if (!$this->model_extension_feed_pluggto->getSync('sync_special_price')) {
				unset($data['special_price']);
			}

			if (!$this->model_extension_feed_pluggto->getSync('sync_dimensions')) {
				unset($data['dimension']);
			}

			if (!$this->model_extension_feed_pluggto->getSync('sync_quantity')) {
				unset($data['quantity']);
			}

			if (!$this->model_extension_feed_pluggto->getSync('sync_photos')) {
				unset($data['photos']);
			}

			if (!$this->model_extension_feed_pluggto->getSync('sync_categories')) {
				unset($data['categories']);
			}

			if (!$this->model_extension_feed_pluggto->getSync('sync_attributes')) {
				unset($data['attributes']);
			}

			if (!$this->model_extension_feed_pluggto->getSync('sync_brand')) {
				unset($data['brand']);
			}
		}
		$response = $this->model_extension_feed_pluggto->sendToPluggTo($data, $product['sku']);
		
		$this->model_extension_feed_pluggto->createLog(print_r($response, 1), 'exportAllProductsToPluggTo');
		$this->model_extension_feed_pluggto->createPluggToProductRelactionOpenCartPluggTo($response->Product->id, $product['product_id']);

		if (!isset($response->Product) && empty($response->Product))
		{
			echo '<hr>';
			
			echo 'Algo deu errado, tente novamente';
			exit;
		}

		echo '<hr>';
		echo 'O produto foi enviado para plugg.to';
		exit;
    }

    public function refreshStockAndPrice() {
		$this->load->model('catalog/product');
        $this->load->model('extension/feed/pluggto');

    	$product_id = $this->request->get['product_id'];

        $productOnOpenCart = $this->model_catalog_product->getProduct($product_id);
        
		if (empty($productOnOpenCart['sku']))
			$productOnOpenCart['sku'] = $productOnOpenCart['product_id'];

		$productOnPluggTo = $this->model_extension_feed_pluggto->getProductBySKU($productOnOpenCart['sku']);

		$response = array();
		foreach ($productOnPluggTo->Product->variations as $i => $variation)
		{
			$nameExplode = explode(' - ', $variation->name);

			$nameEnd = $nameExplode[1];

			$optionId = $this->model_extension_feed_pluggto->getOptionIdByName($nameEnd);

			$variationOc = $this->model_extension_feed_pluggto->getProductOptionValueId($optionId, $productOnOpenCart['product_id']);

			if (!empty($variationOc->row))
			{			
				if ($variation->quantity != $variationOc->row['quantity'])
				{
					$newStock = array(
						'quantity' => $variationOc->row['quantity'],
						'action'   => 'update'
					);	

					$response[] = $this->model_extension_feed_pluggto->refreshStock($variation->sku, $newStock);
				}

				if ($productOnOpenCart['price'] != $productOnPluggTo->Product->price)
				{
					$response[] = $this->refreshProductOnPluggTo($product_id);
				}

			}

		}

		echo json_encode(array('message' => 'sucess', 'response' => $response));

		exit;
    }

	public function refreshProductOnPluggTo($product_id) {
		$this->load->model('catalog/product');

        $this->load->model('extension/feed/pluggto');

        $productOnOpenCart = $this->model_catalog_product->getProduct($product_id);

		if (empty($product['sku']))
		{
			$product['sku'] = $product['product_id'];
		}

		$data = array(
			'sku'        	=> $productOnOpenCart['sku'],
			'grant_type' 	=> "authorization_code",
			'price'      	=> $productOnOpenCart['price'],
			'special_price'	=> $productOnOpenCart['special']
		);

		$response = $this->model_extension_feed_pluggto->sendToPluggTo($data, $productOnOpenCart['sku']);

		return $response;
	}
	
    public function importAllProductsToOpenCart(){
        $this->load->model('extension/feed/pluggto');

        $response = array(
            'action' => "import products from pluggto",
        );
        
        $result = $this->model_extension_feed_pluggto->getProducts(1);
        foreach ($result->result as $i => $product) {
           $return = $this->model_extension_feed_pluggto->prepareToSaveInOpenCart($product);
		
           $productId = $product->Product->id;
           
           $response[$i]['status']  = $return;
           $response[$i]['message'] = $return === true ? "Product '$productId' imported successfully" : "Produts Could not be imported";
        }

		$this->model_extension_feed_pluggto->createLog(print_r($response, 1), 'importAllProductsToOpenCart');

        return json_encode($response);
    }

	public function getNotification(){
		$this->load->model('extension/feed/pluggto');
		
		$inputJSON = file_get_contents('php://input');
		$data      = json_decode($inputJSON, true); //convert JSON into array

		$fields = array(
			'resource_id'   => empty($data['id']) ? '' : $data['id'],
			'type'          => empty($data['type']) ? '' : $data['type'],
			'action'        => empty($data['action']) ? '' : $data['action'],
			'date_created'  => date('Y-m-d h:i:s', time()),
			'date_modified' => date('Y-m-d h:i:s', time()),
			'status'        => 1
		);

		$result = $this->model_extension_feed_pluggto->createNotification($fields);

		$response = array(
			'message' => $result === true ? 'Notification received sucessfully' : 'Failure getting notification. The field: ' . $result . ' can not be empty',
			'code'    => 200,
			'status'  => is_bool($result) ? $result : false
		);

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: POST');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->model_extension_feed_pluggto->createLog(print_r($response, 1), 'getNotification');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($response));
	}

	public function existNewOrders(){
    	$this->load->model('extension/feed/pluggto');

    	$response  = array();
		$allOrders = $this->model_extension_feed_pluggto->getOrders();

		foreach ($allOrders->rows as $order) {
			if ($this->model_extension_feed_pluggto->orderExistInPluggTo($order['order_id'])) {
				continue;
			}

			$response[] = $order;
		}

		return $response;
	}

	public function getPhotosToSaveInOpenCart($product_id, $image_main) {
		$images = $this->model_catalog_product->getProductImages($product_id);

		$response = array();

		if (isset($image_main) && !empty($image_main))
		{
			$response[] = array(
			    'url'   => 'http://' . $_SERVER['SERVER_NAME'] . '/image/' . $image_main,
			    'title' => 'Imagem principal do produto',
			    'order' => 1
			);
		}

		foreach ($images as $i => $image) {
			$response[] = array(
			    'url'   => 'http://' . $_SERVER['SERVER_NAME'] . '/image/' . $image['image'],
			    'title' => 'Imagem principal do produto',
			    'order' => $image['sort_order']
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

		  	if (!$item['subtract'])
		  		continue;

			$attributes = array();
			foreach ($attributesTemp as $value) {
				$attributes[] = $value;
			}

			$attributes[] = array(
				'code'  => 'size',
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

	public function getSpecialPriceProductToPluggTo($product_id) {
		$specialPrice = $this->model_catalog_product->getProductSpecials($product_id);
		$special = isset($specialPrice['special']) ? $specialPrice['special'] : 0.00;

		if (isset($special) && !empty($special))
			return end($special);

		return null;
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

	public function getCategoriesToPluggTo($product_id) {
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		
		$categories = $this->model_catalog_product->getCategories($product_id);

		$response = array();
		foreach ($categories as $category) {
			$categoryData = $this->model_catalog_category->getCategory($category['category_id']);
			$firstParent  = (isset($categoryData['parent_id']) && !empty($categoryData['parent_id'])) ? $this->model_catalog_category->getCategory($categoryData['parent_id']) : null;
			$secondParent = (isset($firstParent['parent_id']) && !empty($firstParent['parent_id'])) ? $this->model_catalog_category->getCategory($firstParent['parent_id']) : null;
			$thirdParent  = (isset($secondParent['parent_id']) && !empty($secondParent['parent_id'])) ? $this->model_catalog_category->getCategory($secondParent['parent_id']) : null;

			$response[] = array(
				'name' => ((isset($categoryData['name'])) ? $categoryData['name'] : '') 
						. ((isset($firstParent['name']))  ? ' > ' . $firstParent['name'] : '')
				 		. ((isset($secondParent['name'])) ? ' > ' . $secondParent['name'] : '')
				  		. ((isset($thirdParent['name']))  ? ' > ' . $thirdParent['name'] : '')
			);
		}
		
		return $response;
	}

	public function getAllItemsQueue(){
		$response = $this->db->query("SELECT * FROM " . DB_PREFIX . "pluggto_products_queue WHERE process = 0");

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
		
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		
			$this->response->addHeader('Access-Control-Max-Age: 1000');
		
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');		
		}

		$this->response->addHeader('Content-Type: application/json');
		
		$this->response->setOutput(json_encode($response));
	}

	public function getAllLogs(){
		$response = $this->db->query("SELECT * FROM " . DB_PREFIX . "pluggto_log ORDER BY id DESC LIMIT 100");

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
		
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		
			$this->response->addHeader('Access-Control-Max-Age: 1000');
		
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');		
		}

		$this->response->addHeader('Content-Type: application/json');
		
		$this->response->setOutput(json_encode($response));
	}

	public function getDefaultFieldColor()
	{
		$sql = $this->db->query("SELECT field_pluggto FROM " . DB_PREFIX . "pluggto_linkage_fields WHERE field_opencart = 'color'");

		return $sql->row['field_pluggto'];
	}

	public function getDefaultFieldSize()
	{
		$sql = $this->db->query("SELECT field_pluggto FROM " . DB_PREFIX . "pluggto_linkage_fields WHERE field_opencart = 'size'");

		return $sql->row['field_pluggto'];
	}
	
	public function getProductsActives() {
		$this->load->model('catalog/product');

		$offset = 0;
		
		if (isset($this->request->get['offset'])) {
			$offset = $this->request->get['offset'];
		}

		$response = $this->model_catalog_product->getProducts(array('filter_status' => 1,'start' => $offset ,'limit' => 1000));
		
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);

			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

			$this->response->addHeader('Access-Control-Max-Age: 1000');

			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');

		$this->response->setOutput(json_encode($response));
	}

	public function getProductsUpdatedLastHour() {
		$this->load->model('catalog/product');
		$this->load->model('extension/feed/pluggto');

		$response = $this->model_extension_feed_pluggto->getProductsUpdatedLastHour();

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);

			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

			$this->response->addHeader('Access-Control-Max-Age: 1000');

			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');

		$this->response->setOutput(json_encode($response));
	}

}
