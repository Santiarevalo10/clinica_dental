<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Dental Dr. Fabian Mora</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .header {
            background-color: #2c3e50; /* Fondo oscuro */
            color: white;
            padding: 15px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header .container-fluid {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .logo {
            display: flex;
            align-items: center;
            font-size: 1.5em;
            font-weight: bold;
        }

        .header .logo img {
            height: 40px; /* Ajuste del tamaño del logo */
            margin-right: 10px;
        }

        .header .nav {
            display: flex;
            align-items: center;
        }

        .header .nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.9em;
        }

        .header .nav a:hover {
            background-color: #34495e;
        }

        .header .nav i {
            font-size: 1.5em;
            margin-bottom: 5px;
        }
        <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Dental Dr. Fabian Mora</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .header {
            background-color: #003366;
            color: white;
            padding: 15px 0;
        }

        .header .container-fluid {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header .logo {
            display: flex;
            align-items: center;
            font-size: 1.5em;
            font-weight: bold;
        }

        .header .logo img {
            height: 50px;
            margin-right: 15px;
        }

        .header .nav .nav-link {
            color: white;
            margin: 0 10px;
        }

        .hero {
            padding: 50px 20px;
            text-align: center;
        }

        .hero img {
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .hero .text-content {
            background-color: #e0f7fa;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .carousel-title {
            text-align: center;
            color: white;
            margin: 30px 0;
            font-size: 2em;
            font-weight: bold;
        }

        .features {
            padding: 50px 20px;
            background-color: #f0f0f0;
        }

        .features .feature-box {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            opacity: 0;
            transform: translateY(30px);
        }

        .features .feature-box:hover {
            transform: translateY(-5px);
        }

        .features .feature-box img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        #preguntas-frecuentes {
            padding: 50px 20px;
            background-color: #e8f5e9;
        }

        #preguntas-frecuentes .card {
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #preguntas-frecuentes .card-header {
            background-color: #fff;
            padding: 15px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #preguntas-frecuentes .card-body {
            padding: 20px;
        }

        .iconos-flotantes {
            position: fixed;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .icono-flotante {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            color: white;
            font-size: 24px;
            transition: transform 0.3s ease;
        }

        .icono-flotante:hover {
            transform: scale(1.1);
        }

        .icono-flotante.instagram {
            background-color: #e4405f;
        }

        .icono-flotante.facebook {
            background-color: #1877f2;
        }

        .icono-flotante.whatsapp {
            background-color: #25d366;
        }

        .politica-datos {
            padding: 50px 20px;
            background-color: #f8f9fa;
            border: 5px solid #003366;
            border-radius: 10px;
        }

        .footer {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        /* Animaciones */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .fadeIn {
            animation: fadeIn 1s ease-in-out;
        }

        .lazy-load {
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .lazy-load.loaded {
            opacity: 1;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="container-fluid">
            <h1 class="logo">
                <img src="Imagenes/odontologia.png" alt="Logo de Clínica Odontológica">
                Clínica Dental Dr. Fabian Mora
            </h1>
            <nav class="nav">
                <a href="#bienvenido">
                    <i class="fas fa-home"></i>
                    Inicio
                </a>
                <a href="#servicios">
                    <i class="fas fa-teeth"></i>
                    Servicios
                </a>
                <a href="#preguntas-frecuentes">
                    <i class="fas fa-question-circle"></i>
                    Preguntas
                </a>
                <a href="login.php">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </a>
            </nav>
        </div>
    </header>

    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
</body>

</html>

<!--FOTO DEL PRIMER INICIO --->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clínica Dental Dr. Fabian Mora</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    /* Estilos para la sección hero */
   .hero {
  background-color: black;
  color: white; /* Asegura que el texto sea visible */
  min-height: 400px; /* Altura mínima */
  padding: 40px; /* Espaciado interno */
  display: flex; /* Para centrar el contenido fácilmente */
  align-items: center; /* Centra verticalmente */
  justify-content: center; /* Centra horizontalmente */
  position: relative; /* Necesario para posicionar el canvas */
}

.hero-content {
  text-align: center; /* Centra el texto */
  max-width: 800px; /* Limita el ancho del contenido */
}

.hero-title {
  font-size: 2.5em; /* Tamaño de la fuente del título */
  margin-bottom: 20px;
}

.hero-description {
  font-size: 1.1em;
  margin-bottom: 30px;
}

.btn-primary {
  background-color: #007bff; /* Un azul de ejemplo */
  color: white;
  padding: 10px 20px;
  text-decoration: none;
  border-radius: 5px;
}

#heroCanvas {
  position: absolute; /* Para que no afecte el flujo del contenido */
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 0; /* Asegura que esté detrás del contenido */
}

    /* Animación de escritura */
    @keyframes typing {
      from { width: 0 }
      to { width: 100% }
    }

    /* Animación del cursor */
    @keyframes blink-caret {
      from, to { border-color: transparent }
      50% { border-color: white; }
    }

    .hero-title i {
      font-size: 1.5em;
      margin-right: 10px; /* Espacio entre el icono y el texto */
      color: #007bff; /* Color del icono */
    }

    .hero-description {
      font-size: 1.2em;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
<section id="bienvenido" class="hero">
  <div class="hero-content">
    <h2 class="hero-title">
      <i class="fas fa-tooth"></i> <span class="hero-title-text">DR. FABIAN MORA</span>
    </h2>
    <p class="hero-description" id="typing-text">
      Desde su apertura el 9 de noviembre de 2020, la Clínica Odontológica del Dr. Fabian Mora ha sido un lugar donde la sonrisa se transforma. Con una sólida formación en Odontología General, Implantología y Rehabilitación Oral, y Ortodoncia y Ortopedia Maxilar, el Dr. Mora y su equipo se dedican a ofrecerte una experiencia dental excepcional.
    </p>
    <a href="#servicios" class="btn btn-primary btn-glow">Descubre Nuestros Servicios</a>
  </div>
  <canvas id="heroCanvas"></canvas>
</section>

<script type="importmap"></script>

<script>
  const text = document.getElementById("typing-text").textContent;
  const typingTextElement = document.getElementById("typing-text");
  typingTextElement.textContent = ""; // Limpiar el contenido inicial

  let charIndex = 0;
  let isDeleting = false;

  function type() {
    if (charIndex < text.length && !isDeleting) {
      typingTextElement.textContent += text.charAt(charIndex);
      charIndex++;
      setTimeout(type, 30); // Velocidad de escritura (ajusta este valor)
    }
  }

  document.addEventListener("DOMContentLoaded", type); // Iniciar la animación al cargar la página.
</script>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Banner con Sidebar</title>
  <style>
    body {
      margin: 0;
      font-family: sans-serif;
    }

    .banner {
      position: fixed;
      bottom: 20px; /* Aumenta el margen inferior */
      left: 20px; /* Añade un margen izquierdo */
      background-color: #f0f0f0;
      padding: 10px 20px;
      text-align: left;
      border-radius: 5px; /* Añade bordes redondeados */
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Añade sombra */
    }

    .sidebar-btn {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
    }

    .sidebar {
      position: fixed;
      top: 20px; /* Añade un margen superior */
      right: -320px; /* Aumenta el ancho */
      width: 300px; /* Aumenta el ancho */
      height: calc(100% - 40px); /* Ajusta la altura */
      background-color: white;
      box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
      transition: right 0.3s ease;
      padding: 20px;
      box-sizing: border-box;
      overflow-y: auto;
    }

    .sidebar.open {
      right: 20px; /* Ajusta la posición abierta */
    }

    .sidebar-content {
      text-align: center;
      padding: 10px;
    }

    .doctor-photo {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 20px;
    }

    .close-btn {
      background-color: #ccc;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      margin-top: 20px;
    }
  </style>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Banner con Sidebar</title>
  <style>
    body {
      margin: 0;
      font-family: sans-serif;
    }

    .banner {
      position: fixed;
      bottom: 40px; /* Aumenta el margen inferior */
      left: 20px;
      background-color:rgb(240, 240, 240);
      padding: 10px 20px;
      text-align: left;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .sidebar-btn {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
    }

    .sidebar {
      position: fixed;
      top: 20px;
      right: -320px;
      width: 300px;
      height: calc(100% - 40px);
      background-color: white;
      box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
      transition: right 0.3s ease;
      padding: 20px;
      box-sizing: border-box;
      overflow-y: auto;
    }

    .sidebar.open {
      right: 20px;
    }

    .sidebar-content {
      text-align: center;
      padding: 10px;
    }

    .doctor-photo {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 20px;
    }

    .close-btn {
      background-color: #ccc;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      margin-top: 20px;
    }
  </style>
</head>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clínica Dental Dr. Fabian Mora</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    /* Estilos generales */
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      color: #333;
    }

    /* Estilos para el sidebar */
    .sidebar {
      position: fixed;
      bottom: 0;
      left: -250px;
      width: 250px;
      height: 400px; /* Altura aumentada para campos adicionales */
      background-color: #fff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      transition: left 0.3s ease-in-out;
      z-index: 1000;
    }

    .sidebar.open {
      left: 0;
    }

    .sidebar-header {
      background-color: #003366;
      color: #fff;
      padding: 20px;
      text-align: center;
    }

    .sidebar-header img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 10px;
    }

    .sidebar-content {
      padding: 10px;
    }

    .sidebar-content h3 {
      margin-top: 0;
      font-size: 1.3em;
    }

    .sidebar-content p {
      margin-bottom: 5px;
      font-size: 0.9em;
    }

    .close-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: transparent;
      border: none;
      font-size: 20px;
      color: #fff;
      cursor: pointer;
    }

    /* Estilos para el botón de abrir/cerrar */
    .toggle-btn {
      position: fixed;
      bottom: 60px; /* Botón más abajo */
      left: 20px;
      background-color: #003366;
      color: #fff;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
      z-index: 1000;
    }

    /* Color del banner */
    .sidebar-header {
      background-color: #007bff; /* Color azul para el banner */
    }
  </style>
</head>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clínica Dental Dr. Fabian Mora</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    /* Estilos generales */
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      color: #333;
    }

    /* Estilos para el sidebar */
    .sidebar {
      position: fixed;
      bottom: 0;
      left: -250px;
      width: 250px;
      height: 400px;
      background-color: #e0f7fa; /* Fondo azul claro para el sidebar */
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      transition: left 0.3s ease-in-out;
      z-index: 1000;
    }

    .sidebar.open {
      left: 0;
    }

    .sidebar-header {
      background-color:rgb(3, 14, 22);
      color: #fff;
      padding: 20px;
      text-align: center;
    }

    .sidebar-header img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 10px;
    }

    .sidebar-content {
      padding: 10px;
    }

    .sidebar-content h3 {
      margin-top: 0;
      font-size: 1.3em;
    }

    .sidebar-content p {
      margin-bottom: 5px;
      font-size: 0.9em;
    }

    .close-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: transparent;
      border: none;
      font-size: 20px;
      color: #fff;
      cursor: pointer;
    }

    /* Estilos para el botón de abrir/cerrar */
    .toggle-btn {
      position: fixed;
      bottom: 60px;
      left: 20px;
      background-color: #003366;
      color: #fff;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
      z-index: 1000;
    }
  </style>
