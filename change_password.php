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

$old_password = !empty($_POST['old_password']) ? $_POST['old_password'] : null;
$new_password = !empty($_POST['new_password']) ? $_POST['new_password'] : null;

$user_id = !empty($_COOKIE['du']) ? (int)$_COOKIE['du'] : null;

if ((auth_control()===1) && $old_password && $new_password && $user_id) {





//ищем по id пользователя
$stmt = $connect->prepare("SELECT * FROM users WHERE id=:user_id");
$stmt->execute(array('user_id' => $user_id));
$result = $stmt->fetch(PDO::FETCH_ASSOC);

//сравниваем хеш

if ($result && hash_equals_to_value($old_password, $result['hash'])) {
    //старый пароль верный
    //меняем
    $new_hash = hashing($new_password);
    $stmt = $connect->prepare("UPDATE users SET hash=:new_hash WHERE id=:user_id");
    $stmt->execute(array('user_id' => $user_id, 'new_hash' => $new_hash));
    save_user_hash_data_in_cookies($user_id, $new_hash);
    echo 1;
    exit();
}
}
    echo 0;
    exit();
