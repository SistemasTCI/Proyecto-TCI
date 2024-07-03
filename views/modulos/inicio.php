<script src="views/dist/js/inicio.js"></script>
<script src="views/dist/js/TCI.Funciones.js"></script>

<body onload="js_inicioInicializar()" id="bdinicio">
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!--<h1 class="m-0">Inicio</h1>-->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Grupo Tramitaciones</a></li>
              <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="content">
      <form enctype="multipart/form-data" action="#" method="post">
        <div class="row">
          <div class="col-sm-12">
            <label> PROVEEDOR</label>
            <select id="inicio_mdlUpFiles_slcPanelProveedor" name="inicio_mdlUpFiles_slcPanelProveedor" class='form-control'>
              <option value="">SELECT AN OPTION</option>
            </select>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-sm-12">
            <label>Path </label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <input type="text" class="form-control" id="inicio_mdlUpFiles_Path" value="" readonly>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-sm-12">
            <label> FILES IN FOLDER</label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label class="input-group-text bg-secondary" for="inicio_mdlUpFiles_XML">XML</label>
            <input type="text" class="form-control" id="inicio_mdlUpFiles_XML" value="" readonly>
          </div>
          <div class="col-sm-4">
            <label class="input-group-text bg-secondary" for="inicio_mdlUpFiles_PDF">PDF</label>
            <input type="text" class="form-control" id="inicio_mdlUpFiles_PDF" value="" readonly>
          </div>
          <div class="col-sm-4">
            <label class="input-group-text bg-secondary" for="inicio_mdlUpFiles_XLS">XLS</label>
            <input type="text" class="form-control" id="inicio_mdlUpFiles_XLS" value="" readonly>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-sm-11">
          </div>
          <div class="col-sm-1">
            <button type="button" class="btn btn-info" id="inicio_mdlUpFiles_btnUpFiles">Upload Files</button>
          </div>
        </div>
      </form>
    </div>
    
    <div class="modal fade" id="inicio_mdlUpFiles" name="inicio_mdlUpFiles" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">UPLOAD FILES</h4>
          </div>
          <div class="modal-body">
            <div class="form">
              <div class="row">
                <div class="col-sm-12">
                  <label> CLIENT OR IMPORTER</label>
                  <select id="inicio_mdlUpFiles_slcPanelClientes" name="inicio_mdlUpFiles_slcPanelClientes" class='form-control'>
                    <option value="">SELECT AN OPTION</option>
                  </select>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-sm-12">
                  <label>Path </label>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="inicio_mdlUpFiles_Path" value="" readonly>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-sm-12">
                  <label> FILES IN IMPORTER FOLDER</label>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <label class="input-group-text bg-secondary" for="inicio_mdlUpFiles_XML">XML</label>
                  <input type="text" class="form-control" id="inicio_mdlUpFiles_XML" value="" readonly>
                </div>
                <div class="col-sm-4">
                  <label class="input-group-text bg-secondary" for="inicio_mdlUpFiles_PDF">PDF</label>
                  <input type="text" class="form-control" id="inicio_mdlUpFiles_PDF" value="" readonly>
                </div>
                <div class="col-sm-4">
                  <label class="input-group-text bg-secondary" for="inicio_mdlUpFiles_XLS">XLS</label>
                  <input type="text" class="form-control" id="inicio_mdlUpFiles_XLS" value="" readonly>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="row col-12">
              <div class="col-sm-6">
              </div>
              <div class="col-sm-4">
                <button type="button" class="btn btn-info" id="inicio_mdlUpFiles_btnUpFiles">Upload Files</button>
              </div>
              <div class="col-sm-2">
                <button type="button" class="btn btn-danger inicio_mdlUpFiles_btnclosemdl">Close</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</body>