</head>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Información del Doctor</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      background-color: #f4f7f9;
      position: relative; /* Necesario para que el botón flotante se posicione correctamente */
    }

    .toggle-btn-info {
      position: fixed; /* Mantiene el botón en su lugar durante el scroll */
      bottom: 20px;
      left: 20px; /* Cambiado de right a left */
      background-color: #1a237e; /* Azul oscuro */
      color: white;
      padding: 0.75rem 1.25rem;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .toggle-btn-info:hover {
      background-color: #0d124a;
      transform: translateY(-5px);
      box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
    }

    .overlay-info {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5); /* Oscurecer el fondo */
      z-index: 1000;
      display: none;
      justify-content: center;
      align-items: center;
    }

    .popup-info {
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); /* Sombra más definida */
      padding: 2rem;
      max-width: 500px;
      width: 90%;
      z-index: 1001; /* Sobre el overlay */
      position: relative;
      animation: fadeIn 0.3s ease-out; /* Animación de aparición */
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.9); }
      to { opacity: 1; transform: scale(1); }
    }

    .popup-close-info {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 1.5rem;
      cursor: pointer;
      color: #777; /* Un gris más claro */
      transition: color 0.2s ease;
    }

    .popup-close-info:hover {
      color: #333;
    }

    .popup-header-info img {
      max-width: 150px;
      height: auto;
      border-radius: 50%;
      margin-bottom: 1rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Sombra ligera en la imagen */
    }

    .popup-header-info h3 {
      font-size: 1.75rem;
      margin-bottom: 0.5rem;
      text-align: center;
      color: #333;
      font-weight: bold;
    }

    .popup-content-info p {
      font-size: 1.1rem;
      margin-bottom: 0.75rem;
      line-height: 1.4;
      color: #444; /* Texto un poco más claro */
    }

    .popup-content-info strong {
      font-weight: bold;
      color: #222;
    }
  </style>
