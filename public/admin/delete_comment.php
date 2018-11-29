<?php
require_once('../../includes/initialize.php');
if(!$session->is_logged_in()) { redirect_to("login.php");}

include_layout_template('admin_header.php');

if(empty($_GET['id'])) {
  $session->message("No comment ID was provided");
  redirect_to('index.php');
}

$comment = Comment::find_by_id($_GET['id']);
if($comment && $comment->delete()) {
  $session->message("The comment was deleted");
  redirect_to("comments.php?id={$comment->photograph_id}");
} else {
  $session->message("The comment not be deleted");
  redirect_to('index.php');
}

include_layout_template('admin_footer.php');

 ?>
