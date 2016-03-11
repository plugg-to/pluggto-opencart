<?php

error_reporting(0);

class ControllerApiPluggto extends Controller {

	public function index(){
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

	public function cronOrders() {
		// $num_orders_pluggto  	= $this->saveOrdersInPluggTo($this->existNewOrdersOpenCart());
		$num_orders_opencart 	= $this->saveOrdersInOpenCart($this->existNewOrdersPluggTo());
                
        $response = [
            'orders_created_opencart' => $num_orders_opencart,
            'orders_created_pluggto'  => $num_orders_pluggto
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

		$notifications = $this->model_pluggto_pluggto->getNotifications(100, 'orders');
		
		foreach ($notifications as $notification) {
			$order = $this->model_pluggto_pluggto->getOrderPluggTo($notification['resource_id']);

			if (!isset($order->Order) && empty($order->Order)) {
				echo 'criar funcao de log';exit;
			}

			$response[] = $order;
		}

		return $response;
	}

	public function saveOrdersInOpenCart($orders) {

		$this->load->model('checkout/order');

		foreach ($orders as $i => $order) {
			$data = [
				'invoice_prefix' 	 => '',
				'store_id'			 => (isset($order->Order->id) ? $order->Order->id : null),
				'store_name' 		 => '',
				'store_url' 		 => '',
				'customer_id' 		 => '',
				'customer_group_id'  => '',
				'firstname' 		 => (isset($order->Order->payer_name) ? $order->Order->payer_name : null),
				'lastname' 			 => (isset($order->Order->payer_lastname) ? $order->Order->payer_lastname : null),
				'email' 			 => (isset($order->Order->receiver_email) ? $order->Order->receiver_email : null),
				'telephone' 		 => (isset($order->Order->receiver_phone) ? $order->Order->receiver_phone : null),
				'fax' 				 => (isset($order->Order->receiver_phone) ? $order->Order->receiver_phone : null),
				'custom_field' 		 => '',
				'payment_firstname'  => (isset($order->Order->payer_name) ? $order->Order->payer_name : null),
				'payment_lastname' 	 => (isset($order->Order->payer_lastname) ? $order->Order->payer_lastname : null),
				'payment_company' 	 => (isset($order->Order->payer_razao_social) ? $order->Order->payer_razao_social : null),
				'payment_address_1'  => (isset($order->Order->payer_address_reference) ? $order->Order->payer_address_reference : null),
				'payment_city' 		 => (isset($order->Order->payer_city) ? $order->Order->payer_city : null),
				'payment_postcode' 	 => (isset($order->Order->payer_zipcode) ? $order->Order->payer_zipcode : null),
				'payment_country' 	 => (isset($order->Order->payer_country) ? $order->Order->payer_country : null),
				'payment_country_id' => 30,
				'payment_zone' 		 => '',
				'payment_zone_id' 	 => '',
				'payment_address_format' => '',
				'payment_custom_field'   => '',
				'payment_method' 	 => '',
				'payment_code'		 => '',
				'shipping_firstname' => (isset($order->Order->receiver_name) ? $order->Order->receiver_name : null),
				'shipping_lastname'  => (isset($order->Order->receiver_lastname) ? $order->Order->receiver_lastname : null),
				'shipping_company' 	 => '',
				'shipping_address_1' => (isset($order->Order->receiver_address) ? $order->Order->receiver_address : null),
				'shipping_address_2' => (isset($order->Order->receiver_address_number) ? $order->Order->receiver_address_number : null),
				'shipping_city' 	 => (isset($order->Order->receiver_city) ? $order->Order->receiver_city : null),
				'shipping_postcode'  => (isset($order->Order->receiver_zipcode) ? $order->Order->receiver_zipcode : null),
				'shipping_country'   => (isset($order->Order->receiver_country) ? $order->Order->receiver_country : null),
				'shipping_zone' 	 => '',
				'shipping_zone_id' 	 => '',
				'shipping_address_format' => '',
				'shipping_custom_field' => '',
				'shipping_method' 	 => (isset($order->Order->shipments[0]->shipping_method) ? $order->Order->shipments[0]->shipping_method : null),
				'shipping_code' 	 => (isset($order->Order->shipments[0]->track_code) ? $order->Order->shipments[0]->track_code : null),
				'comment' 			 => '',
				'total' 			 => (isset($order->Order->total) ? $order->Order->total : null),
				'affiliate_id' 		 => '',
				'commission' 		 => '',
				'marketing_id' 		 => '',
				'tracking' 			 => '',
				'language_id' 		 => '',
				'currency_id' 		 => '',
				'currency_code' 	 => '',
				'currency_value' 	 => '',
				'ip' 				 => '',
				'forwarded_ip'		 => '',
				'user_agent' 		 => '',
				'accept_language' 	 => '',
				'products' 			 => $this->getProductsToSaveOpenCart($order)
			];

			$this->model_checkout_order->addOrder($data);
		}
	}

	public function getProductsToSaveOpenCart($order) {
		if (!isset($order->Order->items) && empty($order->Order->items)) {
			return false;
		}

		$response = [];
		foreach ($order->Order->items as $key => $item) {
			$response[] = [
				'product_id' => $item->id,
				'name'       => $item->name,
				'model'	     => $item->name,
				'quantity'   => $item->quantity,
				'price'		 => $item->price,
				'total'		 => $item->total,
				'tax'		 => null,
				'reward'	 => null
			];
		}

		return $response;
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
    			'items'				  => $this->getItemsToOrderPluggTo($order),
    			'shipments'           => [],
     		];

     		$response = $this->model_pluggto_pluggto->createOrder($params);

     		if ($response->Order->id) {
	     		$this->model_pluggto_pluggto->createRelationOrder($response->Order->id, $order['order_id']);
	     		$cont++;
     		}
    	}
	}

