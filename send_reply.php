<?php
require './PHPMailer/PHPMailerAutoload.php';
require("database.php");

function valid_email($email) {
  return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function send_email($to_address, $to_name, $from_address, $from_name, $subject, $body, $is_body_html = false) {
  if (!valid_email($to_address)) {
    throw new Exception('Invalid To address: ' . htmlspecialchars($to_address));
  }

  if (!valid_email($from_address)) {
    throw new Exception('Invalid From address: ' . htmlspecialchars($from_address));
  }

  $mail = new PHPMailer();
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPSecure = 'tls';
  $mail->Port = 587;
  $mail->SMTPAuth = true;

  $mail->Username = 'inksoultattooing@gmail.com';
  $mail->Password = 'fnqv damh weyn xdyy';

  $mail->setFrom($from_address, $from_name);
  $mail->addAddress($to_address, $to_name);
  $mail->Subject = $subject;
  $mail->Body = $body;
  $mail->AltBody = strip_tags($body);

  if ($is_body_html) {
    $mail->isHTML(true);
  }

  if (!$mail->send()) {
    throw new Exception('Error sending email: ' . htmlspecialchars($mail->ErrorInfo));
  }
}

try {
  send_email(
    $_POST['email'],
    $_POST['name'],
    'inksoultattooing@gmail.com',
    'Ink Soul Admin',
    $_POST['subject'],
    $_POST['message'],
    false
  );

  //  Mark that specific message as replied
  if (isset($_POST['contact_id'])) {
    $stmt = $pdo->prepare("UPDATE contact SET status = 'replied' WHERE id = ?");
    $stmt->execute([$_POST['contact_id']]);
  }

  header("Location: reply_success.php");
  exit;

} catch (Exception $e) {
  echo '<p style="color:red;">Email failed: ' . $e->getMessage() . '</p>';
}