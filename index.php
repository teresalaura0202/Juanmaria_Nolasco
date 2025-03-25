
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>clinica</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="./estilos/index.css">
</head>
<body>

    <div class="top-bar">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-4">
                    <h2 class="text-white">MANOS<span class="text-success">UNIDAS</span></h2>
                </div>
                <div class="col-md-8 text-end">
                <span class="me-3"><a href="historial.php" class="btn btn-primary">
                <i class="fas fa-file-medical"></i> Historial Médico
    </a></i></span>
                    <span class="me-3">Emergencias..</span>
                    <span class="me-3">contacto</span>
                    <span class="text-success">+240 222844484</span>
                </div>
            </div>
        </div>
    </div>

    <!-- stilos para la navegacion -->
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

     </style>
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
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                <h1 style=" font-family: 'Times New Roman', Times, serif;" class="display-4 mb-4">Servicio Médico para la Lepra<br><small>Hoy por ti, mañana por mí</small></h1>
<p style=" font-family: 'Times New Roman', Times, serif;" class="lead mb-4">
    La lepra es una enfermedad infecciosa crónica causada por la bacteria 
    <strong><em>Mycobacterium leprae</em></strong>. Afecta principalmente la piel, los nervios periféricos, la mucosa de las vías respiratorias y los ojos.<br>

</p>

                    <!-- <button class="btn btn-read-more">saber mas</button> -->
                </div>
            </div>
        </div>
    </section>
   
<section class="casos-exitosos py-5">
    <div class="container">
        <!-- Encabezado de la sección -->
        

        <!-- Estadísticas -->
        <div class="container mt-5">
        <h2 class="text-center mb-4">Nuestras Estadísticas</h2>
        
        <div class="row mb-5 text-center">
            <div class="col-md-3 mb-4">
                <div class="bg-white p-4 rounded shadow-sm counter-box">
                    <i class="fas fa-user-md text-success fa-3x mb-3"></i>
                    <h3 class="fw-bold"><span class="counter" data-target="1200">0</span>+</h3>
                    <p class="text-muted">Pacientes Atendidos</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="bg-white p-4 rounded shadow-sm counter-box">
                    <i class="fas fa-procedures text-success fa-3x mb-3"></i>
                    <h3 class="fw-bold"><span class="counter" data-target="500">0</span>+</h3>
                    <p class="text-muted">Cirugías Exitosas</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="bg-white p-4 rounded shadow-sm counter-box">
                    <i class="fas fa-star text-success fa-3x mb-3"></i>
                    <h3 class="fw-bold"><span class="counter" data-target="98">0</span>%</h3>
                    <p class="text-muted">Satisfacción</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="bg-white p-4 rounded shadow-sm counter-box">
                    <i class="fas fa-certificate text-success fa-3x mb-3"></i>
                    <h3 class="fw-bold"><span class="counter" data-target="15">0</span>+</h3>
                    <p class="text-muted">Años de Experiencia</p>
                </div>
            </div>
        </div>
    </div>


        <!-- Testimonios en Carrusel -->
       <!-- Testimonios en Carrusel -->
       <div class="mt-5">
    <h3 class="text-center mb-4">Lo que dicen nuestros pacientes</h3>
    <div id="testimoniosCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            // Usando la conexión existente
           include 'conexion.php';

            // Verificar conexión
            if (!$conexion) {
                die("Conexión fallida: " . mysqli_connect_error());
            }

            // Consulta para obtener comentarios
            $sql = "SELECT foto, comentario FROM comentarios";
            $result = mysqli_query($conexion, $sql);

            // Verificar si hay resultados
            if (mysqli_num_rows($result) > 0) {
                $active = true; // Para marcar el primer elemento como activo
                while($row = mysqli_fetch_assoc($result)) {
                    // Mostrar cada comentario
                    echo '<div class="carousel-item ' . ($active ? 'active' : '') . '">';
                    echo '<div class="card text-center border-0 shadow-sm py-4 px-3">';
                    echo '<div class="card-body">';
                    echo '<p class="card-text">' . $row['comentario'] . '</p>';
                    echo '</div></div></div>';
                    $active = false; // Solo el primero debe ser activo
                }
            } else {
                echo '<div class="carousel-item active"><div class="card text-center border-0 shadow-sm py-4 px-3"><div class="card-body"><p>No hay comentarios disponibles.</p></div></div></div>';
            }

            // No cerramos la conexión aquí ya que podría usarse en otras partes del sitio
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#testimoniosCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-success rounded-circle" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#testimoniosCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-success rounded-circle" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</div>

    </div>
</section>


<style>
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
<!-- Footer Section -->
<footer class="footer bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="footer-title">Sobre Nosotros</h5>
                <p class="footer-text">Somos una clínica dedicada a proporcionar servicios médicos de alta calidad. Nuestro equipo de profesionales está aquí para cuidar de ti y tu familia.</p>
            </div>
            <!-- Quick Links -->
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="footer-title">Enlaces Rápidos</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="#" class="footer-link">Inicio</a></li>
                    <li><a href="#" class="footer-link">Servicios</a></li>
                    <li><a href="#" class="footer-link">Contacto</a></li>
                    <li><a href="#" class="footer-link">Política de Privacidad</a></li>
                </ul>
            </div>
            <!-- Contact Info -->
            <div class="col-md-4">
                <h5 class="footer-title">Contacto</h5>
                <p class="footer-text"><i class="fas fa-map-marker-alt"></i> Nkolombong, Bata</p>
                <p class="footer-text"><i class="fas fa-phone"></i> +240 222844484</p>
                <p class="footer-text"><i class="fas fa-envelope"></i> manosunidas@gmail.com</p>
            </div>
        </div>
        <div class="text-center mt-4">
            <p class="footer-copy">&copy; 2024 Clínica MANOS UNIDAS. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src ="./js/index.js"></script>
</body>
</html>