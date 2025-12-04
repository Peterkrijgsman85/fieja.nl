<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Formulier data veilig ophalen
    $name    = strip_tags(trim($_POST['name'] ?? ''));
    $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $company = strip_tags(trim($_POST['company'] ?? ''));
    $message = trim($_POST['message'] ?? '');

    // Basis validatie
    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400);
        echo "Vul alstublieft alle verplichte velden in.";
        exit;
    }

    // Ontvanger e-mailadres
    $to = "jouw-email@voorbeeld.nl"; // PAS AAN naar je eigen mailadres

    // Onderwerp
    $subject = "Nieuw contactformulier bericht van $name";

    // Berichtinhoud
    $email_content = "Naam: $name\n";
    $email_content .= "E-mail: $email\n";
    $email_content .= "Bedrijf: $company\n";
    $email_content .= "Bericht:\n$message\n";

    // Headers
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Mail versturen
    if (mail($to, $subject, $email_content, $headers)) {
        http_response_code(200);
        echo "Dank! Je bericht is verzonden.";
    } else {
        http_response_code(500);
        echo "Er is iets misgegaan bij het verzenden van je bericht.";
    }

} else {
    http_response_code(403);
    echo "Ongeldig verzoek.";
}
