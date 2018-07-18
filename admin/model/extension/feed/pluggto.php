<?php

class ModelExtensionFeedPluggto extends Model{
 
  public function install() {
    try {        
      $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pluggto` (
              `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `api_user` varchar(255) NOT NULL,
              `api_secret` varchar(255) NOT NULL,
              `client_id` varchar(255) NOT NULL,
              `client_secret` varchar(255) NOT NULL
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

      $this->db->query($sql);

      $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pluggto_log` (
              `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `data` TEXT NOT NULL,
              `function` varchar(50) NOT NULL,
              `date` DATE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

      $this->db->query($sql);

      $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "settings_products_synchronization` (
              `id` int(11) NOT NULL,
              `active` tinyint(4) NOT NULL,
              `refresh_only_stock` tinyint(4) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";    

      $this->db->query($sql);

      $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pluggto_linkage_fields` (
              `id` int(11) NOT NULL,
              `field_opencart` varchar(50) NOT NULL,
              `field_pluggto` varchar(50) NOT NULL,
              `active` tinyint(4) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

      $this->db->query($sql);

      $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pluggto_products_relation_opencart_products` (
              `id` int(11) NOT NULL,
              `pluggto_product_id` varchar(255) NOT NULL,
              `opencart_product_id` int(11) NOT NULL,
              `active` tinyint(4) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

      $this->db->query($sql);

      $sql = "CREATE TABLE `" . DB_PREFIX . "pluggto_notifications` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `resource_id` varchar(100) NOT NULL,
              `type` varchar(20) DEFAULT NULL,
              `action` varchar(50) DEFAULT NULL,
              `date_created` datetime DEFAULT NULL,
              `date_modified` datetime DEFAULT NULL,
              `description` text DEFAULT NULL,
              `status` tinyint(4) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";

      $this->db->query($sql);

      $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "order_relation_pluggto_and_opencart` (
              `id` int(11) NOT NULL,
              `order_id_opencart` varchar(50) NOT NULL,
              `order_id_pluggto` varchar(50) NOT NULL,
              `active` tinyint(4) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

      $this->db->query($sql);

      $sql = "ALTER TABLE `" . DB_PREFIX . "pluggto_products_relation_opencart_products`
              ADD PRIMARY KEY (`id`);";

      $this->db->query($sql);

      $sql = "ALTER TABLE `" . DB_PREFIX . "pluggto_products_relation_opencart_products`
               MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
      
      $this->db->query($sql);

      $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pluggto_products_queue` (
              `id` int(11) NOT NULL,
                `product_id` int(11) NOT NULL,
                `product_id_pluggto` varchar(255) NOT NULL,
                `process` int(11) NOT NULL,
                `response` text NOT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
              ";

      $this->db->query($sql);

      $sql = "ALTER TABLE `" . DB_PREFIX . "pluggto_products_queue`
               MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

      $this->db->query($sql);

      $this->db->query("ALTER TABLE `" . DB_PREFIX . "oc_pluggto_products_queue` ADD PRIMARY KEY (`id`);");
      
      $this->db->query("ALTER TABLE `" . DB_PREFIX . "oc_pluggto_products_queue` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
      
    } catch (Exception $e) {
      
    }

    $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "user ORDER BY user_id DESC");
  }

  public function uninstall() {
    $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "pluggto`;");
  }

  public function getProductsTable() {
    $url = "http://api.plugg.to/products/tabledata";
    $method = "get";
    $accesstoken = $this->getAccesstoken();
    $params = array("access_token" => $accesstoken);
    $data = $this->sendRequest($method, $url, $params);
    return $data;
  }

  public function updateFromProduct($product, $timestamp) {
    date_default_timezone_set('UTC');
    $raw = $product->Product->raw;
    $id = $raw->product_id;

    $sql = "UPDATE `" . DB_PREFIX . "product`
            SET model='".$raw->model."',
                sku='".$raw->sku."',
                upc='".$raw->upc."',
                ean='".$raw->ean."',
                jan='".$raw->jan."',
                isbn='".$raw->isbn."',
                location='".$raw->location."',
                quantity='".$raw->quantity."',
                stock_status='".$raw->stock_status."',
                image='".$raw->image."',
                manufacturer_id='".$raw->manufacturer_id."',
                shipping='".$raw->shipping."',
                price='".$raw->price."',
                points='".$raw->points."',
                tax_class_id='".$raw->tax_class_id."',
                date_avaliable='".$raw->date_avaliable."',
                weight='".$raw->weight."',
                weight_class_id='".$raw->weight_class_id."',
                length='".$raw->length."',
                width='".$raw->width."',
                height='".$raw->height."',
                length_class_id='".$raw->length_class_id."',
                substract='".$raw->substract."',
                minimum='".$raw->minimum."',
                sort_order='".$raw->sort_order."',
                status='".$raw->status."',
                date_added='".$raw->date_added."',
                date_modified='".date('Y-m-d H.i.s', $timestamp). "'
            WHERE product_id = ".$id;

    $this->db->query($sql);


    $pluggto['raw']['description'] = $this->db->query($sql)->row;

    $pluggto['name'] = $pluggto['raw']['description']['name'];

    $pluggto['short_description'] = $pluggto['raw']['description']['description'];

    $pluggto['description'] = $pluggto['raw']['description'];



    $sql = "UPDATE `" . DB_PREFIX . "product_description`
            SET name='".$product->Product->name. "',
                description='".$product->Product->description. "'
            WHERE product_id = ".$id;
    $this->db->query($sql);
  }

  public function setTimestamp($product_id, $timestamp) {
    date_default_timezone_set('UTC');
    $sql = "UPDATE `" . DB_PREFIX . "product`
            SET date_modified='".date('Y-m-d H.i.s', $timestamp). "'
            WHERE product_id = ".$product_id;
    $this->db->query($sql);
  }

  public function setCredentials($api_user, $api_secret, $client_id, $client_secret) {
    $this->db->query("DELETE FROM " . DB_PREFIX . "pluggto");
    $sql = "INSERT INTO `" . DB_PREFIX . "pluggto` ( api_user, api_secret, client_id, client_secret)
            VALUES ('".$api_user."', '".$api_secret."','".$client_id ."','".$client_secret."')";

    $this->db->query($sql);
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

  public function getProducts($page) {
    $url = "http://api.plugg.to/products";
    $method = "get";
    $accesstoken = $this->getAccesstoken();
    $params = array("access_token" => $accesstoken, "page" => $page);
    $data = $this->sendRequest($method, $url, $params);
    return $data;
  }

  public function getProductsTableData() {
    $url = "http://api.plugg.to/products/tableData";
    $method = "get";
    $accesstoken = $this->getAccesstoken();
    $params = array("access_token" => $accesstoken);
    $data = $this->sendRequest($method, $url, $params);
    return $data;    
  }

  public function getProduct($product_id) {
    $url = "http://api.plugg.to/products/".$product_id;
    $method = "get";
    $accesstoken = $this->getAccesstoken();
    $params = array("access_token" => $accesstoken);
    $data = $this->sendRequest($method, $url, $params);
    return $data;
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

    } elseif (strtolower ( $method ) == "post") {

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

    } elseif (strtolower ( $method ) == "put") {

      $data_string = json_encode($params);

      curl_setopt($ch, CURLOPT_URL, $url);

      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string))
      );

    }
    
