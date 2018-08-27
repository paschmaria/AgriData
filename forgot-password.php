<?php 
  include('functions.php');
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
      <div class="page-single">
        <div class="container">
          <div class="row">
            <div class="col col-login mx-auto">
              <div class="text-center mb-6">
                <a href="./">
                  <img src="./assets/images/logo.png" class="h-6" alt="[VERDE]">
                </a>
              </div>
              <?php echo alert(); ?>
              <form class="card" action="./forgot-password.php" method="post">
                <div class="card-body p-6">
                  <div class="card-title text-center">Forgot password</div>
                  <p class="text-muted">Enter your email address and we will send you a link to reset your password.</p>
                  <div class="form-group">
                    <label class="form-label" for="userEmail">Email address</label>
                    <input type="email" class="form-control" name="email" id="userEmail" aria-describedby="emailHelp" placeholder="Enter email address" required>
                  </div>
                  <?php echo display_error(); ?>
                  <div class="form-footer">
                    <button type="submit" class="btn btn-primary btn-block" name="send_email">Send password reset email</button>
                  </div>
                </div>
              </form>
              <div class="text-center text-muted">
                Forget it, <a href="./login.php">take me back</a> to the Login page.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
      require(['jquery'], function ($) {
        $(function() {
          var form = $('form.card');
          form.submit(function (e) {
            $("button[type=submit]").addClass("disabled");
          })
        })
      })
    </script>
  </body>
</html>