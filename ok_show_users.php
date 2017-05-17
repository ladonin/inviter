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




$show_type_load = !empty($_GET['show_type_load']) ? (int)$_GET['show_type_load'] : null;
$show_self_load_users_types = !empty($_GET['show_self_load_users_types']) ? (int)$_GET['show_self_load_users_types'] : null;
$show_imported_categories = !empty($_GET['show_imported_categories']) ? (int)$_GET['show_imported_categories'] : null;
$show_imported_types = !empty($_GET['show_imported_types']) ? (int)$_GET['show_imported_types'] : null;
$show_users_reset = !empty($_GET['show_users_reset']) ? (int)$_GET['show_users_reset'] : null;
$show_users_number = !empty($_GET['show_users_number']) ? (int)$_GET['show_users_number'] : 5;
if($show_users_number>10) {$show_users_number=10;}


if (!$show_users_reset) {
$sql = "SELECT * FROM ok_imports WHERE is_invited=0 AND user_id=:user_id ";

    if ($show_type_load == 2) {
        $sql .= " AND is_imported=0";

        if ($show_self_load_users_types != 1) {
            $sql .= " AND user_type=" . $show_self_load_users_types;
        }
    } elseif($show_type_load == 3) {
        $sql .= " AND is_imported=1";

        if ($show_imported_categories != 1) {
            $sql .= " AND category_id=" . $show_imported_categories;
        }
        if ($show_imported_types != 1) {
            $sql .= " AND user_type=" . $show_imported_types;
        }
    }


        //$sql = "SELECT * FROM $invite_table WHERE is_invited=0 order by id ASC limit 10";
        $stmt = $connect->prepare($sql . " order by id ASC limit " . $show_users_number);
        $stmt->execute(array('user_id' => $user_id));
        $result = $stmt->fetchAll();




        ?>

        <div class="row">
        <div class="row" id='loaded_users_list'>
            <div class="alert alert-warning col-md-5" role="alert" style="width: 640px;">Внимание! Пользователи ниже выводятся только один раз.</div>
            </div>

                <?php
                if ($result) { ?>



            <div class="row">
            <script>var links = new Array();</script>
            <div class="list-group col-md-5" style="padding-right:0; width: 640px;">
                <?php



                foreach ($result as $user) {
                    $link = 'https://ok.ru/';
                    // если профиль только из цифр
                    if (ctype_digit($user['profile_id'])) {
                        $link .= 'profile/' . $user['profile_id'];
                    } else {
                        //если пользователь задал себе имя в url
                        $link .= $user['profile_id'];
                    }



                    $type_name = get_type_name_by_id($user['user_type']);


                    echo('<a target="_blank" style="color:#337ab7 !important" class="list-group-item" href="' . $link . '" onclick="window.open(\'' . $link . '\',\'_blank\',\'left=300, top=100, width=900, height=800\'); return false">

<div class="media">

  <div class="media-left">
      <img class="media-object" style="width:50px; border-radius:5px;" src="' . $user['user_avatar'] . '" title="' . $user['user_fio'] . '">
  </div>

  <div class="media-body">
    <h4 class="media-heading">' . ($user['user_fio'] ?: $user['profile_id'])  . '</h4>
    <div class="text-muted">' . $type_name . '</div>
  </div>
</div>
                    </a><script>links.push(\'' . $link . '\');</script>');

                    $stmt = $connect->prepare("update ok_imports set is_invited=1, modified = '".time()."' where profile_id = :profile_id AND user_id=:user_id");
                    $stmt->execute(array('profile_id' => $user['profile_id'], 'user_id' => $user_id));
                }
                ?>

                    <script>

                        function open_ok_users() {

                            var time=1;
                            for (index = 0; index < links.length; index++) {
                                var url = links[index];
                                setTimeout(function(url){
                                    window.open(url, '_blank', 'left=300, top=100, width=900, height=800');
                                    }, time, url);
                                time += 500;
                            }
                        }
                    </script>
                    <a style="cursor:pointer" class="list-group-item list-group-item-success" onclick='open_ok_users();'>Открыть всех</a>
</div>
            </div>
                    <?php
                }
                ?>
            </div>
        <?php
}
$button_1_added_text = ' следующие ';
require('generic/ok_show_users.php');