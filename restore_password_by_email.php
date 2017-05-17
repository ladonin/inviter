<?php
sleep(1);
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic' . MY_DS . 'phpmailer.php');
$inviter_table = 'users';


$email = !empty($_GET['email']) ? $_GET['email'] : null;


if (!$email) {
    exit;
}



$stmt = $connect->prepare("SELECT id,hash FROM $inviter_table WHERE email=:email");
$stmt->execute(array('email' => $email));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
//если ранее задавали, то обновляем
if (!$result) {
    echo 0;

} else {

    // пишем письмо с кодом
    $mailer = new PHPMailer;
    $mailer->CharSet = 'UTF-8';

        $subject = 'Восстановление доступа';

        $body = '
        <div style=""><a href="http://inviter.biz/restore_password/' . $result['id'] . '/' . md5($result['hash']) . '" style="">Сменить пароль</a> для своего акаунта</div>';
        $alt_body = '';

        $mailer->setFrom('info@inviter.biz', 'Inviter');
        $mailer->addAddress($email);// Add a recipient

        $mailer->isHTML(true);// Set email format to HTML
        $mailer->Subject = $subject;
        $mailer->Body = $body;
        $mailer->AltBody = $alt_body;

        if (!$mailer->send()) {
            echo 1;
        } else {
            echo 2;
        }

    }