</head>
<body>

  <button class="toggle-btn-info" onclick="openDoctorInfoPopup()">
    <i class="fas fa-info-circle"></i> Información del Doctor
  </button>

  <div class="overlay-info" id="doctorInfoOverlay">
    <div class="popup-info">
      <span class="popup-close-info" onclick="closeDoctorInfoPopup()">&times;</span>
      <div class="popup-header-info">
        <img src="Imagenes/Doctor1.jpg" alt="Doctor Fabian Mora">
        <h3>Dr. Fabian Mora</h3>
      </div>
      <div class="popup-content-info">
        <p><strong>Especialidad:</strong> Odontología general, Implantología, Ortodoncia</p>
        <p><strong>Experiencia:</strong> 10 años</p>
        <p><strong>Edad:</strong> 40 años</p>
        <p><strong>Teléfono:</strong> +57 319 590 0206</p>
        <p><strong>Email:</strong> fabianmora@clinicadental.com</p>
        <p><strong>Perfil:</strong> El Dr. Fabian Mora es un odontólogo con amplia experiencia en el cuidado de la salud bucal. Se dedica a brindar atención personalizada y de calidad a sus pacientes.</p>
      </div>
    </div>
  </div>

  <script>
    function openDoctorInfoPopup() {
      document.getElementById('doctorInfoOverlay').style.display = 'flex';
    }

    function closeDoctorInfoPopup() {
      document.getElementById('doctorInfoOverlay').style.display = 'none';
    }
  </script>

