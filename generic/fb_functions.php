<?php


function import_users_init()
{
    global $types_fields_inv;
    global $net_code;
    global $connect;
    global $user_id;
    if (isset($_POST['html_text']) && $_POST['html_text'] && $_POST['type_users']) {
        if (isset($_POST['html_text']) && $_POST['html_text']) {
            if ($_POST['type_users'] == 1) {//classes
                preg_match_all("#<li(?:.+?)<a(?:.+?)data-hovercard=\"(?:.+?)\?id=([0-9]+)&(?:.+?)<img(?:.+?)src=\"(.+?)\"(?:.+?)aria-label=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
                $user_type = 1;
            } else if ($_POST['type_users'] == 2) {//group_users
                preg_match_all("#<div(?:.+?)id=\"member_card_([0-9]+)\"(?:.+?)<img(?:.+?)src=\"(.+?)\"(?:.+?)aria-label=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
                $user_type = 2;
            } else if ($_POST['type_users'] == 3) {//search_results
                preg_match_all("#<li(?:.+?)<img(?:.+?)src=\"(.+?)\"(?:.+?)alt=\"(.+?)\"(?:.+?)id=\"friend_browser_unit_(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
                $user_type = 3;

                foreach ($users_result as $key => $user) {
                    $users_result[$key][1] = $user[3];
                    $users_result[$key][2] = $user[1];
                    $users_result[$key][3] = $user[2];
                }
            } else if ($_POST['type_users'] == 4) {//group_users mobile
                exit();
                //!!!вроде проверил //берем всех, включая и без фото
                //preg_match_all("#<li class=\"item(?:.+?)st\.groupId=(.+?)&amp;(?:.+?)<a href=\"/dk\?st\.cmd=friendMain&amp;st\.friendId=(.+?)&amp;(?:.+?)<div class=\"clickarea_content\">(?:.+?)<img (?:.+?)src=\"(.+?)\"(?:.+?)\"(?:.+?)<span class=\"emphased usr\">(.+?)</span>#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
                $user_type = 2;

            } else if ($_POST['type_users'] == 5) {//surveys
                preg_match_all("#<li(?:.+?)<a(?:.+?)data-hovercard=\"(?:.+?)\?id=([0-9]+)&(?:.+?)<img(?:.+?)src=\"(.+?)\"(?:.+?)aria-label=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
                $user_type = 5;
            } else if ($_POST['type_users'] == 6) {//comments

preg_match_all("#<div(?:.+?)id=\"comment_js_(?:.+?)<a(?:.+?)data-hovercard=\"(?:.+?)\?id=([0-9]+)&(?:.+?)<img(?:.+?)alt=\"(.+?)\"(?:.+?)src=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
                $user_type = 6;

                foreach ($users_result as $key => $user) {
                    $users_result[$key][1] = $user[1];
                    $users_result[$key][2] = $user[3];
                    $users_result[$key][3] = $user[2];
                }
            }

            $comment = strip_tags($_POST['comment']);

            $i = 0;
            foreach ($users_result as $user) {
                $profile_id = $user[1];
                $user_avatar = strip_tags($user[2]);
                $user_avatar = str_replace('&amp;', '&', $user_avatar);

                if ($user_avatar[0] == '/') {
                    $user_avatar = 'https://facebook.com' . $user_avatar;
                }

                $user_fio = strip_tags($user[3]);

                $group_id = !empty($user[4]) ? strip_tags($user[4]) : '';
                // replace тут не подойдет
                // есть ли такой пользователь у клиента уже или нет
                $stmt = $connect->prepare("SELECT * FROM fb_imports WHERE profile_id=:profile_id AND user_id=:user_id");
                $stmt->execute(array('profile_id' => $profile_id, 'user_id' => $user_id));

                $result = $stmt->fetch();
                if (!$result) {
                    //если нет, то просто записываем его клиенту
                    $i++;

                    //тут он будет писаться с нуля
                    $data_array = array();
                    if ($group_id) {
                        $data_array['group_id'] = $group_id;
                    }
                    if ($comment) {
                        $data_array['comments'][$user_type] = $comment;
                    }


                    if ($data_array) {
                        $data = json_encode($data_array, JSON_UNESCAPED_UNICODE);
                    } else {
                        $data = '';
                    }


                    $stmt = $connect->prepare("INSERT into fb_imports (

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

                    if ($group_id) {
                        //обновляем группу
                        $data_array['group_id'] = $group_id;
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
                    fb_imports
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
    $link = 'https://www.facebook.com/profile.php?id=' . $profile_id;
    return $link;
}
