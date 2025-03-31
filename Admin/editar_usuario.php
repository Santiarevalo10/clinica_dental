<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

// Conectar a la base de datos
require '../Conexion/conexion.php';

// Verificar si se ha enviado un ID válido
$id = $_GET['id'] ?? null;

if (!$id) {
    die('ID de usuario no proporcionado.');
}

// Inicializar $sede_id
$sede_id = null;

// Procesar la acción si se envía una solicitud POST para eliminar un historial
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $rol = $_POST['rol'] ?? '';
    $especialidad = $_POST['especialidad'] ?? '';
    $sede_id = $_POST['sedes'] ?? null;

    if ($nombre && $email && $direccion && $telefono && $fecha_nacimiento && $rol) {
        try {
            // Actualizar el usuario en la tabla `usuarios`
            $stmt = $pdo->prepare('UPDATE usuarios 
                                    SET nombre = :nombre, email = :email, direccion = :direccion, telefono = :telefono, fecha_nacimiento = :fecha_nacimiento, rol = :rol 
                                    WHERE id = :id');
            $stmt->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':direccion' => $direccion,
                ':telefono' => $telefono,
                ':fecha_nacimiento' => $fecha_nacimiento,
                ':rol' => $rol,
                ':id' => $id
            ]);

            // Limpiar las tablas específicas para el rol anterior
            $stmt = $pdo->prepare('DELETE FROM doctores WHERE usuario_id = :id');
            $stmt->execute([':id' => $id]);

            $stmt = $pdo->prepare('DELETE FROM pacientes WHERE usuario_id = :id');
            $stmt->execute([':id' => $id]);

            // Insertar o actualizar en la tabla correspondiente según el nuevo rol
            if ($rol === 'doctor') {
                $stmt = $pdo->prepare('
                    INSERT INTO doctores (usuario_id, sede_id, nombre, direccion, telefono, fecha_nacimiento, especialidad, email) 
                    VALUES (:id, :sede_id, :nombre, :direccion, :telefono, :fecha_nacimiento, :especialidad, :email)
                    ON DUPLICATE KEY UPDATE 
                        sede_id = VALUES(sede_id), 
                        nombre = VALUES(nombre), 
                        direccion = VALUES(direccion), 
                        telefono = VALUES(telefono), 
                        fecha_nacimiento = VALUES(fecha_nacimiento), 
                        especialidad = VALUES(especialidad), 
                        email = VALUES(email)
                ');
                $stmt->execute([
                    ':id' => $id,
                    ':sede_id' => $sede_id,
                    ':direccion' => $direccion,
                    ':telefono' => $telefono,
                    ':fecha_nacimiento' => $fecha_nacimiento,
                    ':especialidad' => $especialidad,
                    ':email' => $email
                ]);
            } elseif ($rol === 'paciente') {
                $stmt = $pdo->prepare('INSERT INTO pacientes (usuario_id, nombre, direccion, telefono, fecha_nacimiento, email) 
                                            VALUES (:id, :nombre, :direccion, :telefono, :fecha_nacimiento, :email)
                                            ON DUPLICATE KEY UPDATE nombre = VALUES(nombre), direccion = VALUES(direccion), telefono = VALUES(telefono), fecha_nacimiento = VALUES(fecha_nacimiento), email = VALUES(email)');
                $stmt->execute([
                    ':id' => $id,
                    ':nombre' => $nombre,
                    ':direccion' => $direccion,
                    ':telefono' => $telefono,
                    ':fecha_nacimiento' => $fecha_nacimiento,
                    ':email' => $email
                ]);
            } elseif ($rol === 'administrador') {
                $stmt = $pdo->prepare('INSERT INTO administradores (usuario_id, nombre, direccion, telefono, fecha_nacimiento, email) 
                                            VALUES (:id, :nombre, :direccion, :telefono, :fecha_nacimiento, :email)
                                            ON DUPLICATE KEY UPDATE nombre = VALUES(nombre), direccion = VALUES(direccion), telefono = VALUES(telefono), fecha_nacimiento = VALUES(fecha_nacimiento), email = VALUES(email)');
                $stmt->execute([
                    ':id' => $id,
                    ':nombre' => $nombre,
                    ':direccion' => $direccion,
                    ':telefono' => $telefono,
                    ':fecha_nacimiento' => $fecha_nacimiento,
                    ':email' => $email
                ]);
            }

            header('Location: usuarios.php');
            exit;
        } catch (PDOException $e) {
            die('Error al actualizar el usuario: ' . $e->getMessage());
        }
    } else {
        echo 'Por favor, completa todos los campos.';
    }
}

// Obtener los detalles del usuario
try {
    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die('Usuario no encontrado.');
    }

    // Si el usuario es doctor, obtener la especialidad y sede
    if ($usuario['rol'] === 'doctor') {
        $stmt = $pdo->prepare('SELECT especialidad, sede_id FROM doctores WHERE usuario_id = :id');
        $stmt->execute([':id' => $id]);
        $doctorData = $stmt->fetch(PDO::FETCH_ASSOC);
        $especialidad = $doctorData['especialidad'] ?? '';
        $sede_id = $doctorData['sede_id'] ?? null;
    }

    // Obtener la lista de sedes
    try {
        $stmt = $pdo->query('SELECT id, nombre FROM sedes');
        $sedes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('Error al obtener sedes: ' . $e->getMessage());
    }
} catch (PDOException $e) {
    die('Error al obtener datos: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        function toggleEspecialidad() {
            var rol = document.getElementById('rol').value;
            var especialidadSection = document.getElementById('especialidad-section');
            if (rol === 'doctor') {
                especialidadSection.style.display = 'block';
            } else {
                especialidadSection.style.display = 'none';
            }
        }

        window.onload = function() {
            toggleEspecialidad();
        };
    </script>
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
            display: flex;
            align-items: center;
        }

        .sidebar li a i {
            margin-right: 10px;
        }

        .sidebar li a:hover {
            background-color: rgb(81, 168, 226);
        }

        .content {
            margin-left: 250px;
            padding: 2rem;
            width: calc(100% - 250px);
        }

        .container {
            width: 100%;
            padding: 2rem;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group input[type="email"],
        .form-group input[type="date"],
        .form-group select {
            width: calc(100% - 12px);
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .btn-actualizar {
            display: inline-block;
            background-color: #1a237e; /* Azul oscuro */
            color: white;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            border: none;
            cursor: pointer;
        }

        .btn-actualizar:hover {
            background-color: #0d124a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .container h2 {
            font-family: 'Arial Black', sans-serif;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .footer {
            color: black;
            text-align: center;
            padding: 1rem;
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
        <section class="container">
            <h2>Editar Usuario</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>"
                           required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>"
                           required>
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion"
                           value="<?= htmlspecialchars($usuario['direccion'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono"
                           value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                           value="<?= htmlspecialchars($usuario['fecha_nacimiento'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select id="rol" name="rol" onchange="toggleEspecialidad()" required>
                        <option value="">Seleccione un rol</option>
                        <option value="doctor" <?= ($usuario['rol'] ?? '') === 'doctor' ? 'selected' : '' ?>>Doctor</option>
                        <option value="paciente" <?= ($usuario['rol'] ?? '') === 'paciente' ? 'selected' : '' ?>>Paciente
                        </option>
                        <option
                            value="administrador" <?= ($usuario['rol'] ?? '') === 'administrador' ? 'selected' : '' ?>>
                            Administrador
                        </option>
                    </select>
                </div>
                <div id="especialidad-section"
                     style="display: <?= ($usuario['rol'] ?? '') === 'doctor' ? 'block' : 'none' ?>">
                    <label for="especialidad">Especialidad:</label>
                    <input type="text" id="especialidad" name="especialidad"
                           value="<?= htmlspecialchars($especialidad ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="sedes">Sede:</label>
                    <select id="sedes" name="sedes">
                        <?php foreach ($sedes as $sede): ?>
                            <option value="<?= $sede['id'] ?>" <?= isset($sede_id) && ($sede['id'] == $sede_id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sede['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="submit" class="btn-actualizar" value="Actualizar Usuario">
            </form>
        </section>
    </div>

   
</body>

</html>