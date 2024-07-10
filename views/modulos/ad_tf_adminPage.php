<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
    <link href="/views/dist/css/ad_tf_style.css" rel="stylesheet"> <!-- Archivo CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"> <!-- Font Awesome -->
</head>
<body>
<?php
$estado2 = 'Activo'; // Ejemplo de estado
$estado = 'Inactivo'; // Ejemplo de estado diferente
$estado3 = 'En Validacion'; // Ejemplo de estado diferente
$estado4 = 'Sin Contactos'; // Ejemplo de estado diferente
$estado5 = 'Expirado'; // Ejemplo de estado diferente
$claseEstado = estadoTarifario($estado); // Llamada a la función que devuelve la clase CSS basada en el estado del tarifario
$claseEstado2 = estadoTarifario($estado2); // Llamada a la función que devuelve la clase CSS basada en el estado del tarifario
$claseEstado3 = estadoTarifario($estado3); // Llamada a la función que devuelve la clase CSS basada en el estado del tarifario
$claseEstado4 = estadoTarifario($estado4); // Llamada a la función que devuelve la clase CSS basada en el estado del tarifario
$claseEstado5 = estadoTarifario($estado5); // Llamada a la función que devuelve la clase CSS basada en el estado del tarifario
// Función que devuelve la clase CSS basada en el estado del tarifario
function estadoTarifario($estado) {
    switch ($estado) {
        case 'Activo':
            return 'estado-activo';
        case 'Inactivo':
            return 'estado-inactivo';
        case 'En Validacion':
            return 'estado-validacion';
        case 'Sin Contactos';
            return 'estado-sin-contactos';
        case 'Expirado';
            return 'estado-expirado';
        default:
            return 'estado-desconocido';
    }
}
$infocliente = "Aquí va la información del cliente"; // Reemplaza esto con la información real del cliente
$estado1 = "Aquí va el estado"; // Reemplaza esto con el estado real del tarifario
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> <!-- jQuery -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> <!-- Bootstrap JS -->