	public function getItemsToOrderPluggTo($order){
		$this->load->model('pluggto/pluggto');
		$this->load->model('account/order');

		$items = $this->model_account_order->getOrderProducts($order['order_id']);
		
		$response = [];
		foreach ($items as $item) {
			$responseGetRelaction = $this->model_pluggto_pluggto->getRelactionProductPluggToAndOpenCartByProductIdOpenCart($item['product_id']);
			
			$response[] = [
				'id' => $responseGetRelaction->row['pluggto_product_id'],
				'sku' => null,
				'price' => $item['price'],
				'discount' => null,
				'quantity' => $item['quantity'],
				'total' => $item['total'],
				'external' => $item['product_id'],
				'variation' => []
			];
		}

		return $response;
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

  	public function exportAllProductsToPluggTo() {
	    $this->load->model('catalog/product');
	    $this->load->model('pluggto/pluggto');
	    
	    $products_opencart = $this->model_catalog_product->getProducts();
	    $productPrepare = [];
	    $data = [];

	    foreach ($products_opencart as $product) {
	    	if (empty($product['sku']))
	    		echo 'not exist sku on product';

			$data = [
				'name'       => $product['name'],
				'sku'        => $product['sku'],
				'grant_type' => "authorization_code",
				'price'      => $product['price'],
				'quantity'   => $product['quantity'],
				'external'   => $product['product_id'],
				'description'=> $product['description'],
				'brand'      => isset($product['brand']) ? $product['brand'] : '',
				'ean'        => $product['ean'],
				'nbm'        => isset($product['nbm']) ? $product['nbm'] : '',
				'isbn'       => $product['isbn'],
				'available'  => $product['status'],
				'dimension'  => [
					'length' => $product['length'],
					'width'  => $product['width'],
					'height' => $product['height']
				],
				'photos'     => $this->getPhotosToSaveInOpenCart($product['product_id'], $product['image']),
				'link'       => 'http://' . $_SERVER['SERVER_NAME'] . '/index.php?route=product/product&product_id=' . $product['product_id'],
				'variations' => $this->getVariationsToSaveInOpenCart($product['product_id']),
				'attributes' => $this->getAtrributesToSaveInOpenCart($product['product_id']),
				'special_price' => $this->getSpecialPriceProductToPluggTo($product['product_id'])
			];
			
			$response = $this->model_pluggto_pluggto->sendToPluggTo($data, $product['sku']);
	    }

	    return true;
	}

	public function saveProductsInPluggto(){
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
        
        return json_encode($json);
    }

    public function importAllProductsToOpenCart(){        
        $this->load->model('pluggto/pluggto');

        $response = [
            'action' => "import products from pluggto",
        ];
        
        $result = $this->model_pluggto_pluggto->getProducts(1);
        
        foreach ($result->result as $i => $product) {
           $return = $this->model_pluggto_pluggto->prepareToSaveInOpenCart($product);
           
           $productId = $product->Product->id;
           
           $response[$i]['status']  = $return;
           $response[$i]['message'] = $return === true ? "Product '$productId' imported successfully" : "Produts Could not be imported";
        }

        return json_encode($response);
    }

	public function getNotification(){
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

	public function existNewOrders(){
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

	public function cronUpdateProducts(){
		$this->load->model('pluggto/pluggto');

		$productsQuery = $this->model_pluggto_pluggto->getProductsNotification();

		$message = [];
		foreach ($productsQuery as $key => $value) {			
			if ($value['type'] != 'products') {
				continue;
			}

			$product = $this->model_pluggto_pluggto->getProduct($value['resource_id']);

			if (isset($product->Product)) {				
				$response = $this->model_pluggto_pluggto->prepareToSaveInOpenCart($product);
				
				$message[$key]['resource_id'] = $product->Product->id;
				$message[$key]['saved']       = $this->model_pluggto_pluggto->updateStatusNotification($product->Product->id);
			}
		}

		// $priceAndStock = $this->verifyStockAndPriceProducts();

		array_push($message, null);

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

	public function getSpecialPriceProductToPluggTo($product_id) {
		$specialPrice = $this->model_catalog_product->getProductSpecials($product_id);
		return end($specialPrice)['special'];
	}

	public function getPhotosToSaveInOpenCart($product_id, $image_main) {
		$images = $this->model_catalog_product->getProductImages($product_id);

		$response = [
		  [
		    'url' =>  'http://' . $_SERVER['SERVER_NAME'] . '/image/cache/' . $image_main,
		    'remove' => true
		  ],
		  [
		    'url'   => 'http://' . $_SERVER['SERVER_NAME'] . '/image/cache/' . $image_main,
		    'title' => 'Imagem principal do produto',
		    'order' => 0
		  ]
		];

		return $response;
	}

	public function getVariationsToSaveInOpenCart($product_id) {
		$product = $this->model_catalog_product->getProduct($product_id);
		$options = $this->model_catalog_product->getProductOptions($product_id);
	
		$response = [];
		foreach ($options as $i => $option) {
		  foreach ($option['product_option_value'] as $item) {
		    $response[] = [
		      'name'     => $item['name'],
		      'external' => $option['product_option_id'],
		      'quantity' => $item['quantity'],
		      'special_price' => $this->getSpecialPriceProductToPluggTo($product_id),
		      'price' => ($item['price_prefix'] == '+') ? $product['price'] + $item['price'] : $product['price'] - $item['price'] ,
		      'sku' => 'sku-' . $item['option_value_id'],
		      'ean' => '',
		      'photos' => [],
		      'attributes' => [],
		      'dimesion' => [
		        'length' => $product['length'],
		        'width'  => $product['width'],
		        'height' => $product['height'],
		        'weight' => ($item['weight_prefix'] == '+') ? $item['weight'] + $product['weight'] : $item['weight'] - $product['weight'],
		      ]
		    ];
		  }
		}

		return $response;
	}

	public function getAtrributesToSaveInOpenCart($product_id) {
		$this->load->model('catalog/product');

		$product    = $this->model_catalog_product->getProduct($product_id);
		$attributes = $this->model_catalog_product->getProductAttributes($product_id);

		$response = [];

		foreach ($attributes as $i => $attribute) {
			if (isset($attribute['attribute']) && !empty($attribute['attribute']))
			{
				foreach ($attribute['attribute'] as $i => $attr) {
					$response[] = [
						'code'  => $attr['attribute_id'],
						'label' => $attr['text'],
						'value' => [
							'code'  => $attr['attribute_id'],
							'label' => $attr['text'],
						]
					];
				}

				continue;
			}

			$response[] = [
				'code'  => $attribute['attribute_id'],
				'label' => $attribute['product_attribute_description'][1]['text'],
				'value' => [
					'code'  => $attribute['attribute_id'],
					'label' => $attribute['product_attribute_description'][1]['text'],
				]
			];
		}

		return $response;
	}

}