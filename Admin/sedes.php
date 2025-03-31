<?php
require '../Conexion/conexion.php';

$errores = [];
$mensaje = "";

// Procesar formulario de agregar/editar sede
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $id = $_POST['id'] ?? null;

    if (empty($nombre) || empty($direccion)) {
        $errores[] = 'El nombre y la dirección son obligatorios.';
    }

    if (empty($errores)) {
        if ($id) {
            // Actualizar sede
            $stmt = $pdo->prepare("UPDATE sedes SET nombre = :nombre, direccion = :direccion, telefono = :telefono WHERE id = :id");
            $stmt->execute(['nombre' => $nombre, 'direccion' => $direccion, 'telefono' => $telefono, 'id' => $id]);
            $mensaje = "Sede actualizada con éxito.";
        } else {
            // Insertar nueva sede
            $stmt = $pdo->prepare("INSERT INTO sedes (nombre, direccion, telefono) VALUES (:nombre, :direccion, :telefono)");
            $stmt->execute(['nombre' => $nombre, 'direccion' => $direccion, 'telefono' => $telefono]);
            $mensaje = "Sede agregada con éxito.";
        }
    }
}

// Obtener sedes para listar
$stmt = $pdo->query("SELECT * FROM sedes");
$sedes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Procesar eliminación de sede
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM sedes WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: sedes.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Sedes</title>
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

        .btn-editar,
        .btn-eliminar,
        .btn-crear {
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

        .btn-editar {
            background-color: #1a237e; /* Azul oscuro */
        }

        .btn-eliminar {
            background-color: #800000; /* Vinotinto */
        }

        .btn-crear {
            background-color: #1a237e; /* Azul oscuro */
            margin-bottom: 20px;
        }

        .btn-editar:hover {
            background-color: #0d124a;
            transform: translateY(-2px);
        }

        .btn-eliminar:hover {
            background-color: #590000;
            transform: translateY(-2px);
        }

        .btn-crear:hover {
            background-color: #0d124a;
            transform: translateY(-2px);
        }

        .footer {
            /* Cambio aquí: Quitamos el fondo azul */
            color: black;
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
            <h2>Gestión de Sedes</h2>
            <a href="crear_sede.php" class="btn-crear">Añadir Sede</a>
            <?php if (!empty($mensaje)): ?>
                <p><?php echo $mensaje; ?></p>
            <?php endif; ?>
            <?php if (!empty($errores)): ?>
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sedes as $sede) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($sede['id']); ?></td>
                            <td><?php echo htmlspecialchars($sede['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($sede['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($sede['telefono']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="editar_sede.php?id=<?php echo htmlspecialchars($sede['id']); ?>" class="btn-editar">Editar</a>
                                    <a href="sedes.php?eliminar=<?php echo htmlspecialchars($sede['id']); ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar esta sede?');">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Clínica Dental del Dr. Fabián Mora. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>