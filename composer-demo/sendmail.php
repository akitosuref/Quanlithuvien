<?php
require __DIR__ . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$mail = new PHPMailer(true);
try {
    // Cấu hình SMTP Gmail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'vuongquangquy2511@gmail.com';
    $mail->Password = 'sfmq uufi wewo hpzy'; // App password ở bước 3
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    // Người gửi / Người nhận
    $mail->setFrom('vuongquangquy2511@gmail.com', 'Mailer Demo');
    $mail->addAddress('vuongquangquy2511@example.com', 'Người nhận');
    // Nội dung email
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PHPMailer';
    $mail->Body = '<b>Hello!</b> Đây là email thử nghiệm gửi bằng PHPMailer + Gmail.';
    $mail->AltBody = 'Hello! Đây là email thử nghiệm gửi bằng PHPMailer + Gmail.';
    $mail->send();
    echo "Gửi email thành công!";
} catch (Exception $e) {
    echo "Lỗi khi gửi: {$mail->ErrorInfo}";
}