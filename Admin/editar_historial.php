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
    die('ID de historial clínico no proporcionado.');
}

// Procesar la actualización del historial clínico si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $tratamiento = $_POST['tratamiento'] ?? '';
    $doctor_id = $_POST['doctor_id'] ?? null;
    $paciente_id = $_POST['paciente_id'] ?? null;

    if ($fecha && $descripcion && $tratamiento && $doctor_id && $paciente_id) {
        try {
            $stmt = $pdo->prepare('UPDATE historial_clinico 
                                    SET fecha = :fecha, descripcion = :descripcion, tratamiento = :tratamiento, doctor_id = :doctor_id, paciente_id = :paciente_id 
                                    WHERE id = :id');
            $stmt->execute([
                ':fecha' => $fecha,
                ':descripcion' => $descripcion,
                ':tratamiento' => $tratamiento,
                ':doctor_id' => $doctor_id,
                ':paciente_id' => $paciente_id,
                ':id' => $id
            ]);
            header('Location: ver_historial.php');
            exit;
        } catch (PDOException $e) {
            die('Error al actualizar el historial clínico: ' . $e->getMessage());
        }
    } else {
        echo 'Por favor, completa todos los campos.';
    }
}

// Obtener los detalles del historial clínico junto con el nombre del paciente
try {
    $stmt = $pdo->prepare('SELECT hc.*, p.nombre AS nombre_paciente 
                                    FROM historial_clinico hc 
                                    JOIN pacientes p ON hc.paciente_id = p.id 
                                    WHERE hc.id = :id');
    $stmt->execute([':id' => $id]);
    $historial = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$historial) {
        die('Historial clínico no encontrado.');
    }

    // Obtener lista de doctores
    $stmtDoctores = $pdo->query('SELECT id, nombre FROM doctores');
    $doctores = $stmtDoctores->fetchAll(PDO::FETCH_ASSOC);

    // Obtener lista de pacientes
    $stmtPacientes = $pdo->query('SELECT id, nombre FROM pacientes');
    $pacientes = $stmtPacientes->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('Error al obtener datos: ' . $e->getMessage());
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

        /* Estilos del Sidebar */
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

        /* Estilos del contenido principal */
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
        .form-group textarea,
        .form-group select {
            width: calc(100% - 12px);
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .btn-actualizar,
        .btn-cancelar {
            padding: 0.75rem 1.25rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 120px;
        }

        .btn-actualizar {
            background-color: #1a237e; /* Azul oscuro */
        }

        .btn-actualizar:hover {
            background-color: #0d124a;
            transform: translateY(-2px);
        }

        .btn-cancelar {
            background-color: #800000; /* Rojo oscuro (vinotinto) */
        }

        .btn-cancelar:hover {
            background-color: #590000;
            transform: translateY(-2px);
        }

        .container-principal h2 {
            font-family: 'Arial Black', sans-serif;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 2rem;
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
        <section class="container-principal">
            <h2>Editar Historial Clínico</h2>
            <form method="post" action="editar_historial.php?id=<?php echo htmlspecialchars($id); ?>">
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" id="fecha" name="fecha" value="<?php echo htmlspecialchars($historial['fecha']); ?>"
                           required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion"
                              required><?php echo htmlspecialchars($historial['descripcion']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="tratamiento">Tratamiento:</label>
                    <textarea id="tratamiento" name="tratamiento"
                              required><?php echo htmlspecialchars($historial['tratamiento']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="doctor_id">Doctor:</label>
                    <select id="doctor_id" name="doctor_id" required>
                        <?php foreach ($doctores as $doctor): ?>
                            <option value="<?php echo htmlspecialchars($doctor['id']); ?>" <?php echo $doctor['id'] == $historial['doctor_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($doctor['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="paciente_id">Paciente:</label>
                    <select id="paciente_id" name="paciente_id" required>
                        <?php foreach ($pacientes as $paciente): ?>
                            <option value="<?php echo htmlspecialchars($paciente['id']); ?>" <?php echo $paciente['id'] == $historial['paciente_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($paciente['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="action-buttons">
                    <button type="submit" class="btn-actualizar">Guardar Cambios</button>
                    <a href="ver_historial.php?id=<?php echo htmlspecialchars($historial['id']); ?>" class="btn-cancelar">Cancelar</a>
                </div>
                <p class="footer">&copy; 2024 Clínica Dental del Dr. Fabián Mora. Todos los derechos reservados.</p>

            </form>
        </section>
    </div>
</body>

</html>