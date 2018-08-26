<?php 
  include('project-config.php');
  if(!$_SESSION['user']){ 
    header("Location: ./login.php?nexturl=forms.php?$_SERVER[QUERY_STRING]");
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
                <div class="nav-item d-none d-md-flex">
                  <a href="#" class="btn btn-sm btn-outline-primary create-form"><i class="fe fe-plus"></i> Create new Form</a>
                </div>
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
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="./forms.php?logout='1'">
                      <i class="dropdown-icon fe fe-log-out"></i> Log out
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
                  <input type="search" class="form-control header-search" placeholder="Search Forms&hellip;" tabindex="1">
                  <div class="input-icon-addon">
                    <i class="fe fe-search"></i>
                  </div>
                </form>
              </div>
              <div class="col-lg order-lg-first">
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                  <li class="nav-item" >
                    <a href="./forms.php" class="nav-link active"><i class="fe fe-edit-3"></i> Forms</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="my-3 my-md-5">
          <div class="container">
            <?php echo alert(); ?>
            <div class="dimmer active">
              <div class="loader"></div>
              <div class="dimmer-content">
                <div class="row row-cards row-deck">
                  <div class="col my-5 no-form active text-center">
                    <div class="mb-5">
                      <img src="./assets/images/empty.svg" alt="[NO FORMS]" class="img-fluid">
                    </div>
                    <h1 class="h2 mb-3">Oops... No form was found here!</h1>
                    <p class="h4 text-muted font-weight-normal mb-7">Does your organization have an account on AgriData? Contact your admin.</p>
                  </div>
                </div>
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
      require(['jquery'], function ($) {
        $(function() {
          $('.create-form').on('click', function (e) {
            e.preventDefault();
            alert("Sorry, this feature is unavailable at the moment! 😞");
          });

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

          var url = "./project-data.php";

          function collaboratorCount(c) {
            if (c!=="") {
              var cArr = c.split(',');
              if (cArr.length===1) {
                return `${cArr.length} collaborator`;
              } else {
                return `${cArr.length} collaborators`;
              }
            } else {
              return "0 collaborators";
            }
          }

          function displayName(name) {
            var name = name.split("-");
            for (var i = 0; i < name.length; i++) {
                name[i] = name[i][0].toUpperCase() + name[i].substr(1);
            }
            return name.join(" ");
          }

          // Make CORS Request
          function makeCorsRequest() {
            var xhr = createCORSRequest('GET', url);
            if (!xhr) {
              alert('CORS not supported');
              return;
            }

            // Response handlers.
            xhr.onreadystatechange = function () {
              var row = $('.row.row-cards.row-deck');
              if (this.readyState === 4) {
                if (this.status === 200) {
                  var projectData = JSON.parse(this.responseText);
                  projectData.map(data => {
                    console.log(data);
                    row.prepend(`
                      <div class="col-md-6 col-xl-4">
                        <div class="card" data-form="${data.project_name}" data-id="${data.project_id}">
                          <div class="card-status card-status-left bg-primary"></div>
                          <div class="card-header">
                            <h3 class="card-title">${displayName(data.project_name)}</h3>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-6 col-md-6 py-1">
                                <span class="tag tag-azure text-uppercase font-weight-bold">${data.status}</span>
                              </div>
                              <div class="col-6 col-md-6 py-1">
                                <span>${data.no_of_responses} responses</span>
                              </div>
                              <div class="col-6 col-md-6 py-1"></div>
                              <div class="col-6 col-md-6 py-1">${collaboratorCount(data.collaborators)}</div>
                            </div>
                          </div>
                          <div class="card-footer">
                            ${data.present_user===data.project_owner ?
                              `<ul class="list-inline m-0 text-right">
                                <li class="list-inline-item">
                                  <a href="./collaborate.php?name=${data.project_name}&id=${data.project_id}" class="" title="Add Collaborator"><i class="fe fe-user-plus"></i></a>
                                </li>
                                <li class="list-inline-item">
                                  <a href="./overview.php?name=${data.project_name}&id=${data.project_id}" class="" title="Analytics"><i class="fe fe-trending-up"></i></a>
                                </li>
                                <li class="list-inline-item">
                                  <a href="./data.php?name=${data.project_name}&id=${data.project_id}" class="" title="View Data"><i class="fe fe-file-text"></i></a>
                                </li>
                              </ul>`
                              : ""
                            }
                          </div>
                        </div>
                      </div>
                    `);
                  });
                  
                  var card = $('.card');
                  card.hover(
                    function () {
                      $(this).addClass('shadow-lg')
                        .css("cursor", "pointer");
                      var form = $(this).data('form');
                      var id = $(this).data('id');
                      var formURL = `./${form}.php?name=${form}&id=${id}`;
                      $(this).on('click', function () {
                        window.location.href = formURL;
                      })
                    }, function () {
                      $(this).removeClass('shadow-lg');
                    }
                  );
                  
                  if (projectData.length!==0) {
                     $(".no-form").removeClass("active");
                  }
                  
                  $(".dimmer").removeClass("active");
                } else {
                  console.log("Unable to retrieve data");
                  $(".dimmer").removeClass("active");
                }
              }
            };
            xhr.send();
          }
          makeCorsRequest();
        })
      })
    </script>
  </body>
</html>