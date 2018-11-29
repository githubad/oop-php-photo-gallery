<?php
require_once(LIB_PATH.DS."database.php");

class DatabaseObject {

  protected static $table_name;
  protected static $db_fields;

  // Common Database Mehtods
  public  function find_all() {
    return self::find_by_sql("SELECT * FROM " . static::$table_name);
  }

  public   function find_by_id($id = 0) {
      global $database;
      $result_array = self::find_by_sql("SELECT * FROM ". static::$table_name ." where id = {$database->escape_value($id)} LIMIT 1");
      return (!empty($result_array) ? array_shift($result_array) : false);
  }

  public function find_by_sql($sql = "") {
    global $database;
    $result_set = $database->query($sql);
    $object_array = array();
    while($row = $database->fetch_array($result_set)) {
      $object_array[] = self::instantiate($row);
    }
    return $object_array;
  }

  public function count_all() {
    global $database;
    $sql = "SELECT COUNT(*) from " . static::$table_name;
    $result_set = $database->query($sql);
    $row = $database->fetch_array($result_set);
    return array_shift($row);
  }

  private function instantiate($record) {
      $class_name = get_called_class();
      $object = new $class_name;
      // // kind of manual way to do it -  simple, long form
      // $object->id                   = $record['id'];
      // $object->username             = $record['username'];
      // $object->password             = $record['password'];
      // $object->first_name           = $record['first_name'];
      // $object->last_name            = $record['last_name'];

      // // Dynamic, more robust
      foreach($record as $attribute => $value){
        if($object->has_attribute($attribute)) {
          $object->$attribute = $value;
        }
      }
      return $object;
  }

  private function has_attribute($attribute) {
    //finds all atrributes of this object
    $object_vars = get_object_vars($this);
    // returns if it exists T/F
    return array_key_exists($attribute, $object_vars);
  }




    protected function attributes() {
      // return an array of keys and values
      // // Old approach - return get_object_vars($this);
      $attributes = array();
      $class_name = get_called_class();
      foreach(static::$db_fields as $field) {
        if(property_exists($class_name, $field)){
          $attributes[$field] = $this->$field;
        }
      }
      return $attributes;
    }

    protected  function sanitized_attributes() {
      global $database;
      $clean_attributes = array();
      foreach(self::attributes() as $key => $value) {
        $clean_attributes[$key] = $database->escape_value($value);
      }
      return $clean_attributes;
    }

    public function save() {
      return isset($this->id) ? $this->update() : $this->create();
    }

    public function create() {
        global $database;
        // INSERT INTO table (key, key) values ('value', 'value');
        // Single quotes around values
        // Escape values to prevent SQL injection

        $attributes = $this->sanitized_attributes();
        $sql = " INSERT INTO " . static::$table_name . " (";
        $sql .= join(',', array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";


            if($database->query($sql)) {
              $this->id = $database->insert_id();
              return true;
            } else {
              return false;
            }

      }

    public function update() {
         global $database;
          // UPDATE table SET key='value', key='value' WHERE condition
          $attributes = $this->sanitized_attributes();
          $attribute_pairs = array();
          foreach($attributes as $key => $value){
          $attribute_pairs[] = "${key}='{$value}'";
          }

          $sql = "UPDATE " . static::$table_name . " SET ";
          $sql .= join(", ", $attribute_pairs);
          $sql .= " WHERE id=" . $database->escape_value($this->id) ;

          $database->query($sql);
          return ($database->affected_rows() == 1) ? true : false;
      }

    public function delete() {
        global $database;
        // DELETE FROM table WHERE condition LIMIT 1
        $sql = "DELETE FROM " . static::$table_name;
        $sql .= " WHERE id=" . $database->escape_value($this->id);
        $sql .= " LIMIT 1";

        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;
    }
// class end
}

?>
