<?php
  include('functions.php');

	// call the invite_user() function if the invite user form is submitted
	if (isset($_POST['invite_user'])) {
		invite_user();
  }

	function invite_user() {
		global $db, $errors, $email;

		if (isset($_GET['name'])) {
      // receive input from the form. 
      // call the e() function to escape email value
      $email = e(e_valid($_POST['email_invite']));
      $projname = $_GET['name'];
      $projid = e($_GET['id']);
      $sent_invite = date("Y-m-d H:i:s", time());
      $admin = $_SESSION['user']['username'];
      // var_dump($sent_invite);
      // form validation: ensure that the form is correctly filled
      if (empty($email)) { 
        array_push($errors, "Email is required"); 
      }

      // first check the database to make sure email hasn't already been sent
      $query = "SELECT * FROM agents WHERE email='$email' AND project_id='$projid' LIMIT 1";
      $result = mysqli_query($db, $query);
      $user = mysqli_fetch_assoc($result);
      
      if ($user) { // if user exists
        if ($user['email'] === $email && $user['project_name'] === $projname && $user['project_id'] === $projid) {
          array_push($errors, "Invitation has been sent to email address!");
        }
      }

      if (count($errors) === 0) {
        $query = "INSERT INTO agents (fullname, email, phone, sent_invite, accepted_invite, signed_up, responses, project_name, project_id, project_owner) VALUES ('', '$email', '', '$sent_invite', '2000-01-01 00:00:00', '2000-01-01 00:00:00', 0, '$projname', '$projid', '$admin')";
        if (!mysqli_query($db, $query)) {
          var_dump(mysqli_error($db));
        } else {
          invite_email_content($email, $projid, $projname, $admin);
        }
      }
    }
    // var_dump($_GET['name']);
    // echo $errors;
	}