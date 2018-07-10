<?php 
  include('functions.php');
  if(!$_SESSION['user']){ 
      header("Location: ./login"); 
      exit; 
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="en" />
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#817729">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <link rel="icon" href="assets/images/favico.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favico.ico" />
    <title>Verde - Agricultural Extension and Analytics</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext">
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
    <script src="./assets/js/dashboard.js"></script>
    <!-- c3.js Charts Plugin -->
    <link href="./assets/plugins/charts-c3/plugin.css" rel="stylesheet" />
    <script src="./assets/plugins/charts-c3/plugin.js"></script>
    <!-- Google Maps Plugin -->
    <link href="./assets/plugins/maps-google/plugin.css" rel="stylesheet" />
    <script src="./assets/plugins/maps-google/plugin.js"></script>
    <!-- Input Mask Plugin -->
    <script src="./assets/plugins/input-mask/plugin.js"></script>
  </head>
  <body class="">
    <div class="page">
      <div class="page-main">
        <div class="header py-4">
          <div class="container">
            <div class="d-flex">
              <a class="header-brand" href="./dashboard">
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
                          $words = explode(" ", '$firstname $lastname');
                          $initials = null;
                          foreach ($words as  $w) {
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
                            echo ucfirst($_SESSION['user']['username']);
                          }
                        ?>  
                      </span>
                      <small class="text-muted d-block mt-1">
                        <?php echo ucfirst($_SESSION['user']['user_type']); ?>
                      </small>
                    </span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item" href="./profile">
                      <i class="dropdown-icon fe fe-user"></i> Profile
                    </a>
                    <a class="dropdown-item" href="#">
                      <i class="dropdown-icon fe fe-settings"></i> Settings
                    </a>
                    <a class="dropdown-item" href="#">
                      <span class="float-right"><span class="badge badge-primary">6</span></span>
                      <i class="dropdown-icon fe fe-mail"></i> Inbox
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="../login.html">
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
                  <li class="nav-item">
                    <a href="./register-farmer.php" class="nav-link"><i class="fe fe-user-plus"></i> Register Farmer</a>
                  </li>
                  <li class="nav-item dropdown">
                    <a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fe fe-trending-up"></i> Analytics</a>
                    <div class="dropdown-menu dropdown-menu-arrow">
                      <a href="./farmer-overview.php" class="dropdown-item"><i class="fe fe-box"></i> Overview</a>
                      <a href="./farmer-biodata.php" class="dropdown-item active"><i class="fe fe-file-text"></i> Bio-data</a>
                      <a href="./farmer-demography.php" class="dropdown-item"><i class="fe fe-bar-chart-2"></i> Demographics</a>
                      <a href="./farmer-cropinfo.php" class="dropdown-item"><i class="fe fe-activity"></i> Crop Information</a>
                    </div>
                  </li>
                  <!-- <li class="nav-item dropdown">
                    <a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fe fe-send"></i> Push</a>
                    <div class="dropdown-menu dropdown-menu-arrow">
                      <a href="./sms" class="dropdown-item"><i class="fe fe-message-square"></i> SMS</a>
                      <a href="./voice" class="dropdown-item"><i class="fe fe-phone-outgoing"></i> Voice Calls</a>
                    </div>
                  </li> -->
                  <li class="nav-item dropdown">
                    <a href="./reports.php" class="nav-link"><i class="fe fe-file-text"></i> Get Reports</a>
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
              <div class="col-lg-4 col-sm-12">
                <div class="row">
                  <div class="col-md-6 col-lg-12">
                    <div class="card">
                      <div class="dimmer active">
                        <div class="loader"></div>
                        <div class="dimmer-content">
                          <div class="card-body username_pic"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-8 col-sm-12">
                <div class="card">
                  <div class="card-body">
                    <div id="carousel-indicators" class="carousel slide" data-ride="carousel">
                      <ol class="carousel-indicators"></ol>
                      <div class="carousel-inner mb-5"></div>
                    </div>
                    <div class="profile-details-full"></div>
                  </div>
                </div>
              </div>
              <script>
                require(["jquery"], function ($) {
                  $(function () {
                     // Create the XHR object.
                     function createCORSRequest(method, url) {
                      var xhr = new XMLHttpRequest();
                      if ("withCredentials" in xhr) {
                        // XHR for Chrome/Firefox/Opera/Safari.
                        xhr.open(method, url, true);
                      } else if (typeof XDomainRequest != "undefined") {
                        // XDomainRequest for IE.
                        xhr = new XDomainRequest();
                        xhr.open(method, url);
                      } else {
                        // CORS not supported.
                        xhr = null;
                      }
                      return xhr;
                    }
                    // ID of the Google Spreadsheet
                    var spreadsheetID = "1aZ8aYMpnsVpB6E0iOS5v_eX6sCloxLYlIvyJJoscurA";

                    var url =
                      `https://spreadsheets.google.com/feeds/list/${spreadsheetID}/1/public/values?alt=json`;
                    
                    // Get picture
                    function getPic(url) {
                      var picId = (url.match(/[-\w]{25,}/))[0];
                      var picURL = `https://drive.google.com/thumbnail?authuser=0&sz=w320&id=${picId}`;
                      return picURL;
                    }

                    // Get date of birth
                    function DOB(dob) {
                      var today = new Date();
                      var currentYear = today.getFullYear();
                      var birthDate = new Date(dob);
                      var birthYear = birthDate.getFullYear();
                      var age = currentYear - birthYear;
                      return age;
                    }

                    // Get date of registration
                    function DOR(d) {
                      var fullDate = new Date(d);
                      var regMonth = fullDate.toString().split(' ')[1];
                      var regDay = fullDate.getDay();
                      var regYear = fullDate.getFullYear();
                      return `${regMonth} ${regDay}, ${regYear}`;
                      // console.log(regDay);
                    }

                    // Farm size - acres to hectares converter
                    function ath(a_size) {
                      var h_size = parseFloat(Math.round(0.4 * a_size));
                      return h_size;
                    }

                    // Display secondary phone number
                    function sph(num) {
                      if (num) {
                        return `, 0${num}`;
                      }
                    }

                    // Make CORS Request
                    function makeCorsRequest() {
                      var xhr = createCORSRequest('GET', url);
                      if (!xhr) {
                        alert('CORS not supported');
                        return;
                      }
                      
                      var userId = window.location.search.substring(1);
                      // console.log(userId);

                      // Response handlers.
                      xhr.onreadystatechange = function () {
                        if (this.readyState === 4) {
                          if (this.status === 200) {
                            var data = JSON.parse(this.responseText);
                            var entry = data.feed.entry;
                            // console.log(entry);
                            var userDetails = entry.find( results => results.id.$t === `https://spreadsheets.google.com/feeds/list/1aZ8aYMpnsVpB6E0iOS5v_eX6sCloxLYlIvyJJoscurA/1/public/values/${userId}`);
                            
                            var farmPicArr = userDetails.gsx$farmpicturesmaximumof5.$t.split(',');
                            // console.log(farmPicArr);
                            farmPicArr.forEach((e, i) => {
                              if (i === 0) {
                                $(".carousel-indicators").prepend(`
                                  <li data-target="#carousel-indicators" data-slide-to="${i}" class="active"></li>
                                `)
                                $(".carousel-inner").prepend(`
                                  <div class="carousel-item active">
                                    <img class="d-block w-100 img-fluid" alt="farm-picture-${i}" src="${getPic(e)}" data-holder-rendered="true" style="max-height: 400px;">
                                  </div>
                                `)
                              } else {
                                $(".carousel-indicators").prepend(`
                                  <li data-target="#carousel-indicators" data-slide-to="${i}"></li>
                                `)
                                $(".carousel-inner").prepend(`
                                  <div class="carousel-item">
                                    <img class="d-block w-100 img-fluid" alt="farm-picture-${i}" src="${getPic(e)}" data-holder-rendered="true" style="max-height: 400px;">
                                  </div>
                                `)
                              }
                            });
                            
                            $(".username_pic").prepend(`
                              <div class="mb-4 text-center">
                                <img src="${getPic(userDetails.gsx$pictureoffarmer.$t)}" alt="${userDetails.gsx$firstname.$t} ${userDetails.gsx$lastname.$t}" class="img-fluid">
                              </div>
                              <h4 class="card-title text-center">${userDetails.gsx$firstname.$t} ${userDetails.gsx$lastname.$t}</h4>
                              <div class="card-subtitle text-muted text-center">
                                Registered on: ${DOR(userDetails.title.$t)}
                              </div>
                              <div class="mt-5 d-flex align-items-center">
                                <div class="ml-auto">
                                  <a href="javascript:void(0)" class="btn btn-primary disabled"><i class="fe fe-message-square"></i> Send SMS</a>
                                </div>
                              </div>
                            `);

                            $(".profile-details-full").prepend(`
                              <div class="row">
                                <div class="col-sm-6 col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Phone Number(s)</label>
                                    <div class="form-control-plaintext">0${userDetails.gsx$primaryphonenumber.$t}${sph(userDetails.gsx$secondaryphonenumberifavailable.$t)}</div>
                                  </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Email Address (if available)</label>
                                    <div class="form-control-plaintext">${userDetails.gsx$emailaddress.$t}</div>
                                  </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Gender</label>
                                    <div class="form-control-plaintext">${userDetails.gsx$gender.$t}</div>
                                  </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Annual Farm Income (₦)</label>
                                    <div class="form-control-plaintext">${(userDetails.gsx$annualincomerange.$t)*100000}</div>
                                  </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Age</label>
                                    <div class="form-control-plaintext">${DOB(userDetails.gsx$dateofbirth.$t)}</div>
                                  </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Family Size</label>
                                    <div class="form-control-plaintext">${userDetails.gsx$familysize.$t}</div>
                                  </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Highest Level of Education</label>
                                    <div class="form-control-plaintext">${userDetails.gsx$highestlevelofeducation.$t}</div>
                                  </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Land Size (ha)</label>
                                    <div class="form-control-plaintext">${ath(userDetails.gsx$totallandareaacres.$t)}</div>
                                  </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">State</label>
                                    <div class="form-control-plaintext">${userDetails.gsx$state.$t}</div>
                                  </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Local Government Area</label>
                                    <div class="form-control-plaintext">${userDetails.gsx$localgovernmentarealga.$t}</div>
                                  </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Town/Village</label>
                                    <div class="form-control-plaintext">${userDetails.gsx$townorvillage.$t}</div>
                                  </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Planted Crops</label>
                                    <div class="form-control-plaintext">${userDetails.gsx$plantedcrops.$t}</div>
                                  </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Source of Farm Labour</label>
                                    <div class="form-control-plaintext">${userDetails.gsx$sourceoffarmlabour.$t}</div>
                                  </div>
                                </div>
                              </div>
                            `);

                            $(".dimmer").removeClass("active");
                              
                          } else {
                            console.log("Unable to retrieve data");
                          }
                        }
                      };
                      xhr.send();
                    }
                    makeCorsRequest();

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