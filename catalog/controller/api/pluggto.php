<?php

class ControllerApiPluggto extends Controller {

	public function index() 
    {
		$json = ['status' => 'operational', 'HTTPcode' => 200];


		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
		
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		
			$this->response->addHeader('Access-Control-Max-Age: 1000');
		
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');		
		}

		$this->response->addHeader('Content-Type: application/json');
		
		$this->response->setOutput(json_encode('$json'));

	}

	public function cronGetProductsAndOrders() {
		$num_orders_pluggto  	= $this->saveOrdersInPluggTo($this->existNewOrdersOpenCart());
		$num_orders_opencart 	= $this->saveOrdersInOpenCart($this->existNewOrdersPluggTo());
        
        $exportProducts = $this->saveProductsInPluggto();
        $importProducts = $this->importAllProductsToOpenCart();
        
        $response = [
            'orders_created_pluggto' => $num_orders_pluggto,
            'productsExported'       => $exportProducts,
            'productsImported'       => $importProducts,
        ];

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($response));
	}

	public function existNewOrdersOpenCart() {
    	$this->load->model('pluggto/pluggto');

    	$response  = [];
		$allOrders = $this->model_pluggto_pluggto->getOrders();

		foreach ($allOrders->rows as $order) {
			if ($this->model_pluggto_pluggto->orderExistInPluggTo($order['order_id'])) {
				continue;
			}

			$response[] = $order;
		}

		return $response;
	}

	public function existNewOrdersPluggTo() {
		$this->load->model('pluggto/pluggto');

		$response = [];

		$allOrders = $this->model_pluggto_pluggto->getOrdersPluggTo();

		if (!$allOrders->result) {
			return false;
		}

		foreach ($allOrders->result as $order) {
			if ($this->model_pluggto_pluggto->orderExistInPluggTo($order->Order->external)) {
				continue;
			}

			$response[] = $order;
		}

		return $response;
	}

	public function saveOrdersInOpenCart($orders) {
		$this->load->model('checkout/order');

		foreach ($orders as $i => $order) {
			$data = [
				'invoice_prefix' => '',
				'store_id' => $order->Order->id,
				'store_name' => '',
				'store_url' => '',
				'customer_id' => '',
				'customer_group_id' => '',
				'firstname' => $order->Order->payer_name,
				'lastname' => $order->Order->payer_lastname,
				'email' => $order->Order->receiver_email,
				'telephone' => $order->Order->receiver_phone,
				'fax' => $order->Order->receiver_phone,
				'custom_field' => '',
				'payment_firstname' => $order->Order->payer_name,
				'payment_lastname' => $order->Order->payer_lastname,
				'payment_company' => $order->Order->payer_razao_social,
				'payment_address_1' => $order->Order->payer_address_reference,
				'payment_city' => '',
				'payment_postcode' => '',
				'payment_country' => 'Brasil',
				'payment_country_id' => '',
				'payment_zone' => '',
				'payment_zone_id' => '',
				'payment_address_format' => '',
				'payment_custom_field' => '',
				'payment_method' => '',
				'payment_code' => '',
				'shipping_firstname' => '',
				'shipping_lastname' => '',
				'shipping_company' => '',
				'shipping_address_1' => '',
				'shipping_address_2' => '',
				'shipping_city' => '',
				'shipping_postcode' => '',
				'shipping_country' => '',
				'shipping_zone' => '',
				'shipping_zone_id' => '',
				'shipping_address_format' => '',
				'shipping_custom_field' => '',
				'shipping_method' => '',
				'shipping_code' => '',
				'comment' => '',
				'total' => '',
				'affiliate_id' => '',
				'commission' => '',
				'marketing_id' => '',
				'tracking' => '',
				'language_id' => '',
				'currency_id' => '',
				'currency_code' => '',
				'currency_value' => '',
				'ip' => '',
				'forwarded_ip' => '',
				'user_agent' => '',
				'accept_language' => '',
				'products' => [
					'product_id' => '',
					'name' => '',
					'model' => '',
					'quantity' => '',
					'price' => '',
					'total' => '',
					'tax' => '', 
					'reward' => '',
					'option' => [
						'product_option_id' => '',
						'product_option_value_id' => '',
						'name' => '',
						'value' => '',
						'type' => ''
					]
				]
			];

			$this->model_checkout_order->addOrder($data);
		}

	}

	public function saveOrdersInPluggTo($orders) {
    	$this->load->model('pluggto/pluggto');

    	$cont = 0;
    	foreach ($orders as $order) {
    		$params = [
    			'external' 			  => $order['order_id'],
    			'status' 			  => 'pending', //$order['order_status'],
    			'total' 			  => $order['total'],
    			'subtotal' 			  => '',
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
    			'delivery_type'       => 'onehour', //$order['shipping_method'],
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
    			'items'				  => [],
    			'shipments'           => [],
     		];

     		$response = $this->model_pluggto_pluggto->createOrder($params);

     		if ($response->Order->id) {
	     		$this->model_pluggto_pluggto->createRelationOrder($response->Order->id, $order['order_id']);
	     		$cont++;
     		}
    	}
	}

	public function saveProductsInPluggto()
	{
		$this->load->model('catalog/product');
        $this->load->model('pluggto/pluggto');

        $json = [
            'action' => "export products to pluggto",
        ];
        
        $products_opencart = $this->model_catalog_product->getProducts();
        $productPrepare = [];
        $data = [];

        $messageIndex = 0;
        foreach ($products_opencart as $product) {
            $data = [
                'name'       => isset($product['name']) ? $product['name'] : '',
                'sku'        => isset($product['sku']) ? $product['sku'] : '',
                'price'      => isset($product['price']) ? $product['price'] : '',
                'quantity'   => isset($product['quantity']) ? $product['quantity'] : '',
                'external'   => isset($product['product_id']) ? $product['product_id'] : '',
                'description'=> isset($product['description']) ? $product['description'] : '',
                'brand'      => isset($product['brand']) ? $product['brand'] : '',
                'ean'        => isset($product['ean']) ? $product['ean'] : '',
                'nbm'        => isset($product['nbm']) ? $product['nbm'] : '',
                'isbn'       => isset($product['isbn']) ? $product['isbn'] : '',
                'available'  => isset($product['status']) ? $product['status'] : '',
                'dimension'  => [
                  'length' => $product['length'],
                  'width'  => $product['width'],
                  'height' => $product['height']
                ],
                'photos'     => $this->model_pluggto_pluggto->getPhotosToSaveInOpenCart($product['product_id'], $product['image']),
                'link'       => $_SERVER['SERVER_NAME'] . '/index.php?route=product/product&product_id=' . $product['product_id'],
                'variations' => $this->model_pluggto_pluggto->getVariationsToSaveInOpenCart($product['product_id']),
                'attributes' => $this->model_pluggto_pluggto->getAtrributesToSaveInOpenCart($product['product_id']),
            ];

            $existProduct = $this->model_pluggto_pluggto->getRelactionProductPluggToAndOpenCartByProductIdOpenCart($product['product_id']);

            if ($existProduct->num_rows > 0) {
                $response = $this->model_pluggto_pluggto->updateTo($data, $existProduct->row['pluggto_product_id']);
                
                $productId = $response->Product->id;

                $json[$messageIndex]['status']  = true;
                $json[$messageIndex]['message'] = "Product '$productId' updated successfully";

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
        
        return json_encode($json);
    }

    public function importAllProductsToOpenCart() 
    {        
        $this->load->model('pluggto/pluggto');

        $response = [
            'action' => "import products from pluggto",
        ];
        
        $result = $this->model_pluggto_pluggto->getProducts();
        
        foreach ($result->result as $i => $product) {
           $return = $this->model_pluggto_pluggto->prepareToSaveInOpenCart($product);
           
           $productId = $product->Product->id;
           
           $response[$i]['status']  = $return;
           $response[$i]['message'] = $return === true ? "Product '$productId' imported successfully" : "Produts Could not be imported";
        }

        return json_encode($response);
    }


	public function getNotification() 
	{
		$this->load->model('pluggto/pluggto');

		$fields = [
			'resource_id'   => empty($this->request->post['resource_id']) ? '' : $this->request->post['resource_id'],
			'type'          => empty($this->request->post['type']) ? '' : $this->request->post['type'],
			'action'        => empty($this->request->post['action']) ? '' : $this->request->post['action'],
			'date_created'  => date('Y-m-d h:i:s', time()),
			'date_modified' => date('Y-m-d h:i:s', time()),
			'status'        => 1
		];

		$result = $this->model_pluggto_pluggto->createNotification($fields);

		$response = [
			'message' => $result === true ? 'Notification received sucessfully' : 'Failure getting notification. The field: '.$result.' can not be empty',
			'code'    => 200,
			'status'  => is_bool($result) ? $result : false
		];

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: POST');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($response));
	}

	public function existNewOrders() {
    	$this->load->model('pluggto/pluggto');

    	$response  = [];
		$allOrders = $this->model_pluggto_pluggto->getOrders();

		foreach ($allOrders->rows as $order) {
			if ($this->model_pluggto_pluggto->orderExistInPluggTo($order['order_id'])) {
				continue;
			}

			$response[] = $order;
		}

		return $response;
	}

	public function cronUpdateProducts()
	{
		$this->load->model('pluggto/pluggto');

		$productsQuery = $this->model_pluggto_pluggto->getProductsNotification();

		$message = [];
		foreach ($productsQuery as $key => $value) {			
			$product = $this->model_pluggto_pluggto->getProduct($value['resource_id']);
			
			if (isset($product->Product)) {				
				$this->model_pluggto_pluggto->prepareToSaveInOpenCart($product);
				
				$message[$key]['resource_id'] = $product->Product->id;
				$message[$key]['saved']       = $this->model_pluggto_pluggto->updateStatusNotification($product->Product->id);
			} else {
				$order = $this->model_pluggto_pluggto->getOrder($value['resource_id']);

				if (isset($order->Order)) {
					$orderIdOpenCart = $this->model_pluggto_pluggto->createOrderIdOpenCart();
					$result = $this->model_pluggto_pluggto->createRelationOrder($order->Order->id, $orderIdOpenCart);
	
					$message[$key]['resource_id'] = $order->Order->id;
					$message[$key]['saved']       = $this->model_pluggto_pluggto->updateStatusNotification($order->Order->id);
				} else {
					$message[$key]['resource_id'] = $value['resource_id'];
					$message[$key]['saved']       = "Error: Resource not found";
				}
			}
		}

		$priceAndStock = $this->verifyStockAndPriceProducts();
		array_push($message, $priceAndStock);

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}
		
		$response = [
			'code'    => 200,
			'message' => $message
		];

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($response));
	}

	public function verifyStockAndPriceProducts() 
    {
        $this->load->model('pluggto/pluggto');
        $this->load->model('catalog/product');

        $products_pluggto_relations = $this->model_pluggto_pluggto->getAllPluggToProductRelactionsOpenCart();

        $quantityUpdated = false;
        $priceUpdated    = false;

        foreach ($products_pluggto_relations->rows as $i => $product){
            $pluggto_product_response  = $this->model_pluggto_pluggto->getProduct($product['pluggto_product_id']);
            $opencart_product_response = $this->model_catalog_product->getProduct($product['opencart_product_id']);

            if (isset($pluggto_product_response) && $pluggto_product_response->Product->quantity != $opencart_product_response['quantity']){
	            $data = [
	              'action'   => 'update',
	              'quantity' => $opencart_product_response['quantity']
	            ];

	            $response        = $this->model_pluggto_pluggto->updateStockPluggTo($data, $product['pluggto_product_id']);
	            $quantityUpdated = true;
            }

            if (isset($pluggto_product_response) && $pluggto_product_response->Product->price != $opencart_product_response['price']){
	            $data = [
	              'price' => $opencart_product_response['price']
	            ];

	            $response     = $this->model_pluggto_pluggto->updateTo($data, $product['pluggto_product_id']);
	            $priceUpdated = true;
            }

        }

        $response = [
        	'stockUpdated' => $quantityUpdated === true ? "Stock was updated sucessfully"   : "Stock is up to date",
        	'priceUpdated'  => $priceUpdated   === true ? "Prices were updated sucessfully" : "Prices are up to date"
        ];

        return $response;
    }

}