    $result = curl_exec($ch);
    
    return json_decode($result);
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
    $url = "http://api.plugg.to/products/" . $sku;
    
    $method = "put";
    
    $accesstoken = $this->getAccesstoken();
    
    $url = $url . "?access_token=" . $accesstoken;
    
    $params = $product;
    
    $data = $this->sendRequest($method, $url, $params);
    
    return $data;    
  }

  public function updateStockPluggTo($product, $id) {
    $url = "http://api.plugg.to/products/" . $id . "/stock";
    $method = "put";
    $accesstoken = $this->getAccesstoken();
    $url = $url . "?access_token=" . $accesstoken;
    $params = $product;
    $data = $this->sendRequest($method, $url, $params);
    return $data;
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

  public function prepareToSaveInOpenCart($product) {
    file_get_contents(HTTP_CATALOG . 'index.php?route=api/pluggto/importAllProductsToOpenCart');
    return true;
  }

  public function offAllProductsWithPluggTo() {
    $this->db->query("UPDATE " . DB_PREFIX . "pluggto_products_relation_opencart_products SET active = 0");
    return true;
  }

  public function createPluggToProductRelactionOpenCartPluggTo($pluggto_product_id, $opencart_product_id) {
    return $this->db->query("INSERT INTO " . DB_PREFIX . "pluggto_products_relation_opencart_products SET pluggto_product_id = '" . $this->db->escape($pluggto_product_id) . "', opencart_product_id = '" . $this->db->escape($opencart_product_id) . "', active = 1");    
  }

  public function getAllPluggToProductRelactionsOpenCart() {
    return $this->db->query("SELECT * FROM  " . DB_PREFIX . "pluggto_products_relation_opencart_products WHERE active = 1");
  }

  public function getRelactionProductPluggToAndOpenCartByProductIdOpenCart($product_id_opencart) {
    return $this->db->query("SELECT * FROM " . DB_PREFIX . "pluggto_products_relation_opencart_products WHERE active = 1 AND opencart_product_id = " . $product_id_opencart . "");
  }

  public function existProductInOpenCart($id) {
    $sql = "SELECT * FROM `" . DB_PREFIX . "pluggto_products_relation_opencart_products` WHERE `pluggto_product_id` = '" . $id . "' AND `active` = 1";
    $response = $this->db->query($sql);

    if (empty($response->rows))
      return false;

    return $response->row['opencart_product_id'];
  }

  public function formatObjectCategoryToList($categoriesObject) {
    $response = array();
    
    foreach ($categoriesObject as $i => $category) {
      $auxiliar[] = $category->name;
    }

    if (empty($auxiliar))
      return false;

    $response = $this->findCategoriesInOpenCart($auxiliar);

    return $response;
  }

  public function findCategoriesInOpenCart($namesOfCategories) {
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

  public function prepareDataCategoryToArraySearch($categoriesOpenCart) {
    $response = array();

    foreach ($categoriesOpenCart as $i => $category) {
      $response[$category['category_id']] = $category['name'];
    }
    
    return $response;
  }

  public function productToPluggto($product_id) {
    $sql = "SELECT * FROM `" . DB_PREFIX . "product`
           WHERE product_id = ".$product_id;
           
    $pluggto = array();
    $pluggto['raw'] = $this->db->query($sql)->row;

    $pluggto['external'] = $pluggto['raw']['product_id'];
    $pluggto['sku'] = $pluggto['raw']['sku'];
    $pluggto['weight'] = $pluggto['raw']['weight'];
    $pluggto['height'] = $pluggto['raw']['height'];
    $pluggto['length'] = $pluggto['raw']['length'];
    $pluggto['width'] = $pluggto['raw']['width'];
    $pluggto['price'] = $pluggto['raw']['price'];
    $pluggto['quantity'] = $pluggto['raw']['quantity'];

    $pluggto['raw']['to_store'] = $this->db->query($sql)->rows;

    return $pluggto;
  }

  public function saveSettingsProductsSynchronization($data) {
    $sql = "INSERT INTO " . DB_PREFIX . "settings_products_synchronization (`active`, `refresh_only_stock`) VALUES (" . $data['active'] . ", " . $data['refresh_only_stock'] . ")";
    return $this->db->query($sql);
  }

  public function getSettingsProductsSynchronization(){
    $sql = "SELECT * FROM " . DB_PREFIX . "settings_products_synchronization ORDER BY id DESC LIMIT 1";
    return $this->db->query($sql);    
  }

  public function saveField($field_opencart, $field_pluggto){
    $sql = "SELECT * FROM `" . DB_PREFIX . "pluggto_linkage_fields` WHERE field_opencart = '" . $field_opencart . "' AND active = 1";
    $responseField = $this->db->query($sql);
    
    if ($responseField->num_rows <= 0) {
      $sql = "INSERT INTO `" . DB_PREFIX . "pluggto_linkage_fields` (field_opencart, field_pluggto, active) VALUES ('" . $field_opencart . "', '" . $field_pluggto . "', 1)";
      return $this->db->query($sql);
    }

    $sql = "UPDATE `" . DB_PREFIX . "pluggto_linkage_fields` SET field_opencart = '" . $field_opencart . "', field_pluggto = '" . $field_pluggto . "' WHERE id = '" . $responseField->row['id'] . "' ";
    return $this->db->query($sql);
  }

  public function getAllDefaultsFields() {
    $sql = "SELECT * FROM " . DB_PREFIX . "pluggto_linkage_fields WHERE ACTIVE = 1";
    return $this->processDataToListTypeKeyAndValue($this->db->query($sql));    
  }

  public function processDataToListTypeKeyAndValue($data) {
    if ($data->num_rows <= 0) 
      return false;

    $response = array();

    foreach ($data->rows as $i => $value) {
      $response[$value['field_opencart']] = $value['field_pluggto'];
    }

    return $response;
  }

  public function getNotifications($limit = 100, $type = 'products'){
    $query = "SELECT * FROM " . DB_PREFIX . "pluggto_notifications WHERE status = 1 AND type = '" . $type . "' LIMIT " . $limit;

    if ($type == 'all')
      $query = "SELECT * FROM " . DB_PREFIX . "pluggto_notifications WHERE status = 1 LIMIT " . $limit;

    return $this->db->query($query)->rows;
  }

  public function getAllItemsInQueues(){
    $sql = "SELECT * FROM `" . DB_PREFIX . "pluggto_notifications` WHERE status = 1";
    $responseField = $this->db->query($sql);
  }

  public function saveImportationQueue(){
    $this->load->model('catalog/product');

    $products = $this->getProductsTableData();
    
    $this->insertAllIDsPluggto($products);
  }

  public function insertAllIDsOpenCart($products){
    foreach ($products as $product) {
      $sql = 'INSERT INTO ' . DB_PREFIX . 'pluggto_products_queue (product_id,process) VALUES ("' . $product['product_id'] . '", "0")';
      $this->db->query($sql);
    }
  }

  public function insertAllIDsPluggto($products){
    foreach ($products->Products as $product) {
      $sql = 'INSERT INTO ' . DB_PREFIX . 'pluggto_products_queue (product_id_pluggto, process) VALUES ("' . $product->id . '", "0")';
      $this->db->query($sql);
    }
  }

  public function saveExportationQueue(){
    $this->load->model('catalog/product');

    $products = $this->model_catalog_product->getProducts();
    
    $this->insertAllIDsOpenCart($products);
  }

  public function getStatusOpenCart(){
    $response = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status");
    return $response->rows;
  }

}