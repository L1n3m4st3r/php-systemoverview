<!DOCTYPE html>

<?php
// Always use HTTPS
/*
  if ($_SERVER['HTTP_X_FORWARDED_PROTO'] != "https") {
  header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
  exit();
  }
 */
if ($_SERVER['HTTPS'] != "on") {
   header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
   exit();
}

function get_server_memory_usage() {

   $free = shell_exec('free');
   $free = (string) trim($free);
   $free_arr = explode("\n", $free);
   $mem = explode(" ", $free_arr[1]);
   $mem = array_filter($mem);
   $mem = array_merge($mem);
   $memory_usage = $mem[2] / $mem[1] * 100;

   return $memory_usage;
}

function get_bar_color($value) {
   if ($value < 45) {
      $color = "success";
   } elseif ($value < 80) {
      $color = "warning";
   } else {
      $color = "danger";
   }
   return $color;
}

// Build up a array with system information
$hdd = "simfs";

$system["hostname"] = exec("cat /etc/hostname") . "." . exec("dnsdomainname");
$system["os"] = exec("uname -mo");
$system["uname"] = exec("uname -r");
$system["uname_long"] = exec("uname -a");

$cpu = sys_getloadavg();

$load["cpu"] = round($cpu[0] + 0.2, 1);
$load["mem"] = round(get_server_memory_usage(), 1);
$load["swap"] = round((1 - exec("free | awk '/Swap/ { print $4/$2 }'")) * 100 + 0.2, 1);
$load["disk"] = round(exec("df -hl | awk '/^\/dev\/" . $hdd . "/ { sum+=$5 } END { print sum }'"), 1);
?>

<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, user-scalable=no">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

      <meta name="theme-color" content="#212121">

      <link rel="shortcut icon" ref="/favicon.ico">

      <title><?php echo ($system["hostname"]) ?></title>	

      <!-- Bootstrap -->
      <link href="res/css/bootstrap.min.css" rel="stylesheet">
      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
        <script src="res/js/html5shiv.min.js"></script>
        <script src="hres/js/respond.min.js"></script>
      <![endif]-->
   </head>

   <body>
      <nav class="navbar navbar-inverse">
         <div class="container-fluid">
            <div class="navbar-header">
               <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
               </button>
               <a class="navbar-brand" href="#"><?php echo $system["hostname"] ?></a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
               <ul class="nav navbar-nav navbar-right">
                  <p class="navbar-text"><?php echo $system["uname_long"] ?></p>
               </ul>

            </div>
         </div>
      </nav>

      <div class="container">
         <div class="row">
            <div class="col-sm-3">
            </div>

            <div class="col-sm-6">

               <div class="well">

                  <h3>CPU</h3>
                  <div class="progress progress-striped">
                     <div class="progress-bar progress-bar-<?php echo get_bar_color($load["cpu"]) ?>" style="width: <?php echo $load["cpu"] . "%" ?>"></div>
                  </div>

                  <h3>Memory</h3>
                  <div class="progress progress-striped">
                     <div class="progress-bar progress-bar-<?php echo get_bar_color($load["mem"]) ?>" style="width: <?php echo $load["mem"] . "%" ?>"></div>
                  </div>

                  <h3>Swap</h3>
                  <div class="progress progress-striped">
                     <div class="progress-bar progress-bar-<?php echo get_bar_color($load["swap"]) ?>" style="width: <?php echo $load["swap"] . "%" ?>"></div>
                  </div>

                  <h3>Disk</h3>
                  <div class="progress progress-striped">
                     <div class="progress-bar progress-bar-<?php echo get_bar_color($load["disk"]) ?>" style="width: <?php echo $load["disk"] . "%" ?>"></div>
                  </div>

               </div>

               <div class="well">
                  <center>
                     <a href="https://line-lan.net/datenschutz/">Privacy Policy</a> -
                     <a href="https://line-lan.net/impressum/">Imprint</a> -
                     <a href="https://line-lan.net/kontakt/">Contact us</a>

                     <br/><br />
                     (c) 2012-<?php echo date("Y"); ?> Line-Lan
                  </center>                 
               </div>

            </div>

            <div class="col-sm-3">
            </div>
         </div>
      </div>

      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <script src="res/js/jquery-1.12.3.min.js"></script>
      <!-- Include all compiled plugins (below), or include individual files as needed -->
      <script src="res/js/bootstrap.min.js"></script>
   </body>
</html>