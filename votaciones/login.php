<?php
include 'db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'])) {
    $correo = strtolower(trim($_POST['correo']));

    // Validar formato general del email
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo inválido.";
    } else {
        // Patrón regex para validar dominios permitidos (con subdominios)
        $pattern = '/^[a-zA-Z0-9._%+-]+@([a-zA-Z0-9-]+\.)*(gmail\.com|hotmail\.com|yahoo\.com|outlook\.com|edu\.ec|edu\.com\.ec)$/';

        if (!preg_match($pattern, $correo)) {
            $error = "Solo se permiten correos Gmail, Outlook, Hotmail, Yahoo o dominios institucionales (edu.ec, edu.com.ec).";
        } else {
            // Verificar si ya votó
            $stmt = $conexion->prepare("SELECT id FROM votantes WHERE correo = ?");
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Este correo ya ha votado.";
            } else {
                $_SESSION['correo'] = $correo;
                header('Location: index.php');
                exit;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Votación 2025</title>
<link rel="icon" type="image/png" href="image/logo.png" />

<style>
  body {
  margin: 0;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: url('image/fondo.jpg') no-repeat center center fixed;
  background-size: cover;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #fff;
}

.login-box {
  background: rgba(255,255,255,0.15);
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.3);
  width: 520px;
  max-width: 90vw;
  text-align: center;
  color: #fff;
}

input[type="email"] {
  display: block;       /* hace que el input sea bloque para que margin auto funcione */
  margin: 15px auto 25px auto; /* centra horizontalmente */
  width: 100%;
  padding: 15px;
  border-radius: 6px;
  border: none;
  font-size: 16px;
  text-align: center;   /* centra el texto dentro del input */
  max-width: 400px;     /* opcional: limita el ancho para que no sea muy grande */
}

button {
  background-color: #FFD700;
  border: none;
  padding: 12px 20px;
  border-radius: 8px;
  font-size: 18px;
  cursor: pointer;
  color: #000;
  font-weight: bold;
  box-shadow: 0 0 10px #FFD700;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #ffec5c;
}

.error {
  background: rgba(255, 0, 0, 0.7);
  padding: 10px;
  border-radius: 6px;
  margin-bottom: 15px;
}
</style>

</head>
<body>
  <div class="login-box">
  <img src="image/logo.png" alt="Logo" style="width: 110px; margin-bottom: 10px; border-radius: 8px;">

  <h1>🎉 UEP-JXXIII 2025-2026 🎉</h1>

  <h2>"Gran Corazón en Pequeños Líderes"</h2>

  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <center><input 
      type="email" 
      name="correo" 
      placeholder="Ingresa tu correo aquí" 
      required 
      pattern=".+@([a-zA-Z0-9-]+\.)*(gmail\.com|hotmail\.com|yahoo\.com|outlook\.com|edu\.ec|edu\.com\.ec)" 
      title="Solo se permiten correos Gmail, Outlook, Hotmail, Yahoo o dominios institucionales (edu.ec, edu.com.ec)"
    ></center>
    <button type="submit">Ingresar</button>
  </form>
</div>
</body>
</html>
