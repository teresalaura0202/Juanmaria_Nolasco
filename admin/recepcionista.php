<?php
include '../conexion.php';
session_start();


// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre'])) {
    header("Location: ../login.php"); // Redirigir a la página de inicio de sesión
    exit;
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accion'])) {
        $id_cita = $_POST['id_cita'];
        
        if ($_POST['accion'] === 'aprobar') {
            $fecha_cita = $_POST['fecha_cita'];
            
            $sql = "UPDATE citas SET fecha_cita = ?, estado = 'aprobada' WHERE id_cita = ?";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "si", $fecha_cita, $id_cita);
            mysqli_stmt_execute($stmt);
            
            // Enviar email de aprobación
            $sql = "SELECT p.email, p.nombre FROM pacientes p 
                    INNER JOIN citas c ON p.id_paciente = c.id_paciente 
                    WHERE c.id_cita = ?";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id_cita);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);
            $paciente = mysqli_fetch_assoc($resultado);
            
            mail($paciente['email'], 
                 "Cita Médica Aprobada", 
                 " CLINICA MANOS UNIAS : Su cita ha sido aprobada para el día " . $fecha_cita);
        } 
        elseif ($_POST['accion'] === 'rechazar') {
            $motivo_rechazo = $_POST['motivo_rechazo'];
            
            $sql = "UPDATE citas SET estado = 'rechazada', motivo_rechazo = ? WHERE id_cita = ?";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "si", $motivo_rechazo, $id_cita);
            mysqli_stmt_execute($stmt);
            
            // Enviar email de rechazo
            $sql = "SELECT p.email, p.nombre FROM pacientes p 
                    INNER JOIN citas c ON p.id_paciente = c.id_paciente 
                    WHERE c.id_cita = ?";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id_cita);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);
            $paciente = mysqli_fetch_assoc($resultado);
            
            mail($paciente['email'], 
                 "Cita Médica Rechazada", 
                 "Lo sentimos, su cita ha sido rechazada. Motivo: " . $motivo_rechazo);
        }
    }
}

// Obtener estados únicos de las citas
$sql = "SELECT DISTINCT estado FROM citas";
$resultado = mysqli_query($conexion, $sql);
$estados = array();
while ($fila = mysqli_fetch_assoc($resultado)) {
    $estados[] = $fila['estado'];
}
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
    <link rel="stylesheet" href="../estilos/recepcionista.css">
    <title>Panel de Administración Recepcionista</title>
</head>


<body>
    <div class="d-flex" id="wrapper">
        <!-- Barra lateral -->
        <div class="bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom"><i
                    class="fas fa-user-md me-2"></i>inicio</div>
            <div class="list-group list-group-flush my-3">
          
                
                <a href="./recepcionista.php" class="list-group-item list-group-item-action bg-transparent second-text active fw-bold"><i
                        class="fas fa-users me-2"></i>recepcionista</a>
                <a href="./pacientes.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fas fa-procedures me-2"></i>pacientes</a>
            
                <a href="./citas.php" class="list-group-item list-group-item-action bg-transparent second-text  fw-bold"><i
                        class="fas fa-calendar-check me-2"></i>citas</a>
                       
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
                    <h2 class="fs-2 m-0">Recepcionista</h2>
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
                <div class="row g-3 my-2">
                <div class="col-md-3">
    <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
        <div>
            <?php
                // Conexión a la base de datos
                include_once("../conexion.php");
                
                // Consulta para contar el total de pacientes
                $sql = "SELECT COUNT(*) as total FROM pacientes";
                $result = mysqli_query($conexion, $sql);
                $data = mysqli_fetch_assoc($result);
            ?>
            <h3 class="fs-2"><?php echo $data['total']; ?></h3>
            <p class="fs-5">pacientes</p>
        </div>
        <i class="fas fa-procedures fs-1 primary-text border rounded-full secondary-bg p-3"></i>
    </div>
</div>

                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2">4920</h3>
                                <p class="fs-5">doctores</p>
                            </div>
                            <i
                                class="fas fa-user-md fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2">3899</h3>
                                <p class="fs-5">enfermeros</p>
                            </div>
                            <i class="fas fa-users fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2">25</h3>
                                <p class="fs-5">citas</p>
                            </div>
                            <i class="fas fa-calendar-check fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>
                </div>

<!--  -->



