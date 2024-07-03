<?php
    require_once "conexion.php";
    class ModelosysPermisos{
        static public function MdlRoles(){
            $sql="SELECT * FROM tci.tci_sys_cat_roles";
            $stmt = conexion::conectar()->prepare($sql);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

        static public function MdlModulos(){
            $sql="SELECT * FROM tci.tci_sys_cat_modulos";
            $stmt = conexion::conectar()->prepare($sql);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

        static public function MdlAddRol($Rol,$Descripcion){

            $sql="INSERT INTO tci.tci_sys_cat_roles (Rol_Name,Descripcion,F_Alta) VALUES (:Rol,:Descripcion,now())";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":Rol",$Rol,PDO::PARAM_STR);
            $stmt-> bindParam(":Descripcion",$Descripcion,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlAddModulo($Modulo,$Pagina,$Descripcion){
            var_dump($Modulo,$Pagina,$Descripcion);
            $sql="INSERT INTO tci.tci_sys_cat_modulos (Modulo,PageName,Descripcion,F_Alta) VALUES (:Modulo,:PageName,:Descripcion,now())";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":Modulo",$Modulo,PDO::PARAM_STR);
            $stmt-> bindParam(":PageName",$Pagina,PDO::PARAM_STR);
            $stmt-> bindParam(":Descripcion",$Descripcion,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

        static public function MdlRelPermiso($Rol,$Modulo,$Permiso,$Activo){
            if($Activo==1){
                $sql="UPDATE tci.tci_sys_rel_permisos SET Tipo_Permiso=:Permiso WHERE Modulo=:Modulo AND Rol_Name=:Rol";
            }
            else{
                $sql="INSERT INTO tci.tci_sys_rel_permisos (Rol_Name,Modulo,Tipo_Permiso,Activo,F_Alta) VALUES (:Rol,:Modulo,:Permiso,1,now())";
            }
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":Rol",$Rol,PDO::PARAM_STR);
            $stmt-> bindParam(":Modulo",$Modulo,PDO::PARAM_STR);
            $stmt-> bindParam(":Permiso",$Permiso,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

        static public function MdlMostrarPermisos($Rol){
            $sql=   "Select Modulo,Tipo_Permiso,Activo from tci.tci_sys_rel_permisos where Rol_Name=:Rol";
            /*$SQL="select a.IDModulo,a.Modulo,a.Descripcion,b.Activo,b.Tipo_Permiso,b.Rol_Name 
            from  tci.tci_sys_cat_modulos AS A LEFT JOIN tci.tci_sys_rel_permisos AS B ON a.Modulo=b.Modulo  
            WHERE b.Rol_Name is null OR b.Rol_Name =:Rol";*/
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":Rol",$Rol,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt ->fetchall();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

        static public function MdlActPermiso($Rol,$Modulo,$Estado){
            VAR_DUMP($Rol,$Modulo,$Estado);
            if($Estado==0){
                $sql="INSERT INTO tci.tci_sys_rel_permisos (Rol_Name,Modulo,Tipo_Permiso,Activo,F_Alta) VALUES (:Rol,:Modulo,'NA',1,now())";
            }
            else{
                $sql="UPDATE tci.tci_sys_rel_permisos SET Activo=:Estado WHERE Modulo=:Modulo AND Rol_Name=:Rol";
            }
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":Rol",$Rol,PDO::PARAM_STR);
            $stmt-> bindParam(":Modulo",$Modulo,PDO::PARAM_STR);
           // $stmt-> bindParam(":Estado",$Estado,PDO::PARAM_INT);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlMostrarMenusPadre(){
            $sql="SELECT IDMenu,MenuName FROM  tci.tci_sys_cat_menu as A 
            left join tci.tci_sys_cat_modulos as B on A.IdModulo=B.IdModulo
            WHERE B.IdModulo IS NULL";
            $stmt = conexion::conectar()->prepare($sql);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlMostrarModulosSinMenu(){
            $sql="SELECT b.IdModulo,b.Modulo FROM  tci.tci_sys_cat_menu as A 
            RIGHT join tci.tci_sys_cat_modulos as B on A.IdModulo=B.IdModulo
            WHERE A.IdModulo IS NULL";
            $stmt = conexion::conectar()->prepare($sql);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlAgregarMenu($Padre,$Hoja,$Nombre,$Icono){
            if($Hoja==''){
                $sql="INSERT INTO tci.tci_sys_cat_menu(IdPadre,MenuName,Icon,F_Alta) VALUES (:Padre,:Nombre,:Icono,now())";
                $stmt = conexion::conectar()->prepare($sql);
            }
            else{
                $sql="INSERT INTO tci.tci_sys_cat_menu(IdPadre,IdModulo,MenuName,Icon,F_Alta) VALUES (:Padre,:Hoja,:Nombre,:Icono,now())";
                $stmt = conexion::conectar()->prepare($sql);
                $stmt-> bindParam(":Hoja",$Hoja,PDO::PARAM_STR);
            }
            $stmt-> bindParam(":Padre",$Padre,PDO::PARAM_STR);
            $stmt-> bindParam(":Nombre",$Nombre,PDO::PARAM_STR);
            $stmt-> bindParam(":Icono",$Icono,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
    }

?>