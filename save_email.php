<?php
sleep(1);
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
$inviter_table = 'users';


$user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : null;
$email = !empty($_GET['email']) ? $_GET['email'] : null;

if (!$user_id) {
    return;
}


//если такой email уже есть
$stmt = $connect->prepare("SELECT id FROM $inviter_table WHERE email=:email and id != :user_id");
$stmt->execute(array('email' => $email, 'user_id' => $user_id));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result) {
    echo 0;
    exit();

}










$stmt = $connect->prepare("SELECT id FROM $inviter_table WHERE id=:user_id");
$stmt->execute(array('user_id' => $user_id));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
//если ранее задавали, то обновляем
if ($result) {

    $stmt = $connect->prepare("UPDATE $inviter_table set
                email = :email,
                modified = '".time()."'
                WHERE id='" . $result['id'] . "'
            ");
            $stmt->execute(array(
                'email' => $email
            ));

} else {
    // иначе вставляем
    $stmt = $connect->prepare("INSERT into $inviter_table (
                user_id,
                email,
                created,
                modified
            ) VALUES(
                :user_id,
                :email,
                '".time()."',
                '".time()."'
            )");
            $stmt->execute(array(
                'user_id' => $user_id,
                'email' => $email
            ));
    }



//require_once('save_email_in_cookies.php');




echo 1;