<?php 
  include('functions.php');
  $user = $_SESSION['user'];
  // var_dump($_SERVER['QUERY_STRING']);
  $project_ids = explode(', ', $user['project_id']);
  $project_names = explode(', ', $user['project_name']);
  
  if(!$user){ 
    header("Location: ./login.php?nexturl=responses.php?name=$_GET[name]&id=$_GET[id]"); 
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
  
  if (isset($_GET['uid'])) {
    $user_id = substr(e($_GET['uid']), 0, -13);
    $query = "SELECT * FROM register_farmer WHERE id=$user_id LIMIT 1";
    $results = mysqli_query($db,$query);
    
    if ($results===false) {
      header("Location: ./responses.php?name=$_GET[name]&id=$_GET[id]");
    }
  } else {
    header("Location: ./responses.php?name=$_GET[name]&id=$_GET[id]");
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
                      <span class="float-right"><span class="badge badge-primary">6</span></span>
                      <i class="dropdown-icon fe fe-mail"></i> Inbox
                    </a> -->
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="farmer-profile.php?logout='1'">
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
            <div class="page-header">
              <div class="page-header">
                <h1 class="page-title">Farmer's Profile</h1>
              </div>
            </div>
            <div class="row row-cards">
              <?php
                if (isset($_GET['name'])&&isset($_GET['id'])&&isset($_GET['uid'])) {
                  $user = $_SESSION['user'];
                  $project_ids = explode(', ', $user['project_id']);
                  $project_names = explode(', ', $user['project_name']);
                  $project_name = e($_GET['name']);
                  $project_id = e($_GET['id']);
                  $user_id = substr(e($_GET['uid']), 0, -13);

                  $query = "SELECT * FROM register_farmer WHERE id='$user_id'";
                  $results = mysqli_query($db, $query);
                  $farmer = mysqli_fetch_assoc($results);
                  // var_dump($farmer);

                  // Get date of registration
                  function DOR($d) {
                    $date = date("F j, Y, g:i a", strtotime($d));
                    return $date;
                  }

                  // Get date of birth
                  function DOB($dob) {
                    //explode the date to get month, day and year
                    $birthDate = explode("-", $dob);
                    //get age from date or birthdate
                    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
                      ? ((date("Y") - $birthDate[0]) - 1)
                      : (date("Y") - $birthDate[0]));
                    return $age;
                  }

                  // Display secondary phone number
                  function sph($num) {
                    if ($num !== "") {
                      return ', '. $num;
                    } else {
                      return '';
                    }
                  }

                  $farm_picture = null;
                  if ($farmer['farm_pic']!=="") {
                    $farm_picture = explode(",", $farmer['farm_pic']);
                  }
                  
                  function display_indicators() {
                    global $farm_picture;

                    $indicator = '';
                    for ($i=0; $i<count($farm_picture); $i++) { 
                      $indicator .= '<li data-target="#carousel-indicators" data-slide-to="'. ($i) .'" class="'. ($i===1?'active':null) .'"></li>';
                    }
                    return $indicator;
                  }

                  function display_images() {
                    global $farm_picture;

                    $picture = '';
                    for ($i=0; $i<count($farm_picture); $i++) {
                      // var_dump($i);
                      $picture .= '
                        <div class="carousel-item '. ($i===0?'active':null) .'">
                          <img class="d-block w-100 img-fluid" alt="farm-picture-'. ($i+1) .'" src="./assets/images/farmers_pictures/'. $farm_picture[$i] .'" data-holder-rendered="true" style="max-height: 400px;">
                        </div>
                      ';
                    }
                    return $picture;
                  }

                  // Farm size - acres to hectares converter
                  function ath($a_size) {
                    preg_match_all('/\d+/', $a_size, $matches);
                    $land = (int)implode('', $matches[0]);
                    $h_size = round(0.4 * $land);
                    return $h_size;
                  }

                  echo '
                    <div class="col-lg-4 col-sm-12">
                      <div class="card">
                        <div class="card-body username_pic">
                          <div class="mb-4 text-center">
                            <img src="./assets/images/farmers_pictures/'. $farmer['farmer_pic'] .'" alt="'. $farmer['firstname'] .' '. $farmer['lastname'] .'" class="img-fluid">
                          </div>
                          <h4 class="card-title text-center">'. $farmer['firstname'] .' '. $farmer['lastname'] .'</h4>
                          <div class="card-subtitle text-muted text-center">
                            Response Collected on: '. DOR($farmer['date_of_data_collection']) .'
                          </div>
                          <div class="mt-5 d-flex align-items-center">
                            <div class="ml-auto">
                              <a href="javascript:void(0)" class="btn btn-primary"><i class="fe fe-download-cloud"></i> Download Data</a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-header">
                          <h3 class="card-title">Flag Bad Response</h3>
                        </div>
                        <div class="card-body">
                          <form id="bad-response-form">
                            <div class="form-group">
                              <label class="form-label">Select bad field(s) (Comma separated list)<span class="form-required">*</span></label>
                              <input type="text" class="form-control" name="flagged_fields" id="input-tags" autocomplete="off" placeholder="e.g. Town/Village" required>
                            </div>
                            <div class="form-group">
                              <label class="form-label">Provide suggestions (if any)</label>
                              <textarea class="form-control" rows="5" name="suggestions"></textarea>
                            </div>
                            <div class="form-footer">
                              <button type="submit" class="btn btn-primary btn-block">Send to agent</button>
                            </div>
                          </form>
                          <script>
                            require(["jquery", "selectize"], function ($, selectize) {
                              $("#input-tags").selectize({
                                plugins: ["remove_button"],
                                delimiter: ",",
                                persist: false,
                                create: function (input) {
                                  return {
                                    value: input,
                                    text: input
                                  }
                                }
                              });
                            });
                          </script>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-8 col-sm-12">
                      <div class="card">
                        <div class="card-body">
                          <div id="carousel-indicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">'.
                              display_indicators()
                            .'</ol>
                            <div class="carousel-inner mb-5">'.
                              display_images()
                            .'</div>
                          </div>
                          <div class="profile-details-full">
                            <div class="row">
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">Phone Number(s)</label>
                                  <div class="form-control-plaintext">'.
                                    $farmer['phone_primary'].sph($farmer['phone_secondary'])
                                  .'</div>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">Email Address (if available)</label>
                                  <div class="form-control-plaintext">'. ($farmer['email']?$farmer['email']:'N/A') .'</div>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">Gender</label>
                                  <div class="form-control-plaintext">'. ucwords($farmer['gender']) .'</div>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">Annual Farm Income (₦)</label>
                                  <div class="form-control-plaintext">₦'. number_format($farmer['income']) .'</div>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">Age</label>
                                  <div class="form-control-plaintext">'. DOB($farmer['date_of_birth']) .'</div>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">Family Size</label>
                                  <div class="form-control-plaintext">'. $farmer['family_size'] .'</div>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">Highest Level of Education</label>
                                  <div class="form-control-plaintext">'. $farmer['education'] .'</div>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">Land Size (ha)</label>
                                  <div class="form-control-plaintext">'. ath($farmer['land_area']) .'</div>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">State</label>
                                  <div class="form-control-plaintext">'. $farmer['state'] .'</div>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">Local Government Area</label>
                                  <div class="form-control-plaintext">'. $farmer['lga'] .'</div>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">Town/Village</label>
                                  <div class="form-control-plaintext">'. $farmer['town'] .'</div>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">Planted Crops</label>
                                  <div class="form-control-plaintext">'. $farmer['crops'] .'</div>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">Longitude</label>
                                  <div class="form-control-plaintext">'. $farmer['longitude'] .'</div>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">Latitude</label>
                                  <div class="form-control-plaintext">'. $farmer['latitude'] .'</div>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">Source of Farm Labour</label>
                                  <div class="form-control-plaintext">'. split_string($farmer['farm_labour']) .'</div>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label class="form-label">Annual Produce Volume (Tonnes)</label>
                                  <div class="form-control-plaintext">'. $farmer['produce_volume'] .'</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  ';
                }
              ?>
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

          let a = $('a.nav-link.icon'),
              b = 5,
              child;
          a.click(function (e) {
            child = $(this).next();
            e.preventDefault();
            if (window.innerWidth<=425) {
              child.width((window.innerWidth-2));
              let position = child.position();
              position.left = b
              console.log();
            }
          });

          $('#bad-response-form').submit(function (e) { 
            e.preventDefault();
            let data = $(this).serializeArray();
            loadNotifications(JSON.stringify(data));
            // console.log(JSON.stringify(data));
          });
        })
      })
    </script>
  </body>
</html>