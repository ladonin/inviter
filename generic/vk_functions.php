<?php


function prepare_load_data()
{
    $users_result = array();
    $user_type = null;

    if ($_POST['type_users'] == 1) {//classes
        preg_match_all("#id=\"fans_fan_row(?:.+?)data-id=\"([0-9]+)\"(?:.+?)<img(?:.+?)src=\"(.+?)\"(?:.+?)alt=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
        $user_type = 1;
    } else if ($_POST['type_users'] == 2) {//group_users
        preg_match_all("#id=\"fans_fan_row(?:.+?)data-id=\"([0-9]+)\"(?:.+?)<img(?:.+?)src=\"(.+?)\"(?:.+?)alt=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
        if (!$users_result){
            preg_match_all("#class=\"people_row(?:[ ]*?)(?:.*?)uiPhotoZoom.over\(this,(?:[ ]*?)([0-9]+?)\)\"(?:.+?)<img(?:.+?)src=\"(.+?)\"(?:.+?)class=\"info\"(?:.+?)class=\"labeled(?:.*?)<a(?:.*?)>(.+?)</a>#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
        }
        $user_type = 2;
    } else if ($_POST['type_users'] == 4) {//repost
        preg_match_all("#class=\"post_header\"(?:.+?)<a(?:.+?)<img(?:.+?)src=\"(.+?)\"(?:.+?)class=\"post_author\"(?:.+?)data-from-id=\"(.+?)\"(?:.+?)>(.+?)</a>#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);

        foreach ($users_result as $key => $user) {
            $users_result[$key][1] = $user[2];
            $users_result[$key][2] = $user[1];
            $users_result[$key][3] = $user[3];
        }
        $user_type = 4;
    } else if ($_POST['type_users'] == 3) {//search_results
        preg_match_all("#class=\"people_row(?:.+?)\"uiPhotoZoom.over\(this,(?:[ ]?)([0-9]+)\)\"(?:.+?)<img(?:.+?)src=\"(.+?)\"(?:.+?)alt=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
        $user_type = 3;
    } else if ($_POST['type_users'] == 7) {//group_users mobile
        //!!!вроде проверил //берем всех, включая и без фото
        //preg_match_all("#<li class=\"item(?:.+?)st\.groupId=(.+?)&amp;(?:.+?)<a href=\"/dk\?st\.cmd=friendMain&amp;st\.friendId=(.+?)&amp;(?:.+?)<div class=\"clickarea_content\">(?:.+?)<img (?:.+?)src=\"(.+?)\"(?:.+?)\"(?:.+?)<span class=\"emphased usr\">(.+?)</span>#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
        $user_type = 2;
        exit();
    } else if ($_POST['type_users'] == 5) {//surveys
        preg_match_all("#id=\"fans_fan_row(?:.+?)data-id=\"([0-9]+)\"(?:.+?)<img(?:.+?)src=\"(.+?)\"(?:.+?)alt=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
        $user_type = 5;
    } else if ($_POST['type_users'] == 6) {//comments
        preg_match_all("#<img(?:.+?)src=\"(.+?)\"(?:.+?)class=\"bp_author\"(?:.+?)>(.+?)<(?:.+?)return Board.replyPost\((?:[0-9]+),(?:[ ]?)([\-0-9]+)\)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);

        if (!$users_result){
            preg_match_all("#class=\"reply_wrap(?:[ ]*?)(?:.*?)\"reply_image\"(?:.+?)<img(?:.+?)src=\"(.+?)\"(?:.+?)class=\"author\"(?:.+?)data-from-id=\"([0-9]+?)\"(?:.*?)>(.+?)</a>#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
            foreach ($users_result as $key => $user) {
                if ($user[2] < 0) {
                    unset($users_result[$key]);
                    continue;
                }
                $users_result[$key][1] = $user[2];
                $users_result[$key][2] = $user[1];
                $users_result[$key][3] = $user[3];
            }
        } else {

            foreach ($users_result as $key => $user) {
                if ($user[3] < 0) {
                    unset($users_result[$key]);
                    continue;
                }
                $users_result[$key][1] = $user[3];
                $users_result[$key][2] = $user[1];
                $users_result[$key][3] = $user[2];
            }
        }

        $user_type = 6;
    }

    $comment = isset($_POST['comment']) ? strip_tags($_POST['comment']) : '';

    foreach ($users_result as $key => $user) {

        $profile_id = $user[1];

        if ((stristr($user[2], '/images/camera_') !== FALSE)
                || (stristr($user[2], '/images/deactivated_') !== FALSE)
                || (stristr($user[2], '/images/community_') !== FALSE)
                || $profile_id < 1
                ) {
            unset($users_result[$key]);
            continue;
        }


        $user_avatar = strip_tags($user[2]);

        if ($user_avatar[0] == '/') {
            $user_avatar = 'https://vk.com' . $user_avatar;
        }
        $user_fio = strip_tags($user[3]);

        $group_id = !empty($user[4]) ? strip_tags($user[4]) : '';

        $users_result[$key][1] = $profile_id;
        $users_result[$key][2] = $user_avatar;
        $users_result[$key][3] = $user_fio;
        $users_result[$key][4] = $group_id; // group id
    }

    return array(
        'users_result' => $users_result,
        'user_type' => $user_type,
        'comment' => $comment
    );
}


function load_users_init()
{
    global $types_fields_inv;
    global $net_code;
    global $connect;
    global $user_id;
    if (isset($_POST['html_text']) && $_POST['html_text'] && $_POST['type_users']) {
        if (isset($_POST['html_text']) && $_POST['html_text']) {

            $load_data = prepare_load_data();
            $users_result = $load_data['users_result'];
            $user_type = $load_data['user_type'];
            $comment = $load_data['comment'];

            $i = 0;
            foreach ($users_result as $user) {
                $profile_id = $user[1];
                $user_avatar = $user[2];
                $user_fio = $user[3];
                // replace тут не подойдет
                // есть ли такой пользователь у клиента уже или нет
                $stmt = $connect->prepare("SELECT * FROM vk_imports WHERE profile_id=:profile_id AND user_id=:user_id");
                $stmt->execute(array('profile_id' => $profile_id, 'user_id' => $user_id));

                $result = $stmt->fetch();
                if (!$result) {
                    //если нет, то просто записываем его клиенту
                    $i++;

                    //тут он будет писаться с нуля
                    $data_array = array();
                    if (!empty($user[4])) {
                        $data_array['group_id'] = $user[4];
                    }
                    if ($comment) {
                        $data_array['comments'][$user_type] = $comment;
                    }


                    if ($data_array) {
                        $data = json_encode($data_array, JSON_UNESCAPED_UNICODE);
                    } else {
                        $data = '';
                    }


                    $stmt = $connect->prepare("INSERT into vk_imports (

                profile_id,
                user_fio,
                user_avatar,
                {$types_fields_inv[$user_type]},
                data,
                user_id,
                created

                ) VALUES(

                :profile_id,
                :user_fio,
                :user_avatar,
                1,
                :data,
                :user_id,
                '" . time() . "'

                )");
                    $stmt->execute(array(
                        'profile_id' => $profile_id,
                        'user_fio' => $user_fio,
                        'user_avatar' => $user_avatar,
                        'data' => $data,
                        'user_id' => $user_id
                    ));
                } else {
                    //если есть, то обновляем его у клиента
                    //обновляем user_type и data

                    $data_array = json_decode($result['data'], true);

                    if (!empty($user[4])) {
                        $data_array['group_id'] = $user[4];
                    }
                    if ($comment) {
                        $data_array['comments'][$user_type] = $comment;
                    }


                    if ($data_array) {
                        $data = json_encode($data_array, JSON_UNESCAPED_UNICODE);
                    } else {
                        $data = '';
                    }


                    $stmt = $connect->prepare("
                UPDATE
                    vk_imports
                SET
                    {$types_fields_inv[$user_type]} = 1,
                    data = :data,
                    modified = '" . time() . "'
                WHERE
                    profile_id=:profile_id AND user_id=:user_id
                ");
                    $stmt->execute(array(
                        'data' => $data,
                        'profile_id' => $profile_id,
                        'user_id' => $user_id
                    ));
                }
            }
            return array(
                'users_result' => $users_result,
                'inserts_count' => $i
            );
        }
    }
    return null;
}


function prepare_link_to_user($profile_id)
{
    $link = PROFILE_URL_VK . $profile_id;
    return $link;
}
