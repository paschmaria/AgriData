<?php
  include('functions.php');

  $user = $_SESSION['user'];
  $collaborator_arr = array();
  $administrator_arr = array();
  $proj_name_arr = array();
  $proj_id_arr = array();

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

  function update_agents_table() {
    global $db, $user;

    $fullname = $user['firstname'] . " " . $user['lastname'];
    $phone = $user['phone'];
    $accepted_invite = date("Y-m-d H:i:s", time());
    $signed_up = $user['signed_up'];
    $email = $user['email'];

    $query = "UPDATE agents SET fullname='$fullname', phone='$phone', accepted_invite='$accepted_invite', signed_up='$signed_up' WHERE email='$email'";
    if (!mysqli_query($db, $query)) {
      var_dump(mysqli_error($db));
    }
  }

  function update_user_project($a, $b, $c) {
    global $proj_name_arr, $proj_id_arr, $db;
    // fetch arrays of user project names
    // check if current project name is in the array of user project names
    // If it isn't, add it to the array
    $query = "SELECT project_name, project_id FROM users WHERE username='$a' LIMIT 1";
    
    $merged_name_arr = is_array(fetch_users($query, 'project_name')) ? array_merge($proj_name_arr, fetch_users($query, 'project_name')) : $proj_name_arr;
    if (!in_array($b, $merged_name_arr, true)) {
      array_push($merged_name_arr, $b);
    }

    $merged_id_arr = is_array(fetch_users($query, 'project_id')) ? array_merge($proj_id_arr, fetch_users($query, 'project_id')) : $proj_id_arr;
    if (!in_array($c, $merged_id_arr, true)) {
      array_push($merged_id_arr, $c);
    }

    // update user project name to reflect the new ones
    $project_names = implode(', ', $merged_name_arr);
    $project_ids = implode(', ', $merged_id_arr);
    $new_query = "UPDATE users SET project_name='$project_names', project_id='$project_ids' WHERE username='$a'";
    if (!mysqli_query($db, $new_query)) {
      var_dump(mysqli_error($db));
    }
  }

  if (isset($_GET['name'])&&isset($_GET['id'])) {
    $user_project_name = $user['project_name'];
    $project_name_arr = explode(', ', $user_project_name);
    $user_project_id = $user['project_id'];
    $project_id_arr = explode(', ', $user_project_id);
    $project_name = e($_GET['name']);
    $project_id = e($_GET['id']);

    // if(!$user){ 
    //   header("Location: ./login.php?location=agridata.plurimustech.ng/forms.php&name={$project_name}&id={$project_id}"); 
    //   exit;
    // }

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
      $query = "SELECT email FROM agents WHERE project_name='$project_name' AND project_id='$project_id'";
      $results = mysqli_query($db, $query);
      $emails = mysqli_fetch_assoc($results);
      
      if (in_array($user['email'], $emails, true)) {
        $collaborator = $user['username'];

        // get all the collaborators on this project
        $query = "SELECT collaborators FROM projects WHERE project_name='$project_name' AND project_id='$project_id' LIMIT 1";
        $merged_arr = is_array(fetch_users($query, 'collaborators')) ? array_merge($collaborator_arr, fetch_users($query, 'collaborators')) : $collaborator_arr;

        if (!in_array($collaborator, $merged_arr, true)) {
          array_push($merged_arr, $collaborator);
        } else {
          alert_message("You're already a collaborator!", "success");
        }

        $new_collaborators = implode(', ', $merged_arr);

        // add user as a collaborator on the project
        $query = "UPDATE projects SET collaborators='$new_collaborators' WHERE project_name='$project_name' AND project_id='$project_id' LIMIT 1";
        if (!mysqli_query($db, $query)) {
          var_dump(mysqli_error($db));
        }

        // update project status on user database
        update_user_project($collaborator, $project_name, $project_id);
        update_agents_table();
      } else {
        alert_message("You have no permission to access this project. Contact your Admin!", "danger");
      }
      
    }
  }
  // var_dump($user['project_name']);
