<?php
  include('functions.php');

  if (isset($_POST['data'])&&isset($_POST['projName'])&&isset($_POST['projId'])) {
    $data = e($_POST['data']);
    $name = e($_POST['projName']);
    $id = e($_POST['projId']);
    $data_array = array();

    if ($data==='accepted') {
      $query = "SELECT * FROM agents WHERE project_name='$name' AND project_id='$id' AND accepted_invite!='2000-01-01 00:00:00'";
      $results = mysqli_query($db, $query);

      while ($agents = mysqli_fetch_assoc($results)) {
        $data_array[] = $agents;
      }
    } elseif ($data==='pending') {
      $query = "SELECT * FROM agents WHERE project_name='$name' AND project_id='$id' AND accepted_invite='2000-01-01 00:00:00'";
      $results = mysqli_query($db, $query);

      while ($agents = mysqli_fetch_assoc($results)) {
        $data_array[] = $agents;
      }
    }
    echo json_encode($data_array);
  }