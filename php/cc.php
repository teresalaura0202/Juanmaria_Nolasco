<?php
// Incluir el archivo de conexión
include 'conexion.php';

// Consultar todos los casos de éxito
$sql_select = "SELECT * FROM casos_exito WHERE activo = 1 ORDER BY fecha_registro DESC";
$result = mysqli_query($conexion, $sql_select);

// Obtener categorías para el filtro
$sql_categorias = "SELECT DISTINCT categoria FROM casos_exito WHERE activo = 1";
$result_categorias = mysqli_query($conexion, $sql_categorias);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casos de Éxito - Testimonios</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --light-color: #ecf0f1;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem 0;
            border-radius: 0 0 20px 20px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            overflow: hidden;
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.2);
        }
        
        .card-img-top {
            height: 180px;
            object-fit: cover;
        }
        
        .card-img-overlay {
            background: linear-gradient(0deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 50%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
        
        .card-header {
            font-weight: 600;
            letter-spacing: 0.5px;
            background-color: white;
            border-bottom: 3px solid var(--secondary-color);
        }
        
        .testimonial-icon {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: var(--secondary-color);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .patient-info {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .patient-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--secondary-color);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 10px;
            font-weight: bold;
        }
        
        .category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 10;
        }
        
        .testimonio-preview {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .filter-btn {
            border-radius: 20px;
            margin: 0 5px 10px 0;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }
        
        .filter-btn.active {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .filters-section {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 30px;
        }
        
        .no-results {
            text-align: center;
            padding: 50px 0;
            color: #95a5a6;
        }
        
        .no-results i {
            font-size: 5rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
            opacity: 0.6;
        }
        
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-bottom: none;
        }
        
        .modal-img {
            max-height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }
        
        .date-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
            color: var(--primary-color);
            border-radius: 10px;
            margin-top: 5px;
        }
    </style>
</head>
<body>


    <div class="container">
      
        
        <!-- Casos de Éxito -->
        <div class="row" id="casos-container">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Determinar ícono según la categoría
                    $categoryIcon = '';
                    $categoryClass = '';
                    switch ($row['categoria']) {
                        case 'Cirugía':
                            $categoryIcon = 'fas fa-procedures';
                            $categoryClass = 'bg-danger';
                            break;
                        case 'Tratamiento':
                            $categoryIcon = 'fas fa-pills';
                            $categoryClass = 'bg-success';
                            break;
                        case 'Rehabilitación':
                            $categoryIcon = 'fas fa-walking';
                            $categoryClass = 'bg-warning';
                            break;
                        case 'Diagnóstico':
                            $categoryIcon = 'fas fa-stethoscope';
                            $categoryClass = 'bg-info';
                            break;
                        case 'Consulta':
                            $categoryIcon = 'fas fa-user-md';
                            $categoryClass = 'bg-primary';
                            break;
                        default:
                            $categoryIcon = 'fas fa-tag';
                            $categoryClass = 'bg-secondary';
                    }
                    
                    // Obtener iniciales para avatar
                    $nombres = explode(' ', $row['nombre_paciente']);
                    $iniciales = '';
                    foreach ($nombres as $nombre) {
                        if (!empty($nombre)) {
                            $iniciales .= strtoupper(substr($nombre, 0, 1));
                            if (strlen($iniciales) >= 2) break;
                        }
                    }
                    if (strlen($iniciales) < 2 && !empty($nombres[0])) {
                        $iniciales = strtoupper(substr($nombres[0], 0, 2));
                    }
            ?>
            <div class="col-md-6 col-lg-4 mb-4 caso-card" data-categoria="<?php echo $row['categoria']; ?>">
                <div class="card h-100">
                    <div class="position-relative">
                        <span class="badge <?php echo $categoryClass; ?> category-badge">
                            <i class="<?php echo $categoryIcon; ?> me-1"></i><?php echo $row['categoria']; ?>
                        </span>
                        
                        <?php if (!empty($row['imagen']) && file_exists($row['imagen'])): ?>
                            <img src="<?php echo $row['imagen']; ?>" class="card-img-top" alt="Imagen del caso">
                        <?php else: ?>
                            <div class="card-img-top d-flex justify-content-center align-items-center bg-light">
                                <i class="fas fa-images fa-4x text-secondary opacity-25"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="testimonial-icon">
                            <i class="fas fa-quote-right"></i>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['titulo']); ?></h5>
                        
                        <div class="patient-info">
                            <div class="patient-avatar">
                                <?php echo $iniciales; ?>
                            </div>
                            <div>
                                <div class="fw-bold"><?php echo htmlspecialchars($row['nombre_paciente']); ?></div>
                                <small class="text-muted"><?php echo $row['edad']; ?> años</small>
                            </div>
                        </div>
                        
                        <p class="card-text testimonio-preview">
                            <?php echo htmlspecialchars($row['testimonio']); ?>
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="date-badge">
                                <i class="far fa-calendar-alt me-1"></i>
                                <?php echo date('d/m/Y', strtotime($row['fecha_registro'])); ?>
                            </span>
                            <button class="btn btn-sm btn-primary" 
                                    onclick='verDetalle(<?php echo json_encode($row); ?>)'>
                                <i class="fas fa-eye me-1"></i>Leer más
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
            ?>
            <div class="col-12 no-results">
                <i class="fas fa-folder-open"></i>
                <h4>No hay casos de éxito registrados</h4>
                <p>Pronto compartiremos con ustedes nuestros casos de éxito</p>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
    <!-- estilos para el modal -->
