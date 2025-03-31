<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de doctor
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'doctor') {
    header('Location: ../login.php');
    exit;
}

// Conectar a la base de datos (ajusta la ruta de conexión si es necesario)
require '../Conexion/conexion.php';

// Procesar la acción si se envía una solicitud POST para eliminar un historial
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        try {
            $stmt = $pdo->prepare('DELETE FROM historial_clinico WHERE id = :id');
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error al procesar la acción: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'ID no válido']);
    }
    exit;
}

try {
    $usuario_id = $_SESSION['usuario_id'];

    // Consulta para obtener los historiales clínicos relacionados con el doctor que ha iniciado sesión
    $stmt = $pdo->prepare('
        SELECT historial_clinico.id, historial_clinico.fecha, historial_clinico.descripcion, historial_clinico.tratamiento, 
               doctores.nombre AS doctor, usuarios.nombre AS paciente
        FROM historial_clinico
        JOIN doctores ON historial_clinico.doctor_id = doctores.id
        JOIN pacientes ON historial_clinico.paciente_id = pacientes.id
        JOIN usuarios ON pacientes.usuario_id = usuarios.id
        WHERE doctores.usuario_id = :usuario_id
        ORDER BY historial_clinico.fecha DESC
    ');
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    .btn-editar,
    .btn-eliminar {
        display: inline-block;
        box-sizing: border-box; /* Incluye padding y border en el tamaño */
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
        min-width: 120px; /* Ancho mínimo para mantener el tamaño */
        height: auto; /* Altura automática */
    }

    .btn-editar {
        background-color: #1a237e; /* Azul oscuro */
        color: white;
    }

    .btn-editar:hover {
        background-color: #0d124a;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-eliminar {
        background-color: #800000; /* Vinotinto */
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
            <h2>Historiales Clínicos</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Tratamiento</th>
                        <th>Doctor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($historiales) > 0): ?>
                        <?php foreach ($historiales as $historial): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($historial['paciente']); ?></td>
                                <td><?php echo htmlspecialchars($historial['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($historial['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($historial['tratamiento']); ?></td>
                                <td><?php echo htmlspecialchars($historial['doctor']); ?></td>
                                <td>
                                    <a href="editar_historial.php?id=<?php echo htmlspecialchars($historial['id']); ?>" class="btn-editar">Editar</a>
                                    <button class="btn-eliminar" data-id="<?php echo htmlspecialchars($historial['id']); ?>" data-accion="eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este historial?');">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No hay historiales clínicos disponibles.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
    <script>
        $(document).ready(function() {
            $('.btn-eliminar').click(function() {
                var idHistorial = $(this).data('id');
                var accion = $(this).data('accion');

                $.ajax({
                    type: 'POST',
                    url: 'ver_historial.php',
                    data: {
                        accion: accion,
                        id: idHistorial
                    },
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + result.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error en la solicitud: ' + error);
                    }
                });
            });
        });
    </script>
</body>

</html>