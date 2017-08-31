<?php

$load_type = (int) @$_REQUEST['load_type'] ? : 1;
$reset = (int) @$_REQUEST['reset'] ? 1 : 0;
//my_pre($_REQUEST);

$user_type_klass = isset($_REQUEST['type_users_1']) ? true : null;
$user_type_repost = isset($_REQUEST['type_users_4']) ? true : null;
$user_type_survey = isset($_REQUEST['type_users_5']) ? true : null;
$user_type_comment = isset($_REQUEST['type_users_6']) ? true : null;
$user_type_subscriber = isset($_REQUEST['type_users_2']) ? true : null;
$user_type_search = isset($_REQUEST['type_users_3']) ? true : null;
//$user_type_all = isset($_GET['user_type_all']) ? true : false;



$user_fio = $_REQUEST['user_fio'] ?? '';







$imported_category = (int) @$_REQUEST['imported_category'];

$user_status_showed = (int) @$_REQUEST['user_status_showed'] ? : 3;
$sort_type = (int) @$_REQUEST['sort_type'] ? : 1;

$sort_condition = '';
if ($sort_type == 1) {
    $sort_condition .= 'ORDER by id DESC ';
} elseif ($sort_type == 2) {
    $sort_condition .= 'ORDER by id ASC ';
} elseif ($sort_type == 3) {
    $sort_condition .= 'ORDER by user_fio ASC ';
} elseif ($sort_type == 4) {
    $sort_condition .= 'ORDER by user_fio DESC ';
}

if ($reset) {
 unset($_SESSION['infinity_scroll']['my_users_list']['viewed_users']);
}

$ids_not_invited_array = array();
$from_collection_status = 0;
$is_loaded_status = 0;

$load_type_status_name = $load_type == 1 ? 'self_loaded' : 'from_collection';

$limit = $limit ?? 10;



$count = 0;
//разделяем логику по типам загрузки пользователей
// если загруженные мной
if ($load_type == 1) {
    $is_loaded_status = 1;

    $user_type_condition = prepare_import_types_condition(
            $user_type_klass, $user_type_subscriber, $user_type_survey, $user_type_comment, $user_type_repost, $user_type_search);





    if ($user_status_showed == 1) {
        // все и не неприглашенные
        //        $showed_condition = "AND id IN($ids) AND id NOT IN($ids_not_invited)";
        $showed_condition = "AND is_invited=1";
    } else if ($user_status_showed == 2) {
        // только неприглашенные
        $showed_condition = "AND is_invited=0";
    } else if ($user_status_showed == 3) {
        // все
        $showed_condition = "";
    }

if (!empty($_SESSION['infinity_scroll']['my_users_list']['viewed_users']['self_loaded']) && empty($load_to_file_status)) {
    $viewed_condition = implode(',', $_SESSION['infinity_scroll']['my_users_list']['viewed_users']['self_loaded']);
} else {
    $viewed_condition = 0;
}


    $stmt = $connect->prepare("SELECT count(*) as count FROM {$net_code}_imports  WHERE user_id=:user_id AND user_fio LIKE(:user_fio) $user_type_condition $showed_condition");
    $stmt->execute(
            array(
                'user_id' => $user_id,
                'user_fio' => '%'.$user_fio.'%'
    ));
    $result = $stmt->fetchColumn();

    $count = $result;


    $stmt = $connect->prepare("SELECT * FROM {$net_code}_imports  WHERE user_id=:user_id AND user_fio LIKE(:user_fio) AND  id NOT IN($viewed_condition) $user_type_condition $showed_condition $sort_condition LIMIT $limit");
    $stmt->execute(
            array(
                'user_id' => $user_id,
                'user_fio' => '%'.$user_fio.'%'
    ));
    $result = $stmt->fetchAll();
    /////////////////////////////////////////////////////////
    foreach ($result as $user) {
        if (!$user['is_invited']) {
            $ids_not_invited_array[$user['id']] = $user['id'];
        }
    }
} elseif (($load_type == 2) && $imported_category) {
    $from_collection_status = 1;

    $user_type_condition = prepare_import_types_condition(
            $user_type_klass, $user_type_subscriber, $user_type_survey, $user_type_comment, $user_type_repost);

    $ids_not_invited = 0;
    $ids = 0;


    $sql = "SELECT ids FROM {$net_code}_collections_imports WHERE category_id = $imported_category AND user_id = $user_id";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $ids = $result['ids'] ? : 0;

    $sql = "SELECT ids_not_invited FROM {$net_code}_collections_imports WHERE category_id = $imported_category AND user_id = $user_id";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $ids_not_invited = $result['ids_not_invited'] ? : 0;
    $ids_not_invited_array = explode(',', $ids_not_invited);

    if ($user_status_showed == 1) {

        $array_diff = array_diff(explode(',', $ids), $ids_not_invited_array);
        // все и не неприглашенные
        //        $showed_condition = "AND id IN($ids) AND id NOT IN($ids_not_invited)";
        $showed_condition = "AND id IN(" . implode(',', $array_diff) . ")";
    } else if ($user_status_showed == 2) {
        // только неприглашенные
        $showed_condition = "AND id IN($ids_not_invited)";
    } else if ($user_status_showed == 3) {
        // все
        $showed_condition = "AND id IN($ids)";
    }

$sql_main_condition = "WHERE 1 $user_type_condition $showed_condition AND user_fio LIKE(:user_fio)";



    $sql = "SELECT count(*) as count FROM {$net_code}_collections_$imported_category $sql_main_condition";
    $stmt = $connect->prepare($sql);
    $stmt->execute(
            array(
                'user_fio' => '%'.$user_fio.'%'
        ));
    $count = $stmt->fetchColumn();


if (!empty($_SESSION['infinity_scroll']['my_users_list']['viewed_users']['from_collection']) && empty($load_to_file_status)) {
    $viewed_condition = implode(',', $_SESSION['infinity_scroll']['my_users_list']['viewed_users']['from_collection']);
} else {
    $viewed_condition = 0;
}
    $sql = "SELECT * FROM {$net_code}_collections_$imported_category $sql_main_condition  AND id NOT IN($viewed_condition) $sort_condition limit $limit";

    $stmt = $connect->prepare($sql);
    $stmt->execute(
            array(
                'user_fio' => '%'.$user_fio.'%'
        ));
    $result = $stmt->fetchAll();

    $show_imported_categories = $imported_category;
} else {
    exit();
}
