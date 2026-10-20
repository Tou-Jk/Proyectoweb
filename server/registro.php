<?php
function responderJson(int $status, array $payload): void
{
    http_response_code($status);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    responderJson(405, ["success" => false, "message" => "Método no permitido."]);
}

$nombre   = isset($_POST['nombre'])   ? trim($_POST['nombre'])   : '';
$email    = isset($_POST['email'])    ? trim($_POST['email'])    : '';
$telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
$ciudad   = isset($_POST['ciudad'])   ? trim($_POST['ciudad'])   : '';
$mensaje  = isset($_POST['mensaje'])  ? trim($_POST['mensaje'])  : '';

if ($nombre === '' || $email === '' || $telefono === '' || $ciudad === '' || $mensaje === '') {
    responderJson(422, ["success" => false, "message" => "Por favor, completa todos los campos."]);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    responderJson(422, ["success" => false, "message" => "El correo electrónico ingresado no es válido."]);
}

$to = "lopezluis@tou.net.pe";
$subject = "Nuevo mensaje de contacto de: " . str_replace(["\r", "\n"], " ", $nombre);

$body = "Has recibido un nuevo mensaje desde la web:\n\n";
$body .= "Nombre: " . $nombre . "\n";
$body .= "Correo: " . $email . "\n";
$body .= "Teléfono: " . $telefono . "\n";
$body .= "Ciudad: " . $ciudad . "\n\n";
$body .= "Mensaje:\n" . $mensaje . "\n";

$headers = "From: lopezluis@tou.net.pe\r\n";
$headers .= "Reply-To: " . $email . "\r\n";

if (!mail($to, $subject, $body, $headers)) {
    responderJson(500, [
        "success" => false,
        "message" => "No se pudo enviar tu mensaje. Inténtalo de nuevo más tarde."
    ]);
}

responderJson(200, [
    "success" => true,
    "message" => "¡Gracias, " . $nombre . "! Tu mensaje fue enviado correctamente."
]);