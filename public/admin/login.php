<?php
require_once("../../includes/initialize.php");


if($session->is_logged_in()) { redirect_to("index.php");}

if(isset($_POST['submit'])) {

  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  // Check database if the user/pass exists or not
$found_user = User::authenticate($username, $password);

if($found_user) {
  $session->login($found_user);
  log_action('Login', "{$found_user->username} logged in.");
  redirect_to("index.php");
} else {
  // username/password not found in Database
  $message = "Username/Password combination is incorrect";
}
// Form Submit end
} else {
  // Form has not been submitted
  $username = "";
  $password = "";
}

 ?>

 <form class="" action="login.php" method="post">
   <label for="">Username</label>
   <input type="text" name="username" value="" placeholder="Username">
   <br />
   <br />
   <label for="">Password</label>
   <input type="text" name="password" value="" placeholder="Password">
   <br />
   <br />
   <input type="submit" name="submit" value="Login">
 </form>

<?php if(isset($database)) {$database->close_connection();} ?>
