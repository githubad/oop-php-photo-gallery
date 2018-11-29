<?php
require_once('../includes/initialize.php');

if(empty($_GET['id'])) {
  $session->message("No photograph ID was provided");
  redirect_to('index.php');
}

$photo = Photograph::find_by_id($_GET['id']);
if(!$photo) {
  $session->message("The photo could not be located");
  redirect_to('index.php');
}

if(isset($_POST['submit'])) {
  $author = trim($_POST['author']);
  $body = trim($_POST['body']);

  $new_comment = Comment::make($photo->id, $author, $body);
  if($new_comment && $new_comment->save()) {
$session->message("Comment post successful");
  redirect_to("photo.php?id={$photo->id}");
  } else {
    //comment failed
    $message = "There was an error that prevented the comment from being saved.";
  }

} else {
  $author = "";
  $body = "";

}

include_layout_template('header.php'); ?>

<a href="index.php">Back</a>
<img src="<?php echo $photo->image_path(); ?>" />
<p><?php echo $photo->caption; ?></p>

<!--  comment display -->
<?php
 $comments = $photo->comments();
  foreach($comments as $comment) {
    echo htmlentities($comment->author);
    echo "<br />";
    echo strip_tags($comment->body, '<strong><em><p>');
    echo "<br />";
    echo datetime_to_text($comment->created);
    echo "<br />";
    echo "<br />";
    }
  if(empty($comments)) { echo "No comments"; }
?>


<!-- list comments -->
<?php echo output_message($message); ?>
<form class="" action="photo.php?id=<?php echo $photo->id ?>" method="post">
  <label for="">Your Name:</label>
<input type="text" name="author" value="<?php echo $author; ?>">
<label for="">Your Comment:</label>
<textarea  name="body" cols="40" rows="8"><?php echo $body; ?></textarea>
<input type="submit" name="submit" value="Submit Comment">
</form>
<?php
include_layout_template('footer.php');
?>
