<body onload="adReportes_js_Inicializar('<?php ECHO $_SESSION['ModuloReportes'];?>')" id="bdadReportes">
    <div class="content-wrapper" >
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb" >
                            <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                            <li class="breadcrumb-item active">Reportes</li>
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
                            <h3 class="card-title"><i class="fas fa-file-alt fa-fw text-muted"></i> Panel de  Reportes</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table id="adReportes_tblReportes" class="table table-striped table-valign-middle" style="text-align:center;font-size:12px">
                                <thead>
                                    <tr>
                                    <th>ID</th>
                                    <th>Reporte</th>
                                    <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="adReportes_mdlScheduleMail" name="adReportes_mdlScheduleMail"  style="z-index: 1600;" role="dialog">
        <div class="modal-dialog modal-lg" >
        	<div class="modal-content">
            	<div class="modal-header">
					<h5 class="modal-title" id="mdlScheduleMail_ht">
						<i class="fas fa-envelope fa-fw"></i> 
						<span id="mdlScheduleMail_Imp">Mail Information</span>
					</h5>
            	</div>
				<div class="modal-body">
					<form id="mdlScheduleMail_frmEnviarCorreo" name="mdlScheduleMail_frmEnviarCorreo" method="POST" enctype="multipart/form-data">
						<div class="col-12" id="mdlScheduleMail_div_importer_email">
							<div>
								<label>Client:</label>
								<select id="mdlScheduleMail_slcImportadores" name="mdlScheduleMail_slcImportadores" class='form-control'>
									<option value="">Client</option>
								</select> 
							</div>
							<div class="form-group">
								<label>Subject:</label>
								<input type="text" class="form-control form-upper" aria-label="" name="mdlScheduleMail_txtEmailSubject" id="mdlScheduleMail_txtEmailSubject" value="">
							</div>
							<div class="input-group">
								<input type="text" class="form-control" aria-label="" value="Data Attach:" readonly="">
								<span class="input-group-addon">
								<input type="checkbox" aria-label="Checkbox" name="mdlScheduleMail_chkEmailAttBody" id="mdlScheduleMail_chkEmailAttBody">
								Body
								</span>
								<span class="input-group-addon">
								<input type="checkbox" aria-label="Checkbox" name="mdlScheduleMail_chkEmailAttExcel" id="mdlScheduleMail_chkEmailAttExcel" checked>
								Excel
								</span>
							</div>
							<div class="form-group">
								<label>Body:</label>
								<textarea class="form-control form-upper" name="mdlScheduleMail_txtEmailBody" id="mdlScheduleMail_txtEmailBody">Cuerpo del Correo</textarea>
							</div>
							<label>Add Mail:</label>
							<div class="input-group">
								<select class="custom-select" id="mdlScheduleMail_slc_type_add">
								<option value="1" name="To">To</option>
								<option value="2" name="Cc">Cc</option>
								</select>
								<input type="text" class="form-control" aria-label="" name="mdlScheduleMail_txtMailAdd" id="mdlScheduleMail_txtMailAdd">
								<button class="btn btn-secondary" type="button" id="mdlScheduleMail_btnMailAdd" name="mdlScheduleMail_btnMailAdd">
								<i class="fa fa-plus" aria-hidden="true"></i>
								Add
								</button>
							</div>
						</div>
						<table class="table" id="mdlScheduleMail_tbl_importer_email_pendientes">
							<tr>
								<th colspan="3">Email Contacts New</th>
							</tr>
						</table>
						<div class="form-group col-12">
							<table class="table table-sm table-inverse" id="mdlScheduleMail_tblListContacts">
								<thead>
									<tr>
										<th>Type</th>
										<th>Email</th>
										<th>Fecha Creacion</th>
										<th></th>
									</tr>
								</thead>
							</table>
						</div>
					</form>
					<div class="col-12" id="mdlScheduleMail_div_importer_email_contactos">
					</div>
				</div>
				<div class="modal-footer">
					<div class="row col-12">
						<div class="col-2">
							<button class="btn btn-info adReportes_SendMail" type="button" id="mdlScheduleMail_btnsendmail">
								<i class="far fa-paper-plane"></i>
								Send
							</button>
						</div>
						<div class="col-6"></div>
						<div class="col-4">
							<button class="btn btn-secondary adReportes_mdlScheduleMail" type="button" data-dismiss="modal">
								<i class="fas fa-times-circle fa-fw"></i>
								Close
							</button> 
							<button class="btn btn-primary adReportes_SaveMailInformation" type="button" id="mdlScheduleMail_btnsave">
							<i class="fas fa-save"></i>
								Save
							</button>
						</div>
					</div>
            	</div>
          	</div>
        </div>
    </div>
	<div class="modal fade" id="adReportes_mdlFrecuencyMail" name="adReportes_mdlFrecuencyMail"  style="z-index: 1600;" role="dialog">
        <div class="modal-dialog modal-lg" >
        	<div class="modal-content">
            	<div class="modal-header">
					<h5 class="modal-title">
						<i class="fas fa-envelope fa-fw"></i> 
						<span id="mdlFrecuencyMail_hdd">Mail Scheduling</span>
					</h5>
            	</div>
				<div class="modal-body">
						<div class="card">
							<div class="card-header border-0">
								<h3 class="card-title">FILTER RULES</h3>
							</div>
							<div class="card-body with-border">
								<form id="adReportes_frmFilterRules">
                        
								</form>	
							</div>
						</div>
					<form id="mdlScheduleMail_frmFrecuencyMail" name="mdlScheduleMail_FrecuencyMail">
						<div class="card">
							<div class="card-header border-0">
								<h3 class="card-title">FRECUENCY</h3>
							</div>
							<div class="card-body with-border">
								<div class="row">
									<div class="col-1">
									</div>
									<div class="col-5">
										<div class="form-check">
											<input class="form-check-input mdlFrecuency_Options" type="radio" name="mdlScheduleMail_rdOption" id="mdlScheduleMail_rdOptionWK" value="week" checked>
											<strong><label class="form-check-label" for="mdlScheduleMail_rdOptionWK">Days of the Week</label></strong>
										</div>
										<div id="mdlScheduleMail_WKContainer">
											<div class="row">
												<div class="col-1">
													<br>
												</div>
												<div class="col-3">
													<div class="form-check form-switch">
														<input class="form-check-input mdlFrecuencyMail_dias" type="checkbox" id="mdlScheduleMail_chk0" value="0">
														<label class="form-check-label" for="mdlScheduleMail_chk0">Monday</label>
													</div>
													<div class="form-check form-switch">
														<input class="form-check-input mdlFrecuencyMail_dias" type="checkbox" id="mdlScheduleMail_chk1" value="1">
														<label class="form-check-label" for="mdlScheduleMail_chk1">Tuesday</label>
													</div>
													<div class="form-check form-switch">
														<input class="form-check-input mdlFrecuencyMail_dias" type="checkbox" id="mdlScheduleMail_chk2" value="2">
														<label class="form-check-label" for="mdlScheduleMail_chk2">Wednesday</label>
													</div>
													<div class="form-check form-switch">
														<input class="form-check-input mdlFrecuencyMail_dias" type="checkbox" id="mdlScheduleMail_chk3" value="3">
														<label class="form-check-label" for="mdlScheduleMail_chk3">Thursday</label>
													</div>
													<div class="form-check form-switch">
														<input class="form-check-input mdlFrecuencyMail_dias" type="checkbox" id="mdlScheduleMail_chk4" value="4">
														<label class="form-check-label" for="mdlScheduleMail_chk4">Friday</label>
													</div>
													<div class="form-check form-switch">
														<input class="form-check-input mdlFrecuencyMail_dias" type="checkbox" id="mdlScheduleMail_chk5" value="5">
														<label class="form-check-label" for="mdlScheduleMail_chk5">Saturday</label>
													</div>
													<div class="form-check form-switch">
														<input class="form-check-input mdlFrecuencyMail_dias" type="checkbox" id="mdlScheduleMail_chk6" value="6">
														<label class="form-check-label" for="mdlScheduleMail_chk6">Sunday</label>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-check">
												<input class="form-check-input mdlFrecuency_Options" type="radio" name="mdlScheduleMail_rdOption" id="mdlScheduleMail_rdOptionOD" value="demand">
												<strong><label class="form-check-label" for="mdlScheduleMail_rdOptionOD">On a specific date/time</label></strong>
											</div>
										</div>
										<div id="mdlScheduleMail_ODContainer">
											<div class="row">
												<br>
											</div>
											<div class="row input-group">
												<div class="col-6">
													<label>Month Day</label>
													<input class="form-control" type="number" id="mdlScheduleMail_MonthDay" min="1" max="31" step="1" disabled>
												</div>
												<div class="col-6">
													<label>Hours</label>
													<input class="form-control" type="number" id="mdlScheduleMail_MonthDayHour" min="1" max="24" step="1" disabled>
												</div>
											</div>
										</div>
									</div>
									<div class="col-5">
										<div class="row">
											<div class="input-group col-2">
												<label>Hours</label>
											</div>
											<div class="input-group col-4">
												<input class="form-control" type="number" min="1" max="24" step="1" style="width: 50px;" id="mdlFrecuencyMail_txthour" name="mdlFrecuencyMail_txthour">
												<button class="btn btn-secondary btn-sm " type="button" id="mdlFrecuencyMail_btnTimeAdd" name="mdlFrecuencyMail_btnTimeAdd">
													<i class="fas fa-plus"></i>
												</button>
											</div>
										</div>
										<div class="row">
											<table class="table table-striped table-valign-middle" id="mdlFrecuencyMail_tbHourList" name="mdlFrecuencyMail_tbHourList">
												<thead>
													<tr>
														<th>ID</th>
														<th>Hour</th>
														<th>Eliminar</th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
									<div class="col-1">
									</div>
								</div>	
							</div>
						</div>				
					</form>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary adReportes_btnResetmldmdlFrecuencyMail" type="button" data-dismiss="modal">
						<i class="fas fa-times-circle fa-fw"></i>
						Close
					</button>
					<button class="btn btn-primary" type="button" id="mdlFrecuencyMail_btnsave">
					<i class="fas fa-save"></i>
						Save
					</button>
            	</div>
          	</div>
        </div>
    </div>
	<div class="modal dialog" id="adReportes_mdlListScheduleReport" name="adReportes_mdlListScheduleReport"  style="z-index: 1400;" role="dialog">
		<div class="modal-dialog modal-lg" >
			<div class="modal-content">
				<div class="modal-body">
					<div class="card">
						<div class="card-header border-0">
							<h3 class="card-title">Report Schedule with Automatic Send</h3>
							<div class="card-tools">
								<a href="#" class="btn btn-tool btn-sm">
									<buttom class="btn btn-secundary adReportes_ViewmdlScheduleMail"><i class="fas fa-plus-square"></i> </buttom>
								</a>
							</div>
						</div>
						<div class="card-body with-border">
							<div>
								<label id="mdlListScheduleReport_ReportName" name="mdlListScheduleReport_ReportName"></label>
								<input type="hidden" name="mdlListScheduleReport_ReportID" id="mdlListScheduleReport_ReportID">
								<input type="hidden" name="mdlScheduleMail_hdd_id" id="mdlScheduleMail_hdd_id">
								<input type="hidden" name="mdlScheduleMail_KEY_RELM_C" id="mdlScheduleMail_KEY_RELM_C">
							</div>	
							<div class="table-responsive">
								<table class="table table-sm table-striped" id="tbListScheduleReport" name="tbListScheduleReport" width="100%" cellspacing="0">	
									<thead>
										<tr>
											<th>Client</th>
											<th>Title</th>
											<th>Fecha Creacion</th>
											<th>Acciones</th>
										</tr>
									</thead>
								</table>
							</div>		
						</div>
					</div>			
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-dark adReportes_btnResetmldDescargar" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
    <div class="modal fade" id="adReportes_mdlDescargarReporte" name="adReportes_mdlDescargarReporte" role="dialog">
		<div class="modal-dialog modal-md" role="dialog">
			<div class="modal-content">
                <div class="modal-header">
					<h5 class="modal-title">
						<i class="fas fa-envelope fa-fw"></i> 
						<span id="adReportes_spReporteTitulo"></span>
					</h5>
            	</div>
				<div class="modal-body">
					<form id="adReportes_frmDescargarReportes">
                        
                    </form>
				</div>
                <div class="modal-footer">
					<button class="btn btn-secondary adReportes_btnResetmldDescargar" type="button" data-dismiss="modal">
						<i class="fas fa-times-circle fa-fw"></i>
						Cancelar
					</button>
					<button class="btn btn-primary adReportes_btnDescargar" type="button">
						<i class="fas fa-arrow-down"></i>
						Descargar
					</button>
            	</div>
			</div>
		</div>
	</div>  
</body>