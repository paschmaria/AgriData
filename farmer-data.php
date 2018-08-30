<?php
  include('functions.php');
  
  $user = $_SESSION['user']['username'];
  $query = "SELECT * FROM register_farmer";
  $result = mysqli_query($db, $query);
  $data_array = array();
  while ($farmers = mysqli_fetch_assoc($result)) {
    $data_array[] = $farmers;
  }

  echo json_encode($data_array);

  // $fp = fopen('data.php', 'w'); 
  // fwrite($fp, json_encode($data_array)); 
  // if(!fwrite($fp, json_encode($data_array))){
  //   die('Error : File Not Opened. ' . error_get_last());
  // }
  // fclose($fp); 
  
  mysqli_close($db);