<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

// Conectar a la base de datos
require '../Conexion/conexion.php';

$errores = [];
$mensaje = "";

// Obtener el ID de la sede a editar
$id = $_GET['id'] ?? null;

if ($id) {
    // Obtener los datos de la sede para prellenar el formulario
    try {
        $stmt = $pdo->prepare('SELECT * FROM sedes WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $sede = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sede) {
            $errores[] = 'Sede no encontrada.';
        }
    } catch (PDOException $e) {
        $errores[] = 'Error al obtener los datos de la sede: ' . $e->getMessage();
    }
} else {
    $errores[] = 'ID de sede no proporcionado.';
}

// Procesar formulario de edición de sede
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    if (empty($nombre) || empty($direccion)) {
        $errores[] = 'El nombre y la dirección son obligatorios.';
    }

    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare('UPDATE sedes SET nombre = :nombre, direccion = :direccion, telefono = :telefono WHERE id = :id');
            $stmt->execute(['nombre' => $nombre, 'direccion' => $direccion, 'telefono' => $telefono, 'id' => $id]);
            $mensaje = 'Sede actualizada con éxito.';
            header("Location: sedes.php"); // Redirigir a la lista de sedes
            exit;
        } catch (PDOException $e) {
            $errores[] = 'Error al actualizar la sede: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sede</title>
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
            display: flex; /* Añadido para alinear icono y texto */
            align-items: center; /* Centrar verticalmente */
        }

        .sidebar li a i {
            margin-right: 10px; /* Espacio entre el icono y el texto */
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
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-guardar,
        .btn-cancelar {
            padding: 0.75rem 1.25rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
            color: white;
            min-width: 120px; /* Asegura un mínimo de ancho para la coherencia */
            box-sizing: border-box; /* Incluir padding y border en el ancho */
            display: inline-flex;  /* Usar inline-flex para centrar el contenido */
            align-items: center;   /* Centrar verticalmente */
            justify-content: center; /* Centrar horizontalmente */
        }

        .btn-guardar {
            background-color: #1a237e; /* Azul oscuro */
        }

        .btn-guardar:hover {
            background-color: #0d124a;
            transform: translateY(-2px);
        }

        .btn-cancelar {
            background-color: #800000; /* Vinotinto */
        }

        .btn-cancelar:hover {
            background-color: #590000;
            transform: translateY(-2px);
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;      /* Espacio entre los botones */
            margin-top: 2rem; /* Espacio arriba del contenedor de botones */
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
            <h2>Editar Sede</h2>
            <?php if ($mensaje): ?>
                <p class="exito"><?php echo htmlspecialchars($mensaje); ?></p>
            <?php endif; ?>
            <?php if ($errores): ?>
                <ul class="error">
                    <?php foreach ($errores as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ($sede): ?>
                <form action="editar_sede.php?id=<?php echo htmlspecialchars($id); ?>" method="post">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($sede['nombre']); ?>"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input type="text" id="direccion" name="direccion"
                               value="<?php echo htmlspecialchars($sede['direccion']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" id="telefono" name="telefono"
                               value="<?php echo htmlspecialchars($sede['telefono']); ?>">
                    </div>
                    <div class="action-buttons">
                        <button type="submit" class="btn-guardar">Guardar Cambios</button>
                        <a href="sedes.php" class="btn-cancelar">Cancelar</a>
                    </div>

                </form>
            <?php endif; ?>
        </section>
    </div>
</body>

</html>