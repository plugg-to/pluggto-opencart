<?php

class ModelExtensionFeedPluggto extends Model{
 
  public function getOrders($data = array()) {
    $sql = "SELECT o.*, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";

    if (isset($data['filter_order_status'])) {
      $implode = array();

      $order_statuses = explode(',', $data['filter_order_status']);

      foreach ($order_statuses as $order_status_id) {
        $implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
      }

      if ($implode) {
        $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
      }
    } else {
      $sql .= " WHERE o.order_status_id > '0'";
    }

    if (!empty($data['filter_order_id'])) {
      $sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
    }

    if (!empty($data['filter_customer'])) {
      $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
    }

    if (!empty($data['filter_date_added'])) {
      $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
    }

    if (!empty($data['filter_date_modified'])) {
      $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
    }

    if (!empty($data['filter_total'])) {
      $sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
    }

    $sort_data = array(
      'o.order_id',
      'customer',
      'status',
      'o.date_added',
      'o.date_modified',
      'o.total'
    );

    if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
      $sql .= " ORDER BY " . $data['sort'];
    } else {
      $sql .= " ORDER BY o.order_id";
    }

    if (isset($data['order']) && ($data['order'] == 'DESC')) {
      $sql .= " DESC";
    } else {
      $sql .= " ASC";
    }

    if (isset($data['start']) || isset($data['limit'])) {
      if ($data['start'] < 0) {
        $data['start'] = 0;
      }

      if ($data['limit'] < 1) {
        $data['limit'] = 20;
      }

      $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
    }

    $query = $this->db->query($sql);

