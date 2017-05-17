<?php
sleep(1);
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic/functions.php');
$email = !empty($_POST['email']) ? $_POST['email'] : null;
$password = !empty($_POST['password']) ? $_POST['password'] : null;

if (!$email || !$password) {
    echo 0;
    exit;
}

//проверяем есть ли такой пользователь
$stmt = $connect->prepare("SELECT * FROM users WHERE email=:email");
$stmt->execute(array('email' => $email));
$result = $stmt->fetch(PDO::FETCH_ASSOC);


if ($result && (hash_equals_to_value($password, $result['hash']))) {
   //пишем в куки хеш
    save_user_hash_data_in_cookies($result['id'], $result['hash']);
    echo 1;
    exit;
} else {
    echo 0;
    exit;
}