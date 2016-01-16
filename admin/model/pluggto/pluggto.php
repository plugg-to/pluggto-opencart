<?php
class ModelPluggtoPluggto extends Model{
 
  public function install() {
    $this->db->query("
          CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pluggto` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `api_user` varchar(255) NOT NULL,
            `api_secret` varchar(255) NOT NULL,
            `client_id` varchar(255) NOT NULL,
            `client_secret` varchar(255) NOT NULL
          ) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

          CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "settings_products_synchronization` (
          `id` int(11) NOT NULL,
            `active` tinyint(4) NOT NULL,
            `refresh_only_stock` tinyint(4) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
    ");
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
 
  public function searchProduct($products, $id) {

    foreach ( $products as $key => $product ) {

      if (isset($product->external)) {

        if ( $id == $product->external ) {
            $product->key = $key;
            return $product;
        }
      }
    }
    return false;
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

  public function checkProducts($pluggto_products) {
    $result = array();
    $result['all'] = 0;
    $result['create_from'] = 0;
    $result['update_from'] = 0;
    $result['create_to'] = 0;
    $result['update_to'] = 0;


    $sql = "SELECT product_id, date_modified FROM `" . DB_PREFIX . "product`";

    $products = $this->db->query($sql)->rows;

    foreach ($pluggto_products->products as $key => $product) {

      if ( empty($product->external)) {

        $result['create_from'] += 1;
      }
    }

    foreach ($products as $key => $product) {
      date_default_timezone_set('UTC');
      $pluggto_product = $this->searchProduct($pluggto_products->products, $product["product_id"]);

      if (!empty($pluggto_product)) {
        $product_timestamp = strtotime($product["date_modified"]);

        $pluggto_product_timestamp = $pluggto_product->timestamp;


        if ($product_timestamp > $pluggto_product_timestamp) {
          $product_json = $this->productToPluggto($product["product_id"]);
          $data = $this->updateTo($product_json, $pluggto_product->key);
          $product_timestamp = $this->getProduct($pluggto_product->key);
          $this->setTimestamp($product["product_id"], $product_timestamp->Product->timestamp);
          $result['update_to'] += 1;

        }elseif ($product_timestamp < $pluggto_product_timestamp) {
          $product_to_import = $this->getProduct($pluggto_product->key);
          $this->updateFromProduct($product_to_import, $pluggto_product->timestamp);
          $update_from[] = $pluggto_product;
          $result['update_from'] += 1;
        }

      }else {
        $product_json = $this->productToPluggto($product["product_id"]);
        $this->createTo($product_json);

        $result['create_to'] += 1;
      }
      $result['all'] += 1;
    }
    return $result;
  }

  public function setCredentials($api_user, $api_secret, $client_id, $client_secret) {
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
      $params = array("grant_type"=>"password", "client_id" => $credential["client_id"], "client_secret" => $credential["client_secret"], "api_user" => $credential["api_user"], "api_secret" => $credential["api_secret"]);

      $data = $this->sendRequest("get", $url, $params);

      if (!empty($data->access_token)) {
        return $data->access_token;
      }else {
        return false;
      }
    }
  }

  public function getProducts() {
    $url = "http://api.plugg.to/products";
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
        $url = $url.$value;
      }

      curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url
      ));

    }elseif (strtolower ( $method ) == "post") {

      $data_string = json_encode($params);

      curl_setopt($ch, CURLOPT_URL, $url);

      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string))
      );

    }elseif (strtolower ( $method ) == "put") {

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
    $url = $url."?access_token=".$accesstoken;
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

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_attribute`
           WHERE product_id = ".$product_id;

    $pluggto['raw']['attributes'] = $this->db->query($sql)->rows;

    foreach ($pluggto['raw']['attributes'] as $key=>$value) {
      $pluggto['variations'][$key]['name'] = $value['text'];
    }

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_description`
           WHERE product_id = ".$product_id;

    $pluggto['raw']['description'] = $this->db->query($sql)->row;

    $pluggto['name'] = $pluggto['raw']['description']['name'];

    $pluggto['short_description'] = $pluggto['raw']['description']['description'];

    $pluggto['description'] = $pluggto['raw']['description'];

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_discount`
           WHERE product_id = ".$product_id;

    $pluggto['raw']['discount'] = $this->db->query($sql)->rows;

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_filter`
           WHERE product_id = ".$product_id;

    $pluggto['raw']['filter'] = $this->db->query($sql)->rows;

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_image`
           WHERE product_id = ".$product_id;

    $pluggto['raw']['image'] = $this->db->query($sql)->rows;

    foreach ($pluggto['raw']['image'] as $key=>$value) {
      $pluggto['photos'][$key]['url'] = $value['image'];
      $pluggto['photos'][$key]['order'] = $value['sort_order'];
    }

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_option`
           WHERE product_id = ".$product_id;

    $pluggto['raw']['option'] = $this->db->query($sql)->rows;

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_option_value`
           WHERE product_id = ".$product_id;

    $pluggto['raw']['option_value'] = $this->db->query($sql)->rows;

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_profile`
           WHERE product_id = ".$product_id;

    $pluggto['raw']['profile'] = $this->db->query($sql)->rows;

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_recurring`
           WHERE product_id = ".$product_id;

    $pluggto['raw']['recurring'] = $this->db->query($sql)->rows;

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_related`
           WHERE product_id = ".$product_id;

    $pluggto['raw']['related'] = $this->db->query($sql)->rows;

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_reward`
           WHERE product_id = ".$product_id;

    $pluggto['raw']['reward'] = $this->db->query($sql)->rows;

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_special`
           WHERE product_id = ".$product_id;

    $pluggto['raw']['special'] = $this->db->query($sql)->rows;

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_to_category`
           WHERE product_id = ".$product_id;

    $pluggto['raw']['to_category'] = $this->db->query($sql)->rows;

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_to_download`
           WHERE product_id = ".$product_id;

    $pluggto['raw']['to_download'] = $this->db->query($sql)->rows;

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_to_layout`
           WHERE product_id = ".$product_id;

    $pluggto['raw']['to_layout'] = $this->db->query($sql)->rows;

    $sql = "SELECT * FROM `" . DB_PREFIX . "product_to_store`
           WHERE product_id = ".$product_id;

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

}