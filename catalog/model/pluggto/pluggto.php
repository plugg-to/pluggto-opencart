<?php
class ModelPluggtoPluggto extends Model{

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

}