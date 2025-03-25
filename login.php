<?php
session_start();
include 'conexion.php'; // Asegúrate de que este archivo contenga la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta para verificar si el usuario existe
    $sql = "SELECT contrasena, rol, nombre FROM usuarios WHERE usuario = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) > 0) {
        // Si el usuario existe, obtener el hash de la contraseña y el nombre
        $fila = mysqli_fetch_assoc($resultado);
        // Verificar la contraseña
        if (password_verify($contrasena, $fila['contrasena'])) {
            // Si las credenciales son correctas, establecer la sesión
            $_SESSION['rol'] = $fila['rol'];
            $_SESSION['nombre'] = $fila['nombre']; // Almacenar el nombre en la sesión
            
            // Redirigir a la página correspondiente
            if ($_SESSION['rol'] === 'recepcionista') {
                header("Location: ./admin/recepcionista.php");
            } elseif ($_SESSION['rol'] === 'doctor') {
                header("Location: ./admin/doctor.php");
            }
            exit;
        } else {
            // Si la contraseña es incorrecta
            echo '<div class="alert alert-danger">Usuario o contraseña incorrectos.</div>';
        }
    } else {
        // Si el usuario no existe
        echo '<div class="alert alert-danger">Usuario o contraseña incorrectos.</div>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            height: 100vh;
            background: linear-gradient(45deg, #1a73e8, #289cf5, #02b3e4);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header i {
            font-size: 60px;
            background: linear-gradient(45deg, #1a73e8, #02b3e4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
            display: inline-block;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .login-header h2 {
            color: #333;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 0.9em;
        }

        .form-group {
            position: relative;
            margin-bottom: 25px;
        }

        .form-control {
            height: 50px;
            padding-left: 45px;
            font-size: 1rem;
            border: 2px solid #e1e1e1;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 0.2rem rgba(26, 115, 232, 0.25);
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #1a73e8;
            font-size: 1.2em;
            transition: all 0.3s ease;
        }

        .form-control:focus + .input-icon {
            color: #1a73e8;
            transform: translateY(-50%) scale(1.1);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            background: linear-gradient(45deg, #1a73e8, #02b3e4);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-login:hover {
            background: linear-gradient(45deg, #1557b0, #0282a8);
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(0,0,0,0.2);
        }

        .btn-login i {
            margin-right: 8px;
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .register-link p {
            color: #666;
            margin-bottom: 0;
        }

        .register-link a {
            color: #1a73e8;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: #1557b0;
            text-decoration: none;
        }

        /* Animación para los campos de entrada */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            animation: slideIn 0.5s ease forwards;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-user-md"></i>
            <h2>Bienvenido</h2>
            <p>Ingresa a tu cuenta para continuar</p>
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" required>
                <i class="fas fa-user input-icon"></i>
            </div>

            <div class="form-group">
                <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña" required>
                <i class="fas fa-lock input-icon"></i>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i>
                Iniciar Sesión
            </button>
        </form>

        <div class="register-link">
            <p>¿No tienes una cuenta? <a href="registro.php">Crear cuenta</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 