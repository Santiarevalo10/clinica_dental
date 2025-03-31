<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de doctor
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'doctor') {
    header('Location: ../login.php');
    exit;
}

// Conectar a la base de datos
require '../Conexion/conexion.php';

// Obtener productos disponibles
$productos = [];
try {
    $stmt = $pdo->query('SELECT id, nombre, costo FROM productos');
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Error al obtener productos: ' . $e->getMessage());
}

// Obtener citas disponibles
$citas = [];
$cita_actual = [];
try {
    $usuario_id = $_SESSION['usuario_id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cita_id'])) {
        $cita_id = $_POST['cita_id'];
        $stmt = $pdo->prepare('
            SELECT citas.id, paciente.nombre AS paciente_nombre, doctor.nombre AS doctor_nombre, 
            citas.fecha, citas.hora, citas.estado, citas.motivo, citas.costo, sede.nombre AS sede_nombre,
            especialidad.nombre AS especialidad
            FROM citas
            JOIN pacientes AS paciente ON citas.paciente_id = paciente.id
            JOIN doctores AS doctor ON citas.doctor_id = doctor.id
            JOIN sedes AS sede ON citas.sede_id = sede.id
            JOIN especialidades AS especialidad ON citas.especialidad_id = especialidad.id
            WHERE citas.id = :cita_id
        ');
        $stmt->bindParam(':cita_id', $cita_id, PDO::PARAM_INT);
        $stmt->execute();
        $cita_actual = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    $stmt = $pdo->prepare('
        SELECT citas.id, paciente.nombre AS paciente_nombre, doctor.nombre AS doctor_nombre, 
        citas.fecha, citas.hora, citas.estado, citas.motivo, citas.costo, sede.nombre AS sede_nombre,
        especialidad.nombre AS especialidad
        FROM citas
        JOIN pacientes AS paciente ON citas.paciente_id = paciente.id
        JOIN doctores AS doctor ON citas.doctor_id = doctor.id
        JOIN sedes AS sede ON citas.sede_id = sede.id
        JOIN especialidades AS especialidad ON citas.especialidad_id = especialidad.id
        WHERE doctor.usuario_id = :usuario_id
        ORDER BY citas.fecha, citas.hora
    ');
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Error al obtener citas: ' . $e->getMessage());
}

// Procesar el formulario cuando se envía una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cita_id'])) {
    $cita_id = $_POST['cita_id'];
    $motivo = $_POST['motivo'];
    $productos_usados = $_POST['productos'];
    $costo_servicio = $_POST['costo_servicio'];

    // Calcular el costo total
    $costo_total = $costo_servicio;
    foreach ($productos_usados as $producto_id => $cantidad) {
        if ($cantidad > 0) {
            $stmt = $pdo->prepare('SELECT costo FROM productos WHERE id = :producto_id');
            $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
            $stmt->execute();
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);
            $costo_total += $producto['costo'] * $cantidad;
        }
    }

    try {
        // Actualizar la cita con los detalles y el costo total
        $stmt = $pdo->prepare('
            UPDATE citas
            SET motivo = :motivo, costo = :costo_total, estado = "completada"
            WHERE id = :cita_id
        ');
        $stmt->execute([
            ':cita_id' => $cita_id,
            ':motivo' => $motivo,
            ':costo_total' => $costo_total
        ]);

        // Redirigir a la página de ver citas con un mensaje de éxito
        header('Location: ver_citas.php?mensaje=Cita actualizada exitosamente');
        exit;
    } catch (PDOException $e) {
        $error = 'Error al actualizar la cita: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Cita</title>
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

        .btn-guardar-cita,
        .btn-calcular-costo {
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

        .btn-guardar-cita:hover,
        .btn-calcular-costo:hover {
            background-color: #0d124a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-cancelar {
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

        .footer {
            color: black;
            text-align: center;
            padding: 1rem;
            margin-top: 2rem;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('calcular_costo').addEventListener('click', function() {
                let costo_servicio = parseFloat(document.getElementById('costo_servicio').value) || 0;
                let costo_total = costo_servicio;

                document.querySelectorAll('#productos input').forEach(function(input) {
                    let cantidad = parseFloat(input.value) || 0;
                    let costo_producto = parseFloat(input.closest('div').querySelector('label').textContent.split('$')[1]) || 0;
                    costo_total += cantidad * costo_producto;
                });

                document.getElementById('costo_total').value = costo_total.toFixed(2);
            });
        });
    </script>
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
            <h2>Actualizar Citas</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="cita_id">Seleccionar Cita:</label>
                    <select id="cita_id" name="cita_id" required>
                        <option value="">Selecciona una cita</option>
                        <?php foreach ($citas as $cita): ?>
                            <option value="<?php echo htmlspecialchars($cita['id']); ?>" <?php echo isset($_POST['cita_id']) && $_POST['cita_id'] == $cita['id'] ? 'selected' : ''; ?>>
                                Paciente: <?php echo htmlspecialchars($cita['paciente_nombre']); ?> 
                                - Fecha: <?php echo htmlspecialchars($cita['fecha']); ?> 
                                - Hora: <?php echo htmlspecialchars($cita['hora']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="motivo">Motivo del Tratamiento:</label>
                    <textarea id="motivo" name="motivo" required><?php echo isset($cita_actual['motivo']) ? htmlspecialchars($cita_actual['motivo']) : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="productos">Productos Utilizados:</label>
                    <div id="productos">
                        <?php foreach ($productos as $producto): ?>
                            <div>
                                <label for="producto-<?php echo htmlspecialchars($producto['id']); ?>">
                                    <?php echo htmlspecialchars($producto['nombre']); ?> ($<?php echo htmlspecialchars($producto['costo']); ?>)
                                </label>
                                <input type="number" id="producto-<?php echo htmlspecialchars($producto['id']); ?>" name="productos[<?php echo htmlspecialchars($producto['id']); ?>]" min="0" value="0">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="costo_servicio">Costo del Servicio del Doctor:</label>
                    <input type="number" id="costo_servicio" name="costo_servicio" min="0" step="0.01" required>
                </div>
                <button type="button" id="calcular_costo" class="btn-calcular-costo">Calcular Costo</button>
                <div class="form-group">
                    <label for="costo_total">Costo Total:</label>
                    <input type="number" id="costo_total" name="costo_total" min="0" step="0.01" readonly>
                </div>
                <button type="submit" class="btn-guardar-cita">Guardar Cita</button>
                <a href="ver_citas.php" class="btn-cancelar">Cancelar</a>
            </form>
        </section>
    </div>
    
</body>
</html>