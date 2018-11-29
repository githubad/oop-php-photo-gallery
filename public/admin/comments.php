<?php
require_once('../../includes/initialize.php');
if(!$session->is_logged_in()) { redirect_to("login.php");}

include_layout_template('admin_header.php');

if(empty($_GET['id'])) {
  $session->message("No photograph ID was provided");
  redirect_to('index.php');
}

$photo = Photograph::find_by_id($_GET['id']);
if(!$photo) {
  $session->message("The photo could not be located");
  redirect_to('index.php');
}
 $comments = $photo->comments();
  foreach($comments as $comment) {
    echo htmlentities($comment->author);
    echo "<br />";
    echo strip_tags($comment->body, '<strong><em><p>');
    echo "<br />";
    echo datetime_to_text($comment->created);

    ?>
    <a href="delete_comment.php?id=<?php echo $comment->id; ?>">Delete Comment</a>

    <?php
    echo "<br />";
    echo "<br />";
   }
  if(empty($comments)) { echo "No comments"; }

  echo output_message($message);
include_layout_template('admin_footer.php');

?>
