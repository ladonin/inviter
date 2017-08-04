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

$user_id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
$hash_md5 = !empty($_POST['hash']) ? $_POST['hash'] : null;
$password = !empty($_POST['password']) ? $_POST['password'] : null;

if (!$user_id || !$hash_md5 || !$password) {
    exit;
}



//ищем по id пользователя
$stmt = $connect->prepare("SELECT * FROM users WHERE id=:user_id");
$stmt->execute(array('user_id' => $user_id));
$result = $stmt->fetch(PDO::FETCH_ASSOC);

//сравниваем хеш
if (($result) && ($hash_md5 === md5($result['hash']))) {
    //меняем
    $new_hash = hashing($password);
    $stmt = $connect->prepare("UPDATE users SET hash=:new_hash WHERE id=:user_id");
    $stmt->execute(array('user_id' => $user_id, 'new_hash' => $new_hash));
    echo 1;
    include('generic/exit.php');
    exit();
}
    echo 0;
    exit();