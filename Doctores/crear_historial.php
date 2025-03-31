<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'doctor') {
    header('Location: ../login.php');
    exit;
}

require '../Conexion/conexion.php';

$doctor_id = $_SESSION['usuario_id'];

$stmt = $pdo->prepare('SELECT id FROM doctores WHERE usuario_id = :doctor_id');
$stmt->execute([':doctor_id' => $doctor_id]);
$doctor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$doctor) {
    die('El doctor no existe en la base de datos.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paciente_id = $_POST['paciente_id'];
    $descripcion = $_POST['descripcion'];
    $tratamiento = $_POST['tratamiento'];

    try {
        $stmt = $pdo->prepare('
            INSERT INTO historial_clinico (paciente_id, doctor_id, descripcion, tratamiento, fecha)
            VALUES (:paciente_id, :doctor_id, :descripcion, :tratamiento, NOW())
        ');
        $stmt->execute([
            ':paciente_id' => $paciente_id,
            ':doctor_id' => $doctor['id'],
            ':descripcion' => $descripcion,
            ':tratamiento' => $tratamiento
        ]);

        header('Location: ver_historial.php?mensaje=Historial creado exitosamente');
        exit;
    } catch (PDOException $e) {
        $error = 'Error al crear el historial: ' . $e->getMessage();
    }
}
try {
    $stmt = $pdo->prepare('
        SELECT pacientes.id, usuarios.nombre 
        FROM pacientes
        JOIN usuarios ON pacientes.usuario_id = usuarios.id
    ');
    $stmt->execute();
    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error al obtener los pacientes: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Historial Clínico</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            flex: 1;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-guardar,
        .btn-cancelar {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
        }

        .btn-guardar {
            background-color: #1a237e; /* Azul oscuro */
            color: white;
        }

        .btn-guardar:hover {
            background-color: #0d124a;
        }

        .btn-cancelar {
            background-color: #800000; /* Vinotinto */
            color: white;
        }

        .btn-cancelar:hover {
            background-color: #590000;
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
        <h2>Crear Historial Clínico</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="crear_historial.php">
            <div class="form-group">
                <label for="paciente_id">Paciente:</label>
                <select id="paciente_id" name="paciente_id" required>
                    <?php foreach ($pacientes as $paciente): ?>
                        <option value="<?php echo htmlspecialchars($paciente['id']); ?>">
                            <?php echo htmlspecialchars($paciente['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>
            </div>
            <div class="form-group">
                <label for="tratamiento">Tratamiento:</label>
                <textarea id="tratamiento" name="tratamiento" required></textarea>
            </div>
            <button type="submit" class="btn-guardar">Guardar Historial</button>
            <a href="ver_historial.php" class="btn-cancelar">Cancelar</a>
        </form>
    </div>
</body>
</html>