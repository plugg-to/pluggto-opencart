<?php

class ModelPluggtoPluggto extends Model{
 
  public function getOrders() {
    $order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o");

    if ($order_query->num_rows) {
      $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

      if ($country_query->num_rows) {
        $payment_iso_code_2 = $country_query->row['iso_code_2'];
        $payment_iso_code_3 = $country_query->row['iso_code_3'];
      } else {
        $payment_iso_code_2 = '';
        $payment_iso_code_3 = '';
      }

      $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

      if ($zone_query->num_rows) {
        $payment_zone_code = $zone_query->row['code'];
      } else {
        $payment_zone_code = '';
      }

      $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

      if ($country_query->num_rows) {
        $shipping_iso_code_2 = $country_query->row['iso_code_2'];
        $shipping_iso_code_3 = $country_query->row['iso_code_3'];
      } else {
        $shipping_iso_code_2 = '';
        $shipping_iso_code_3 = '';
      }

      $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

      if ($zone_query->num_rows) {
        $shipping_zone_code = $zone_query->row['code'];
      } else {
        $shipping_zone_code = '';
      }

      $this->load->model('localisation/language');

      $language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

      if ($language_info) {
        $language_code = $language_info['code'];
        $language_directory = $language_info['directory'];
      } else {
        $language_code = '';
        $language_directory = '';
      }
      
      return $order_query;  
    } else {
      return false;
    }
  }

  public function orderExistInPluggTo($order_id) {
    $order_query = $this->db->query("SELECT id FROM `" . DB_PREFIX . "order_relation_pluggto_and_opencart` WHERE order_id_opencart = '" . (int)$order_id . "' ");    
    
    if ($order_query->row) {
      return true;
    }

    return false;
  }

  public function createRelationOrder($pluggto_order_id, $opencart_order_id) {
    $sql = "INSERT INTO `" . DB_PREFIX . "order_relation_pluggto_and_opencart` ( order_id_opencart, order_id_pluggto, active)
            VALUES ('".$opencart_order_id."', '".$pluggto_order_id."', '1')";

    return $this->db->query($sql);
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

  public function getOrdersPluggTo() {
    $url = "http://api.plugg.to/orders";
    $method = "get";
    $accesstoken = $this->getAccesstoken();
    $url = $url."?access_token=".$accesstoken;
    $data = $this->sendRequest($method, $url, []);
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

  public function validateFields($fields) 
  {
    foreach ($fields as $key => $field) {
      if (empty($field)) {
        return $key;
      }
    }
    return true;
  }

  public function createNotification($fields) 
  {
    $validate = $this->validateFields($fields);

    if ($validate === true) {
      return $this->saveNotification($fields);
    }
    return $validate;
  }

  public function saveNotification($fileds) 
  {
      $sql = "INSERT INTO `" . DB_PREFIX . "pluggto_notifications` (resource_id, type, action, date_created, date_modified, status) 
                      VALUES 
                            ('".$fileds['resource_id']."', '".$fileds['type']."', '".$fileds['action']."', '".$fileds['date_created']."', 
                             '".$fileds['date_modified']."', '".$fileds['status']."')";
      
      return $this->db->query($sql);
  }

 
  public function getProduct($product_id) 
  {
      $url = "http://api.plugg.to/products/".$product_id;
      $method = "get";
      $accesstoken = $this->getAccesstoken();
      $params = array("access_token" => $accesstoken);
      $data = $this->sendRequest($method, $url, $params);
    
      return $data;
  }

  public function getProductsNotification($field = false)
  {
        if (!$field) {
            $field = '*';
        }

        $query = "SELECT ".$field." FROM ".DB_PREFIX."pluggto_notifications WHERE status = 1";

        $result = $this->db->query($query);

        return $result->rows;
  }
  
public function prepareToSaveInOpenCart($product) {
    $synchronizationSettings = $this->getSettingsProductsSynchronization();

    if (!$synchronizationSettings->row['refresh_only_stock']) {
      $data = [
        'sku' => $product->Product->sku,
        'model' => $product->Product->name,
        'price' => $product->Product->price,
        'weight' => $product->Product->dimension->weight,
        'length' => $product->Product->dimension->length,
        'width' => $product->Product->dimension->width,
        'height' => $product->Product->dimension->height,
        'status' => 1,
        'product_description' => [
          1 => [
            'name' => $product->Product->name,
            'description' => $product->Product->short_description,
            'tag' => '',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keyword' => '',
          ]
        ],
        'product_store' => [
          0
        ],
        'product_category' => $this->formatObjectCategoryToList($product->Product->categories)
      ];
    }

    $data['quantity'] = $product->Product->quantity;

    $this->load->model('catalog/product');

    if (!$this->existProductInOpenCart($product->Product->id) && !$synchronizationSettings->row['refresh_only_stock']){
      $product_id = $this->model_catalog_product->addProduct($data);     
      return $this->createPluggToProductRelactionOpenCartPluggTo($product->Product->id, $product_id);
    }

    if ($synchronizationSettings->row['refresh_only_stock']) {
      $product_id = $this->existProductInOpenCart($product->Product->id);
      return $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '" . $this->db->escape($data['quantity']) . "' WHERE product_id = '" . (int)$product_id . "'");
    }

    return $this->model_catalog_product->editProduct($this->existProductInOpenCart($product->Product->id), $data);
  }

  public function getSettingsProductsSynchronization()
  {
        $sql = "SELECT * FROM " . DB_PREFIX . "settings_products_synchronization ORDER BY id DESC LIMIT 1";
        return $this->db->query($sql);    
  }

   public function formatObjectCategoryToList($categoriesObject) 
    {
        $response = [];
        
        foreach ($categoriesObject as $i => $category) {
          $auxiliar[] = $category->name;
        }

        if (empty($auxiliar))
          return false;

        $response = $this->findCategoriesInOpenCart($auxiliar);

        return $response;
    }

    public function findCategoriesInOpenCart($namesOfCategories)
    {
        $response = [];

        $this->load->model('catalog/category');
        $categories = $this->prepareDataCategoryToArraySearch($this->model_catalog_category->getCategories());

        foreach ($namesOfCategories as $i => $names) {
          $id_category = array_search(explode(' >', $names)[0], $categories);
          $response[] = $id_category;
        }

        return $response;
    }

    public function prepareDataCategoryToArraySearch($categoriesOpenCart) 
    {
        $response = [];

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

    public function updateStatusNotification($productId)
    {
         $query = "UPDATE ".DB_PREFIX."pluggto_notifications SET status = 0 WHERE resource_id = '$productId'";

         return $this->db->query($query);
    }




}