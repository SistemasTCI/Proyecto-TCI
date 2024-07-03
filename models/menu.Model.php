<?php
    require_once "conexion.php";
    class ModeloMenu{
        static public function MdlMenu(){
            $sql="SELECT A.IdMenu,A.IdPadre,A.MenuName,A.ICON,B.IdModulo,B.PageName FROM tci.tci_sys_cat_menu as A left join tci.tci_sys_cat_modulos as B on A.IdModulo=B.IdModulo order by A.IdPadre";
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
    }

?>