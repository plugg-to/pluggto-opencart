<?php

ini_set('memory_limit', '-1');
error_reporting(-1);

class ControllerApiPluggto extends Controller {

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
		$this->load->model('pluggto/pluggto');

		$num_orders_opencart = $this->saveOrdersInOpenCart($this->existNewOrdersPluggTo());

        $response = array(
            'orders_created_or_updated_opencart' => $num_orders_opencart,
        );

        $this->model_pluggto_pluggto->createLog(print_r($response, 1), 'cronOrders');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($response));
	}

	public function cronProducts(){
		exit('Function deprecated from version 2.0.0 of plugin Plugg.To opencart');

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);

			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

			$this->response->addHeader('Access-Control-Max-Age: 1000');

			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');

		$this->load->model('pluggto/pluggto');
		$this->load->model('catalog/product');

		$products = $this->model_catalog_product->getProducts();

		$return = array();
		foreach ($products as $i => $product) {
			$result = $this->model_pluggto_pluggto->getProductBySKU($product['sku']);

			if (isset($result->Product->id))
				$return[$i] = json_encode($this->exportAllProductsToPluggTo($product));
		}

		$this->response->setOutput(json_encode($return));
	}

	public function cronUpdateProducts(){
		exit('Function deprecated from version 2.0.0 of plugin Plugg.To opencart');

		$this->load->model('pluggto/pluggto');

		$productsQuery = $this->model_pluggto_pluggto->getProductsNotification();

		$message = array();
		foreach ($productsQuery as $key => $value) {
			if ($value['type'] != 'products') {
				continue;
			}

			$product = $this->model_pluggto_pluggto->getProduct($value['resource_id']);

			$product = isset($product->result[0]) ? $product->result[0] : (isset($product->Product) ? $product : null);

			if (isset($product)) {
				try {
					$response = $this->model_pluggto_pluggto->updateStockAndPrice($product);

					$message[$key]['resource_id'] = $product->Product->id;
					$message[$key]['saved']       = $this->model_pluggto_pluggto->updateStatusNotification($product->Product->id);
				} catch (Exception $e) {
					$message[$key]['resource_id'] = $product->Product->id;
					$message[$key]['saved']       = $this->model_pluggto_pluggto->updateStatusNotification($product->Product->id);
				}
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$response = array(
			'code'    => 200,
			'message' => $message
		);

        $this->model_pluggto_pluggto->createLog(print_r($response, 1), 'cronUpdateProducts');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($response));
	}

	public function processQueue(){
		$this->load->model('catalog/product');
		$this->load->model('pluggto/pluggto');

		$productsQueue = $this->model_pluggto_pluggto->getQueuesProducts('opencart');

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

					$this->model_pluggto_pluggto->processedQueueProduct($product['product_id'], "opencart");
				} catch (Exception $e) {
					continue;
				}
			}
		}

		$productsQueue = $this->model_pluggto_pluggto->getQueuesProducts('pluggto');

		if (!empty($productsQueue))
		{
			foreach ($productsQueue as $product) {
				try {
			        $product = $this->model_pluggto_pluggto->getProduct($product['product_id_pluggto']);
		            $return = $this->model_pluggto_pluggto->prepareToSaveInOpenCart($product);

					$response[$product->Product->id]['status']  = $return;
					$response[$product->Product->id]['message'] = $return !== 'Problem SKU' ? "Product '$productId' imported successfully" : "Produts Could not be imported";

					$this->model_pluggto_pluggto->processedQueueProduct($product->Product->id, "pluggto");
				} catch (Exception $e) {
					continue;
				}
			}
		}

        $this->model_pluggto_pluggto->createLog(print_r($response, 1), 'processQueue');

		$this->response->setOutput(json_encode($response));
	}

	public function existNewOrdersPluggTo() {
		$this->load->model('pluggto/pluggto');

		$response = array();

		$notifications = $this->model_pluggto_pluggto->getNotifications(100, 'orders');

		foreach ($notifications as $notification) {
			$order = $this->model_pluggto_pluggto->getOrderPluggTo($notification['resource_id']);

			if (!isset($order->Order) && empty($order->Order)) {
				$this->model_pluggto_pluggto->createLog(print_r($order, 1), 'existNewOrdersPluggTo');
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

		$currency = $this->model_pluggto_pluggto->getCurrencyMain();

		foreach ($orders as $id_pluggto => $order) {
			try {
				$customer    = $this->model_pluggto_pluggto->getCustomerByEmail($order->Order->receiver_email);
				$customer_id =  $customer['customer_id'];

				$customer = array(
					'customer_group_id'  => 1,
					'firstname' 		 => (isset($order->Order->payer_name) ? $order->Order->payer_name : null),
					'lastname' 			 => (isset($order->Order->payer_lastname) ? $order->Order->payer_lastname : null),
					'email' 			 => (isset($order->Order->receiver_email) ? $order->Order->receiver_email : null),
					'telephone' 		 => (isset($order->Order->receiver_phone) ? $order->Order->receiver_phone : null),
					'fax' 				 => (isset($order->Order->receiver_phone) ? $order->Order->receiver_phone : null),
					'payment_firstname'  => (isset($order->Order->payer_name) ? $order->Order->payer_name : null),
					// 'custom_field'		 => [
					// 	2 => (isset($order->Order->payer_cpf) ? $order->Order->payer_cpf : null)
					// ]
				);

				if (empty($customer_id)) {
					$customer_id = $this->model_account_customer->addCustomer($customer);
				}

				if (!empty($customer_id)) {
					$this->model_pluggto_pluggto->editCustomer($customer, $customer_id);
				}

				$address = array(
					'firstname'    => (isset($order->Order->payer_name) ? $order->Order->payer_name : null),
					'lastname'     => (isset($order->Order->payer_lastname) ? $order->Order->payer_lastname : null),
					'company'      => '',
					'address_1'    => (isset($order->Order->payer_address) ? $order->Order->payer_address : null),
					'address_2'    => (isset($order->Order->payer_neighborhood) ? $order->Order->payer_neighborhood : null),
					'postcode'     => (isset($order->Order->payer_zipcode) ? $order->Order->payer_zipcode : null),
					'city'         => (isset($order->Order->payer_city) ? $order->Order->payer_city : null),
					'zone_id'      => $this->getPaymentZoneIDByCity((isset($order->Order->payer_state) ? $order->Order->payer_state : null)),
					'country_id'   => 30,
					// 'custom_field' => [
					// 	7 => (isset($order->Order->receiver_address_number) ? $order->Order->receiver_address_number : null),
					// 	8 => (isset($order->Order->receiver_address_complement) ? $order->Order->receiver_address_complement : null)
					// ]
				);

				$data = array(
					'invoice_prefix' 	 => (isset($order->Order->id) ? $order->Order->id : null),
					'store_id'			 => (isset($order->Order->id) ? $order->Order->id : null),
					'store_name' 		 => $this->config->get('config_name'),
					'store_url' 		 => HTTP_SERVER,
					'customer_id' 		 => $customer_id,
					'customer_group_id'  => 1,
					'firstname' 		 => (isset($order->Order->payer_name) ? $order->Order->payer_name : null),
					'lastname' 			 => (isset($order->Order->payer_lastname) ? $order->Order->payer_lastname : null),
					'email' 			 => (isset($order->Order->receiver_email) ? $order->Order->receiver_email : null),
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
					'payment_zone' 		 => (isset($order->Order->payer_city) ? $order->Order->payer_city : null),
					'payment_zone_id' 	 => $this->getPaymentZoneIDByCity((isset($order->Order->payer_state) ? $order->Order->payer_state : null)),
					'payment_method' 	 => $this->getPaymentMethodByOrderPluggTo($order->Order),
					'payment_code'		 => $this->getPaymentCodeByOrderPluggTo($order->Order),
					'shipping_firstname' => (isset($order->Order->receiver_name) ? $order->Order->receiver_name : null),
					'shipping_lastname'  => (isset($order->Order->receiver_lastname) ? $order->Order->receiver_lastname : null),
					'shipping_company' 	 => '',
					'shipping_address_1' => (isset($order->Order->receiver_address) ? $order->Order->receiver_address : null),
					'shipping_address_2' => (isset($order->Order->payer_neighborhood) ? $order->Order->payer_neighborhood : null),
					'shipping_city' 	 => (isset($order->Order->receiver_city) ? $order->Order->receiver_city : null),
					'shipping_postcode'  => (isset($order->Order->receiver_zipcode) ? $order->Order->receiver_zipcode : null),
					'shipping_country'   => (isset($order->Order->receiver_country) ? $order->Order->receiver_country : null),
					'shipping_zone' 	 => (isset($order->Order->receiver_city) ? $order->Order->receiver_city : null),
					'shipping_zone_id' 	 => $this->getPaymentZoneIDByCity((isset($order->Order->receiver_city) ? $order->Order->receiver_city : null)),
					'shipping_method' 	 => (isset($order->Order->shipments[0]->shipping_method) ? $order->Order->shipments[0]->shipping_method : null),
					'shipping_code' 	 => (isset($order->Order->shipments[0]->track_code) ? $order->Order->shipments[0]->track_code : null),
					'store_id'			 => 0,
					'comment' 			 => '',
					'total' 			 => (isset($order->Order->total) ? $order->Order->total : null),
					'totals'			 => array(
						array(
							'code'  	 => 'sub_total',
							'title' 	 => 'Sub-total',
							'value' 	 => (isset($order->Order->total) ? $order->Order->total : null),
							'sort_order' => 1,
						),
						array(
							'code'  	 => 'total',
							'title' 	 => 'Total',
							'value' 	 => (isset($order->Order->total) ? $order->Order->total : null),
							'sort_order' => 9,
						),
					),
					'currency_id' 		 => $currency['currency_id'],
					'currency_code' 	 => $currency['currency_code'],
					'currency_value' 	 => $currency['currency_value'],
					'order_product'		 => $this->getProductsToSaveOpenCart($order),
					//'products' 			 => $this->getProductsToSaveOpenCart($order),
					'custom_field'		 => array(
						2 => (isset($order->Order->payer_cpf) ? $order->Order->payer_cpf : null),
					),
					'order_status_id' => $this->model_pluggto_pluggto->getStatusSaleByHistory($order->Order->status_history),
					'shipping_custom_field' => array(
						8 => (isset($order->Order->receiver_address_complement) ? $order->Order->receiver_address_complement : null),
						7 => (isset($order->Order->receiver_address_number) ? $order->Order->receiver_address_number : null)
					),
					'payment_custom_field' => array(
						8 => (isset($order->Order->receiver_address_complement) ? $order->Order->receiver_address_complement : null),
						7 => (isset($order->Order->receiver_address_number) ? $order->Order->receiver_address_number : null)
					)
				);
				
				$existOrderID = $this->model_pluggto_pluggto->orderExistInPluggTo($id_pluggto);

				if ($existOrderID) {
					$response_id = $existOrderID;

					$this->addOrderHistory($response_id, $this->model_pluggto_pluggto->getStatusSaleByHistory($order->Order->status_history));
				} else {
					$response_id = $this->addOrder($data);

					$this->addOrderHistory($response_id, $this->model_pluggto_pluggto->getStatusSaleByHistory($order->Order->status_history));
				}
				
				$this->model_pluggto_pluggto->createRelationOrder($order->Order->id, $response_id);

				$this->model_pluggto_pluggto->updateStatusNotification($id_pluggto, json_encode(array('success' => true, 'message' => 'OK')));
			} catch (Exception $e) {
				$this->model_pluggto_pluggto->updateStatusNotification($id_pluggto, json_encode(array('success' => false, 'message' => $e->getMessage())));
			}

			$i++;
		}

		return $i;
	}

	public function getProductsToSaveOpenCart($order) {
		if (!isset($order->Order->items) && empty($order->Order->items)) {
			return false;
		}

		$response = array();
		foreach ($order->Order->items as $key => $item) {
			$response[] = array(
				'product_id' => $this->model_pluggto_pluggto->getIDItemBySKU($item->sku),
				'name'       => $item->name,
				'model'	     => $item->sku,
				'quantity'   => $item->quantity,
				'price'		 => $item->price,
				'total'		 => $item->total,
				'tax'		 => 0,
				'reward'	 => 0,
				'option'     => array(),
				'download'   => array()
			);
		}

		return $response;
	}

	public function getPaymentZoneIDByCity($city) {
		$response = $this->model_pluggto_pluggto->getPaymentZoneIDByCity($city);

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
    	$this->load->model('pluggto/pluggto');

    	$cont = 0;
    	$return = array();
    	foreach ($orders as $order) {
    		$params = array(
    			'external' 			  => $order['order_id'],
    			'status' 			  => $this->model_pluggto_pluggto->getStatusToPluggToByStatusOpenCart($order['order_status_id']),
    			'total' 			  => $this->model_pluggto_pluggto->getOrderTotalByCode($order['order_id'], 'total'),
    			'subtotal' 			  => $this->model_pluggto_pluggto->getOrderTotalByCode($order['order_id'], 'sub_total'),
    			'shipping' 			  => $this->model_pluggto_pluggto->getOrderTotalByCode($order['order_id'], 'shipping'),
    			'discount' 			  => $this->model_pluggto_pluggto->getOrderTotalByCode($order['order_id'], 'discount'),
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
    			'delivery_type'       => $this->model_pluggto_pluggto->getShippingMethodToPluggByOpenCart($order['shipping_method']),
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

     		$response = $this->model_pluggto_pluggto->getRelactionOrder($order['order_id']);
     		echo '<pre>';print_r($response);exit;
     		$return[$response['order_id_pluggto']] = 'Não editado, pedido criado direto no PluggTo';

     		// if (empty($response))
     		// {
     		// 	$responsePluggTo = $this->model_pluggto_pluggto->createOrder($params);

     		// 	if (!empty($responsePluggTo->Order->id))
     		// 	{
     		// 		$this->model_pluggto_pluggto->createRelationOrder($responsePluggTo->Order->id, $order['order_id']);
     		// 	}

     		// 	$return[$responsePluggTo->Order->id] = print_r($responsePluggTo, 1);
     		// }

     		if (!empty($response))
     		{
     			$responsePluggTo = $this->model_pluggto_pluggto->editOrder($params, $response['order_id_pluggto']);

     			$return[$response['order_id_pluggto']] = print_r($responsePluggTo, 1);
     		}
    	}

        $this->model_pluggto_pluggto->createLog(print_r($return, 1), 'saveOrdersInPluggTo');

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
		$this->load->model('pluggto/pluggto');
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
			$this->model_pluggto_pluggto->createLog(print_r(array('message' => 'not exist sku', 'sku' => $product['sku']), 1), 'exportAllProductsToPluggTo');
			return 'Problem SKU' . $product['name'];
    	}

		$responseRemove = $this->model_pluggto_pluggto->removeProduct($product['sku']);

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

		$response = $this->model_pluggto_pluggto->sendToPluggTo($data, $product['sku']);

		$this->model_pluggto_pluggto->createLog(print_r($response, 1), 'exportAllProductsToPluggTo');

	    return $response;
	}

	public function saveProductsInPluggto(){
		$this->load->model('catalog/product');
        $this->load->model('pluggto/pluggto');

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
                'photos'     => $this->model_pluggto_pluggto->getPhotosToSaveInOpenCart($product['product_id'], $product['image']),
                'link'       => $_SERVER['SERVER_NAME'] . '/index.php?route=product/product&product_id=' . $product['product_id'],
                'variations' => $this->model_pluggto_pluggto->getVariationsToSaveInOpenCart($product['product_id']),
                'attributes' => $this->model_pluggto_pluggto->getAtrributesToSaveInOpenCart($product['product_id']),
            );

            $existProduct = $this->model_pluggto_pluggto->getRelactionProductPluggToAndOpenCartByProductIdOpenCart($product['product_id']);

            if ($existProduct->num_rows > 0) {
                $response = $this->model_pluggto_pluggto->updateTo($data, $existProduct->row['pluggto_product_id']);

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

            $response = $this->model_pluggto_pluggto->createTo($data);

            if (isset($response->Product->id)) {
                $json[$messageIndex]['status']  = $this->model_pluggto_pluggto->createPluggToProductRelactionOpenCartPluggTo($response->Product->id, $product['product_id']);
                $json[$messageIndex]['message'] = "Products created successfully";
            } else {
                $json[$messageIndex]['status']  = false;
                $json[$messageIndex]['message'] = "Products could not be created";
            }

            $messageIndex++;
        }

		$this->model_pluggto_pluggto->createLog(json_encode($json), 'saveProductsInPluggto');

        return json_encode($json);
    }

    public function refreshStockAndPrice() {
		$this->load->model('catalog/product');
        $this->load->model('pluggto/pluggto');

    	$product_id = $this->request->get['product_id'];

        $productOnOpenCart = $this->model_catalog_product->getProduct($product_id);
        
		if (empty($productOnOpenCart['sku']))
			$productOnOpenCart['sku'] = $productOnOpenCart['product_id'];

		$productOnPluggTo = $this->model_pluggto_pluggto->getProductBySKU($productOnOpenCart['sku']);

		$response = array();
		foreach ($productOnPluggTo->Product->variations as $i => $variation)
		{
			$nameExplode = explode(' - ', $variation->name);

			$nameEnd = $nameExplode[1];

			$optionId = $this->model_pluggto_pluggto->getOptionIdByName($nameEnd);

			$variationOc = $this->model_pluggto_pluggto->getProductOptionValueId($optionId, $productOnOpenCart['product_id']);

			if (!empty($variationOc->row))
			{			
				if ($productOnOpenCart['price'] != $productOnPluggTo->Product->price)
				{
					$response[] = $this->refreshProductOnPluggTo($product_id);
				}

				if ($variation->quantity != $variationOc->row['quantity'] || $variation->price != $productOnPluggTo->Product->price)
				{
					$response[] = $this->refreshStock($productOnOpenCart['sku'], $variationOc->row['quantity'], $variation->sku, $productOnPluggTo->Product->price, $productOnPluggTo->Product->special_price);
				}

			}

			continue;

		}

		echo json_encode(array('message' => 'sucess', 'response' => $response));

		exit;
    }	

    public function refreshStock($sku, $newStock, $skuFilho, $price, $specialPrice)
    {
		$this->load->model('catalog/product');

        $this->load->model('pluggto/pluggto');

		$data = array(
			'sku'        => $sku,
			'grant_type' => "authorization_code",
			'variations' => array(
				array(
					'sku' 			=> $skuFilho,
					'quantity'		=> $newStock,
					'price'			=> $price,
					'special_price' => $specialPrice
				)
			)
		);

		$response = $this->model_pluggto_pluggto->sendToPluggTo($data, $sku);

		return $response;
    }

	public function refreshProductOnPluggTo($product_id) {
		$this->load->model('catalog/product');

        $this->load->model('pluggto/pluggto');

        $productOnOpenCart = $this->model_catalog_product->getProduct($product_id);

		if (empty($productOnOpenCart['sku']))
		{
			$productOnOpenCart['sku'] = $productOnOpenCart['product_id'];
		}

		$data = array(
			'sku'        	=> $productOnOpenCart['sku'],
			'grant_type' 	=> "authorization_code",
			'price'      	=> $productOnOpenCart['price'],
			'special_price'	=> $productOnOpenCart['special']
		);

		$response = $this->model_pluggto_pluggto->sendToPluggTo($data, $productOnOpenCart['sku']);

		return $response;
	}

    public function forceSyncProduct(){
		$this->load->model('catalog/product');
        $this->load->model('pluggto/pluggto');

    	$product_id = $this->request->get['product_id'];

        $product = $this->model_catalog_product->getProduct($product_id);

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

		$response = $this->model_pluggto_pluggto->sendToPluggTo($data, $product['sku']);

		$this->model_pluggto_pluggto->createLog(print_r($response, 1), 'exportAllProductsToPluggTo');

		if (!isset($response->Product) && empty($response->Product))
		{
			echo 'Algo deu errado, tente novamente';
			exit;
		}

		echo 'O produto foi enviado para plugg.to';
		exit;
    }

    public function importAllProductsToOpenCart(){
        $this->load->model('pluggto/pluggto');

        $response = array(
            'action' => "import products from pluggto",
        );

        $result = $this->model_pluggto_pluggto->getProducts(1);
        foreach ($result->result as $i => $product) {
           $return = $this->model_pluggto_pluggto->prepareToSaveInOpenCart($product);

           $productId = $product->Product->id;

           $response[$i]['status']  = $return;
           $response[$i]['message'] = $return === true ? "Product '$productId' imported successfully" : "Produts Could not be imported";
        }

		$this->model_pluggto_pluggto->createLog(print_r($response, 1), 'importAllProductsToOpenCart');

        return json_encode($response);
    }

	public function getNotification(){
		$this->load->model('pluggto/pluggto');

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

		$result = $this->model_pluggto_pluggto->createNotification($fields);

		$response = array(
			'message' => $result === true ? 'Notification received sucessfully' : 'Failure getting notification. The field: '.$result.' can not be empty',
			'code'    => 200,
			'status'  => is_bool($result) ? $result : false
		);

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: POST');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->model_pluggto_pluggto->createLog(print_r($response, 1), 'getNotification');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($response));
	}

	public function existNewOrders(){
    	$this->load->model('pluggto/pluggto');

    	$response  = array();
		$allOrders = $this->model_pluggto_pluggto->getOrders();

		foreach ($allOrders->rows as $order) {
			if ($this->model_pluggto_pluggto->orderExistInPluggTo($order['order_id'])) {
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
			    'url' =>  'http://' . $_SERVER['SERVER_NAME'] . '/image/' . $image_main,
			    'remove' => true
		    );

			$response[] = array(
			    'url'   => 'http://' . $_SERVER['SERVER_NAME'] . '/image/' . $image_main,
			    'title' => 'Imagem principal do produto',
			    'order' => 0
			);
		}

		foreach ($images as $i => $image) {

			$response[] = array(
			    'url' =>  'http://' . $_SERVER['SERVER_NAME'] . '/image/' . $image['image'],
			    'remove' => true
		    );

			$response[] = array(
			    'url'   => 'http://' . $_SERVER['SERVER_NAME'] . '/image/' . $image['image'],
			    'title' => 'Imagem principal do produto',
			    'order' => $image['sort_order']
			);
		}

		return $response;
	}

	public function getVariationsToSaveInOpenCart($product_id) {
		$product = $this->model_catalog_product->getProduct($product_id);
		$options = $this->model_catalog_product->getProductOptions($product_id);

		$response = array();
		foreach ($options as $i => $option) {
		  if (!$option['required'])
		  	continue;

		  foreach ($option['option_value'] as $item) {
			$attributesTemp = $this->getAtrributesToSaveInOpenCart($product_id);

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
						'label' => $attr['text'],
						'value' => array(
							'code'  => $attr['name'],
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

	public function addOrder($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_company_id = '" . $this->db->escape($data['payment_company_id']) . "', payment_tax_id = '" . $this->db->escape($data['payment_tax_id']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', date_added = NOW(), date_modified = NOW()");

		$order_id = $this->db->getLastId();

      	if (isset($data['order_product'])) {		
      		foreach ($data['order_product'] as $order_product) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$order_product['product_id'] . "', name = '" . $this->db->escape($order_product['name']) . "', model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . (int)$order_product['quantity'] . "', price = '" . (float)$order_product['price'] . "', total = '" . (float)$order_product['total'] . "', tax = '" . (float)$order_product['tax'] . "', reward = '" . (int)$order_product['reward'] . "'");
			
				$order_product_id = $this->db->getLastId();
				
				$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");
				
				if (isset($order_product['order_option'])) {
					foreach ($order_product['order_option'] as $order_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$order_option['product_option_id'] . "', product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");
						
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
			}
		}

		foreach ($data['products'] as $product) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");

			$order_product_id = $this->db->getLastId();

			foreach ($product['option'] as $option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
			}

			foreach ($product['download'] as $download) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'");
			}
		}

		foreach ($data['vouchers'] as $voucher) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");
		}

		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
		}

		return $order_id;
	}

	public function addOrderHistory($order_id, $order_status_id){
		return $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . $order_status_id . "' WHERE order_id = '" . $order_id . "'");
	}

	public function getProductsActives() {
		$this->load->model('catalog/product');

		$response = $this->model_catalog_product->getProducts(array('filter_status' => 1));

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
		$this->load->model('pluggto/pluggto');

		$response = $this->model_pluggto_pluggto->getProductsUpdatedLastHour();

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);

			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

			$this->response->addHeader('Access-Control-Max-Age: 1000');

			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');

		$this->response->setOutput(json_encode($response));
	}

	public function getOrdersUpdatedLastHour() {
		$this->load->model('pluggto/pluggto');

		$this->load->model('pluggto/pluggto');

		$orders = $this->model_pluggto_pluggto->getOrdersUpdatedLastHour();

		$num_orders_pluggto = $this->saveOrdersInPluggTo($orders);

        $response = array(
            'orders_created_or_updated_pluggto' => $num_orders_pluggto,
        );

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