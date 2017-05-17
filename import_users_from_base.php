<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');

require_once('generic/functions.php');

if (auth_control() !==1){
    exit();
}

$category_id = !empty($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$user_type = !empty($_GET['user_type']) ? (int)$_GET['user_type'] : null;

$users_count = !empty($_GET['users_count']) ? (int)$_GET['users_count'] : null;

if (!$category_id || !$user_type || !$user_id || !$users_count) {
    return;
}

//берем данные покупателя
$stmt = $connect->prepare("SELECT id,balance FROM users WHERE id = :user_id");
$stmt->execute(array('user_id' => $user_id));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = $result['id'];
$balance = $result['balance'];

//если не найден
if (!$user_id) {
    return;
}


// сколько будет все это стоить
$cost = MY_USER_IMPORT_COST * $users_count;

//хватит денег или нет
if ($balance < $cost) {
    //не хватает
    return;
}

//если денег хватает -->





//сколько всего можно импортировать пользователей данного типа и категории
$import_max = ok_get_category_type_users_count($category_id,$user_type);







//если мы хотим больше, чем возможно импортировать
if ($users_count > $import_max) {
    //нехуй играться
    return;
}


//если пользователей хватает -->







// берем последний id импорта
$stmt = $connect->prepare("SELECT data, id FROM collections_imports WHERE user_id= " . $user_id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$import_data = !empty($result['data']) ? $result['data'] : '';
$import_data = json_decode($import_data, true);
$last_id = !empty($import_data[$category_id][$user_type]['last_id']) ? $import_data[$category_id][$user_type]['last_id'] : 0;


// Если строки нет, то создаем
if (empty($result['id'])) {
    $stmt = $connect->prepare("INSERT INTO collections_imports (user_id, created, modified) VALUES ('" . $user_id . "', '" . time() . "','" . time() . "')");
    $stmt->execute();
}


// заносим импорт
$stmt = $connect->prepare("
INSERT INTO ok_imports
(
    user_id,
    profile_id,
    user_fio,
    user_avatar,
    user_type,
    is_imported,
    category_id,
    is_invited,
    created,
    modified
)

SELECT

    :user_id as user_id,
    t2.profile_id,
    t2.user_fio,
    t2.user_avatar,
    " . $user_type . " as user_type,
    1 as is_imported,
    ". $category_id . " as category_id,
    '0' as is_invited,
    " . time() . " as created,
    " . time() . " as modified

FROM ok_collections t2

LEFT JOIN ok_imports t3 ON t2.profile_id = t3.profile_id AND t3.profile_id IS NULL AND t3.user_id=:user_id



    WHERE
        t2.category_id = ". $category_id . "
        AND t2.user_type = " . $user_type . "
        AND t2.id > " . $last_id . "
    LIMIT " . $users_count);

$stmt->execute(array('user_id' => $user_id));




// обновляем последний id импорта
$stmt = $connect->prepare("
SELECT
    id
FROM ok_collections t2
    WHERE
        t2.category_id = ". $category_id . "
        AND t2.user_type = " . $user_type . "
        AND t2.id > " . $last_id . "
    ORDER by id ASC
    LIMIT " . $users_count);
$stmt->execute();
while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $new_last_id = $result['id'];
}




$import_data[$category_id][$user_type]['last_id'] = $new_last_id;
$import_data_json = json_encode($import_data);
$stmt = $connect->prepare("UPDATE collections_imports SET data = :data, modified = " . time() . " WHERE user_id = " . $user_id);
$stmt->execute(array('data' => $import_data_json));


// снимаем деньги
$stmt = $connect->prepare("UPDATE users SET balance = (balance - :cost), modified = " . time() . " WHERE id = :user_id");
$stmt->execute(array('cost' => $cost, 'user_id' => $user_id));

//пишем лог о снятии денег
$stmt = $connect->prepare("INSERT INTO expenditures
(
    user_id,
    data,
    type,
    created
)
VALUES (
:user_id,
'".json_encode(['category_id' => $category_id,'user_type' => $user_type, 'previous_id' => $last_id, 'import_max' => $import_max, 'users_count' => $users_count, 'cost' => $cost, 'balance' => $balance])."',
1,
" . time() . "
)");
$stmt->execute(array('user_id' => $user_id));






//сколько импортнули пользователей
echo $users_count;