<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de doctor
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'doctor') {
    header('Location: ../login.php');
    exit;
}

// Conectar a la base de datos
require '../Conexion/conexion.php';

$doctor_id = $_SESSION['usuario_id'];
$citas = [];
$errores = [];

try {
    // Obtener el ID del usuario que inició sesión
    $usuario_id = $_SESSION['usuario_id'];

    // Consulta para obtener las citas del paciente que ha iniciado sesión
    $stmt = $pdo->prepare('
        SELECT citas.id, paciente.nombre AS paciente_nombre, paciente.telefono, paciente.email
        FROM citas
        JOIN pacientes AS paciente ON citas.paciente_id = paciente.id
        JOIN doctores AS doctor ON citas.doctor_id = doctor.id
        WHERE doctor.usuario_id = :usuario_id
        ORDER BY paciente.id
    ');
    // Enlazar el ID del usuario actual
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

    // Ejecutar la consulta
    $stmt->execute();
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log('Error en la consulta: ' . $e->getMessage());
    $errores[] = 'Error al procesar la solicitud. Inténtelo de nuevo más tarde.';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pacientes</title>
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

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #ddd;
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

        .btn-editar {
            display: inline-flex; /* Usar inline-flex para centrar contenido */
            align-items: center; /* Centrar verticalmente */
            justify-content: center; /* Centrar horizontalmente */
            background-color: #1a237e; /* Azul oscuro */
            color: white;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            border: none;
            cursor: pointer;
            white-space: nowrap; /* Evita que el texto se rompa */
        }

        .btn-editar:hover {
            background-color: #0d124a;
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

        .table td:last-child {
            text-align: center; /* Centrar el botón en la celda */
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
            <h2>Mis Pacientes</h2>
            <?php if (!empty($errores)): ?>
                <div class="error">
                    <?php foreach ($errores as $error): ?>
                        <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($citas) > 0): ?>
                        <?php foreach ($citas as $cita):?>
                            <tr id="cita-<?php echo htmlspecialchars($cita['id']); ?>">
                                <td><?php echo htmlspecialchars($cita['paciente_nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($cita['telefono'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($cita['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <a href="crear_seguimiento.php?paciente_id=<?php echo htmlspecialchars($cita['id']); ?>" class="btn-editar">Agregar Seguimiento</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No tienes pacientes agendados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>