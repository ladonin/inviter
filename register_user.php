<?php
sleep(1);
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic' . MY_DS . 'phpmailer.php');
require_once('generic/generic_functions.php');
$email = !empty($_GET['email']) ? $_GET['email'] : null;
$password = !empty($_GET['password']) ? $_GET['password'] : null;

if (!$email || !$password) {
    exit;
}


// проверяем уникальность почты
$stmt = $connect->prepare("SELECT id FROM users WHERE email=:email");
$stmt->execute(array('email' => $email));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result) {
    //такая почта уже зарегистрирована
    echo 0;
    exit();
}


// --> почта уникальная
//провераяем нет ли её уже в качестве претендентов на регистрацию
$stmt = $connect->prepare("SELECT id FROM users_registration_temp WHERE email=:email");
$stmt->execute(array('email' => $email));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result) {
    //такая почта уже в ожидании подтверждения
    echo 1;
    exit();
}

// --> почта уникальная и еще ни в ожидании подтверждения
//генерим уникальную ссылку
$unique_code = md5(uniqid());
$stmt = $connect->prepare("INSERT into users_registration_temp (
                email,
                password,
                code,
                created
            ) VALUES(
                :email,
                :password,
                '".$unique_code."',
                '".time()."'
            )");
            $stmt->execute(array(
                'email' => $email,
                'password' => $password,
            ));

//шлем письмо
    $mailer = new PHPMailer;
    $mailer->CharSet = 'UTF-8';

        $subject = 'Регистрация';

        $body = '
        <h2>Добро пожаловать в сервис Inviter.biz.</h2>
        <div style="margin-bottom:10px;">
            Теперь собирать активных подписчиков станет намного проще!
        </div>
        <div style="margin-bottom:10px;">Ваш пароль: <b style="color:#000">' . $password . '</b></div>
        <div style=""><a href="' . MY_DOMEN . '/confirm_email/' . $unique_code . '" style="">закончить регистрацию и войти в личный кабинет</a></div>';

        //$alt_body = '';
        $mailer->setFrom('info@inviter.biz', 'Inviter');
        $mailer->addAddress($email);// Add a recipient

        $mailer->isHTML(true);// Set email format to HTML
        $mailer->Subject = $subject;
        $mailer->Body = $body;
        //$mailer->AltBody = $alt_body;

        if (!$mailer->send()) {
            echo 99;

            $stmt = $connect->prepare("INSERT into email_errors (
                email,
                password,
                created
            ) VALUES(
                :email,
                :password,
                '".time()."'
            )");
            $stmt->execute(array(
                'email' => $email,
                'password' => $password,
            ));

        } else {
            echo 2;
        }
