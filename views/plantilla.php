<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

 if(isset($_SESSION["iniciarsession"]) && $_SESSION["iniciarsession"] == "ok"){
  require 'modulos/includes/exportardata.php';
 }

ini_set('memory_limit', '10G');
ini_set('max_execution_time', 0); // sin límite de tiempo

?>


<!DOCTYPE html>
<html lang="es">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>Colombia Red Reports</title>

  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="icon" href="views/img/logos/favicon.png">

  <!--======= PLUGINGS DE CSS =========-->
  
  <!-- Vendor CSS Files -->
  <link href="views/bower_components/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="views/bower_components/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="views/bower_components/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="views/bower_components/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="views/bower_components/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="views/bower_components/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  

  <!-- Font Awesome -->
  <link rel="stylesheet" href="views/bower_components/font-awesome/css/font-awesome.min.css">

  <!-- Ionicons -->
  <link rel="stylesheet" href="views/bower_components/Ionicons/css/ionicons.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="views/dist/css/style.css">
  <!-- <link rel="stylesheet" href="views/bower_components/bootstrap/dist/css/bootstrap.min.css"> -->


  <!-- DataTables -->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">


  <!--======= PLUGINGS DE JAVASCRIPT =========-->

<!-- jQuery 3 -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <!-- Bootstrap 3.3.7 -->
  
  <!-- Vendor JS Files -->
  
  <script src="views/bower_components/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="views/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="views/bower_components/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="views/bower_components/vendor/chart.js/chart.umd.js"></script>
  <script src="views/bower_components/vendor/echarts/echarts.min.js"></script>
  <script src="views/bower_components/vendor/quill/quill.min.js"></script>
  <script src="views/bower_components/vendor/tinymce/tinymce.min.js"></script>
  <script src="views/bower_components/vendor/php-email-form/validate.js"></script>

  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.2/FileSaver.min.js"></script>


  <!-- alerts -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>

<body class="hold-transition skin-blue sidebar-mini login-page ">
  <?php 

    if(isset($_SESSION["iniciarsession"]) && $_SESSION["iniciarsession"] == "ok")
    {

          
      echo '<div class="wrapper">';

      include "modulos/includes/header.php";
      include "modulos/includes/mainbar.php";

      if($_SESSION["estado"] == 1){
        include 'modulos/modalPassword.php';
      }else{

      if (isset($_GET["ruta"])) {
        
        if ($_SESSION["perfil"] == "Administrador"){
          if ($_GET["ruta"] == "dashboard" ||
              $_GET["ruta"] == "usuarios" ||
              $_GET["ruta"] == "reportcdr" ||
              $_GET["ruta"] == "miaccount" ||
              $_GET["ruta"] == "monthconsumption" ||
              $_GET["ruta"] == "payhistory" ||
              $_GET["ruta"] == "modalPassword" ||
              $_GET["ruta"] == "dayconsumption" ||
              $_GET["ruta"] == "tarifas" ||
              $_GET["ruta"] == "Anti-hack" ||
              $_GET["ruta"] == "reportconsumo" ||
              $_GET["ruta"] == "salir")
            {
              include "modulos/".$_GET["ruta"].".php";
            } else {

                include "modulos/404.php";

            }
        } else {
          if ($_GET["ruta"] == "dashboard" ||
              $_GET["ruta"] == "reportcdr" ||
              $_GET["ruta"] == "miaccount" ||
              $_GET["ruta"] == "monthconsumption" ||
              $_GET["ruta"] == "payhistory" ||
              $_GET["ruta"] == "dayconsumption" ||
              $_GET["ruta"] == "modalPassword" ||
              $_GET["ruta"] == "tarifas" ||
              $_GET["ruta"] == "reportconsumo" ||
              $_GET["ruta"] == "salir")
            {
              include "modulos/".$_GET["ruta"].".php";
            } else {

                include "modulos/404.php";

            }
        }
      }

      include "modulos/includes/footer.php";

      echo '</div>';
            }
    } else {
      include "modulos/login.php";
    }
  ?>

    
  </div>
  <!-- ./wrapper -->
  <script src="views/js/plantilla.js"></script>
  <script src="views/js/usuarios.js"></script>
  <script src="views/js/firewall.js"></script>
  <script src="views/js/main.js"></script>
</body>
</html>