<style>
    /* Reducción de tamaño del modal */
.modal-dialog {
    max-width: 500px; /* Reducido el tamaño del modal */
    animation: zoomIn 0.5s ease-out; /* Animación para la entrada */
}

/* Animación de entrada del modal (zoom-in) */
@keyframes zoomIn {
    from {
        transform: scale(0.5);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

/* Estilo para los botones */
.modal-footer .btn {
    font-weight: bold;
    border-radius: 25px; /* Bordes redondeados */
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.modal-footer .btn:hover {
    transform: scale(1.05); /* Efecto de hover divertido */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Sombra suave */
}

/* Estilo de la imagen */
#detalle-imagen-container img {
    border-radius: 15px;
    transition: transform 0.3s ease;
    max-width: 100%;
}

#detalle-imagen-container img:hover {
    transform: scale(1.1); /* Efecto de zoom al pasar el cursor */
}

/* Estilo para el testimonio */
.testimonial-container {
    background: #f7f7f7;
    border: 1px solid #ddd;
    border-radius: 12px;
    position: relative;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.testimonial-container:hover {
    box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.2);
}

/* Animación de texto en el testimonio */
#detalle-testimonio {
    font-style: italic;
    color: #555;
    line-height: 1.6;
    animation: fadeIn 1s ease-out;
}

/* Animación de aparición suave del texto */
@keyframes fadeIn {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

/* Estilo para el título */
.modal-title {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    color: #5a3d5c;
}

/* Estilo para el encabezado */
.modal-header {
    background-color: #f5f5f5;
    border-bottom: 2px solid #ddd;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

/* Estilo para el contenido */
.modal-body {
    padding: 20px;
}

/* Estilo para las etiquetas y botones */
#detalle-categoria {
    background-color: #007bff;
    color: white;
}

#compartir-btn {
    background-color: #28a745;
    color: white;
}

#compartir-btn:hover {
    background-color: #218838; /* Cambio de color en hover */
}

/* Animación de cierre del modal */
.modal.fade .modal-dialog {
    transform: scale(0.7);
}

.modal.fade.show .modal-dialog {
    transform: scale(1);
}

</style>
    <!-- Modal para ver detalles -->
    <div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-star me-2"></i>Historia de Éxito</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 text-center mb-3">
                        <div id="detalle-imagen-container" class="mb-3">
                            <!-- La imagen se insertará aquí -->
                        </div>
                        <div class="mt-3">
                            <h5 id="detalle-paciente" class="mb-1"></h5>
                            <p id="detalle-edad" class="text-muted"></p>
                            <span id="detalle-categoria" class="badge bg-info"></span>
                            <div id="detalle-fecha" class="small text-muted mt-2"></div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h4 id="detalle-titulo" class="mb-3 border-bottom pb-2"></h4>
                        <div class="p-3 bg-light rounded position-relative testimonial-container">
                            <i class="fas fa-quote-left fa-2x position-absolute text-secondary opacity-25" style="top: 10px; left: 10px;"></i>
                            <div class="ps-4 pt-3" id="detalle-testimonio"></div>
                            <i class="fas fa-quote-right fa-2x position-absolute text-secondary opacity-25" style="bottom: 10px; right: 10px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cerrar
                </button>
                <a href="#" class="btn btn-primary" id="compartir-btn">
                    <i class="fas fa-share-alt me-1"></i>Compartir
                </a>
            </div>
        </div>
    </div>
