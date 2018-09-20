<?php
  include("functions.php");

  if (isset($_POST['response'])&&isset($_POST['projName'])&&isset($_POST['projId'])) {
    $response = e($_POST['response']);
    $name = e($_POST['projName']);
    $id = e($_POST['projId']);
    $date_array = array();
    $time_array = array();
    $frequency = array();
    $current_day = ['12AM', '1AM', '2AM', '3AM', '4AM', '5AM', '6AM', '7AM', '8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM', '3PM', '4PM', '5PM', '6PM', '7PM', '8PM', '9PM', '10PM', '11PM'];
    $current_week = [
      'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
    ];
    $current_month = array();
    // $current_quarter = array();

    $query = "SELECT date_of_data_collection FROM $name";
    $results = mysqli_query($db, $query);

    while ($dates = mysqli_fetch_row($results)) {
      foreach ($dates as $date) {
        $date_array[] = $date;
      }
    }

    $no_of_days = date('t', strtotime(date('d')));

    for ($i=1; $i <= $no_of_days; $i++) { 
      array_push($current_month, $i);
    }

    // function update_frequency($c, $t) {
    //   global $frequency;
    // }

    if ($response==='day') { // if today's data was requested
      foreach ($date_array as $date) { // get each date a response was collected
        if (date('Ymd')===date('Ymd',strtotime($date))) { // if any date matches today's date
          array_push($time_array, date('gA', strtotime($date)));
        }
      }

      // update_frequency($current_day, $time_array);
      foreach ($current_day as $d) {
        if (in_array($d, $time_array, true)) {
          if (!function_exists('filter')) {
            function filter($a) {
              global $d;
              
              return $a==$d;
            };
          }
          $b = count(array_filter($time_array, 'filter'));
          array_push($frequency, $b);
        } else {
          array_push($frequency, 0);
        }
      }
      $data = array();
      array_push($data, $current_day, $frequency);
      echo json_encode($data);
    } elseif ($response==='week') {
      foreach ($date_array as $date) {
        // find the year (ISO-8601 year number) and the current week of today's date
        $year = date('o');
        $week = date('W');
        $week_arr = array();
        // print week for the current date
        for($i = 1; $i <= 7; $i++) {
            // timestamp from ISO week date format
            $ts = strtotime($year.'W'.$week.$i);
            array_push($week_arr, date("Y-m-d", $ts));
        }
        if (in_array(date('Y-m-d',strtotime($date)), $week_arr, true)) {
          array_push($time_array, date('l',strtotime($date)));
        }
      }

      // update_frequency($current_week, $time_array);
      foreach ($current_week as $d) {
        if (in_array($d, $time_array, true)) {
          if (!function_exists('filter')) {
            function filter($a) {
              global $d;
              
              return $a==$d;
            };
          }
          $b = count(array_filter($time_array, 'filter'));
          array_push($frequency, $b);
        } else {
          array_push($frequency, 0);
        }
      }
      $data = array();
      array_push($data, $current_week, $frequency);
      echo json_encode($data);
    } elseif ($response==='month') {
      foreach ($date_array as $date) {
        if (date('Ym')===date('Ym',strtotime($date))) {
          array_push($time_array, (int)date('j',strtotime($date)));
        }
      }

      // update_frequency($current_month, $time_array);
      foreach ($current_month as $d) {
        if (in_array($d, $time_array, true)) {
          if (!function_exists('filter')) {
            function filter($a) {
              global $d;
              
              return $a==$d;
            };
          }
          $b = count(array_filter($time_array, 'filter'));
          array_push($frequency, $b);
        } else {
          array_push($frequency, 0);
        }
      }
      $data = array();
      array_push($data, $current_month, $frequency);
      echo json_encode($data);
    }
  }