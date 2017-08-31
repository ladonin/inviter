<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Получить подписчиков в группу';
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic/generic_functions.php');



//$types_fields = array(
//    'is_comment' => 6,
//    'is_survey' => 5,
//    'is_subscriber' => 2,
//    'is_klass' => 1,
//    'is_search' => 3,
//    'is_repost' => 4,
//);
$searched_url = 'https://ok.ru/poleznye.sovetu/topic/66291194858250';
$user_type = 5;


exit();



change_category_in_collections($net = 'ok', $is_temp = '', $category_from = '7', $category_to = '10', $user_type, $searched_url);

function change_category_in_collections($net, $is_temp, $category_from, $category_to, $user_type, $searched_url)
{
    global $net_code;
    global $connect;
    global $nets;
    global $types_fields_inv;



    $deleted_from_from = 0;
    $updated_from_from = 0;
    $cutted_from_from = 0;
    $ignored_from_from = 0;

    $ignored_in_to = 0;
    $updated_in_to = 0;
    $inserted_in_to = 0;
    $added_new_type_in_to = 0;




    // берем все пользователей
    $temp = $is_temp ? '_temp' : '';

    $table_name_from = "{$net}_collections{$temp}_{$category_from}";
    $table_name_to = "{$net}_collections{$temp}_{$category_to}";

    $stmt = $connect->prepare("SELECT * FROM {$table_name_from} WHERE {$types_fields_inv[$user_type]} = 1");
    $stmt->execute();
    $all_users = $stmt->fetchAll();

    // ищем из них пользователей с этим url для этого типа

    $users = array();
    foreach ($all_users as $current_user) {

        $status = 0;
        $current_key = null;
        $data_array = json_decode($current_user['data'], true);

        // дублирование проверки существования типа
        if (isset($data_array['urls'][$user_type])) {

            // ищем в нем наш $searched_url
            foreach ($data_array['urls'][$user_type] as $key => $current_url) {

                if ($current_url === $searched_url) {
                    // нашли, значит status у текущего пользователя должен быть > 0
                    // 1) если тип пользователя один и url тоже один (только наш)
                    if (count($data_array['urls']) == 1 && count($data_array['urls'][$user_type]) == 1) {
                        // удаляем эту запись из $category_from
                        $status = 1;
                    } else if (count($data_array['urls']) == 1 && count($data_array['urls'][$user_type]) > 1) {
                        // 2) если тип пользователя один, но url в нем несколько
                        // удаляем только этот url - удаляем $data_array['urls'][$user_type][$searched_url] в $category_from
                        // сохраняем запись в $category_from
                        $status = 2;
                    } else if (count($data_array['urls']) > 1 && count($data_array['urls'][$user_type]) == 1) {
                        // 3) если типов пользователя несколько и url для нашего типа один (только наш)
                        // сохраняем запись в $category_from
                        // удаляем $user_type в url - удаляем $data_array['urls'][$user_type] в $category_from
                        // обнуляем этот тип - ставим {$types_fields_inv[$user_type]} = 0 в $category_from
                        $status = 3;
                    } else if (count($data_array['urls']) > 1 && count($data_array['urls'][$user_type]) > 1) {
                        // 4) если типов пользователя несколько и url для нашего типа несколько
                        // удаляем только этот url - удаляем $data_array['urls'][$user_type][$searched_url] в $category_from
                        // сохраняем запись в $category_from
                        $status = 4;
                    }
                    $current_key = $key;
                    break;
                }
            }
        }

        if (!is_null($current_key)) {

            // Делаем что-то с пользователем в $category_from
            if ($status == 1) {
                $deleted_from_from++;
                // удаляем эту запись из $category_from
                $connect->prepare("DELETE FROM {$table_name_from} WHERE id=:id")->execute(array('id' => $current_user['id']));
            } else if ($status == 2) {
                $updated_from_from++;
                // удаляем только этот url - удаляем $data_array['urls'][$user_type][$searched_url] в $category_from
                unset($data_array['urls'][$user_type][$current_key]);
            } else if ($status == 3) {
                $cutted_from_from++;
                // удаляем $user_type в url - удаляем $data_array['urls'][$user_type] в $category_from
                unset($data_array['urls'][$user_type]);
                // обнуляем этот тип - ставим {$types_fields_inv[$user_type]} = 0 в $category_from
                $connect->prepare("UPDATE {$table_name_from} SET {$types_fields_inv[$user_type]} = 0 WHERE id=:id")->execute(array('id' => $current_user['id']));
            } else if ($status == 4) {
                $updated_from_from++;
                // удаляем только этот url - удаляем $data_array['urls'][$user_type][$searched_url] в $category_from
                unset($data_array['urls'][$user_type][$current_key]);
            }
            if ($status > 1) {
                // то есть, если не удалили,
                // то обновляем его data
                $connect->prepare("UPDATE {$table_name_from} SET data = :data WHERE id=:id")->execute(
                        array(
                            'id' => $current_user['id'],
                            'data' => json_encode($data_array, JSON_UNESCAPED_UNICODE)
                        )
                );
            }

            // для $category_to
            if ($status) {

                // Ищем этого пользователя в $category_to
                $stmt = $connect->prepare("SELECT * FROM {$table_name_to} WHERE profile_id = :profile_id");
                $stmt->execute(array('profile_id' => $current_user['profile_id']));
                $user_from_to = $stmt->fetch();
                if ($user_from_to) {

                    // Если такой пользователь там уже есть, то надо его обновить
                    $data_array_from_to = json_decode($user_from_to['data'], true);


                    // Есть ли у него такой тип
                    if (isset($data_array_from_to['urls'][$user_type])) {

                        // Смотрим - есть ли у него уже такой url в данном типе
                        if (in_array($searched_url, $data_array_from_to['urls'][$user_type])) {
                            // у него уже есть такой url, то ничего не делаем
                            $ignored_in_to++;
                        } else {
                            // такой тип есть, но $searched_url в нем нет
                            // добавляем к остальным url в этом типе
                            $data_array_from_to['urls'][$user_type][] = $searched_url;
                            $connect->prepare("UPDATE {$table_name_to} SET data = :data WHERE id=:id")->execute(
                                    array(
                                        'id' => $user_from_to['id'],
                                        'data' => json_encode($data_array_from_to, JSON_UNESCAPED_UNICODE)
                                    )
                            );
                            $updated_in_to++;
                        }
                    } else {
                        // У него нет такого типа
                        // добавляем к его urls тип с $searched_url
                        $data_array_from_to['urls'][$user_type][] = $searched_url;

                        // добавляем ему тип {$types_fields_inv[$user_type]} = 1
                        $connect->prepare("UPDATE {$table_name_to} SET data = :data, {$types_fields_inv[$user_type]} = 1 WHERE id=:id")->execute(
                                array(
                                    'id' => $user_from_to['id'],
                                    'data' => json_encode($data_array_from_to, JSON_UNESCAPED_UNICODE)
                                )
                        );
                        $added_new_type_in_to++;
                    }
                } else {
                    // Если такого пользователя нет
                    // добавляем его с {$types_fields_inv[$user_type]} = 1 и с $searched_url в $user_type в urls
                    $stmt = $connect->prepare("INSERT into {$table_name_to} (
                            profile_id,
                            user_fio,
                            user_avatar,
                            " . $types_fields_inv[$user_type] . ",
                            data,
                            created

                        ) VALUES(
                            :profile_id,
                            :user_fio,
                            :user_avatar,
                            1,
                            :data,
                            '" . time() . "'
                        )");
                    $stmt->execute(array(
                        'profile_id' => $current_user['profile_id'],
                        'user_fio' => $current_user['user_fio'],
                        'user_avatar' => $current_user['user_avatar'],
                        'data' => json_encode(array('urls' => array($user_type => array($searched_url))), JSON_UNESCAPED_UNICODE)
                    ));
                    $inserted_in_to++;
                }
            }
        }
    }



    echo('deleted_from_from:' . $deleted_from_from . '<br>');
    echo('updated_from_from:' . $updated_from_from . '<br>');
    echo('cutted_from_from:' . $cutted_from_from . '<br>');
    echo('ignored_from_from:' . $ignored_from_from . '<br>');

    echo('ignored_in_to:' . $ignored_in_to . '<br>');
    echo('updated_in_to:' . $updated_in_to . '<br>');
    echo('inserted_in_to:' . $inserted_in_to . '<br>');
    echo('added_new_type_in_to:' . $added_new_type_in_to . '<br>');

}
