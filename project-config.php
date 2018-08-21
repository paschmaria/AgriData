<?php
  include('functions.php');

  $user = $_SESSION['user'];
  $collaborator_arr = array();
  $administrator_arr = array();
  $proj_name_arr = array();

  function fetch_users($query, $query_string) {
    global $db;
    // get current collaborators]
    $results = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($results);
    if ($user[$query_string]!=="") {
      $new = explode(', ', $user[$query_string]);
      return $new;
    }
  }

  function update_user_project($a, $b) {
    global $proj_name_arr, $db;
    // fetch arrays of user project names
    // check if current project name is in the array of user project names
    // If it isn't, add it to the array
    $query = "SELECT project_name FROM users WHERE username='$a' LIMIT 1";
    $merged_arr = is_array(fetch_users($query, 'project_name')) ? array_merge($proj_name_arr, fetch_users($query, 'project_name')) : $proj_name_arr;
    if (!in_array($b, $merged_arr, true)) {
      array_push($merged_arr, $b);
    }

    // update user project name to reflect the new ones
    $project_names = implode(', ', $merged_arr);
    $new_query = "UPDATE users SET project_name='$project_names' WHERE username='$a'";
    if (!mysqli_query($db, $new_query)) {
      var_dump(mysqli_error($db));
    }
  }

  if (!empty($_GET['id'])) {
    $user_project_name = $user['project_name'];
    $project_name_arr = explode(', ', $user_project_name);
    $user_project_id = $user['project_id'];
    $project_id_arr = explode(', ', $user_project_id);
    $project_name = e($_GET['name']);
    $project_id = e($_GET['id']);

    if(!$user){ 
      header("Location: ./login.php?location=agridata.plurimustech.ng/forms.php&name={$project_name}&id={$project_id}"); 
      exit;
    }

    if ($user['user_type']==="administrator") {
      foreach ($project_name_arr as $project) {
        if ($project) {
          foreach ($project_id_arr as $id) {
            if ($project===$project_name&&$id===$project_id) {
              $project_owner = $user['username'];
              $query = "SELECT project_owner FROM projects WHERE project_name='$project_name' AND project_id='$project_id' LIMIT 1";
              $merged_arr = is_array(fetch_users($query, 'project_owner')) ? array_merge($administrator_arr, fetch_users($query, 'project_owner')) : $administrator_arr;
              if (!in_array($project_owner, $merged_arr, true)) {
                array_push($merged_arr, $project_owner);
              } else {
                alert_message("You're already an administrator!", "success");
              }
              $new_admins = implode(', ', $merged_arr);

              $query = "UPDATE projects SET project_owner='$new_admins' WHERE project_name='$project_name' AND project_id='$project_id' LIMIT 1";
              if (!mysqli_query($db, $query)) {
                var_dump(mysqli_error($db));
              }
            }
          }
        }
      }
    } elseif ($user['user_type']==="agent") {
      $collaborator = $user['username'];
      $query = "SELECT collaborators FROM projects WHERE project_name='$project_name' AND project_id='$project_id' LIMIT 1";
      $merged_arr = is_array(fetch_users($query, 'collaborators')) ? array_merge($collaborator_arr, fetch_users($query, 'collaborators')) : $collaborator_arr;

      if (!in_array($collaborator, $merged_arr, true)) {
        array_push($merged_arr, $collaborator);
      } else {
        alert_message("You're already a collaborator!", "success");
      }

      $new_collaborators = implode(', ', $merged_arr);
      // var_dump($merged_arr);
      $query = "UPDATE projects SET collaborators='$new_collaborators' WHERE project_name='$project_name' AND project_id='$project_id' LIMIT 1";
      if (!mysqli_query($db, $query)) {
        var_dump(mysqli_error($db));
      }

      update_user_project($collaborator, $project_name);
    }
  }
  // var_dump($user['project_name']);
