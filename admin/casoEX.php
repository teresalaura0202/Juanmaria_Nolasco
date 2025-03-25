<?php

require_once 'conexion.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>clinica</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="../estilos/index.css">
<link rel="stylesheet" href="../estilos/caspex.css">
</head>
<body>

    <div class="top-bar">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-4">
                    <h2 class="text-white">MANOS<span class="text-success">UNIDAS</span></h2>
                </div>
                <div class="col-md-8 text-end">
                <span class="me-3"><a href="../historial.php" class="btn btn-primary">
                <i class="fas fa-file-medical"></i> Historial Médico
    </a></i></span>
                    <span class="me-3">Emergencias..</span>
                    <span class="me-3">contacto</span>
                    <span class="text-success">+240 222844484</span>
                </div>
            </div>
        </div>
    </div>

 

     </style>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="../servicios.php">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link" href="../nosotros.php">Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link" href="../contacto.php">Contacto</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Exito</a></li>
                </ul>
               
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                <h1 style=" font-family: 'Times New Roman', Times, serif;" class="display-4 mb-4">
    <i class="fas fa-hospital-user text-primary me-2"></i>
    Servicio Médico <br>
    <i class="fas fa-stethoscope text-success me-2"></i>
    Con Más Éxito <br>
    <i class="fas fa-thumbs-up text-warning me-2"></i>
</h1>
<p class="leadd">
    Ofrecemos atención médica especializada, diagnósticos precisos y tratamientos efectivos<br>
    para garantizar el mejor cuidado y bienestar de nuestros pacientes.
</p>
      
                </div>
            </div>
        </div>
    </section>


<section class="casos-exitosos py-5">
    <div class="container">
        <!-- Encabezado de la sección -->
        <div class="text-center mb-5">
            <h2 style=" font-family: 'Times New Roman', Times, serif;" class="display-4 fw-bold">Casos Exitosos</h2>
            <p class="lead text-muted">Historias que nos enorgullecen y demuestran nuestro compromiso con la salud</p>
        </div>


    </div>

  <?php include '../php/cc.php' ?>

    </div>
</section>

<!-- Footer Section -->
<footer class="footer bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-md-4">
                <h5>Sobre Nosotros</h5>
                <p>Somos una clínica dedicada a proporcionar servicios médicos de alta calidad. Nuestro equipo de profesionales está aquí para cuidar de ti y tu familia.</p>
            </div>
            <!-- Quick Links -->
            <div class="col-md-4">
                <h5>Enlaces Rápidos</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white">Inicio</a></li>
                    <li><a href="#" class="text-white">Servicios</a></li>
                    <li><a href="#" class="text-white">Contacto</a></li>
                    <li><a href="#" class="text-white">Política de Privacidad</a></li>
                </ul>
            </div>
            <!-- Contact Info -->
            <div class="col-md-4">
                <h5>Contacto</h5>
                <p><i class="fas fa-map-marker-alt"></i> nkolombong, bata</p>
                <p><i class="fas fa-phone"></i> +240 222844484</p>
                <p><i class="fas fa-envelope"></i> manosunidas@gmail.com</p>
            </div>
        </div>
        <div class="text-center mt-3">
            <p>&copy; 2024 Clínica MANOS UNIDAS. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src ="../js/index.js"></script>
</body>
</html>