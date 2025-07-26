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
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Resultados de Votación</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
  <link rel="icon" href="image/logo.png" />
  
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('image/fondo.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 40px;
      min-height: 100vh;
    }
    .box {
      background: rgba(0, 128, 0, 0.6);
      padding: 30px;
      border-radius: 16px;
      text-align: center;
      box-shadow: 0 0 25px rgba(255, 255, 255, 0.2);
      width: 90%;
      max-width: 900px;
    }
    .banner {
      background-color: #FFD700;
      color: #000;
      padding: 15px;
      border-radius: 10px;
      font-weight: bold;
      font-size: 18px;
      margin-top: 20px;
    }
    img.logo {
      width: 600px;
      height: 150px;
      border-radius: 10px;
      margin-bottom: 10px;
    }

    /* Contenedor para las gráficas en fila horizontal */
    .graficos {
      display: flex;
      justify-content: center;
      gap: 40px;
      margin-top: 30px;
      flex-wrap: nowrap;
    }
    .grafico-container {
      flex: 1 1 0;
      max-width: 550px;
      max-height: 450px;
    }
    canvas {
      width: 100% !important;
      height: 400px !important;
    }

    .btn {
      margin: 10px;
      background-color: #FFD700;
      border: none;
      padding: 12px 20px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      font-weight: bold;
      color: #000;
      box-shadow: 0 0 10px #FFD700;
      transition: background-color 0.3s ease;
    }
    .btn:hover {
      background-color: #ffec5c;
    }
  </style>
</head>
<body>
  <div class="box">
    <img class="logo" src="image/UEPJXXIII.jpg" alt="Logo Institución" />
    <h2>📊 Resultados Finales de la Votación</h2>
    <div style="display: flex; justify-content: center; gap: 20px; font-size: 22px; margin-top: 10px;">
  <p><strong>Lista A:</strong> <?= $votos['A'] ?> votos</p>
  <p><strong>Lista B:</strong> <?= $votos['B'] ?> votos</p>
</div>


    <div class="graficos">
      <div class="grafico-container">
        <canvas id="graficoBarras"></canvas>
      </div>
      <div class="grafico-container">
        <canvas id="graficoPastel"></canvas>
      </div>
    </div>

    <div class="banner">
      📢 Por decisión democrática a través del sistema de votación online, se decreta como <strong>ganadora del proceso electoral estudiantil 2025-2026 a <?= $ganadora ?></strong>.
    </div>

    <button class="btn" onclick="descargarGrafico('graficoBarras', 'votacion_barras.png')">📥 Barras</button>
    <button class="btn" onclick="descargarGrafico('graficoPastel', 'votacion_pastel.png')">📥 Pastel</button>
    <button class="btn" onclick="window.location.href='descargar_word.php'">📄 Resultados</button>
   <button class="btn" onclick="window.location.href='login.php'">🏠 Inicio</button>
</div>

  <script>
    // Registrar el plugin de datalabels para usarlo en los gráficos
    Chart.register(ChartDataLabels);

    const votosA = <?= $votos['A'] ?>;
    const votosB = <?= $votos['B'] ?>;

    // Gráfico de barras
    const ctxBarras = document.getElementById('graficoBarras').getContext('2d');
    const graficoBarras = new Chart(ctxBarras, {
      type: 'bar',
      data: {
        labels: ['Lista A', 'Lista B'],
        datasets: [{
          label: 'Total de Votos',
          data: [votosA, votosB],
          backgroundColor: ['#00BFFF', '#FF69B4'],
          borderRadius: 10
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          title: { display: true, text: 'Votos (Barras)' },
          datalabels: {
            anchor: 'end',
            align: 'top',
            color: '#000',
            font: { weight: 'bold', size: 14 },
            formatter: function(value) {
              return value;
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { color: '#000' }
          },
          x: {
            ticks: { color: '#000' }
          }
        }
      },
      plugins: [ChartDataLabels]
    });

    // Gráfico de pastel
    const ctxPastel = document.getElementById('graficoPastel').getContext('2d');
    const graficoPastel = new Chart(ctxPastel, {
      type: 'pie',
      data: {
        labels: ['Lista A', 'Lista B'],
        datasets: [{
          label: 'Total de Votos',
          data: [votosA, votosB],
          backgroundColor: ['#00BFFF', '#FF69B4'],
          borderColor: '#fff',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'bottom', labels: { color: '#000' } },
          title: { display: true, text: 'Votos (Pastel)' },
          datalabels: {
            color: '#000',
            font: { weight: 'bold', size: 14 },
            formatter: function(value, context) {
              let label = context.chart.data.labels[context.dataIndex];
              return label + ': ' + value;
            }
          }
        }
      },
      plugins: [ChartDataLabels]
    });

    function descargarGrafico(idCanvas, filename) {
      const canvas = document.getElementById(idCanvas);
      const link = document.createElement('a');
      link.href = canvas.toDataURL('image/png');
      link.download = filename;
      link.click();
    }
  </script>
</body>
</html>
