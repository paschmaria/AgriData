<?php 
  include('functions.php');
  $user = $_SESSION['user'];
  $project_ids = explode(', ', $user['project_id']);
  $project_names = explode(', ', $user['project_name']);
  
  if(!$user){ 
    header("Location: ./login.php?nexturl=price-tables.php?$_SERVER[QUERY_STRING]"); 
    exit; 
  }

  if (isset($_GET['id'])) {
    if ($user['user_type']!=='administrator') {
      header('HTTP/1.0 403 Forbidden');
      header('Location: ./403.html');
    } elseif (!in_array(e($_GET['id']), $project_ids, true)||!in_array(e($_GET['name']), $project_names, true)) {
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
    <script charset="utf-8" src="./assets/js/pace.min.js"></script>
    <script src="./assets/js/require.min.js"></script>
    <script>
      setTimeout(hideURLbar, 0);
      function hideURLbar(){
        window.scrollTo(0,1);
      }
      requirejs.config({
        baseUrl: '.'
      });
    </script>
    <!-- Dashboard Core -->
    <link href="./assets/css/dashboard.css" rel="stylesheet" />
    <link href="./assets/css/pace.css" rel="stylesheet" />
    <script charset="utf-8" async src="./assets/js/dashboard.js"></script>
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
                <div class="dropdown d-none d-md-flex">
                  <a class="nav-link icon" data-toggle="dropdown">
                    <i class="fe fe-bell"></i>
                    <span class="nav-unread"></span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a href="#" class="dropdown-item d-flex">
                      <!-- <span class="avatar mr-3 align-self-center" style="background-image: url(demo/faces/male/41.jpg)"></span> -->
                      <div>
                        <p>New farmer signed on -
                          <strong>Musa Abdullahi</strong>
                        </p>
                        <div class="small text-muted">10 minutes ago</div>
                      </div>
                    </a>
                    <a href="#" class="dropdown-item d-flex">
                      <div>
                        <p>50 messages sent to farmers in
                          <strong>Kano State</strong>
                        </p>
                        <div class="small text-muted">1 hour ago</div>
                      </div>
                    </a>
                    <a href="#" class="dropdown-item d-flex">
                      <div>
                        <p>5 voice calls were not picked.</p>
                        <div class="small text-muted">2 hours ago</div>
                      </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item text-center text-muted-dark">Mark all as read</a>
                  </div>
                </div>
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
                    <a class="dropdown-item" href="#">
                      <i class="dropdown-icon fe fe-settings"></i> Settings
                    </a>
                    <a class="dropdown-item" href="#">
                      <span class="float-right">
                        <span class="badge badge-primary">6</span>
                      </span>
                      <i class="dropdown-icon fe fe-mail"></i> Inbox
                    </a>
                    <!-- <a class="dropdown-item" href="#">
                      <i class="dropdown-icon fe fe-send"></i> Message
                    </a> -->
                    <div class="dropdown-divider"></div>
                    <!-- <a class="dropdown-item" href="#">
                      <i class="dropdown-icon fe fe-help-circle"></i> Need help?
                    </a> -->
                    <a class="dropdown-item" href="./price-tables.php?logout='1'">
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
                <form class="input-icon my-3 my-lg-0">
                  <input type="search" class="form-control header-search" placeholder="Search&hellip;" tabindex="1">
                  <div class="input-icon-addon">
                    <i class="fe fe-search"></i>
                  </div>
                </form>
              </div>
              <div class="col-lg order-lg-first">
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                  <li class="nav-item dropdown" style="<?php if ($_SESSION['user']['user_type']!=='administrator') { ?>visibility: hidden;<?php } ?>">
                    <a href="javascript:void(0)" class="nav-link active" data-toggle="dropdown"><i class="fe fe-trending-up"></i> Analytics</a>
                    <?php if ($_GET['name'] === 'register_farmer') { ?>
                      <div class="dropdown-menu dropdown-menu-arrow">
                        <a href="./overview.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item active"><i class="fe fe-box"></i> Overview</a>
                        <a href="./responses.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-file-text"></i> Bio-data</a>
                        <a href="./rf_analytics.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-bar-chart-2"></i> Demographics</a>
                      </div>
                    <?php } elseif ($_GET['name'] === 'market_prices') { ?>
                      <div class="dropdown-menu dropdown-menu-arrow">
                        <a href="./overview.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-box"></i> Overview</a>
                        <a href="./price-tables.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item active"><i class="fe fe-file-text"></i> Price Tables</a>
                      </div>
                    <?php } ?>
                  </li>
                  <li class="nav-item">
                    <a href="./reports.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="nav-link"><i class="fe fe-file-text"></i> Data</a>
                  </li>
                  <li class="nav-item">
                    <a href="./collaborate.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="nav-link"><i class="fe fe-users"></i> Collaborate</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="my-3 my-md-5">
          <div class="container">
            <div class="page-header" style="flex-direction: row;">
              <h1 class="page-title">Market Reports</h1>
              <div class="page-subtitle">
                <?php
                  $query = "SELECT * FROM market_prices";
                  $results = mysqli_query($db, $query);
                  $rows = mysqli_num_rows($results);
                  $page_num = 0;

                  if (isset($_GET['pagenum'])&&is_numeric($_GET['pagenum'])) {
                    $page_num = (int)$_GET['pagenum'];
                  }  
                  
                  echo '
                    <p class="m-0">
                      '. ($page_num>0?($page_num*10)+1:0) .' - '. ((($page_num*10)+10)>$rows?$rows:(($page_num*10)+10)) .' of '. $rows .' Market Records
                    </p>
                  ';
                ?>
              </div>
              <div class="page-options d-flex">
                <!-- <select class="form-control custom-select w-auto">
                  <option value="asc">Newest</option>
                  <option value="desc">Oldest</option>
                </select> -->
                <div class="input-icon ml-2">
                  <span class="input-icon-addon">
                    <i class="fe fe-search"></i>
                  </span>
                  <input type="text" id="marketSearch" class="form-control w-10" placeholder="Search for market...">
                </div>
              </div>
            </div>
            <?php
              if (isset($_GET['name'])&&isset($_GET['id'])) {
                $user = $_SESSION['user'];
                $project_ids = explode(', ', $user['project_id']);
                $project_names = explode(', ', $user['project_name']);
                $project_name = e($_GET['name']);
                $project_id = e($_GET['id']);
                $page_num = 0;

                $query = "SELECT * FROM $project_name";
                $results = mysqli_query($db, $query);
                $rows = mysqli_num_rows($results);
                $rows_per_page = 10;
                $pages = (int)ceil($rows/$rows_per_page);
                
                function create_pagination() {
                  global $pages;
                  $list_item = '';
                  for ($i=1; $i <= $pages; $i++) { 
                    $list_item .= '<li class="page-item"><a class="page-link" href="./price-tables.php?name='. $project_name .'&id='. $project_id . ($i===1 ? '' : ('&pagenum='. ($i-1))) .'">'. $i .'</a></li><br />';
                  }
                  return $list_item;
                }

                if (isset($_GET['pagenum'])&&is_numeric($_GET['pagenum'])) {
                  $page_num = (int)$_GET['pagenum'];
                }

                $limit = $page_num*$rows_per_page .', '. $rows_per_page;
                
                $new_query = "SELECT * FROM $project_name LIMIT $limit";
                $table_results = mysqli_query($db, $new_query);

                function display_table_data() {
                  global $table_results;
                  $tr = '';
                  $count = 1;

                  while ($tables = mysqli_fetch_assoc($table_results)) {
                    $tr .= '
                      <tr>
                        <td><span class="text-muted">'. $count++ .'</span></td>
                        <td>'. $tables['collectors_firstname'] .'</td>
                        <td>'. $tables['collectors_lastname'] .'</td>
                        <td>'. $tables['state'] .'</td>
                        <td>'. $tables['market_name'] .'</td>
                        <td>'. $tables['market_lga'] .'</td>
                        <td>'. $tables['market_days'] .'</td>
                        <td>'. $tables['produce_price_per_bag'] .'</td>
                        <td>'. $tables['produce_price_per_tonne'] .'</td>
                        <td>'. DC($tables['date_collected']) .'</td>
                      </tr>
                    ';
                  }
                  return $tr;
                }

                // Get date of registration
                function DC($d) {
                  $date = date("F j, Y, g:i a", strtotime($d));
                  return $date;
                }

                echo '
                  <div class="card">
                    <div class="table-responsive">
                      <table id="priceTable" class="table table-hover table-outline table-vcenter text-nowrap card-table">
                        <thead>
                          <tr>
                            <th class="w-1">S/N</th>
                            <th>Collectors\'s First Name</th>
                            <th>Collectors\'s Last Name</th>
                            <th>State</th>
                            <th>Market Name</th>
                            <th>Market Location (LGA)</th>
                            <th>Market Days</th>
                            <th>Produce Price per Bag (₦)</th>
                            <th>Produce Price per Tonne (₦)</th>
                            <th>Date Collected</th>
                          </tr>
                        </thead>
                        <tbody class="results">'.
                          display_table_data()
                        .'</tbody>
                      </table>
                    </div>
                  </div>
                  <nav aria-label="Page navigation">
                    <ul class="pagination">
                      <li class="page-item'. ($page_num===0?' disabled':null) .'">
                        <a class="page-link" href="./responses.php?name=register_farmer&id=c4ca4238a0b923820dcc509a6f75849b'. ($page_num<=1 ? '' : ('&pagenum='. ($page_num-1))) .'" aria-label="Previous">
                          <span aria-hidden="true">&laquo;</span>
                          <span class="sr-only">Previous</span>
                        </a>
                      </li>'.
                        create_pagination()
                      .'<li class="page-item'. ($page_num===($pages-1)||$pages===0?' disabled':null) .'">
                        <a class="page-link" href="./responses.php?name=register_farmer&id=c4ca4238a0b923820dcc509a6f75849b&pagenum='. ($page_num+1) .'" aria-label="Next">
                          <span aria-hidden="true">&raquo;</span>
                          <span class="sr-only">Next</span>
                        </a>
                      </li>
                    </ul>
                  </nav>
                ';
              }
            ?>
            <script>
              require(['jquery'], function ($) {
                $(function() {
                  // Search for names 
                  var input, filter, table, tr, td, i;
                  input = document.getElementById("marketSearch");
                  input.onkeyup = function() {
                    filter = this.value.toUpperCase();
                    table = document.getElementById("priceTable");
                    tr = table.getElementsByTagName("tr");

                    // Loop through all table rows, and hide those who don't match the search query
                    for (i = 0; i < tr.length; i++) {
                      td = tr[i].getElementsByTagName("td");
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
  </body>
</html>