<div class="container mt-5">
        <h2>Gestión de Citas</h2>
        
        <div class="filtros-estados">
            <button class="btn btn-outline-primary filtro-btn activo" data-estado="todos">Todas</button>
            <?php foreach ($estados as $estado): ?>
            <button class="btn btn-outline-primary filtro-btn" data-estado="<?php echo $estado; ?>">
                <?php echo ucfirst($estado); ?>
            </button>
            <?php endforeach; ?>
        </div>

        <div class="tabla-citas">
            <table class="table">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Email</th>
                        <th>Fecha Solicitud</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="tabla-citas-body">
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalRechazo" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rechazar Cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="formRechazo">
                    <div class="modal-body">
                        <input type="hidden" name="id_cita" id="rechazo_id_cita">
                        <input type="hidden" name="accion" value="rechazar">
                        <div class="mb-3">
                            <label for="motivo_rechazo" class="form-label">Motivo del rechazo</label>
                            <textarea class="form-control" name="motivo_rechazo" id="motivo_rechazo" 
                                    required rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Confirmar Rechazo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




                

            <!--  -->
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
    </script>
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cargarCitas(estado) {
            const tablaBody = document.getElementById('tabla-citas-body');
            const tablaCitas = document.querySelector('.tabla-citas');
            tablaCitas.classList.add('loading');

            // Primero hacemos fade out a las filas existentes
            const filasExistentes = tablaBody.querySelectorAll('tr');
            filasExistentes.forEach((fila, index) => {
                setTimeout(() => {
                    fila.classList.add('fade-out');
                }, index * 50);
            });

            // Esperamos a que termine la animación de fade out
            setTimeout(() => {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `../obtener_citas.php?estado=${estado}`, true);
                
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const citas = JSON.parse(xhr.responseText);
                        let html = '';
                        
                        citas.forEach((cita, index) => {
                            html += `
                                <tr style="animation-delay: ${index * 0.1}s">
                                    <td>${cita.nombre}</td>
                                    <td>${cita.email}</td>
                                    <td>${cita.fecha_solicitud}</td>
                                    <td>
                                        <span class="badge bg-${getBadgeColor(cita.estado)}">
                                            ${cita.estado}
                                        </span>
                                    </td>
                                    <td>
                                        ${cita.estado === 'pendiente' ? `
                                            <div class="btn-group">
                                                <form method="POST" class="d-inline me-2">
                                                    <input type="hidden" name="id_cita" value="${cita.id_cita}">
                                                    <input type="hidden" name="accion" value="aprobar">
                                                    <input type="datetime-local" name="fecha_cita" required class="form-control form-control-sm d-inline me-1" style="width: auto;">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-check"></i> Aprobar
                                                    </button>
                                                </form>
                                                
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        onclick="mostrarModalRechazo(${cita.id_cita})">
                                                    <i class="fas fa-times"></i> Rechazar
                                                </button>
                                            </div>
                                        ` : cita.estado === 'rechazada' ? `
                                            <small class="text-muted">
                                                Motivo: ${cita.motivo_rechazo || 'No especificado'}
                                            </small>
                                        ` : ''}
                                    </td>
                                </tr>
                            `;
                        });
                        
                        tablaBody.innerHTML = html;
                        tablaCitas.classList.remove('loading');
                    }
                };
                
                xhr.send();
            }, 300); // Esperar 300ms para que termine la animación de fade out
        }

        function getBadgeColor(estado) {
            switch(estado.toLowerCase()) {
                case 'pendiente':
                    return 'warning';
                case 'aprobada':
                    return 'success';
                case 'cancelada':
                    return 'danger';
                case 'completada':
                    return 'info';
                default:
                    return 'secondary';
            }
        }

        // Manejar clicks en los botones de filtro
        document.querySelectorAll('.filtro-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activo'));
                this.classList.add('activo');
                cargarCitas(this.dataset.estado);
            });
        });

        // Cargar todas las citas al inicio
        cargarCitas('todos');

        let modalRechazo;
        
        document.addEventListener('DOMContentLoaded', function() {
            modalRechazo = new bootstrap.Modal(document.getElementById('modalRechazo'));
        });

        function mostrarModalRechazo(idCita) {
            document.getElementById('rechazo_id_cita').value = idCita;
            modalRechazo.show();
        }
    </script>
</body>

</html>
