<?php
require_once('../../includes/initialize.php');
if(!$session->is_logged_in()) { redirect_to("login.php");}

include_layout_template('admin_header.php');

$photos = Photograph::find_all();
echo output_message($message);
 foreach($photos as $photo) {
   echo "<br />";
  ?>
  <img src="../<?php echo $photo->image_path(); ?>" alt="" caption="<?php echo $photo->caption; ?>" width="300" />
  <p><?php echo $photo->filename; ?></p>
  <p><?php echo $photo->caption; ?></p>
  <p><?php echo $photo->size_as_text(); ?></p>
  <p><?php echo $photo->type; ?></p>
  <a href="comments.php?id=<?php echo $photo->id; ?>">Comments</a>
  <p><?php echo count($photo->comments()); ?></p>

  <p><a href="delete_photo.php?id=<?php echo $photo->id; ?>">Delete</a></p>

<?php } ?>
<br /><a href="photo_upload.php">Upload a New Photograph</a>
<?php

include_layout_template('admin_footer.php');

?>
