<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
//sleep(2);
require_once('generic/generic_functions.php');
require_once('generic/net_functions.php');
require_once("generic/{$net_code}_functions.php");

if (auth_control() !==1){
    exit();
}

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$user_type_klass = !empty($_GET['user_type_1']) ? $_GET['user_type_1'] : null;
$user_type_subscriber = !empty($_GET['user_type_2']) ? $_GET['user_type_2'] : null;
$user_type_survey = !empty($_GET['user_type_5']) ? $_GET['user_type_5'] : null;
$user_type_comment = !empty($_GET['user_type_6']) ? $_GET['user_type_6'] : null;
$user_type_repost = !empty($_GET['user_type_4']) ? $_GET['user_type_4'] : null;

$users_count = !empty($_GET['users_count']) ? (int)$_GET['users_count'] : null;

if (!$category_id || !$user_id || !$users_count || (!$user_type_klass && !$user_type_subscriber && !$user_type_survey && !$user_type_comment && !$user_type_repost)) {
    return;
}

//берем данные покупателя
$stmt = $connect->prepare("SELECT id,balance,koeff_{$net_code}_import_collection FROM users WHERE id = :user_id");
$stmt->execute(array('user_id' => $user_id));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = $result['id'];
$balance = $result['balance'];
$koeff_net_import_collection = $result['koeff_'.$net_code.'_import_collection'];

//если не найден
if (!$user_id) {
    return;
}


$user_import_cost = get_import_collection_request_cost_per_one_user([
        'KLASS' => $user_type_klass,
        'SUBSCRIBER' => $user_type_subscriber,
        'SURVEY' => $user_type_survey,
        'COMMENT' => $user_type_comment,
        'REPOST' => $user_type_repost,
    ]
    );

// сколько будет все это стоить
$cost = $user_import_cost * $koeff_net_import_collection * $users_count;

//хватит денег или нет
if ($balance < $cost) {
    //не хватает
    return;
}

//если денег хватает -->





//сколько всего можно импортировать пользователей данного типа и категории
$import_max = get_category_type_users_count_collections($category_id,$user_type_klass, $user_type_subscriber, $user_type_survey, $user_type_comment, $user_type_repost);







//если мы хотим больше, чем возможно импортировать
if ($users_count > $import_max) {
    //нехуй играться
    return;
}


//если пользователей хватает -->






/*
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
*/




// --> заносим импорт
    //берем те, что уже загруженны нами
    $stmt = $connect->prepare("
        SELECT ids, ids_not_invited
    FROM {$net_code}_collections_imports
        WHERE user_id = $user_id AND category_id = $category_id");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $has_record_in_net_collections_imports = $result['ids'] ? true : false;
    $old_ids = $result['ids'] ?: 0;
    $old_ids_not_invited = $result['ids_not_invited'] ?: '';

    // берем те id-шники, которые можем добавить в {$net_code}_collections_imports
    $sql = "SELECT
                id
            FROM
                {$net_code}_collections_$category_id
            WHERE id NOT IN(" . $old_ids . ") " . prepare_import_types_condition($user_type_klass, $user_type_subscriber, $user_type_survey, $user_type_comment, $user_type_repost);

    $sql .= " ORDER by RAND() LIMIT $users_count";

    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $added_ids = array();
    foreach($result as $data){
        $added_ids[] = $data['id'];
    }

// имеем
// $added_ids - новый массив из id пользователей, которых добавляем
// $old_ids_not_invited - строка набора id - шников, которых загрузили, но еще не пригласили
$new_ids = Kits_Converter::add_new_numbers_to_numbers(explode(',', $old_ids), $added_ids);

// добавляем их к непросмотренным нами
$new_ids_not_invited = Kits_Converter::add_new_numbers_to_numbers(explode(',', $old_ids_not_invited), $added_ids);

// смотрим - есть ли у клиента строка с записанными ранее пользователями из данной категории
if (!$has_record_in_net_collections_imports){
    //создаем
    $stmt = $connect->prepare("
    INSERT INTO {$net_code}_collections_imports
    (
        user_id,
        ids,
        ids_not_invited,
        category_id,
        created,
        modified
    )
    VALUES
    (
        $user_id,
        '" . implode(',', $new_ids) . "',
        '" . implode(',', $new_ids_not_invited) . "',
        $category_id,
        " . time() . ",
        " . time() . "
    )");

} else {
    //добавляем
    $stmt = $connect->prepare("
    UPDATE
        {$net_code}_collections_imports
    SET
        ids = '" . implode(',', $new_ids) . "',
        ids_not_invited = '" . implode(',', $new_ids_not_invited) . "',
        modified = " . time() . "
    WHERE user_id = $user_id AND category_id = $category_id");
}
$stmt->execute();
// <-- заносим импорт


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
'".json_encode(['category_id' => $category_id,'user_types' => import_needed_types_list($user_type_klass, $user_type_subscriber, $user_type_survey, $user_type_comment, $user_type_repost), 'import_max' => $import_max, 'users_count' => $users_count, 'cost' => $cost, 'balance' => $balance])."',
1,
" . time() . "
)");
$stmt->execute(array('user_id' => $user_id));


//сколько импортнули пользователей
echo $users_count;



