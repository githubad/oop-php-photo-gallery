<?php
require_once(LIB_PATH.DS."database.php");

class Photograph extends DatabaseObject {

  protected static $table_name="photographs";
  protected static $db_fields =array('id','filename','type','size','caption');
  public $id;
  public $filename;
  public $type;
  public $size;
  public $caption;

  private $temp_path;
  protected $upload_dir="images";
  public $errors=array();

  protected $upload_errors = array(
    UPLOAD_ERR_OK               => " No errors",
    UPLOAD_ERR_INI_SIZE         => " Larger than upload_max_filesize",
    UPLOAD_ERR_FORM_SIZE        => " Larger than form MAX_FILE_SIZE",
    UPLOAD_ERR_PARTIAL          => " Partial Upload",
    UPLOAD_ERR_NO_FILE          => " No file",
    UPLOAD_ERR_NO_TMP_DIR       => " No temporary directory",
    UPLOAD_ERR_CANT_WRITE       => " Can't write to disk",
    UPLOAD_ERR_EXTENSION        => " File upload stopped by extention"

  );

  public function attach_file($file) {
    if(!$file || empty($file) || !is_array($file)) {
      $this->errors[] = "No file was uploaded.";
      return false;
    } else if ($file['error'] != 0) {
      $this->errors[] = $this->upload_errors[$file['error']];
      return false;
    } else {
      $this->temp_path = $file['tmp_name'];
      $this->filename = basename($file['name']);
      $this->type = $file['type'];
      $this->size = $file['size'];
      return true;
    }
  }

  public function save() {
    // overwriting save -  to use parent we can do parent::save();
    if(isset($this->id)) {
      // Really just to update the caption
      $this->update();
    } else {
      // Making sure of no errors, attemting to move file, saving the entry to database
      if(!empty($this->errors)) { return false;}
      if(strlen($this->caption) >= 255) {
        $this->errors[] = " The caption can only be 255 characters long";
        return false;
      }
      if(empty($this->filename) || empty($this->temp_path)) {
        $this->errors[] = "The file location was not available";
        return false;
      }

      $target_path = SITE_ROOT . DS . 'public' . DS . $this->upload_dir . DS . $this->filename;

      if(file_exists($target_path)) {
        $this->errors[] = "The file {$this->filename} already exists.";
        return false;
      }

      if(move_uploaded_file($this->temp_path, $target_path)){
        //Success
        if($this->create()) {
          unset($this->temp_path);
          return true;
        }
      } else {
        // File not moved
        $this->errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder.";
        return false;
      }


    }

  }

  public function image_path() {
    return $this->upload_dir.DS.$this->filename;
  }

  public function size_as_text() {
    if($this->size < 1024) {
      return "{$this->size} bytes";
    } else if($this->size < 1048576) {
      $size_kb = round($this->size/1024);
      return "{$size_kb} KB";
    } else {
      $size_mb = round($this->size/1048576, 1);
      return "{$size_mb} MB";
    }
  }

  public function destroy() {
    // Remove the database entry
    // then remove the file
    if($this->delete()) {
     $target_path = SITE_ROOT.DS.'public'.DS.$this->image_path();
     return unlink($target_path) ? true : false;


    } else {
      //database delete failed
    }
  }

  public function comments() {
    return Comment::find_comments_on($this->id);
  }

// class end
}

 ?>
