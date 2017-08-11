<?php
session_start();
$net_code = '';
$net_profile_url = '';

$types_fields = array(
    'is_comment' => 6,
    'is_survey' => 5,
    'is_subscriber' => 2,
    'is_klass' => 1,
    'is_search' => 3
);
$types_fields_inv = array(
    6 => 'is_comment',
    5 => 'is_survey',
    2 => 'is_subscriber',
    1 => 'is_klass',
    3 => 'is_search'
);


function prepare_net_code()
{
    global $net_code;
    $net_code = $_GET['net_code'];

    if (!$net_code || (($net_code !== NET_CODE_OK) && ($net_code !== NET_CODE_FB) && ($net_code !== NET_CODE_VK))) {
        echo('net_code is undefined');
        exit();
    }
}


function prepare_net_profile_url()
{

    global $net_profile_url;
    global $net_code;
    $net_profile_url = constant('PROFILE_URL_' . strtoupper($net_code));

    if (!$net_profile_url) {
        echo('net_profile_url is undefined');
        exit();
    }
}


function get_net_title()
{
    global $net_code;

    $net_title = constant('NET_TITLE_' . strtoupper($net_code));
    if (!$net_title) {
        echo('net_title is undefined');
        exit();
    }

    return mb_strtoupper($net_title);
}


function get_net_header_background_color()
{
    global $net_code;

    $net_header_background_color = constant('NET_HEADER_BACKGROUND_COLOR_' . strtoupper($net_code));
    if (!$net_header_background_color) {
        echo('net_header_background_color is undefined');
        exit();
    }

    return $net_header_background_color;
}


function get_type_name($user_type, $eng = false)
{
    if ($eng) {
        if ($user_type == 1) {
            return 'like!';
        } elseif ($user_type == 2) {
            return 'subscriber';
        } elseif ($user_type == 3) {
            return 'from search';
        } elseif ($user_type == 5) {
            return 'surveys';
        } elseif ($user_type == 6) {
            return 'comments';
        }
    }

    if ($user_type == 1) {
        return 'понравился пост';
    } elseif ($user_type == 2) {
        return 'участник группы';
    } elseif ($user_type == 3) {
        return 'из результатов поиска';
    } elseif ($user_type == 5) {
        return 'опросы';
    } elseif ($user_type == 6) {
        return 'комментарии';
    }
}


function has_loaded_users()
{
    global $net_code;
    global $connect;
    global $user_id;
    $stmt = $connect->prepare("SELECT count(*) as count FROM {$net_code}_imports WHERE user_id=$user_id");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count ? true : false;
}


function has_imported_users()
{
    return get_client_imported_categories() ? true : false;
}


function report_init()
{
    global $net_code;
    global $connect;
    global $user_id;
    global $net_profile_url;

// Экспорт в csv всех пользователей этого логина
    if (isset($_POST['export_users'])) {

        if ($_POST['load_type'] == 1) {
            # Для загруженного мной
            $stmt = $connect->prepare("SELECT * FROM {$net_code}_imports WHERE user_id=$user_id order by id DESC");
            $stmt->execute();
            $result = $stmt->fetchAll();
            $file_name = "reports/{$net_code}_self_loaded_" . uniqid() . $user_id . '.csv';
            $fp = fopen($file_name, 'w');

            if ($_POST['report_type'] == 1) {
                // Excel
                fputcsv($fp, array(
                    'Date upload',
                    'Url',
                    'Is shown',
                    'Date show'), ';');

                foreach ($result as $key => $row) {
                    fputcsv($fp, array(
                        date("H:i:s d.m.Y", $row['created']),
                        $net_profile_url . $row['profile_id'],
                        $row['is_invited'] ? '+' : ' ',
                        !$row['is_invited'] ? ' ' : ($row['modified'] ? date("H:i:s d.m.Y", $row['modified']) : '')
                            ), ';'
                    );
                }
            } else if ($_POST['report_type'] == 2) {
                // CSV
                fputcsv($fp, array(
                    'Дата сохранения',
                    'Ссылка на профиль',
                    'ФИО',
                    'Показан?',
                    'Дата показа'), ';');

                foreach ($result as $key => $row) {
                    fputcsv($fp, array(
                        date("H:i:s d.m.Y", $row['created']),
                        $net_profile_url . $row['profile_id'],
                        $row['user_fio'],
                        $row['is_invited'] ? 'Да' : ' ',
                        !$row['is_invited'] ? ' ' : ($row['modified'] ? date("H:i:s d.m.Y", $row['modified']) : '')
                            ), ';'
                    );
                }
            }
            fclose($fp);
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=" . $file_name);
            header("Content-Length: " . filesize($file_name));
            readfile($file_name);
            exit();
        } else if (($_POST['load_type'] == 2) && ($category = (int) $_POST['category'])) {
            # Для импортированного из коллекции

            $stmt = $connect->prepare("SELECT ids,ids_not_invited FROM {$net_code}_collections_imports WHERE user_id=$user_id AND category_id=$category");
            $stmt->execute();
            $result = $stmt->fetch();

            $ids_not_invited_array = explode(',', $result['ids_not_invited']);
            $ids_condition = Kits_Converter::convert_to_intenvals($result['ids'], '', false);
            $ids_condition = $ids_condition['sql_condition'];


            $stmt = $connect->prepare("SELECT * FROM {$net_code}_collections_$category WHERE $ids_condition order by id DESC LIMIT " . MY_REPORT_USERS_COLLECTION_LIMIT);
            $stmt->execute();
            $result = $stmt->fetchAll();
            $file_name = "reports/{$net_code}_imported_" . uniqid() . $user_id . '.csv';
            $fp = fopen($file_name, 'w');

            if ($_POST['report_type'] == 1) {
                // Excel
                fputcsv($fp, array(
                    'Date import',
                    'Url',
                    'Is shown'), ';');
                foreach ($result as $key => $row) {
                    $is_invited = !in_array($row['id'], $ids_not_invited_array);
                    fputcsv($fp, array(
                        date("H:i:s d.m.Y", $row['created']),
                        $net_profile_url . $row['profile_id'],
                        $is_invited ? '+' : ' '), ';'
                    );
                }
            } else if ($_POST['report_type'] == 2) {
                // CSV
                fputcsv($fp, array(
                    'Дата импорта',
                    'Ссылка на профиль',
                    'ФИО',
                    'Показан?'), ';');
                foreach ($result as $key => $row) {
                    $is_invited = !in_array($row['id'], $ids_not_invited_array);
                    fputcsv($fp, array(
                        date("H:i:s d.m.Y", $row['created']),
                        $net_profile_url . $row['profile_id'],
                        $row['user_fio'],
                        $is_invited ? 'Да' : ' '), ';'
                    );
                }
            }
            fclose($fp);
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=" . $file_name);
            header("Content-Length: " . filesize($file_name));
            readfile($file_name);
            exit();
        }
    }
}


