<?php
// Zet error reporting aan voor debugging (optioneel)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // not allowed
    exit("Method not allowed.");
}

// Sanitize input
function clean($data) {
    return htmlspecialchars(stripslashes(trim($data)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

$naam    = clean($_POST['naam'] ?? '');
$email   = clean($_POST['email'] ?? '');
$bericht = clean($_POST['bericht'] ?? '');

if (empty($naam) || empty($email) || empty($bericht)) {
    exit("Vul alstublieft alle verplichte velden in.");
}

// PHPMailer gebruiken
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // SMTP settings — pas aan met jouw gegevens
    $mail->isSMTP();
    $mail->Host       = 'smtp.office365.com';       // bijv. Outlook / Office365 SMTP-server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'peter_krijgsman@outlook.com'; // jouw e-mail
    $mail->Password   = 'JE_SMTP_WACHTWOORD';          // wachtwoord of app-wachtwoord
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // From / To
    $mail->setFrom('peter_krijgsman@outlook.com', 'Fieja B.V. Website');
    $mail->addAddress('peter_krijgsman@outlook.com');  // ontvangstadres

    // Inhoud
    $mail->Subject = 'Nieuw bericht via website Fieja';
    $body  = "Naam: {$naam}\n";
    $body .= "E-mail: {$email}\n\n";
    $body .= "Bericht:\n{$bericht}\n";
    $mail->Body = $body;

    $mail->send();

    // Redirect terug naar homepage of bevestiging tonen
    header("Location: index.html?status=success");
    exit;

} catch (Exception $e) {
    // Foutmelding
    echo "Er is iets misgegaan met verzenden: " . $mail->ErrorInfo;
    exit;
}
?>
