<?php 
  include('report-config.php');
  $user = $_SESSION['user'];
  $project_ids = explode(', ', $user['project_id']);
  $project_names = explode(', ', $user['project_name']);
  
  if(!$user){ 
    header("Location: ./login.php?nexturl=reports.php?$_SERVER[QUERY_STRING]"); 
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
                      <div class="dropdown d-none d-md-flex">
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
                    <!-- <a class="dropdown-item" href="#">
                      <i class="dropdown-icon fe fe-help-circle"></i> Need help?
                    </a> -->
                    <a class="dropdown-item" href="./reports.php?logout='1'">
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
                <!-- <form class="input-icon my-3 my-lg-0">
                  <input type="search" class="form-control header-search" placeholder="Search&hellip;" tabindex="1">
                  <div class="input-icon-addon">
                    <i class="fe fe-search"></i>
                  </div>
                </form> -->
              </div>
              <div class="col-lg order-lg-first">
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                  <li class="nav-item dropdown" style="<?php if ($_SESSION['user']['user_type']!=='administrator') { ?>visibility: hidden;<?php } ?>">
                    <a href="javascript:void(0)" class="nav-link active" data-toggle="dropdown"><i class="fe fe-activity"></i> Data</a>
                    <?php if ($_GET['name'] === 'register_farmer') { ?>
                      <div class="dropdown-menu dropdown-menu-arrow">
                        <a href="./overview.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-box"></i> Overview</a>
                        <a href="./biodata.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-file-text"></i> Responses</a>
                        <a href="./reports.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item active"><i class="fe fe-share"></i> Export Data</a>
                      </div>
                    <?php } elseif ($_GET['name'] === 'market_prices') { ?>
                      <div class="dropdown-menu dropdown-menu-arrow">
                        <a href="./overview.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-box"></i> Overview</a>
                        <a href="./price-tables.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-file-text"></i> Responses</a>
                        <a href="./reports.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item active"><i class="fe fe-share"></i> Export Data</a>
                      </div>
                    <?php } ?>
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
            <div class="row">
              <div class="col-lg-3 col-md-4">
                <h3 class="page-title mb-5">Export Data</h3>
                <div>
                  <div class="mt-6 mb-6">
                    <a href="./reports.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="btn btn-secondary btn-block active">
                      <span class="icon mr-3"><i class="fe fe-download-cloud"></i></span>Generate PDF Report
                    </a>
                  </div>
                  <div class="mt-6 mb-6">
                    <a href="./export.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="btn btn-secondary btn-block">
                      <span class="icon mr-3"><i class="fe fe-share"></i></span>Export
                    </a>
                  </div>
                </div>
              </div>
              <div class="col-lg-9 col-md-8">
              <form action="./reports.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" method="POST" class="card" id="pdfReportForm" enctype="multipart/form-data">
                  <div class="card-header">
                    <h3 class="card-title">Generate PDF Data Tables</h3>
                  </div>
                  <div class="card-body">
                    <?php echo display_error(); ?>
                    <div class="row">
                      <div class="col-lg-6 col-md-12">
                        <h3>Table Header</h3>
                        <div class="form-group">
                          <label class="form-label">Background Color<span class="form-required">*</span></label>
                          <input type="color" class="form-control" name="header_bgcolor" autocomplete="off" value="#ffffff" required>
                        </div>
                        <div class="form-group">
                          <label class="form-label">Text Color<span class="form-required">*</span></label>
                          <input type="color" class="form-control" name="header_txtcolor" autocomplete="off" value="#222222" required>
                        </div>
                        <div class="form-group">
                          <label class="form-label">Border<span class="form-required">*</span></label>
                          <div class="custom-controls-stacked">
                            <label class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" name="header_border" value="zero" required checked>
                              <span class="custom-control-label">None</span>
                            </label>
                            <label class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" name="header_border" value="one" required>
                              <span class="custom-control-label">All</span>
                            </label>
                            <label class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" name="header_border" value="LR" required>
                              <span class="custom-control-label">Left-Right</span>
                            </label>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="form-label">Border Color<span class="form-required">*</span></label>
                          <input type="color" class="form-control" id="headerBdColor" name="header_bdcolor" autocomplete="off" value="#222222" required>
                        </div>
                        <div class="form-group">
                          <label class="form-label">Border Width<span class="form-required">*</span></label>
                          <div class="row">
                            <div class="col">
                              <input type="range" id="widthRange" class="form-control custom-range" step="1" min="1" max="5" name="header_bdwidth">
                            </div>
                            <div class="col">
                              <input type="number" id="widthBox" class="form-control" value="2" required>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-12">
                        <h3>Table Body</h3>
                        <div class="form-group">
                          <label class="form-label">Background Color<span class="form-required">*</span></label>
                          <input type="color" class="form-control" name="body_bgcolor" autocomplete="off" value="#ffffff" required>
                        </div>
                        <div class="form-group">
                          <label class="form-label">Text Color<span class="form-required">*</span></label>
                          <input type="color" class="form-control" name="body_txtcolor" autocomplete="off" value="#222222" required>
                        </div>
                        <div class="form-group">
                          <label class="form-label">Border<span class="form-required">*</span></label>
                          <div class="custom-controls-stacked">
                            <label class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" name="body_border" value="zero" required checked>
                              <span class="custom-control-label">None</span>
                            </label>
                            <label class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" name="body_border" value="one" required>
                              <span class="custom-control-label">All</span>
                            </label>
                            <label class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" name="body_border" value="LR" required>
                              <span class="custom-control-label">Left-Right</span>
                            </label>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="form-label">
                            Border Color
                            <span class="form-required">*</span>
                            <span class="col-auto align-self-center">
                              <span class="form-help" data-toggle="popover" data-placement="top" data-content="<p class='m-0'>Auto-generated data. Not available for editing</p>
                              ">?</span>
                            </span>
                          </label>
                          <input type="color" class="form-control" id="bodyBdColor" name="body_bdcolor" autocomplete="off" required disabled>
                        </div>
                        <div class="form-group">
                          <label class="form-label">
                            Border Width
                            <span class="form-required">*</span>
                            <span class="col-auto align-self-center">
                              <span class="form-help" data-toggle="popover" data-placement="top" data-content="<p class='m-0'>Auto-generated data. Not available for editing</p>
                              ">?</span>
                            </span>
                          </label>
                          <div class="row">
                            <div class="col">
                              <input type="range" class="form-control custom-range" id="bodyBdWidth" step="1" min="1" max="5" name="body_bdwidth" disabled>
                            </div>
                          </div>
                        </div>
                        <script>
                          var headerBdColor = document.getElementById("headerBdColor");
                          var bodyBdColor = document.getElementById("bodyBdColor");
                          var bodyBdWidth = document.getElementById("bodyBdWidth");
                          var slider = document.getElementById("widthRange");
                          var output = document.getElementById("widthBox");
                          
                          handleChange(slider,output,bodyBdWidth);
                          handleChange(output,slider,bodyBdWidth);
                          handleChange(headerBdColor,bodyBdColor,null);

                          function handleChange(x,y,z) {
                            z==null
                              ? x.value = y.value
                              : x.value = y.value = z.value;
                            x.onchange = function() {
                              y.value = this.value;
                              z.value = this.value;
                            }
                          }
                        </script>
                        <div class="row">
                          <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                              <label class="form-label">
                                Stripped Rows
                                <span class="form-required">*</span>
                                <span class="col-auto align-self-center">
                                  <span class="form-help" data-toggle="popover" data-placement="top" data-content="<p class='m-0'>Select to add zebra-striping to table rows.</p>
                                  ">?</span>
                                </span>
                              </label>
                              <div class="custom-controls-stacked">
                                <label class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" class="custom-control-input" name="stripped_body_rows" value="yes" required checked>
                                  <span class="custom-control-label">Yes</span>
                                </label>
                                <label class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" class="custom-control-input" name="stripped_body_rows" value="no" required>
                                  <span class="custom-control-label">No</span>
                                </label>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                              <label class="form-label">Font Name<span class="form-required">*</span></label>
                              <select name="body_fontname" class="form-control custom-select" required>
                                <option value="">Select</option>
                                <option value="Courier" style="font-family: Courier;">Courier</option>
                                <option value="Arial" style="font-family: Arial">Arial</option>
                                <option value="Times" style="font-family: Times">Times</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                              <label class="form-label">
                                Font Size
                                <span class="form-required">*</span>
                                <span class="col-auto align-self-center">
                                  <span class="form-help" data-toggle="popover" data-placement="top" data-content="<p class='m-0'>Available values range from 10 to 20.</p>
                                  ">?</span>
                                </span>
                              </label>
                              <input type="number" name="body_fontsize" class="form-control" min="10" max="20" step="2" value="10" required>
                            </div>
                          </div>
                          <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                              <label class="form-label">Font Style<span class="form-required">*</span></label>
                              <select name="body_fontstyle" class="form-control custom-select" id="lga-select" required>
                                <option value="">Select</option>
                                <option value="B" style="font-weight: bold;">Bold</option>
                                <option value="I" style="font-style: italic;">Italics</option>
                                <option value="N">Normal</option>
                                <option value="U" style="text-decoration: underline;">Underline</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="form-label">Select Table Column Headings<span class="form-required">*</span></div>
                          <div>
                            <?php
                              if (isset($_GET['name'])&&isset($_GET['id'])) {
                                $user = $_SESSION['user'];
                                $project_ids = explode(', ', $user['project_id']);
                                $project_names = explode(', ', $user['project_name']);
                                $project_name = e($_GET['name']);
                                $project_id = e($_GET['id']);
        
                                if (in_array($project_id, $project_ids, true)&&in_array($project_name, $project_names, true)) {
                                  $query = "SELECT `COLUMN_NAME` 
                                            FROM `INFORMATION_SCHEMA`.`COLUMNS` 
                                            WHERE `TABLE_SCHEMA`='agridata' 
                                            AND `TABLE_NAME`='$project_name'";
                                  $results = mysqli_query($db, $query);

                                  while ($headers = mysqli_fetch_row($results)) {
                                    foreach ($headers as $key => $header) {
                                      if ($header!=="id"&&$header!=="farm_pic"&&$header!=="farmer_pic"
                                          &&$header!=="project_id"&&$header!=="device"&&$header!=="seen_as_notification"&&$header!=="deleted") {
                                        echo '
                                          <label class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" name="pdf_heading[]" value='. $header .'>
                                            <span class="custom-control-label">'. split_string($header) .'</span>
                                          </label>
                                        ';
                                      }
                                    }
                                  }
                                }
                              }
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary" id="printBtn" name="generate_PDF">Generate PDF</button>
                  </div>
                </form>
              </div>
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

          var form = $('form.card');
          form.submit(function (e) {
            $("button[type=submit]").addClass("btn-loading");
          })
        })
      })
    </script>
  </body>
</html>