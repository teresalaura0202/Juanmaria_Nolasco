<?php
session_start(); // Iniciar sesión
include 'conexion.php';
$mensaje = ''; // Variable para almacenar el mensaje

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_paciente'], $_SESSION['nombre'], $_SESSION['email'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login_paciente.php");
    exit;
}

// Obtener los datos del paciente de la sesión
$id_paciente = $_SESSION['id_paciente']; // Asegúrate de que este ID esté almacenado en la sesión
$nombre = $_SESSION['nombre']; // Nombre del paciente
$email = $_SESSION['email']; // Email del paciente
$telefono = isset($_SESSION['telefono']) ? $_SESSION['telefono'] : 'No disponible'; // Teléfono del paciente

// Si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Usar el ID del paciente directamente
    $sql = "INSERT INTO citas (id_paciente) VALUES (?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_paciente);
    
    if(mysqli_stmt_execute($stmt)) {
        $mensaje = [
            'tipo' => 'success',
            'titulo' => '¡Éxito!',
            'texto' => 'Su solicitud de cita ha sido enviada correctamente'
        ];
    } else {
        $mensaje = [
            'tipo' => 'error',
            'titulo' => 'Error',
            'texto' => 'Hubo un problema al procesar su solicitud'
        ];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Solicitar Cita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .header-user {
            background: #f8f9fa;
            padding: 10px 20px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 30px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .user-name {
            font-weight: 600;
            color: #2d3748;
        }
        
        .logout-btn {
            color: #dc3545;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s ease;
        }
        
        .logout-btn:hover {
            color: #bd2130;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header con información del usuario -->
    <div class="header-user">
        <div class="container">
            <div class="user-info">
                <span class="user-name">
                    <i class="fas fa-user me-2"></i>
                    <?php echo $nombre; ?>
                </span>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar sesión
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Botón para abrir el modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#solicitarCitaModal">
            <i class="fas fa-calendar-plus me-2"></i>
            Solicitar Cita Médica
        </button>

        <!-- Modal -->
        <div class="modal fade" id="solicitarCitaModal" tabindex="-1" aria-labelledby="solicitarCitaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="solicitarCitaModalLabel">Solicitar Cita Médica</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nombre:</label>
                                <input type="text" class="form-control" value="<?php echo $nombre; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" class="form-control" value="<?php echo $email; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Teléfono:</label>
                                <input type="tel" class="form-control" value="<?php echo $telefono; ?>" readonly>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-calendar-check me-2"></i>
                                Solicitar Cita
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($mensaje): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '<?php echo $mensaje['tipo']; ?>',
                title: '<?php echo $mensaje['titulo']; ?>',
                text: '<?php echo $mensaje['texto']; ?>',
                confirmButtonColor: '#3085d6'
            });
        });
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>