<?php
//Librerias Externas


require_once "tools/PHPExcel/PHPExcel.php";
require_once "tools/PHPMailerV2/class.phpmailer.php";
require_once "tools/PHPMailerV2/class.smtp.php";

//Funciones del Sistema
require_once "controllers/funciones/TCI.Funciones.php";

//Controladores del Sistema
require_once "controllers/template.php";
require_once "controllers/Usuarios.Controlador.php";
require_once "controllers/opEdi.Controlador.php";

//Modelos del Sistema
require_once "models/Usuarios.Model.php";
require_once "models/opEdi.Model.php";
require_once "models/menu.Model.php";

$template = new TemplateController();
$template -> ctrlTemplate();

?>