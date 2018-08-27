<?php
  include('functions.php');

  if (isset($_GET['hvc'])) {
    $verify_code = e($_GET['hvc']);
    $zero = 0;
    $one = 1;
    $query = "SELECT * FROM users WHERE verified='$zero' AND verify_code='$verify_code' LIMIT 1";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results)===1) {
      mysqli_query($db, "UPDATE users SET verified='$one' WHERE verify_code='$verify_code'");
      alert_message("Your account has been activated! Login to continue.", "success");
    } else {
      alert_message("Account activation link expired! ⚠️", "danger");
    //   header("Location: ./expired.php");
    }
    mysqli_close($db);
  }