</body>
</html>
</html>






</body>
</html>

<style>
/* Estilos adicionales para los efectos */
.doctor-image-container {
    overflow: hidden; /* Para el efecto de revelado */
}

.doctor-image {
    transition: transform 0.5s ease;
    transform: scale(1.05); /* Ligeramente ampliada */
}

.doctor-image-container:hover .doctor-image {
    transform: scale(1.1); /* Zoom al hacer hover */
}

.animated-title {
    overflow: hidden;
    white-space: nowrap;
    border-right: .15em solid orange; /* Efecto de escritura */
    animation: typing 3.5s steps(40, end), blink-caret .75s step-end infinite;
}

/* Animación de escritura */
@keyframes typing {
    from { width: 0 }
    to { width: 100% }
}

/* Animación del cursor */
@keyframes blink-caret {
    from, to { border-color: transparent }
    50% { border-color: orange; }
}

.animated-paragraph {
    opacity: 0;
    animation: fadeIn 1s ease-in-out forwards 0.5s; /* Retraso de 0.5 segundos */
}

@keyframes fadeIn {
    to { opacity: 1; }
}

.btn-glow {
    position: relative;
    overflow: hidden;
}

.btn-glow:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.4), transparent);
    transform: translate(-50%, -50%) scale(0);
    transition: transform 0.8s;
}

.btn-glow:hover:before {
    transform: translate(-50%, -50%) scale(1);
}

.btn-primary {
  background-color: #435EBE;
}

.btn-primary:hover{
    background-color: #7A89CB;
    transition:0.5s;
}

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .header {
            background-color: #003366;
            color: white;
            padding: 15px 0;
            position: sticky; /* Header fijo */
            top: 0; /* Se pega a la parte superior */
            z-index: 100; /* Asegura que esté encima de otros elementos */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Sombra sutil */
        }

        .header .container-fluid {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .logo {
            display: flex;
            align-items: center;
            font-size: 1.5em;
            font-weight: bold;
        }

        .header .logo img {
            height: 50px;
            margin-right: 15px;
        }

        .header .nav .nav-link {
            color: white;
            margin: 0 10px;
        }

        /* Resto de tu CSS */
        .hero {
            padding: 50px 20px;
            text-align: center;
        }

        .hero img {
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .hero .text-content {
            background-color: #e0f7fa;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .carousel-title {
            text-align: center;
            color: white;
            margin: 30px 0;
            font-size: 2em;
            font-weight: bold;
        }

        .features {
            padding: 50px 20px;
            background-color: #f0f0f0;
        }

        .features .feature-box {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            opacity: 0;
            transform: translateY(30px);
        }

        .features .feature-box:hover {
            transform: translateY(-5px);
        }

        .features .feature-box img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        #preguntas-frecuentes {
            padding: 50px 20px;
            background-color: #e8f5e9;
        }

        #preguntas-frecuentes .card {
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #preguntas-frecuentes .card-header {
            background-color: #fff;
            padding: 15px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #preguntas-frecuentes .card-body {
            padding: 20px;
        }

        .iconos-flotantes {
            position: fixed;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .icono-flotante {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            color: white;
            font-size: 24px;
            transition: transform 0.3s ease;
        }

        .icono-flotante:hover {
            transform: scale(1.1);
        }

        .icono-flotante.instagram {
            background-color: #e4405f;
        }

        .icono-flotante.facebook {
            background-color: #1877f2;
        }

        .icono-flotante.whatsapp {
            background-color: #25d366;
        }

        .politica-datos {
            padding: 50px 20px;
            background-color: #f8f9fa;
            border: 5px solidrgb(202, 131, 37);
            border-radius: 10px;
        }

        .footer {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        /* Animaciones */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .fadeIn {
            animation: fadeIn 1s ease-in-out;
        }

        .lazy-load {
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .lazy-load.loaded {
            opacity: 1;
        }

</style>

<script>
    // Script para cargar las imágenes de forma perezosa
    document.addEventListener("DOMContentLoaded", function() {
        var lazyloadImages = document.querySelectorAll("img.lazy-load");
        var imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var image = entry.target;
                    image.src = image.dataset.src;
                    image.classList.remove("lazy-load");
                    imageObserver.unobserve(image);
                }
            });
        });

        lazyloadImages.forEach(function(image) {
            imageObserver.observe(image);
        });
    });
