<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de paciente
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'paciente') {
    header('Location: ../login.php');
    exit;
}

// Conectar a la base de datos (ajusta la ruta de conexión si es necesario)
require '../Conexion/conexion.php';

try {
    // Obtener el historial clínico del paciente
    $stmt = $pdo->prepare('
        SELECT historial_clinico.id, historial_clinico.fecha, historial_clinico.descripcion, historial_clinico.tratamiento, doctores.nombre AS doctor
        FROM historial_clinico
        JOIN doctores ON historial_clinico.doctor_id = doctores.id
        WHERE historial_clinico.paciente_id = :paciente_id
        ORDER BY historial_clinico.fecha DESC
    ');
    $stmt->execute(['paciente_id' => $_SESSION['usuario_id']]);
    $historiales = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error en la consulta: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial Clínico</title>
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
            <li><a href="principal_pacientes.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="ver_citas.php"><i class="fas fa-calendar-alt"></i> Ver Citas</a></li>
            <li><a href="ver_historial.php"><i class="fas fa-history"></i> Historial Clínico</a></li>
            <li><a href="perfil.php"><i class="fas fa-user"></i> Mi Perfil</a></li>
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
        </ul>
    </div>
    <div class="content">
        <section class="container-principal">
            <h2>Mi Historial Clínico</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Tratamiento</th>
                        <th>Doctor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($historiales) > 0): ?>
                        <?php foreach ($historiales as $historial): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($historial['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($historial['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($historial['tratamiento']); ?></td>
                                <td><?php echo htmlspecialchars($historial['doctor']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No tienes historial clínico disponible.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
    <footer class="footer">
   
</body>
</html>