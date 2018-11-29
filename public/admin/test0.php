<?php
require_once('../../includes/initialize.php');
if(!$session->is_logged_in()) { redirect_to("login.php");}

include_layout_template('admin_header.php');
//
// $user = new User();
// $user->username = "adnanqaizar";
// $user->password =  "anypass";
// $user->first_name = "John";
// $user->last_name  = "Smith";
// $user->create();

// User::$username="a";
// User::$password="a";
// User::$first_name="a";
// User::$last_name="a";
// User::create();
//

// $user = User::find_by_id(24);
// $user->password = "aaaa";
// $user->save();

//  $user = User::find_by_id(24);
// echo $user->password;
//  $user->save();


// $user = User::find_by_id(33);
// $user->delete();


// $users = User::find_all();
// foreach ($users as $user) {
//   echo "User : " . $user->username . "<br />";
//   echo "Password : " . $user->password . "<br />";
// }


include_layout_template('admin_footer.php');

 ?>
