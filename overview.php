<?php 
  include('functions.php');
  $user = $_SESSION['user'];
  $project_ids = explode(', ', $user['project_id']);
  $project_names = explode(', ', $user['project_name']);
  
  if(!$user){ 
    header("Location: ./login.php?nexturl=overview.php?$_SERVER[QUERY_STRING]"); 
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
    <title>Verde - Agricultural Extension and Analytics</title>
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
  <body class="">
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
                        <p>New farmer signed on - <strong>Musa Abdullahi</strong></p>
                        <div class="small text-muted">10 minutes ago</div>
                      </div>
                    </a>
                    <a href="#" class="dropdown-item d-flex">
                      <div>
                        <p>50 messages sent to farmers in <strong>Kano State</strong></p>
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
                      <span class="float-right"><span class="badge badge-primary">6</span></span>
                      <i class="dropdown-icon fe fe-mail"></i> Inbox
                    </a>
                    <!-- <a class="dropdown-item" href="#">
                      <i class="dropdown-icon fe fe-send"></i> Message
                    </a> -->
                    <div class="dropdown-divider"></div>
                    <!-- <a class="dropdown-item" href="#">
                      <i class="dropdown-icon fe fe-help-circle"></i> Need help?
                    </a> -->
                    <a class="dropdown-item" href="./overview.php?logout='1'">
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
                        <a href="./biodata.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-file-text"></i> Bio-data</a>
                        <a href="./demography.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-bar-chart-2"></i> Demographics</a>
                      </div>
                    <?php } elseif ($_GET['name'] === 'market_prices') { ?>
                      <div class="dropdown-menu dropdown-menu-arrow">
                        <a href="./overview.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item active"><i class="fe fe-box"></i> Overview</a>
                        <a href="./price-tables.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-file-text"></i> Price Tables</a>
                      </div>
                    <?php } ?>
                  </li>
                  <li class="nav-item" style="<?php if ($_SESSION['user']['user_type']!=='administrator') { ?>visibility: hidden;<?php } ?>">
                    <a href="./data.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="nav-link"><i class="fe fe-file-text"></i> Data</a>
                  </li>
                  <li class="nav-item" style="<?php if ($_SESSION['user']['user_type']!=='administrator') { ?>visibility: hidden;<?php } ?>">
                    <a href="./collaborate.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="nav-link"><i class="fe fe-users"></i> Collaborate</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="my-3 my-md-5">
        <div class="container">
            <div class="row row-cards row-deck">
              <div class="col-md-6 col-xl-6">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Responses</h3>
                    <div class="card-options">
                      <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                    </div>
                  </div>
                  <div class="card-body text-center">
                    <span class="display-3">
                      <strong>
                        <?php
                          if (isset($_GET['name'])&&isset($_GET['id'])) {
                            $user = $_SESSION['user'];
                            $project_ids = explode(', ', $user['project_id']);
                            $project_names = explode(', ', $user['project_name']);
                            $project_name = e($_GET['name']);
                            $project_id = e($_GET['id']);
    
                            if (in_array($project_id, $project_ids, true)&&in_array($project_name, $project_names, true)) {
                              $query = "SELECT no_of_responses FROM projects WHERE project_name='$project_name' LIMIT 1";
                              $results = mysqli_query($db, $query);
                              $responses = implode(",",mysqli_fetch_row($results));
                              echo $responses;
                            }
                          }
                        ?>
                      </strong>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-6">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Collaborators</h3>
                    <div class="card-options">
                      <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                    </div>
                  </div>
                  <div class="card-body text-center">
                    <span class="display-3">
                      <strong>
                        <?php
                          if (isset($_GET['name'])&&isset($_GET['id'])) {
                            $user = $_SESSION['user'];
                            $project_ids = explode(', ', $user['project_id']);
                            $project_names = explode(', ', $user['project_name']);
                            $project_name = e($_GET['name']);
                            $project_id = e($_GET['id']);
    
                            if (in_array($project_id, $project_ids, true)&&in_array($project_name, $project_names, true)) {
                              $query = "SELECT collaborators FROM projects WHERE project_name='$project_name' LIMIT 1";
                              $results = mysqli_query($db, $query);
                              $collaborators = explode(",",implode(",",mysqli_fetch_row($results)));
                              echo count($collaborators);
                            }
                          }
                        ?>
                      </strong>
                    </span>
                  </div>
                </div>
              </div>
              <!-- <div class="col-md-4 col-xl-4">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Mobile Devices</h3>
                    <div class="card-options">
                      <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                    </div>
                  </div>
                  <div class="card-body text-center">
                    <span class="display-3">
                      <strong></strong>
                    </span>
                  </div>
                </div>
              </div> -->
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Responses Over Time</h3>
                    <div class="card-options">
                      <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                        <button type="button" data-range="day" class="btn btn-secondary btn-response">Day</button>
                        <button type="button" data-range="week" class="btn btn-secondary btn-response">Week</button>
                        <button type="button" data-range="month" class="btn btn-secondary btn-response">Month</button>
                        <!-- <button type="button" data-range="quarter" class="btn btn-secondary btn-response">Quarter</button> -->
                      </div>
                      <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="dimmer">
                      <div class="loader"></div>
                      <div class="dimmer-content">
                        <canvas id="responseChart" style="height: 30rem;"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <script>
                require(['chart', 'jquery'], function(Chart, $){
                  $(function() {
                    function loadData(data) {
                      let urlParams = new URLSearchParams(window.location.search);
                      let name = urlParams.get('name');
                      let id = urlParams.get('id');
                      $.post('response_data.php', {
                        response: data,
                        projName: name,
                        projId: id
                      }, function (response, status) {
                        displayData(JSON.parse(response));
                      })
                    }
                    loadData("day");
                    $(".btn-response").click(function (e) {
                      let data = $(this).data("range");
                      loadData(data);
                    })

                    function displayData(responses) {
                      var resArr = responses[1];
                      var ctx = $('#responseChart'),
                        responseChart;
                      responseChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                          datasets: [{
                            data: resArr,
                            borderColor: tabler.colors["teal-dark"],
                            fill: false,
                            lineTension: 0.1
                          }],
                          labels: responses[0]
                        },
                        options: {
                          maintainAspectRatio: false,
                          responsive: true
                        }
                      });
                    }

                    displayData();
                  })
                })
              </script>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
              Copyright © 2018 <a href="../index.html" target="_blank" class="text-primary">Plurimus Technologies</a>. All rights reserved.
            </div>
          </div>
        </div>
      </footer>
    </div>
  </body>
</html>