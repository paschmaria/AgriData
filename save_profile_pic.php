<?php
  include('functions.php');

  $path = "./assets/images/profile_pictures";
  $username = $_SESSION['user']['username'];
  $valid_file_formats = array("jpg", "png", "gif","jpeg");
  if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
  {
    $name = $_FILES['profile_pic']['name'];
    $size = $_FILES['profile_pic']['size'];

    if(strlen($name)) {
      list($txt, $ext) = explode(".", $name);
      if(in_array($ext,$valid_file_formats)) {
        if($size<(2097152)) {
          $user_id = 1;
          $image_name = time().'_'.$user_id.".".$ext;
          $tmp = $_FILES['photoimg']['tmp_name'];
          if(move_uploaded_file($tmp, $path.$image_name)){
            $query = "UPDATE users SET profile_pic='$new_profile_pic' WHERE username='$username'";
    
            $result = mysqli_query($db, $query) or die("error to update image data");

            array_push($errors, "Successfully!  Uploaded image..");
          }
          else
            array_push($errors, "Image Upload failed..!");
        }
        else
          array_push($errors, "Image file size maximum 2 MB..!");
      }
      else
        array_push($errors, "Invalid file format..!");
    }
    else
      array_push($errors, "Please select image..!");

    echo $errors;
    exit;
  }