<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

require '../Conexion/conexion.php';

$citas = [];
$error = '';

try {
    $stmt = $pdo->prepare('
        SELECT citas.id, paciente.nombre AS paciente_nombre, doctor.nombre AS doctor_nombre, 
        citas.fecha, citas.hora, citas.estado, citas.motivo AS motivo, citas.costo, sede.direccion AS sede_direccion
        FROM citas
        JOIN pacientes AS paciente ON citas.paciente_id = paciente.id
        JOIN doctores AS doctor ON citas.doctor_id = doctor.id
        JOIN sedes AS sede ON citas.sede_id = sede.id
        ORDER BY citas.fecha, citas.hora
    ');
    $stmt->execute();
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Error en la consulta: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];
    $id_cita = $_POST['id_cita'];

    if ($accion === 'eliminar') {
        try {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM citas WHERE id = :id');
            $stmt->execute([':id' => $id_cita]);
            $count = $stmt->fetchColumn();

            if ($count == 0) {
                echo json_encode(['success' => false, 'error' => 'La cita no existe.']);
                exit;
            }

            $stmt = $pdo->prepare('DELETE FROM citas WHERE id = :id');
            $stmt->execute([':id' => $id_cita]);
            echo json_encode(['success' => true, 'message' => 'Cita eliminada con éxito.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error al procesar la acción: ' . $e->getMessage()]);
        }
    } elseif ($accion === 'reagendar') {
        $nueva_fecha = $_POST['nueva_fecha'];
        $nueva_hora = $_POST['nueva_hora'];

        try {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM citas WHERE id = :id');
            $stmt->execute([':id' => $id_cita]);
            $count = $stmt->fetchColumn();

            if ($count == 0) {
                echo json_encode(['success' => false, 'error' => 'La cita no existe.']);
                exit;
            }

            $stmt = $pdo->prepare('UPDATE citas SET fecha = :nueva_fecha, hora = :nueva_hora WHERE id = :id');
            $stmt->execute([':nueva_fecha' => $nueva_fecha, ':nueva_hora' => $nueva_hora, ':id' => $id_cita]);

            $stmt = $pdo->prepare('INSERT INTO solicitudes (cita_id, tipo, fecha_solicitud) VALUES (:cita_id, :tipo, NOW())');
            $stmt->execute([':cita_id' => $id_cita, ':tipo' => 'aplazamiento']);

            echo json_encode(['success' => true, 'message' => 'Cita reagendada con éxito.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error al procesar la acción: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Acción no reconocida.']);
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Dental - Ver Citas</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            display: flex;
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
            overflow-x: auto;
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
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: 600;
        }

        .table tr:hover {
            background-color: #f9f9f9;
        }

        .btn-eliminar,
        .btn-Reagendar,
        .btn-Agendar {
            padding: 0.7rem 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
            color: white;
            display: inline-flex; /* Botones en línea */
            align-items: center; /* Centrar verticalmente */
            justify-content: center; /* Centrar horizontalmente */
            text-decoration: none; /* Asegurar que no haya subrayado */
        }

        .btn-eliminar {
            background-color: #800000;
        }

        .btn-eliminar:hover {
            background-color: #590000;
            transform: translateY(-2px);
        }

        .btn-Reagendar,
        .btn-Agendar {
            background-color: #1a237e;
        }

        .btn-Reagendar:hover,
        .btn-Agendar:hover {
            background-color: #0d124a;
            transform: translateY(-2px);
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

        /* Espaciado entre botones de acción */
        .action-buttons {
            display: flex;
            gap: 0.5rem; /* Pequeño espacio entre los botones */
            justify-content: center; /* Centrar los botones horizontalmente */
        }

        /* Ajustes para el scroll horizontal */
        body {
            display: grid;
            grid-template-columns: 250px 1fr;
        }

        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .content {
            margin-left: 0;
            width: auto;
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
            <h2>Ver Citas</h2>
            <a href="agendar_citas.php?cita_id=<?php echo htmlspecialchars($cita['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="btn-Agendar">Crear Cita</a>
            <?php if ($error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Doctor</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Descripción</th>
                        <th>Sede</th>
                        <th>Dirección</th>
                        <th>Costo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($citas)): ?>
                        <tr>
                            <td colspan="10">No hay citas programadas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($citas as $cita): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cita['id']); ?></td>
                                <td><?php echo htmlspecialchars($cita['paciente_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($cita['doctor_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($cita['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($cita['hora']); ?></td>
                                <td><?php echo htmlspecialchars($cita['estado']); ?></td>
                                <td><?php echo htmlspecialchars($cita['motivo']); ?></td>
                                <td><?php echo htmlspecialchars($cita['sede_direccion']); ?></td>
                                <td><?php echo htmlspecialchars($cita['sede_direccion']); ?></td>
                                <td><?php echo htmlspecialchars($cita['costo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-eliminar" data-id="<?php echo htmlspecialchars($cita['id']); ?>" data-accion="eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar esta cita?');">Eliminar</button>
                                        <button class="btn-Reagendar" data-id="<?php echo htmlspecialchars($cita['id']); ?>" data-accion="reagendar" onclick="return confirm('¿Estás seguro de que deseas reagendar esta cita?');">Reagendar</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <p class="footer">&copy; 2024 Clínica Dental del Dr. Fabián Mora. Todos los derechos reservados.</p>
        </section>
    </div>

    <script>
        $(document).ready(function() {
            $('.btn-eliminar').click(function() {
                var idCita = $(this).data('id');
                var accion = 'eliminar';

                $.ajax({
                    type: 'POST',
                    url: 'ver_citas.php',
                    data: {
                        accion: accion,
                        id_cita: idCita
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert(response.error);
                        }
                    }
                });

                return false;
            });

            $('.btn-Reagendar').click(function() {
                var idCita = $(this).data('id');
                var nuevaFecha = prompt('Introduce la nueva fecha (YYYY-MM-DD):');
                var nuevaHora = prompt('Introduce la nueva hora (HH:MM):');

                if (nuevaFecha && nuevaHora) {
                    $.ajax({
                        type: 'POST',
                        url: 'ver_citas.php',
                        data: {
                            accion: 'reagendar',
                            id_cita: idCita,
                            nueva_fecha: nuevaFecha,
                            nueva_hora: nuevaHora
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                location.reload();
                            } else {
                                alert(response.error);
                            }
                        }
                    });
                }

                return false;
            });
        });
    </script>
</body>

</html>