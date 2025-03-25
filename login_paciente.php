<?php
session_start();
include 'conexion.php';
$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT id_paciente, nombre, email FROM pacientes WHERE email = ? AND password = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, md5($password));
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    
    if ($fila = mysqli_fetch_assoc($resultado)) {
        $_SESSION['id_paciente'] = $fila['id_paciente'];
        $_SESSION['nombre'] = $fila['nombre'];
        $_SESSION['email'] = $fila['email'];
        $_SESSION['tipo_usuario'] = 'paciente';
        
        header("Location: solicitar.php");
        exit;
    } else {
        $mensaje = [
            'tipo' => 'error',
            'titulo' => 'Error',
            'texto' => 'Email o contraseña incorrectos'
        ];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f6f8fb 0%, #e9f0f7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            max-width: 400px;
            width: 90%;
            padding: 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header i {
            font-size: 3rem;
            color: #4299e1;
            margin-bottom: 15px;
        }
        
        .login-header h2 {
            color: #2d3748;
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            color: #4a5568;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }
        
        .form-label i {
            color: #4299e1;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }
        
        .login-btn {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            background: linear-gradient(135deg, #4299e1, #3182ce);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 153, 225, 0.2);
        }
        
        .register-link {
            text-align: center;
            color: #4a5568;
        }
        
        .register-link a {
            color: #4299e1;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .register-link a:hover {
            color: #2b6cb0;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-user-circle"></i>
            <h2>confirma que eres para volver a solicitar</h2>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-envelope"></i>
                    Email
                </label>
                <input type="email" class="form-control" name="email" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-lock"></i>
                    Contraseña
                </label>
                <input type="password" class="form-control" name="password" required>
            </div>
            
            <button type="submit" class="login-btn">
                <i class="fas fa-sign-in-alt me-2"></i>
                Iniciar Sesión
            </button>
            
            <div class="register-link">
                ¿No tienes una cuenta? 
                <a href="registro_paciente.php">Regístrate aquí</a>
            </div>
        </form>
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
</body>
</html> 