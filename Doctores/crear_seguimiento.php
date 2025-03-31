<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de doctor
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'doctor') {
    header('Location: ../login.php');
    exit;
}

// Conectar a la base de datos
require '../Conexion/conexion.php';

$doctor_id = $_SESSION['usuario_id']; // Usar el usuario_id del doctor desde la sesión
$pacientes = [];
$errores = [];

// Obtener el ID del doctor desde la base de datos
try {
    $stmt = $pdo->prepare('
        SELECT id FROM doctores WHERE usuario_id = :usuario_id
    ');
    $stmt->bindParam(':usuario_id', $doctor_id, PDO::PARAM_INT);
    $stmt->execute();
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($doctor) {
        $doctor_id = $doctor['id'];
    } else {
        $errores[] = 'No se encontró el ID del doctor.';
    }
} catch (PDOException $e) {
    error_log('Error al obtener el ID del doctor: ' . $e->getMessage());
    $errores[] = 'Error al obtener el ID del doctor. Inténtelo de nuevo más tarde.';
}

// Obtener la lista de pacientes asociados al doctor
try {
    $stmt = $pdo->prepare('
        SELECT paciente.id, paciente.nombre 
        FROM pacientes AS paciente
        JOIN citas ON paciente.id = citas.paciente_id
        JOIN doctores AS doctor ON citas.doctor_id = doctor.id
        WHERE doctor.id = :doctor_id
        GROUP BY paciente.id, paciente.nombre
    ');
    $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
    $stmt->execute();
    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Error en la consulta de pacientes: ' . $e->getMessage());
    $errores[] = 'Error al obtener los pacientes. Inténtelo de nuevo más tarde.';
}

// Procesar el formulario cuando se envía una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paciente_id = $_POST['paciente_id'];
    $detalle = $_POST['detalle'];

    try {
        // Insertar el nuevo seguimiento
        $stmt = $pdo->prepare('
            INSERT INTO seguimientos (paciente_id, doctor_id, fecha, detalle)
            VALUES (:paciente_id, :doctor_id, NOW(), :detalle)
        ');
        $stmt->execute([
            ':paciente_id' => $paciente_id,
            ':doctor_id' => $doctor_id,
            ':detalle' => $detalle
        ]);

        // Redirigir a la página de seguimientos con un mensaje de éxito
        header('Location: ver_paciente.php?mensaje=Seguimiento creado exitosamente');
        exit;
    } catch (PDOException $e) {
        $error = 'Error al crear el seguimiento: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Seguimiento</title>
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

        .container-principal {
            width: 100%;
            padding: 2rem;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-guardar {
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

        .btn-guardar:hover {
            background-color: #0d124a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-cancelar {
            display: inline-block;
            background-color: #800000; /* Vinotinto */
            color: white;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            border: none;
            cursor: pointer;
        }

        .btn-cancelar:hover {
            background-color: #590000;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .container-principal h2 {
            font-family: 'Arial Black', sans-serif;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .copyright-text {
            text-align: center;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Clínica Dental</h2>
        <ul>
            <li><a href="principal_doctores.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="ver_citas.php"><i class="fas fa-calendar-alt"></i> Ver Citas</a></li>
            <li><a href="ver_paciente.php"><i class="fas fa-users"></i> Mis pacientes</a></li>
            <li><a href="seguimiento.php"><i class="fas fa-notes-medical"></i> Seguimiento</a></li>
            <li><a href="ver_historial.php"><i class="fas fa-history"></i> Historiales</a></li>
            <li><a href="perfil.php"><i class="fas fa-user"></i> Mi Perfil</a></li>
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
        </ul>
    </div>
    <div class="content">
        <section class="container-principal">
            <h2>Agregar Seguimiento</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
            <form method="post" action="crear_seguimiento.php">
                <div class="form-group">
                    <label for="paciente_id">Paciente:</label>
                    <select id="paciente_id" name="paciente_id" required>
                        <option value="">Selecciona un paciente</option>
                        <?php foreach ($pacientes as $paciente): ?>
                            <option value="<?php echo htmlspecialchars($paciente['id']); ?>">
                                <?php echo htmlspecialchars($paciente['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="detalle">Detalles del Seguimiento:</label>
                    <textarea id="detalle" name="detalle" required></textarea>
                </div>
                <button type="submit" class="btn-guardar">Guardar Seguimiento</button>
                <a href="ver_paciente.php" class="btn-cancelar">Cancelar</a>
            </form>
        </section>
        <div class="copyright-text">
            <p>&copy; 2024 Clínica Dental del Dr. Fabián Mora. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>