<?php
  include('functions.php');

  if (!empty($_GET['hash'])) {
    $verify_code = e($_GET['hash']);

    $query = "SELECT * FROM users WHERE verify_code='$verify_code' LIMIT 1";
    if (mysqli_query($db, $query)) {
      alert_message("Your account has been verified!", "success");
    }
  }

  mysqli_close($db);

  if (isset($_POST['resend'])) {
    send_email($_SESSION['user']['email']);
    var_dump($_SESSION['user']['email']);
  }