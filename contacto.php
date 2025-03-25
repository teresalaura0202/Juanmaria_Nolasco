<?php
include 'conexion.php';
$mensaje = ''; // Variable para almacenar el mensaje

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    // Verificar si el nombre ya existe
    $sql_verificar = "SELECT id_paciente FROM pacientes WHERE nombre = ?";
    $stmt_verificar = mysqli_prepare($conexion, $sql_verificar);
    mysqli_stmt_bind_param($stmt_verificar, "s", $nombre);
    mysqli_stmt_execute($stmt_verificar);
    $resultado_verificar = mysqli_stmt_get_result($stmt_verificar);

    if (mysqli_num_rows($resultado_verificar) > 0) {
        // Si el nombre ya existe
        $mensaje = [
            'tipo' => 'error',
            'titulo' => 'paciente ya existe',
            'texto' => 'El nombre ya existe. Por favor, utiliza otro nombre.'
        ];
    } else {
        // Insertar el nuevo paciente
        $sql = "INSERT INTO pacientes (nombre, email, telefono) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $telefono);

        if (mysqli_stmt_execute($stmt)) {
            $id_paciente = mysqli_insert_id($conexion);

            // Insertar la cita asociada
            $sql = "INSERT INTO citas (id_paciente) VALUES (?)";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id_paciente);

            if (mysqli_stmt_execute($stmt)) {
                $mensaje = [
                    'tipo' => 'success',
                    'titulo' => '¡Éxito!',
                    'texto' => 'Su solicitud de cita ha sido enviada correctamente'
                ];
            }
        } else {
            $mensaje = [
                'tipo' => 'error',
                'titulo' => 'Error',
                'texto' => 'Hubo un problema al procesar su solicitud'
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestros Servicios</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link rel="stylesheet" href="./estilos/contacto.css">
</head>
<body>
    <div class="top-bar">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-4">
                    <h2 class="text-white">MANOS<span class="text-success">UNIDAS</span></h2>
                </div>
                <div class="col-md-8 text-end">
                  
                    <span class="me-3">Emergencias..</span>
                    <span class="me-3">contacto</span>
                    <span class="text-success">+240 222844484</span>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* Estilo para la barra superior */
.top-bar {
    background-color: #34495e; /* Fondo oscuro */
    padding: 10px 20px;
    color: #ecf0f1; /* Color claro para el texto */
    border-bottom: 2px solid #2980b9; /* Línea de separación debajo */
    transition: all 0.3s ease; /* Transición suave para cualquier cambio */
}

.top-bar:hover {
    background-color: #2c3e50; /* Cambio de color de fondo al pasar el ratón */
    border-bottom-color: #1abc9c; /* Cambio de color en la línea de separación */
}

/* Estilo para el título */
.top-bar h2 {
    font-size: 1.8rem; /* Tamaño de fuente más grande */
    font-weight: 700;
    letter-spacing: 1px; /* Espaciado entre letras */
    animation: slideInLeft 0.8s ease-out; /* Animación para el título */
}

@keyframes slideInLeft {
    0% {
        opacity: 0;
        transform: translateX(-100%);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Estilo para los botones y enlaces */
.top-bar a.btn {
    text-decoration: none;
    color: #fff;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
}

.top-bar a.btn:hover {
    background-color: #1abc9c; /* Cambio de color en hover */
    transform: translateY(-5px); /* Efecto de desplazamiento hacia arriba */
}

.top-bar a.btn i {
    margin-right: 10px; /* Separación entre íconos y texto */
}

/* Estilo para los enlaces de texto */
.top-bar .me-3 {
    font-size: 1rem;
    color: #ecf0f1;
    transition: color 0.3s ease, transform 0.3s ease;
}

.top-bar .me-3:hover {
    color: #3498db; /* Color azul al pasar el ratón */
    transform: translateX(5px); /* Efecto de desplazamiento de texto */
}

/* Estilo para el número de teléfono */
.top-bar .text-success {
    font-size: 1.2rem;
    font-weight: 600;
    transition: color 0.3s ease, transform 0.3s ease;
}

.top-bar .text-success:hover {
    color: #16a085; /* Color verde más brillante al pasar el ratón */
    transform: translateY(-3px); /* Efecto de desplazamiento hacia arriba */
}


        /* Estilo para la barra de navegación */

.nav-link {
    color: #ecf0f1; /* Color de texto claro para los enlaces */
    font-size: 1.1rem; /* Tamaño de texto mayor para hacerlo más legible */
    font-weight: 500; /* Peso de fuente moderado */
    transition: color 0.3s ease, transform 0.3s ease; /* Transiciones suaves para el color y transformación */
    margin-right: 15px; /* Espaciado entre los enlaces */
}

.nav-link:hover {
    color: #3498db; /* Cambio de color de los enlaces al pasar el ratón */
    transform: scale(1.1); /* Agrandar ligeramente el texto en hover */
}

/* Estilo para el botón de la navbar */
.navbar-toggler {
    border-color: #ecf0f1; /* Bordes blancos para el icono de la hamburguesa */
}

.navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30'%3e%3cpath stroke='%23ecf0f1' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e"); /* Color blanco para el icono */
}

/* Animaciones para los enlaces al entrar */
.navbar-nav .nav-item {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.8s forwards;
}

.navbar-nav .nav-item:nth-child(1) {
    animation-delay: 0.2s;
}

.navbar-nav .nav-item:nth-child(2) {
    animation-delay: 0.4s;
}

.navbar-nav .nav-item:nth-child(3) {
    animation-delay: 0.6s;
}

.navbar-nav .nav-item:nth-child(4) {
    animation-delay: 0.8s;
}

.navbar-nav .nav-item:nth-child(5) {
    animation-delay: 1s;
}

/* Animación de desvanecimiento y subida */
@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Efecto para la navbar en dispositivos móviles */
@media (max-width: 768px) {
    .navbar-toggler {
        margin-left: auto; /* Alinea el ícono a la derecha */
    }
}

    /* Fondo oscuro con padding extra para más espacio */
.footer {
    background-color: #2c3e50;
    padding-top: 50px;
    padding-bottom: 50px;
    font-family: 'Arial', sans-serif;
    position: relative;
}

/* Títulos con sombra y color más claro */
.footer-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ecf0f1;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: relative;
}

/* Descripción y texto de contacto con un color más suave */
.footer-text {
    color: #bdc3c7;
    line-height: 1.8;
}

/* Enlaces con efecto de transición suave */
.footer-links .footer-link {
    color: #ecf0f1;
    text-decoration: none;
    font-size: 1rem;
    display: block;
    padding: 5px 0;
    transition: color 0.3s ease, padding-left 0.3s ease;
}

.footer-links .footer-link:hover {
    color: #1abc9c;
    padding-left: 10px; /* Añadido efecto de deslizamiento */
}

/* Efecto hover sobre el footer */
.footer:hover {
    background-color: #34495e;
    transition: background-color 0.3s ease;
}

/* Estilo para el texto de copyright */
.footer-copy {
    color: #bdc3c7;
    font-size: 0.9rem;
    margin-top: 20px;
    font-weight: 400;
}

/* Iconos dentro del footer con animación */
.footer i {
    color: #1abc9c;
    margin-right: 10px;
    transition: color 0.3s ease;
}

.footer i:hover {
    color: #ecf0f1;
}

/* Animación de los elementos cuando aparecen */
.footer .footer-title, .footer .footer-text, .footer .footer-link {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.8s ease-out forwards;
}

.footer .footer-title:nth-child(1) {
    animation-delay: 0.3s;
}

.footer .footer-text, .footer .footer-link {
    animation-delay: 0.5s;
}

/* Animación keyframes */
@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
     </style>
     <style>
    /* Efecto de parpadeo */
@keyframes blink {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

.blink {
    animation: blink 1s infinite;
}

/* Efecto de transición en el ícono */
i {
    transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
}

/* Agregar un rebote al texto */
.display-4 {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-15px);
    }
}

</style>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="./index.php">inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="./servicios.php">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link" href="./nosotros.php">Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link" href="./contacto.php">Contacto</a></li>
                    <li class="nav-item"><a class="nav-link" href="./admin/casoEX.php">Exito</a></li>
                </ul>

            </div>
        </div>
    </nav>

<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-overlay d-flex align-items-center justify-content-center">
        <div class="text-center text-white">
            <h1 style=" font-family: 'Times New Roman', Times, serif;" class="display-4"> <i class="fas fa-user-md text-success fa-3x mb-3"></i>Servicios médicos de calidad y confianza</h1>
            <p class="lead">En nuestra clínica, cada tratamiento es un paso hacia tu bienestar, porque
                <br> creemos en tu capacidad de sanar y vivir plenamente</p>
        </div>
    </div>
</div>



<section class="contacto py-5">
    <div class="container">
        <h2 class="text-center mb-4">Contáctanos</h2>
        <p class="text-center text-muted mb-5">Estamos aquí para ayudarte. Envíanos tus preguntas o comentarios y te responderemos lo antes posible.</p>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="procesar_contacto.php">
                    <div class="mb-3">
                        <label for="nombre" class="form-label"><i class="fas fa-user"></i> Nombre completo:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese su nombre completo" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email:</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese su correo electrónico" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label"><i class="fas fa-phone"></i> Teléfono:</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Ingrese su número de teléfono" required>
                    </div>
                    <div class="mb-3">
                        <label for="mensaje" class="form-label"><i class="fas fa-comment"></i> Mensaje:</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="5" placeholder="Escribe tu mensaje aquí" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100"><i class="fas fa-paper-plane"></i> Enviar Mensaje</button>
                </form>
            </div>
        </div>
    </div>
</section>



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
<!-- Bootstrap JS y Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>