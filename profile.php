<?php 
  include('profile-config.php');
  if(!$_SESSION['user']){ 
    header("Location: ./login.php?nexturl=profile.php");
    exit; 
  }
  // var_dump($_SESSION['user']['firstname']);
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
    <script src="./assets/js/dashboard.js"></script>
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
                    <a class="dropdown-item active" href="./profile.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>">
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
                    <a class="dropdown-item" href="./profile.php?logout='1'">
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
                  <li class="nav-item dropdown" style="<?php if ($_SESSION['user']['user_type']!=='administrator'||!isset($_GET['name'])) { ?>visibility: hidden;<?php } ?>">
                    <a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fe fe-trending-up"></i> Analytics</a>
                    <?php if (isset($_GET['name'])&&$_GET['name']==='register_farmer') { ?>
                      <div class="dropdown-menu dropdown-menu-arrow">
                        <a href="./overview.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-box"></i> Overview</a>
                        <a href="./biodata.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-file-text"></i> Bio-data</a>
                        <a href="./demography.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-bar-chart-2"></i> Demographics</a>
                      </div>
                    <?php } elseif (isset($_GET['name'])&&$_GET['name']==='market_prices') { ?>
                      <div class="dropdown-menu dropdown-menu-arrow">
                        <a href="./overview.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-box"></i> Overview</a>
                        <a href="./price-tables.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="dropdown-item"><i class="fe fe-file-text"></i> Price Tables</a>
                      </div>
                    <?php } ?>
                  </li>
                  <li class="nav-item" style="<?php if ($_SESSION['user']['user_type']!=='administrator'||!isset($_GET['name'])) { ?>visibility: hidden;<?php } ?>">
                    <a href="./data.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="nav-link"><i class="fe fe-file-text"></i> Data</a>
                  </li>
                  <li class="nav-item" style="<?php if ($_SESSION['user']['user_type']!=='administrator'||!isset($_GET['name'])) { ?>visibility: hidden;<?php } ?>">
                    <a href="./collaborate.php<?php echo isset($_GET['id']) ? '?name='.e($_GET['name']).'&id='.e($_GET['id']) : null ?>" class="nav-link"><i class="fe fe-users"></i> Collaborate</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="my-3 my-md-5">
          <div class="container">
            <?php echo alert(); ?>
            <div class="row">
              <div class="col-lg-4">
                <div class="card card-profile">
                  <div class="card-header">
                  </div>
                  <div class="card-body text-center">
                    <form class="mb-4 text-center" id="profile-form" style="position: relative" enctype="multipart/form-data">
                      <div class="dimmer">
                        <div class="loader"></div>
                        <div class="dimmer-content">
                          <img class="card-profile-img" src="./assets/images/user.png">
                          <div id="picPreview" class="profile-pic-preview"></div>
                        </div>
                      </div>
                      <label for="profile-pic-input" id="profilePic" class="m-0">
                        <img src="./assets/images/edit.png" alt="" class="img-fluid edit" style="width: 1rem;">
                      </label>
                      <input type="file" name="profile_pic" id="profile-pic-input" onchange="getPicture(this.files);" accept="image/*" required>
                      <button type="submit" class="d-none pic-upload"></button>
                    </form>
                    <script>
                      function getPicture(files) {
                        var edit = document.querySelector(".edit"),
                          dimmer = document.querySelector(".dimmer"),
                         preview = document.querySelector("#picPreview"),
                        imgInput = document.querySelector("#profile-pic-input");
                        var file = files[0];
                        
                        // if (!file.type.startsWith('image/')){ continue }
                        var img = document.createElement("img"),
                            div = document.createElement("div");
                          
                        function createImage() {      
                          img.classList.add("profile-pic");
                          div.classList.add("profile-pic-wrapper");
                          img.file = file;
                          div.appendChild(img);
                          preview.appendChild(div);
                          
                          var reader = new FileReader();
                          reader.onload = (function(myImg) {
                            // uploadPic(img);
                            dimmer.classList.add("active");
                            return function(e) {
                              myImg.src = e.target.result;
                              uploadPic(myImg);
                              dimmer.classList.remove("active");
                            };
                          })(img);
                          reader.readAsDataURL(file);
                        }
                        
                        // console.log($("#profile-pic-input").val());
                        var that = preview.childNodes[0];
                        // console.log(preview.childNodes);
                        if (preview.childNodes.length === 0) {
                          createImage();
                        } else {
                          preview.removeChild(that);
                          createImage();
                        }

                        function uploadPic(myImg) {
                          require(['jquery'], function($) {
                            console.log("done");
                            var picture = $("#profile-pic-input").val();
                            $.ajax({
                              type: "POST",
                              url: "./save_profile_pic.php",
                              data: picture,
                              dataType: "json",
                              processData: false,  // tell jQuery not to process the data
                              contentType: false,  // tell jQuery not to set contentType
                  
                              success: function (data) {
                                console.log(data['error']);
                                if(data.error == 1) {
                                // $('.alert-danger').removeClass('hide').addClass('show').html(data['msg']);
                                } else {
                                // $('.alert-success').removeClass('hide').addClass('show').html('Uploaded');
                                console.log(data);
                                }
                              },
                              error: function (data) {
                                console.log(data);
                                // $('.alert-danger').removeClass('hide').addClass('show').html(data);
                              },
                          });
                          })
                        }
                      }
                    </script>
                    <h3 class="mb-3">
                      <?php
                        if ($_SESSION['user']['firstname']) {
                            echo $_SESSION['user']['firstname'].' '.$_SESSION['user']['lastname'];
                        } else {
                            echo "User";
                        }
                      ?>
                    </h3>
                    <p class="mb-4">
                      <small class="d-block">
                        @<?php
                            echo $_SESSION['user']['username'];
                        ?>
                      </small>
                      <span>
                        <?php
                          echo ucwords($_SESSION['user']['user_type']);
                        ?>
                      </span>
                    </p>
                    <a href="mailto:<?php echo $_SESSION['user']['email'];?>" class="btn-sm btn-outline-primary btn">
                        <?php
                            echo $_SESSION['user']['email'];
                        ?>
                    </a>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">My Profile</h3>
                  </div>
                  <div class="card-body">
                     <div class="">
                      <h5>About Me</h5>
                      <p>
                        <?php
                            if ($_SESSION['user']['bio']!=="") {
                                echo $_SESSION['user']['bio'];
                            } else {
                                echo "N/A";
                            }
                        ?>
                      </p>
                    </div>
                    <div class="">
                      <h5>Phone number</h5>
                      <p>
                        <?php
                          if ($_SESSION['user']['phone']) {
                              echo $_SESSION['user']['phone'];
                          } else {
                              echo "N/A";
                          }
                        ?>
                      </p>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <h5>Date of Birth</h5>
                        <p>
                            <?php
                                if ($_SESSION['user']['date_of_birth']) {
                                    echo $_SESSION['user']['date_of_birth'];
                                } else {
                                    echo "N/A";
                                }
                            ?>
                        </p>
                      </div>
                      <div class="col-md-6 col-sm-12">
                        <h5>Gender</h5>
                        <p>
                            <?php
                                if ($_SESSION['user']['gender']) {
                                    echo $_SESSION['user']['gender'];
                                } else {
                                    echo "N/A";
                                }
                            ?>
                        </p>
                      </div>
                    </div>
                    <div class="">
                      <h5>Highest Educational Level</h5>
                      <p>
                        <?php
                            if ($_SESSION['user']['education']) {
                                echo $_SESSION['user']['education'];
                            } else {
                                echo "N/A";
                            }
                        ?>
                      </p>
                    </div>
                    <div class="">
                      <h5>Degree - If available</h5>
                      <p>
                        <?php
                            if ($_SESSION['user']['degree']) {
                                echo $_SESSION['user']['degree'];
                            } else {
                                echo "N/A";
                            }
                        ?>
                      </p>
                    </div>
                    <div class="">
                      <h5>Address</h5>
                      <p>
                        <?php
                            if ($_SESSION['user']['address']) {
                                echo $_SESSION['user']['address'];
                            } else {
                                echo "N/A";
                            }
                        ?>
                      </p>
                    </div>
                    <div class="">
                      <h5>State of Residence</h5>
                      <p>
                        <?php
                            if ($_SESSION['user']['state']) {
                                echo $_SESSION['user']['state'];
                            } else {
                                echo "N/A";
                            }
                        ?>
                      </p>
                    </div>
                    <div class="">
                      <h5>Local Government Area (LGA)</h5>
                      <p>
                        <?php
                            if ($_SESSION['user']['lga']) {
                                echo $_SESSION['user']['lga'];
                            } else {
                                echo "N/A";
                            }
                        ?>
                      </p>
                    </div>
                    <div class="">
                      <h5>Town/Village</h5>
                      <p>
                        <?php
                            if ($_SESSION['user']['town']) {
                                echo $_SESSION['user']['town'];
                            } else {
                                echo "N/A";
                            }
                        ?>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-8">
                <form class="card" method="POST" action="./profile.php">
                  <div class="card-body">
                    <h3 class="card-title">Edit Profile</h3>
                    <div class="row">
                      <div class="col-sm-5 col-md-5">
                        <div class="form-group">
                          <label class="form-label">First Name<span class="form-required">*</span></label>
                          <input type="text" class="form-control" placeholder="First Name" name="firstname" required>
                        </div>
                      </div>
                      <div class="col-sm-5 col-md-5">
                        <div class="form-group">
                          <label class="form-label">Last Name<span class="form-required">*</span></label>
                          <input type="text" class="form-control" placeholder="Last Name" name="lastname" required>
                        </div>
                      </div>
                      <div class="col-sm-2 col-md-2">
                        <div class="form-group">
                          <label class="form-label">Gender<span class="form-required">*</span></label>
                          <select name="gender" class="form-control custom-select" required>
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-12">
                        <div class="form-group">
                          <label class="form-label">Date of Birth<span class="form-required">*</span></label>
                          <div class="row gutters-xs">
                            <div class="col-5">
                              <select name="dob_month" class="form-control custom-select" required>
                                <option value="">Month</option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                              </select>
                            </div>
                            <div class="col-3">
                              <select name="dob_day" class="form-control custom-select" required>
                                <option value="">Day</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                              </select>
                            </div>
                            <div class="col-4">
                              <select name="dob_year" class="form-control custom-select" required>
                                <option value="">Year</option>
                                <option value="2000">2000</option>
                                <option value="1999">1999</option>
                                <option value="1998">1998</option>
                                <option value="1997">1997</option>
                                <option value="1996">1996</option>
                                <option value="1995">1995</option>
                                <option value="1994">1994</option>
                                <option value="1993">1993</option>
                                <option value="1992">1992</option>
                                <option value="1991">1991</option>
                                <option value="1990">1990</option>
                                <option value="1989">1989</option>
                                <option value="1988">1988</option>
                                <option value="1987">1987</option>
                                <option value="1986">1986</option>
                                <option value="1985">1985</option>
                                <option value="1984">1984</option>
                                <option value="1983">1983</option>
                                <option value="1982">1982</option>
                                <option value="1981">1981</option>
                                <option value="1980">1980</option>
                                <option value="1979">1979</option>
                                <option value="1978">1978</option>
                                <option value="1977">1977</option>
                                <option value="1976">1976</option>
                                <option value="1975">1975</option>
                                <option value="1974">1974</option>
                                <option value="1973">1973</option>
                                <option value="1972">1972</option>
                                <option value="1971">1971</option>
                                <option value="1970">1970</option>
                                <option value="1969">1969</option>
                                <option value="1968">1968</option>
                                <option value="1967">1967</option>
                                <option value="1966">1966</option>
                                <option value="1965">1965</option>
                                <option value="1964">1964</option>
                                <option value="1963">1963</option>
                                <option value="1962">1962</option>
                                <option value="1961">1961</option>
                                <option value="1960">1960</option>
                                <option value="1959">1959</option>
                                <option value="1958">1958</option>
                                <option value="1957">1957</option>
                                <option value="1956">1956</option>
                                <option value="1955">1955</option>
                                <option value="1954">1954</option>
                                <option value="1953">1953</option>
                                <option value="1952">1952</option>
                                <option value="1951">1951</option>
                                <option value="1950">1950</option>
                                <option value="1949">1949</option>
                                <option value="1948">1948</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group">
                          <label class="form-label">Username</label>
                          <input type="text" class="form-control" disabled="disabled" placeholder="Username" value="<?php echo $_SESSION['user']['username'];?>">
                        </div>
                      </div>
                      <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                          <label class="form-label">Phone Number<span class="form-required">*</span></label>
                          <input type="number" class="form-control" placeholder="Phone Number" name="phone" required>
                        </div>
                      </div>
                      <div class="col-sm-6 col-md-4">
                        <div class="form-group">
                          <label class="form-label">Email address</label>
                          <input type="email" class="form-control" placeholder="Email" disabled="disabled" value="<?php echo $_SESSION['user']['email'];?>">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="form-label">Address<span class="form-required">*</span></label>
                          <input type="text" class="form-control" placeholder="Address" name="address" required>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                          <label class="form-label">Highest Level of Education<span class="form-required">*</span></label>
                          <select name="education" class="form-control custom-select" required>
                            <option value="">Select</option>
                            <option value="None">None</option>
                            <option value="Quaranic">Quaranic</option>
                            <option value="Primary">Primary</option>
                            <option value="Secondary">Secondary</option>
                            <option value="Tertiary">Tertiary</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                          <label class="form-label">Degree - If available</label>
                          <select name="degree" class="form-control custom-select">
                            <option value="">Select</option>
                            <option value="B.Tech">B.Tech</option>
                            <option value="B.Eng">B.Eng</option>
                            <option value="B.Sc">B.Sc</option>
                            <option value="B.A">B.A</option>
                            <option value="B.Arch">B.Arch</option>
                            <option value="M.B">M.B.B.S</option>
                            <option value="B.Pharm">B.Pharm</option>
                            <option value="B.Ed">B.Ed</option>
                            <option value="LL.B">LL.B</option>
                            <option value="Others">Others</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-12">
                        <div class="form-group">
                          <label class="form-label">State<span class="form-required">*</span></label>
                          <select name="user_state" class="form-control custom-select" id="state-select"  onChange="updateTownSelect(this.value);" required>
                            <option value="">Select state</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-12">
                        <div class="form-group">
                          <label class="form-label">Local Gov't Area (LGA)<span class="form-required">*</span></label>
                          <select name="user_lga" class="form-control custom-select" id="lga-select" required>
                            <option value="">Select LGA</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-12">
                        <div class="form-group">
                          <label class="form-label">Town/Village<span class="form-required">*</span></label>
                          <input type="text" name="user_town" class="form-control" autocomplete="off" required>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group mb-0">
                          <label class="form-label">About Me</label>
                          <textarea rows="5" class="form-control" placeholder="Brief description about you..." name="about_user"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary" name="update_profile">Update Profile</button>
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
    <!-- LGA Data -->
    <script src="./assets/js/lga_data.js"></script>
    <!-- LGA js -->
    <script src="./assets/js/lga.js"></script>
  </body>
</html>