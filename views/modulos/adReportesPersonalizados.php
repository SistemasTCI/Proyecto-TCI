<body id="bdadReportesPersonalizados">
    <div class="content-wrapper" >
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb" >
                            <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                            <li class="breadcrumb-item active">Reportes Personalizados</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card col-12 h-100">
                        <div class="card-header border-0">
                            <h3 class="card-title"><i class="fas fa-file-alt fa-fw text-muted"></i> Panel de  Reportes Personalizados</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table id="adReportesPersonalizados_tblReportes" class="table table-striped table-valign-middle" style="text-align:center;font-size:12px">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Reporte</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>                                    
                                    <tr>
                                        <th>1</th>
                                        <th>Plantilla Facturas Mahle Behr</th>
                                        <th>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-dark adReportesPersonalizados_ViewmdlFacturaMahle" id="'.$x.'" name="'.$x.'"  data-reporte='.$ID.' data-nombre="'.$Reporte.'" data-stp='.$Stp.'>
                                                    <i class="fas fa-mail-bulk fa-fw"></i>
                                                </button> 
                                                <button type="button" class="btn btn-sm btn-dark" data-toggle="tooltip" data-placement="top" title="PLantilla Mahle">
                                                    <i class="fas fa-info-circle fa-fw"></i>
                                                </button>  
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>2</th>
                                        <th>Plantilla Facturas Mahle Componentes</th>
                                        <th>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-dark adReportesPersonalizados_ViewmdlFacturaMahleC" id="'.$x.'" name="'.$x.'"  data-reporte='.$ID.' data-nombre="'.$Reporte.'" data-stp='.$Stp.'>
                                                    <i class="fas fa-mail-bulk fa-fw"></i>
                                                </button> 
                                                <button type="button" class="btn btn-sm btn-dark" data-toggle="tooltip" data-placement="top" title="Plantilla Mahle Componentes">
                                                    <i class="fas fa-info-circle fa-fw"></i>
                                                </button>  
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>3</th>
                                        <th>Carta Porte</th>
                                        <th>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-dark adReportesPersonalizados_ViewmdlCartaPorte" id="'.$x.'" name="'.$x.'"  data-reporte='.$ID.' data-nombre="'.$Reporte.'" data-stp='.$Stp.'>
                                                    <i class="fas fa-mail-bulk fa-fw"></i>
                                                </button> 
                                                <button type="button" class="btn btn-sm btn-dark" data-toggle="tooltip" data-placement="top" title="PLantilla Carta Porte">
                                                    <i class="fas fa-info-circle fa-fw"></i>
                                                </button>  
                                            </div>
                                        </th>
                                    </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="adReportesPersonalizados_FacturaMahle" name="adReportesPersonalizados_FacturaMahle"  style="z-index: 1600;" role="dialog">
        <div class="modal-dialog modal-lg" >
        	<div class="modal-content">
            	<div class="modal-header">
					<h5 class="modal-title">
						<i class="fas fa-envelope fa-fw"></i> 
						<span>Correo Factura Mahle</span>
					</h5>
            	</div>
				<div class="modal-body">
					<form id="mdlFacturaMahle_frmEnviarCorreo" name="mdlFacturaMahle_frmEnviarCorreo" method="POST" enctype="multipart/form-data">
						<div class="col-12">
							<div class="form-group">
								<label>Trafico</label>
								<input type="text" class="form-control form-upper" aria-label="" name="mdlFacturaMahle_txtTrafico" id="mdlFacturaMahle_txtTrafico" value="">
							</div>
						</div>
                    
					</form>
				</div>
				<div class="modal-footer">
					<div class="row col-12">
						<div class="col-2">
						</div>
						<div class="col-6"></div>
						<div class="col-4">
							<button class="btn btn-secondary adReportesPersonalizados_closemdlFacturaMahle" type="button" data-dismiss="modal">
								<i class="fas fa-times-circle fa-fw"></i>
								Close
							</button> 
							<button class="btn btn-primary adReportesPersonalizados_btnEnviarTrafico" type="button" id="mdlFacturaMahle_btnEnviarTrafico">
							<i class="fas fa-save"></i>
								Procesar
							</button>
						</div>
					</div>
            	</div>
          	</div>
        </div>
    </div>
    
    <div class="modal fade" id="adReportesPersonalizados_FacturaMahleC" name="adReportesPersonalizados_FacturaMahleC"  style="z-index: 1600;" role="dialog">
        <div class="modal-dialog modal-lg" >
        	<div class="modal-content">
            	<div class="modal-header">
					<h5 class="modal-title">
						<i class="fas fa-envelope fa-fw"></i> 
						<span>Factura Mahle</span>
					</h5>
            	</div>
				<div class="modal-body">
					<form id="mdlFacturaMahle_frmEnviarCorreoC" name="mdlFacturaMahle_frmEnviarCorreoC" method="POST" enctype="multipart/form-data">
						<div class="col-12">
							<div class="form-group">
								<label>Trafico</label>
								<input type="text" class="form-control form-upper" aria-label="" name="mdlFacturaMahle_txtTraficoC" id="mdlFacturaMahle_txtTraficoC" value="">
							</div>
						</div>
                    
					</form>
				</div>
				<div class="modal-footer">
					<div class="row col-12">
						<div class="col-2">
						</div>
						<div class="col-6"></div>
						<div class="col-4">
							<button class="btn btn-secondary adReportesPersonalizados_closemdlFacturaMahleC" type="button" data-dismiss="modal">
								<i class="fas fa-times-circle fa-fw"></i>
								Close
							</button> 
							<button class="btn btn-primary adReportesPersonalizados_btnEnviarTraficoC" type="button" id="mdlFacturaMahle_btnEnviarTraficoC">
							<i class="fas fa-save"></i>
								Procesar
							</button>
						</div>
					</div>
            	</div>
          	</div>
        </div>
    </div>

    <div class="modal fade" id="adReportesPersonalizados_CartaPorte" name="adReportesPersonalizados_CartaPorte"  style="z-index: 1600;" role="dialog">
        <div class="modal-dialog modal-lg" >
        	<div class="modal-content">
            	<div class="modal-header">
					<h5 class="modal-title">
						<i class="fas fa-envelope fa-fw"></i> 
						<span>Descarga Plantilla Carta Porte</span>
					</h5>
            	</div>
				<div class="modal-body">
					<form id="mdlCartaPorte_frmEnviarCorreo" name="mdlCartaPorte_frmEnviarCorreo" method="POST" enctype="multipart/form-data">
						<div class="col-12">
							<div class="form-group">
								<label>Orden de Carga</label>
								<input type="text" class="form-control form-upper" aria-label="" name="mdlCartaPorte_txtOrdenCarga" id="mdlCartaPorte_txtOrdenCarga" value="">
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<div class="row col-12">
						<div class="col-2">
						</div>
						<div class="col-6"></div>
						<div class="col-4">
							<button class="btn btn-secondary adReportesPersonalizados_closemdlCartaPorte" type="button" data-dismiss="modal">
								<i class="fas fa-times-circle fa-fw"></i>
								Close
							</button> 
							<button class="btn btn-primary adReportesPersonalizados_btnEnviarCartaPorte" type="button" id="mdlCartaPorte_btnEnviarCartaPorte">
							<i class="fas fa-save"></i>
                                Descargar
							</button>
						</div>
					</div>
            	</div>
          	</div>
        </div>
    </div> 

</body>