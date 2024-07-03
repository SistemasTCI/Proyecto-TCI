
<!-- Content Wrapper. Contains page content -->
<body onload="sysPermisos_js_Inicializar()" id="bdsysPermisos">
  <div class="content-wrapper" >
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <!--<div class="col-sm-6">
              <h1 class="m-0">Permisos</h1>
            </div> -->
            <div class="col-sm-12">
              <!--<ol class="breadcrumb float-sm-right">-->
              <ol class="breadcrumb" >
                <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                <li class="breadcrumb-item active">Permisos</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <nav class="navbar navbar-secondary  bg-secondary " style="background-color: #365e80!important;">
          <form class="form-inline">
            <div class="col-3">
              <button class="btn btn-dark ViewmdlRoles" type="button" data-toggle="tooltip" data-placement="top" data-original-title="Agregar Rol">
              <i class="fas fa-user-tie"></i>
              </button>
            </div>
            <div class="col-2">
              <div class="btn-group" role="group">
                <button class="btn btn-dark ViewmdlModulos" type="button" data-toggle="tooltip" data-placement="top" data-original-title="Agregar Modulo">
                  <i class="far fa-clone fa-fw"></i>
                </button>
                <button class="btn btn-dark ViewmdlMenus" type="button" data-toggle="tooltip" data-placement="top" data-original-title="Agregar Menu">
                  <i class="fas fa-sitemap fa-fw"></i>
                </button>
              </div>
            </div>
          </form>
        </nav>

        <br>
        <div class="row">
          <div class="col-lg-6">
            <div class="card col-12 h-100">
              <div class="card-header border-0">
                <h3 class="card-title"><i class="fas fa-user-tie text-muted"></i> Roles del Sistema</h3>
              </div>
              <div class="card-body table-responsive p-0">
                <table id="sysPermisos_tblRoles" class="table table-striped table-valign-middle" style="text-align:center;font-size:12px">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Rol</th>
                      <th>Description</th>
                      <th></th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card col-12 h-100">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title"><i class="far fa-clone fa-fw text-muted"></i> Modulos del Sistema</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="input-group">
                        <label for="sysPermisos_RelPermisos">Tabla de  Permisos para el ROL:  </label>
                        <input type="text" class="form-control text-muted" id="sysPermisos_RelPermisos"  name="sysPermisos_RelPermisos" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <table id="sysPermisos_tblModulos" class="table table-striped table-valign-middle" style="text-align:center;font-size:12px">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Modulo</th>
                        <th>Description</th>
                        <th>Estado</th>
                        <th>Permiso</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>   
        </div>
      </div>
  </div>

  <div class="modal fade" id="sysPermisos_mdlRoles" aria-labelledby="sysPermisos_mdlRoles">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title"> Roles de  Sistema </h3>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fas fa-briefcase"></i></span>
                  <input type="text" class="form-control input-lg" ID="sysPermisos_Rol"  name="sysPermisos_Rol" placeholder="Nombre del Rol" required>
                </div>
              </div>
						</div>
					</div>
          <div class="row">
						<div class="col-sm-12">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fas fa-tag"></i></span>
                  <input type="text" class="form-control input-lg" ID="sysPermisos_RolDescripcion"  name="sysPermisos_RolDescripcion" placeholder="Descripcion" required>
                </div>
              </div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6">
							<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
						</div>
            <div class="col-md-6">
							<button type="button" class="btn btn-info pull-rigth sysPermisos_AgregarRol" >Agregar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
  </div>

  <div class="modal fade" id="sysPermisos_mdlModulos" aria-labelledby="sysPermisos_mdlModulos">
		<div class="modal-dialog  ">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title"> Modulos de  Sistema </h3>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fas fa-briefcase"></i></span>
                  <input type="text" class="form-control input-lg" ID="sysPermisos_Modulo"  name="sysPermisos_Modulo" placeholder="Nombre del Modulo" required>
                </div>
              </div>
						</div>
					</div>
          <div class="row">
						<div class="col-sm-12">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon"><i class="far fa-file"></i></span>
                  <input type="text" class="form-control input-lg" ID="sysPermisos_ModuloNomPagina"  name="sysPermisos_ModuloNomPagina" placeholder="Nombre de Pagina" required>
                </div>
              </div>
						</div>
					</div>
          <div class="row">
						<div class="col-sm-12">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fas fa-tag"></i></span>
                  <input type="text" class="form-control input-lg" ID="sysPermisos_ModuloDescripcion"  name="sysPermisos_ModuloDescripcion" placeholder="Descripcion" required>
                </div>
              </div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6">
							<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
						</div>
            <div class="col-md-6">
							<button type="button" class="btn btn-info pull-rigth sysPermisos_AgregarModulo" >Agregar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
  </div>

  <div class="modal fade" id="sysPermisos_mdlMenus" aria-labelledby="sysPermisos_mdlMenus">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title"> Menus del Sistema </h3>
				</div>
				<div class="modal-body">
          <form id='sysPermisos_frmMenus'>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fas fa-user-tie"></i></span>
                      <select class="form-control" id="sysPermisos_slcMenusPadre" name="sysPermisos_slcMenusPadre" required>
                      </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="far fa-file"></i></span>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                      <select class="form-control sysPermisos_SelecTipoMenu" id="sysPermisos_slcSubMenu" name="sysPermisos_slcSubMenu" required>
                        <option value="0"  selected>SubMenu</option>
                        <option value="1">Pendientes</option>
                      </select>
                      </div>
                      <input type="text" class="form-control" placeholder="Nombre del SubMenu" required id='sysPermisos_txtSubmenu' name='sysPermisos_txtSubmenu'>
                      <select class="form-control" id="sysPermisos_slcMenusHoja" name="sysPermisos_slcMenusHoja" style='visibility:hidden'>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fas fa-user-tie"></i></span>
                    <input type="text" class="form-control" placeholder="Icono" id="sysPermisos_txtIcono" name="sysPermisos_txtIcono">
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6">
							<button type="button" class="btn btn-danger pull-left sysPermisos_mdlMenusReset">Close</button>
						</div>
            <div class="col-md-6">
							<button type="button" class="btn btn-info pull-rigth sysPermisos_AgregarMenu" >Agregar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
  </div>
</body>