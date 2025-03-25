<?php
include '../conexion.php';
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre'])) {
    header("Location: ../login.php");
    exit;
}

// Verificar si el rol del usuario es "director"
if ($_SESSION['rol'] !== 'doctor') {
    header("Location: ../login.php"); // Redirigir a una página de error o acceso denegado
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cita = $_POST['id_cita'];
    $diagnostico = $_POST['diagnostico'];
    $medicamentos = $_POST['medicamentos'];
    $indicaciones = $_POST['indicaciones'];
    $observaciones = $_POST['observaciones'];
    $proxima_cita = $_POST['proxima_cita'];
    $hora_proxima_cita = $_POST['hora_proxima_cita'];
    
    // Obtener el id_paciente
    $sql = "SELECT id_paciente FROM citas WHERE id_cita = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_cita);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $cita_actual = mysqli_fetch_assoc($resultado);
    
    // Crear la próxima cita
    if (!empty($proxima_cita) && !empty($hora_proxima_cita)) {
        $sql_proxima_cita = "INSERT INTO citas (id_paciente, fecha_cita, hora_cita, estado) VALUES (?, ?, ?, 'programada')";
        $stmt = mysqli_prepare($conexion, $sql_proxima_cita);
        mysqli_stmt_bind_param($stmt, "iss", $cita_actual['id_paciente'], $proxima_cita, $hora_proxima_cita);
        mysqli_stmt_execute($stmt);
        
        // Crear notificación para el paciente
        $mensaje_notificacion = "Nueva cita programada para el día " . $proxima_cita . " a las " . $hora_proxima_cita;
        $sql_notificacion = "INSERT INTO notificaciones (id_paciente, mensaje, fecha_creacion, estado) VALUES (?, ?, NOW(), 'no_leida')";
        $stmt = mysqli_prepare($conexion, $sql_notificacion);
        mysqli_stmt_bind_param($stmt, "is", $cita_actual['id_paciente'], $mensaje_notificacion);
        mysqli_stmt_execute($stmt);
    }

    $prescripcion = "DIAGNÓSTICO:\n" . $diagnostico . "\n\n" .
                    "MEDICAMENTOS:\n" . $medicamentos . "\n\n" .
                    "INDICACIONES:\n" . $indicaciones . "\n\n" .
                    "OBSERVACIONES:\n" . $observaciones . "\n\n" .
                    ($proxima_cita ? "PRÓXIMA CITA: " . $proxima_cita . " a las " . $hora_proxima_cita : "");
    
    $sql = "INSERT INTO recetas (id_cita, prescripcion) VALUES (?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "is", $id_cita, $prescripcion);
    mysqli_stmt_execute($stmt);
    
    // Actualizar estado de la cita actual
    $sql = "UPDATE citas SET estado = 'atendida' WHERE id_cita = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_cita);
    mysqli_stmt_execute($stmt);
    
    // Enviar receta por email
   // Obtener el email del paciente
$sql = "SELECT p.email, p.nombre FROM pacientes p 
INNER JOIN citas c ON p.id_paciente = c.id_paciente 
WHERE c.id_cita = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_cita);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$paciente = mysqli_fetch_assoc($resultado);

// Preparar el mensaje HTML
$fecha_actual = date("d/m/Y");
$mensaje_html = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    color: #333333;
    max-width: 600px;
    margin: 0 auto;
}
.header {
    background-color: #4287f5;
    color: white;
    padding: 15px;
    text-align: center;
    border-radius: 5px 5px 0 0;
}
.content {
    padding: 20px;
    border: 1px solid #dddddd;
    border-top: none;
    border-radius: 0 0 5px 5px;
}
.footer {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #dddddd;
    font-size: 12px;
    color: #777777;
    text-align: center;
}
.prescripcion {
    background-color: #f9f9f9;
    padding: 15px;
    border-left: 3px solid #4287f5;
    margin: 15px 0;
    white-space: pre-line;
}
</style>
</head>
<body>
<div class="header">
<h2>RECETA MÉDICA</h2>
</div>
<div class="content">
<p><strong>Paciente:</strong> ' . $paciente['nombre'] .  '</p>
<p><strong>Fecha:</strong> ' . $fecha_actual . '</p>

