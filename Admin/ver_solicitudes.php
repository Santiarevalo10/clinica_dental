<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

require '../Conexion/conexion.php';

try {
    $stmt = $pdo->prepare('
        SELECT solicitudes.id, citas.id AS cita_id, especialidades.nombre AS especialidad, doctores.nombre AS doctor, citas.fecha, citas.hora, citas.estado, sedes.nombre AS sede, solicitudes.tipo, solicitudes.estado AS solicitud_estado
        FROM solicitudes
        JOIN citas ON solicitudes.cita_id = citas.id
        JOIN especialidades ON citas.especialidad_id = especialidades.id
        JOIN doctores ON citas.doctor_id = doctores.id
        JOIN sedes ON citas.sede_id = sedes.id
        WHERE solicitudes.estado = "pendiente"
        ORDER BY solicitudes.fecha_solicitud DESC
    ');
    $stmt->execute();
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error en la consulta: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-repeat: no-repeat;
            background-position: center;
            background-size: 50%;
            background-attachment: fixed;
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
            flex-grow: 1;
        }

        .container-principal {
            width: 100%;
            padding: 2rem;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
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

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn-aprobar,
        .btn-eliminar {
            padding: 0.7rem 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-decoration: none;
            color: white;
            min-width: 80px;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-aprobar {
            background-color: #1a237e; /* Azul oscuro */
        }

        .btn-eliminar {
            background-color: #800000; /* Vinotinto */
        }

        .btn-aprobar:hover {
            background-color: #0d124a;
            transform: translateY(-2px);
        }

        .btn-eliminar:hover {
            background-color: #590000;
            transform: translateY(-2px);
        }

        .footer {
            background-color: rgba(52, 15, 70, 0.97);
            color: white;
            text-align: center;
            padding: 1rem;
        }

        .container-principal h2 {
            font-family: 'Arial Black', sans-serif; /* Fuente llamativa */
            text-align: center;
            text-transform: uppercase; /* Asegura que esté en mayúsculas */
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
            <h2>Solicitudes de Citas</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Especialidad</th>
                        <th>Doctor</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Sede</th>
                        <th>Tipo de Solicitud</th>
                        <th>Estado de Solicitud</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($solicitudes) > 0): ?>
                        <?php foreach ($solicitudes as $solicitud): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($solicitud['especialidad']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['doctor']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['hora']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['sede']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['tipo']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['solicitud_estado']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button onclick="procesarSolicitud(<?php echo htmlspecialchars($solicitud['id']); ?>, 'aprobar')" class="btn-aprobar">Aprobar</button>
                                        <button onclick="procesarSolicitud(<?php echo htmlspecialchars($solicitud['id']); ?>, 'rechazar')" class="btn-eliminar">Rechazar</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No hay solicitudes pendientes.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
  
    <script>
        function procesarSolicitud(solicitudId, accion) {
            if (confirm(`¿Estás seguro de que quieres ${accion === 'aprobar' ? 'aprobar' : 'rechazar'} esta solicitud?`)) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'procesar_solicitud_ajax.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert(`Solicitud ${accion === 'aprobar' ? 'aprobada' : 'rechazada'} exitosamente.`);
                            location.reload();
                        } else {
                            alert('Error al procesar la solicitud: ' + response.error);
                        }
                    } else {
                        alert('Error en la solicitud.');
                    }
                };
                xhr.onerror = function() {
                    alert('Error en la solicitud AJAX.');
                };
                xhr.send('solicitud_id=' + encodeURIComponent(solicitudId) + '&accion=' + encodeURIComponent(accion));
            }
        }
    </script>
</body>
</html>