<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $to = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $name = htmlspecialchars($_POST["name"]);
    $subject = htmlspecialchars($_POST["subject"]);
    $message = htmlspecialchars($_POST["message"]);

    $fullMessage = "Hello $name,\n\n$message\n\n— Ink Soul";
    $headers = "From: Ink Soul <clgoerk@rogers.com>\r\n";
    $headers .= "Reply-To: Ink Soul <clgoerk@rogers.com>\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    $sent = mail($to, $subject, $fullMessage, $headers);

    if ($sent) {
        // only redirect if the mail was actually sent
        header("Location: reply_success.php?sent=1");
        exit;
    } else {
        echo "<h2 style='text-align:center;'>❌ Failed to send reply.</h2>";
        echo "<p style='text-align:center;'>This server may not support PHP mail(). Try using PHPMailer with SMTP or check server mail config.</p>";
        echo "<p style='text-align:center;'><a href='admin_dashboard.php'>← Return to Dashboard</a></p>";
    }
} else {
    header("Location: admin_dashboard.php");
    exit;
}