<div class="prescripcion">' . nl2br($prescripcion) . '</div>

<p>Por favor, siga todas las indicaciones médicas descritas anteriormente.</p>
<p><strong>Esta receta es un documento médico oficial.</strong></p>
</div>
<div class="footer">
<p>Este correo es confidencial y contiene información médica protegida.</p>
<p>No reenvíe este mensaje sin autorización del médico tratante.</p>
</div>
</body>
</html>
';

// Preparar encabezados para correo HTML
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: clinica@ejemplo.com\r\n";

// Enviar el correo
mail(
$paciente['email'],
"Receta Médica - " . $fecha_actual,
$mensaje_html,
$headers
); }

$sql = "SELECT c.*, p.nombre, p.email FROM citas c 
        INNER JOIN pacientes p ON c.id_paciente = p.id_paciente 
        WHERE c.estado = 'aprobada'";
$resultado = mysqli_query($conexion, $sql);

$sql_recetas = "SELECT r.*, c.fecha_cita, p.nombre as nombre_paciente 
                FROM recetas r
                INNER JOIN citas c ON r.id_cita = c.id_cita 
                INNER JOIN pacientes p ON c.id_paciente = p.id_paciente
                WHERE c.estado = 'atendida'
                ORDER BY c.fecha_cita DESC";
$resultado_recetas = mysqli_query($conexion, $sql_recetas);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="../estilos/doctor.css">
    <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Panel de Administración Responsivo Bootstrap 5</title>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Barra lateral -->
        <div class="bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom"><i
                    class="fas fa-user-md me-2"></i>inicio</div>
            <div class="list-group list-group-flush my-3">
            <a href="./index.php" class="list-group-item list-group-item-action bg-transparent second-text  "><i
            class="fas fa-user-md me-2"></i>inicio</a>
           
            <a href="./doctor.php" class="list-group-item list-group-item-action bg-transparent second-text active  "><i
                        class="fas fa-user-md me-2"></i>doctores</a>
                
                        <a href="./recepcionista.php" onclick="mostrarAlerta();" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
    <i class="fas fa-procedures me-2"></i>recepcionista