<!-- Tabla Principal-->
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light w-100">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Tramitaciones</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <button class="btn btn-light my-2 my-sm-0" type="submit"><i class="fas fa-user"></i></button>
                            <button id="redirectButton" class="btn btn-light my-2 my-sm-0" type="submit"><i class="fas fa-share"></i></button>
                            <button class="btn btn-light my-2 my-sm-0" type="submit" data-toggle="modal" data-target="#downModal"><i class="fas fa-bars"></i></button>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Barra de búsqueda -->
    <form class="form-inline my-2 my-lg-0 mr-auto">
        <div>
            <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Buscar" style="width: 300px;">
            <button class="btn btn-light my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
            <button class="btn btn-light my-2 my-sm-0" type="submit"><i class="fas fa-print"></i> Template</button>
        </div>
    </form>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Número de Identificación Fiscal</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Tarifario</th>
                    <th>Fecha de Actualización</th>
                    <th>Fecha de Caducidad</th>
                    <th>Usuario de Actualización</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Cliente 1</td>
                    <td>12345678A</td>
                    <td>Dato 1</td>
                    <td>Dato 2</td>
                    <td>Dirección 1</td>
                    <td><button type="button" class="<?= $claseEstado ?>"><?= $estado ?></button></td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#previewModal"><i class="fas fa-eye"></i></button></td>
                    <td>2023-06-01</td>
                    <td>2024-11-01</td>
                    <td>Usuario de Actualización</td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#histModal"><i class="fas fa-landmark"></i></button></td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#addModal"><i class="fas fa-file"></i></button></td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#contModal"><i class="fas fa-address-card"></i></button></td>
                </tr>
                <tr>
                    <td>Cliente 2</td>
                    <td>12345678B</td>
                    <td>Dato 1.1</td>
                    <td>Dato 2.1</td>
                    <td>Dirección 2</td>
                    <td><button type="button" class="<?= $claseEstado2 ?>"><?= $estado2 ?></button></td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#previewModal"><i class="fas fa-eye"></i></button></td>
                    <td>2023-06-02</td>
                    <td>2024-11-02</td>
                    <td>Usuario de Actualización</td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#histModal"><i class="fas fa-landmark"></i></button></td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#addModal"><i class="fas fa-file"></i></button></td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#contModal"><i class="fas fa-address-card"></i></button></td>
                </tr>
                <tr>
                    <td>Cliente 3</td>
                    <td>12345678C</td>
                    <td>Dato 1.2</td>
                    <td>Dato 2.2</td>
                    <td>Dirección 3</td>
                    <td><button type="button" class="<?= $claseEstado3 ?>"><?= $estado3 ?></button></td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#previewModal"><i class="fas fa-eye"></i></button></td>
                    <td>2023-06-03</td>
                    <td>2024-11-03</td>
                    <td>Usuario de Actualización</td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#histModal"><i class="fas fa-landmark"></i></button></td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#addModal"><i class="fas fa-file"></i></button></td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#contModal"><i class="fas fa-address-card"></i></button></td>
                </tr>
                <tr>
                    <td>Cliente 4</td>
                    <td>12345678D</td>
                    <td>Dato 1.3</td>
                    <td>Dato 2.3</td>
                    <td>Dirección 4</td>
                    <td><button type="button" class="<?= $claseEstado4 ?>"><?= $estado4 ?></button></td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#previewModal"><i class="fas fa-eye"></i></button></td>
                    <td>2023-06-04</td>
                    <td>2024-11-04</td>
                    <td>Usuario de Actualización</td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#histModal"><i class="fas fa-landmark"></i></button></td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#addModal"><i class="fas fa-file"></i></button></td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#contModal"><i class="fas fa-address-card"></i></button></td>
                </tr>
                <tr>
                    <td>Cliente 5</td>
                    <td>12345678F</td>
                    <td>Dato 1.4</td>
                    <td>Dato 2.4</td>
                    <td>Dirección 5</td>
                    <td><button type="button" class="<?= $claseEstado5 ?>"><?= $estado5 ?></button></td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#previewModal"><i class="fas fa-eye"></i></button></td>
                    <td>2023-06-05</td>
                    <td>2024-11-05</td>
                    <td>Usuario de Actualización</td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#histModal"><i class="fas fa-landmark"></i></button></td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#addModal"><i class="fas fa-file"></i></button></td>
                    <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#contModal"><i class="fas fa-address-card"></i></button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Footer -->
<footer class="bg-body-tertiary text-center text-lg-start">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);"></div>
</footer>

<!-- Modal de Envío -->
<div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="sendModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendModal">Notificaciones</h5>
            </div>
            <div class="modal-body">
                <!-- Aquí puedes agregar el contenido que deseas previsualizar -->
                <div>
                    <div class="checkboxes-container" style="margin-left: 20px;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" class="checkbox1">
                            <label class="form-check-label" for="checkbox1">Usuario 1 - Correo@tramitaciones.com - Departamento</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" class="checkbox2">
                            <label class="form-check-label" for="checkbox2">Usuario 2 - Correo@tramitaciones.com - Departamento</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" class="checkbox3">
                            <label class="form-check-label" for="checkbox3">Usuario 3 - Correo@tramitaciones.com - Departamento</label>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="form-check" style="margin-top:20px;">
                        <input class="form-check-input selectAll" type="checkbox" value="">
                        <label class="form-check-label" for="selectAll">Select All/Deselect All</label>
                    </div>
                </div>
            </div>
            <div>
                <input style="margin-left: 20px; border-radius: 100px;">
                <label>@tramitaciones.com</label>
            </div>
            <div style="background-color: #E0F9AF; padding: 20px; margin: 10px; width: 90%;">
                <span>Datos</span>
            </div>
            <div>
                <button type="button" class="btn btn-light" style="margin: 10px; width: 90%;">Agregar Nuevo Contacto</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Previsualización -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Previsualizar</h5>
            </div>
            <div class="modal-body">
                <!-- Aquí puedes agregar el contenido que deseas previsualizar -->
                <iframe src="https://mozilla.github.io/pdf.js/web/viewer.html?file=/ruta/a/tu/archivo.pdf" width="100%" height="500px"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Contactos -->
