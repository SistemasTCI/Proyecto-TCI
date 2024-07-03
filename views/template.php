<?php session_start()?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TCI</title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- <link rel="stylesheet" type="text/css" href="views/plugins/fontawesome-free/css/all.min.css">-->
  <!-- <link rel="stylesheet" type="text/css" href="views/plugins/fontawesome-free5/css/all.min.css">-->
  <link rel="stylesheet" type="text/css" href="views/plugins/fontawesome-free6/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="views/plugins/fontawesome-free6/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="views/plugins/bs-stepper/css/bs-stepper.css">
  <link rel="stylesheet" type="text/css" href="views/plugins/chart.js/Chart.min.css">
  <link rel="stylesheet" type="text/css" charset="utf8" href="views/dist/css/adminlte.min.css">
  <link rel="stylesheet" type="text/css" charset="utf8" href="views/dist/css/tci.dt.css">


 
<!-- sCRIPTS USADOS POR  JOSE E N SU PROYECTO DE  COSENTINO-->
<link rel="stylesheet" type="text/css" href="views/dist/css/dropzone.css">
<script src="views/dist/js/dropzone.js"></script>


<!-- FIN-->
  
<!-- Personalizadas TCI DataTables inicio-->
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css" defer>
 <link rel="stylesheet" type="text/css" href="views/plugins/datatables-responsive/css/responsive.bootstrap4.css" defer>
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" defer>

 <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js" defer></script>
 


 <script type="text/javascript" charset="utf8" src="views/plugins/datatables-responsive/js/dataTables.responsive.js" defer></script>
 <script type="text/javascript" charset="utf8" src="views/plugins/datatables-responsive/js/responsive.bootstrap4.js" defer></script>

 <!-- Conversion a ZIP-->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>

 <!--script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/select/1.3.0/js/select.dataTables.min.js" defer></script> -->
 <script type="text/javascript" charset="utf8" src="views/dist/js/dataTables.select.min.js" defer></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"  defer></script>

 <script scr="tools/Notifications/Notification.js"></script>
 

 <!-- Personalizadas TCI DataTables cierre -->


 
<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="views/plugins/jquery/jquery.js"></script>
<!--<script src="views/plugins/jquery/jquery.min.js"></script>-->

<!-- Bootstrap 4 -->
<script src="views/plugins/bootstrap/js/bootstrap.bundle.js"></script>
<!--<script src="views/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>-->
<!-- AdminLTE App -->
<script src="views/dist/js/adminlte.js"></script>
<!--<script src="views/dist/js/adminlte.min.js"></script>-->

<!-- ChartJS -->
<script src="views/plugins/chart.js/Chart.min.js"></script>
<script type="text/javascript" charset="utf8" src="https:////cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@1" defer></script>


<!-- AdminLTE for demo purposes -->
<script src="views/dist/js/demo.js"></script>
<!-- Page specific script -->


<script src="views/dist/js/template.js"></script>
<script src="views/dist/js/TCI.Funciones.js"></script>


</head>
<?php
  if(isset($_SESSION['IniciarSesion']) && $_SESSION['IniciarSesion']=="ok"){
    echo '<body class="hold-transition sidebar-mini layout-fixed">';
    echo '<div class="wrapper">';
    include "views/modulos/head.php";
    include "views/modulos/menu.php";
    if(isset($_GET['ruta'])){

      $RutasPermiso=$_SESSION['RutasPermisos'];
      //var_dump( $RutasPermiso);
      $cont=count($RutasPermiso)-1;
      for($i=0;$i<=$cont;$i++)
      {
        if($_GET['ruta']==$RutasPermiso[$i]['Ruta'] ){

          $_SESSION['ModuloReportes']= $RutasPermiso[$i]['Modulo'] ;
          include "views/modulos/".$_GET['ruta'].".php";
          break;
        }
        elseif($i==$cont){
          if($_GET['ruta']=="inicio" || $_GET['ruta']=="logout"){
            include "views/modulos/".$_GET['ruta'].".php";
            break;
          }
          else{
            include "views/modulos/404.php";
            break;
          }
        }
      }
    }
    include "views/modulos/footer.php";
    echo '</div>';
  }
  else{
    echo '<body class="hold-transition sidebar-mini layout-fixed login-page">';
    include "views/modulos/login.php";

  }
?>



<!-- Personalizadas TCI DataTables-->

<script src="views/dist/js/template.js"></script>
<!--<script src="views/dist/js/LaredoCHB.Funciones.js"></script>-->
<script src="views/dist/js/MaTrackingMb.js"></script>
<script src="views/dist/js/usuarios.js"></script>
<script src="views/dist/js/maOperaciones.js"></script>
<!--<script src="views/dist/js/opEdi.js"></script> SE  MIGRO A LA PAGINA  PHP DE  OPEDIS-->
<script src="views/dist/js/grnMetricos.js"></script>
<script src="views/dist/js/sysPermisos.js"></script>
<script src="views/dist/js/adReportes.js"></script>
<script src="views/dist/js/adReportesPersonalizados.js"></script>
<script src="views/dist/js/opTaskList.js"></script>
<script src="views/dist/js/opMonitor.js"></script>

<!-- Personalizadas TCI DataTables-->
</body>
</html>
