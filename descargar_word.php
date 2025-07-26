<?php
include 'db.php';

// Obtener totales
$votos = ['A' => 0, 'B' => 0];
$sql = "SELECT id_elemento, COUNT(*) AS total FROM votantes GROUP BY id_elemento";
$result = $conexion->query($sql);
while ($row = $result->fetch_assoc()) {
    if ($row['id_elemento'] == 1) $votos['A'] = $row['total'];
    if ($row['id_elemento'] == 2) $votos['B'] = $row['total'];
}

// Determinar ganadora
$ganadora = '';
if ($votos['A'] > $votos['B']) {
    $ganadora = 'Lista A';
} elseif ($votos['B'] > $votos['A']) {
    $ganadora = 'Lista B';
} else {
    $ganadora = 'Empate';
}

header("Content-Type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=resultados_votacion.doc");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
 
<title>Acta de Resultados</title>
<style>
  @page {
    size: A4;
    margin: 2cm 2cm 2cm 2cm;
  }
  body {
    font-family: Times New Roman, serif;
    margin: 0;
    padding: 0;
    color: #1a1a1a;
    background: #fff;
  }
  .container {
    padding: 20px 40px;
  }
  h1 {
    text-align: center;
    color: #004080;
    font-size: 15pt;
    margin-bottom: 10px;
    text-shadow: 1px 1px 2px #aaa;
  }
  p {
    font-size: 12pt;
    margin-top: 10px;
    text-align: justify;
    line-height: 1.6;
    color: #333;
  }
  .banner {
    background-color: #ffcc00;
    color: #3d2e00;
    font-weight: bold;
    font-size: 15pt;
    padding: 15px;
    border-radius: 10px;
    margin: 30px auto 40px auto;
    width: 90%;
    max-width: 700px;
    box-shadow: 0 0 15px #b38600;
    text-align: center;
  }

  table {
    border-collapse: collapse;
    margin: 10px auto; /* Centra la tabla horizontalmente */
    width: fit-content; /* Ajusta ancho según contenido */
    background: white;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    font-size: 12pt;
  }

  th, td {
    border: 1px solid #004080;
    padding: 5px 10px; /* Padding reducido para menos altura */
    text-align: center;
    white-space: nowrap; /* Evita que se parta el texto */
  }

  th {
    background-color: #004080;
    color: white;
    font-weight: 600;
    text-transform: uppercase;
  }

  td {
    color: #333;
  }

  .signature-section {
    margin-top: 80px;
    width: 700px;
    margin-left: auto;
    margin-right: auto;
    font-size: 12pt;
    color: #555;
    display: flex;
    justify-content: space-between;
  }
  .signature-block {
    width: 320px;
    text-align: center;
  }
  .signature-line {
    margin-top: 60px;
    border-bottom: 2px solid #004080;
    width: 100%;
  }
</style>
</head>
<body>
  <div class="container">
    <h1>ACTA DE RESULTADOS DE VOTACIÓN ONLINE</h1>

    <p>
      En la fecha <?= date("d/m/Y") ?>, se llevó a cabo el proceso de votación digital del video más viral de la Unidad Educativa Particular "Juan XXIII" 2025-2026, utilizando un sistema de votación online "Click and Like" que garantiza transparencia, seguridad y la participación activa de la comunidad liceina.
    </p>

    <p>
      El presente acta documenta los resultados oficiales obtenidos mediante el conteo automático de likes emitidos por los usuarios que se han registrado de forma voluntaria a este proceso, asegurando la legitimidad y validez del proceso democrático realizado.
    </p>

    <div class="banner">
      📢 Por decisión democrática y transparente a través del sistema de votación online "Click and Like", se declara como <strong>ganadora del proceso electoral estudiantil 2025-2026 a <?= $ganadora ?></strong>.
    </div>

    <center><table>
      <tr>
        <th>Listas</th>
        <th>Total likes</th>
      </tr>
      <tr>
        <td>Lista A</td>
        <td><?= $votos['A'] ?></td>
      </tr>
      <tr>
        <td>Lista B</td>
        <td><?= $votos['B'] ?></td>
      </tr>
    </table></center>

    <p>
      Se deja constancia de que el proceso de votación se llevó a cabo bajo estrictas medidas de control y auditoría, garantizando la integridad de los datos y la participación equitativa de toda la comunidad liceina. Este documento será firmado por las autoridades responsables para validar oficialmente los resultados.
    </p>

    <br><br><br>
    <div class="signature-section">
      <div class="signature-block">
        Firma del Responsable del Consejo
        <div class="signature-line"></div>
      </div><br><br><br><br><br><br>
      <div class="signature-block">
        Firma del Tribunal Electoral Estudiantil
        <div class="signature-line"></div>
      </div>
    </div>
  </div>
</body>
</html>
