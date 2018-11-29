<?php
require_once(LIB_PATH.DS."config.php");

class MySQLDatabase {

  private $con;
  public $last_query;

  function __construct() {
    $this->open_connection();
  }

  function __deconstruct() {

  }

  public function con_setter() {

  }

  public function con_getter() {
    echo $this->con;
  }

  public function open_connection() {
        $this->con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
        if(!$this->con) {
          die('Couldn\'t connect to database' . mysqli_error() );
        } else {
          $db = mysqli_select_db( $this->con, DB_NAME);
          if(!$db){
            die('Couldn\'t use selected database' . mysqli_error());
          }
        }}

  public function close_connection() {
    if(isset($this->con)){
      mysqli_close($this->con);
      unset($this->con);
    }
  }

  public function query($sql) {
    $this->last_query = $sql;
    $result = mysqli_query($this->con, $sql);
    $this->confirm_query($result);
    return $result;
  }

  private function confirm_query($result) {
    if(!$result) {
      $output = "Database query failed" . mysqli_error($this->con);
      $output .= "Last SQL Query:" . $this->last_query;
      die($output);
    }
  }

// Database Neutral Methods
  public function fetch_array($result_set) {
  return mysqli_fetch_array($result_set);
}

  public function num_rows($result_set) {
    return mysqli_num_rows($result_set);
  }

  public function insert_id() {
      return mysqli_insert_id($this->con);
    }

  public function affected_rows() {
        return mysqli_affected_rows($this->con);
    }

  public function escape_value($value){
   return mysqli_real_escape_string($this->con, $value);
  }




}

$database =  new MySQLDatabase();
$db =& $database;
?>