</div>


    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filtrado de tarjetas por categoría
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const cards = document.querySelectorAll('.caso-card');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Quitar clase active de todos los botones
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    // Añadir clase active al botón clickeado
                    this.classList.add('active');
                    
                    const filter = this.getAttribute('data-filter');
                    
                    // Filtrar las tarjetas
                    cards.forEach(card => {
                        if (filter === 'todos' || card.getAttribute('data-categoria') === filter) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    
                    // Mostrar mensaje si no hay resultados
                    const visibleCards = document.querySelectorAll('.caso-card[style="display: block;"]');
                    const container = document.getElementById('casos-container');
                    const noResultsElement = document.querySelector('.no-results');
                    
                    if (visibleCards.length === 0 && !noResultsElement) {
                        const noResults = document.createElement('div');
                        noResults.className = 'col-12 no-results';
                        noResults.innerHTML = `
                            <i class="fas fa-search"></i>
                            <h4>No se encontraron casos en esta categoría</h4>
                            <p>Intente con otra categoría o vea todos los casos</p>
                        `;
                        container.appendChild(noResults);
                    } else if (visibleCards.length > 0 && noResultsElement) {
                        noResultsElement.remove();
                    }
                });
            });
        });
        
        // Función para ver detalles
        function verDetalle(caso) {
            document.getElementById('detalle-titulo').innerText = caso.titulo;
            document.getElementById('detalle-paciente').innerText = caso.nombre_paciente;
            document.getElementById('detalle-edad').innerText = caso.edad + ' años';
            
            // Determinar clase de la insignia según la categoría
            let categoryClass = 'bg-secondary';
            let categoryIcon = 'fas fa-tag';
            
            switch (caso.categoria) {
                case 'Cirugía':
                    categoryClass = 'bg-danger';
                    categoryIcon = 'fas fa-procedures';
                    break;
                case 'Tratamiento':
                    categoryClass = 'bg-success';
                    categoryIcon = 'fas fa-pills';
                    break;
                case 'Rehabilitación':
                    categoryClass = 'bg-warning';
                    categoryIcon = 'fas fa-walking';
                    break;
                case 'Diagnóstico':
                    categoryClass = 'bg-info';
                    categoryIcon = 'fas fa-stethoscope';
                    break;
                case 'Consulta':
                    categoryClass = 'bg-primary';
                    categoryIcon = 'fas fa-user-md';
                    break;
            }
            
            const categoriaElement = document.getElementById('detalle-categoria');
            categoriaElement.className = `badge ${categoryClass}`;
            categoriaElement.innerHTML = `<i class="${categoryIcon} me-1"></i>${caso.categoria}`;
            
            document.getElementById('detalle-testimonio').innerText = caso.testimonio;
            document.getElementById('detalle-fecha').innerText = 'Registrado: ' + new Date(caso.fecha_registro).toLocaleDateString();
            
            const imagenContainer = document.getElementById('detalle-imagen-container');
            if (caso.imagen && caso.imagen !== '') {
                imagenContainer.innerHTML = `<img src="${caso.imagen}" alt="Imagen del caso" class="img-fluid modal-img">`;
            } else {
                imagenContainer.innerHTML = `<div class="bg-light rounded p-5 text-center text-muted">
                    <i class="fas fa-image fa-4x mb-3"></i>
                    <p>Sin imagen</p>
                </div>`;
            }
            
            // Configurar el botón de compartir
            document.getElementById('compartir-btn').onclick = function(e) {
                e.preventDefault();
                if (navigator.share) {
                    navigator.share({
                        title: caso.titulo,
                        text: `Caso de éxito: ${caso.titulo} - ${caso.nombre_paciente}`,
                        url: window.location.href,
                    })
                    .catch((error) => console.log('Error al compartir:', error));
                } else {
                    alert('La función de compartir no está disponible en este navegador');
                }
            };
            
            const modalDetalle = new bootstrap.Modal(document.getElementById('modalDetalle'));
            modalDetalle.show();
        }
    </script>
</body>
</html>

<?php
// Cerrar la conexión
mysqli_close($conexion);
?>
