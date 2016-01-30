<?php
class ControllerApiPluggto extends Controller {

	public function index() {
		$json = ['status' => 'operational', 'HTTPcode' => 200];

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function teste($name) {
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode('teste'));
	}

	public function getNotification() 
	{
		$this->load->model('pluggto/pluggto');

		$fields = [
			'resource_id'   => empty($this->request->post['resource_id']) ? '' : $this->request->post['resource_id'],
			'type'          => empty($this->request->post['type']) ? '' : $this->request->post['type'],
			'action'        => empty($this->request->post['action']) ? '' : $this->request->post['action'],
			'date_created'  => time(),
			'date_modified' => time(),
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
}
