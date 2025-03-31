<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

require '../Conexion/conexion.php';

try {
    $stmt = $pdo->prepare('SELECT nombre, email FROM usuarios WHERE id = :id');
    $stmt->execute(['id' => $_SESSION['usuario_id']]);
    $paciente = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->query('SELECT COUNT(*) FROM citas WHERE estado = "agendada"');
    $numCitasAgendadas = $stmt->fetchColumn();

    $stmt = $pdo->query('SELECT COUNT(*) FROM usuarios');
    $numUsuarios = $stmt->fetchColumn();

    $stmt = $pdo->query('SELECT COUNT(*) FROM especialidades');
    $numEspecialidades = $stmt->fetchColumn();

    $stmt = $pdo->query('SELECT COUNT(*) FROM sedes');
    $numSedes = $stmt->fetchColumn();
} catch (PDOException $e) {
    die('Error en la consulta: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal Admin</title>
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
        }

        .dashboard-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .summary-card {
            background-color: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra sutil */
            text-align: center;
        }

        .summary-card h3 {
            margin-bottom: 0.5rem;
            color: #333; /* Color de texto más oscuro */
        }

        .summary-card p {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #555; /* Color de texto más oscuro */
        }

        .btn-view {
            display: inline-flex; /* Usar inline-flex */
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
            font-size: 1rem;
        }

        .btn-view:hover {
            background-color: #0d124a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .content h2 {
            font-family: 'Arial Black', sans-serif;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 2rem;
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
        <h2>Bienvenido, <?php echo htmlspecialchars($paciente['nombre']); ?></h2>
        <div class="dashboard-summary">
            <div class="summary-card">
                <h3>Citas Agendadas</h3>
                <p><?php echo htmlspecialchars($numCitasAgendadas); ?> citas</p>
                <a href="ver_citas.php" class="btn-view">Ver Detalles</a>
            </div>
            <div class="summary-card">
                <h3>Usuarios</h3>
                <p><?php echo htmlspecialchars($numUsuarios); ?> usuarios</p>
                <a href="usuarios.php" class="btn-view">Gestionar Usuarios</a>
            </div>
            <div class="summary-card">
                <h3>Especialidades</h3>
                <p><?php echo htmlspecialchars($numEspecialidades); ?> especialidades</p>
                <a href="especialidades.php" class="btn-view">Gestionar Especialidades</a>
            </div>
            <div class="summary-card">
                <h3>Sedes</h3>
                <p><?php echo htmlspecialchars($numSedes); ?> sedes</p>
                <a href="sedes.php" class="btn-view">Gestionar Sedes</a>
            </div>
        </div>
    </div>
</body>

</html>