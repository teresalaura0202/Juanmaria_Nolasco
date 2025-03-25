<?php
session_start();
require_once 'conexion.php';

// Si ya está autenticado, mostrar el historial
if (isset($_SESSION['paciente_id'])) {
    $id_paciente = $_SESSION['paciente_id'];
} else {
    // Si no está autenticado y se envió el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $stmt = $conexion->prepare("SELECT id_paciente, nombre, password FROM pacientes WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $paciente = $result->fetch_assoc();
            if (md5($password) === $paciente['password']) {
                $_SESSION['paciente_id'] = $paciente['id_paciente'];
                $_SESSION['paciente_nombre'] = $paciente['nombre'];
                $id_paciente = $paciente['id_paciente'];
            } else {
                $error = "Contraseña incorrecta";
            }
        } else {
            $error = "Email no encontrado";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Historial Médico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            background: white;
        }
        
        .history-container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .card {
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            color: white;
            border: none;
        }
        
        .timeline {
            position: relative;
            padding: 20px 0;
        }
        
        .timeline-item {
            padding: 20px;
            border-left: 2px solid #0d6efd;
            position: relative;
            margin-left: 20px;
            margin-bottom: 20px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 20px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #0d6efd;
        }
        
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        
        .main-header {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }

        .user-welcome {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-footer {
            background: #f8f9fa;
            padding: 1.5rem 0;
            margin-top: 3rem;
            border-top: 1px solid #dee2e6;
        }

        .proxima-cita-alert {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            border: none;
            margin-bottom: 2rem;
        }
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
        
        .login-container {
            max-width: 400px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            background: linear-gradient(145deg, #ffffff, #f5f7fa);
            font-family: 'Poppins', sans-serif;
        }
        
        .login-container h2 {
            color: #3a7bd5;
            font-weight: 600;
            margin-bottom: 25px;
            text-align: center;
            position: relative;
        }
        
        .login-container h2:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #3a7bd5, #6ac1f0);
            border-radius: 3px;
        }
        
        .form-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 24px;
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 14px;
            color: #3a7bd5;
            font-size: 18px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e1e5ea;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }
        
        .form-control:focus {
            border-color: #3a7bd5;
            box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.2);
            outline: none;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #3a7bd5, #6ac1f0);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(58, 123, 213, 0.2);
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(58, 123, 213, 0.3);
        }
        
        .alert-danger {
            background-color: #fee2e2;
            color: #ef4444;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            border-left: 4px solid #ef4444;
        }
        
        .alert-danger i {
            margin-right: 10px;
            font-size: 18px;
        }
        
        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }
        
        .forgot-password a {
            color: #3a7bd5;
            text-decoration: none;
            font-size: 14px;
        }
        
        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="bg-light">

<?php if (!isset($id_paciente)): ?>

    <div class="login-container">  
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="input-group">
            <i class="fas fa-envelope"></i>
           
            <input type="email" name="email" class="form-control" required placeholder="Ingresa tu correo electrónico">
        </div>
        
        <div class="input-group">
            <i class="fas fa-lock"></i>
        
            <input type="password" name="password" class="form-control" required placeholder="Ingresa tu contraseña">
        </div>
        
        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt"></i> Acceder
        </button>
        
        <div class="forgot-password">
            <a href="#"><i class="fas fa-key"></i> ¿Olvidaste tu contraseña?</a>
        </div>
    </form>
</div>