function get_category_name($category)
{
    global $net_code;
    $stmt = $connect->prepare("SELECT name FROM {$net_code}_collections_categories WHERE category_id=$category");
    $stmt->execute();
    return $stmt->fetchColumn();
}


function get_not_invited_count()
{
    global $net_code;
    global $connect;
    global $user_id;

    // для импортнутых из html
    $stmt = $connect->prepare("SELECT count(*) as count FROM {$net_code}_imports where user_id=$user_id and is_invited = 0");
    $stmt->execute(array('user_id' => $user_id));
    $count_non_invited_loaded = $stmt->fetchColumn();


    // для импортнутых из коллекции
    $count_non_invited_from_collection = 0;
    $stmt = $connect->prepare("SELECT id from {$net_code}_collections_categories");
    $stmt->execute();
    $all_categories = $stmt->fetchAll();

    $all_categories_list = '';
    foreach ($all_categories as $category) {
        $all_categories_list .= $category['id'] . ',';
    }
    $all_categories_list = trim($all_categories_list, ',');

    $stmt = $connect->prepare("SELECT * from {$net_code}_collections_imports where user_id = $user_id and category_id IN ($all_categories_list)");
    $stmt->execute();
    $all_categories = $stmt->fetchAll();

    foreach ($all_categories as $category) {
        if ($category['ids_not_invited']) {
            $count_non_invited_from_collection += count(explode(',', $category['ids_not_invited']));
        }
    }
    return $count_non_invited_loaded + $count_non_invited_from_collection;
}


//берем доступные типы загрузки пользователей
function get_types_loads_users($styled = true)
{
    global $user_id;
    global $connect;
    global $net_code;

    $return = array();

    $stmt = $connect->prepare("SELECT count(*) as count FROM {$net_code}_imports WHERE user_id=:user_id AND is_invited=0");
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($styled) {
        $return[1] = ($result['count'] > 0) ? '' : 'disabled'; // load
    } else {
        if ($result['count'] > 0) {
            $return[] = 1; // load
        }
    }

    $stmt = $connect->prepare("SELECT count(*) as count FROM {$net_code}_collections_imports WHERE user_id=:user_id AND ids_not_invited!=''");
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($styled) {
        $return[2] = ($result['count'] > 0) ? '' : 'disabled'; // import
    } else {
        if ($result['count'] > 0) {
            $return[] = 2; // import
        }
    }

    return $return;
}


function get_category_type_users_count_collections($category_id, $user_type_klass, $user_type_subscriber, $user_type_survey, $user_type_comment)
{

    global $net_code;
    global $types_fields_inv;
    global $user_id;
    global $connect;

    $category_id = (int) $category_id;


    $stmt = $connect->prepare("
            SELECT ids_condition
        FROM {$net_code}_collections_imports
            WHERE user_id = $user_id AND category_id = $category_id");

    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);



    $sql = "SELECT
                COUNT(0) as count
            FROM
                {$net_code}_collections_$category_id
            WHERE 1 " . ($result['ids_condition'] ? (" AND " . $result['ids_condition']) : '') . " " . prepare_import_types_condition($user_type_klass, $user_type_subscriber, $user_type_survey, $user_type_comment);

    //echo($sql);
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['count'];
}


function get_load_type_name_by_id($id)
{

    if ($id == 1) {
        return 'Загруженные мной';
    } elseif ($id == 2) {
        return 'Из коллекции';
    }
}


//берем доступные типы пользователей, которые клиент сам импортировал
function get_client_self_load_enabled_users_types()
{
    global $user_id;

    $return = get_available_loaded_types_from_base($user_id, $is_invited = 0);

    return $return ? : array();
}


