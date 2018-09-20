<?php
  include('functions.php');

  $user = $_SESSION['user'];
  $username = $user['username'];
  $user_projects = explode(', ', $user['project_name']);
  $data = $_POST['data'];
  $response_array = array();
  $data_array = array();

  // Not in use at the moment
  function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
      'y' => 'year',
      'm' => 'month',
      'w' => 'week',
      'd' => 'day',
      'h' => 'hour',
      'i' => 'minute',
      's' => 'second',
    );
    foreach ($string as $k => &$v) {
      if ($diff->$k) {
        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
      } else {
        unset($string[$k]);
      }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
  }

  // Not in use at the moment
  function get_collaborator_name($n) {
    global $db;

    $query = "SELECT firstname, lastname FROM users WHERE username='$n' LIMIT 1";
    $result = mysqli_query($db, $query);
    while ($user = mysqli_fetch_assoc($result)) {
      if ($user['firstname']!==''&&$user['lastname']!=='') {
        $fullname = $user['firstname']. ' ' .$user['lastname'];
        return $fullname;
      } else {
        return $n;
      }
    }
  }

  if ($data==='showNotifications') {
    foreach ($user_projects as $key => $project) {
      $query = "SELECT * FROM projects WHERE project_name='$project' AND project_owner='$username' LIMIT 1";
      $result = mysqli_query($db, $query);
      if (mysqli_num_rows($result)===1) {
        $query = "SELECT * FROM $project WHERE seen_as_notification=0 AND deleted=0";
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result) > 1) {
          while ($response = mysqli_fetch_assoc($result)) {
            $response_array[$project] = $response;
            $data_array[] = $response_array;
          }
        }
      }
    }
  } elseif ($data==='clearNotifications') {
    foreach ($user_projects as $key => $project) {
      $query = "UPDATE $project SET seen_as_notification=1, deleted=1";
      if (!mysqli_query($db, $query)) {
        var_dump(mysqli_error($db));
      }
    }
    $data_array[] = '';
  } else {
    $data_array[] = $data;
  }

  echo json_encode($data_array);