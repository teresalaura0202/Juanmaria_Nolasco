<?php
include '../conexion.php'; // Asegúrate de que la conexión a la base de datos esté incluida

// Consultar el total de doctores
$sql_doctores = "SELECT COUNT(*) as total_doctores FROM usuarios WHERE rol = 'doctor'"; // Contar solo los usuarios con rol de doctor
$resultado_doctores = mysqli_query($conexion, $sql_doctores);
$fila_doctores = mysqli_fetch_assoc($resultado_doctores);
$total_doctores = $fila_doctores['total_doctores'];

// Consultar el total de enfermeros (si es necesario)
$sql_enfermeros = "SELECT COUNT(*) as total_enfermeros FROM usuarios WHERE rol = 'recepcionista'"; // Contar solo los usuarios con rol de recepcionista
$resultado_enfermeros = mysqli_query($conexion, $sql_enfermeros);
$fila_enfermeros = mysqli_fetch_assoc($resultado_enfermeros);
$total_enfermeros = $fila_enfermeros['total_enfermeros'];

// Consultar el total de pacientes
$sql_pacientes = "SELECT COUNT(*) as total_pacientes FROM pacientes"; // Contar todos los pacientes
$resultado_pacientes = mysqli_query($conexion, $sql_pacientes);
$fila_pacientes = mysqli_fetch_assoc($resultado_pacientes);
$total_pacientes = $fila_pacientes['total_pacientes'];

$sql_citas = "SELECT COUNT(*) as total_citas FROM citas"; // Contar todas las citas
$resultado_citas = mysqli_query($conexion, $sql_citas);
$fila_citas = mysqli_fetch_assoc($resultado_citas);
$total_citas = $fila_citas['total_citas'];

// Consultar pacientes atendidos
$sql_atendidos = "SELECT p.id_paciente, p.nombre, p.email, p.telefono 
                  FROM pacientes p 
                  INNER JOIN citas c ON p.id_paciente = c.id_paciente 
                  WHERE c.estado = 'atendida' 
                  GROUP BY p.id_paciente";
$resultado_atendidos = mysqli_query($conexion, $sql_atendidos);


// Si llega aquí, el admin está autenticado
// Obtener paciente específico si se proporciona un ID
$paciente_seleccionado = null;
if (isset($_GET['paciente_id']) && is_numeric($_GET['paciente_id'])) {
    $paciente_id = $_GET['paciente_id'];
    
    // Obtener información del paciente seleccionado
    $stmt = $conexion->prepare("SELECT id_paciente, nombre, email, telefono FROM pacientes WHERE id_paciente = ?");
    $stmt->bind_param("i", $paciente_id);
    $stmt->execute();
    $paciente_seleccionado = $stmt->get_result()->fetch_assoc();
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Panel de Administración Responsivo Bootstrap 5</title>
    <style>
        .table-container {
            max-height: 400px; /* Altura máxima para el scroll */
            overflow-y: auto; /* Habilitar scroll vertical */
            border: 1px solid #dee2e6; /* Borde alrededor de la tabla */
            border-radius: 10px; /* Bordes redondeados */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra */
            background: white; /* Fondo blanco */
        }
        .table th, .table td {
            vertical-align: middle; /* Alinear verticalmente el contenido */
        }
        .table thead th {
            position: sticky; /* Fijar el encabezado */
            top: 0; /* Posición en la parte superior */
            background-color: white; /* Fondo blanco para el encabezado */
            z-index: 10; /* Asegurarse de que esté por encima del contenido */
        }
        .chart-small {
            max-width: 400px; /* Ancho máximo */
            max-height: 200px; /* Altura máxima */
            width: 100%; /* Ancho responsivo */
            height: auto; /* Altura automática */
        }
        /* Fijar el sidebar */
        #sidebar-wrapper {
            position: fixed; /* Fijar el sidebar */
            height: 100vh; /* Altura completa de la ventana */
            overflow-y: auto; /* Habilitar scroll si el contenido del sidebar es largo */
            z-index: 1000; /* Asegurar que esté por encima del contenido */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); /* Sombra para el sidebar */
        }

        /* Ajustar el contenido principal para que no se superponga con el sidebar */
        #page-content-wrapper {
            margin-left: 250px; /* Ajustar este valor al ancho del sidebar */
            width: calc(100% - 250px); /* Restar el ancho del sidebar */
        }
    </style>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Barra lateral -->
        <div class="bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom"><i
                    class="fas fa-user-md me-2"></i>inicio</div>
            <div class="list-group list-group-flush my-3">
            <a href="./doctor.php" class="list-group-item list-group-item-action bg-transparent second-text  "><i
                        class="fas fa-user-md me-2"></i>doctores</a>
                
                <a href="./recepcionista.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fas fa-users me-2"></i>recepcionista</a>
                <a href="./pacientes.php" class="list-group-item list-group-item-action bg-transparent second-text active fw-bold"><i
                        class="fas fa-procedures me-2"></i>pacientes</a>
                <a href="./contraseñas.php" class="list-group-item list-group-item-action bg-transparent second-text  fw-bold"><i
                        class="fas fa-pills me-2"></i>contraseñas</a>
                <a href="./citas.php" class="list-group-item list-group-item-action bg-transparent second-text  fw-bold"><i
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
                <div class="row g-3 my-2">
                  
                    

                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2"><?php echo $total_pacientes; ?></h3>
                                <p class="fs-5">pacientes</p>
                            </div>
                            <i class="fas fa-procedures fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>

                   
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <h3 class="fs-4">Pacientes Atendidos</h3>
                        <div class="table-container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID Paciente</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($fila_atendidos = mysqli_fetch_assoc($resultado_atendidos)): ?>
                                    <tr>
                                        <td><?php echo $fila_atendidos['id_paciente']; ?></td>
                                        <td><?php echo $fila_atendidos['nombre']; ?></td>
                                        <td><?php echo $fila_atendidos['email']; ?></td>
                                        <td><?php echo $fila_atendidos['telefono']; ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
