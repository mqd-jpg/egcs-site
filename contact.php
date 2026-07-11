<?php
// Simple contact form handler for EG Computer Solutions
// - Validates and sanitizes input
// - Prevents basic header injection
// - Attempts to send mail via PHP mail()
// - Logs submissions to data/submissions.csv

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

$honeypot = trim($_POST['phone'] ?? '');
if ($honeypot !== '') {
    // likely bot; silently redirect as success to avoid feedback
    header('Location: index.html?sent=1');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.html?error=1');
    exit;
}

// Prevent header injection
if (preg_match('/[\r\n]/', $name) || preg_match('/[\r\n]/', $email)) {
    header('Location: index.html?error=1');
    exit;
}

require __DIR__ . '/vendor/autoload.php';

$brevoConfig = [
    'host' => 'smtp-relay.brevo.com',
    'port' => 587,
    'username' => 'b1a18e001@smtp-brevo.com', // your Brevo SMTP login
    'password' => 'YOUR_BREVO_SMTP_KEY',
    'from_email' => 'mqdescallar@gmail.com',
    'from_name' => 'EG Computer Solutions & Enterprises',
    'to_email' => 'egcomputers2014@gmail.com',
];

$subject = 'Website contact — ' . mb_substr($name, 0, 64);
// Flatten message newlines so the email body is exactly three lines as requested
$safeMessage = preg_replace('/\R+/', ' ', $message);
$body = "Name: {$name}\r\nEmail: {$email}\r\nMessage: {$safeMessage}\r\n";

$mail_sent = send_brevo_mail($brevoConfig, $subject, $body, $name, $email);

// Log submission to CSV (date, name, email, message, status)
$logDir = __DIR__ . '/data';
if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}
$csvFile = $logDir . '/submissions.csv';
$csvLine = [date('c'), $name, $email, str_replace(["\r", "\n"], [' ', ' '], $message), $mail_sent ? 'sent' : 'failed'];
if ($fp = @fopen($csvFile, 'a')) {
    @fputcsv($fp, $csvLine);
    @fclose($fp);
}

if ($mail_sent) {
    header('Location: index.html?sent=1');
} else {
    header('Location: index.html?error=1');
}
exit;

function send_brevo_mail(array $config, string $subject, string $body, string $senderName, string $senderEmail): bool
{
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $config['port'];
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($config['to_email']);
        $mail->addReplyTo($senderEmail, $senderName);
        $mail->Subject = $subject;
        // Build both plain-text and HTML versions so email clients render line breaks correctly.
        $plainBody = $body;
        $htmlBody = nl2br(htmlspecialchars($plainBody));
        $mail->isHTML(true);
        $mail->Body = $htmlBody;
        $mail->AltBody = $plainBody;
        $mail->send();
        return true;
    } catch (\PHPMailer\PHPMailer\Exception $e) {
        return false;
    }
}

