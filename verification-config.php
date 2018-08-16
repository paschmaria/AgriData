<?php
  include('functions.php');

  if (!empty($_GET['hvc'])) {
    $verify_code = e($_GET['hvc']);

    $query = "SELECT * FROM users WHERE verify_code='$verify_code' AND verified=0 LIMIT 1";
    if (mysqli_query($db, $query)) {
      alert_message("Your account has been activated!", "success");
      mysqli_query($db, "UPDATE users SET verified=1 WHERE verify_code='$verify_code'");
    } else {
      header("Location: ./expired.php");
      alert_message("Account activation link expired! ⚠️", "danger");
    }
  }

  if (isset($_POST['resend'])) {
    send_email($_SESSION['user']['email']);
    var_dump($_SESSION['user']['email']);
  }