</script>


        <section class="hero" style="background-color:rgb(14, 30, 75); color: white; overflow: hidden;">
  <div class="container" style="display: flex; justify-content: space-between;">
    <div style="width: 48%; position: relative; border: 2px solid white; border-radius: 5px;">
      <div id="carouselExampleLeft" class="carousel slide" data-ride="carousel" data-interval="3000">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="Imagenes/FabianMora.jpeg" class="d-block w-100" alt="Imagen 1" style="animation: kenburns 8s ease-out both;">
            <div class="carousel-caption d-none d-md-block" style="background-color: rgba(0, 0, 0, 0.5); padding: 10px; border-radius: 5px;">
              <h5>Tratamientos Avanzados</h5>
            </div>
          </div>
          <div class="carousel-item">
            <img src="Imagenes/implante.jpeg" class="d-block w-100" alt="Imagen 2" style="animation: kenburns 8s ease-out both;">
            <div class="carousel-caption d-none d-md-block" style="background-color: rgba(0, 0, 0, 0.5); padding: 10px; border-radius: 5px;">
              <h5>Implantes Dentales</h5>
            </div>
          </div>
          <div class="carousel-item">
            <img src="Imagenes/sonrisa.jpeg" class="d-block w-100" alt="Imagen 3" style="animation: kenburns 8s ease-out both;">
            <div class="carousel-caption d-none d-md-block" style="background-color: rgba(0, 0, 0, 0.5); padding: 10px; border-radius: 5px;">
              <h5>Sonrisas Brillantes</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div style="width: 48%; position: relative; border: 2px solid white; border-radius: 5px;">
      <div id="carouselExampleRight" class="carousel slide" data-ride="carousel" data-interval="3000">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="Imagenes/soria.jpeg" class="d-block w-100" alt="Imagen 4" style="animation: kenburns 8s ease-out both;">
            <div class="carousel-caption d-none d-md-block" style="background-color: rgba(0, 0, 0, 0.5); padding: 10px; border-radius: 5px;">
              <h5>Atención Personalizada</h5>
            </div>
          </div>
          <div class="carousel-item">
            <img src="Imagenes/Logo.jpg" class="d-block w-100" alt="Imagen 5" style="animation: kenburns 8s ease-out both;">
            <div class="carousel-caption d-none d-md-block" style="background-color: rgba(0, 0, 0, 0.5); padding: 10px; border-radius: 5px;">
              <h5>Calidad y Confianza</h5>
            </div>
          </div>
          <div class="carousel-item">
            <img src="Imagenes/blanqueamiento.jpeg" class="d-block w-100" alt="Imagen 6" style="animation: kenburns 8s ease-out both;">
            <div class="carousel-caption d-none d-md-block" style="background-color: rgba(0, 0, 0, 0.5); padding: 10px; border-radius: 5px;">
              <h5>Blanqueamiento Dental</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <h2 class="carousel-title" style="text-align: center; margin-top: 20px;">Bienvenido a la Clínica Odontológica</h2>
</section>

<style>
  @keyframes kenburns {
    0% { transform: scale(1) translate(0, 0); }
    100% { transform: scale(1.25) translate(20%, 10%); }
  }
</style>

<style>
  @keyframes kenburns {
    0% { transform: scale(1) translate(0, 0); }
    100% { transform: scale(1.25) translate(20%, 10%); }
  }
</style>

<style>
  @keyframes kenburns {
    0% { transform: scale(1) translate(0, 0); }
    100% { transform: scale(1.25) translate(20%, 10%); }
  }
</style>

      <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios Dentales</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .oculto {
            display: none;
        }
        .feature-box img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios Dentales</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .oculto {
            display: none;
        }
        .feature-box img {
            max-width: 100%;
            height: auto;
        }
        #ver-mas, #ver-menos {
            margin-top: 20px; /* Añadido margen superior para bajar los botones */
        }
    </style>
</head>
<body>

