<?php
require_once "../controllers/sysPermisos.Controlador.php";
require_once "../models/sysPermisos.Model.php";

if(isset($_POST["InicializarTbRoles"])){
    $inicializarRol=new  AjaxsysPermisos();
    $inicializarRol->ajaxInicializarRol();    
}
if(isset($_POST["InicializarTbModulos"])){
    $inicializarModulo=new  AjaxsysPermisos();
    $inicializarModulo->ajaxInicializarModulo();    
}
if(isset($_POST["AddRol"])){
    $AddRol=new  AjaxsysPermisos();
    $AddRol->Rol=$_POST['Rol'];
    $AddRol->Descripcion=$_POST['Descripcion'];
    $AddRol->ajaxAddRol();    
}
if(isset($_POST["AddModulo"])){
    $AddModulo=new  AjaxsysPermisos();
    $AddModulo->Modulo=$_POST['Modulo'];
    $AddModulo->Pagina=$_POST['Pagina'];
    $AddModulo->Descripcion=$_POST['Descripcion'];
    $AddModulo->ajaxAddModulo();    
}
if(isset($_POST["RelPermiso"])){
    $RelPermiso=new  AjaxsysPermisos();
    $RelPermiso->Modulo=$_POST['Modulo'];
    $RelPermiso->Activo=$_POST['Activo'];
    $RelPermiso->Permiso=$_POST['Permiso'];
    $RelPermiso->Rol=$_POST['Rol'];
    $RelPermiso->ajaxRelPermiso();    
}
if(isset($_POST["MostrarPermisos"])){
    $MostrarPermisos=new  AjaxsysPermisos();
    $MostrarPermisos->Rol=$_POST['Rol'];
    $MostrarPermisos->ajaxMostrarPermisos();    
}

if(isset($_POST["ActPermiso"])){
    $ActPermiso=new  AjaxsysPermisos();
    $ActPermiso->Modulo=$_POST['Modulo'];
    $ActPermiso->Estado=$_POST['Estado'];
    $ActPermiso->Rol=$_POST['Rol'];
    $ActPermiso->ajaxActPermiso();    
}

if(isset($_POST["LlenarMenus"])){
    $LlenarMenus=new  AjaxsysPermisos();
    $LlenarMenus->ajaxLlenarMenus();    
}
if(isset($_POST["AgregarMenu"])){
    $AgregarMenu=new  AjaxsysPermisos();
    $AgregarMenu->Padre=$_POST['Padre'];
    $AgregarMenu->Hoja=$_POST['Hoja'];
    $AgregarMenu->Nombre=$_POST['Nombre'];
    $AgregarMenu->Icono=$_POST['Icono'];
    $AgregarMenu->ajaxAgregarMenu();    
}

class AjaxsysPermisos{
    public function ajaxInicializarRol(){
        $respuesta=ControladorsysPermisos::ctrInicializarRol();
        echo json_encode($respuesta);
    }
    public function ajaxInicializarModulo(){
        $respuesta=ControladorsysPermisos::ctrInicializarModulo();
        echo json_encode($respuesta);
    }
    public function ajaxAddRol(){
        $AddRol=ControladorsysPermisos::ctrAddRol($this->Rol,$this->Descripcion);
        echo json_encode($AddRol);
    }
    public function ajaxAddModulo(){
        $AddModulo=ControladorsysPermisos::ctrAddModulo($this->Modulo,$this->Pagina,$this->Descripcion);
        echo json_encode($AddModulo);
    }
    public function ajaxRelPermiso(){
        $RelPermiso=ControladorsysPermisos::ctrRelPermiso($this->Rol,$this->Modulo,$this->Permiso,$this->Activo);
        echo json_encode($RelPermiso);
    }
    public function ajaxMostrarPermisos(){
        $MostrarPermisos=ControladorsysPermisos::ctrMostrarPermisos($this->Rol);
        echo json_encode($MostrarPermisos);
    }
    public function ajaxActPermiso(){
        $ActPermiso=ControladorsysPermisos::ctrActPermiso($this->Rol,$this->Modulo,$this->Estado);
        echo json_encode($ActPermiso);
    }
    public function ajaxLlenarMenus(){
        $LlenarMenus=ControladorsysPermisos::ctrLlenarMenus();
        echo json_encode($LlenarMenus);
    }
    public function ajaxAgregarMenu(){
        $AgregarMenu=ControladorsysPermisos::ctrAgregarMenu($this->Padre,$this->Hoja,$this->Nombre,$this->Icono);
        echo json_encode($AgregarMenu);
    }
}
?>