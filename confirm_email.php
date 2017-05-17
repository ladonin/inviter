<?php
sleep(1);
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic' . MY_DS . 'phpmailer.php');
require_once('generic/functions.php');

$code = !empty($_GET['code']) ? $_GET['code'] : null;

if (!$code) {
    exit;
}

//проверяем, что код есть в базе
$stmt = $connect->prepare("SELECT * FROM users_registration_temp WHERE code=:code");
$stmt->execute(array('code' => $code));
$result_user_data = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$result_user_data) {
    //код левый или удален, если прошли по ссылке повторно
    header("Location: /");
    exit();
}

// --> код есть в базе


//удаляем его оттуда
$stmt = $connect->prepare("DELETE FROM users_registration_temp WHERE code=:code");
$stmt->execute(array('code' => $code));

//заносим пользователя в базу пользователей
$stmt = $connect->prepare("INSERT into users (
                hash,
                email,
                created,
                modified
            ) VALUES(
                '".hashing($result_user_data['password'])."',
                '".$result_user_data['email']."',
                '".time()."',
                '".time()."'
            )");
            $stmt->execute();
    header("Location: /enter");
    exit();