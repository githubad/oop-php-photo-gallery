<?php
require_once('../../includes/initialize.php');
if(!$session->is_logged_in()) { redirect_to("login.php");}

include_layout_template('admin_header.php');
echo output_message($message);
?>
<a href="list_photos.php">List Photos</a>
<a href="logfile.php">View Log File</a>
<a href="logout.php">Logout</a>

<?php

include_layout_template('admin_footer.php');
 ?>
