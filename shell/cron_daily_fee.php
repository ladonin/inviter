<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);



require_once(realpath(dirname(__FILE__)) . MY_DS . '../generic' . MY_DS . 'constants.php');
require_once(realpath(dirname(__FILE__)) . MY_DS . '../generic' . MY_DS . 'connection.php');


//каждый день снимаем со всех польховаталей деньги в размере дневного тарифа



// собираем данные всех пользователей
$stmt = $connect->prepare("SELECT id, balance FROM users");
$stmt->execute();
$result = $stmt->fetchAll();

//пишем данные о пользователях в массив и снимаем деньги тут же
$data = array();

//для каждого пользователя
foreach($result as $user) {

    // --> снимаем деньги
    //если баланс до снятия не был нулевым
    if ($user['balance'] > 0) {

        $data[$user['id']] = array(
            'balance' => $user['balance'],
            'fee' => MY_USER_DAILY_FEE_COST,
        );

        $resulted_balance = $user['balance'] - MY_USER_DAILY_FEE_COST;

        //если баланс обнулился
        if ($resulted_balance <= 0) {
            $resulted_balance = 0;

            // --> тут можно сообщить пользователю, что у него закончились деньги на счету <--
        }

        // обновляем текущий баланс пользователя
        $stmt = $connect->prepare("
            UPDATE users SET balance = " . $resulted_balance . " WHERE id = " . $user['id']);
        $stmt->execute();
    }
    // <--
};



//пишем лог о снятии денег текущих пользователей
$stmt = $connect->prepare("INSERT INTO cron_balance_withdrawals
(
    data,
    type,
    date,
    created
)
VALUES (
'".json_encode($data)."',
1,
'".date("d.m.Y")."',
" . time() . "
)");
$stmt->execute();







echo(json_encode($data));
















