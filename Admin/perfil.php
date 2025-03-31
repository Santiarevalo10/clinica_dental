<?php
session_start();

// Inicializar variables de error y éxito
$error = '';
$success = '';

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

// Conectar a la base de datos
require '../Conexion/conexion.php';

try {
    // Obtener información del usuario desde la base de datos
    $stmt = $pdo->prepare('
        SELECT nombre, email, direccion, telefono, fecha_nacimiento
        FROM usuarios
        WHERE id = :usuario_id
    ');
    $stmt->execute(['usuario_id' => $_SESSION['usuario_id']]);
    $administrador = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si la consulta retornó resultados válidos
    if (!$administrador) {
        $error = 'No se encontraron datos del administrador.';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $contrasena_confirmar = $_POST['contrasena_confirmar'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';

        // Validar contraseñas
        if ($contrasena !== '' && $contrasena !== $contrasena_confirmar) {
            $error = 'Las contraseñas no coinciden.';
        }

        if (empty($error)) {
            // Actualizar datos del usuario
            $update_user = 'UPDATE usuarios SET nombre = :nombre, email = :email, direccion = :direccion, telefono = :telefono, fecha_nacimiento = :fecha_nacimiento' .
                ($contrasena ? ', contrasena = :contrasena' : '') .
                ' WHERE id = :id';
            $stmt = $pdo->prepare($update_user);
            $params = [
                'nombre' => $nombre,
                'email' => $email,
                'direccion' => $direccion,
                'telefono' => $telefono,
                'fecha_nacimiento' => $fecha_nacimiento,
                'id' => $_SESSION['usuario_id'],
            ];
            if ($contrasena) {
                $params['contrasena'] = password_hash($contrasena, PASSWORD_DEFAULT);
            }
            $stmt->execute($params);

            $success = 'Perfil actualizado exitosamente.';
        }
    }
} catch (PDOException $e) {
    $error = 'Error en la consulta: ' . $e->getMessage();
} catch (Exception $e) {
    $error = 'Error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: rgb(15, 30, 80);
            color: white;
            padding: 1rem;
            height: 100vh;
            position: fixed;
        }

        .sidebar h2 {
            color: rgb(235, 247, 245);
            margin-bottom: 2rem;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 0.75rem;
            transition: background-color 0.3s ease;
        }

        .sidebar li a:hover {
            background-color: rgb(81, 168, 226);
        }

        .content {
            margin-left: 250px;
            padding: 2rem;
            width: calc(100% - 250px);
        }

        .container-principal {
            width: 100%;
            padding: 2rem;
            background-color: rgba(255, 255, 255, 0.8); /* Fondo semi-transparente */
            border-radius: 8px; /* Bordes redondeados */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra sutil */
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: 600;
        }

        .table tr:hover {
            background-color: #f9f9f9;
        }

        .btn-perfil {
            display: inline-flex; /* Usar inline-flex */
            align-items: center; /* Centrar verticalmente */
            justify-content: center; /* Centrar horizontalmente */
            background-color: #1a237e; /* Azul oscuro */
            color: white;
            padding: 0.75rem 1.5rem; /* Ajustar padding */
            text-decoration: none;
            border-radius: 5px;
            margin-top: 1.5rem; /* Ajustar margen superior */
            transition: background-color 0.3s ease, transform 0.2s ease;
            font-size: 1rem;
            border: none;
            cursor: pointer;
        }

        .btn-perfil:hover {
            background-color: #0d124a;
            transform: translateY(-2px);
        }

        .container-principal h2 {
            font-family: 'Arial Black', sans-serif; /* Fuente llamativa */
            text-align: center;
            text-transform: uppercase; /* Asegura que esté en mayúsculas */
            margin-bottom: 20px;
        }

        .error {
            color: #ff0000;
            margin-bottom: 1rem;
        }

        .exito {
            color: #008000;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Clínica Dental</h2>
        <ul>
            <li><a href="principalAdmin.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="ver_citas.php"><i class="fas fa-calendar-alt"></i> Ver Citas</a></li>
            <li><a href="ver_historial.php"><i class="fas fa-history"></i> Historial Clínico</a></li>
            <li><a href="perfil.php"><i class="fas fa-user"></i> Mi Perfil</a></li>
            <li><a href="usuarios.php"><i class="fas fa-users"></i> Usuarios</a></li>
            <li><a href="ver_solicitudes.php"><i class="fas fa-envelope"></i> Solicitudes</a></li>
            <li><a href="sedes.php"><i class="fas fa-map-marker-alt"></i> Sedes</a></li>
            <li><a href="especialidades.php"><i class="fas fa-stethoscope"></i> Especialidades</a></li>
            <li><a href="productos.php"><i class="fas fa-shopping-cart"></i> Productos</a></li>
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
        </ul>
    </div>
    <div class="content">
        <section class="container-principal">
            <h2>Mi Perfil</h2>
            <?php if ($error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p class="exito"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>
            <table class="table">
                <tbody>
                    <tr>
                        <th>Nombre:</th>
                        <td><?php echo htmlspecialchars($administrador['nombre']); ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo htmlspecialchars($administrador['email']); ?></td>
                    </tr>
                    <tr>
                        <th>Teléfono:</th>
                        <td><?php echo htmlspecialchars($administrador['telefono']); ?></td>
                    </tr>
                    <tr>
                        <th>Dirección:</th>
                        <td><?php echo htmlspecialchars($administrador['direccion']); ?></td>
                    </tr>
                    <tr>
                        <th>Fecha de Nacimiento:</th>
                        <td><?php echo htmlspecialchars($administrador['fecha_nacimiento']); ?></td>
                    </tr>
                </tbody>
            </table>
            <a href="editar_perfil.php" class="btn-perfil">Editar Perfil</a>
            <p style="text-align: center; margin-top: 20px;">&copy; 2024 Clínica Dental del Dr. Fabián Mora. Todos los derechos reservados.</p>
        </section>
    </div>
</body>

</html>