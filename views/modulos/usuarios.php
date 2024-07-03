<body onload="usuarios_js_Inicializar()">
<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">CBRIS</a></li>
              <li class="breadcrumb-item active"><a href="inicio">Inicio</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="card">
      <div class="card-header border-0">
        <h3 class="card-title">Panel de  Usuarios</h3>
        <div class="card-tools">
          <a href="#" class="btn btn-tool btn-sm">
            <buttom class="btn btn-secundary sm" data-toggle="modal" data-target="#modalAgregarUsuario"><i class="fas fa-user-plus"></i> </buttom>
          </a>
        </div>
      </div>
      <div class="card-body with-border">
        <div class="box">
          <div class="box-header with-border">

          </div>
          <div class="box-body">
            <table class="table table-striped tabladatatable dt-responsive">
            <!--<table class="table table-striped tabladatatable dt-responsive" id="tabla_usuarios">-->
              <thead>
                <tr>
                  <th scope="col">IdUsuario</th>
                  <th scope="col">Usuario</th>
                  <th scope="col">Perfil</th>
                  <th scope="col">Foto</th>
                  <th scope="col">Estado</th>
                  <th scope="col">Fecha de Alta</th>
                  <th scope="col">Last Login</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $item=null;
                  $valor=null;
                  $usuarios=ControladorUsuarios::ctrMostrarUsuarios($item,$valor);
                  foreach($usuarios as $key=> $value){
                    echo '<tr>
                    <th scope="row">'.$value['IdUsuario'].'</th>
                    <td>'.$value['Usuario'].'</td>
                    <td>'.$value['Perfil'].'</td>';
                    if($value["Foto"]!=""){

                      echo '<td><img src="'.$value['Foto'].'" class="img-thumbnail" width="40px"></td>';

                    }
                    else{
                      echo '<td><img src="views/dist/img/User.png" class="img-thumbnail" width="40px"></td>';
                    }

                    if ($value['Estado']!="1"){
                      echo '<td><button class="btn btn-danger btn-xs btnActivar" idUsuario="'.$value['IdUsuario'].'" estadoUsuario="1">Inactivo</button></td>';
                    }
                    else{
                      echo '<td><button class="btn btn-success btn-xs btnActivar" idUsuario="'.$value['IdUsuario'].'" estadoUsuario="0">Activo</button></td>';
                    }
                    
                    echo '
                    <td>'.$value['F_Alta'].'</td>
                    <td>'.$value['U_Login'].'</td>
                    <td> 

                      <div class="btn-group">

                        <button class="btn btn-warning btn-xs btnEditarUsuario" idUsuario="'.$value['IdUsuario'].'" data-toggle="modal" data-target="#modalEditarUsuario">
                          <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button class="btn btn-danger btn-xs btnEliminarUsuario" idUsuario="'.$value['IdUsuario'].'" fotoUsuario="'.$value["Foto"].'" Usuario="'.$value["IdUsuario"].'">
                          <i class="fas fa-times"></i>
                        </button>

                      </div>

                    </td>

                    </tr>';

                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalAgregarUsuario" role="dialog">
      <div class="modal-dialog">
        <form role="form" method="post" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header" style="background: #3c8dbc; color: white;">
              <buttom type="buttom" class="close" data-dismiss="modal">&times;</buttom>
              <h4 class="modal-title">Agregar usuarios</h4>
            </div>
            <div class="modal-body">
              <div class="box-body">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control input-lg" ID="nuevoNombre"  name="nuevoNombre" placeholder="Ingresar Nombre" required>
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon" style="margin:10px 10px 0px 0px;"><i class="fa fa-key"></i></span>
                    <input type="text"  class="form-control input-lg" id="nuevoUsuario" name="nuevoUsuario" placeholder="Ingresar Usuario" required>
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="text" class="form-control input-lg" ID="nuevoPassword"  name="nuevoPassword" placeholder="Ingresar Password" required>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <select class="form-control input-lg usuarios_viewRol" ID="nuevoPerfil" name="nuevoPerfil">
                      <option values="">Seleccionar Perfil</option>
                    </select>
                  </div>
                </div>
              
                <div class="form-group">
                  <div class="panel">Subir Foto</div>
                  <input type="file" class="nuevaFoto" name="nuevaFoto" class="center-block">
                  <p class="center-block">Peso Maximo de la foto 2Mb</p>
                  <img src="views/dist/img/user.png" class="thumbnail center-block previsualizar" width="100px">
                </div>

              </div>
            </div>
            <div class="modal-footer">
              <button type="buttom" class="btn btn-danger pull-left" data-dismiss="modal">Salir</button>
              <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
          </div>
          <?php
            //$crearUsuario = ControladorUsuarios::ctrCrearUsuario();
            /*$crearUsuario = new ControladorUsuarios();
            $crearUsuario -> ctrCrearUsuario();*/
          ?>
        </form>
      </div>
    </div>
    
    <div class="modal fade" id="modalEditarUsuario" role="dialog">
      <div class="modal-dialog">
        <form role="form" method="post" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Agregar Usuario</h5>
              <button type="button" class="close" data-dismiss="modal" arial-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon" style="margin:10px 10px 0px;">
                    <i class="fa fa-key"></i>
                  </span>
                  <input type="text" name="editarUsuario" id="editarUsuario" class="form-control input-lg" placeholder="Ingresar Usuario" readonly>
                </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon" style="margin:10px 10px 0px;">
                    <i class="fa fa-lock"></i>
                  </span>
                  <input type="text" name="editarPassword" id="editarPassword" class="form-control input-lg" placeholder="Escriba la nueva contraseña">
                  <input type="hidden" name="passwordActual" id="passwordActual">
                </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon" style="margin:10px 10px 0px 0px;"><i class="fa fa-user"></i></span>
                  <select class="form-control input-lg" id="editarPerfil" name="editarPerfil">
                    <option  value="" id="UsuarioPerfil"></option>
              
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <div class="panel">Subir Foto</div>
                  <input type="file" class="nuevaFoto center-block" name="editarFoto">
                  <p class="center-block">Peso Maximo de la foto 2Mb</p>
                  <img src="views/dist/img/user.png" class="thumbnail center-block previsualizar" width="100px">
                  <input type="hidden" name="fotoActual" id="fotoActual">
              </div>
            </div>
            <div class="modal-footer">
              <button type="buttom" class="btn btn-danger" data-dismiss="modal">Salir</button>
              <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
          </div>
          <?php
            $editarusuario = new ControladorUsuarios();
            $editarusuario -> ctrEditarUsuario();
          ?>
        </form>
      </div>        
    </div>
</div>
</body>
<?php
  $crearUsuario = ControladorUsuarios::ctrCrearUsuario(); 
  $borrarUsuario= new ControladorUsuarios();
  $borrarUsuario ->ctrBorrarUsuario();

?>




<!-- Ejemplo web-- >




