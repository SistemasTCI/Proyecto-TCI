<body onload="js_opEdiInicializar()" id="bdopEdi">
	<div class="wrapper">	
		<div class="content-wrapper">
            <div class="content-header">
            </div>
		    <div class="container-fluid">
		        <div class="card mb-3">
                    <div class="card-header bg-info"> 
                        <span id="div_header">
                            <i class="fas fa-cubes fa-fw"></i>
                            Laredo CHB| Edi's 
                        </span>
                        <div class="card-tools">
                            <div class="dropdown dropleft">
                                <a class="btn btn-sm dropdown-toggle" href="#" role="button" id="dpdMenu" data-toggle="dropdown" data-placement="left"  aria-haspopup="true" aria-expanded="false" style="float: right;">
								    <i class="fas fa-bars"></i>
                                </a >
                                <a href="#" class="btn btn-sm opedis_readNotificaciones">
                                    <i class="fas fa-bell-slash"></i>
                                </a>	
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item opEdi_showmdlUpFiles" href="#">Upload Files</a>
                                    <a class="dropdown-item opEdi_showmdlClients" href="#">Automated Clients</a>
                                    <a class="dropdown-item opEdi_updateABITables" href="#">Update ABI Tables</a>
                                    <a class="dropdown-item opEdi_printManifest" href="#">Print Manifest</a>
                                    <a class="dropdown-item opEdi_showmldAddTC" href="#">Add Exchange Rate</a>
							    </div>
						    </div>
                            
                        </div>
                    </div>
                    <div class="card-body">
                       
                        <div class="row">
						    <div class="col-md-3">
							    <select class="form-control" id="slcPanelClientes">
                                    <option value="">Select the automated</option>
                                </select>
						    </div>
						    <div class="col-md-1">
							    <button type="button" class="btn btn-info btn-md btnMostrarCajas" style="margin-right: 5px;">
								    <i class="fa fa-search"> Show </i>
							    </button>
						    </div>
                            <div class="col-md-2">
                                    <select class="form-control" id="slcProcessTaskOfOperations" style="visibility:hidden;">
                                        <!--<option value="0" selected>Select a Task</option>
                                        <option value="1">Process Selected Operations</option>
                                        <option value="2">Process All Operations</option>
                                        <option value="3">Delete Selected Operations</option>
                                        <option value="4">Delete All Operations</option>-->
                                    </select>
						    </div>
                            <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-md btnProcess" style="visibility:hidden;" id="opEdi_btnProcess" name="opEdi_btnProcess">
                                        <i class="fas fa-cogs"> Start </i>
                                    </button>
						    </div>
                            <div class="col-md-2">
                                <div class="form-group" style="visibility:hidden;" id="opEdi_optionsViewBoxPanel">
                                    <div class="btn-group btn-group-toggle text-sm" data-toggle="buttons">
                                        <label class="btn bg-info active" for="opEdi_rdSinProcesar">
                                            <input class="chkMostrarCajas" type="radio" id="opEdi_rdSinProcesar" name="opEdi_rdViewBoxPanel" autocomplete="off" checked>
                                            Pending
                                        </label>
                                        <label class="btn bg-info" for="opEdi_rdProcesados">
                                            <input class="chkMostrarCajas" type="radio" id="opEdi_rdProcesados" name="opEdi_rdViewBoxPanel" autocomplete="off">
                                            Processed
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="btn-group" role='group' style="visibility:hidden;" id="opEdi_dashboardgroup" name="opEdi_dashboardgroup">
                                    <input type="text" class="form-control" id="opEdi_RangoIni" value = "" readonly >
                                    <input type="text" class="form-control" id="opEdi_RangoFin" value = "" readonly >
                                    <input type="text" class="form-control" id="opEdi_Folios" value = "" readonly >
                                </div>
                            </div>
                        </div>
                        </br>
                         <div class="row">
                            <div class="col-12 card card-outline card-dark" >
                                <div class="card-header">
                                    <h3 class="card-title " id="opEdi_Card_hClientName">Observaciones!</h3>
                                    <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    </div>
                                </div>
                                <div class="card-body collapse">
                                    <p id="opEdi_Card_Observaciones">Esta seccion le dara detalles sobre la Operacion del Importadro Selccionado, Presione el boton "Show" para iniciar.</p>
                                </div>
                            </div>
                        </div>
                        </br>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped" id="tbCajas" name="tbCajas" width="100%" cellspacing="0">	
                                <thead>
                                    <tr class="text-sm">
                                        <th>IMPORTER </th>
                                        <th>ENTRY </th>
									    <th>BOX NO </th>
									    <th>INVOICES </th>
									    <th>GROSS WEIGHT </th>
                                        <th>QUANTITY </th>
									    <th>AMOUNT </th>
                                        <th>SCAC </th>
                                        <th>MANUFACTURER </th>
                                        <th>CAT STATUS </th>
									    <th>START DATE </th>
                                        <th>Options </th>
								    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>	
			</div>

            <div class="modal fade" id="mldGenerarEdi" name="mldGenerarEdi" role="dialog">
                <div class="modal-dialog  modal-xl" role="dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="input-group">
                                <h3 class="modal-title" id="hedi">
                                    <label>Procesar EDI </label>
                                    <label id="mldGenerarEdi_Entry"></label>
                                </h3>
                            </div>
                        </div>
                        <div class="modal-body">
                            <form id="frmedi">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label > Date </label>
                                        <input type="date" class="form-control input-sm" id="txtmldGenerarEdi_Date" readonly >
                                    </div>
                                    <div class="col-sm-2">
                                        <label > Invoice </label>
                                        <input type="text" class="form-control input-sm opTaskListValidarCamposTexto" id="txtmldGenerarEdi_Invoice" value = ""  >
                                        <input type="hidden" class="form-control input-sm" id="idarchivo" value = "" readonly >
                                        <input type="hidden" class="form-control input-sm" id="archivo" value = "" readonly >
                                        <input type="hidden" class="form-control input-sm" id="mldGenerarEdi_cus_ref" value = "" readonly >
                                        
                                    </div>
                                    <div class="col-sm-2">
                                        <label >Moneda</label>
                                        <input type="text" class="form-control input-sm" id="txtmldGenerarEdi_Moneda" value = "" readonly >
                                    </div>
                                    <div class="col-sm-2">
                                        <label >TC</label>
                                        <input type="text" class="form-control input-sm" id="txtmldGenerarEdi_Tc" value = "" readonly >
                                    </div>
                                    <div class="col-sm-2">
                                        <label > Subtotal </label>
                                        <input type="text" class="form-control input-sm" id="txtmldGenerarEdi_Subtotal" value = "" readonly >
                                    </div>
                                    <div class="col-sm-2">
                                        <label > Total </label>
                                        <input type="text" class="form-control input-sm" id="txtmldGenerarEdi_Total" value = "" readonly >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label > Importer </label>
                                        <input type="text" class="form-control input-sm" id="txtmldGenerarEdi_Importer" value = "" data-claveimp="" data-tcat="" data-invasbr="" readonly >
                                    </div>
                                    <div class="col-sm-2">
                                        <label > Consigneer </label>
                                        <input type="text" class="form-control input-sm" id="txtmldGenerarEdi_Consigneer" value = "" data-clavecons="" readonly >
                                    </div>
                                    <div class="col-sm-2">
                                        <label > Customer </label>
                                        <input type="text" class="form-control input-sm" id="txtmldGenerarEdi_Customer" value = "" readonly >
                                    </div>

                                    <div class="col-sm-2">
                                        <label > SCAC</label>
                                        <select class="form-control LCHB_ValidarCamposVal" id="slcmldGenerarEdi_scac" name="slcmldGenerarEdi_scac">
                                            <option value=""> </option>
                                        </select>
                                        
                                    </div>

                                    <div class="col-sm-2">
                                        <label > Box </label>
                                        <input type="text" class="form-control input-sm" id="txtmldGenerarEdi_Caja" value = ""  data-krelfc="" readonly>
                                    </div>
                                    <div class="col-sm-1">
                                        <label > Port </label>
                                        <input type="text" class="form-control input-sm LCHB_ValidarCamposTexto" id="txtmldGenerarEdi_Puerto" value = ""  >
                                    </div>
                                    <div class="col-sm-1">
                                        <label > Location </label>
                                        <input type="text" class="form-control input-sm LCHB_ValidarCamposTexto" id="txtmldGenerarEdi_Locacion" value = ""  >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-1">
                                        <label > Weigth </label>
                                        <input type="text" class="form-control input-sm btnCalculaPeso" id="txtmldGenerarEdi_Peso" name="txtmldGenerarEdi_Peso" value = "" readonly="readonly">
                                    </div>
                                    <div class="col-sm-1">
                                        <label > Bultos </label>
                                        <input type="text" class="form-control input-sm btnCalculaBultos" id="txtmldGenerarEdi_Bultos" name="txtmldGenerarEdi_Bultos" value = "" readonly="readonly">
                                    </div>
                                    <div class="col-sm-1">
                                        <label > U.M. </label>
                                        <input type="text" class="form-control input-sm" id="txtmldGenerarEdi_Medida" value = "" readonly="readonly">
                                    </div>
                                    <div class="col-sm-1">
                                        <label > Accion </label>
                                        <button type="button" id="btnHabilitarUnidades" style="visibility:visible;" class="btn btn-warning btn-sm btnHabilitarUnidades"><i class="fas fa-pencil-alt"></i></button>
                                        <button type="button" id="btnActualizarUnidades" style="visibility:hidden;" class="btn btn-warning btn-sm btnActualizarUnidades" data-peso=""  data-bultos="" ><i class="fas fa-plus"></i></button>       
                                    </div>
                                    <div class="col-sm-1">
                                        <label > Origen</label>
                                        <input type="text" class="form-control input-sm LCHB_ValidarCamposTexto" id="txtmldGenerarEdi_Origen" value = "MX"  >
                                    </div>
                                    <div class="col-sm-1">
                                    <label > TIOP</label>
                                        <input type="text" class="form-control input-sm LCHB_ValidarCamposTexto" id="txtmldGenerarEdi_TIOP" value = "11"  >
                                    </div>
                                    <div class="col-sm-4">
                                        <label > Manufacturer</label>
                                        <select class="form-control LCHB_ValidarCamposVal" id="slcmldGenerarEdi_Manufactura" name="slcmldGenerarEdi_Manufactura" >
                                            <option value=""> </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label > Reference </label>
                                        <input type="text" class="form-control input-sm" id="txtmldGenerarEdi_Referencia" value = "" readonly >
                                    </div>
                                </div>
                                </BR>
                                <div class="row">
                                    <label > Products </label>
                                </div>
                                <div class="row">
                                    <div class="col-8"></div>
                                    <div class="form-check form-switch col-sm-2">
                                        <input class="form-check-input" type="checkbox" id="opEdi_mldGenerarEdi_chkAddExtLin">
                                        <label class="form-check-label" for="opEdi_mldGenerarEdi_chkAddExtLin"> Add Extra Line </label>
                                    </div>
                                    <div class="form-check form-switch col-sm-2">
                                        <input class="form-check-input" type="checkbox" id="opEdi_mldGenerarEdi_chkMgrProd">
                                        <label class="form-check-label" for="opEdi_mldGenerarEdi_chkMgrProd"> Merge Products </label>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="tblopEdiProcesado" name="tblopEdiProcesado"  class="table table-sm text-sm">
                                            <thead>
                                                <tr>
                                                    <th>Line </th>
                                                    <th>Factura</th>
                                                    <th>Article</th>
                                                    <th class="never">Bultos </th>
                                                    <th>Qty </th>
                                                    <th>G Wth </th>
                                                    <th>N Wth </th>
                                                    <th>Unit </th>
                                                    <th>Value Unit</th>
                                                    <th>Total Line </th>
                                                    <th>Descripcion </th>
                                                    <th class="never">HTS </th>
                                                    <th>SPI CODE </th>
                                                    <th>Acciones</th>
                                                    <th class="never">Qty2</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary btnGenerarEdi">Proceed</button>
                            <button type="button" class="btn btn-danger pull-left btnResetfrmedi" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal dialog" id="mldAddCatABI" name="mldAddCatABI" role="dialog">
                <div class="modal-dialog  modal-sl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="hedi">NUMERO DE  PARTE</h3>
                        </div>
                        <div class="modal-body">
                        
                            <div class="card mb-3">
                                <div class="card-header"> 
                                    <span id="div_headerDtMB">
                                    <i class="fas fa-cubes fa-fw"></i>
                                        Relacion Producto -  ABI
                                    </span>
                                    <div class="pull-right"></div>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row col-sm-12">
                                            <label > Numero de Parte </label>
                                            <input type="text" class="form-control input-sm" id="txtmldAddCatABI_NP" value = ""  >
                                            <input type="hidden" class="form-control input-sm" id="txtmldAddCatABI_IN" value = "" readonly >
                                        </div>
                                        <div class="row col-sm-12">
                                            <label > Descripcion </label>
                                            <input type="text" class="form-control input-sm" id="txtmldAddCatABI_DESC" value = ""  >
                                        </div>
                                        <div class="row col-sm-12">
                                            <div class="col-sm-8">
                                                <label > Catalogo ABI</label>
                                            </div>
                                            <div class="col-sm-4">
                                                <input class="form-check-input" type="checkbox" id="opEdi_mldAddCatABI_chkTMEC" Checked>
                                                <label class="form-check-label" for="opEdi_mldAddCatABI_chkTMEC"> Aplica TMEC </label>
                                            </div>
                                        </div>
                                        <div class="row col-sm-12">
                                            <div class="input-group">
                                                <select class="form-control" id="slcmldAddCatABI_CatalogoABI">
                                                    <option value=""> </option>
                                                </select>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-primary mldAddCatABI_btnRefreshCatABI"><i class="fas fa-sync-alt fa-fw"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    <form>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary btnRelProdABI">Relacionar</button>
                            <button type="button" class="btn btn-danger pull-left btnResetAddCatABI" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal dialog" id="mldUpdCatABI" name="mldUpdCatABI" role="dialog">
                <div class="modal-dialog  modal-sl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="mldUpdCatABI_hedi">NUMERO DE PARTE</h3>
                        </div>
                        <div class="modal-body">
                            <div class="card mb-3">
                                <div class="card-header"> 
                                    <span id="div_headerDtMB">
                                    <i class="fas fa-cubes fa-fw"></i>
                                        Relacion Producto -  ABI
                                    </span>
                                    <div class="pull-right"></div>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row col-sm-12">
                                            <label > Numero de Parte Cliente </label>
                                            <input type="text" class="form-control input-sm" id="mldUpdCatABI_txtNPCL" value = ""  readonly>
                                            <input type="hidden" class="form-control input-sm" id="mldUpdCatABI_NPCL" value = "" readonly >
                                            <input type="hidden" class="form-control input-sm" id="mldUpdCatABI_INX" value = "" readonly >
                                            <input type="hidden" class="form-control input-sm" id="mldUpdCatABI_DESC" value = "" readonly >
                                        </div>
                                        <div class="row col-sm-12">
                                            <label > Numero de Parte ABI </label>
                                            <input type="text" class="form-control input-sm" id="mldUpdCatABI_txtNPABI" value = "" readonly >
                                            <input type="hidden" class="form-control input-sm" id="mldUpdCatABI_hidNPABI" value = "" readonly >
                                            <input type="hidden" class="form-control input-sm" id="mldUpdCatABI_hidNPABI_DESC" value = "" readonly >
                                        </div>
                                        <div class="row col-sm-12">
                                            <div class="row col-sm-12">
                                                <div class="col-sm-8">
                                                    <label > Nuevo Numero de Parte</label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input class="form-check-input" type="checkbox" id="opEdi_mldUpdCatABI_chkTMEC">
                                                    <label class="form-check-label" for="opEdi_mldUpdCatABI_chkTMEC"> Aplica TMEC </label>
                                                </div>
                                            </div>

                                            <div class="input-group">
                                                <select class="form-control" id="mldUpdCatABI__SLCCatalogoABI">
                                                    <option value=""> </option>
                                                </select>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-secondary mldUpdCatABI_btnRefreshCatABI"><i class="fas fa-sync-alt fa-fw"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    <form>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary mldUpdCatABI_btnRelProdABI">Actualizar</button>
                            <button type="button" class="btn btn-danger pull-left mldUpdCatABI_btnResetUpdCatABI" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="opEdi_mdlUpFiles" name="opEdi_mdlUpFiles" role="dialog">
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
                                        <select id="opEdi_mdlUpFiles_slcPanelClientes" name="opEdi_mdlUpFiles_slcPanelClientes" class='form-control'>
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
                                        <input type="text" class="form-control" id="opEdi_mdlUpFiles_Path" value = "" readonly >
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
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlUpFiles_XML">XML</label>
                                        <input type="text" class="form-control" id="opEdi_mdlUpFiles_XML" value = "" readonly >
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlUpFiles_PDF">PDF</label>
                                        <input type="text" class="form-control" id="opEdi_mdlUpFiles_PDF" value = "" readonly >
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlUpFiles_XLS">XLS</label>
                                        <input type="text" class="form-control" id="opEdi_mdlUpFiles_XLS" value = "" readonly >
                                    </div>
                                </div>
                                <!--<div class="row">
                                    <div class="col-sm-12">
                                        <label> CUSTOM FILE</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label> FIELDS</label>
                                        <select id="opEdi_mdlUpFiles_slcCustomFields" name="opEdi_mdlUpFiles_slcCustomFields" class='form-control'>
                                            <option value="">OPTIONS</option>
                                        </select> 
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlUpFiles_PDF">PDF</label>
                                        <input type="text" class="form-control" id="opEdi_mdlUpFiles_PDF" value = "" readonly >
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlUpFiles_XLS">XLS</label>
                                        <input type="text" class="form-control" id="opEdi_mdlUpFiles_XLS" value = "" readonly >
                                    </div>
                                </div>-->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row col-12">
                                <div class="col-sm-6">
                                </div>
                                <div class="col-sm-4">
                                    <button type="button" class="btn btn-info" id="opEdi_mdlUpFiles_btnUpFiles">Upload Files</button>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-danger opEdi_mdlUpFiles_btnclosemdl" >Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal fade" id="opEdi_mdlPrintManifest" name="opEdi_mdlPrintManifest" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">PRINT MANIFEST</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="opEdi_mdlPrintManifest_rdByEntry">
                                            <input class="opEdi_mdlPrintManifest_chkShowOptionManifest" type="radio" id="opEdi_mdlPrintManifest_rdByEntry" name="opEdi_mdlPrintManifest_rdManifest" value=0 autocomplete="off" checked>
                                            MANIFEST BY ENTRY
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlPrintManifest_entry">ENTRY</label>
                                        <input type="text" class="form-control" id="opEdi_mdlPrintManifest_entry" value = "">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="opEdi_mdlPrintManifest_rdByRange">
                                            <input class="opEdi_mdlPrintManifest_chkShowOptionManifest" type="radio" id="opEdi_mdlPrintManifest_rdByRange" name="opEdi_mdlPrintManifest_rdManifest" value=1 autocomplete="off">
                                            MANIFEST BY ENTRY RANGE
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlPrintManifest_firts">FIRTS</label>
                                        <input type="text" class="form-control" id="opEdi_mdlPrintManifest_firts" value = "" readonly >
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlPrintManifest_last">LAST</label>
                                        <input type="text" class="form-control" id="opEdi_mdlPrintManifest_last" value = "" readonly >
                                    </div>
                                </div>
                                <br>
                                <!--<div class="row">
                                    <div class="col-sm-12">
                                        <label for="opEdi_mdlPrintManifest_rdByEntryImporter">
                                            <input class="opEdi_mdlPrintManifest_chkShowOptionManifest" type="radio" id="opEdi_mdlPrintManifest_rdByEntryImporter" name="opEdi_mdlPrintManifest_rdManifest" value=2 autocomplete="off">
                                            BY AUTOMATED
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <select id="opEdi_mdlPrintManifest_slcImporter" name="opEdi_mdlPrintManifest_slcImporter" class='form-control' disable>
                                            <option value="">AUTOMATED</option>
                                        </select> 
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class=" table-responsive">
                                        <table class="table table-sm table-striped" id="opEdi_mdlPrintManifest_tbEntriesImporter" name="opEdi_mdlPrintManifest_tbEntriesImporter" cellspacing="0" readonly>	
                                            <thead>
                                                <tr class="text-sm">
                                                    <th >ENTRY </th>
                                                    <th >CREATED </th>
                                                    <th >VALIDATED </th>
                                                    <th >SELECTED </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr >
                                                    <td >No data </td>
                                                    <td ></td>
                                                    <td ></td>
                                                    <td ></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row col-12">
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-warning" id="opEdi_mdlPrintManifest_btnUpdateManifestTable">Update Manifest</button>
                                </div>
                                <div class="col-sm-4">
                                    <button type="button" class="btn btn-info" id="opEdi_mdlPrintManifest_btnPrintFiles">Print Files</button>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-danger opEdi_mdlPrintManifest_btnclosemdl" >Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="opEdi_mdlSplitBox" name="opEdi_mdlSplitBox" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">
                                <i class="fas fa-truck-loading text-muted"></i>
                                <span id="opEdi_spCaja" class="text-muted"></span>
                                <input id="mdlSplitBox_IDIMPCOS" name="mdlSplitBox_IDIMPCOS" type="hidden" value="">
                                <input id="mdlSplitBox_krelfc" name="mdlSplitBox_krelfc" type="hidden" value="">
                                <input id="mdlSplitBox_caja" name="mdlSplitBox_caja" type="hidden" value="">
                            </h3>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="input-group mb-3 col-6">
                                    <select class="custom-select slcSplitBoxAcciones" id="opEdi_mdlSplitBox_slcAcciones">
                                        <option selected>Task</option>
                                        <option value="1">Change Box</option>
                                        <option value="2">Duplicate Box</option>
                                        <option value="3">Split Box</option>
                                    </select>
                                    
                                    
                                </div>
                                <div class="input-group-append col-6">
                                    <input type="text" class="form-control" name="opEdi_mdlSplitBox_txtSCAC" id="opEdi_mdlSplitBox_txtSCAC" placeholder="Write SCAC" readonly>
                                    <input type="text" class="form-control" name="opEdi_mdlSplitBox_txtBox" id="opEdi_mdlSplitBox_txtBox" placeholder="Write Box" readonly>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <table id="opEdi_tbSplitCajas" name="opEdi_tbSplitCajas"  class="table table-sm text-sm">
                                    <thead>
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Invoice Total</th>
                                            <th>Gross Weight</th>
                                            <th>QTY</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">
                                <i class="fas fa-times-circle fa-fw"></i>
                                Cerrar
                            </button>
                            <button class="btn btn-primary btnSplitCajasProcesar" type="button">
                            <i class="fas fa-check-circle fa-fw"></i>
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade modal_lv1" id="opEdi_mdlClientes" name="opEdi_mdlClientes" role="dialog">
                <div class="modal-dialog modal-xl" role="dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">AUMATED CONTAINERS</h4>
                            <div class="card-tools">
                                <button type="button" class="btn btn-secondary btn-md opEdi_showmdlCreateClients" style="margin-right: 5px;">
                                    <i class="fas fa-user-tie"></i>
                                </button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="form">
                                <div class="row">
                                    <div class=" table-responsive">
                                        <table class="table table-sm table-striped" id="opEdi_mdlClientes_tbClientes" name="opEdi_mdlClientes_tbClientes" cellspacing="0">	
                                            <thead>
                                                <tr class="text-sm">
                                                    <th >ABI KEY </th>
                                                    <th >AUTOMATED </th>
                                                    <th >PATH </th>
                                                    <th>FILER </th>
                                                    <th>RANGE </th>
                                                    <th>FIRST </th>
                                                    <th>LAST </th>
                                                    <th>NEXT </th>
                                                    <th>CREATED </th>
                                                    <th>Options </th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row col-12">
                                <div class="col-sm-11">
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-danger opEdi_mdlClientes_btnclosemdl" >CLOSE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal fade modal_lv3" id="opEdi_mdlCreateClientes" name="opEdi_mdlCreateClientes" role="dialog">
                <div class="modal-dialog" role="dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Create Clients</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form">
                                
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label>Client Name </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>ABI KEY</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="opEdi_mdlCreateClientes_txtClientName" value = "">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="opEdi_mdlCreateClientes_txtABIKeyClient" value = "">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label>Path </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Filer</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="opEdi_mdlCreateClientes_txtPath" value = "" >
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="opEdi_mdlCreateClientes_txtFiler" value = "" >
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label> Range </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlCreateClientes_slcRangeType">Range Type</label>
                                        <select class="custom-select" id="opEdi_mdlCreateClientes_slcRangeType">
                                            <option value="0">Auto</option>
                                            <option value="1">Manual</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlCreateClientes_txtRangeStart">Firts</label>
                                        <input type="text" class="form-control" id="opEdi_mdlCreateClientes_txtRangeStart" value = "" readonly >
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlCreateClientes_txtRangeLast">Last</label>
                                        <input type="text" class="form-control" id="opEdi_mdlCreateClientes_txtRangeLast" value = "" readonly >
                                    </div>
                                </div>

                                <br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlCreateClientes_txtObservaciones">Observaciones</label>
                                        <input type="text" class="form-control" id="opEdi_mdlCreateClientes_txtObservaciones" value = "" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row col-12">
                                <div class="col-sm-5">
                                </div>
                                <div class="col-sm-5">
                                    <button type="button" class="btn btn-info" id="opEdi_mdlCreateClientes_btnCreateCliente">Create Client</button>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-danger opEdi_mdlCreateClientes_btnclosemdl" >Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade modal_lv2" id="opEdi_mdlImporters" name="opEdi_mdlImporters" role="dialog">
                <div class="modal-dialog modal-xl" role="dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="opEdi_mdlImporters_ClienteName" name="opEdi_mdlImporters_ClienteName"></h4>
                            <div class="card-tools">
                                <button type="button" class="btn btn-secondary btn-md opEdi_showmdlUploadCatalog" style="margin-right: 5px;">
                                    <i class="fas fa-file-upload"></i>
                                </button>	
                                <button type="button" class="btn btn-secondary btn-md opEdi_showmdlCreateImporters" style="margin-right: 5px;">
                                    <i class="fas fa-user-cog"></i>
                                </button>	
                            </div>
                            <input type="hidden" class="form-control" id="opEdi_mdlImporters_hdIDEDCLI" value = "" >
                        </div>
                        <div class="modal-body">
                            <div class="form">
                                <div class="row">
                                    <div class="col-5">
                                        <div class=" table-responsive">
                                            <table class="table table-sm table-striped" id="opEdi_mdlImporters_tbImporters" name="opEdi_mdlImporters_tbImporters" cellspacing="0">	
                                                <thead>
                                                    <tr class="text-sm">
                                                        <th >CBRIS KEY </th>
                                                        <th >ABI KEY </th>
                                                        <th >IMPORTER </th>
                                                        <th>Options </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="form">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label class="input-group-text bg-secondary" id="opEdi_mdlImporters_lblImporterName" name="opEdi_mdlImporters_lblImporterName">Importer: </label>
                                                    <input type="hidden" class="form-control" id="opEdi_mdlImporters_hdIDEDIMPCOS" value = "" >
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <label>Consignee </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>ABI KEY</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="opEdi_mdlImporters_txtConsigName" value = "" readonly>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" id="opEdi_mdlImporters_txtABIKeyConsig" value = "" readonly >
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label> Default Values </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label class="input-group-text bg-secondary" for="opEdi_mdlImporters_txtManuf">Manuf</label>
                                                    <input type="text" class="form-control" id="opEdi_mdlImporters_txtManuf" value = "" readonly >
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="input-group-text bg-secondary" for="opEdi_mdlImporters_txtInvoice">Type </label>
                                                    <input type="text" class="form-control" id="opEdi_mdlImporters_txttInvoice" value = "XML" readonly >
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="input-group-text bg-secondary" for="opEdi_mdlImporters_txtCat">Cat</label>
                                                    <input type="text" class="form-control" id="opEdi_mdlImporters_txtCat" value = "1" readonly >
                                                </div>
                                            </div>
                                            </BR>
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <label class="input-group-text bg-secondary" for="opEdi_mdlImporters_txtUOM">UOM</label>
                                                    <input type="text" class="form-control" id="opEdi_mdlImporters_txtUOM" value = "" readonly >
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="input-group-text bg-secondary" for="opEdi_mdlImporters_txtOrigin">Origin</label>
                                                    <input type="text" class="form-control" id="opEdi_mdlImporters_txtOrigin" value = "" readonly >
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="input-group-text bg-secondary" for="opEdi_mdlImporters_txtPort">Port</label>
                                                    <input type="text" class="form-control" id="opEdi_mdlImporters_txtPort" value = "" readonly >
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="input-group-text bg-secondary" for="opEdi_mdlImporters_txtLocation">Location</label>
                                                    <input type="text" class="form-control" id="opEdi_mdlImporters_txtLocation" value = "" readonly >
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="input-group-text bg-secondary" for="opEdi_mdlImporters_txtTypeOP">TypeOP</label>
                                                    <input type="text" class="form-control" id="opEdi_mdlImporters_txtTypeOP" value = "" readonly >
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label> XML Configuration</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">

                                                <div class=" table-responsive">
                                                    <table class="table table-sm table-striped" id="opEdi_mdlImporters_tbImporterXML" name="opEdi_mdlImporters_tbImporterXML" cellspacing="0">	
                                                        <thead>
                                                            <tr class="text-sm">
                                                                <th> Emisor </th>
                                                                <th> Receptor </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>

                                                </div>
                                            </div>
                                            </BR>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label> Options</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-check form-switch col-sm-6">
                                                    <input class="form-check-input" type="checkbox" id="opEdi_mdlImporters_chkOpByInv" disabled>
                                                    <label class="form-check-label" for="opEdi_mdlImporters_chkOpByInv"> Create Operation By Invoice </label>
                                                </div>
                                                <div class="form-check form-switch col-sm-6">
                                                    <input class="form-check-input" type="checkbox" id="opEdi_mdlImporters_chkManNotifications" disabled>
                                                    <label class="form-check-label" for="opEdi_mdlImporters_chkManNotifications"> Enable Manifest Nofications </label>
                                                </div>
                                            </div> 
                                            <div class="row">
                                                <div class="form-check form-switch col-sm-6">
                                                    <input class="form-check-input" type="checkbox" id="opEdi_mdlImporters_chkOpWhtBox" disabled>
                                                    <label class="form-check-label" for="opEdi_mdlImporters_chkOpWhtBox"> Operations Without Box </label>
                                                </div>
                                                <div class="form-check form-switch col-sm-6">
                                                    <input class="form-check-input" type="checkbox" id="opEdi_mdlImporters_chkInvAsBR" disabled>
                                                    <label class="form-check-label" for="opEdi_mdlImporters_chkInvAsBR"> Use Invoce as Broker Reference </label>
                                                </div>
                                            </div>   
                                            </BR>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row col-12">
                                <div class="col-sm-11">
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-danger opEdi_mdlImporters_btnclosemdl" >Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>      

            <div class="modal fade modal_lv3" id="opEdi_mdlCreateImporters" name="opEdi_mdlCreateImporters" role="dialog">
                <div class="modal-dialog" role="dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Create Importer</h4>
                            <div class="card-tools">
                                <button type="button" class="btn btn-secondary btn-md opEdi_showmdlCopyImporterData" style="margin-right: 5px;">
                                <i class="fas fa-clone"></i>
                                </button>		
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="form">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label>Import Name </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>ABI KEY</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="opEdi_mdlCreateImporters_txtImportName" value = "">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="opEdi_mdlCreateImporters_txtABIKeyImport" value = "">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label>Consignee </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>ABI KEY</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="opEdi_mdlCreateImporters_txtConsigName" value = "" >
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="opEdi_mdlCreateImporters_txtABIKeyConsig" value = "" >
                                    </div>
                                </div>
                                </BR>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label> Default Values </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlCreateImporters_txtManuf">Manuf</label>
                                        <input type="text" class="form-control" id="opEdi_mdlCreateImporters_txtManuf" value = "" >
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlCreateImporters_txtTInv">Type </label>
                                        <input type="text" class="form-control" id="opEdi_mdlCreateImporters_txtTInv" value = "XML" readonly >
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlCreateImporters_slcTypeOfCat">Cat</label>
                                        <!--<input type="text" class="form-control" id="opEdi_mdlCreateImporters_txtCat" value = "1" readonly >-->
                                        <select class="form-control" id="opEdi_mdlCreateImporters_slcTypeOfCat">
                                            <option value=""> Select a type of Catalog</option>    
                                            <option value="1"> 1 - By Importer</option>
                                            <option value="2"> 2 - By Importer and Consignee</option>
                                        </select>
                                    </div>
                                </div>
                                </BR>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlCreateImporters_txtUOM">UOM</label>
                                        <input type="text" class="form-control" id="opEdi_mdlCreateImporters_txtUOM" value = "">
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlCreateImporters_txtOrigin">Origin</label>
                                        <input type="text" class="form-control" id="opEdi_mdlCreateImporters_txtOrigin" value = "" >
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlCreateImporters_txtPort">Port</label>
                                        <input type="text" class="form-control" id="opEdi_mdlCreateImporters_txtPort" value = "" >
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlCreateImporters_txtLocation">Location</label>
                                        <input type="text" class="form-control" id="opEdi_mdlCreateImporters_txtLocation" value = "">
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlCreateImporters_txtTypeOp">TypeOP</label>
                                        <input type="text" class="form-control" id="opEdi_mdlCreateImporters_txtTypeOp" value = "">
                                    </div>
                                </div>
                                </BR>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label> Add XML Configuration </label>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="input-group-text bg-secondary" for="opEdi_mdlCreateImporters_txtEmisor">Emisor Name</label>
                                            <input type="text" class="form-control" id="opEdi_mdlCreateImporters_txtEmisor" value = "" >
                                        </div>
                                        <div class="col-sm-5">
                                            <label class="input-group-text bg-secondary" for="opEdi_mdlCreateImporters_txtReceptor">Receptor Name</label>
                                            <input type="text" class="form-control" id="opEdi_mdlCreateImporters_txtReceptor" value = "" >
                                        </div>
                                        <div class='col-sm-2'>
                                            <button class="btn btn-secondary" type="button" id="opEdi_mdlCreateImporters_btnAddXML" name="opEdi_mdlCreateImporters_btnAddXML">
                                                <i class="fa fa-plus" aria-hidden="true"></i>Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <table class="table" id="opEdi_mdlCreateImporters_tblnewxml">
                                    <tr>
                                        <th colspan="3">XML</th>
                                    </tr>
                                </table>
                                </BR>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label> Options</label>
                                    </div>
                                </div> 
                                </BR>
                                <div class="row">
                                    <div class="form-check form-switch col-sm-6">
                                        <input class="form-check-input" type="checkbox" id="opEdi_mdlCreateImporters_chkOpByInv">
                                        <label class="form-check-label" for="opEdi_mdlCreateImporters_chkOpByInv"> Create Operation By Invoice </label>
                                    </div>
                                    <div class="form-check form-switch col-sm-6">
                                        <input class="form-check-input" type="checkbox" id="opEdi_mdlCreateImporters_chkManNotifications">
                                        <label class="form-check-label" for="opEdi_mdlCreateImporters_chkManNotifications"> Enable Manifest Nofications </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-check form-switch col-sm-6">
                                        <input class="form-check-input" type="checkbox" id="opEdi_mdlCreateImporters_chkOpWhtBox">
                                        <label class="form-check-label" for="opEdi_mdlCreateImporters_chkOpWhtBox"> Operations Without Box </label>
                                    </div>
                                    <div class="form-check form-switch col-sm-6">
                                        <input class="form-check-input" type="checkbox" id="opEdi_mdlCreateImporters_chkInvAsBR">
                                        <label class="form-check-label" for="opEdi_mdlCreateImporters_chkInvAsBR"> Use Invoce as Broker Reference </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row col-12">
                                <div class="col-sm-5">
                                </div>
                                <div class="col-sm-5">
                                    <button type="button" class="btn btn-info" id="opEdi_mdlCreateImporters_btnCreateImporter">Create Importer</button>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-danger opEdi_mdlCreateImporters_btnclosemdl" >Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade modal_lv4" id="opEdi_mdlCopyImporterData" name="opEdi_mdlCopyImporterData" role="dialog">
                <div class="modal-dialog modal-lg" role="dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Copy Data Importer</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-sm table-striped" id="opEdi_mdlCopyImporterData_tbImporters" name="opEdi_mdlCopyImporterData_tbImporters" cellspacing="0">	
                                        <thead>
                                            <tr class="text-sm">
                                                <th >IMPORTER KEY</th>
                                                <th >NAME IN CBRIS</th>
                                                <th >CONSIGNEE NAME</th>
                                                <th>Options </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row col-12">
                                <div class="col-sm-5">
                                </div>
                                <div class="col-sm-5">
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-secondary opEdi_mdlCopyImporterData_btnclosemdl" >Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade modal_lv3" id="opEdi_mdlUploadCatalog" name="opEdi_mdlUploadCatalog" role="dialog">
                <div class="modal-dialog" role="dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Upload Catalog</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label class="input-group-text bg-secondary" for="opEdi_mdlUploadCatalog_file">Select File</label>
                                        <input type="file"  id="opEdi_mdlUploadCatalog_file" name="opEdi_mdlUploadCatalog_File">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row col-12">
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-secondary" id="opEdi_mdlUploadCatalog_btnDownTemplate">Download Template</button>
                                </div>
                                <div class="col-sm-4">
                                    <button type="button" class="btn btn-info" id="opEdi_mdlUploadCatalog_btnUploadFile">Upload</button>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-danger opEdi_mdlUploadCatalog_btnclosemdl" >Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal dialog" id="mldAddTC" name="mldAddTC" role="dialog">
                <div class="modal-dialog  modal-sl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">ADD EXCHANGE RATE</h3>
                        </div>
                        <div class="modal-body">
                            <div class="card mb-3">
                                <div class="card-header"> 
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row col-sm-12">
                                            <label > CURRENCY </label>
                                            <select class="form-control" id="mldAddTC_slcCurrency">
                                                <option value="" SELECTED>SELECT CURRENCY</option>
                                                <option value="MX">MX</option>
                                                <option value="EUR">EUR</option>
                                            </select>
                                        </div>
                                        <div class="row col-sm-12">
                                            <label > USD VALUE </label>
                                            <input type="number" class="form-control input-sm" id="mldAddTC_txtTC" value = ""  >
                                        </div>
                                    <form>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary mldAddTC_btnAddExchRate">ADD</button>
                            <button type="button" class="btn btn-danger pull-left mldAddTC_btnResetExchRate" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>

		</div>
	</div>
</body>


<script src="views/dist/js/opEdi.js"></script>
