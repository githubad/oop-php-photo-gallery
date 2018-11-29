<?php
require_once('../../includes/initialize.php');
if(!$session->is_logged_in()) { redirect_to("login.php");}



if(isset($_GET['clear']) && ($_GET['clear'] == 'true')) {
  file_put_contents($logfile,'');
  log_action('Logs Cleared', "by User ID {$session->user_id}");
  redirect_to('logfile.php');

}


include_layout_template('admin_header.php');
?>

<a href="logfile.php?clear=true">Clear Log File</a>
<br />

<?php
if(file_exists($logfile) && is_readable($logfile) && $handle = fopen($logfile,'r')) {
  while(!feof($handle)){
    $entry = fgets($handle);
    if(trim($entry) != "") {

      echo "{$entry}" . "<br />";
    }
  }
  fclose($handle);
} else {
  echo "Could not read from {$logfile}";
}


include_layout_template('admin_footer.php');
 ?>
