<?php

class ControladorUsuarios{

        static public function ctrInicializar(){
            $respuesta=ModeloUsuarios::MdlInicializar();
            return $respuesta;
        }

        static public function ctrIngresar(){
            //var_dump('ENtro');
            if(isset($_POST['txtUsuario'])){
               if(preg_match('/^[a-zA-Z0-9]+$/',$_POST['txtUsuario']) && preg_match('/^[a-zA-Z0-9-#]+$/',$_POST['txtPassword'])){
                    //Tabla
                    $tabla="tci_sys_tb_usuarios";
                    //Columna
                    $item="Usuario";
                    $valor=$_POST['txtUsuario'];
                    //Restaurar password encryptado
                    $salt=md5($_POST["txtPassword"]);
                    $passwordEncriptado=crypt($_POST["txtPassword"],$salt);
                    //VAR_DUMP( $passwordEncriptado);
                    //$passwordEncriptado=$_POST["txtPassword"];
                    //hasta aqui
                    $respuesta=ModeloUsuarios::MdlMostrarUsuarios($tabla,$item,$valor);
                    //if($respuesta['Usuario']==$_POST['txtUsuario']&&$respuesta['Password']==$_POST['txtPassword']){
                    if($respuesta['Usuario']==$_POST['txtUsuario'] && $respuesta['Password']== $passwordEncriptado){
                        if($respuesta['Estado']==1){
                            $_SESSION['IniciarSesion']='ok';
                            $_SESSION['Usuario']=$respuesta['Usuario'];
                            $_SESSION['Foto']=$respuesta['Foto'];
                            //Seccion de permisos  y roles.
                            $_SESSION['Perfil']=$respuesta['Perfil'];
                            //Solicitar  Tabla  de  Permisos
                            $permisos=ModeloUsuarios::mdlPermisos($respuesta['Perfil']);
                            foreach($permisos as $permiso){
                                $TPermiso[]=['Tipo_Permiso'=>$permiso['Tipo_Permiso']];
                                $Permiso[]=['Modulo'=>$permiso['Modulo']];
                                $RPermiso[]=['Ruta'=>$permiso['PageName'],'Modulo'=>$permiso['Modulo']];
                            }
                            $_SESSION['Tipo_Permisos']=$TPermiso;
                            $_SESSION['RutasPermisos']=$RPermiso;
                            $_SESSION['Permisos']= $Permiso;

                            date_default_timezone_set("America/Chicago");
                            $fecha =date("y-m-d");
                            $hora =date("H:i:s");
                            $fechaActual=$fecha." ".$hora;
                            $item1="U_Login";
                            $valor1=$fechaActual;
                            $item2="IdUsuario";
                            $valor2=$respuesta['IdUsuario'];
                            $actualizarLogin=ModeloUsuarios::mdlActualizarUsuario($tabla,$item1,$valor1,$item2,$valor2);
                            if($actualizarLogin=="ok"){
                                echo '<script>
                                    window.location="inicio";
                                </script>'; 
                            }
                        }
                        else{
                            echo ("<div class='alert alert-danger'> Usuario Inactivo, contacte al administrador.</div>");
                        }    
                   }
                   else
                   {
                       echo("<div class='alert alert-danger'> Error al Ingresar al Sistema</div>");
                   }
               }    
            }
            
        }

