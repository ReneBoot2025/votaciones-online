<?php
include 'db.php';

header('Content-Type: application/json');

$correo = isset($_POST['correo']) ? strtolower(trim($_POST['correo'])) : '';

if (!$correo || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Correo inválido']);
    exit;
}

$dominiosValidos = ['gmail.com', 'hotmail.com', 'outlook.com', 'yahoo.com', 'edu.ec'];
$dominio = explode('@', $correo)[1] ?? '';

$permitido = false;
foreach ($dominiosValidos as $dom) {
    if (substr($dominio, -strlen($dom)) === $dom) {
        $permitido = true;
        break;
    }
}

if (!$permitido) {
    echo json_encode(['success' => false, 'message' => 'Solo se permiten correos de Gmail, Outlook, Hotmail, Yahoo o dominios institucionales permitidos']);
    exit;
}

// Verificar si correo ya votó
$stmt = $conexion->prepare("SELECT id FROM votantes WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Este correo ya ha votado']);
} else {
    echo json_encode(['success' => true]);
}
$stmt->close();
