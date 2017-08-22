<?php
function publish_temp_init(){

    global $net_code;
    global $connect;
    global $nets;


$iii=0;
        // по каждой соцсети
        foreach($nets as $net) {
            // по каждой категории
            $stmt = $connect->prepare("SELECT * FROM {$net}_collections_categories");
            $stmt->execute();
            $categories = $stmt->fetchAll();
            foreach($categories as $category) {
                // берем всех пользователей категории
                $stmt2 = $connect->prepare("SELECT * FROM {$net}_collections_{$category['id']}");////////////////
                $stmt2->execute();
                $users = $stmt2->fetchAll();
                foreach($users as $user) {

$iii++;
                    $stmt = $connect->prepare("INSERT into {$net}_collections_temp_{$category['id']} (
                            profile_id,
                            user_fio,
                            user_avatar,
                            is_comment,
                            is_survey,
                            is_subscriber,
                            is_klass,
                            is_repost,
                            data,
                            created

                        ) VALUES(
                            :profile_id,
                            :user_fio,
                            :user_avatar,
                            :is_comment,
                            :is_survey,
                            :is_subscriber,
                            :is_klass,
                            :is_repost,
                            :data,
                            '" . time() . "'
                        )");
                        $stmt->execute(array(
                            'profile_id' => $user['profile_id'],
                            'user_fio' => $user['user_fio'],
                            'user_avatar' => $user['user_avatar'],
                            'is_comment' => $user['is_comment'],
                            'is_survey' => $user['is_survey'],
                            'is_subscriber' => $user['is_subscriber'],
                            'is_klass' => $user['is_klass'],
                            'is_repost' => $user['is_repost'],
                            'data' => $user['data']
                        ));


                }
            }
        }
        my_pre('все ' . $iii);
    }