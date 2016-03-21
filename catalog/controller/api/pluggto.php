<?php

ini_set('memory_limit', '-1');
error_reporting(0);

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

	public function cronUpdateOrders() {
		$this->load->model('pluggto/pluggto');

		$num_orders_pluggto = $this->saveOrdersInPluggTo($this->model_pluggto_pluggto->getOrders()->rows);
	        
        $response = array(
            'orders_created_or_updated_pluggto' => $num_orders_pluggto,
        );

        $this->model_pluggto_pluggto->createLog(print_r($response, 1), 'cronUpdateOrders');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($response));
	}

	public function cronProducts(){
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
		
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		
			$this->response->addHeader('Access-Control-Max-Age: 1000');
		
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');		
		}

		$this->response->addHeader('Content-Type: application/json');
		
		$this->response->setOutput($this->exportAllProductsToPluggTo());	
	}

	public function cronUpdateProducts(){
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
					$response = $this->model_pluggto_pluggto->prepareToSaveInOpenCart($product);						
					
					$message[$key]['resource_id'] = $product->Product->id;
					$message[$key]['saved']       = $this->model_pluggto_pluggto->updateStatusNotification($product->Product->id);
				} catch (Exception $e) {
					$message[$key]['resource_id'] = $product->Product->id;
					$message[$key]['saved']       = $this->model_pluggto_pluggto->updateStatusNotification($product->Product->id);
				}					
			}
		}

		// $priceAndStock = $this->verifyStockAndPriceProducts();

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
		$this->load->model('checkout/order');

		$currency = $this->model_pluggto_pluggto->getCurrencyMain();

		foreach ($orders as $id_pluggto => $order) {
			try {
				$data = array(
					'invoice_prefix' 	 => (isset($order->Order->id) ? $order->Order->id : null),
					'store_id'			 => (isset($order->Order->id) ? $order->Order->id : null),
					'store_name' 		 => $this->config->get('config_name'),
					'store_url' 		 => HTTP_SERVER,
					'customer_id' 		 => 0,
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
					'payment_city' 		 => (isset($order->Order->payer_city) ? $order->Order->payer_city : null),
					'payment_postcode' 	 => (isset($order->Order->payer_zipcode) ? $order->Order->payer_zipcode : null),
					'payment_country' 	 => (isset($order->Order->payer_country) ? $order->Order->payer_country : null),
					'payment_country_id' => 30,
					'payment_zone' 		 => (isset($order->Order->payer_city) ? $order->Order->payer_city : null),
					'payment_zone_id' 	 => $this->getPaymentZoneIDByCity((isset($order->Order->payer_city) ? $order->Order->payer_city : null)),
					'payment_method' 	 => $this->getPaymentMethodByOrderPluggTo($order->Order),
					'payment_code'		 => $this->getPaymentCodeByOrderPluggTo($order->Order),
					'shipping_firstname' => (isset($order->Order->receiver_name) ? $order->Order->receiver_name : null),
					'shipping_lastname'  => (isset($order->Order->receiver_lastname) ? $order->Order->receiver_lastname : null),
					'shipping_company' 	 => '',
					'shipping_address_1' => (isset($order->Order->receiver_address) ? $order->Order->receiver_address : null),
					'shipping_address_2' => (isset($order->Order->receiver_address_number) ? $order->Order->receiver_address_number : null),
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
					'products' 			 => $this->getProductsToSaveOpenCart($order)
				);

				$existOrderID = $this->model_pluggto_pluggto->orderExistInPluggTo($id_pluggto);
				
				if ($existOrderID) {
					// $response_id = $this->model_checkout_order->editOrder($existOrderID, $data);
					$this->model_checkout_order->addOrderHistory($response_id, $this->model_pluggto_pluggto->getStatusSaleByHistory($order->Order->status_history));
				} else {
					$response_id = $this->model_checkout_order->addOrder($data);
					$this->model_checkout_order->addOrderHistory($response_id, $this->model_pluggto_pluggto->getStatusSaleByHistory($order->Order->status_history));
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
				'product_id' => $this->model_pluggto_pluggto->getIDItemBySKU($item->id),
				'name'       => $item->name,
				'model'	     => $item->name,
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
    	$response = array();
    	foreach ($orders as $order) {
    		$totals = $this->model_pluggto_pluggto->getOrderTotals($order['order_id']);
    		
    		$params = array(
    			'external' 			  => $order['order_id'],
    			'status' 			  => $this->model_pluggto_pluggto->getStatusToPluggToByStatusOpenCart($order['order_status_id']),
    			'total' 			  => $totals[0]['value'],
    			'subtotal' 			  => $totals[1]['value'],
    			'shipping' 			  => '',
    			'discount' 			  => '',
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
    			// 'delivery_type'       => $this->model_pluggto_pluggto->getShippingMethodToPluggByOpenCart($order['shipping_method']),
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

     		$order_id = $order['invoice_prefix'];

     		try {

	     		$existOrderOnPluggTo = $this->model_pluggto_pluggto->getOrder($order_id);
				
	     		if (empty($existOrderOnPluggTo->error)){

	     			$params['shipments'][0]['id'] = isset($existOrderOnPluggTo->Order->shipments[0]->id) ? $existOrderOnPluggTo->Order->shipments[0]->id : null;
	     			
	     			$response[$order_id] = $this->model_pluggto_pluggto->editOrder($params, $order_id);
	
	     		} else {
					
	     			$temp = $this->model_pluggto_pluggto->createOrder($params);     			
	     			
	     			$response[$temp->Order->id] = $response;

					$order['invoice_prefix'] = $temp->Order->id;

		     		$this->model_checkout_order->editOrder($order['order_id'], $order);
	
	     			continue;
	
	     		} 

     		} catch (Exception $e) {
    
     			$response[$order_id] = $e->getMessage();
    
     		}
    	}

    	return $response;
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

		$items = $this->model_account_order->getOrderProducts($order['order_id']);
		
		$response = array();
		foreach ($items as $item) {			
			$response[] = array(
				'id' => $responseGetRelaction->row['pluggto_product_id'],
				'sku' => null,
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
			return 'Problem SKU';
    	}

		$data = array(
			'name'       => $product['name'],
			'sku'        => $product['sku'],
			'grant_type' => "authorization_code",
			'price'      => $product['price'],
			'quantity'   => $product['quantity'],
			'external'   => $product['product_id'],
			'description'=> $product['description'],
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
		foreach ($images as $i => $image) {
			$response[] = array(
			    'url' =>  'http://' . $_SERVER['SERVER_NAME'] . '/image/' . $image['image'],
			    'remove' => true
		    );
			
			$response[] = array(
			    'url'   => 'http://' . $_SERVER['SERVER_NAME'] . '/image/' . $image['image'],
			    'title' => 'Imagem principal do produto',
			    'order' => 0
			);
		}

		return $response;
	}

	public function getVariationsToSaveInOpenCart($product_id) {
		$product = $this->model_catalog_product->getProduct($product_id);
		$options = $this->model_catalog_product->getProductOptions($product_id);
	
		$response = array();
		foreach ($options as $i => $option) {
		  foreach ($option['product_option_value'] as $item) {
		    $response[] = array(
		      'name'     => $item['name'],
		      'external' => $option['product_option_id'],
		      'quantity' => $item['quantity'],
		      'special_price' => $this->getSpecialPriceProductToPluggTo($product_id),
		      'price' => ($item['price_prefix'] == '+') ? $product['price'] + $item['price'] : $product['price'] - $item['price'] ,
		      'sku' => 'sku-' . $item['option_value_id'],
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

	public function getAllItemsQueue() {
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

	public function getAllLogs() {		
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

}