    return $query->rows;
  }

  public function orderExistInPluggTo($order_id) {
    $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_relation_pluggto_and_opencart` WHERE order_id_pluggto = '" . $order_id . "' ");    
    
    if ($order_query->row) {
      return $order_query->row['order_id_opencart'];
    }

    return false;
  }

  public function getRelactionOrder($order_id_opencart) {
    $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_relation_pluggto_and_opencart` WHERE order_id_opencart = '" . $order_id_opencart . "' ");    
    
    if ($order_query->row) {
      return $order_query->row;
    }

    return false;
  }

  public function createRelationOrder($pluggto_order_id, $opencart_order_id) {
    $sql = "INSERT INTO `" . DB_PREFIX . "order_relation_pluggto_and_opencart` ( order_id_opencart, order_id_pluggto, active)
            VALUES ('".$opencart_order_id."', '".$pluggto_order_id."', '1')";

    return $this->db->query($sql);
  }

  public function getPaymentZoneIDByState($state) {
    $sql = 'SELECT zone_id FROM ' . DB_PREFIX . 'zone WHERE name LIKE "%' . $state . '%"';
    return $this->db->query($sql);
  }

  public function getOrderTotalByCode($order_id, $code) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code = '" . $code . "' ORDER BY sort_order");
    
    return $query->row['value'];
  }

  public function getShippingMethodToPluggByOpenCart($input){
    $sql = 'SELECT * FROM ' . DB_PREFIX . 'pluggto_linkage_fields WHERE field_opencart = "' . $input . '"';
    $response = $this->db->query($sql);

    if (empty($response->row))
    {
      return 'standard';
    }

    return $response->row['field_pluggto'];
  }

  public function getStatusToPluggToByStatusOpenCart($status_opencart) {
    if (!$status_opencart) {
      return 'pending';
    }

    $sql = 'SELECT * FROM ' . DB_PREFIX . 'order_status WHERE order_status_id = ' . $status_opencart;
    $response = $this->db->query($sql);

    $sql = 'SELECT * FROM ' . DB_PREFIX . 'pluggto_linkage_fields WHERE field_pluggto = "' . $response->row['name'] . '"';
    $response_field = $this->db->query($sql);

    if (empty($response_field->row)) {
      return -1;
    }

    return $response_field->row['field_opencart'];
  }

  public function getCurrencyMain() {
    $sql = 'SELECT * FROM ' . DB_PREFIX . 'currency WHERE code = "BRL"';
    $response = $this->db->query($sql);

    if (!empty($response->row)) {
      return array(
        'currency_code'  => $response->row['code'],
        'currency_id'    => $response->row['currency_id'],
        'currency_value' => $response->row['value']
      );
    }
    
    $sql = 'SELECT * FROM ' . DB_PREFIX . 'currency WHERE code = "USD"';
    $response = $this->db->query($sql);

    return array(
      'currency_code'  => $response->row['code'],
      'currency_id'    => $response->row['currency_id'],
      'currency_value' => $response->row['value']
    );
  }

  public function getStatusSaleByHistory($status) {
    if (empty($status)) {
      return 1;//status correspondente a pendente
    }

    //field_pluggto == opencart
    //field_opencart == pluggto
    $sql = 'SELECT * FROM ' . DB_PREFIX . 'pluggto_linkage_fields WHERE field_opencart = "' . $status . '"';
    $response_field = $this->db->query($sql);

    if (!empty($response_field->row)) {
      $sql = 'SELECT * FROM ' . DB_PREFIX . 'order_status WHERE name = "' . $response_field->row['field_pluggto'] . '"';
      $response_status = $this->db->query($sql);

      if (empty($response_status->row))
        return -1;

      return $response_status->row['order_status_id'];
    }

    switch ($status) {
      case 'pending':
        return 1;
      break; 
      case 'paid': 
        return 5;
      break;
      case 'approved': 
        return 2;
      break;
      case 'waiting_invoice': 
        return 2;
      break;
      case 'invoiced': 
        return 2;
      break;
      case 'invoice_error': 
        return 8;
      break;
      case 'shipping_informed': 
        return 5;
      break;
      case 'shipped': 
        return 5;
      break;
      case 'shipping_error': 
        return 10;
      break;
      case 'delivered': 
        return 5;
      break;  
      case 'canceled': 
        return 9;
      break;
      case 'under_review':
        return 13;
      break;
      default:
        return 1;
      break;
    }
  }

  public function getIDItemBySKU($sku){
    $sql = 'SELECT product_id FROM ' . DB_PREFIX . 'product WHERE sku = "' . $sku . '"';
    
    $response = $this->db->query($sql);
    
    if (!empty($response->row)) {
      return $response->row['product_id'];
    }

    return false;
  }

  public function getModelItemBySKU($sku){
    $sql = 'SELECT model FROM ' . DB_PREFIX . 'product WHERE sku = "' . $sku . '"';
    
    $response = $this->db->query($sql);
    
    if (!empty($response->row)) {
      return $response->row['model'];
    }

    return false;
  }

  public function checkOrderByIDPluggTo($pluggto_id) {
    $sql = 'SELECT order_id FROM ' . DB_PREFIX . 'order WHERE invoice_prefix = "INV-' . date('Y') . "-" . base64_encode($pluggto_id) . '"';
    
    $response = $this->db->query($sql);
    
    if (!empty($response->row)) {
      return $response->row->order_id;
    }

    return false;
  }

  public function createOrder($params) {
    $url = "http://api.plugg.to/orders";
    $method = "post";
    $accesstoken = $this->getAccesstoken();
    $url = $url."?access_token=".$accesstoken;
    $params = $params;
    $data = $this->sendRequest($method, $url, $params);
    return $data;
  }

  public function editOrder($params, $id) {
    $url = "http://api.plugg.to/orders/" . $id;
    $method = "put";
    $accesstoken = $this->getAccesstoken();
    $url = $url."?access_token=".$accesstoken;
    $data = $this->sendRequest($method, $url, $params);
    return $data;
  }

  public function getOrdersPluggTo() {
    $url = "http://api.plugg.to/orders";
    $method = "get";
    $accesstoken = $this->getAccesstoken();
    $url = $url."?access_token=".$accesstoken;
    $data = $this->sendRequest($method, $url, array());
    return $data;    
  }

  public function getOrderPluggTo($id) {
    $url = "http://api.plugg.to/orders";
    $method = "get";
    $accesstoken = $this->getAccesstoken();
    $url = $url."/" . $id . "?access_token=".$accesstoken;
    $data = $this->sendRequest($method, $url, array());
    return $data;        
  }

  public function getCredentials() {
    $sql = "SELECT * FROM `". DB_PREFIX . "pluggto`
            ORDER BY id DESC
            LIMIT 1";
    $pluggto = $this->db->query($sql);
    return $pluggto->row;
  }

  public function getAccesstoken() {
    $credential = $this->getCredentials();
    if (empty($credential)) {
     return false;
    }else {
      $url = "http://api.plugg.to/oauth/token";
      $params = array("grant_type"=>"password", "client_id" => $credential["client_id"], "client_secret" => $credential["client_secret"], "username" => $credential["api_user"], "password" => $credential["api_secret"]);

      $data = $this->sendRequest("post", $url, $params);

      if (!empty($data->access_token)) {
        return $data->access_token;
      }else {
        return false;
      }
    }
  }

  public function sendRequest($method, $url, $params) {
    $ch = curl_init();

    if (strtolower ( $method ) == "get")  {
      $i =0;
      foreach ($params as $key => $value) {

        if ($i == 0) {
          $value = "?".$key."=".$value;
        }else {
          $value = "&".$key."=".$value;
        }
        $i++;
        $url = $url . $value;
      }

      curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url
      ));

    }elseif (strtolower ( $method ) == "post") {

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

    }elseif (strtolower ( $method ) == "put") {
      $data_string = json_encode($params);

      curl_setopt($ch, CURLOPT_URL, $url);


      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_ENCODING, "");
      curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);
      curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "content-type: application/json"
        )
      );

    }elseif (strtolower ( $method ) == "delete") {
      
      curl_setopt($ch, CURLOPT_URL, $url);


        $data_string = json_encode($params);

      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string)
        )
      );

    }

    $result = curl_exec($ch);

    return json_decode($result);
  }

  public function removeProduct($sku){
      $url = "http://api.plugg.to/skus/".$sku;
      $method = "delete";
      $accesstoken = $this->getAccesstoken();
      $params = array("access_token" => $accesstoken);
      $data = $this->sendRequest($method, $url . '?access_token=' . $accesstoken, $params);    
      return $data;
  }
  
  public function createNotification($fields){
    return $this->saveNotification($fields);
  }

  public function createLog($data, $function){
    //return $this->db->query("INSERT INTO " . DB_PREFIX . "pluggto_log (data, function, date) VALUES ('" . $data . "', '" . $function . "', '" . date('Y-m-d') . "')");
  }
  
  public function saveNotification($field){
      $sql = "INSERT INTO `" . DB_PREFIX . "pluggto_notifications` (resource_id, type, action, date_created, date_modified, status) 
                      VALUES 
                            ('".$field['resource_id']."', '".$field['type']."', '".$field['action']."', '".$field['date_created']."', 
                             '".$field['date_modified']."', '".$field['status']."')";
      
      return $this->db->query($sql);
  }
 
  public function getProduct($product_id){
      $url = "http://api.plugg.to/products/".$product_id;
      $method = "get";
      $accesstoken = $this->getAccesstoken();
      $params = array("access_token" => $accesstoken);
      $data = $this->sendRequest($method, $url, $params);    
      return $data;
  }

  public function getProductBySKU($sku) {
      $url = "http://api.plugg.to/skus/" . $sku;
      $method = "get";
      $accesstoken = $this->getAccesstoken();
      $params = array("access_token" => $accesstoken);
      $data = $this->sendRequest($method, $url, $params);    
      return $data;
  }

  public function getOrder($orderId){
      $url = "http://api.plugg.to/orders/" . $orderId;
      $method = "get";
      $accesstoken = $this->getAccesstoken();
      $params = array("access_token" => $accesstoken);
      $data = $this->sendRequest($method, $url, $params);
    
      return $data;
  }

  public function getProductsNotification($field = false){
        if (!$field) {
            $field = '*';
        }

        $query = "SELECT ".$field." FROM ".DB_PREFIX."pluggto_notifications WHERE status = 1";

        $result = $this->db->query($query);

        return $result->rows;
  }

  public function getNotifications($limit = 100, $type = 'products'){
    $query = "SELECT * FROM " . DB_PREFIX . "pluggto_notifications WHERE status = 1 AND type = '" . $type . "' LIMIT " . $limit;
    
    return $this->db->query($query)->rows;
  }

  public function getQueuesProducts($origin='opencart'){
        if ($origin != "opencart"){
          $query = "SELECT id, product_id, product_id_pluggto FROM ".DB_PREFIX."pluggto_products_queue WHERE process = 0 AND product_id_pluggto <> '' limit 100";
        } else {
          $query = "SELECT id, product_id, product_id_pluggto FROM ".DB_PREFIX."pluggto_products_queue WHERE process = 0 AND product_id <> '' limit 100";
        }

        $result = $this->db->query($query);


        return $result->rows;
  }

  public function prepareToSaveInOpenCart($product) {
    $synchronizationSettings = $this->getSettingsProductsSynchronization();
    
    if (!$synchronizationSettings->row['refresh_only_stock']) {
      $data = array(
        'sku'    => $product->Product->sku,
        'model'  => $product->Product->sku,
        'price'  => $product->Product->price,
        'weight' => $product->Product->dimension->weight,
        'length' => $product->Product->dimension->length,
        'width'  => $product->Product->dimension->width,
        'height' => $product->Product->dimension->height,
        'manufacturer_id' => $this->getManufacturerID($product->Product->brand),
        'manufacturer' => $product->Product->brand,
        'subtract'=> 1,
        'status' => 1,
        'image'  => 'catalog/' . $this->uploadImagesToOpenCart($product->Product->photos, true),
        'product_image' => $this->uploadImagesToOpenCart($product->Product->photos, false),
        'product_description' => $this->getProductDescriptions($product),
        'product_option' => $this->getProductOptionToOpenCart($product),
        'product_special' => $this->getProductSpecialPriceToOpenCart($product),
        'product_store' => array(
          0
        ),
        'product_category' => $this->formatObjectCategoryToList($product->Product->categories)
      );
    }

    $data['quantity'] = $product->Product->quantity;
    
    $this->load->model('catalog/product');

    $query = "SELECT product_id FROM ".DB_PREFIX."product WHERE sku = '" . $product->Product->sku . "'";
    
    $sku = $this->db->query($query);

    if (!isset($sku->row['product_id']))
    {
      return $this->addProduct($data);
    }

    $this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$sku->row['product_id'] . "'");

    return $this->editProduct($sku->row['product_id'], $data);
  }

  public function getManufacturerID($brand) {
    $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer WHERE name = '" . $brand . "'");
    
    return $result->row['manufacturer_id'];    
  }

  public function getProductSpecialPriceToOpenCart($product){
    $response   = array();
    
    $response[] = array(
      'customer_group_id' => 1,
      'priority' => 1,
      'special' => $product->Product->special_price,
      'price'   => $product->Product->special_price,
      'date_start' => null,
      'date_end' => null
    );

    return ($product->Product->special_price > 0) ? $response : null;
  }

  public function uploadImagesToOpenCart($photos, $main=true){
    $this->load->model('tool/image');
    
    $response = array();
    foreach ($photos as $i => $photo) {
    
      $type = substr($photo->url, -4);

      $filename = $photo->id;
      
      $photo = file_get_contents(str_replace('https', 'http', $photo->url));
      
      if (!$photo)
        return null;

      if (file_exists(DIR_IMAGE . 'cache/catalog/' . $filename)){
        unlink(DIR_IMAGE . 'cache/catalog/' . $filename);
      }

      if (file_exists(DIR_IMAGE . 'catalog/' . $filename)){
        unlink(DIR_IMAGE . 'catalog/' . $filename . '-40x40' . $type);
        unlink(DIR_IMAGE . 'catalog/' . $filename . '-100x100' . $type);
        unlink(DIR_IMAGE . 'catalog/' . $filename);
      }
      
      $file = fopen(DIR_IMAGE . 'cache/catalog/' . $filename . $type, 'w+');        
      fputs($file, $photo);
      fclose($file);        

      $file2 = fopen(DIR_IMAGE . 'catalog/' . $filename . $type, 'w+');        
      fputs($file2, $photo);
      fclose($file2);        
      
      $sizes = array(
        array(
          'width' => 40,
          'height' => 40,
        ),
        array(
          'width' => 100,
          'height' => 100,
        )
      );

      $filename = $filename . $type;
      foreach ($sizes as $size) {
        $this->model_tool_image->resize('catalog/' . $filename, $size['width'], $size['height']);
      }

      if ($main)
        return $filename;

      $response[] = array(
        'image' => 'catalog/' . $filename,
        'sort_order' => $i
      );
    }

    return $response;
  }

  public function getProductOptionToOpenCart($product){
    if (empty($product->Product->variations)){
      return array();
    }

    $response   = array();
    $response[] = array(
      'name' => 'Size',
      'type' => 'select',
      'required' => 1,
      'option_id' => 11,
      'product_option_id' => null,
    );

    foreach ($product->Product->variations as $i => $variation) {
      $response[0]['product_option_value'][] = array(
          'option_value_id' => $this->getOptionValueIDByName($variation->name),
          'product_option_value_id' => $this->getOptionValueIDByName($variation->name),
          'quantity' => $variation->quantity,
          'subtract' => 1,
          'price' => null,
          'price_prefix' => '+',
          'points' => null,
          'points_prefix' => '+',
          'weight' => null,
          'weight_prefix' => '+',
      );
    }

    return $response;
  }

  public function getOptionValueIDByName($name){
    $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value_description WHERE name = '" . $name . "'");
    
    return $result->row['option_value_id'];
  }

  public function getProductDescriptions($product){
    $languages = $this->db->query("SELECT * FROM " . DB_PREFIX . "language");

    $response = array();
    foreach ($languages->rows as $i => $language) {
      $response[$language['language_id']] = array(
        'name'             => $product->Product->name,
        'description'      => $product->Product->short_description,
        'tag'              => '',
        'meta_title'       => '',
        'meta_description' => '',
        'meta_keyword'     => '',
      );
    }
    
    return $response;
  }

  public function getSettingsProductsSynchronization(){
        $sql = "SELECT * FROM " . DB_PREFIX . "settings_products_synchronization ORDER BY id DESC LIMIT 1";
        return $this->db->query($sql);    
  }

  public function formatObjectCategoryToList($categoriesObject){
      $response = array();
      
      foreach ($categoriesObject as $i => $category) {
        $auxiliar[] = $category->name;
      }

      if (empty($auxiliar))
        return false;

      $response = $this->findCategoriesInOpenCart($auxiliar);

      return $response;
  }

  public function findCategoriesInOpenCart($namesOfCategories){
      $response = array();

      $this->load->model('catalog/category');
      $categories = $this->prepareDataCategoryToArraySearch($this->model_catalog_category->getCategories());

      foreach ($namesOfCategories as $i => $names) {
        $explode = explode(' >', $names);
        $id_category = array_search($explode[0], $categories);
        $response[] = $id_category;
      }

      return $response;
  }

  public function prepareDataCategoryToArraySearch($categoriesOpenCart){
      $response = array();

      foreach ($categoriesOpenCart as $i => $category) {
        $response[$category['category_id']] = $category['name'];
      }
      
      return $response;
  }

  public function existProductInOpenCart($id) 
  {
      $sql = "SELECT * FROM `" . DB_PREFIX . "pluggto_products_relation_opencart_products` WHERE `pluggto_product_id` = '" . $id . "' AND `active` = 1";
      $response = $this->db->query($sql);

      if (empty($response->rows))
        return false;

      return $response->row['opencart_product_id'];
  }

  public function createPluggToProductRelactionOpenCartPluggTo($pluggto_product_id, $opencart_product_id) 
  {
       return $this->db->query("INSERT INTO " . DB_PREFIX . "pluggto_products_relation_opencart_products SET pluggto_product_id = '" . $this->db->escape($pluggto_product_id) . "', opencart_product_id = '" . $this->db->escape($opencart_product_id) . "', active = 1");    
  }

  public function updateStatusNotification($id, $response=array())
  {
      // if (!$response['success']) {
      //   $query = "UPDATE ".DB_PREFIX."pluggto_notifications SET status = 1, description = '$response' WHERE resource_id = '$id'";

      //   return $this->db->query($query);
      // }

      $query = "UPDATE ".DB_PREFIX."pluggto_notifications SET status = 0, description = '$response' WHERE resource_id = '$id'";

      return $this->db->query($query);
  }

  public function processedQueueProduct($productId, $origin)
  {
      if ($origin == "opencart")
        $query = "UPDATE ".DB_PREFIX."pluggto_products_queue SET process = 1 WHERE product_id = '$productId'";

      if ($origin == "pluggto")
        $query = "UPDATE ".DB_PREFIX."pluggto_products_queue SET process = 1 WHERE product_id_pluggto = '$productId'";

       return $this->db->query($query);
  }

  public function createOrderIdOpenCart($length = 50) 
  {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $string = '';

      for ($i = 0; $i < $length; $i++) {
          $string .= $characters[mt_rand(0, strlen($characters) - 1)];
      }

      return $string;
  }

  public function getAllPluggToProductRelactionsOpenCart() 
  {
      return $this->db->query("SELECT * FROM  " . DB_PREFIX . "pluggto_products_relation_opencart_products WHERE active = 1");
  }

  public function updateStockPluggTo($product, $id) 
  {
      $url = "http://api.plugg.to/products/" . $id . "/stock";
      $method = "put";
      $accesstoken = $this->getAccesstoken();
      $url = $url . "?access_token=" . $accesstoken;
      $params = $product;
      $data = $this->sendRequest($method, $url, $params);
      return $data;
  }

  public function updateTo($product, $id) {
      $url = "http://api.plugg.to/products/".$id;
      
      $method = "put";
      
      $accesstoken = $this->getAccesstoken();
      
      $url = $url . "?access_token=" . $accesstoken;
      
      $params = $product;
      
      $data = $this->sendRequest($method, $url, $params);
      
      return $data;
  }

  public function sendToPluggTo($product, $sku) {
	  
    $url = "http://api.plugg.to/skus/" . trim($sku);
    
    $method = "put";
    
    $accesstoken = $this->getAccesstoken();
    
    $url = $url . "?access_token=" . $accesstoken;
    
    $params = $product;
    
    $data = $this->sendRequest($method, $url, $params);
    
    return $data;    
  }

  public function getRelactionProductPluggToAndOpenCartByProductIdOpenCart($product_id_opencart) {
      return $this->db->query("SELECT * FROM " . DB_PREFIX . "pluggto_products_relation_opencart_products WHERE active = 1 AND opencart_product_id = " . $product_id_opencart . "");
  }

  public function getSync($field) 
  {
    $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "pluggto_linkage_fields where field_opencart = '{$field}'");

    if (empty($result)) {
      return true;
    }
    
    return isset($result->row['field_pluggto']) ? $result->row['field_pluggto'] : 1;
  }

  public function createTo($product) {
      $url = "http://api.plugg.to/products";
      $method = "post";
      $accesstoken = $this->getAccesstoken();
      $url = $url."?access_token=".$accesstoken;
      $params = $product;
      $data = $this->sendRequest($method, $url, $params);
      return $data;
  }

  public function getProducts($page) {
    $url = "http://api.plugg.to/products";
    $method = "get";
    $accesstoken = $this->getAccesstoken();
    $params = array("access_token" => $accesstoken, "page" => $page);
    $data = $this->sendRequest($method, $url, $params);
    return $data;
  }

  public function addProduct($data) {
    $this->event->trigger('pre.admin.product.add', $data);

    $this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");

    $product_id = $this->db->getLastId();

    if (isset($data['image'])) {
      $this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
    }

    foreach ($data['product_description'] as $language_id => $value) {
      $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
    }

    if (isset($data['product_store'])) {
      foreach ($data['product_store'] as $store_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
      }
    }

    if (isset($data['product_attribute'])) {
      foreach ($data['product_attribute'] as $product_attribute) {
        if ($product_attribute['attribute_id']) {
          foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
          }
        }
      }
    }
    
    if (isset($data['product_option'])) {
      foreach ($data['product_option'] as $product_option) {
        if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
          if (isset($product_option['product_option_value'])) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

            $product_option_id = $this->db->getLastId();

            foreach ($product_option['product_option_value'] as $product_option_value) {
              $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
            }
          }
        } else {
          $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
        }
      }
    }

    if (isset($data['product_discount'])) {
      foreach ($data['product_discount'] as $product_discount) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
      }
    }

    if (isset($data['product_special'])) {
      foreach ($data['product_special'] as $product_special) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
      }
    }

    if (isset($data['product_image'])) {
      foreach ($data['product_image'] as $product_image) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
      }
    }

    if (isset($data['product_download'])) {
      foreach ($data['product_download'] as $download_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
      }
    }

    if (isset($data['product_category'])) {
      foreach ($data['product_category'] as $category_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
      }
    }

    if (isset($data['product_filter'])) {
      foreach ($data['product_filter'] as $filter_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
      }
    }

    if (isset($data['product_related'])) {
      foreach ($data['product_related'] as $related_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
      }
    }

    if (isset($data['product_reward'])) {
      foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
        if ((int)$product_reward['points'] > 0) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$product_reward['points'] . "'");
        }
      }
    }

    if (isset($data['product_layout'])) {
      foreach ($data['product_layout'] as $store_id => $layout_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
      }
    }

    if (isset($data['keyword'])) {
      $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
    }

    if (isset($data['product_recurrings'])) {
      foreach ($data['product_recurrings'] as $recurring) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$recurring['customer_group_id'] . ", `recurring_id` = " . (int)$recurring['recurring_id']);
      }
    }

    $this->cache->delete('product');

    $this->event->trigger('post.admin.product.add', $product_id);

    return $product_id;
  }

  public function editProduct($product_id, $data) {
    $this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");

    if (isset($data['image'])) {
      $this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
    }

    $this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

    foreach ($data['product_description'] as $language_id => $value) {
      $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
    }

    $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

    if (isset($data['product_store'])) {
      foreach ($data['product_store'] as $store_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
      }
    }

    $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");

    if (!empty($data['product_attribute'])) {
      foreach ($data['product_attribute'] as $product_attribute) {
        if ($product_attribute['attribute_id']) {
          // Removes duplicates
          $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

          foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
          }
        }
      }
    }

    $this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
    $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");

    if (isset($data['product_option'])) {
      foreach ($data['product_option'] as $product_option) {
        if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
          if (isset($product_option['product_option_value'])) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

            $product_option_id = $this->db->getLastId();

            foreach ($product_option['product_option_value'] as $product_option_value) {
              $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
            }
          }
        } else {
          $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
        }
      }
    }

    $this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");

    if (isset($data['product_discount'])) {
      foreach ($data['product_discount'] as $product_discount) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
      }
    }

    $this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");

    if (isset($data['product_special'])) {
      foreach ($data['product_special'] as $product_special) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
      }
    }

    $this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

    if (isset($data['product_image'])) {
      foreach ($data['product_image'] as $product_image) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
      }
    }

    $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

    if (isset($data['product_download'])) {
      foreach ($data['product_download'] as $download_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
      }
    }

    $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

    if (isset($data['product_category'])) {
      foreach ($data['product_category'] as $category_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
      }
    }

    $this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

    if (isset($data['product_filter'])) {
      foreach ($data['product_filter'] as $filter_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
      }
    }

    $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
    $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");

    if (isset($data['product_related'])) {
      foreach ($data['product_related'] as $related_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
      }
    }

    $this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

    if (isset($data['product_reward'])) {
      foreach ($data['product_reward'] as $customer_group_id => $value) {
        if ((int)$value['points'] > 0) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
        }
      }
    }

    $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

    if (isset($data['product_layout'])) {
      foreach ($data['product_layout'] as $store_id => $layout_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
      }
    }

    $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");

    if ($data['keyword']) {
      $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
    }

    $this->db->query("DELETE FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = " . (int)$product_id);

    if (isset($data['product_recurring'])) {
      foreach ($data['product_recurring'] as $product_recurring) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$product_recurring['customer_group_id'] . ", `recurring_id` = " . (int)$product_recurring['recurring_id']);
      }
    }

    $this->cache->delete('product');
  }

  public function getCustomerByEmail($email) {
    $query = $this->db->query("SELECT customer_id, firstname, lastname FROM `" . DB_PREFIX . "customer` WHERE email = '" . $this->db->escape($email) . "' AND email != ''");

    return $query->row;
  }

  public function getIdCustomFieldByName($name) {
    $sql = 'SELECT * FROM ' . DB_PREFIX . 'pluggto_linkage_fields WHERE field_opencart LIKE "%' . $name . '%" LIMIT 1';
    $response_field = $this->db->query($sql);
    return !empty($response_field->row['field_pluggto']) ? $response_field->row['field_pluggto'] : 0;
  }

  public function editCustomer($data, $customer_id) {
    $this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "' WHERE customer_id = '" . (int)$customer_id . "'");
  }

  public function addAddress($data, $customer_id){
    $address = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "' AND postcode = '" . $this->db->escape($data['postcode']) . "'");
    
    if (!empty($address->row['address_id']))
    {
      $this->db->query("UPDATE " . DB_PREFIX . "address SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "' WHERE address_id  = '" . (int)$address->row['address_id'] . "'");
      return $address->row['address_id'];
    }

    $this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . $customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "'");

    $address_id = $this->db->getLastId();
  }

  public function refreshStock($sku, $params) {
    $url = "http://api.plugg.to/skus/" . $sku . "/stock";

    $method = "put";
    
    $accesstoken = $this->getAccesstoken();
    
    $url = $url . "?access_token=" . $accesstoken;
    
    $data = $this->sendRequest($method, $url, $params);
    
    return $data;
  }

  public function getProductOptionValueId($optionId, $productId, $optionValueId=null)
  {
    if ($optionValueId != null)
    {
      $query = "SELECT * FROM " . DB_PREFIX . "product_option_value WHERE option_value_id = " . $optionValueId . " AND option_id = " . $optionId . " AND product_id = " . $productId;
    }
    else
    {
      $query = "SELECT * FROM " . DB_PREFIX . "product_option_value WHERE option_id = " . $optionId . " AND product_id = " . $productId;
    }

    return $this->db->query($query);
  }

  public function getOptionIdByName($name) {
    $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value_description WHERE name = '" . $name . "'");

    return $result->row['option_id'];    
  }

  public function getOptionValueIdByNameNew($name) {
    $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value_description WHERE name = '" . $name . "'");

    return $result->row['option_value_id'];    
  }

  public function getProductsUpdatedLastHour() {
    $customer_group_id = $this->config->get('config_customer_group_id');
        
    $query = "
      SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.date_modified >= DATE_SUB(NOW(),INTERVAL 1 HOUR); 
    ";

    $query = $this->db->query($query);
  
    $product_data = array();
        
    foreach ($query->rows as $result) {
      $product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
    }

    return $product_data;
  }

}