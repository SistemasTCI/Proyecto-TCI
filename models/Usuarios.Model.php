<?php

require_once "conexion.php";

class ModeloUsuarios{
    static public function MdlInicializar(){
        $sql="SELECT IdRol,Rol_Name FROM tci.tci_sys_cat_roles";
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

    static public function MdlMostrarUsuarios($tabla,$item,$valor){
        if($item!=null){
            //echo "<script>console.log('Debug Objects2: Entro a consulta' );</script>";
            //Primer Proceso, se  usa para  saber quien se logueo
            $stmt = conexion::conectar()->prepare("Select * From $tabla where $item = :$item");
            $stmt-> bindParam(":".$item,$valor,PDO::PARAM_STR);
            $stmt -> execute();
            //$resultado=$stmt -> fetch();
            return $stmt -> fetch();
            /*
            if($stmt-> rowCount()>0)
            {
                //echo "<script>console.log('RETORNO VALORES".$tabla.$item.$valor.$resultado['Usuario'].$resultado['Password'].$resultado['Foto']."' );</script>";
                return $resultado;
            }
            else{
               //echo "<script>console.log('rETORNO 1' );</script>";
                 return "1";
            }*/
        }
        else{
            //Segundo proceso para  consulta de  tabla Usuarios, llenara los datos de la tabla.
            $stmt=conexion::conectar()->prepare("Select * From $tabla");
            $stmt -> execute();
            return $stmt -> fetchAll();
        }
        $stmt->close();
        $stmt=null; 
    }

    static public function mdlIngresarUsuarios($tabla,$datos){
        // Se requiere  agregar el campo de ID Empleado,  el ID  Usuario  es  para el Sistema tci, el EMpleado es para nomina
        //$sql="Insert Into $tabla(usuario,password,perfil,estado,f_alta) values (:Usuario,:Password,:Perfil,:Estado,:F_Alta)";
        $sql="Insert Into $tabla (Usuario,Password,Perfil,Estado,F_Alta,Foto) values (:Usuario,:Password,:Perfil,:Estado,:F_Alta,:Foto)";
        $stmt=conexion::conectar()->prepare($sql);
        $stmt-> bindParam(":Usuario",$datos["Usuario"],PDO::PARAM_STR);
        $stmt-> bindParam(":Password",$datos["Password"],PDO::PARAM_STR);
        $stmt-> bindParam(":Perfil",$datos["Perfil"],PDO::PARAM_STR);
        $stmt-> bindParam(":Estado",$datos["Estado"],PDO::PARAM_STR);
        $stmt-> bindParam(":F_Alta",$datos["F_Alta"],PDO::PARAM_STR);
        $stmt-> bindParam(":Foto",$datos["Ruta"],PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }
        else{
            return "error ".$sql;
        }
        $stmt -> close();
        $stmt =null;
    
    }

    static public function mdlEditarUsuario($tabla,$datos){
        $stmt= conexion::conectar()-> prepare("UPDATE $tabla SET Password=:Password,Perfil=:Perfil,Foto=:Foto where Usuario=:Usuario");
        $stmt -> bindParam(":Usuario",$datos['Usuario'],PDO::PARAM_STR); 
        $stmt -> bindParam(":Password",$datos['Password'],PDO::PARAM_STR); 
        $stmt -> bindParam(":Perfil",$datos['Perfil'],PDO::PARAM_STR); 
        $stmt -> bindParam(":Foto",$datos['Ruta'],PDO::PARAM_STR);
        if($stmt->execute()){
            return "ok";
        } 
        else{
            return "error";
        }
        $stmt -> close();
        $stmt=null;
    }

    static public function mdlActualizarUsuario($tabla,$item1,$valor1,$item2,$valor2){

        $stmt=conexion::conectar()->prepare("UPDATE $tabla SET $item1=:$item1 where $item2=:$item2");
        $stmt -> bindParam(":".$item1,$valor1,PDO::PARAM_STR); 
        $stmt -> bindParam(":".$item2,$valor2,PDO::PARAM_STR); 
        if($stmt -> execute()){
            return "ok";
            
        }
        else{
            return "error";
        }
        $stmt -> close();
        $stmt -> null;
    }

    static public function mdlBorrarUsuario($tabla,$datos){
        $stmt=conexion::conectar()->prepare("DELETE FROM $tabla where IdUsuario=:IdUsuario");
        $stmt->bindParam(":IdUsuario",$datos,PDO::PARAM_STR);
        if($stmt->execute()){
            return "ok";
        }
        else{
            return "error";
        }
        $stmt->close();
        $stmt=null;
    }

    static public function mdlPermisos($Perfil){
        $sql='SELECT A.Modulo,a.Tipo_Permiso,b.PageName  FROM tci.tci_sys_rel_permisos AS A LEFT JOIN tci.tci_sys_cat_modulos AS B ON A.modulo=b.idmodulo where Rol_Name=:Rol and Tipo_Permiso !="SinPermiso";';
        $stmt=conexion::conectar()->prepare($sql);
        $stmt->bindParam(":Rol",$Perfil,PDO::PARAM_STR);
        if($stmt->execute()){
            return $stmt -> fetchAll();
        }
        else{
            return "error";
        }
        $stmt->close();
        $stmt=null;
    }


}

?>