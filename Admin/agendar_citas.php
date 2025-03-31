<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene el rol de administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

// Incluir el archivo de conexión a la base de datos
require '../Conexion/conexion.php';

$mensaje = '';
$errores = [];

// Sede predeterminada (puedes cambiar esto según tus necesidades)
$sede_predeterminada = 1;

// Horarios disponibles para las citas
$horarios_disponibles = [
    '08:00:00' => '08:00 - 09:00',
    '09:00:00' => '09:00 - 10:00',
    '10:00:00' => '10:00 - 11:00',
    '11:00:00' => '11:00 - 12:00',
    '14:00:00' => '14:00 - 15:00',
    '15:00:00' => '15:00 - 16:00',
    '16:00:00' => '16:00 - 17:00',
    '17:00:00' => '17:00 - 18:00'
];

try {
    // Obtener especialidades
    $stmtEspecialidades = $pdo->prepare('SELECT id, nombre FROM especialidades');
    $stmtEspecialidades->execute();
    $especialidades = $stmtEspecialidades->fetchAll(PDO::FETCH_ASSOC);

    // Obtener doctores y sedes
    $stmtDoctores = $pdo->prepare('
        SELECT d.id AS doctor_id, d.nombre, s.id AS sede_id, s.nombre AS sede_nombre, s.direccion
        FROM doctores d
        JOIN sedes s ON d.sede_id = s.id
    ');
    $stmtDoctores->execute();
    $doctores = $stmtDoctores->fetchAll(PDO::FETCH_ASSOC);

    // Obtener pacientes
    $stmtPacientes = $pdo->prepare('SELECT id, nombre, email, telefono FROM pacientes');
    $stmtPacientes->execute();
    $pacientes = $stmtPacientes->fetchAll(PDO::FETCH_ASSOC);

    // Procesar formulario si se ha enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario_id = $_POST['usuario_id'] ?? '';
        $especialidad_id = $_POST['especialidad_id'] ?? '';
        $doctor_id = $_POST['doctor_id'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        $motivo = $_POST['motivo'] ?? '';
        $estado = 'agendada';
        $sede_id = $_POST['sede_id'] ?? $sede_predeterminada;

        // Validar entradas
        if (empty($usuario_id) || empty($especialidad_id) || empty($doctor_id) || empty($fecha) || empty($hora)) {
            $errores[] = 'Todos los campos son obligatorios.';
        }

        // Validar formato de la fecha
        if (!DateTime::createFromFormat('Y-m-d', $fecha)) {
            $errores[] = 'La fecha no tiene un formato válido (YYYY-MM-DD).';
        }

        // Validar formato de la hora
        if (!array_key_exists($hora, $horarios_disponibles)) {
            $errores[] = 'La hora seleccionada no es válida.';
        }

        // Verificar si el usuario_id existe en la tabla pacientes
        if (empty($errores)) {
            $stmtUsuario = $pdo->prepare('SELECT COUNT(*) FROM pacientes WHERE id = :usuario_id');
            $stmtUsuario->execute(['usuario_id' => $usuario_id]);
            $usuarioExiste = $stmtUsuario->fetchColumn();

            if ($usuarioExiste == 0) {
                $errores[] = 'El paciente no está registrado en la base de datos.';
            } else {
                // Verificar si el doctor existe en la tabla doctores
                $stmtDoctor = $pdo->prepare('SELECT COUNT(*) FROM doctores WHERE id = :doctor_id');
                $stmtDoctor->execute(['doctor_id' => $doctor_id]);
                $doctorExiste = $stmtDoctor->fetchColumn();

                if ($doctorExiste == 0) {
                    $errores[] = 'El doctor no está registrado en la base de datos.';
                } else {
                    // Verificar si el doctor ya tiene una cita en el horario seleccionado
                    $stmtCita = $pdo->prepare('SELECT COUNT(*) FROM citas WHERE doctor_id = :doctor_id AND fecha = :fecha AND hora = :hora');
                    $stmtCita->execute([
                        'doctor_id' => $doctor_id,
                        'fecha' => $fecha,
                        'hora' => $hora
                    ]);
                    $citaExistente = $stmtCita->fetchColumn();

                    if ($citaExistente > 0) {
                        $errores[] = 'El doctor ya tiene una cita programada en este horario.';
                    } else {
                        // Insertar la cita si no hay errores
                        $stmtInsertarCita = $pdo->prepare('INSERT INTO citas (usuario_id, doctor_id, fecha, hora, sede_id, especialidad_id, motivo, estado, paciente_id) VALUES (:usuario_id, :doctor_id, :fecha, :hora, :sede_id, :especialidad_id, :motivo, :estado, :paciente_id)');
                        $stmtInsertarCita->execute([
                            'usuario_id' => $usuario_id,
                            'doctor_id' => $doctor_id,
                            'fecha' => $fecha,
                            'hora' => $hora,
                            'sede_id' => $sede_id,
                            'especialidad_id' => $especialidad_id,
                            'motivo' => $motivo,
                            'estado' => $estado,
                            'paciente_id' => $usuario_id
                        ]);
                        $mensaje = 'Cita agendada con éxito.';
                    }
                }
            }
        }
    }
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
    <title>Clínica Dental - Agendar Cita</title>
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
        }

        .sidebar li a:hover {
            background-color: rgb(81, 168, 226);
        }

        .content {
            margin-left: 250px;
            padding: 2rem;
            width: calc(100% - 250px);
            box-sizing: border-box; /* Importante para el correcto ancho */
        }

        .container-principal {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container-principal h2 {
            font-family: 'Arial Black', sans-serif;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 2rem;
        }

        form {
            width: 100%;
            margin: 0 auto; /* Centrar el formulario */
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .form-group select,
        .form-group input,
        .form-group textarea,
        .form-group input[type="date"] {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-agendar-cita {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #1a237e; /* Azul oscuro */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-agendar-cita:hover {
            background-color: #0d124a;
            transform: translateY(-2px);
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .exito {
            background-color: #d4edda;
            color: #155724;
            padding: 1rem;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .footer {
            color: black;
            text-align: center;
            padding: 1rem;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const especialidadSelect = document.getElementById('especialidad_id');
            const doctorSelect = document.getElementById('doctor_id');
            const sedeInput = document.getElementById('sede_id');
            const sedeInfo = document.getElementById('sede_info');
            const pacienteSelect = document.getElementById('usuario_id');
            const pacienteInfo = document.getElementById('paciente_info');

            doctorSelect.disabled = true;
            sedeInput.disabled = true;
            sedeInfo.textContent = '';
            pacienteInfo.textContent = '';

            especialidadSelect.addEventListener('change', function() {
                if (this.value) {
                    doctorSelect.disabled = false;
                } else {
                    doctorSelect.disabled = true;
                    doctorSelect.innerHTML = '<option value="">Seleccionar</option>';
                    sedeInput.value = '';
                    sedeInfo.textContent = '';
                }
            });

            doctorSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const sedeId = selectedOption.dataset.sedeId;
                const sedeNombre = selectedOption.dataset.sedeNombre;
                const sedeDireccion = selectedOption.dataset.sedeDireccion;

                sedeInput.value = sedeId || '';
                sedeInfo.textContent = sedeId ? `Sede: ${sedeNombre} - Dirección: ${sedeDireccion}` : '';
            });

            pacienteSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const pacienteNombre = selectedOption.dataset.nombre;
                const pacienteEmail = selectedOption.dataset.email;
                const pacienteTelefono = selectedOption.dataset.telefono;

                pacienteInfo.textContent = pacienteNombre ? `Nombre: ${pacienteNombre} - Email: ${pacienteEmail} - Teléfono: ${pacienteTelefono}` : '';
            });
        });
    </script>
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
            <h2>Agendar Cita</h2>
            <?php if (!empty($errores)): ?>
                <div class="error">
                    <?php foreach ($errores as $error): ?>
                        <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if ($mensaje): ?>
                <div class="exito">
                    <p><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php endif; ?>
            <form action="agendar_citas.php" method="post">
                <div class="form-group">
                    <label for="usuario_id">Paciente:</label>
                    <select id="usuario_id" name="usuario_id" required>
                        <option value="">Seleccionar</option>
                        <?php foreach ($pacientes as $paciente): ?>
                            <option value="<?php echo htmlspecialchars($paciente['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-nombre="<?php echo htmlspecialchars($paciente['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-email="<?php echo htmlspecialchars($paciente['email'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-telefono="<?php echo htmlspecialchars($paciente['telefono'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($paciente['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="especialidad_id">Especialidad:</label>
                    <select id="especialidad_id" name="especialidad_id" required>
                        <option value="">Seleccionar</option>
                        <?php foreach ($especialidades as $especialidad): ?>
                            <option value="<?php echo htmlspecialchars($especialidad['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($especialidad['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="doctor_id">Doctor:</label>
                    <select id="doctor_id" name="doctor_id" required>
                        <option value="">Seleccionar</option>
                        <?php foreach ($doctores as $doctor): ?>
                            <option value="<?php echo htmlspecialchars($doctor['doctor_id'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-sede-id="<?php echo htmlspecialchars($doctor['sede_id'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-sede-nombre="<?php echo htmlspecialchars($doctor['sede_nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-sede-direccion="<?php echo htmlspecialchars($doctor['direccion'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($doctor['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" id="fecha" name="fecha" required>
                </div>
                <div class="form-group">
                    <label for="hora">Hora:</label>
                    <select id="hora" name="hora" required>
                        <option value="">Seleccionar</option>
                        <?php foreach ($horarios_disponibles as $hora => $descripcion): ?>
                            <option value="<?php echo htmlspecialchars($hora, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="hidden" id="sede_id" name="sede_id">
                <div class="form-group">
                    <label for="sede_info">Sede:</label>
                    <div id="sede_info"></div>
                </div>
                <div class="form-group">
                    <label for="paciente_info">Datos del Paciente:</label>
                    <div id="paciente_info"></div>
                </div>
                <div class="form-group">
                    <label for="motivo">Motivo:</label>
                    <textarea id="motivo" name="motivo"></textarea>
                </div>
                <button type="submit" name="btn-agendar-cita" class="btn-agendar-cita">Agendar Cita</button>
            </form>
            <p class="footer">&copy; 2024 Clínica Dental del Dr. Fabián Mora. Todos los derechos reservados.</p>

        </section>
    </div>       
</body>

</html>