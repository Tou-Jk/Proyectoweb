<?php
// Validar que la solicitud venga mediante método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Recepción y limpieza de variables
    $nombre   = isset($_POST['nombre'])   ? trim($_POST['nombre'])   : '';
    $email    = isset($_POST['email'])    ? trim($_POST['email'])    : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    $ciudad   = isset($_POST['ciudad'])   ? trim($_POST['ciudad'])   : '';
    $mensaje  = isset($_POST['mensaje'])  ? trim($_POST['mensaje'])  : '';

    // 2. Validar que ningún campo esté vacío
    if (empty($nombre) || empty($email) || empty($telefono) || empty($ciudad) || empty($mensaje)) {
        die("Por favor, completa todos los campos del formulario.");
    }

    // 3. Validar formato de correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("El correo electrónico ingresado no es válido.");
    }
    
    $to = "lopezluis@tou.net.pe"; 
    $subject = "Nuevo mensaje de contacto de: " . $nombre;
    
    $body = "Has recibido un nuevo mensaje desde la web:\n\n";
    $body .= "Nombre: " . $nombre . "\n";
    $body .= "Correo: " . $email . "\n";
    $body .= "Teléfono: " . $telefono . "\n";
    $body .= "Ciudad: " . $ciudad . "\n\n";
    $body .= "Mensaje:\n" . $mensaje . "\n";

    $headers = "From: webmaster@proyelectric.com\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";

    mail($to, $subject, $body, $headers);

    echo "<h1>¡Gracias, " . htmlspecialchars($nombre) . "!</h1>";
    echo "<p>Tu mensaje ha sido enviado correctamente. Nos pondremos en contacto contigo pronto.</p>";
    echo "<a href='../index.html'>Volver al inicio</a>";

} else {
    // Si intentan entrar directo a registro.php desde el navegador
    header("Location: ../index.html");
    exit();
}
?>