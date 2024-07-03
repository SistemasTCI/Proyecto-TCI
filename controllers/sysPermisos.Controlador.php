<?php
    class ControladorsysPermisos{
        static public function ctrInicializarRol(){
            $data = new stdClass();
            $Roles=ModelosysPermisos::MdlRoles();
            $cont=count($Roles);
            if($cont>0){ 
                for($x=0;$x<$cont;$x++){
                    $ID = $Roles[$x]["IdRol"];
                    $Rol= $Roles[$x]["Rol_Name"];
                    $Descripcion=$Roles[$x]["Descripcion"];
                    $Boton= '<div class="btn-group">'.
                                '<button type="button" class="btn btn-primary btn-smy sysPermisos_SelecionaRol" id="sysPermisos_bntSeleccionar'.$x.'" name="sysPermisos_bntSeleccionar'.$x.'"  data-rol='.$Rol.'>'. 
                                    '<i class="fa fa-fw fa-check"></i> Seleccionar'.
                                '</button>'. 
                            '</div>';       
                    $data->Roles[$x]=array($ID,$Rol,$Descripcion,$Boton); 
                }
            }
            else{
                $data->Roles[0]=array('No data','','',''); 
            }
            return ($data);    
        }
        static public function ctrInicializarModulo(){
            $data = new stdClass();
            $Modulos=ModelosysPermisos::MdlModulos();
            $cont=count($Modulos);
            if($cont>0){ 
                for($x=0;$x<$cont;$x++){
                    $ID = $Modulos[$x]["IdModulo"];
                    $Modulo= $Modulos[$x]["Modulo"];
                    $Descripcion = $Modulos[$x]["Descripcion"];
                    $Estado='';
                    //$Estado = '<button class="btn btn-danger btn-xs sysPermisos_Activar" data-modulo="'.$Modulo.'" data-estado="1">Inactivo</button>';
                    //$Estado = '<button class="btn btn-success btn-xs sysPermisos_Activar" data-modulo="'.$Modulo.'" data-estado="0">Activo</button>';
                    /*$Permiso=   '<div class="btn-group">'.
                                    '<select class="form-control sysPermisos_SelectPermiso" id="sysPermisos_slcPermisos'.$x.'" name="sysPermisos_slcPermisos'.$x.'" data-modulo="'.$Modulo.'">'.
                                        '<option value="0">Sin Acceso</option>'. 
                                        //'<option value="Consulta">Consulta </option>'.
                                        //'<option value="Usuario">Usuario</option>'.
                                        '<option value="Administrador">Administrador</option>'.
                                    '</select>'.
                                '</div>';*/
                    $Permiso='';
                    $data->Modulos[$x]=array($ID,$Modulo,$Descripcion,$Estado,$Permiso); 
                }
            }
            else{
                $data->Modulos[0]=array('No data','','',''); 
            }
            return ($data);    
        }
        static public function ctrAddRol($Rol,$Descripcion){
            $AddRol=ModelosysPermisos::MdlAddRol($Rol,$Descripcion);
            return $AddRol;  
        }
        static public function ctrAddModulo($Modulo,$Pagina,$Descripcion){
            $AddModulo=ModelosysPermisos::MdlAddModulo($Modulo,$Pagina,$Descripcion);
            return $AddModulo;  
        }
        static public function ctrRelPermiso($Rol,$Modulo,$Permiso,$Activo){
            $RelPermiso=ModelosysPermisos::MdlRelPermiso($Rol,$Modulo,$Permiso,$Activo);
            return $RelPermiso;  
        }
        static public function ctrMostrarPermisos($Rol){
            $data = new stdClass();
            $MostrarPermisos=ModelosysPermisos::MdlMostrarPermisos($Rol);
            $cont=count($MostrarPermisos);
            $modulos=ModelosysPermisos::MdlModulos();
            $contM=0;
            if(isset($modulos))
            {
                foreach($modulos as $k=>$v){
                    $ID = $modulos[$k]["IdModulo"];
                    $Modulo= $modulos[$k]["Modulo"];
                    $Descripcion = $modulos[$k]["Descripcion"];
                    $Estado='';
                    $opciones=[1=>'<option value="SinPermiso">Sin Permiso</option>',2=>'<option value="Administrador">Administrador</option>',3=>'<option value="Usuario">Usuario</option>',4=>'<option value="Consulta">onsulta</option>'];
                    $permiso='';
                    $activo='0';
                    for($x=0;$x<$cont;$x++){
                        if($MostrarPermisos[$x]["Modulo"]== $ID){
                            $permiso=$MostrarPermisos[$x]["Tipo_Permiso"];
                            $activo='1';
                        }
                    }
                    switch($permiso){
                        case "Administrador":
                            $opciones[2]='<option value="Administrador"  selected>Administrador</option>';
                            break;
                        case "Usuario":
                            $opciones[3]='<option value="Usuario" selected>Usuario</option>';
                            break;
                        case "Consulta":
                            $opciones[4]='<option value="Consulta"  selected>consulta</option>';
                            break;
                        default:
                            $opciones[1]='<option value="SinPermiso"  selected>Sin Permiso</option>';
                            break;
                    }
                    $Select=   '<div class="btn-group">'.
                                    '<select class="form-control sysPermisos_SelectPermiso" id="sysPermisos_slcPermisos'.$ID.'" name="sysPermisos_slcPermisos'.$ID.'" data-modulo="'.$ID.'" data-activo="'.$activo.'">'.
                                        $opciones[1].
                                        $opciones[2]. 
                                        $opciones[3].
                                        $opciones[4].
                                    '</select>'.
                                '</div>';
                    $data->MostrarPermisos[$contM]=array($ID,$Modulo,$Descripcion,$Estado,$Select);
                    $contM++;
                }  
            }
            else{
                $data->MostrarPermisos[0]=array('No data','','','',''); 
            }
            return $data; 
            
        }
        static public function ctrActPermiso($Rol,$Modulo,$Estado){
            $ActPermiso=ModelosysPermisos::MdlActPermiso($Rol,$Modulo,$Estado);
            return $ActPermiso;  
        }
        static public function ctrLlenarMenus(){
            $data = new stdClass();
            $MenusPadre=ModelosysPermisos::MdlMostrarMenusPadre();
            $SinMenu=ModelosysPermisos::MdlMostrarModulosSinMenu();
            $data->MenusPadre=$MenusPadre;
            $data->SinMenu=$SinMenu;
            return $data;  
        }
        static public function ctrAgregarMenu($Padre,$Hoja,$Nombre,$Icono){
            $AgregarMenu=ModelosysPermisos::MdlAgregarMenu($Padre,$Hoja,$Nombre,$Icono);
            return $AgregarMenu;  
        }
    }

?>