<?php else: ?>
    <header class="main-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="user-welcome">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Bienvenido/a</h5>
                        <h4 class="mb-0"><?php echo htmlspecialchars($_SESSION['paciente_nombre']); ?></h4>
                    </div>
                </div>
                <a href="logout.php" class="btn btn-light">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Botón para abrir el modal de solicitud de cita -->
        

        <?php
        // Consulta para obtener la próxima cita desde las recetas
        $stmt = $conexion->prepare("
            SELECT c.fecha_cita, c.hora_cita 
            FROM citas c 
            WHERE c.id_paciente = ? 
            AND c.fecha_cita >= CURDATE() 
            AND c.estado = 'programada'
            ORDER BY c.fecha_cita ASC 
            LIMIT 1
        ");
        $stmt->bind_param("i", $id_paciente);
        $stmt->execute();
        $proxima_cita = $stmt->get_result()->fetch_assoc();

        if ($proxima_cita): ?>
            <div class="alert proxima-cita-alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-check fa-2x me-3"></i>
                    <div>
                        <h5 class="mb-1">Tu próxima cita está programada para:</h5>
                        <h4 class="mb-0">
                            <?php 
                            echo date('d/m/Y', strtotime($proxima_cita['fecha_cita'])) . 
                                 ' a las ' . 
                                 date('H:i', strtotime($proxima_cita['hora_cita'])); 
                            ?>
                        </h4>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Próximas Citas -->
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Próximas Citas</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $stmt = $conexion->prepare("
                            SELECT fecha_cita, hora_cita, estado
                            FROM citas 
                            WHERE id_paciente = ? 
                            AND fecha_cita >= CURDATE()
                            AND estado != 'atendida'
                            ORDER BY fecha_cita ASC
                        ");
                        $stmt->bind_param("i", $id_paciente);
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
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($cita = $proximas_citas->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo date('d/m/Y', strtotime($cita['fecha_cita'])); ?></td>
                                                <td><?php echo date('H:i', strtotime($cita['hora_cita'])); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php 
                                                        echo match($cita['estado']) {
                                                            'pendiente' => 'warning',
                                                            'aprobada' => 'info',
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
                            <p class="text-muted mb-0">No tienes citas programadas próximamente.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recetas Médicas -->
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-prescription-bottle-alt"></i> Recetas Médicas</h5>
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
                        $stmt->bind_param("i", $id_paciente);
                        $stmt->execute();
                        $recetas = $stmt->get_result();
                        
                        if ($recetas->num_rows > 0):
                            while ($receta = $recetas->fetch_assoc()):
                        ?>
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="text-primary mb-0">
                                            <i class="fas fa-calendar-day"></i> 
                                            Fecha: <?php echo date('d/m/Y', strtotime($receta['fecha_emision'])); ?>
                                        </h6>
                                        <?php if ($receta['prescripcion']): ?>
                                            <a href="imprimir_receta.php?id=<?php echo $receta['id_receta']; ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-print"></i> Imprimir Receta
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($receta['diagnostico']): ?>
                                        <div class="mb-2">
                                            <strong><i class="fas fa-stethoscope"></i> Diagnóstico:</strong>
                                            <p class="mb-2"><?php echo nl2br(htmlspecialchars($receta['diagnostico'])); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($receta['medicamentos']): ?>
                                        <div class="mb-2">
                                            <strong><i class="fas fa-pills"></i> Medicamentos:</strong>
                                            <p class="mb-2"><?php echo nl2br(htmlspecialchars($receta['medicamentos'])); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($receta['indicaciones']): ?>
                                        <div class="mb-2">
                                            <strong><i class="fas fa-list"></i> Indicaciones:</strong>
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
            </div>

            <!-- Historial de Citas (código existente) -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Historial de Citas</h5>
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
                        $stmt->bind_param("i", $id_paciente);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        while ($row = $result->fetch_assoc()):
                        ?>
                            <div class="timeline-item">
                                <h6 class="text-primary">
                                    <i class="fas fa-calendar"></i> 
                                    Cita: <?php echo date('d/m/Y', strtotime($row['fecha_solicitud'])); ?>
                                </h6>
                                <p class="mb-2">
                                    <span class="badge bg-<?php 
                                        echo match($row['estado']) {
                                            'pendiente' => 'warning',
                                            'aprobada' => 'info',
                                            'atendida' => 'success',
                                            'rechazada' => 'danger',
                                            default => 'secondary'
                                        };
                                    ?>">
                                        <?php echo ucfirst($row['estado']); ?>
                                    </span>
                                </p>
                                
                                <?php if ($row['prescripcion']): ?>
                                    <div class="card mt-2">
                                        <div class="card-body">
                                            <h6 class="text-primary"><i class="fas fa-prescription"></i> Receta Médica</h6>
                                            <pre class="mb-0"><?php echo htmlspecialchars($row['prescripcion']); ?></pre>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="m-0">Registro de Nuevo Testimonio</h4>
                </div>
                <div class="card-body">
                    <?php
                   // Procesamiento del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre']) && isset($_POST['comentario'])) {
    // Incluir archivo de conexión si no está incluido
    if (!isset($conexion)) {
        include 'conexion.php';
    }
    
    // Obtener datos del formulario
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $comentario = mysqli_real_escape_string($conexion, $_POST['comentario']);
    $foto = ''; // Valor predeterminado
    
    // Procesar imagen si se ha subido
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $directorio_destino = "uploads/";
        
        // Crear directorio si no existe
        if (!file_exists($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }
        
        // Generar nombre único para el archivo
        $nombre_archivo = uniqid() . "_" . basename($_FILES['foto']['name']);
        $ruta_completa = $directorio_destino . $nombre_archivo;
        
        // Mover el archivo subido al directorio destino
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_completa)) {
            $foto = $ruta_completa;
        } else {
            echo '<div class="alert alert-danger">Hubo un error al subir la imagen.</div>';
        }
    }
    
    // Insertar datos en la base de datos
    $sql = "INSERT INTO comentarios (nombre, comentario, foto) 
            VALUES ('$nombre', '$comentario', '$foto')";
    
    if (mysqli_query($conexion, $sql)) {
        echo '<div class="alert alert-success">¡Testimonio registrado con éxito!</div>';
    } else {
        echo '<div class="alert alert-danger">Error al registrar el testimonio: ' . mysqli_error($conexion) . '</div>';
    }
}
                    ?>
                    
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Paciente</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        
                    
                        
                        <div class="mb-3">
                            <label for="comentario" class="form-label">Testimonio</label>
                            <textarea class="form-control" id="comentario" name="comentario" rows="4" required></textarea>
                        </div>
                     
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Guardar Testimonio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<object data="./solicitar.php" width="100%" height="500px"></object>



    <footer class="main-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-hospital me-2"></i>Clínica Médica</h5>
                    <p class="mb-0">Cuidando tu salud desde siempre</p>
                </div>
                <div class="col-md-4">
                    <h5><i class="fas fa-clock me-2"></i>Horario de Atención</h5>
                    <p class="mb-0">Lunes a Viernes: 8:00 AM - 8:00 PM<br>
                    Sábados: 8:00 AM - 2:00 PM</p>
                </div>
                <div class="col-md-4">
                    <h5><i class="fas fa-phone me-2"></i>Contacto</h5>
                    <p class="mb-0">Teléfono: (123) 456-7890<br>
                    Email: contacto@clinica.com</p>
                </div>
            </div>
            <div class="text-center mt-3">
                <small class="text-muted">© <?php echo date('Y'); ?> Clínica Médica. Todos los derechos reservados.</small>
            </div>
        </div>
    </footer>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 