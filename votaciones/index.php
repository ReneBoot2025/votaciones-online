<?php 
include 'db.php'; 
session_start();
$correoValido = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'])) {
  $correo = trim(strtolower($_POST['correo']));

  $pattern = '/^[a-zA-Z0-9._%+-]+@([a-zA-Z0-9.-]+\.)?(gmail\.com|hotmail\.com|yahoo\.com|outlook\.com|edu\.ec|edu\.com\.ec)$/';

  if (filter_var($correo, FILTER_VALIDATE_EMAIL) && preg_match($pattern, $correo)) {
    $stmt = $conexion->prepare("SELECT id FROM votantes WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
      $_SESSION['correo'] = $correo;
      $correoValido = true;
    } else {
      $error = "<strong>Este correo ya ha votado.</strong>";
    }
    $stmt->close();
  } else {
    $error = "<strong>Solo se permiten correos de Gmail, Outlook, Hotmail, Yahoo o dominios institucionales permitidos (edu.ec, edu.com.ec).</strong>";
  }
} elseif (isset($_SESSION['correo'])) {
  $correoValido = true;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Votación con Likes</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/png" href="image/logo.png" />
</head>
<body>
  <div class="banner"></div><br>
  <h1>🎉 Vota por tu favorito 🎉</h1>

  <?php if (!$correoValido): ?>
    <form method="POST" class="form-correo">
      <label for="correo">Ingresa tu correo electrónico para votar:</label><br />
      <input 
        type="email" 
        name="correo" 
        id="correo" 
        required 
        placeholder="ejemplo@gmail.com" 
        pattern=".+@([a-zA-Z0-9.-]+\.)?(gmail\.com|hotmail\.com|yahoo\.com|outlook\.com|edu\.ec|edu\.com\.ec)" 
        title="Solo se permiten correos de Gmail, Outlook, Hotmail, Yahoo o dominios institucionales" 
      />
      <button type="submit">Ingresar</button>
      <?php if (isset($error)): ?>
        <p class="error"><?= $error ?></p>
        <audio autoplay src="sonidos/error.mp3"></audio>
      <?php endif; ?>
    </form>
  <?php else: ?>
    <div class="listas">
      <?php
      $correo = $_SESSION['correo'] ?? '';
      $result = $conexion->query("SELECT * FROM elementos LIMIT 2");
      while ($row = $result->fetch_assoc()):
        $stmt2 = $conexion->prepare("SELECT COUNT(*) FROM votantes WHERE correo = ? AND id_elemento = ?");
        $stmt2->bind_param("si", $correo, $row['id']);
        $stmt2->execute();
        $stmt2->bind_result($count);
        $stmt2->fetch();
        $votoUsuario = $count > 0 ? 1 : 0;
        $stmt2->close();
      ?>
        <div class="elemento <?= htmlspecialchars($row['lista']) ?>">
          <h3><?= htmlspecialchars($row['nombre']) ?></h3>
          <?php if (!empty($row['video_url'])): ?>
            <div class="video-container">
              <?php 
              $video_url = $row['video_url'];
              if (strpos($video_url, 'drive.google.com') !== false) {
                  // Extraer ID de Google Drive correctamente
                  if (preg_match('/\/d\/([a-zA-Z0-9_-]{25,})/', $video_url, $matches)) {
                      $videoID = $matches[1];
                      $embedUrl = "https://drive.google.com/uc?export=preview&id=" . $videoID;
                      echo '<iframe width="100%" height="200" src="' . htmlspecialchars($embedUrl) . '" frameborder="0" allowfullscreen allow="autoplay; encrypted-media" sandbox="allow-scripts allow-same-origin allow-presentation"></iframe>';
                  } else {
                      echo "<p>Video no válido de Drive.</p>";
                  }
              } elseif (preg_match('/(youtube\.com|youtu\.be)/', $video_url)) {
                  echo '<iframe width="100%" height="200" src="' . htmlspecialchars($video_url) . '" frameborder="0" allowfullscreen allow="autoplay; encrypted-media" sandbox="allow-scripts allow-same-origin allow-presentation"></iframe>';
              } else {
                  echo '<video width="100%" height="200" controls>
                          <source src="' . htmlspecialchars($video_url) . '" type="video/mp4" />
                          Tu navegador no soporta la reproducción de video.
                        </video>';
              }
              ?>
            </div>
          <?php endif; ?>
          <button class="like-btn" data-id="<?= (int)$row['id'] ?>" <?= $votoUsuario ? 'disabled' : '' ?>>
            ❤️ <span class="contador"><?= $votoUsuario ?></span>
          </button>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>

  <audio id="like-sound" src="sonidos/like.mp3" preload="auto"></audio>
  <audio id="error-sound" src="sonidos/error.mp3" preload="auto"></audio>

  <div id="voto-modal" class="modal">
    <div class="modal-content">
      <h2 id="modal-text">¡Gracias por votar!</h2>
      <button id="cerrar-modal">Cerrar</button>
    </div>
  </div>

  <script src="js/script.js"></script>

  <footer class="footer">
    <p>© <?= date('Y') ?> "JUAN XXIII - Unidad Educativa Particular". Todos los derechos reservados.</p>
    <div class="social-icons">
      <a href="https://wa.me/+593998884768" target="_blank"><i class="fab fa-whatsapp"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="https://www.facebook.com/JuanXXIIIUEP" target="_blank"><i class="fab fa-facebook"></i></a>
      <a href="https://www.instagram.com/liceojuan23/?hl=es" target="_blank"><i class="fab fa-instagram"></i></a>
    </div>
  </footer>
</body>
</html>
