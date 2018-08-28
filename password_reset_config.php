<?php
  include('functions.php');

  if (isset($_POST['reset_password'])) {
    save_password();
  }

  function save_password() {
    global $db, $errors;

		// receive all input values from the form. 
		// call the e() function to escape form values
		$password_1  =  e($_POST['passw_1']);
		$password_2  =  e($_POST['passw_2']);

    if (!empty($_GET['pass_rc'])) {
      // form validation: ensure that the form is correctly filled
      if (empty($password_1)) { 
        array_push($errors, "Password is required"); 
      }
      if (empty($password_2)) { 
        array_push($errors, "Please confirm password"); 
      }
      if ($password_1 !== $password_2) {
        array_push($errors, "The two passwords do not match");
      }
  
      $reset_code = e($_GET['pass_rc']);
      $password = md5($password_1); //encrypt the password before saving in the database

      $query = "SELECT * FROM users WHERE password_reset_code='$reset_code' LIMIT 1";
      $results = mysqli_query($db, $query);
      $user = mysqli_fetch_assoc($results);
      $email = $user['email'];

      if (mysqli_num_rows($results)===1&&count($errors)===0) {
        mysqli_query($db, "UPDATE users SET password='$password' WHERE email='$email'");

        // var_dump(count($errors));
				$_SESSION['user'] = $user; // put logged in user in session
				$_SESSION['success']  = "You are now logged in";

				header('location: ./forms.php');
      } elseif (mysqli_num_rows($results)!==1) {
        alert_message("Password reset link has expired! ⚠️", "danger");
      }
      mysqli_close($db);
    } else {
      alert_message("Invalid password reset link! ⚠️", "danger");
    }
  }
