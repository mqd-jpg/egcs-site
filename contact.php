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

$to = 'info@egcomputersolutions.com'; // update to your real destination
$subject = 'Website contact — ' . mb_substr($name, 0, 64);
$body = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}\n";
$headers = "From: {$name} <{$email}>\r\n";
$headers .= "Reply-To: {$email}\r\n";

$mail_sent = false;
try {
    $mail_sent = @mail($to, $subject, $body, $headers);
} catch (Exception $e) {
    $mail_sent = false;
}

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

?>
