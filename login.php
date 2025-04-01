<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require './Conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    // Verificar que la conexión se haya establecido
    if (!$pdo) {
        die('No se pudo conectar a la base de datos.');
    }

    // Consulta para verificar las credenciales del usuario
    try {
        $stmt = $pdo->prepare('SELECT id, rol, contrasena FROM usuarios WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && $contrasena === $usuario['contrasena']) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['rol'] = $usuario['rol'];

            // Redirigir según el rol del usuario
            switch ($usuario['rol']) {
                case 'paciente':
                    header('Location: ./Pacientes/principal_pacientes.php');
                    break;
                case 'doctor':
                    header('Location: ./Doctores/principal_doctores.php');
                    break;
                case 'administrador':
                    header('Location: ./Admin/principalAdmin.php');
                    break;
                default:
                    echo 'Rol desconocido.';
            }
            exit;
        } else {
            $error = 'Credenciales incorrectas.';
        }
    } catch (PDOException $e) {
        $error = 'Error en la consulta: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Dental - Iniciar sesión</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e0f2f7;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            background-color: rgb(3, 29, 56);
            color: white;
            padding: 1rem 0;
        }

        .header .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .header .logo img {
            height: 40px;
            margin-right: 10px;
        }

        .header .nav a {
            color: white;
            text-decoration: none;
            margin-left: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            display: flex; /* Añadido para alinear icono y texto */
            align-items: center; /* Añadido para alinear icono y texto */
        }

        .header .nav a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .header .nav a i {
            margin-right: 5px; /* Espacio entre el icono y el texto */
        }

        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container-cuenta {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 400px;
            text-align: center;
        }

        .container-cuenta h2 {
            color: #007bff;
            margin-bottom: 1.5rem;
        }

        .container-cuenta form {
            display: flex;
            flex-direction: column;
        }

        .container-cuenta label {
            text-align: left;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .container-cuenta input {
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .container-cuenta button {
            background-color: hsla(211, 91.60%, 23.30%, 0.36);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .container-cuenta button:hover {
            background-color: rgb(5, 33, 63);
        }

        .container-cuenta .error {
            color: #d32f2f;
            margin-bottom: 1rem;
        }

        .footer {
            background-color: rgb(5, 33, 63);
            color: white;
            text-align: center;
            padding: 1rem 0;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="logo">
                <img src="Imagenes/odontologia.png" alt="Logo de Clínica Odontológica">
                Clínica Dental
            </h1>
            <nav class="nav">
                <a href="index.php" class="nav-link"><i class="fas fa-home"></i> Inicio</a>
                <a href="login.php" class="nav-link"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="container-cuenta">
            <h2>Iniciar sesión</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form action="login.php" method="post">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>

                <button type="submit">Iniciar sesión</button>
            </form>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Clínica Dental del Dr. Fabián Mora. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
