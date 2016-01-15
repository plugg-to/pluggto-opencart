<?php
class ControllerModulePluggTo extends Controller {
  private $error = array();

  public function install() {
    $this->load->model('pluggto/pluggto');
    $this->model_pluggto_pluggto->install();
  }
  public function uninstall() {
    $this->load->model('pluggto/pluggto');
    $this->model_pluggto_pluggto->uninstall();
  }

  public function check() {
    $this->load->model('pluggto/pluggto');

    $this->model_pluggto_pluggto->checkProducts($this->model_pluggto_pluggto->getProductsTable());
    $result = $this->model_pluggto_pluggto->checkProducts($this->model_pluggto_pluggto->getProductsTable());

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

    $data['heading_title'] = $this->language->get('heading_title');
    $data['action'] = $this->url->link('module/pluggto', 'token=' . $this->session->data['token'], 'SSL');
    $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

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

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('module/pluggto.tpl', $data));
  }

  protected function validate() {
    if (!$this->user->hasPermission('modify', 'module/store')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    return !$this->error;
  }
}
?>