<section id="servicios" class="features">
    <div class="container">
        <h2 class="carousel-title" style="color: #003366;">NUESTROS SERVICIOS</h2>
        <div class="row" id="servicios-contenedor">
            <div class="col-md-6 col-lg-4 feature-box lazy-load servicio-item" data-src="Imagenes/blanqueamiento.jpeg">
                <img src="Imagenes/blanqueamiento.jpeg" alt="Blanqueamiento Dental">
                <h3>Blanqueamiento Dental</h3>
                <p>Recupera la blancura natural de tus dientes.</p>
            </div>
            <div class="col-md-6 col-lg-4 feature-box lazy-load servicio-item" data-src="Imagenes/cordales.jpeg">
                <img src="Imagenes/cordales.jpeg" alt="Cordales">
                <h3>Cordales</h3>
                <p>Tratamiento de muelas del juicio.</p>
            </div>
            <div class="col-md-6 col-lg-4 feature-box lazy-load servicio-item" data-src="Imagenes/disenno.jpeg">
                <img src="Imagenes/disenno.jpeg" alt="Diseño de Sonrisa">
                <h3>Diseño de Sonrisa</h3>
                <p>Transforma tu sonrisa con nuestro servicio de diseño.</p>
            </div>
            <div class="col-md-6 col-lg-4 feature-box lazy-load servicio-item oculto" data-src="Imagenes/endodoncia.jpeg">
                <img src="Imagenes/endodoncia.jpeg" alt="Endodoncia">
                <h3>Endodoncia</h3>
                <p>Tratamientos de conducto para salvar tus dientes.</p>
            </div>
            <div class="col-md-6 col-lg-4 feature-box lazy-load servicio-item oculto" data-src="Imagenes/estetica.jpeg">
                <img src="Imagenes/estetica.jpeg" alt="Estética Dental">
                <h3>Estética Dental</h3>
                <p>Mejora la apariencia de tus dientes.</p>
            </div>
            <div class="col-md-6 col-lg-4 feature-box lazy-load servicio-item oculto" data-src="Imagenes/implantologia.jpeg">
                <img src="Imagenes/implantologia.jpeg" alt="Implantología">
                <h3>Implantología</h3>
                <p>Implantes dentales seguros y efectivos.</p>
            </div>
            <div class="col-md-6 col-lg-4 feature-box lazy-load servicio-item oculto" data-src="Imagenes/limpieza.jpeg">
                <img src="Imagenes/limpieza.jpeg" alt="Limpieza Dental">
                <h3>Limpieza Dental</h3>
                <p>Mantén tus dientes saludables con una limpieza profesional.</p>
            </div>
            <div class="col-md-6 col-lg-4 feature-box lazy-load servicio-item oculto" data-src="Imagenes/ortodoncia.jpeg">
                <img src="Imagenes/ortodoncia.jpeg" alt="Ortodoncia">
                <h3>Ortodoncia</h3>
                <p>Corrección de problemas de alineación y mordida.</p>
            </div>
            <div class="col-md-6 col-lg-4 feature-box lazy-load servicio-item oculto" data-src="Imagenes/ortopendia.jpeg">
                <img src="Imagenes/ortopendia.jpeg" alt="Ortopedia">
                <h3>Ortopedia</h3>
                <p>Tratamientos ortopédicos para tu salud dental.</p>
            </div>
        </div>
        <div class="text-center">
            <button id="ver-mas" class="btn btn-primary">Ver más</button>
            <button id="ver-menos" class="btn btn-secondary oculto">Ver menos</button>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const servicios = document.querySelectorAll('.servicio-item');
        const botonVerMas = document.getElementById('ver-mas');
        const botonVerMenos = document.getElementById('ver-menos');
        let serviciosVisibles = 3;

        function mostrarServicios(cantidad) {
            servicios.forEach((servicio, index) => {
                if (index < cantidad) {
                    servicio.classList.remove('oculto');
                } else {
                    servicio.classList.add('oculto');
                }
            });
        }

        mostrarServicios(serviciosVisibles);
        botonVerMenos.classList.add('oculto');

        botonVerMas.addEventListener('click', function() {
            mostrarServicios(servicios.length);
            botonVerMas.classList.add('oculto');
            botonVerMenos.classList.remove('oculto');
        });

        botonVerMenos.addEventListener('click', function() {
            mostrarServicios(serviciosVisibles);
            botonVerMenos.classList.add('oculto');
            botonVerMas.classList.remove('oculto');
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
        
        <!---Preguntas Frecuentes --->
        <!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clínica Dental Dr. Fabian Mora</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
  <style>
    /* Estilos para la sección de preguntas frecuentes */
    .faq-section {
      padding: 50px 20px;
      background-color: #f8f8f8;
    }

    .faq-title {
      text-align: center;
      margin-bottom: 30px;
      color: #333;
    }

    .faq-item {
      margin-bottom: 20px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      overflow: hidden; /* Para que el efecto de la flecha no se desborde */
    }

    .faq-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px;
      cursor: pointer;
      background-color: #f0f0f0;
      border-bottom: 1px solid #ddd;
    }

    .faq-button {
      background: none;
      border: none;
      font-size: 1.2em;
      font-weight: bold;
      color: #333;
      display: flex;
      align-items: center;
      width: 100%; /* Para que ocupe todo el ancho del header */
      text-align: left; /* Para alinear el texto a la izquierda */
    }

    .faq-button i {
      margin-left: 10px;
      transition: transform 0.3s ease;
    }

    .faq-body {
      padding: 20px;
      background-color: #fff;
    }

    /* Efecto de la flecha */
    .faq-item.collapsed .faq-button i {
      transform: rotate(0deg);
    }

    .faq-item:not(.collapsed) .faq-button i {
      transform: rotate(180deg);
    }
  </style>
</head>
<body>

<section style="border-top: 0px solid #003366;">
  <section id="preguntas-frecuentes" class="faq-section">
    <div class="container">
      <h2 class="carousel-title faq-title" data-aos="fade-up">PREGUNTAS FRECUENTES</h2>

      <div class="accordion faq-accordion" id="faqAccordion">
        <div class="faq-item" data-aos="flip-left">
          <div class="faq-header" id="faqHeadingOne">
            <button class="faq-button" type="button" data-toggle="collapse" data-target="#faqCollapseOne" aria-expanded="true" aria-controls="faqCollapseOne">
              ¿Dónde están ubicados?
              <i class="fas fa-chevron-down"></i>
            </button>
          </div>
          <div id="faqCollapseOne" class="collapse show" aria-labelledby="faqHeadingOne" data-parent="#faqAccordion">
            <div class="faq-body">
              <p>Cra. 45a #95-37 conslt 205, Barrios Unidos, Bogotá, Cundinamarca</p>
            </div>
          </div>
        </div>
        <div class="faq-item" data-aos="flip-right">
          <div class="faq-header" id="faqHeadingTwo">
            <button class="faq-button collapsed" type="button" data-toggle="collapse" data-target="#faqCollapseTwo" aria-expanded="false" aria-controls="faqCollapseTwo">
              ¿Qué medios de pago manejan?
              <i class="fas fa-chevron-down"></i>
            </button>
          </div>
          <div id="faqCollapseTwo" class="collapse" aria-labelledby="faqHeadingTwo" data-parent="#faqAccordion">
            <div class="faq-body">
              <p>Nequi, Daviplata, tarjeta de crédito, y en efectivo.</p>
            </div>
          </div>
        </div>
        <div class="faq-item" data-aos="flip-left">
          <div class="faq-header" id="faqHeadingThree">
            <button class="faq-button collapsed" type="button" data-toggle="collapse" data-target="#faqCollapseThree" aria-expanded="false" aria-controls="faqCollapseThree">
              ¿La valoración tiene algún costo?
              <i class="fas fa-chevron-down"></i>
            </button>
          </div>
          <div id="faqCollapseThree" class="collapse" aria-labelledby="faqHeadingThree" data-parent="#faqAccordion">
            <div class="faq-body">
              <p>Sí, tiene un costo de 300 mil.</p>
            </div>
          </div>
        </div>
        <div class="faq-item" data-aos="flip-right">
          <div class="faq-header" id="faqHeadingFour">
            <button class="faq-button collapsed" type="button" data-toggle="collapse" data-target="#faqCollapseFour" aria-expanded="false" aria-controls="faqCollapseFour">
              ¿En la clínica odontológica realizan exámenes de radiografía panorámica y otros estudios de diagnóstico por imagen?
              <i class="fas fa-chevron-down"></i>
            </button>
          </div>
          <div id="faqCollapseFour" class="collapse" aria-labelledby="faqHeadingFour" data-parent="#faqAccordion">
            <div class="faq-body">
              <p>No, pero contamos con un aliado.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</section>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
  AOS.init();
</script>

</body>
</html>

<style>
    .faq-section {
        background: linear-gradient(135deg, #e0f2f7, #cce0e5); /* Fondo degradado */
        padding: 50px 20px;
    }

    .faq-title {
        color: #003366;
        text-align: center;
        margin-bottom: 30px;
    }

    .faq-controls {
        text-align: center;
        margin-bottom: 20px;
    }

    .faq-accordion {
        border: none;
    }

    .faq-item {
        border-bottom: 1px solid #ddd;
        margin-bottom: 10px;
    }

    .faq-header {
        background-color: transparent;
        border: none;
        padding: 0;
    }

    .faq-button {
        background-color: transparent;
        border: none;
        padding: 15px;
        text-align: left;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1em;
        cursor: pointer;
        color: #333;
    }

    .faq-button:focus {
        outline: none;
    }

    .faq-button i {
        transition: transform 0.3s ease;
    }

    .faq-button.collapsed i {
        transform: rotate(-180deg);
    }

    .faq-body {
        padding: 15px;
        background-color: #f9f9f9;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleAllButton = document.getElementById('toggle-all-faq');
        const faqButtons = document.querySelectorAll('.faq-button');

        toggleAllButton.addEventListener('click', () => {
            faqButtons.forEach(button => {
                if (button.classList.contains('collapsed')) {
                    button.click(); // Abre si está cerrado
                } else {
                    button.click(); // Cierra si está abierto
                }
            });
        });
    });
</script>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Política de Datos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
<!DOCTYPE html>
<html lang="es">
<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  </head>
<body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Comentarios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    .card-img-top {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      margin: 20px auto;
    }
    .rating {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 10px;
    }
    .rating i {
      color: #ffc107; /* Color de las estrellas */
      margin: 0 2px;
    }
  </style>
</head>
<body>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Comentarios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    .card-img-top {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      margin: 20px auto;
    }

    .rating {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 10px;
    }
    .rating i {
      color: #ffc107; /* Color de las estrellas */
      margin: 0 2px;
    }
  </style>
</head>
<body>

<section class="comentarios py-5">
  <div class="container">
    <h2 class="text-center mb-4">Comentarios de Nuestros Pacientes</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="card mb-4">
          <img src="https://ui-avatars.com/api/?name=Juan+Rozo&background=random" class="card-img-top" alt="Foto de perfil de Juan Jimenez">
          <div class="card-body">
            <div class="rating">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <h5 class="card-title">Juan Rozo</h5>
            <p class="card-text">"¡Excelente servicio! El doctor fue muy amable y profesional. Me sentí muy cómodo durante todo el tratamiento."</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card mb-4">
          <img src="https://ui-avatars.com/api/?name=David+Bustos&background=random" class="card-img-top" alt="Foto de perfil de David Bustos">
          <div class="card-body">
            <div class="rating">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <h5 class="card-title">David Bustos</h5>
            <p class="card-text">"La clínica es muy moderna y limpia. El personal es muy atento y me explicaron todo con detalle. ¡Muy recomendable!"</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card mb-4">
          <img src="https://ui-avatars.com/api/?name=LiLiana+Gonzalez&background=random" class="card-img-top" alt="Foto de perfil de Liliana Arevalo">
          <div class="card-body">
            <div class="rating">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <h5 class="card-title">Liliana Gonzalez</h5>
            <p class="card-text">"¡Estoy muy contenta con los resultados! El tratamiento fue rápido y sin dolor. ¡Gracias al equipo de la clínica del Dr Fabian Mora!"</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
<section id="contacto" class="politica-datos">
  <div class="container">
    <div class="card bg-dark text-light" data-aos="zoom-in">
      <div class="card-body">
        <div class="text-center mb-4">
          <i class="fas fa-shield-alt fa-4x"></i>
        </div>
        <h2 class="text-center mb-3" style="font-family: 'Georgia', serif; font-style: italic;">Política de Datos</h2>
        <p class="text-center" style="font-size: 1.2rem; font-family: 'Georgia', serif; font-style: italic;">
          La política de datos es fundamental en la gestión y protección de la información.
          Asegura que los datos sean manejados de manera ética y responsable, promoviendo la transparencia y la confianza.
        </p>
        <div class="text-center">
          <a href="https://drive.google.com/file/d/1SVCGXyO7fkyKzTe2_MtZE_Wy9HAKeRS5/view?usp=drive_link" target="_blank" class="btn btn-primary btn-lg">Leer Más</a>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
    </main>

    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2024 Clínica Odontológica. Todos los derechos reservados.</p>
        </div>
    </footer>

    <div class="iconos-flotantes">
        <a href="https://www.instagram.com/odontologia.fabian.mora/" target="_blank" class="icono-flotante instagram">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="https://www.facebook.com/fabian.mora.104/" target="_blank" class="icono-flotante facebook">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://wa.me/573195900206" target="_blank" class="icono-flotante whatsapp">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.carousel').carousel();

            $('.accordion').on('show.bs.collapse', function () {
                $(this).find('.collapse.show').collapse('hide');
            });

            // Lazy loading
            function lazyLoadImages() {
                const lazyImages = document.querySelectorAll('.lazy-load');

                lazyImages.forEach(image => {
                    if (isElementInViewport(image)) {
                        image.src = image.getAttribute('data-src');
                        image.classList.add('loaded');
                    }
                });
            }

            // Helper function to check if an element is in the viewport
            function isElementInViewport(el) {
                const rect = el.getBoundingClientRect();
                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            }

            // Initial load
            lazyLoadImages();

            // Load images when scrolling
            window.addEventListener('scroll', lazyLoadImages);
        });
    </script>
</body>


<!--Boton para subir --->

<head>
    <style>

        /* Estilos para el botón de subir al inicio */
        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #003366;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1000;
        }

        .back-to-top.show {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <script>
        // Script para el botón de subir al inicio
        const backToTopButton = document.getElementById('backToTop');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>





</html>



