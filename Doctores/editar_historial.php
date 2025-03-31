<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de doctor
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'doctor') {
    header('Location: ../login.php');
    exit;
}

// Conectar a la base de datos
require '../Conexion/conexion.php';

// Obtener el ID del historial de la URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die('ID de historial no válido.');
}

try {
    // Obtener el historial clínico
    $stmt = $pdo->prepare('
        SELECT * FROM historial_clinico WHERE id = :id
    ');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $historial = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$historial) {
        die('El historial clínico no existe.');
    }

    // Obtener el usuario_id del doctor asociado al historial
    $doctor_id = $historial['doctor_id'];
    $stmt = $pdo->prepare('
        SELECT usuario_id FROM doctores WHERE id = :doctor_id
    ');
    $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
    $stmt->execute();
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$doctor || $doctor['usuario_id'] !== $_SESSION['usuario_id']) {
        die('El historial clínico no le pertenece a este doctor.');
    }

    // Procesar el formulario cuando se envía una solicitud POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $descripcion = $_POST['descripcion'];
        $tratamiento = $_POST['tratamiento'];
        $paciente_id = $_POST['paciente_id'];

        try {
            $stmt = $pdo->prepare('
                UPDATE historial_clinico
                SET paciente_id = :paciente_id, descripcion = :descripcion, tratamiento = :tratamiento
                WHERE id = :id
            ');
            $stmt->execute([
                ':paciente_id' => $paciente_id,
                ':descripcion' => $descripcion,
                ':tratamiento' => $tratamiento,
                ':id' => $id
            ]);

            // Redirigir a la página de historial clínico con un mensaje de éxito
            header('Location: ver_historial.php?mensaje=Historial actualizado exitosamente');
            exit;
        } catch (PDOException $e) {
            $error = 'Error al actualizar el historial: ' . $e->getMessage();
        }
    }

    // Obtener la lista de pacientes
    $stmt = $pdo->prepare('
        SELECT pacientes.id, usuarios.nombre
        FROM pacientes
        JOIN usuarios ON pacientes.usuario_id = usuarios.id
    ');
    $stmt->execute();
    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('Error al obtener el historial clínico: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Historial Clínico</title>
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

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-actualizar,
        .btn-cancelar2 {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            min-width: 120px;
            text-align: center;
        }

        .btn-actualizar {
            background-color: #1a237e; /* Azul oscuro */
            color: white;
        }

        .btn-actualizar:hover {
            background-color: #0d124a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-cancelar2 {
            background-color: #800000; /* Vinotinto */
            color: white;
        }

        .btn-cancelar2:hover {
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

        .footer {
            color: black;
            text-align: center;
            padding: 1rem;
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
            <h2>Editar Historial Clínico</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="post" action="editar_historial.php?id=<?php echo htmlspecialchars($id); ?>">
                <div class="form-group">
                    <label for="paciente_id">Paciente:</label>
                    <select id="paciente_id" name="paciente_id" required>
                        <?php foreach ($pacientes as $paciente): ?>
                            <option value="<?php echo htmlspecialchars($paciente['id']); ?>" <?php echo $paciente['id'] == $historial['paciente_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($paciente['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($historial['descripcion']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="tratamiento">Tratamiento:</label>
                    <textarea id="tratamiento" name="tratamiento" required><?php echo htmlspecialchars($historial['tratamiento']); ?></textarea>
                </div>
                <button type="submit" class="btn-actualizar">Guardar Cambios</button>
                <a href="ver_historial.php" class="btn-cancelar2">Cancelar</a>
            </form>
        </section>
    </div>
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Clínica Dental del Dr. Fabián Mora. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>