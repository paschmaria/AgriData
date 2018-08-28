<?php
  include('functions.php');

  $profile_pic 	= $_FILES['profile_pic'];
  $username		  = $_SESSION['user']['username'];
  
  echo "error";
  $profile_pic_name = $profile_pic['name'];
  $profile_pic_size = $profile_pic['size'];
  $profile_pic_tmp  = $profile_pic['tmp_name'];

  $new_profile_pic = check_and_save_file($profile_pic_size,$profile_pic_name,$profile_pic_tmp,"profile_pictures");

  if (count($errors===0)) {
    $query = "UPDATE users SET profile_pic='$new_profile_pic' WHERE username='$username'";

    if (!mysqli_query($db, $query)) {
      echo mysqli_error($db);
    }
  }