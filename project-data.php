<?php
  include('functions.php');

  $user = $_SESSION['user']['username'];
  $query = "SELECT * FROM users WHERE username='$user' LIMIT 1";
	$user_details = mysqli_fetch_assoc(mysqli_query($db, $query));

  $project_name = $user_details['project_name'];
  $project_arr = explode(', ', $project_name);
  $data_array = array();
  
  foreach ($project_arr as $key => $project) {
    $query = "SELECT * FROM projects WHERE project_name='$project' LIMIT 1";
    $results = mysqli_query($db, $query);
    while ($projects = mysqli_fetch_assoc($results)) {
      $data_array[] = $projects;
    }
  }

  echo json_encode($data_array);
  mysqli_close($db);