<div class="modal fade" id="contModal" tabindex="-1" role="dialog" aria-labelledby="contModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contModalLabel">Contactos X Usuario</h5>
            </div>
            <div class="modal-body">
                <!-- ListBox agregado aquí -->
                <select class="form-control" style="margin-bottom: 10px; border-radius: 10px;">
                    <option>Departamento 1</option>
                    <option>Departamento 2</option>
                    <option>Departamento 3</option>
                    <!-- Agrega más opciones según sea necesario -->
                </select>
                <div>
                    <div class="checkboxes-container" style="margin-left: 20px;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" class="checkbox1">
                            <label class="form-check-label" for="checkbox1">Usuario 1 - Correo@tramitaciones.com - Departamento</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" class="checkbox2">
                            <label class="form-check-label" for="checkbox2">Usuario 2 - Correo@tramitaciones.com - Departamento</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" class="checkbox3">
                            <label class="form-check-label" for="checkbox3">Usuario 3 - Correo@tramitaciones.com - Departamento</label>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="form-check" style="margin-top:20px;">
                        <input class="form-check-input selectAll" type="checkbox" value="">
                        <label class="form-check-label" for="selectAll">Select All/Deselect All</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Histórico -->
<div class="modal fade" id="histModal" tabindex="-1" role="dialog" aria-labelledby="histModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="histModalLabel">Archivo Histórico</h5>
            </div>
            <div class="modal-body">
                <!-- Aquí puedes agregar el contenido que deseas previsualizar -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Tarifario</th>
                                <th>Periodo Activo</th>
                                <th>Usuario de Actualización</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><button type="button" class="<?= $claseEstado ?>"><?= $estado ?></button></td>
                                <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#previewModal"><i class="fas fa-eye"></i></button></td>
                                <td>2023-06-01 - 2024-11-01</td>
                                <td>Juan Pablo</td>
                                <td><button type="button" class="btn btn-light"><i class="fas fa-download"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Actualización de Tarifario</h5>
            </div>
            <div class="modal-body" id="infocliente" style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                <!-- Aquí puedes agregar el contenido que deseas previsualizar -->
                <div style="background-color: #E0F9AF; padding: 20px; margin: 10px; width: 100%;">
                    <span><?= $infocliente ?> </span>
                </div>
                <div style="padding: 20px; width: 100%;" id="datTarifario">
                    <h1>Tarifario de servicios</h1>
                    <span>ID: 123456</span>
                </div>
                <div id="estado" style="background-color: #E0F9AF; padding: 20px; margin: 10px; width: 100%;">
                    <span><?= $estado ?></span>
                </div>
                <div style="padding: 20px; display: flex; align-items: center; justify-content: space-between; width: 100%;">
                    <button type="button" class="btn" data-toggle="modal" data-target="#reviewModal"><i class="fas fa-check"></i></button>
                    <button type="button" class="btn"><i class="fas fa-retweet"></i></button>
                    <div>
                        <label for="pdfUpload">Archivo PDF</label>
                        <input type="file" id="pdfUpload" class="small-input" accept=".pdf" />
                    </div>
                </div>
                <label for="periodoRevision">Agendar Periodo de Revisión</label>
                <!-- Div para agendar revisiones -->
                <div style="padding: 5px;">
                    <label for="revisionDatetime">Inicio de Periodo:</label>
                    <input type="datetime-local" id="revisionDatetime" name="revisionDatetime" style="width: 50%; border-radius: 10px;">
                    <label for="revisionDatetime">Final de Periodo:</label>
                    <input type="datetime-local" id="revisionDatetime" name="revisionDatetime" style="width: 50%; border-radius: 10px;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-toggle="modal" data-target="#sendModal"><i class="fas fa-file-import"></i></button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Revisión -->
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Revisión</h5>
            </div>
            <div class="modal-body">
                <!-- Aquí puedes agregar el contenido que deseas previsualizar -->
                <iframe src="https://mozilla.github.io/pdf.js/web/viewer.html?file=/ruta/a/tu/archivo.pdf" width="100%" height="500px"></iframe>
                <div>
                    <input type="checkbox" id="usuarioHaRevisado" name="usuarioHaRevisado" value="revisado">
                    <label for="usuarioHaRevisado">He revisado este documento</label>
                </div>
                <div>
                    <button type="button" id="btnAprobado" class="btn btn-light" disabled data-dismiss="modal">Aprobado</button>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Cambio</th>
                                <th>Fecha de Cambio</th>
                                <th>Detalles de Cambio</th>
                                <th>Área de Impacto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Cambio 1</td>
                                <td>25-06-2024</td>
                                <td>----------------------------------</td>
                                <td>Importaciones</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <button type="button" id="btnCerrar" class="btn btn-light" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de descarga -->