        static public function ctrCrearUsuario(){
            if (isset($_POST['nuevoUsuario'])){  
                //if(preg_match('/^[a-zA-Z0-9]+$/',$_POST['nuevoUsuario']) && preg_match('/^[a-zA-Z0-9]+$/',$_POST['nuevoPassword']) && preg_match('/^[a-zA-Z0-9]+$/',$_POST['nuevoNombre'])){                  
                if(preg_match('/^[a-zA-Z0-9]+$/',$_POST['nuevoUsuario'])){ 
                    $ruta="";
                    if(isset($_FILES['nuevaFoto']['tmp_name']) && $_FILES['nuevaFoto']['tmp_name']!=''){
                       list($ancho,$alto)= getimagesize($_FILES['nuevaFoto']['tmp_name']);
                       $nuevoancho =500;
                       $nuevoalto=500;
                       //Crear directorio
                       $directorio="views/dist/img/usuarios/".$_POST['nuevoUsuario'];
                       mkdir($directorio,0755);
                        //De acuerdo al tipo de imagen se hace el proceso de recorte de la foto
                       if($_FILES['nuevaFoto']['type']=="image/jpeg"){
                           $aleatorio=mt_rand(100,999);
                           $ruta = $directorio."/".$aleatorio.".jpg";
                           $origen = imagecreatefromjpeg($_FILES['nuevaFoto']['tmp_name']);
                           $destino=imagecreatetruecolor($nuevoancho,$nuevoalto);
                           imagecopyresized($destino,$origen,0,0,0,0,$nuevoancho,$nuevoalto,$ancho,$alto);
                           imagejpeg($destino,$ruta);
                        }
                        else{
                            echo    '<script>
                                        Swal.fire({
                                            title: "Error de Foto type!",
                                            text: "!Registro no Exitoso!",
                                            icon: "error",
                                            confirmButtonText: "Cerrar"
                                        });
                                    </script>';

                        }

                        if($_FILES['nuevaFoto']['type']=="image/png"){
                            $aleatorio=mt_rand(100,999);
                            $ruta = $directorio."/".$aleatorio.".png";
                            $origen = imagecreatefrompng($_FILES['nuevaFoto']['tmp_name']);
                            $destino=imagecreatetruecolor($nuevoancho,$nuevoalto);
                            imagecopyresized($destino,$origen,0,0,0,0,$nuevoancho,$nuevoalto,$ancho,$alto);
                            imagepng($destino,$ruta);
                        }
                        else{
                            echo   '<script>
                                        Swal.fire({
                                            title: "Error de Foto type!",
                                            text: "!Registro no Exitoso!",
                                            icon: "error",
                                            confirmButtonText: "Cerrar"
                                        });
                                    </script>';

                        }
                    }
                    /*else{
                        echo    '<script>
                                    Swal.fire({
                                        title: "Error de Foto 1!",
                                        text: "!Registro  no Exitoso!",
                                        icon: "error",
                                        confirmButtonText: "Cerrar"
                                    });
                                </script>';
                    }*/
                    $tabla="tci_sys_tb_usuarios";
                    //var_dump('dos');
                    //COntrasena  encryptada
                    $salt=md5($_POST["nuevoPassword"]);
                    $passwordEncriptado=crypt($_POST["nuevoPassword"],$salt);
                    // hasta aqui
                    $datos=array("Usuario"=>$_POST['nuevoUsuario'],
                            //"Password"=>$_POST['nuevoPassword'],
                            "Password"=>$passwordEncriptado,
                            "Perfil"=>$_POST['nuevoPerfil'],
                            "Estado"=>true,
                            "F_Alta"=>date('Y-m-d H:i:s'),
                            "Ruta"=>$ruta
                            );
                    $respuesta=ModeloUsuarios::mdlIngresarUsuarios($tabla,$datos);
                    //var_dump( $respuesta);

                    if($respuesta=="ok"){
                        echo "<script>
                            Swal.fire({
                                title: 'Success!',
                                text: '!Registro Exitoso!',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                            </script>";
                    }
                    else{
                        echo "<script>
                        Swal.fire({
                            title: 'Error!',
                            text: '!No puedes usar caracteres especiales! ".$respuesta.$datos['Usuario'].$datos['Password'].$datos['Perfil'].$datos['Estado'].$datos['F_Alta']."',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                        </script>
                    ";
                    }
                }
                else{
                    var_dump(preg_match('/^[a-zA-Z0-9]+$/',$_POST['nuevoUsuario']));
                    var_dump(preg_match('/^[a-zA-Z0-9]+$/',$_POST['nuevoPassword']));
                    var_dump(preg_match('/^[a-zA-Z0-9]+$/',$_POST['nuevoNombre']));
                    echo    '<script>
                                    Swal.fire({
                                        title: "Error de Foto 1!",
                                        text: "!Registro  no Exitoso!",
                                        icon: "error",
                                        confirmButtonText: "Cerrar"
                                    });
                                </script>';
                }
            }
            else{
                echo "No se puede  Validar el usuario.";
            }
            
        }

        static public function ctrMostrarUsuarios($item,$valor){
            $tabla="tci_sys_tb_usuarios";
            $respuesta=ModeloUsuarios::MdlMostrarUsuarios($tabla,$item,$valor);
            return $respuesta;
        }

        static public function ctrEditarUsuario(){
            if(isset($_POST['editarUsuario'])){
                if(preg_match('/^[a-zA-Z0-9ñÑaáéÉíÍóÓúÚ]+$/',$_POST['editarUsuario'])){
                    $ruta=$_POST['fotoActual'];
                    if(isset($_FILES['editarFoto']['tmp_name']) && !empty($_FILES['editarFoto']['tmp_name'])){
                        list($ancho,$alto)=getimagesize($_FILES['editarFoto']['tmp_name']);
                        $nuevoancho=500;
                        $nuevoalto=500;
                        $directorio="views/dist/img/usuarios/".$_POST['editarUsuario'];
                        if(!empty($_POST['fotoActual'])){
                            unlink($_POST['fotoActual']);
                        }
                        else{
                            mkdir($directorio,0755);
                        }
                        if($_FILES['editarFoto']['type']=="image/jpeg"){
                            $aleatorio=mt_rand(100,999);
                            $ruta=$directorio."/".$aleatorio.".jpg";
                            $origen=imagecreatefromjpeg($_FILES['editarFoto']['tmp_name']);
                            $destino=imagecreatetruecolor($nuevoancho,$nuevoalto);
                            imagecopyresized($destino,$origen,0,0,0,0,$nuevoancho,$nuevoalto,$ancho,$alto);
                            imagejpeg($destino,$ruta);
                        }
                        if($_FILES['editarFoto']['type']=="image/png"){
                            $aleatorio=mt_rand(100,999);
                            $ruta=$directorio."/".$aleatorio.".png";
                            $origen=imagecreatefrompng($_FILES['editarFoto']['tmp_name']);
                            $destino=imagecreatetruecolor($nuevoancho,$nuevoalto);
                            imagecopyresized($destino,$origen,0,0,0,0,$nuevoancho,$nuevoalto,$ancho,$alto);
                            imagepng($destino,$ruta);
                        }
                    }
                    $tabla="tci_sys_tb_usuarios";
                    if($_POST['editarPassword']!=""){
                        if(preg_match('/^[a-zA-Z0-9]+$/',$_POST['editarPassword'])){
                            $salt=md5($_POST['editarPassword']);
                            $passwordEncriptado=crypt($_POST['editarPassword'],$salt);
                        }
                        else{
                            echo"<script>
							Swal.fire({ 
								title: 'Error!',
								text: '¡No puedes usar caraceres especiales en el campo contraseña!',
								icon: 'error',
								confirmButtonText:'Ok'
								});
							</script>";
                        }
                    }
                    else{
                        $passwordEncriptado=$_POST['passwordActual'];
                    }
                    $datos=array("Usuario"=>$_POST['editarUsuario'],"Password"=>$passwordEncriptado,"Perfil"=>$_POST['editarPerfil'],"Ruta"=>$ruta);
                    $respuesta=ModeloUsuarios::mdlEditarUsuario($tabla,$datos);
                    if($respuesta=="ok"){

                        echo "<script>
                        Swal.fire({
                            title: 'Sucess',
                            text:'!El usuario ha sdo actualizado corrctamente!',
                            icon: 'success',
                            confirmButtonText:'Ok' 
                        }).then((result)=>{
                            if(result.value){
                                window.location='usuarios';
                            }

                        })
                        </script>";
                    }
                    else{
                        echo"<script>
							Swal.fire({ 
								title: 'Error!',
								text: '¡No puedes usar caraceres especiales en el campo nombre!',
								icon: 'error',
								confirmButtonText:'Ok'
								})
						</script>";
                    }
                }
                else{
                    echo"<script>
                        Swal.fire({ 
                            title: 'Error!',
                            text: '¡No puedes usar caraceres especiales en el campo nombre!',
                            icon: 'error',
                            confirmButtonText:'Ok'
                            })
                    </script>";
                }

            }


        }

        static public function ctrBorrarUsuario(){
            if(isset($_GET['idUsuario'])){
                $tabla='tci_sys_tb_usuarios';
                $datos=$_GET['idUsuario'];
                if($_GET['fotousuario']!=""){
                    unlink($_GET['fotousuario']);
                    rmdir("views/dist/img/usuarios/".$_GET['usuario']);
                }
                $respuesta=ModeloUsuarios::mdlBorrarUsuario($tabla.$datos);
                if($respuesta=="ok"){
                    echo"<script>
                    swal.fire({
                        tilte:'Success!',
                        text: 'El usuario ha sido actualizado correctamente',
                        icon: 'success',
                        confirmButton:'Ok'
                    }).then((result)=>{
                        if(result.value){
                            window.location='usuarios';
                        }
                    })
                    </script>";
                }


            }
        }
    } 

?>