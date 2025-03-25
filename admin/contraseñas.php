<?php
session_start();
require_once '../conexion.php';

// Verificar si el usuario está logueado y es recepcionista
// ... (tu código de verificación aquí)

// Procesar el formulario de actualización de contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paciente = $_POST['id_paciente'];
    $nueva_password = $_POST['nueva_password'];
    
    // Obtener el email del paciente
    $stmt_email = $conexion->prepare("SELECT email, nombre FROM pacientes WHERE id_paciente = ?");
    $stmt_email->bind_param("i", $id_paciente);
    $stmt_email->execute();
    $result = $stmt_email->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $email_paciente = $row['email'];
        $nombre_paciente = $row['nombre'];
        
        // Crear hash de contraseña
        $password_hash = md5($nueva_password);
        
        // Actualizar la contraseña en la base de datos
        $stmt = $conexion->prepare("UPDATE pacientes SET password = ? WHERE id_paciente = ?");
        $stmt->bind_param("si", $password_hash, $id_paciente);
        
        if ($stmt->execute()) {
            // Enviar correo con la nueva contraseña
            $asunto = "Nueva contraseña para su cuenta de paciente";
            
            // Crear el cuerpo del mensaje
            $mensaje_email = "
            <html>
            <head>
                <title>Nueva contraseña para su cuenta</title>
            </head>
            <body>
                <p>Estimado/a <strong>{$nombre_paciente}</strong>,</p>
                <p>Le informamos que se ha generado una nueva contraseña para acceder a su cuenta de paciente en nuestro sistema.</p>
                <p>Su nueva contraseña es: <strong>{$nueva_password}</strong></p>
                <p>Por motivos de seguridad, le recomendamos cambiar esta contraseña una vez acceda al sistema.</p>
                <p>Si usted no solicitó este cambio, por favor contacte inmediatamente con nuestra clínica.</p>
                <p>Saludos cordiales,</p>
                <p>El equipo médico</p>
            </body>
            </html>
            ";
            
            // Cabeceras para enviar correo HTML
            $cabeceras  = "MIME-Version: 1.0\r\n";
            $cabeceras .= "Content-type: text/html; charset=UTF-8\r\n";
            $cabeceras .= "From: noreply@clinica.com\r\n";
            
            // Enviar el correo
            if (mail($email_paciente, $asunto, $mensaje_email, $cabeceras)) {
                $mensaje = "Contraseña actualizada con éxito y enviada por correo electrónico";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Contraseña actualizada, pero hubo un error al enviar el correo electrónico";
                $tipo_mensaje = "warning";
            }
        } else {
            $mensaje = "Error al actualizar la contraseña";
            $tipo_mensaje = "danger";
        }
    } else {
        $mensaje = "No se encontró el correo electrónico del paciente";
        $tipo_mensaje = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Contraseñas de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        .page-header {
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid #eaeaea;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .page-title {
            font-weight: 600;
            color: #2b3445;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .title-icon {
            background: linear-gradient(135deg, #3a7bd5, #00d2ff);
            color: white;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 18px;
        }
        
        .table-container {
            max-height: 600px;
            overflow-y: auto;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        
        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table thead tr {
            background: linear-gradient(135deg, #f6f9fc, #f1f4f9);
        }
        
        .table thead th {
            color: #4a5568;
            font-weight: 600;
            border-bottom: none;
            padding: 16px;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        
        .table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-top: 1px solid #f0f0f0;
            font-size: 14px;
        }
        
        .table tbody tr:hover {
            background-color: #f9fbfd;
            transition: background-color 0.2s ease;
        }
        
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #fcfcfc;
        }
        
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
            position: relative;
        }
        
        .status-indicator::after {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border-radius: 50%;
            border: 1px solid;
            opacity: 0.3;
        }
        
        .has-password {
            background-color: #10b981;
        }
        
        .has-password::after {
            border-color: #10b981;
        }
        
        .no-password {
            background-color: #ef4444;
        }
        
        .no-password::after {
            border-color: #ef4444;
        }
        
        .search-container {
            max-width: 350px;
            margin-bottom: 20px;
        }
        
        .search-input {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            padding-left: 42px;
            font-size: 14px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: #3a7bd5;
            box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            z-index: 10;
        }
        
        .btn-action {
            border-radius: 8px;
            font-weight: 500;
            padding: 8px 16px;
            font-size: 13px;
            letter-spacing: 0.3px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3a7bd5, #00d2ff);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2e69c2, #00b8e0);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(58, 123, 213, 0.2);
        }
        
        .alert {
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 20px;
            font-weight: 500;
            border: none;
        }
        
        .alert-success {
            background-color: #ecfdf5;
            color: #10b981;
        }
        
        .alert-danger {
            background-color: #fef2f2;
            color: #ef4444;
        }
        
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .modal-header {
            background: linear-gradient(135deg, #f6f9fc, #f1f4f9);
            border-bottom: 1px solid #eaeaea;
            padding: 20px;
            border-radius: 15px 15px 0 0;
        }
        
        .modal-title {
            font-weight: 600;
            color: #2b3445;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .modal-body {
            padding: 24px;
        }
        
        .modal-footer {
            border-top: 1px solid #eaeaea;
            padding: 16px 24px;
            border-radius: 0 0 15px 15px;
        }
        
        .form-label {
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-control {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #3a7bd5;
            box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.1);
        }
        
        .form-text {
            font-size: 12px;
            color: #718096;
            margin-top: 8px;
        }
        
        .btn-outline-secondary {
            color: #718096;
            border-color: #e2e8f0;
        }
        
        .btn-outline-secondary:hover {
            background-color: #f9fafb;
            color: #4a5568;
        }
        
        .empty-state {
            padding: 3rem;
            text-align: center;
            color: #718096;
        }
        
        .patient-name {
            font-weight: 500;
            color: #2b3445;
        }
        
        .patient-email, .patient-phone {
            color: #718096;
        }
        
        /* Animaciones */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
      
    .btn-volver {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        background-color: #f8f9fa;
        color: #0d6efd;
        text-decoration: none;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
        margin: 10px 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    
    .btn-volver:hover {
        background-color: #e9ecef;
        color: #0a58ca;
        text-decoration: none;
        border-color: #ced4da;
    }
    
    .btn-volver i {
        margin-right: 8px;
    }
    
    .btn-volver:active {
        transform: translateY(1px);
        box-shadow: none;
    }
</style>
   
</head>
<body>
<a href="./index.php" class="btn-volver">
    <i class="fas fa-arrow-left"></i> Volver
</a>
    <div class="main-container fade-in">
        <div class="page-header">
            <h2 class="page-title">
                <span class="title-icon"><i class="fas fa-key"></i></span>
                Gestionar Contraseñas de Pacientes
            </h2>
        </div>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?php echo $tipo_mensaje === 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
                <?php echo $mensaje; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="search-container position-relative">
            <i class="fas fa-search search-icon"></i>
            <input type="text" 
                   id="searchInput" 
                   class="form-control search-input" 
                   placeholder="Buscar paciente por nombre, email o teléfono..."
                   autocomplete="off">
        </div>

        <div class="table-container">
            <table class="table table-hover table-striped">
                <thead class="sticky-top">
                    <tr>
                        <th width="5%">Estado</th>
                        <th width="30%">Nombre</th>
                        <th width="25%">Email</th>
                        <th width="20%">Teléfono</th>
                        <th width="20%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM pacientes ORDER BY nombre";
                    $result = $conexion->query($query);
                    
                    if ($result->num_rows > 0):
                        while ($paciente = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td>
                            <span class="status-indicator <?php echo empty($paciente['password']) ? 'no-password' : 'has-password'; ?>"
                                  title="<?php echo empty($paciente['password']) ? 'Sin contraseña' : 'Con contraseña'; ?>">
                            </span>
                            <span class="visually-hidden">
                                <?php echo empty($paciente['password']) ? 'Sin contraseña' : 'Con contraseña'; ?>
                            </span>
                        </td>
                        <td class="patient-name"><?php echo htmlspecialchars($paciente['nombre']); ?></td>
                        <td class="patient-email"><?php echo htmlspecialchars($paciente['email']); ?></td>
                        <td class="patient-phone"><?php echo htmlspecialchars($paciente['telefono']); ?></td>
                        <td>
                            <button type="button" 
                                    class="btn btn-primary btn-action"
                                    data-bs-toggle="modal"
                                    data-bs-target="#passwordModal"
                                    data-id="<?php echo $paciente['id_paciente']; ?>"
                                    data-nombre="<?php echo htmlspecialchars($paciente['nombre']); ?>">
                                <i class="fas fa-key"></i> 
                                <?php echo empty($paciente['password']) ? 'Asignar' : 'Cambiar'; ?>
                            </button>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                    <tr>
                        <td colspan="5" class="empty-state">
                            <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                            <p>No hay pacientes registrados en el sistema.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para asignar/cambiar contraseña -->
    <div class="modal fade" id="passwordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-key"></i> 
                        Gestionar Contraseña
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id_paciente" id="pacienteId">
                        <p class="mb-4">Asignar contraseña para: <strong id="pacienteNombre"></strong></p>
                        
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <div class="input-group">
                                <input type="password" 
                                       name="nueva_password" 
                                       id="nuevaPassword" 
                                       class="form-control" 
                                       required 
                                       minlength="6">
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                La contraseña debe tener al menos 6 caracteres
                            </div>
                        </div>

                        <div class="password-strength mt-3 d-none" id="passwordStrength">
                            <div class="mb-2 d-flex justify-content-between">
                                <small>Seguridad de la contraseña:</small>
                                <small id="strengthText">Débil</small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-danger" id="strengthBar" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Guardar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Búsqueda en tiempo real
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
            
            // Mostrar mensaje si no hay resultados
            const visibleRows = document.querySelectorAll('tbody tr[style=""]').length;
            const emptyState = document.querySelector('.empty-state');
            
            if (visibleRows === 0 && !emptyState) {
                const tbody = document.querySelector('tbody');
                const tr = document.createElement('tr');
                tr.className = 'empty-state-search';
                tr.innerHTML = `
                    <td colspan="5" class="empty-state">
                        <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                        <p>No se encontraron pacientes que coincidan con "<strong>${searchTerm}</strong>"</p>
                    </td>
                `;
                tbody.appendChild(tr);
            } else if (visibleRows > 0) {
                const emptySearch = document.querySelector('.empty-state-search');
                if (emptySearch) {
                    emptySearch.remove();
                }
            }
        });

        // Mostrar/ocultar contraseña
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('nuevaPassword');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Configurar modal
        const passwordModal = document.getElementById('passwordModal');
        passwordModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            
            document.getElementById('pacienteId').value = id;
            document.getElementById('pacienteNombre').textContent = nombre;
            
            // Limpiar el campo de contraseña
            document.getElementById('nuevaPassword').value = '';
            
            // Ocultar el indicador de fortaleza
            document.getElementById('passwordStrength').classList.add('d-none');
        });
        
        // Verificar fortaleza de contraseña
        document.getElementById('nuevaPassword').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            const strengthContainer = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthContainer.classList.add('d-none');
                return;
            }
            
            strengthContainer.classList.remove('d-none');
            
            // Calcular fortaleza
            let strength = 0;
            
            // Longitud
            if (password.length >= 6) strength += 20;
            if (password.length >= 8) strength += 10;
            
            // Complejidad
            if (/[a-z]/.test(password)) strength += 10;
            if (/[A-Z]/.test(password)) strength += 20;
            if (/[0-9]/.test(password)) strength += 20;
            if (/[^a-zA-Z0-9]/.test(password)) strength += 20;
            
            // Actualizar UI
            strengthBar.style.width = strength + '%';
            
            if (strength < 40) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Débil';
                strengthText.className = 'text-danger';
            } else if (strength < 70) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Media';
                strengthText.className = 'text-warning';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Fuerte';
                strengthText.className = 'text-success';
            }
        });
        
        // Animación de alerta
        const alertElement = document.querySelector('.alert');
        if (alertElement) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertElement);
                bsAlert.close();
            }, 4000);
        }
    </script>
</body>
</html>