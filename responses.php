<?php 
  include('functions.php');
  $user = $_SESSION['user'];
  $project_ids = explode(', ', $user['project_id']);
  $project_names = explode(', ', $user['project_name']);
  if(!$user){ 
    header("Location: ./login.php?nexturl=responses.php?$_SERVER[QUERY_STRING]");
    exit; 
  }

  if (isset($_GET['id'])) {
    if ($user['user_type']!=='administrator') {
      header('HTTP/1.0 403 Forbidden');
      header('Location: ./403.html');
    } elseif (!in_array($_GET['id'], $project_ids, true)||!in_array($_GET['name'], $project_names, true)) {
      header('HTTP/1.0 404 Not Found');
      header('Location: ./404.html');
    }
  } else {
    header("Location: ./forms.php"); 
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

  <head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="en"/>
    <meta name="theme-color" content="#817729">
    <meta name="msapplication-TileColor" content="#817729">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Agridata">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="application-name" content="Agridata">
    <link rel="apple-touch-icon" sizes="180x180" href="./apple-touch-icon-precomposed.png">
    <link rel="icon" type="image/x-icon" href="./favicon.ico">
    <link rel="manifest" href="./site.webmanifest">
    <link rel="mask-icon" href="./safari-pinned-tab.svg" color="#5bbad5">
    <title>AGRIDATA - COLLECT AND ANALYZE ANY KIND OF FIELD DATA, ANYTIME</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext">
    <script type="text/javascript" charset="utf-8" src="./assets/js/pace.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="./assets/js/require.min.js"></script>
    <script type="text/javascript" charset="utf-8" >
      setTimeout(hideURLbar,0);function hideURLbar(){window.scrollTo(0,1)}requirejs.config({baseUrl:'.'});
    </script>
    <!-- Dashboard Core -->
    <link href="./assets/css/dashboard.css" rel="stylesheet" />
    <link href="./assets/css/pace.css" rel="stylesheet" />
    <script type="text/javascript" charset="utf-8" src="./assets/js/dashboard.js"></script>
  </head>
  <body>
    <div class="page">
      <div class="page-main">
        <div class="header py-4">
          <div class="container">
            <div class="d-flex">
              <a class="header-brand" href="./forms.php">
                <img src="./assets/images/logo.png" class="header-brand-img" alt="[VERDE]">
              </a>
              <div class="d-flex order-lg-2 ml-auto">
                <?php
                  $user = $_SESSION['user'];
                  if ($user['user_type']==='administrator') {
                    echo '
                      <div class="dropdown d-flex">
                        <a class="nav-link icon" data-toggle="dropdown">
                          <i class="fe fe-bell"></i>
                          <span class="nav-unread d-none"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                          <div class="notification-menu"></div>
                          <div class="dropdown-divider notification-divider d-none"></div>
                          <a href="javascript:void(0)" class="dropdown-item text-center text-muted-dark notification-handler disabled">No notifications found!</a>
                        </div>
                      </div>
                    ';
                  }
                ?>
                <div class="dropdown">
                  <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                    <span class="avatar avatar-blue">
                      <?php
                        $firstname = $_SESSION['user']['firstname'];
                        $lastname = $_SESSION['user']['lastname'];

                        if ($firstname) {
                          $words = explode(" ", $firstname .' '. $lastname);
                          $initials = null;
                          foreach ($words as $w) {
                            $initials .= $w[0];
                          }
                          echo $initials;
                        } else if ($_SESSION['user']['user_type'] === 'agent') {
                          echo "A";
                        }
                      ?>
                    </span>
                    <span class="ml-2 d-none d-lg-block">
                      <span class="text-primary">
                        <?php 
                          if ($_SESSION['user']['firstname']) {
                            echo $_SESSION['user']['firstname'].' '.$_SESSION['user']['lastname'];
                          } else {
                            echo ucwords($_SESSION['user']['username']);
                          }
                        ?>  
                      </span>
                      <small class="text-muted d-block mt-1">
                        <?php echo ucwords($_SESSION['user']['user_type']); ?>
                      </small>
                    </span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item" href="./profile.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>">
                      <i class="dropdown-icon fe fe-user"></i> Profile
                    </a>
                    <!-- <a class="dropdown-item" href="#">
                      <i class="dropdown-icon fe fe-settings"></i> Settings
                    </a>
                    <a class="dropdown-item" href="#">
                      <span class="float-right">
                        <span class="badge badge-primary">6</span>
                      </span>
                      <i class="dropdown-icon fe fe-mail"></i> Inbox
                    </a> -->
                    <div class="dropdown-divider"></div>
                    <!-- <a class="dropdown-item" href="#">
                      <i class="dropdown-icon fe fe-help-circle"></i> Need help?
                    </a> -->
                    <a class="dropdown-item" href="./responses.php?logout='1'">
                      <i class="dropdown-icon fe fe-log-out"></i> Sign out
                    </a>
                  </div>
                </div>
              </div>
              <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
                <span class="header-toggler-icon"></span>
              </a>
            </div>
          </div>
        </div>
        <div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-lg-3 ml-auto">
              </div>
              <div class="col-lg order-lg-first">
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                  <li class="nav-item dropdown" style="<?php if ($_SESSION['user']['user_type']!=='administrator') { ?>visibility: hidden;<?php } ?>">
                    <a href="javascript:void(0)" class="nav-link active" data-toggle="dropdown"><i class="fe fe-activity"></i> Data</a>
                    <div class="dropdown-menu dropdown-menu-arrow">
                      <a href="./overview.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-box"></i> Overview</a>
                      <a href="./responses.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item active"><i class="fe fe-file-text"></i> Responses</a>
                      <a href="./reports.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-share"></i> Export Data</a>
                    </div>
                  </li>
                  <li class="nav-item" style="<?php if ($_SESSION['user']['user_type']!=='administrator') { ?>visibility: hidden;<?php } ?>">
                    <a href="./collaborate.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="nav-link"><i class="fe fe-users"></i> Collaborate</a>
                  </li>
                  <li class="nav-item" style="<?php if ($_SESSION['user']['user_type']!=='administrator') { ?>visibility: hidden;<?php } ?>">
                    <a href="./rf_analytics.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="nav-link"><i class="fe fe-bar-chart-2"></i> Analytics</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="my-3 my-md-5">
          <div class="container">
            <div class="page-header" style="flex-direction: row;">
              <h1 class="page-title">Responses</h1>
              <div class="page-subtitle">
                <?php
                  $project_name = e($_GET['name']);
                  $project_id = e($_GET['id']);
                  $query = "SELECT * FROM $project_name";
                  $results = mysqli_query($db, $query);
                  $rows = mysqli_num_rows($results);
                  $page_num = 0;

                  if (isset($_GET['pagenum'])&&is_numeric($_GET['pagenum'])) {
                    $page_num = (int)$_GET['pagenum'];
                  }  
                ?>
                  
                <p class="m-0">
                  <?php
                    echo ($rows!==0?($page_num*10)+1:$rows) .' - '. ((($page_num*10)+10)>$rows?$rows:(($page_num*10)+10)) .' of '. $rows .' responses'
                  ?>
                </p>
              </div>
              <div class="page-options d-flex">
                <div class="input-icon ml-2">
                  <span class="input-icon-addon">
                    <i class="fe fe-search"></i>
                  </span>
                  <input type="text" id="nameSearch" class="form-control w-10" placeholder="Search responses by collaborator...">
                </div>
              </div>
            </div>
            <?php
              if (isset($_GET['name'])&&isset($_GET['id'])) {
                $user = $_SESSION['user'];
                $project_ids = explode(', ', $user['project_id']);
                $project_names = explode(', ', $user['project_name']);

                $query = "SELECT * FROM $project_name";
                $results = mysqli_query($db, $query) or die("Sql error : " . mysqli_error($db));
                $rows = mysqli_num_rows($results);
                $rows_per_page = 10;
                $pages = (int)ceil($rows/$rows_per_page);

                function create_pagination() {
                  global $pages,$project_name,$project_id;
                  $list_item = '';
                  for ($i=1; $i <= $pages; $i++) { 
                    if ($i<=2) {
                      $list_item .= '<li class="page-item"><a class="page-link" href="./responses.php?name='. $project_name .'&id='. $project_id . ($i===1 ? '' : ('&pagenum='. ($i-1))) .'">'. $i .'</a></li>';
                    } elseif ($i===3&&$pages>3) {
                      $list_item .= '<li class="page-item disabled"><a class="page-link">...</a></li>';
                    } elseif ($i===$pages) {
                      $list_item .= '<li class="page-item"><a class="page-link" href="./responses.php?name='. $project_name .'&id='. $project_id . ($i===1 ? '' : ('&pagenum='. ($i-1))) .'">'. $i .'</a></li>';
                    }
                  }
                  return $list_item;
                }

                function get_collaborator_name($n) {
                  global $db;

                  $results = mysqli_query($db, "SELECT firstname, lastname FROM users WHERE username='$n' LIMIT 1");
                  while ($names = mysqli_fetch_array($results)) {
                    return $names['firstname'] .' '. $names['lastname'];
                  }
                }

                $limit = $page_num*$rows_per_page .', '. $rows_per_page;
                
                $new_query = "SELECT * FROM $project_name LIMIT $limit";
                $table_results = mysqli_query($db, $new_query);
                function display_table_data() {
                  global $table_results, $project_name;
                  $tr = '';

                  while ($tables = mysqli_fetch_assoc($table_results)) {
                    $tr .= '
                      <tr>
                        <td class="text-center">'. $tables['id'] .'</td>
                        <td>'. date("F j, Y", strtotime($tables['date_of_data_collection'])) .'</td>
                        <td>'. date("g:i a", strtotime($tables['date_of_data_collection'])) .'</td>
                        <td>'. get_collaborator_name($tables['registered_by']) .'</td>
                        <td>'. split_string($project_name) .'</td>
                        <td class="text-center">
                          <div class="item-action dropdown">
                            <a href="javascript:void(0)" data-toggle="dropdown" class="icon"><i class="fe fe-more-vertical"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                              <a href="./farmer-profile.php?name=register_farmer&id=c4ca4238a0b923820dcc509a6f75849b&uid='. uniqid($tables['id']) .'" id="navigator" class="dropdown-item"><i class="dropdown-icon fe fe-eye"></i> View Response Details </a>
                            </div>
                          </div>
                        </td>
                      </tr>
                    ';
                  }
                  return $tr;
                }
              ?>
              <div class="card">
                <div class="table-responsive">
                  <table id="responseTable" class="table table-hover table-outline table-vcenter text-nowrap card-table">
                    <thead>
                      <tr>
                        <th class="text-center w-1">
                          S/N
                        </th>
                        <th>Date of Collection</th>
                        <th>Time of Collection</th>
                        <th>Collaborator's Name</th>
                        <th>Project Name</th>
                        <th class="text-center">
                          <i class="icon-settings"></i>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="results"><?php echo display_table_data() ?></tbody>
                  </table>
                </div>
              </div>
              <nav aria-label="Page navigation">
                <ul class="pagination">
                  <li class="page-item<?php echo $page_num===0?' disabled':null ?>">
                    <a class="page-link" href="./responses.php?name=register_farmer&id=c4ca4238a0b923820dcc509a6f75849b<?php echo $page_num<=1 ? '' : ('&pagenum='. ($page_num-1)) ?>" aria-label="Previous">
                      <span aria-hidden="true">&laquo;</span>
                      <span class="sr-only">Previous</span>
                    </a>
                  </li>
                  <?php echo create_pagination() ?>
                  <li class="page-item<?php echo $page_num===($pages-1)||$rows===0?' disabled':null ?>">
                    <a class="page-link" href="./responses.php?name=register_farmer&id=c4ca4238a0b923820dcc509a6f75849b&pagenum=<?php echo $page_num+1 ?>" aria-label="Next">
                      <span aria-hidden="true">&raquo;</span>
                      <span class="sr-only">Next</span>
                    </a>
                  </li>
                </ul>
              </nav>
          <?php } ?>
          </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
              Copyright © 2018
              <a href="../index.html" target="_blank" class="text-primary">Plurimus Technologies</a>. All rights reserved.
            </div>
          </div>
        </div>
      </footer>
    </div>
    <script>
      require(['jquery', 'moment'], function ($, moment) {
        $(function() {
          function loadNotifications(data = "") {
            $.post("notification-config.php",
              {data:data},
              function (data, textStatus, jqXHR) {
                displayNotifications(JSON.parse(data));
              }
            );
          }
          loadNotifications('showNotifications');
          setInterval(function () {
            loadNotifications('showNotifications');
          }, 5000);
          
          let idArr = [];
          function displayNotifications(data) {
            if (data.length!==0) {
              for (let i = 0; i < data.length; i++) {
                const elem = data[i];
                for (const key in elem) {
                  if (elem.hasOwnProperty(key)) {
                    const project = elem[key];
                    if (idArr.indexOf(project.id) === -1) {
                      $('.notification-menu').prepend(`
                        <a href="./<?php echo $_GET['name'] === 'register_farmer'?'farmer-profile':'price-details-full' ?>.php?name=${key}&id=${project.project_id}&uid=<?php echo uniqid('${project.id}') ?>" class="dropdown-item d-flex">
                          <div>
                            <p class="m-0">${displayName(project.registered_by)} submitted a new response: <strong>${displayName(key)}</strong>.</p>
                            <div class="small text-muted d-inline-flex">${moment(project.date_of_data_collection).fromNow()}</div>
                            <div class="small text-muted d-inline-flex float-right"><i>Click to view.</i></div>
                          </div>
                        </a>
                      `);
                      idArr.push(project.id);
                    }
                  }
                }
              }
              $('.notification-divider, .nav-unread').removeClass('d-none');
              $('.notification-handler').removeClass('disabled').html('Clear all notifications');
            } else if (data.length===0) {
              $('.notification-menu').empty();
              $('.notification-divider, .nav-unread').addClass('d-none');
              $('.notification-handler').addClass('disabled').html('No notifications found!');
            }
          }
          
          let $this = document.querySelector('.notification-handler');
          $this.onclick = function (e) {
            if (!this.classList.contains('disabled')) {
              loadNotifications('clearNotifications');
            }
          }

          function displayName(name) {
            var name = name.split("_");
            for (var i = 0; i < name.length; i++) {
                name[i] = name[i][0].toUpperCase() + name[i].substr(1);
            }
            return name.join(" ");
          }

          // Search for names 
          var input, filter, table, tr, td, i;
          input = document.getElementById("nameSearch");
          input.onkeyup = function() {
            filter = this.value.toUpperCase();
            table = document.getElementById("responseTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
              td = tr[i].getElementsByTagName("td")[3];
              if (td) {
                if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                  tr[i].style.display = "";
                } else {
                  tr[i].style.display = "none";
                }
              } 
            }
          }
        })
      })
    </script>
  </body>
</html>