/////////////////////////////////// get_available_types_from_base
// доступные типы загруженных через html пользователей
function get_available_loaded_types_from_base($table, $user_id, $is_invited = 0)
{
    global $connect;
    global $types_fields;
    global $net_code;
    global $user_id;

    $return = array();

    foreach ($types_fields as $type_field => $number_type) {
        $stmt = $connect->prepare("SELECT count(*) as count FROM {$net_code}_imports WHERE user_id=:user_id AND is_invited=$is_invited AND $type_field = 1");
        $stmt->execute(array('user_id' => $user_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result['count'] > 0) {
            $return[] = $number_type;
        }
    }
    return $return ? : array();
}


function get_type_name_by_id($user_type)
{
    global $net_code;

    if ($user_type == 1) {

        if ($net_code === NET_CODE_OK) {
            return "Поставили 'Класс!'";
        } elseif ($net_code === NET_CODE_VK) {
            return "Поставили 'Мне нравится'";
        }

        return "Поставили 'Like'";
    } elseif ($user_type == 2) {
        return 'Подписчики';
    } elseif ($user_type == 3) {
        return 'Из результата поиска';
    } elseif ($user_type == 5) {
        return 'Учавствуют в опросах';
    } elseif ($user_type == 6) {
        return 'Оставляют комментарии';
    }
}


//берем доступные импортированные категории пользователей
function get_client_imported_enabled_categories()
{
    global $user_id;
    global $connect;
    global $net_code;

    // берем все категории клиента, где еще есть непросмотренные пользователи

    $stmt = $connect->prepare("
        SELECT
            {$net_code}_collections_imports.category_id,
            {$net_code}_collections_categories.name
        FROM {$net_code}_collections_imports
        LEFT JOIN
            {$net_code}_collections_categories ON {$net_code}_collections_categories.category_id={$net_code}_collections_imports.category_id
        WHERE
            {$net_code}_collections_imports.user_id=:user_id AND {$net_code}_collections_imports.ids_not_invited!=''");

    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result ? : array();
}


function get_client_imported_categories()
{

    global $user_id;
    global $connect;
    global $net_code;

    // берем все категории клиента

    $stmt = $connect->prepare("
        SELECT
            {$net_code}_collections_imports.category_id,
            {$net_code}_collections_categories.name
        FROM {$net_code}_collections_imports
        LEFT JOIN
            {$net_code}_collections_categories ON {$net_code}_collections_categories.category_id={$net_code}_collections_imports.category_id
        WHERE
            {$net_code}_collections_imports.user_id=:user_id");

    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result ? : array();
}


function show_users_get_loaded_users()
{
    global $types_fields_inv;
    global $user_id;
    global $connect;
    global $show_self_load_users_types;
    global $show_users_number;
    global $net_code;

    // берем все записи из таблицы
    $condition = "WHERE is_invited=0 AND user_id=:user_id ";
    if ($show_self_load_users_types > 0) {
        $condition .= " AND {$types_fields_inv[$show_self_load_users_types]}=1";
    }

    $sql = "SELECT * FROM {$net_code}_imports " . $condition;
    $stmt = $connect->prepare($sql . " order by id ASC limit " . $show_users_number);
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetchAll();

    // ищем сколько осталось по данному критерию
    $sql = "SELECT count(*) FROM {$net_code}_imports $condition";
    $stmt = $connect->prepare($sql);
    $stmt->execute(array('user_id' => $user_id));
    $count = $stmt->fetchColumn() - $show_users_number;
    $count = $count > 0 ? $count : 0;





    //помечаем их как просмотренные
    $list = '';
    foreach ($result as $user) {
        $list .= "'".$user['profile_id'] . "',";
    }
    $list = trim($list, ',');
    $stmt = $connect->prepare("update {$net_code}_imports set is_invited=1, modified = '" . time() . "' where profile_id IN ($list) AND user_id=:user_id");
    $stmt->execute(array('user_id' => $user_id));

    return array(
        'result' => $result,
        'count_reserve' => $count
    );
}


// также помечаем их как просмотренные
function show_users_get_imported_users_from_collection()
{
    global $types_fields_inv;
    global $show_imported_categories;
    global $user_id;
    global $connect;
    global $show_imported_types;
    global $show_users_number;
    global $net_code;

    // если импортированы из коллекции
    // берем условия выборки
    // !!! --> заметка
    // 100 000 непросмотренных пользователей с десятизначными ids = (100 000 (количество ids) * 10 (разряды) + 100 000 (запятые) + 100 (сам код)) * 4 (количество байт в символе - это максимум)= 4 МБ
    // max_allowed_packet = обычно равен 16 МБ
    // !!! <-- заметка

    $sql = "SELECT ids_not_invited FROM {$net_code}_collections_imports WHERE category_id = $show_imported_categories AND user_id = $user_id";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $ids_not_invited = $result['ids_not_invited'];

    $condition = '';

    if ($ids_not_invited) {
        $condition .= " id IN ($ids_not_invited) ";
    } else {
        $condition .= " 1 ";
    }

    // теперь мы знаем кого можно показать
    //  если указали тип пользователя (при просмотре только один тип выбирается)
    if ($show_imported_types > 0) {
        $condition .= " AND {$types_fields_inv[$show_imported_types]}=1";
    }
    $sql = "SELECT * FROM {$net_code}_collections_$show_imported_categories WHERE $condition  order by id ASC limit " . $show_users_number;

    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    // ищем сколько осталось по данному критерию
    $sql = "SELECT count(*) FROM {$net_code}_collections_$show_imported_categories WHERE $condition";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $count = $stmt->fetchColumn() - $show_users_number;
    $count = $count > 0 ? $count : 0;

    //помечаем их как просмотренные
    $new_ids_not_invited = array();

    foreach (explode(',', $ids_not_invited) as $id_not_invited) {
        $new_ids_not_invited[$id_not_invited] = $id_not_invited;
    }


    foreach ($result as $row) {
        unset($new_ids_not_invited[$row['id']]);
    }
    $new_ids_not_invited = implode(',', $new_ids_not_invited);

    $sql = "UPDATE {$net_code}_collections_imports SET ids_not_invited = '$new_ids_not_invited' WHERE category_id = $show_imported_categories AND user_id = $user_id";
    $stmt = $connect->prepare($sql);
    $stmt->execute();

    return array(
        'result' => $result,
        'count_reserve' => $count
    );
}


function get_types_array($row)
{
    global $types_fields;
    $types_array = array();
    foreach ($types_fields as $type_field => $type_number) {

        if (!empty($row[$type_field]) && $row[$type_field] == 1) {
            $types_array[] = $type_number;
        }
    }
    return $types_array;
}


function get_types_text($row, $excel = false)
{

    global $types_fields;
    $text_array = array();
    foreach ($types_fields as $type_number) {

        if ($row[$types_field] == 1) {
            $text_array[] = get_type_name($type_number, $excel);
        }
    }

    return implode(',', $text_array);
}


function get_type_code_by_id($user_type)
{

    if ($user_type == 1) {
        return 'klass';
    } elseif ($user_type == 2) {
        return 'subscribe';
    } elseif ($user_type == 3) {
        return 'search';
    } elseif ($user_type == 5) {
        return 'survey';
    } elseif ($user_type == 6) {
        return 'comment';
    }
}


//берем доступные импортированные типы пользователей по категории
function get_client_imported_enabled_types_by_category($category_id)
{
    global $user_id;

    if ($category_id == 0) {
        // то есть все категории
        $category_id = false;
    }

    $return = get_available_collection_imported_types_from_base_not_invited($user_id, $category_id);
    return $return ? : array();
}


function get_available_collection_imported_types_from_base_not_invited($user_id, $category_id)
{

    global $connect;
    global $types_fields;
    global $net_code;

    $category_id = (int) $category_id;
    $return = array();

    // берем его неприглашенных пользователей из таблицы ok_collections_imports
    $sql = "SELECT ids_not_invited FROM {$net_code}_collections_imports WHERE user_id=:user_id AND category_id=$category_id";

    $stmt = $connect->prepare($sql);
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $ids_not_invited = $result['ids_not_invited'];
    // !!! --> заметка
    // 100 000 непросмотренных пользователей с десятизначными ids = (100 000 (количество ids) * 10 (разряды) + 100 000 (запятые) + 100 (сам код)) * 4 (количество байт в символе - это максимум)= 4 МБ
    // max_allowed_packet = обычно равен 16 МБ
    // !!! <-- заметка

    // преобразуем в условие
    //$condition_query = Kits_Converter::convert_numbers_to_query($numbers_array = explode(',', $ids), $not = false);
    //$condition_query = $condition_query ? : '';




    // теперь берем все записи из таблицы ok_collections_{$category_id} также как и из ok_imports
    foreach ($types_fields as $type_field => $number_type) {
        if ($type_field == 'is_search') {
            continue;
        }
        $stmt = $connect->prepare("SELECT count(*) as count FROM {$net_code}_collections_{$category_id} WHERE id IN ($ids_not_invited) AND $type_field = 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result['count'] > 0) {
            $return[] = [$number_type, get_type_name_by_id($number_type)];
        }
    }
    return $return ? : array();
}


// стоимость за одного пользователя по заданным параметрам
// > метод, позволяющий при импорте определять стоимость 1 пользователя по его типам ,что указали в чекбоксах, (чем больше в его составе типов, тем дороже)
//>> что делаем: берем самый дорогой тип, делаем цену = его стоимости, потом прибавляем к нему остальные помноженнные, скажем, на 30%
//>> т.е. стоимость составного типа = стоимость самого дорогого типа + (сумма стоимостей остальных типов)*0.3
//NOTE - есть такая же JS функция get_import_collection_request_cost_per_one_user()
function get_import_collection_request_cost_per_one_user($data)
{

    if (($data['KLASS'] == -1) && ($data['SUBSCRIBER'] == -1) && ($data['SURVEY'] == -1) && ($data['COMMENT'] == -1)) {
        return MY_USER_IMPORT_COST['GENERIC'];
    }


    $most_expensive = 0;
    $most_expensive_type = '';


    $count_types = 0;
    $tmp_type = '';
    foreach ($data as $type => $value) {
        if ($value) {
            $count_types++;
            $tmp_type = $type;
            if ($most_expensive < MY_USER_IMPORT_COST[$type]) {
                $most_expensive = MY_USER_IMPORT_COST[$type];
                $most_expensive_type = $type;
            }
        }
    }
    // если выбран 1 тип
    if ($count_types == 1) {
        return round(MY_USER_IMPORT_COST[$tmp_type], 2);
    }
    if ($count_types == 0) {
        return null;
    }

    // если выбрано 2+ типа
    $price = $most_expensive;

    foreach (MY_USER_IMPORT_COST as $type => $amount) {
        if ((!empty($data[$type])) && ($most_expensive_type != $type)) {
            $price += $amount * MY_USER_IMPORT_COST_ADDITIONAL_KOEF;
        }
    }
    return round($price, 2);
}


function prepare_import_types_condition($user_type_klass, $user_type_subscriber, $user_type_survey, $user_type_comment)
{

    $sql = "";

    if ($user_type_klass == -1) {

    } else {

        if (!is_null($user_type_comment)) {
            $user_type_comment = $user_type_comment ? 1 : 0;
            $sql .= " AND is_comment = $user_type_comment";
        }
        if (!is_null($user_type_survey)) {
            $user_type_survey = $user_type_survey ? 1 : 0;
            $sql .= " AND is_survey = $user_type_survey";
        }
        if (!is_null($user_type_subscriber)) {
            $user_type_subscriber = $user_type_subscriber ? 1 : 0;
            $sql .= " AND is_subscriber = $user_type_subscriber";
        }
        if (!is_null($user_type_klass)) {
            $user_type_klass = $user_type_klass ? 1 : 0;
            $sql .= " AND is_klass = $user_type_klass";
        }
    }
    return $sql;
}


function import_needed_types_list($user_type_klass, $user_type_subscriber, $user_type_survey, $user_type_comment)
{

    $list = "";

    if ($user_type_klass == -1) {
        $list .= 'all';
    } else {

        if (!is_null($user_type_comment)) {
            $user_type_comment = $user_type_comment ? 1 : 0;
            $list .= "is_comment:$user_type_comment,";
        }
        if (!is_null($user_type_survey)) {
            $user_type_survey = $user_type_survey ? 1 : 0;
            $list .= " is_survey:$user_type_survey,";
        }
        if (!is_null($user_type_subscriber)) {
            $user_type_subscriber = $user_type_subscriber ? 1 : 0;
            $list .= "is_subscriber:$user_type_subscriber,";
        }
        if (!is_null($user_type_klass)) {
            $user_type_klass = $user_type_klass ? 1 : 0;
            $list .= "is_klass:$user_type_klass,";
        }
    }
    return trim($list, ',');
}

class Kits_Converter
{


    //$row_numbers_array - ключ = значениЮ
    public static function convert_numbers_to_ranges($numbers_array)
    {

        $row_numbers_array = array();
        foreach ($numbers_array as $value) {
            if ($value) {
                $row_numbers_array[$value] = $value;
            }
        }

        $duplicate_array = $row_numbers_array;
        $new_intervals_array = array();

        foreach ($row_numbers_array as $key => $current_value) {

            if ($current_value) {
                //для каждого числа

                unset($duplicate_array[$key]);
                // то есть сравниваем со следующими новыми числами, то есть не текущее с самим собой и не прошлые с текущим
                // интервал для $current_value
                foreach ($duplicate_array as $d_key => $compared_value) {
                    if ($compared_value) {
                        // сравниваемые числа дожны быть меньше на 1 или больше на 1
                        if (($current_value == ($compared_value + 1)) || ($current_value == ($compared_value - 1))) {

                            // тогда оба числа - текущее и после него (или до него) - переходят в интервал
                            $new_intervals_array[$current_value] = $current_value;
                            $new_intervals_array[$compared_value] = $compared_value;
                        }
                    }
                }
            }
        }
        ksort($new_intervals_array);
        // в итоге получили массив $new_intervals_array от min до max чисел, которые имеют интервал
        // теперь нужно сформировать из них диапазоны
        $current_interval_value = '';
        $new_ranges_array = array();
        $counter_new_ranges = 1;
        foreach ($new_intervals_array as $new_interval_key => $new_interval_value) {
            if ($new_interval_value) {
                // удаляем эти числа из $row_numbers_array, т.к. они теперь будут в диапазоне
                unset($row_numbers_array[$new_interval_key]);

                ### задача - получить двухмерный массив с наборами чисел
                if (!$current_interval_value) {
                    //с этой записи все начинается
                    $new_ranges_array[$counter_new_ranges][] = $new_interval_value;
                } else {
                    // далее думаем - в какой набор добавлять новое число
                    if ($current_interval_value == ($new_interval_value - 1)) { // ($new_interval_value + 1) тут не нужен, так как массив отсортирован по ASC
                        // означает продолжение текущего набора
                    } else {
                        // означает новый диапазон
                        $counter_new_ranges ++;
                    }
                    $new_ranges_array[$counter_new_ranges][] = $new_interval_value;
                }
                $current_interval_value = $new_interval_value;
            }
        }
        ksort($row_numbers_array);
        return array(
            'new_ranges_array' => $new_ranges_array, // - двухмерный массив с новыми диапазонами старых и новых чисел
            'row_numbers_array' => $row_numbers_array// - оставшиеся числа - старые и новые, что не стали интервалами
        );
    }


    public static function convert_to_intenvals($current_row, $additional_row, $not_query = true)
    {
        // $current_row - строка из базы - в котором могут присутствовать интервалы и строки
        // $additional_row - строка с новыми числами
        // добавляем в конец новый ряд
        $current_row = $current_row ? ($current_row . ',' . $additional_row) : $additional_row;

        // в итоге получаем новый непреобразованный ряд в виде строки
        // преобразуем строковый ряд в массив
        $row_generic_array = explode(',', $current_row);

        //получили массив со строками с отдельными числами и интервалами
        // разделяем этот массив на 2 - с отдельными числами и интервалами
        // 1: с отдельными числами
        $row_numbers_array = array();
        foreach ($row_generic_array as $key => $row_generic_value) {
            if ($row_generic_value) {
                if (strpos($row_generic_value, '-') === false) {
                    $row_numbers_array[$row_generic_value] = $row_generic_value;
                    unset($row_generic_array[$key]);
                }
            }
        }

        // 2: с интервалами
        // оставшиеся элементы - это интервалы
        $row_intervals_array = $row_generic_array;
        unset($row_generic_array);

        // в итоге имеем
        // $row_numbers_array - массив с числами
        // $row_intervals_array - массив с интервалами
        # задача - сделать как можно больше интервалов
        //(числа могут либо стать новыми интервалами, либо расширить существующий интервал)
        ## задача 1 - попробовать преобразовать числа в диапазоны
        $current_result = self::convert_numbers_to_ranges($row_numbers_array);
        $new_ranges_array = $current_result['new_ranges_array'];
        $row_numbers_array = $current_result['row_numbers_array'];
        // в итоге получили - двухмерный массив с новыми диапазонами старых (включительно) и новых чисел (заметьте - сформированные ранее диапазоны тут не упоминаются... только числа) и массив с просто числами за все время, не образующими диапазон ни с кем
        // ! все это сформировано из чисел - новых и старых
        // $new_ranges_array - двухмерный массив с новыми диапазонами старых и новых чисел
        // и $row_numbers_array - оставшиеся числа - старые и новые, что не стали интервалами
        // $row_intervals_array - массив со старыми интервалами
        //print_r($new_ranges_array);
        //print_r($row_numbers_array);
        ## задача 2 - внедрить новые интервалы в уже существующие (старые) - состыковать по возможности или просто добавить как новые
        $replaced_intervals = array();
        //! новые интервалы между собой не свяжутся, потому что, если бы это было бы так, то они бы уже связались при формировании $new_ranges_array
        // проходимся по старым интервалам и пытаемся связать каждый новый интервал с каждым старым
        foreach ($row_intervals_array as &$row_interval_string) {
            if ($row_interval_string) {
                $range_min_max = explode('-', $row_interval_string);
                $min_current_range = $range_min_max[0]; // - минимальное число из интервала
                $max_current_range = $range_min_max[1]; // - максимальное число из интервала
                foreach ($new_ranges_array as $new_range_key => $new_range_array) {
                    if ($new_range_array) {
                        if (array_key_exists($new_range_key, $replaced_intervals)) {
                            // этот новый интервал уже состыкован, потом попытаемся объединить между собой обновленные интервалы
                            continue;
                        }

                        $min_new_range = current($new_range_array);
                        $max_new_range = end($new_range_array);

                        // --> (продублировано ниже)
                        // какие варианты могут быть:
                        $was_replaced = false;
                        if ((($max_current_range + 1) == $min_new_range) // рядом по правую сторону от старой
                                || (($min_new_range <= $max_current_range) && ($min_new_range >= $min_current_range))) {
                            // левая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона, либо поглощен старым диапазоном, либо дипазоны полностью идентичны
                            //echo "=1=";
                            $max_current_range = ($max_current_range < $max_new_range) ? $max_new_range : $max_current_range;
                            $was_replaced = true;
                        } elseif ((($min_current_range - 1) == $max_new_range) // рядом по левую сторону от старой
                                || (($max_new_range >= $min_current_range) && ($max_new_range <= $max_current_range))) {
                            // правая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона, либо поглощен старым диапазоном, либо дипазоны полностью идентичны
                            //echo "=2=";
                            $min_current_range = ($min_current_range > $min_new_range) ? $min_new_range : $min_current_range;
                            $was_replaced = true;
                        } elseif (($max_current_range < $max_new_range) && ($min_current_range > $min_new_range)) {// новый диапазон перекрывает по всем сторонам старый, то ес тьрасширяет его с двух сторон
                            //echo "=3=";
                            $min_current_range = $min_new_range;
                            $max_current_range = $max_new_range;
                            $was_replaced = true;
                        }
                        // <-- (продублировано ниже)

                        if ($was_replaced == true) {
                            $replaced_intervals[$new_range_key] = $new_range_key;
                            // переписываем интервал
                            $row_interval_string = $min_current_range . '-' . $max_current_range;
                            // удаляем перенесенный интервал из $new_ranges_array
                            unset($new_ranges_array[$new_range_key]);
                        }
                        //echo $min_current_range."\n";
                        //echo $max_current_range."\n";
                        //echo $min_new_range."\n";
                        //echo $max_new_range."\n\n\n";
                    }
                }
            }
        }

        // добавляем новые несостыкованные интервалы к старым как отдельные
        foreach ($new_ranges_array as $new_range_array) {
            if ($new_range_array) {
                $row_intervals_array[] = current($new_range_array) . '-' . end($new_range_array);
            }
        }
        unset($new_ranges_array);
        // в итоге
        // перенесли интервалы из $new_ranges_array в $row_intervals_array, по возможности состыковав со старыми
        // имеем:
        // и $row_numbers_array - все числа
        // $row_intervals_array - массив со всеми интервалами
        ## задача 3 - попробовать объединить существующие интервалы между собой
        #! надо уничтожить лишние интервалы и, если один интервал состыковывается с несколькими, то не надо расширять все, достаточно расширить один, иначе будет сильное ненужное перекрытие
        $duplicate_array = $row_intervals_array;
        $replaced_intervals = array();
        foreach ($row_intervals_array as $key => &$current_value) {
            if ($current_value) {
                //для каждого интервала
                unset($duplicate_array[$key]);

                if (array_key_exists($key, $replaced_intervals)) {
                    continue;
                }

                // то есть сравниваем со следующими новыми интервалами, то есть не текущий с самим собой и не прошлые с текущим
                // для $current_value
                $range_min_max_current = explode('-', $current_value);
                $min_current_range = $range_min_max_current[0]; // - минимальное число из интервала
                $max_current_range = $range_min_max_current[1]; // - максимальное число из интервала
                foreach ($duplicate_array as $d_key => $compared_value) {
                    if ($compared_value) {
                        if (array_key_exists($d_key, $replaced_intervals)) {
                            continue;
                        }
                        $range_min_max_compared = explode('-', $compared_value);
                        $min_compared_range = $range_min_max_compared[0]; // - минимальное число из интервала
                        $max_compared_range = $range_min_max_compared[1]; // - максимальное число из интервала
                        // --> (продублировано выше)
                        // какие варианты могут быть:
                        $was_replaced = false;
                        if ((($max_current_range + 1) == $min_compared_range) // рядом по правую сторону от старой
                                || (($min_compared_range <= $max_current_range) && ($min_compared_range >= $min_current_range))) {
                            // левая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона, либо поглощен старым диапазоном, либо дипазоны полностью идентичны
                            //echo "=1=";
                            $max_current_range = ($max_current_range < $max_compared_range) ? $max_compared_range : $max_current_range;
                            $was_replaced = true;
                        } elseif ((($min_current_range - 1) == $max_compared_range) // рядом по левую сторону от старой
                                || (($max_compared_range >= $min_current_range) && ($max_compared_range <= $max_current_range))) {
                            // правая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона, либо поглощен старым диапазоном, либо дипазоны полностью идентичны
                            //echo "=2=";
                            $min_current_range = ($min_current_range > $min_compared_range) ? $min_compared_range : $min_current_range;
                            $was_replaced = true;
                        } elseif (($max_current_range < $max_compared_range) && ($min_current_range > $min_compared_range)) {// новый диапазон перекрывает по всем сторонам старый, то есть расширяет его с двух сторон
                            //echo "=3=";
                            $min_current_range = $min_compared_range;
                            $max_current_range = $max_compared_range;
                            $was_replaced = true;
                        }
                        // <-- (продублировано выше)
                        // либо $current_value расширен либо $compared_value поглощен by $current_value
                        if ($was_replaced == true) {
                            $replaced_intervals[$d_key] = $d_key;
                            // переписываем интервал
                            $current_value = $min_current_range . '-' . $max_current_range;
                            // удаляем состыкованный интервал из $row_intervals_array
                            unset($row_intervals_array[$d_key]);
                        }
                        //echo $min_current_range."\n";
                        //echo $max_current_range."\n";
                        //echo $min_compared_range."\n";
                        //echo $max_compared_range."\n\n\n";
                    }
                }
            }
        }
        // в итоге получили
        // оптимизированный $row_intervals_array - часть интервалов попытались объединить
        // $row_numbers_array - все числа
        ## задача 4 - получившиеся новые интервалы мы уже попробовали состыковать со старыми,
        ## теперь попробуем состыковать оставшиеся числа с получившимися интервалами
        ## (чисел в конце алгоритма должно стать меньше, равно как и интервалов (если они "слились" между собой), если добавились, то не на много, не стоит бояться)
        // имеем - оптимизированные интервалы $row_intervals_array и числа $row_numbers_array
        // проходим по каждому интервалу в $row_intervals_array и состыковываем числа из $row_numbers_array с ними
        $replaced_numbers = array();
        foreach ($row_intervals_array as $key => &$current_value) {
            if ($current_value) {
                // для $current_value
                $range_min_max_current = explode('-', $current_value);
                $min_current_range = $range_min_max_current[0]; // - минимальное число из интервала
                $max_current_range = $range_min_max_current[1]; // - максимальное число из интервала
                // проходим по каждому числу
                foreach ($row_numbers_array as $row_number_key => $row_number_value) {
                    if ($row_number_key) {
                        if (array_key_exists($row_number_key, $replaced_numbers)) {
                            continue;
                        }

                        $was_replaced = false;


                        if ($row_number_value == ($min_current_range - 1)) {
                            //если присостыковывается слева
                            $min_current_range = $row_number_value;
                            $was_replaced = true;
                        } else if ($row_number_value == ($max_current_range + 1)) {
                            //если присостыковывается справа
                            $max_current_range = $row_number_value;
                            $was_replaced = true;
                        } elseif (($row_number_value >= $min_current_range) && ($row_number_value <= $max_current_range)) {
                            // если внутри интервала
                            $was_replaced = true;
                        }

                        if ($was_replaced == true) {
                            $replaced_numbers[$row_number_key] = $row_number_key;
                            // переписываем интервал
                            $current_value = $min_current_range . '-' . $max_current_range;
                            // удаляем состыкованное число из $row_numbers_array
                            unset($row_numbers_array[$row_number_key]);
                        }
                    }
                }
            }
        }
        // в итоге имеем
        // $row_intervals_array - полностью оптимизированные интервалы (если еще раз прогнать их, то возможно станут более оптимизированнее, но это можно сделать при очередной прогонке после следующего импорта из коллекции)
        // $row_numbers_array - числа, которые еще не состыкуются с интервалами (возможно при следующем импорте они дополнятся новыми числами и образуют наконец свой интервал и по возможности сольются с существующими интервалами)
        ## задача 5 - сформировать строку
        $stroke = '';
        $intervals_stroke = implode(',', $row_intervals_array);
        $numbers_stroke = implode(',', $row_numbers_array);
        if ($numbers_stroke && $intervals_stroke) {
            $stroke = $intervals_stroke . ',' . $numbers_stroke;
        } elseif ($intervals_stroke) {
            $stroke = $intervals_stroke;
        } elseif ($numbers_stroke) {
            $stroke = $numbers_stroke;
        }


        ## задача 6 - сформировать SQL запрос
        $not = $not_query ? 'NOT' : '';
        $sql_condition = '';
        foreach ($row_intervals_array as $row_interval_value) {
            if ($row_interval_value) {
                $range_min_max_current = explode('-', $row_interval_value);
                $min_current_range = $range_min_max_current[0]; // - минимальное число из интервала
                $max_current_range = $range_min_max_current[1]; // - максимальное число из интервала
                $sql_condition .= " (id $not BETWEEN " . $min_current_range . ' AND ' . $max_current_range . ') AND';
            }
        }
        if (!$numbers_stroke) {
            $sql_condition = trim($sql_condition, 'AND');
        } else {
            $sql_condition .= " id $not IN (" . $numbers_stroke . ')';
        }

        return array(
            'stroke' => $stroke,
            'sql_condition' => $sql_condition
        );
    }
    /* тест
     *
     *
     *
     *
     *
     * задача 2
     * <?php

      $new_ranges_array = [0=>[215,216,217,218],1=>[109,110,111,112,113,114,115,116]];
      $row_intervals_array = ['10-12','114-119','251-309'];

      ## задача 2 - внедрить новые интервалы в уже существующие (старые) - состыковать по возможности или просто добавить как новые
      $replaced_intervals = array();
      //! новые интервалы между собой не свяжутся, потому что, если бы это было бы так, то они бы уже связались при формировании $new_ranges_array
      // проходимся по старым интервалам и пытаемся связать каждый новый интервал с каждым старым
      foreach($row_intervals_array as &$row_interval_string) {
      $range_min_max = explode('-', $row_interval_string);
      $min_current_range = $range_min_max[0];// - минимальное число из интервала
      $max_current_range = $range_min_max[1];// - максимальное число из интервала
      foreach($new_ranges_array as $new_range_key => $new_range_array) {


      if (array_key_exists($new_range_key, $replaced_intervals)) {
      continue;
      }

      $min_new_range = current($new_range_array);
      $max_new_range = end($new_range_array);
      // какие варианты могут быть:
      $was_replaced = false;
      if ((($max_current_range + 1) == $min_new_range) // рядом по правую сторону от старой
      || (($min_new_range <= $max_current_range) && ($min_new_range >= $min_current_range)))
      // левая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона
      {
      //echo "=1=";
      $max_current_range = ($max_current_range < $max_new_range) ? $max_new_range : $max_current_range;
      $was_replaced = true;
      } elseif ((($min_current_range - 1) == $max_new_range) // рядом по левую сторону от старой
      || (($max_new_range >= $min_current_range) && ($max_new_range <= $max_current_range)))
      // правая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона
      {
      //echo "=2=";
      $min_current_range = ($min_current_range > $min_new_range) ? $min_new_range : $min_current_range;
      $was_replaced = true;
      } elseif (($max_current_range < $max_new_range) && ($min_current_range > $min_new_range)) {// новый диапазон перекрывает по всем сторонам старый, то ес тьрасширяет его с двух сторон
      //echo "=3=";
      $min_current_range = $min_new_range;
      $max_current_range = $max_new_range;
      $was_replaced = true;
      }

      if ($was_replaced == true) {
      $replaced_intervals[$new_range_key] = $new_range_key;
      // переписываем интервал
      $row_interval_string = $min_current_range . '-' . $max_current_range;
      unset($new_ranges_array[$new_range_key]);
      }
      //echo $min_current_range."\n";
      //echo $max_current_range."\n";
      //echo $min_new_range."\n";
      //echo $max_new_range."\n\n\n";
      }
      }
      // добавляем новые несостыкованные интервалы к старым как отдельные
      foreach ($new_ranges_array as $new_range_array) {
      $row_intervals_array[] = current($new_range_array) . '-' . end($new_range_array);
      }
      unset($new_ranges_array);
      print_r($row_intervals_array);
     *
     *
     *
     *
     *
     *
     *
     *
     *
     * задача 3
     * <?php
      $row_intervals_array = ['14-119','51-309','10-19'];
      ## задача 3 - попробовать объединить существующие интервалы между собой
      #! надо уничтожить лишние интервалы и, если один интервал состыковывается с несколькими, то не надо расширять все, достаточно расширить один, иначе будет сильное ненужное перекрытие
      $duplicate_array = $row_intervals_array;
      $replaced_intervals = array();
      foreach ($row_intervals_array as $key => &$current_value){
      //для каждого интервала
      unset($duplicate_array[$key]);

      if (array_key_exists($key, $replaced_intervals)) {
      continue;
      }

      // то есть сравниваем со следующими новыми интервалами, то есть не текущий с самим собой и не прошлые с текущим

      // для $current_value
      $range_min_max_current = explode('-', $current_value);
      $min_current_range = $range_min_max_current[0];// - минимальное число из интервала
      $max_current_range = $range_min_max_current[1];// - максимальное число из интервала
      foreach($duplicate_array as  $d_key => $compared_value) {
      if (array_key_exists($d_key, $replaced_intervals)) {
      continue;
      }
      $range_min_max_compared = explode('-', $compared_value);
      $min_compared_range = $range_min_max_compared[0];// - минимальное число из интервала
      $max_compared_range = $range_min_max_compared[1];// - максимальное число из интервала

      // --> (продублировано выше)
      // какие варианты могут быть:
      $was_replaced = false;
      if ((($max_current_range + 1) == $min_compared_range) // рядом по правую сторону от старой
      || (($min_compared_range <= $max_current_range) && ($min_compared_range >= $min_current_range)))
      // левая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона
      {
      //echo "=1=";
      $max_current_range = ($max_current_range < $max_compared_range) ? $max_compared_range : $max_current_range;
      $was_replaced = true;
      } elseif ((($min_current_range - 1) == $max_compared_range) // рядом по левую сторону от старой
      || (($max_compared_range >= $min_current_range) && ($max_compared_range <= $max_current_range)))
      // правая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона
      {

      //echo "=2=";
      $min_current_range = ($min_current_range > $min_compared_range) ? $min_compared_range : $min_current_range;
      $was_replaced = true;
      } elseif (($max_current_range < $max_compared_range) && ($min_current_range > $min_compared_range)) {// новый диапазон перекрывает по всем сторонам старый, то есть расширяет его с двух сторон
      //echo "=3=";
      $min_current_range = $min_compared_range;
      $max_current_range = $max_compared_range;
      $was_replaced = true;
      }
      // <-- (продублировано выше)

      // либо $current_value расширен либо $compared_value поглощен by $current_value
      if ($was_replaced == true) {
      $replaced_intervals[$d_key] = $d_key;
      // переписываем интервал
      $current_value = $min_current_range . '-' . $max_current_range;
      // удаляем состыкованный интервал из $row_intervals_array
      unset($row_intervals_array[$d_key]);
      }
      //echo $min_current_range."\n";
      //echo $max_current_range."\n";
      //echo $min_compared_range."\n";
      //echo $max_compared_range."\n\n\n";
      }
      // в итоге получили сокращенный $row_intervals_array - часть интервалов попытались объединить
      }


      print_r($row_intervals_array);
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
      <?php


      $row_intervals_array = ['14-119','121-309','10-120'];

      $row_numbers_array = [1,5,19,190,310];
      ## задача 4 - получившиеся новые интервалы мы уже попробовали состыковать со старыми,
      ## теперь попробуем состыковать оставшиеся числа с получившимися интервалами
      ## (чисел в конце алгоритма должно стать меньше, равно как и интервалов (если они "слились" между собой), если добавились, то не на много, не стоит бояться)


      // имеем - оптимизированные интервалы $row_intervals_array и числа $row_numbers_array
      // проходим по каждому интервалу в $row_intervals_array и состыковываем числа из $row_numbers_array с ними
      $replaced_numbers = array();
      foreach ($row_intervals_array as $key => &$current_value){
      // для $current_value
      $range_min_max_current = explode('-', $current_value);
      $min_current_range = $range_min_max_current[0];// - минимальное число из интервала
      $max_current_range = $range_min_max_current[1];// - максимальное число из интервала

      // проходим по каждому числу
      foreach ($row_numbers_array as $row_number_key => $row_number_value){

      if (array_key_exists($row_number_key, $replaced_numbers)) {
      continue;
      }

      $was_replaced = false;


      if ($row_number_value == ($min_current_range - 1)) {
      //если присостыковывается слева
      $min_current_range = $row_number_value;
      $was_replaced = true;
      } else if ($row_number_value == ($max_current_range + 1)) {
      //если присостыковывается справа
      $max_current_range = $row_number_value;
      $was_replaced = true;
      } elseif (($row_number_value >=$min_current_range) && ($row_number_value <=$max_current_range)){
      // если внутри интервала
      $was_replaced = true;
      }

      if ($was_replaced == true) {
      $replaced_numbers[$row_number_key] = $row_number_key;
      // переписываем интервал
      $current_value = $min_current_range . '-' . $max_current_range;
      // удаляем состыкованное число из $row_numbers_array
      unset($row_numbers_array[$row_number_key]);
      }
      }
      }

      print_r($row_intervals_array);

      print_r($row_numbers_array);
     *
     *
     *
     *
     *
     *
     *
     *
     *
     */


    public static function add_to_kit($ids_current, $ids_new)
    {
        $ids_current_array = explode(',', $ids_current);
        $ids_new_array = explode(',', $ids_new);

        $ids_result_array = array();
        foreach ($ids_current_array as $ids_current_value) {
            if ($ids_current_value) {
                $ids_result_array[$ids_current_value] = $ids_current_value;
            }
        }
        foreach ($ids_new_array as $ids_new_value) {
            if ($ids_new_value) {
                $ids_result_array[$ids_new_value] = $ids_new_value;
            }
        }

        return $ids_result_array;
    }


    public static function remove_from_kit($ids_current, $ids_to_remove)
    {
        $ids_current_array = explode(',', $ids_current);
        $ids_to_remove_array = explode(',', $ids_to_remove);

        $ids_result_array = array();
        foreach ($ids_current_array as $ids_current_value) {
            if ($ids_current_value) {
                $ids_result_array[$ids_current_value] = $ids_current_value;
            }
        }
        foreach ($ids_to_remove_array as $ids_to_remove_value) {
            unset($ids_result_array[$ids_to_remove_value]);
        }

        return $ids_result_array;
    }


    public static function convert_numbers_to_not_query($numbers_array)
    {
        return self::convert_numbers_to_query($numbers_array, $not = true);
    }


    //$numbers_array - может быть [1=>3] или [3=>3] - не важно - все равно массив приводится к требуемому состоянию в методе convert_numbers_to_ranges()
    public static function convert_numbers_to_query($numbers_array, $not = false)
    {


        $not = $not ? 'NOT ' : '';

        $numbers_to_ranges_result = self::convert_numbers_to_ranges($numbers_array);
        $ranges_array = $numbers_to_ranges_result['new_ranges_array'];
        $numbers_array = $numbers_to_ranges_result['row_numbers_array'];
        // имеем
        // $ranges_array - двухмерный массив с новыми диапазонами старых и новых чисел
        // и $numbers_array - оставшиеся числа - старые и новые, что не стали интервалами
        $sql_condition = '';
        foreach ($ranges_array as $range_key => $range_array) {
            if ($range_array) {
                $min = current($range_array);
                $max = end($range_array);
                $sql_condition .= ' (id' . $not . ' BETWEEN ' . $min . ' AND ' . $max . ') ' . ($not ? 'AND' : 'OR');
            }
        }

        $numbers_stroke = implode(',', $numbers_array);

        if (!$numbers_stroke) {
            $sql_condition = trim($sql_condition, ($not ? 'AND' : 'OR'));
        } else {
            $sql_condition .= ' id' . $not . ' IN (' . $numbers_stroke . ')';
        }

        return $sql_condition;
    }


    public static function add_new_numbers_to_numbers($old_numbers, $new_numbers)
    {

        $result = array();
        foreach ($old_numbers as $value) {
            if ($value) {
                $result[$value] = $value;
            }
        }
        foreach ($new_numbers as $new_numbers) {
            if ($new_numbers) {
                $result[$new_numbers] = $new_numbers;
            }
        }
        ksort($result);

        return $result;
    }
}

prepare_net_code();
prepare_net_profile_url();
