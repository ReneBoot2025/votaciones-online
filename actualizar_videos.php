<?php
include 'db.php';

// Consulta para obtener todos los elementos con video_url de Drive que contengan '/view'
$sql = "SELECT id, video_url FROM elementos WHERE video_url LIKE '%drive.google.com/file/d/%/view%'";

$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    $updated = 0;
    while ($row = $result->fetch_assoc()) {
        $id = (int)$row['id'];
        $video_url = $row['video_url'];

        // Obtener la parte antes de /view y agregar /preview
        $new_url = strstr($video_url, '/view', true) . '/preview';

        // Actualizar el registro si la URL cambió
        if ($new_url !== $video_url) {
            $update_sql = "UPDATE elementos SET video_url = ? WHERE id = ?";
            $stmt = $conexion->prepare($update_sql);
            $stmt->bind_param('si', $new_url, $id);
            if ($stmt->execute()) {
                $updated++;
                echo "Actualizado ID $id: $new_url<br>";
            } else {
                echo "Error actualizando ID $id: " . $conexion->error . "<br>";
            }
            $stmt->close();
        }
    }
    echo "<br>Total de videos actualizados: $updated";
} else {
    echo "No se encontraron videos de Drive con URL para actualizar.";
}

$conexion->close();
?>
