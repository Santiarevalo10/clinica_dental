<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

// Conectar a la base de datos
require '../Conexion/conexion.php';

$errores = [];
$mensaje = "";

// Obtener el ID de la especialidad desde la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID de especialidad inválido.');
}

$especialidad_id = (int) $_GET['id'];

// Obtener la información actual de la especialidad
try {
    $stmt = $pdo->prepare('SELECT * FROM especialidades WHERE id = :id');
    $stmt->execute([':id' => $especialidad_id]);
    $especialidad = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$especialidad) {
        die('Especialidad no encontrada.');
    }
} catch (PDOException $e) {
    die('Error al obtener especialidad: ' . $e->getMessage());
}

// Obtener sedes y doctores para los dropdowns
try {
    $sedesStmt = $pdo->query('SELECT id, nombre FROM sedes');
    $sedes = $sedesStmt->fetchAll(PDO::FETCH_ASSOC);

    $doctoresStmt = $pdo->query('SELECT id, nombre FROM doctores');
    $doctores = $doctoresStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error al obtener sedes o doctores: ' . $e->getMessage());
}

// Manejar el formulario de edición de especialidad
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $sede_id = $_POST['sede_id'];
    $doctor_id = $_POST['doctor_id'];

    try {
        // Iniciar una transacción
        $pdo->beginTransaction();

        // Obtener el nombre del doctor si se selecciona uno nuevo
        $doctor_nombre = '';
        if ($doctor_id) {
            $stmt = $pdo->prepare('SELECT nombre FROM doctores WHERE id = :doctor_id');
            $stmt->execute([':doctor_id' => $doctor_id]);
            $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
            $doctor_nombre = $doctor['nombre'];
        }

        // Actualizar en la tabla especialidades
        $stmt = $pdo->prepare('UPDATE especialidades SET nombre = :nombre, sede_id = :sede_id, doctor_id = :doctor_id, doctor = :doctor_nombre WHERE id = :id');
        $stmt->execute([
            ':nombre' => $nombre,
            ':sede_id' => $sede_id,
            ':doctor_id' => $doctor_id,
            ':doctor_nombre' => $doctor_nombre,
            ':id' => $especialidad_id
        ]);

        // Confirmar la transacción
        $pdo->commit();

        // Redirigir a la página de especialidades
        header('Location: especialidades.php');
        exit;
    } catch (PDOException $e) {
        // Deshacer la transacción en caso de error
        $pdo->rollBack();
        die('Error al actualizar especialidad: ' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Especialidad</title>
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
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
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

        .footer {
            color: black;
            text-align: center;
            padding: 1rem;
        }

        .container-principal h2 {
            font-family: 'Arial Black', sans-serif;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 20px;
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
            <h2>Editar Especialidad</h2>

            <form action="editar_especialidad.php?id=<?= $especialidad_id ?>" method="post">
                <div class="form-group">
                    <label for="nombre">Nombre de Especialidad:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($especialidad['nombre']) ?>"
                           required>
                </div>
                <div class="form-group">
                    <label for="sede_id">Sede:</label>
                    <select id="sede_id" name="sede_id" required>
                        <?php foreach ($sedes as $sede): ?>
                            <option value="<?= $sede['id'] ?>" <?= $sede['id'] == $especialidad['sede_id'] ? 'selected' : '' ?>><?= htmlspecialchars($sede['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="doctor_id">Doctor:</label>
                    <select id="doctor_id" name="doctor_id">
                        <option value="">Ninguno</option>
                        <?php foreach ($doctores as $doctor): ?>
                            <option value="<?= $doctor['id'] ?>" <?= $doctor['id'] == $especialidad['doctor_id'] ? 'selected' : '' ?>><?= htmlspecialchars($doctor['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-actualizar">Actualizar Especialidad</button>
            </form>

        </section>
    </div>
    
</body>

</html>