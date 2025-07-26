<?php
include 'db.php';
session_start();

header('Content-Type: application/json');
$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['correo'], $_POST['id'])) {
    $correo = $_SESSION['correo'];
    $id_elemento = (int) $_POST['id'];

    // Verificar si ya votó este correo para este elemento
    $stmt = $conexion->prepare("SELECT id FROM votantes WHERE correo = ? AND id_elemento = ?");
    $stmt->bind_param("si", $correo, $id_elemento);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();

        // Insertar voto
        $stmt = $conexion->prepare("INSERT INTO votantes (correo, id_elemento) VALUES (?, ?)");
        $stmt->bind_param("si", $correo, $id_elemento);

        if ($stmt->execute()) {
            // Actualizar votos en tabla elementos
            $update = $conexion->prepare("UPDATE elementos SET votos = votos + 1 WHERE id = ?");
            $update->bind_param("i", $id_elemento);
            $update->execute();
            $update->close();

            $response['success'] = true;
        } else {
            $response['error'] = "Su correo ya fue registrado";
        }
        $stmt->close();
    } else {
        $response['error'] = "Usted ya votó por esta lista";
    }
} else {
    $response['error'] = "Datos incompletos o sesión no iniciada.";
}

echo json_encode($response);
