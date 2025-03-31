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
    // Obtener el ID del usuario que inició sesión
    $usuario_id = $_SESSION['usuario_id'];

    // Consulta para obtener las citas del paciente que ha iniciado sesión junto con el estado de la solicitud
    $stmt = $pdo->prepare('
        SELECT citas.id, 
            paciente.nombre AS paciente_nombre, 
            doctor.nombre AS doctor_nombre, 
            citas.fecha, 
            citas.hora, 
            citas.estado AS estado_cita, 
            citas.motivo, 
            citas.costo, 
            citas.estado,
            sede.nombre AS sede_nombre,
            especialidad.nombre AS especialidad,
            solicitud.estado AS estado_solicitud
        FROM citas
        JOIN pacientes AS paciente ON citas.paciente_id = paciente.id
        JOIN doctores AS doctor ON citas.doctor_id = doctor.id
        JOIN sedes AS sede ON citas.sede_id = sede.id
        JOIN especialidades AS especialidad ON citas.especialidad_id = especialidad.id
        LEFT JOIN solicitudes AS solicitud ON citas.id = solicitud.cita_id
        WHERE paciente.usuario_id = :usuario_id
        ORDER BY citas.fecha, citas.hora
    ');
    // Enlazar el ID del usuario actual
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

    // Ejecutar la consulta
    $stmt->execute();
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener el estado de las solicitudes para las citas del paciente
    $cita_ids = array_column($citas, 'id');
    if (count($cita_ids) > 0) {
        $placeholders = implode(',', array_fill(0, count($cita_ids), '?'));
        $stmt = $pdo->prepare('
            SELECT cita_id, tipo, estado 
            FROM solicitudes 
            WHERE cita_id IN (' . $placeholders . ')
        ');
        $stmt->execute($cita_ids);
        $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Indexar solicitudes por cita_id
        $solicitud_estado = [];
        foreach ($solicitudes as $solicitud) {
            $solicitud_estado[$solicitud['cita_id']] = $solicitud;
        }
    } else {
        $solicitud_estado = [];
    }
} catch (PDOException $e) {
    die('Error en la consulta: ' . $e->getMessage());
}

// Verificar si el usuario es administrador
$is_admin = $_SESSION['rol'] === 'administrador';

// Mensaje de estado de la solicitud, si existe
$mensaje_estado = $_GET['mensaje_estado'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Citas</title>
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
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table th,
        .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: 600;
        }

        .table tbody tr:hover {
            background-color: #f9f9f9;
        }

        .btn-Agendar,
        .btn-eliminar {
            display: inline-block;
            box-sizing: border-box;
            padding: 0.75rem 1.5rem;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            white-space: nowrap;
            overflow: hidden;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            min-width: 120px;
            height: auto;
            margin: 0.25rem;
            vertical-align: top; /* Alinea los botones en la misma línea vertical */
            margin-bottom: 0.25rem; /* Ajusta el margen inferior */
        }

        .btn-Agendar {
            background-color: #1a237e;
            color: white;
        }

        .btn-Agendar:hover {
            background-color: #0d124a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-eliminar {
            background-color: #800000;
            color: white;
        }

        .btn-eliminar:hover {
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
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .copyright-center-text {
            text-align: center;
            margin-top: 2rem;
        }
    </style>

    <script>
        function solicitarEliminacion(citaId) {
            if (confirm('¿Estás seguro de que quieres eliminar esta cita?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'eliminar_cita_ajax.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert('Solicitud de eliminación enviada exitosamente.');
                            location.reload(); 
                        } else {
                            alert('Error al solicitar la eliminación: ' + response.error);
                        }
                    } else {
                        alert('Error en la solicitud.');
                    }
                };
                xhr.onerror = function() {
                    alert('Error en la solicitud AJAX.');
                };
                xhr.send('cita_id=' + encodeURIComponent(citaId));
            }
        }
    </script>
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
            <h2>Mis Citas</h2>
            <?php if ($mensaje_estado): ?>
                <p class="alerta"><?php echo htmlspecialchars($mensaje_estado); ?></p>
            <?php endif; ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Especialidad</th>
                        <th>Doctor</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado de la Cita</th>
                        <th>Estado de Solicitud</th>
                        <th>Sede</th>
                        <th>Costo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($citas) > 0): ?>
                        <?php foreach ($citas as $cita): ?>
                            <tr id="cita-<?php echo htmlspecialchars($cita['id']); ?>">
                                <td><?php echo htmlspecialchars($cita['especialidad']); ?></td>
                                <td><?php echo htmlspecialchars($cita['doctor_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($cita['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($cita['hora']); ?></td>
                                <td><?php echo htmlspecialchars($cita['estado']); ?></td>
                                <td><?php echo htmlspecialchars($cita['estado_solicitud']); ?></td>
                                <td><?php echo htmlspecialchars($cita['sede_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($cita['costo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <?php
                                    if (isset($solicitud_estado[$cita['id']])) {
                                        $solicitud = $solicitud_estado[$cita['id']];
                                        if ($solicitud['estado'] === 'aprobada') {
                                            if ($solicitud['tipo'] === 'aplazamiento') {
                                                echo '<br>Tu solicitud de aplazamiento ha sido aprobada.';
                                            } elseif ($solicitud['tipo'] === 'eliminacion') {
                                                echo '<br>Tu solicitud de eliminación ha sido aprobada. Esta cita ya no está agendada.';
                                            }
                                        }
                                    }
                                    ?>
                                    <div style="display: flex; justify-content: flex-start;">
                                        <a href="reagendar_citas.php?cita_id=<?php echo htmlspecialchars($cita['id']); ?>" class="btn-Agendar">Reagendar</a>
                                        <button onclick="solicitarEliminacion(<?php echo htmlspecialchars($cita['id']); ?>)" class="btn-eliminar">Solicitar Eliminación</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">No tienes citas agendadas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
   
    
</body>

</html>