<div class="modal fade" id="downModal" tabindex="-1" role="dialog" aria-labelledby="downModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downModalLabel">Descarga de Archivo</h5>
            </div>
            <div class="modal-body">
                <p>Descarga el archivo generico.</p>
                <div>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Descargar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script que maneja el cambio de modales -->
<script>
$(document).ready(function() {
    var modalsState = {
        currentOpen: null,
        previousOpen: null
    };

    function openModal(targetModalId) {
        var targetModal = $(targetModalId);
        if (modalsState.currentOpen) {
            // Se cierra el modal actual y se establece un callback para abrir el nuevo modal después de que se haya cerrado completamente.
            $(modalsState.currentOpen).modal('hide').on('hidden.bs.modal', function () {
                // Este callback se asegura de que el modal anterior se abra solo después de que el modal actual se haya cerrado completamente.
                $(this).off('hidden.bs.modal'); // Remueve el listener para evitar que se dispare múltiples veces
                setTimeout(function() {
                    targetModal.modal('show');
                }, 300);
            });
            modalsState.previousOpen = modalsState.currentOpen;
        } else {
            // Si no hay un modal abierto actualmente, simplemente abre el nuevo modal.
            setTimeout(function() {
                targetModal.modal('show');
            }, 300);
        }
        modalsState.currentOpen = targetModalId;
    }

    function closeModalAndReopenPrevious() {
        $(modalsState.currentOpen).modal('hide');
        if (modalsState.previousOpen) {
            setTimeout(function() {
                $(modalsState.previousOpen).modal('show');
                modalsState.currentOpen = modalsState.previousOpen;
                modalsState.previousOpen = null;
            }, 300);
        } else {
            modalsState.currentOpen = null;
        }
    }

    $('.btn[data-target]').click(function() {
        var targetModalId = $(this).data('target');
        openModal(targetModalId);
    });

    $('.modal').on('hidden.bs.modal', function() {
        // Asegúrate de que este evento no interfiera con el callback de 'hidden.bs.modal' definido en openModal
        if (!modalsState.previousOpen) {
            closeModalAndReopenPrevious();
        }
    });
});
</script>

<!-- Script para manejo de checkboxes -->
<script>
$(document).ready(function() {
    // Cuando el estado de cualquier checkbox "Seleccionar Todo" cambia
    $('.selectAll').change(function() {
        // Obtiene el estado actual del checkbox "Seleccionar Todo"
        var isChecked = $(this).prop('checked');
        // Establece el estado de todos los otros checkboxes en el mismo contenedor basado en el estado de "Seleccionar Todo"
        $(this).closest('.modal-content').find('.checkboxes-container .form-check-input').prop('checked', isChecked);
    });
});
</script>

<!-- Script que deshabilita el botón de aprobado si el checkbox no está seleccionado -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vincula el evento change al checkbox
    document.getElementById("usuarioHaRevisado").addEventListener('change', function() {
        // Habilita o deshabilita los botones basado en el estado del checkbox
        var revisado = this.checked;
        document.getElementById("btnAprobado").disabled = !revisado;
    });
});
</script>

<!-- Script que maneja la redirección al hacer clic en el botón de redirección -->
<script>
document.getElementById("redirectButton").addEventListener('click', function() {
    // Redirige a la página deseada
    window.location.href = './ad_tf_userPage.php';
});
</script>
</body>
</html>

