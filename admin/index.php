<?php
include '../conexion.php'; // Asegúrate de que la conexión a la base de datos esté incluida



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

// Si el usuario ha iniciado sesión y es director, continúa con la lógica de la página


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
    <title>Panel </title>
  <link rel="stylesheet" href="../estilos/admindex.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Barra lateral -->
        <div class="bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom"><i
                    class="fas fa-user-md me-2"></i>inicio</div>
            <div class="list-group list-group-flush my-3">
            <a href="./index.php" class="list-group-item list-group-item-action bg-transparent second-text  active  "><i
            class="fas fa-user-md me-2"></i>inicio</a>
           
            <a href="./doctor.php" class="list-group-item list-group-item-action bg-transparent second-text  "><i
                        class="fas fa-user-md me-2"></i>doctores</a>
                
                        <a href="#" onclick="mostrarAlerta();" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
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
                                <h3 class="fs-2"><?php echo $total_doctores; ?></h3>
                                <p class="fs-5">doctores</p>
                            </div>
                            <i class="fas fa-user-md fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2"><?php echo $total_enfermeros; ?></h3>
                                <p class="fs-5">recepcionista</p>
                            </div>
                            <i class="fas fa-users fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2"><?php echo $total_pacientes; ?></h3>
                                <p class="fs-5">pacientes</p>
                            </div>
                            <i class="fas fa-procedures fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>

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
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="fs-4 mb-3">Estadísticas Generales</h3>
                                <canvas id="statsChart" style="max-width: 800px; max-height: 400px; width: 100%; height: auto;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="fs-4 mb-3">Pacientes y Citas</h3>
                                <canvas id="patientsChart" style="max-width: 800px; max-height: 400px; width: 100%; height: auto;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
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

        // Crear la gráfica de dona
        const ctx = document.getElementById('statsChart').getContext('2d');
        const statsChart = new Chart(ctx, {
            type: 'doughnut', // Cambiado a gráfica de dona
            data: {
                labels: ['Doctores', 'Enfermeros', 'Pacientes', 'Citas'],
                datasets: [{
                    label: 'Cantidad',
                    data: [
                        <?php echo $total_doctores; ?>,
                        <?php echo $total_enfermeros; ?>,
                        <?php echo $total_pacientes; ?>,
                        <?php echo $total_citas; ?>
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                family: 'Arial, sans-serif',
                                weight: 'bold'
                            },
                            color: '#333'
                        }
                    },
                    title: {
                        display: true,
                        text: 'Distribución de Personal y Servicios',
                        font: {
                            size: 18,
                            family: 'Arial, sans-serif',
                            weight: 'bold'
                        },
                        color: '#333'
                    }
                }
            }
        });

        // Nueva gráfica de barras para pacientes y citas
        const ctx2 = document.getElementById('patientsChart').getContext('2d');
        const patientsChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Pacientes', 'Citas'],
                datasets: [{
                    label: 'Cantidad',
                    data: [
                        <?php echo $total_pacientes; ?>,
                        <?php echo $total_citas; ?>
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false // Ocultar leyenda para esta gráfica
                    },
                    title: {
                        display: true,
                        text: 'Total de Pacientes y Citas',
                        font: {
                            size: 18,
                            family: 'Arial, sans-serif',
                            weight: 'bold'
                        },
                        color: '#333'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10
                        }
                    }
                }
            }
        });
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
</body>

</html>
