<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol'];

    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) > 0) {
        echo '<div class="alert alert-danger">El usuario ya existe. Por favor, elige otro nombre de usuario.</div>';
    } else {
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (usuario, contrasena, rol, nombre) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $usuario, $contrasena_hash, $rol, $nombre);
        mysqli_stmt_execute($stmt);
        echo '<div class="alert alert-success">Cuenta creada exitosamente. Puedes iniciar sesión ahora.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5 d-flex justify-content-center">
        <div class="card shadow-lg p-4" style="width: 400px; border-radius: 12px;">
            <h2 class="text-center mb-4">Crear Cuenta</h2>
            <form method="POST" action="">
                <!-- Usuario -->
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" name="usuario" required>
                    </div>
                </div>

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input type="password" class="form-control" name="contrasena" required>
                    </div>
                </div>

                <!-- Rol -->
                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-user-tag"></i></span>
                        <select class="form-select" name="rol" required>
                            <option value="recepcionista">Recepcionista</option>
                            <option value="doctor">Doctor</option>
                        </select>
                    </div>
                </div>

                <!-- Botón de envío -->
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa fa-user-plus"></i> Crear Cuenta
                </button>
            </form>

            <!-- Enlace para iniciar sesión -->
            <p class="mt-3 text-center">¿Ya tienes una cuenta? 
                <a href="login.php" class="text-decoration-none">Inicia sesión aquí</a>.
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
