<?php
include '../conexion.php'; // Asegúrate de que la conexión a la base de datos esté incluida

// Consultar el total de doctores
$paciente_seleccionado = null;
if (isset($_GET['paciente_id']) && is_numeric($_GET['paciente_id'])) {
    $paciente_id = $_GET['paciente_id'];
    
    // Obtener información del paciente seleccionado
    $stmt = $conexion->prepare("SELECT id_paciente, nombre, email, telefono FROM pacientes WHERE id_paciente = ?");
    $stmt->bind_param("i", $paciente_id);
    $stmt->execute();
    $paciente_seleccionado = $stmt->get_result()->fetch_assoc();
}

$sql_citas = "SELECT COUNT(*) as total_citas FROM citas"; // Contar todas las citas
$resultado_citas = mysqli_query($conexion, $sql_citas);
$fila_citas = mysqli_fetch_assoc($resultado_citas);
$total_citas = $fila_citas['total_citas'];

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
    <link rel="stylesheet" href="../estilos/citas.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Panel de Administración Responsivo Bootstrap 5</title>
   
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Barra lateral -->
        <div class="bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom"><i
                    class="fas fa-user-md me-2"></i>inicio</div>
            <div class="list-group list-group-flush my-3">
                <a href="./doctor.php" class="list-group-item list-group-item-action bg-transparent second-text "><i
                        class="fas fa-user-md me-2"></i>doctores</a>
                
                <a href="./recepcionista.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fas fa-users me-2"></i>recepcionista</a>
                <a href="./pacientes.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fas fa-procedures me-2"></i>pacientes</a>
                <a href="./contraseñas.php" class="list-group-item list-group-item-action bg-transparent second-text  fw-bold"><i
                        class="fas fa-pills me-2"></i>contraseñas</a>
                <a href="./citas.php" class="list-group-item list-group-item-action bg-transparent second-text active fw-bold"><i
                        class="fas fa-calendar-check me-2"></i>citas</a>
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
                                <i class="fas fa-user me-2"></i>@Billiesono..
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
                

                   
                   

                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                        <div>
                                <h3 class="fs-2"><?php echo $total_citas; ?></h3>
                                <p class="fs-5">citas</p>
                            </div>
                            <i class="fas fa-calendar-check fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>
                </div>

              
                <div class="container">
        <?php if (!$paciente_seleccionado): ?>
            <div class="search-container">
                <h4 class="mb-4"><i class="fas fa-search me-2"></i>Buscar Paciente</h4>
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre" value="<?php echo isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Buscar
                        </button>
                    </div>
                </form>
            </div>

            <h3 class="mb-4"><i class="fas fa-users me-2"></i>Listado de Pacientes</h3>
            
            <div class="row">
                <?php
                // Construir la consulta según los parámetros de búsqueda
                $sql = "SELECT id_paciente, nombre, email, telefono FROM pacientes";
                $params = [];
                $types = "";
                $where_clauses = [];
                
                if (isset($_GET['nombre']) && !empty($_GET['nombre'])) {
                    $where_clauses[] = "nombre LIKE ?";
                    $params[] = "%" . $_GET['nombre'] . "%";
                    $types .= "s";
                }
                
                if (isset($_GET['email']) && !empty($_GET['email'])) {
                    $where_clauses[] = "email LIKE ?";
                    $params[] = "%" . $_GET['email'] . "%";
                    $types .= "s";
                }
                
                if (!empty($where_clauses)) {
                    $sql .= " WHERE " . implode(" AND ", $where_clauses);
                }
                
                $sql .= " ORDER BY nombre ASC";
                
                $stmt = $conexion->prepare($sql);
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0):
                    while ($paciente = $result->fetch_assoc()):
                ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card patient-card" onclick="window.location.href='?paciente_id=<?php echo $paciente['id_paciente']; ?>'">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="patient-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?php echo htmlspecialchars($paciente['nombre']); ?></h5>
                                        <p class="text-muted mb-0"><?php echo htmlspecialchars($paciente['email']); ?></p>
                                    </div>
                                </div>
                                
                                <?php
                                // Obtener cantidad de citas
                                $stmt_citas = $conexion->prepare("SELECT COUNT(*) as total FROM citas WHERE id_paciente = ?");
                                $stmt_citas->bind_param("i", $paciente['id_paciente']);
                                $stmt_citas->execute();
                                $total_citas = $stmt_citas->get_result()->fetch_assoc()['total'];
                                
                                // Obtener cantidad de recetas
                                $stmt_recetas = $conexion->prepare("
                                    SELECT COUNT(*) as total FROM recetas r
                                    INNER JOIN citas c ON r.id_cita = c.id_cita
                                    WHERE c.id_paciente = ?
                                ");
                                $stmt_recetas->bind_param("i", $paciente['id_paciente']);
                                $stmt_recetas->execute();
                                $total_recetas = $stmt_recetas->get_result()->fetch_assoc()['total'];
                                ?>
                                
                                <div class="d-flex justify-content-between mt-3">
                                    <span class="badge bg-info text-dark fs-6">
                                        <i class="fas fa-calendar-check me-1"></i> <?php echo $total_citas; ?> Citas
                                    </span>
                                    <span class="badge bg-warning text-dark fs-6">
                                        <i class="fas fa-prescription me-1"></i> <?php echo $total_recetas; ?> Recetas
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent text-center">
                                <small class="text-muted">
                                    <i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($paciente['telefono'] ?? 'No disponible'); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No se encontraron pacientes con los criterios especificados.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        
        <?php else: ?>
            <!-- Vista detallada del paciente seleccionado -->
            <div class="mb-4">
                <a href="?" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al listado
                </a>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Datos del Paciente</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($paciente_seleccionado['nombre']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($paciente_seleccionado['email']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($paciente_seleccionado['telefono'] ?? 'No disponible'); ?></p>
                            <p><strong>ID:</strong> <?php echo $paciente_seleccionado['id_paciente']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Próximas Citas -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Próximas Citas</h5>
                </div>
                <div class="card-body">
                    <?php
                    $stmt = $conexion->prepare("
                        SELECT fecha_cita, hora_cita, motivo, estado
                        FROM citas 
                        WHERE id_paciente = ? 
                        AND fecha_cita >= CURDATE()
                        ORDER BY fecha_cita ASC
                    ");
                    $stmt->bind_param("i", $paciente_seleccionado['id_paciente']);
                    $stmt->execute();
                    $proximas_citas = $stmt->get_result();
                    
                    if ($proximas_citas->num_rows > 0):
                    ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($cita = $proximas_citas->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($cita['fecha_cita'])); ?></td>
                                            <td><?php echo date('H:i', strtotime($cita['hora_cita'])); ?></td>
                                            <td><?php echo htmlspecialchars($cita['motivo'] ?? 'No especificado'); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo match($cita['estado']) {
                                                        'pendiente' => 'warning',
                                                        'aprobada' => 'info',
                                                        'programada' => 'primary',
                                                        'atendida' => 'success',
                                                        'rechazada' => 'danger',
                                                        default => 'secondary'
                                                    };
                                                ?>">
                                                    <?php echo ucfirst($cita['estado']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No hay citas programadas próximamente.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Recetas Médicas -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-prescription-bottle-alt me-2"></i>Recetas Médicas</h5>
                </div>
                <div class="card-body">
                    <?php
                    $stmt = $conexion->prepare("
                        SELECT r.*, c.fecha_cita
                        FROM recetas r
                        INNER JOIN citas c ON r.id_cita = c.id_cita
                        WHERE c.id_paciente = ?
                        ORDER BY r.fecha_emision DESC
                    ");
                    $stmt->bind_param("i", $paciente_seleccionado['id_paciente']);
                    $stmt->execute();
                    $recetas = $stmt->get_result();
                    
                    if ($recetas->num_rows > 0):
                        while ($receta = $recetas->fetch_assoc()):
                    ?>
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-primary mb-0">
                                        <i class="fas fa-calendar-day me-2"></i> 
                                        Fecha: <?php echo date('d/m/Y', strtotime($receta['fecha_emision'])); ?>
                                    </h6>
                                    <?php if ($receta['prescripcion']): ?>
                                        <a href="imprimir_receta.php?id=<?php echo $receta['id_receta']; ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-print me-1"></i> Imprimir Receta
                                        </a>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($receta['diagnostico']): ?>
                                    <div class="mb-2">
                                        <strong><i class="fas fa-stethoscope me-1"></i> Diagnóstico:</strong>
                                        <p class="mb-2"><?php echo nl2br(htmlspecialchars($receta['diagnostico'])); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($receta['medicamentos']): ?>
                                    <div class="mb-2">
                                        <strong><i class="fas fa-pills me-1"></i> Medicamentos:</strong>
                                        <p class="mb-2"><?php echo nl2br(htmlspecialchars($receta['medicamentos'])); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($receta['indicaciones']): ?>
                                    <div class="mb-2">
                                        <strong><i class="fas fa-list me-1"></i> Indicaciones:</strong>
                                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($receta['indicaciones'])); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <p class="text-muted mb-0">No hay recetas médicas disponibles.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Historial de Citas -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Historial de Citas</h5>
                </div>
                <div class="card-body timeline">
                    <?php
                    $stmt = $conexion->prepare("
                        SELECT c.*, r.diagnostico, r.medicamentos, r.indicaciones, r.observaciones, r.fecha_emision, r.prescripcion
                        FROM citas c
                        LEFT JOIN recetas r ON c.id_cita = r.id_cita
                        WHERE c.id_paciente = ?
                        ORDER BY c.fecha_solicitud DESC
                    ");
                    $stmt->bind_param("i", $paciente_seleccionado['id_paciente']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                    ?>
                        <div class="timeline-item">
                            <h6 class="text-primary">
                                <i class="fas fa-calendar me-2"></i> 
                                Cita: <?php echo date('d/m/Y', strtotime($row['fecha_cita'] ?? $row['fecha_solicitud'])); ?>
                                <?php if (isset($row['hora_cita'])): ?>
                                    a las <?php echo date('H:i', strtotime($row['hora_cita'])); ?>
                                <?php endif; ?>
                            </h6>
                            <p>
                                <span class="badge bg-<?php 
                                    echo match($row['estado']) {
                                        'pendiente' => 'warning',
                                        'aprobada' => 'info',
                                        'programada' => 'primary',
                                        'atendida' => 'success',
                                        'rechazada' => 'danger',
                                        default => 'secondary'
                                    };
                                ?>">
                                    <?php echo ucfirst($row['estado']); ?>
                                </span>
                                
                                <?php if ($row['motivo']): ?>
                                    <span class="ms-2"><?php echo htmlspecialchars($row['motivo']); ?></span>
                                <?php endif; ?>
                            </p>
                            
                            <?php if ($row['observaciones']): ?>
                                <p class="mb-2">
                                    <strong>Observaciones:</strong> 
                                    <?php echo nl2br(htmlspecialchars($row['observaciones'])); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ($row['diagnostico']): ?>
                                <div class="alert alert-light mt-2">
                                    <h6 class="text-primary"><i class="fas fa-stethoscope me-2"></i>Diagnóstico:</h6>
                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($row['diagnostico'])); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <p class="text-muted mb-0">No hay historial de citas disponible.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
                <!-- Nuevo contenedor para la gráfica -->
               
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
   

</body>

</html>
