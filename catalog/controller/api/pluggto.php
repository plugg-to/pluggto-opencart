<?php
class ControllerApiPluggto extends Controller {
	
	public function index() {
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
		$num_orders_pluggto = $this->saveOrdersInPluggTo($this->existNewOrders());

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode('orders_created_pluggto' => $num_orders_pluggto));
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

}
