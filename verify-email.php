<?php 
  include('functions.php');
  if(!$_SESSION['user']){ 
    header("Location: ./login.php"); 
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
    <script charset="utf-8" async src="./assets/js/dashboard.js"></script>
  </head>
  <body class="">
    <div class="page">
      <div class="page-single">
        <div class="container">
          <div class="row">
            <div class="col col-login mx-auto">
              <div class="text-center mb-6">
                <a href="./">
                  <img src="./assets/images/logo.png" class="h-6" alt="[VERDE]">
                </a>
              </div>
              <div class="card">
                <div class="card-body">
                  <h4>Hey there, <?php echo ucfirst($_SESSION['user']['username']); ?>!</h4>
                  <p>We just sent an activation link to <span><?php echo $_SESSION['user']['email']; ?></span>. Kindly click the link in the email to finish setting up your AgriData account.</p>
                  <button type="submit" class="btn btn-primary btn-block mt-6" name="resend">Didn't get it? Resend</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
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

      var url = "./verify-email.php";

      function makeCorsRequest() {
        var xhr = createCORSRequest('POST', url);
        if (!xhr) {
          alert('CORS not supported');
          return;
        }

        // Response handlers.
        xhr.onreadystatechange = function () {
          var tbody = $('.results');
          if (this.readyState === 4) {
            if (this.status === 200) {
              console.log("Clicked");
            }
          }
        }
        xhr.send();
      }
    </script>
  </body>
</html>