</a>

                <a href="./pacientes.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fas fa-procedures me-2"></i>pacientes</a>
                <a href="./contraseñas.php" class="list-group-item list-group-item-action bg-transparent second-text  fw-bold"><i
                        class="fas fa-pills me-2"></i>contraseñas</a>
               
                        <a href="./panel_casosEX.PHP" class="list-group-item list-group-item-action bg-transparent second-text  fw-bold"><i
                        class="fas fa-calendar-check me-2"></i>Exitosos</a>
                <a href="../login.php" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold"><i
                        class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Contenido de la página -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
                    <h2 class="fs-2 m-0">Panel de control</h2>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Alternar navegación">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-2"></i><?php echo $_SESSION['nombre']; ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Perfil</a></li>
                                <li><a class="dropdown-item" href="#">Configuraciones</a></li>
                                <li><a class="dropdown-item" href="#">Cerrar sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid px-4">
                

                <div class="container">
                    <div class="section-header mb-4">
                        <h2 class="section-title">
                            <i class="fas fa-calendar-check me-2"></i>
                            Citas Aprobadas
                        </h2>
                    </div>

                    <div class="nav-buttons">
                        <a href="./doctorcitas.php" class="nav-button pending <?php echo basename($_SERVER['PHP_SELF']) == 'doctorcitas.php' ? 'active' : ''; ?>">
                            <i class="fas fa-clock"></i>
                           recetas
                        </a>
                        <a href="./doctor.php" class="nav-button recipes <?php echo basename($_SERVER['PHP_SELF']) == 'doctor.php' ? 'active' : ''; ?>">
                            <i class="fas fa-file-medical"></i>
                            pendientes
                        </a>
                    </div>


                    <div class="appointment-cards">
                        <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                            <div class="appointment-card">
                                <div class="patient-info">
                                <div class="patient-name">
                                    <i class="fas fa-user me-2"></i>
                                    <?php echo $fila['nombre']; ?>
                                </div>
                                <div class="patient-email">
                                    <i class="fas fa-envelope me-2"></i>
                                    <?php echo $fila['email']; ?>
                                </div>
                                <div class="appointment-date">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?php echo $fila['fecha_cita']; ?>
                                </div>
                            </div>
                            <button type="button" class="btn-emit-recipe" data-bs-toggle="modal" 
                                    data-bs-target="#modalEmitirReceta" 
                                    onclick="setCitaId(<?php echo $fila['id_cita']; ?>)">
                                <i class="fas fa-file-medical"></i>
                                Emitir Receta
                            </button>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Modal para Emitir Receta -->
                <div class="modal fade" id="modalEmitirReceta" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-file-medical fa-lg"></i>
                                    Emitir Receta Médica
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="formEmitirReceta" method="POST" action="">
                                <div class="modal-body">
                                    <input type="hidden" name="id_cita" id="id_cita_modal">
                                    
                                    <div class="recipe-form">
                                        <!-- Sección de Diagnóstico -->
                                        <div class="recipe-section">
                                            <div class="recipe-section-header">
                                            <i class="fas fa-stethoscope"></i>
                                                <h6 class="recipe-section-title">Diagnóstico</h6>
                                            </div>
                                            <textarea name="diagnostico" class="form-control recipe-textarea" required 
                                                placeholder="Ingrese el diagnóstico detallado del paciente"></textarea>
                                    </div>

                                        <!-- Sección de Medicamentos -->
                                        <div class="recipe-section">
                                            <div class="recipe-section-header">
                                            <i class="fas fa-pills"></i>
                                                <h6 class="recipe-section-title">Medicamentos</h6>
                                            </div>
                                            <textarea name="medicamentos" class="form-control recipe-textarea" required 
                                                placeholder="Especifique nombre del medicamento, dosis y frecuencia"></textarea>
                                    </div>

                                        <!-- Sección de Indicaciones -->
                                        <div class="recipe-section">
                                            <div class="recipe-section-header">
                                            <i class="fas fa-list-ul"></i>
                                                <h6 class="recipe-section-title">Indicaciones</h6>
                                            </div>
                                            <textarea name="indicaciones" class="form-control recipe-textarea" required 
                                                placeholder="Detalle las instrucciones específicas de administración"></textarea>
                                    </div>

                                        <!-- Sección de Observaciones -->
                                        <div class="recipe-section">
                                            <div class="recipe-section-header">
                                            <i class="fas fa-comment-medical"></i>
                                                <h6 class="recipe-section-title">Observaciones</h6>
                                            </div>
                                            <textarea name="observaciones" class="form-control recipe-textarea" 
                                                placeholder="Incluya recomendaciones adicionales"></textarea>
                                    </div>

                                        <!-- Sección de Próxima Cita -->
                                        <div class="recipe-section">
                                            <div class="recipe-section-header">
                                            <i class="fas fa-calendar-plus"></i>
                                                <h6 class="recipe-section-title">Programar Próxima Cita</h6>
                                            </div>
                                        <div class="date-time-inputs">
                                                <div class="input-group mb-3">
                                                <span class="input-group-text">
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                                <input type="date" name="proxima_cita" class="form-control">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-clock"></i>
                                                </span>
                                                <input type="time" name="hora_proxima_cita" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times"></i>
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i>
                                        Emitir Receta
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

             <!-- Modificar la sección de la tabla -->

                <script>
                    // Inicializar el selector de fecha
                    flatpickr("input[type=date]", {
                        minDate: "today",
                        dateFormat: "Y-m-d"
                    });
                </script>
            </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var el = document.getElementById("wrapper");
    var toggleButton = document.getElementById("menu-toggle");

    toggleButton.onclick = function () {
        el.classList.toggle("toggled");
    };

    function setCitaId(idCita) {
        document.getElementById('id_cita_modal').value = idCita;
    }
   
</script>
<script>
    function mostrarAlerta() {
        Swal.fire({
            icon: 'error',
            title: 'Acceso Denegado',
            text: 'No eres un recepcionisata.',
            confirmButtonText: 'Entendido'
        });
    }
</script>
</script>
</body>
</html>