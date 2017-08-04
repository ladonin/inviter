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
                //!!!вроде проверил //берем всех, включая и без фото
                preg_match_all("#class=\"photoWrapper\" href=\"/(?:profile/)?(.+?)\"(?:.+?)(?:<img src=\"(.+?)\" alt=(?:.+?))?class=\"o\"(?:.+?)>(.+?)<#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
                $user_type = 1;
            } else if ($_POST['type_users'] == 2) {//group_users
                //!!!вроде проверил //берем всех, включая и без фото
                preg_match_all("#class=\"photoWrapper\"(?:.+?)(?:<img src=\"(.+?)\" alt=(?:.+?))?id=\"hook_ShortcutMenu(?:.+?)<!--{(?:.*?)\"groupId\":\"(.+?)\"(?:.+?)\"userId\":\"(.+?)\"(?:.+?)\"fio\":\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);

                $user_type = 2;
                foreach ($users_result as &$user) {
                    $var1 = $user[1];
                    $var2 = $user[2];
                    $var3 = $user[3];
                    $var4 = $user[4];

                    $user[1] = $var3;
                    $user[2] = $var1;
                    $user[3] = $var4;
                    $user[4] = $var2;
                }
            } else if ($_POST['type_users'] == 3) {//search_results
                //!!!вроде проверил //берем всех, включая и без фото
                preg_match_all("#<div data-l=(?:.+?)(?:<img class=\"photo_img(?:.+?)src=\"(.+?)\" alt=(?:.+?))?<div class=\"hookData(?:.+?)<!--{(?:.*?)\"userId\":\"(.+?)\"(?:.+?)\"fio\":\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
                $user_type = 3;

                foreach ($users_result as &$user) {
                    $var1 = $user[1];
                    $var2 = $user[2];
                    $var3 = $user[3];


                    $user[1] = $var2;
                    $user[2] = $var1;
                    $user[3] = $var3;
                }
            } else if ($_POST['type_users'] == 4) {//group_users mobile
                //!!!вроде проверил //берем всех, включая и без фото
                preg_match_all("#<li class=\"item(?:.+?)st\.groupId=(.+?)&amp;(?:.+?)<a href=\"/dk\?st\.cmd=friendMain&amp;st\.friendId=(.+?)&amp;(?:.+?)<div class=\"clickarea_content\">(?:.+?)<img (?:.+?)src=\"(.+?)\"(?:.+?)\"(?:.+?)<span class=\"emphased usr\">(.+?)</span>#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
                $user_type = 2;

                foreach ($users_result as &$user) {
                    $var1 = $user[1];
                    $var2 = $user[2];
                    $var3 = $user[3];
                    $var4 = $user[4];

                    $user[1] = $var2;
                    $user[2] = $var3;
                    $user[3] = $var4;
                    $user[4] = $var1;
                }
            } else if ($_POST['type_users'] == 5) {//surveys
                //!!!вроде проверил //берем всех, включая и без фото
                preg_match_all("#class=\"photoWrapper\" href=\"/(?:profile/)?(.+?)\"(?:.+?)(?:<img src=\"(.+?)\" alt=(?:.+?))?class=\"o\"(?:.+?)>(.+?)<#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
                $user_type = 5;
            } else if ($_POST['type_users'] == 6) {//comments
                //!!!вроде проверил //берем всех, включая и без фото
                preg_match_all("#<img uid=\"goToUserFromComment\"(?:.+?)data-query=\"\{&quot;userId&quot;:&quot;(.+?)&quot;\}\"(?:.+?)src=\"(.+?)\"(?:.+?)<span(?:.+?) uid=\"goToUserFromComment\"(?:.+?)>(.+?)</span>#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
                $user_type = 6;
            }

            $comment = strip_tags($_POST['comment']);

            $i = 0;
            foreach ($users_result as $user) {
                $profile_id = $user[1];
                $user_avatar = strip_tags($user[2]);
                $user_fio = strip_tags($user[3]);

                $group_id = !empty($user[4]) ? strip_tags($user[4]) : '';
                // replace тут не подойдет
                // есть ли такой пользователь у клиента уже или нет
                $stmt = $connect->prepare("SELECT * FROM ok_imports WHERE profile_id=:profile_id AND user_id=:user_id");
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


                    $stmt = $connect->prepare("INSERT into ok_imports (

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
                    ok_imports
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
    $link = 'https://ok.ru/';
    // если профиль только из цифр
    if (ctype_digit($profile_id)) {
        $link .= 'profile/' . $profile_id;
    } else {
        //если пользователь задал себе имя в url
        $link .= $profile_id;
    }
    return $link;
}
