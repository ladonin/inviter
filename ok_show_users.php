<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic/functions.php');

if (auth_control() !== 1) {
    exit();
}




$show_type_load = @(int) $_GET['show_type_load'];
$show_self_load_users_types = @(int) $_GET['show_self_load_users_types'];
$show_imported_categories = @(int) $_GET['show_imported_categories'];
$show_imported_types = @(int) $_GET['show_imported_types'];
$show_users_reset = @(int) $_GET['show_users_reset'];
$show_users_number = @(int) $_GET['show_users_number'];                                 //$show_users_number=2;////////////////////////////////////////////
if ($show_users_number > 10) {
    $show_users_number = 10;
}

if (!$show_type_load){
    echo 'укажите тип загрузки';
    exit();
}

$is_loaded_status = 0;
$from_collection_status = 0;





?>
<div id="loaded_users_buttons_up">
</div><?php


function ok_show_users_get_loaded_users()
{
    global  $types_fields_inv;
    global $user_id;
    global $connect;
    global $show_self_load_users_types;
    global $show_users_number;

    // берем все записи из таблицы
    $condition = "WHERE is_invited=0 AND user_id=:user_id ";
    if ($show_self_load_users_types > 0) {
        $condition .= " AND {$types_fields_inv[$show_self_load_users_types]}=1";
    }

    $sql = "SELECT * FROM ok_imports " . $condition;
    $stmt = $connect->prepare($sql . " order by id ASC limit " . $show_users_number);
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetchAll();

    // ищем сколько осталось по данному критерию
    $sql = "SELECT count(*) FROM ok_imports $condition";
    $stmt = $connect->prepare($sql);
    $stmt->execute(array('user_id' => $user_id));
    $count = $stmt->fetchColumn() - $show_users_number;
    $count = $count > 0 ? $count : 0;





    //помечаем их как просмотренные
    $list = '';
    foreach($result as $user) {
        $list .= $user['profile_id'] . ',';
    }
    $list = trim($list,',');
    $stmt = $connect->prepare("update ok_imports set is_invited=1, modified = '" . time() . "' where profile_id IN ($list) AND user_id=:user_id");
    $stmt->execute(array('user_id' => $user_id));

return array(
    'result' => $result,
    'count_reserve' => $count
        );
}

// также помечаем их как просмотренные
function ok_show_users_get_imported_users_from_collection()
{
    global  $types_fields_inv;
    global  $show_imported_categories;
    global $user_id;
    global $connect;
    global $show_imported_types;
    global $show_users_number;
    // если импортированы из коллекции
    // берем условия выборки
    // !!! --> заметка
    // 100 000 непросмотренных пользователей с десятизначными ids = (100 000 (количество ids) * 10 (разряды) + 100 000 (запятые) + 100 (сам код)) * 4 (количество байт в символе - это максимум)= 4 МБ
    // max_allowed_packet = обычно равен 16 МБ
    // !!! <-- заметка

    $sql = "SELECT ids_not_invited FROM ok_collections_imports WHERE category_id = $show_imported_categories AND user_id = $user_id";
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
    $sql = "SELECT * FROM ok_collections_$show_imported_categories WHERE $condition  order by id ASC limit " . $show_users_number;





    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();




    // ищем сколько осталось по данному критерию
    $sql = "SELECT count(*) FROM ok_collections_$show_imported_categories WHERE $condition";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $count = $stmt->fetchColumn() - $show_users_number;
    $count = $count > 0 ? $count : 0;











    //помечаем их как просмотренные
    $new_ids_not_invited = array();

    foreach(explode(',',$ids_not_invited) as $id_not_invited) {
        $new_ids_not_invited[$id_not_invited] = $id_not_invited;
    }


    foreach($result as $row) {
        unset($new_ids_not_invited[$row['id']]);
    }
    $new_ids_not_invited = implode(',',$new_ids_not_invited);

    $sql = "UPDATE ok_collections_imports SET ids_not_invited = '$new_ids_not_invited' WHERE category_id = $show_imported_categories AND user_id = $user_id";
    $stmt = $connect->prepare($sql);
    $stmt->execute();


return array(
    'result' => $result,
    'count_reserve' => $count
        );

}















if (!$show_users_reset) {



    //разделяем логику по типам загрузки пользователей
    // если загруженные мной
    if ($show_type_load == 1) {


        $data = ok_show_users_get_loaded_users();
        $result = $data['result'];
        $count_reserve = $data['count_reserve'];

        $is_loaded_status = 1;
    } elseif (($show_type_load == 2) && $show_imported_categories) {
        $data = ok_show_users_get_imported_users_from_collection();
        $result = $data['result'];
        $count_reserve = $data['count_reserve'];

        $from_collection_status = 1;
    } else {
       exit();
    }
    ?>



    <div class="row" id='loaded_users_list'>




    <div class="alert alert-warning" role="alert" style="width: 640px;">Осталось пользователей по данному критерию: <?php echo($count_reserve);?></div>







<?php if (!empty($_SESSION['ok']['last_viewed_users'])) { ?>
<div style="padding-bottom:10px;">
  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
    Предыдущие пользователи
  </button>
</div>
<div class="collapse" id="collapseExample">

            <div class="row">
                <div class="list-group col-md-5" style="padding-right:0; width: 640px; border-radius: 4px;border: 1px solid #337ab7; padding: 10px;background-color: #fff;">
                    <?php
                    foreach ($_SESSION['ok']['last_viewed_users'] as $last_viewed_user) {
                        echo($last_viewed_user);
                    }
                    ?>
                    <div class="pull-left"><i class="glyphicon glyphicon-arrow-up"></i><span style='margin-left:10px;'>Предыдущие пользователи</span></div>
                    <div class="pull-right" style="cursor:pointer" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><span style='margin-left:10px; color:#337ab7'>свернуть</span></div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <?php
            //затем их затираем, чтобы новых записать
            $_SESSION['ok']['last_viewed_users'] = null;

        ?>
</div>
<?php } ?>



<div style="padding-bottom:10px;">
</div>














    <?php if ($result) { ?>



            <div class="row">
                <script>var links = new Array();</script>
                <div class="list-group col-md-5" style="padding-right:0; width: 640px; border-radius: 4px;border: 1px solid #ddd; padding: 10px;background-color: #fff;">
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
            '';
            $type_images = '';



            $user_types = get_types_array($user);

            $data_array = json_decode($user['data'], true);



            foreach ($user_types as $user_type) {

                $user_type_link = '';

                if ($is_loaded_status == 1) {
                    if ($user_type == 2) {
                        if (!empty($data_array['group_id'])) {
                            $user_type_link = 'https://ok.ru/group/' . $data_array['group_id'];
                        }
                    }
                } else {

                    if (!empty($data_array['urls'][$user_type])) {
                        $user_type_link = $data_array['urls'][$user_type];
                    }
                }





                if ($user_type_link) {
                    $type_images .= '<a href="' . $user_type_link . '" target="_blank">';
                }
                $type_images .= '<img src="/img/' . get_type_code_by_id($user_type) . '.png" width="25" style="margin-left:10px; margin-bottom:10px;" data-toggle="tooltip" data-placement="top" title="' . get_type_name_by_id($user_type) . '">';
                if ($user_type_link) {
                    $type_images .= '</a>';
                }
            }




            $type_comments = '';
            $data_comments = (!empty($data_array['comments'])) ? $data_array['comments'] : array();

            foreach ($data_comments as $key_type => $data_comment) {
                $type_comments .= '<div style="padding-top:5px">';


                if (count($user_types) > 1) {




                    $type_comments .= '<img src="/img/' . get_type_code_by_id($key_type) . '.png" width="20" style="margin-right:10px;" data-toggle="tooltip" data-placement="top" title="' . get_type_name_by_id($key_type) . '">';
                }

                $type_comments .= '<span style="color:#333">' . $data_comment . '</span></div>';
            }
            $type_comments = $type_comments ? '<div style="border-top:0px solid #ddd; margin:10px 0"></div><div class="text-left"><b style="color:#555555;">мои комментарии:</b>' . $type_comments . '</div>' : '';






            $avatar = $user['user_avatar'] ? $user['user_avatar'] : '/img/no-photo.png';
            $border_color = $from_collection_status ? '#bc6060' : '#6085bc';


            ob_start();


            ?>



                        <div class="row" style="border-left: 5px solid <?= $border_color ?>;">
                            <div class="col-xs-10" style="padding-left:0">
                                <a target="_blank" style="
                                   color:#337ab7 !important;
                                   border-radius: 0;
                                   border: 0;
                                   padding: 10px;
                                   " href="<?= $link; ?>" class="list-group-item" onclick="window.open('<?= $link; ?>', '_blank', 'left=300, top=100, width=900, height=800');
                                           return false;">

                                    <div class="media">
                                        <div class="media-left">
                                            <img class="media-object" style="width:50px; border-radius:5px;" src="<?= $avatar; ?>" title="<?= $user['user_fio']; ?>">
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading" style="margin-top: 5px;"><?= ($user['user_fio'] ? : $user['profile_id']); ?></h4>
                        <?= $type_comments; ?>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-xs-2 text-right" style="padding-right:10px; padding-top:10px">
            <?= $type_images; ?>
                            </div>
                        </div>
                        <div style="border-top:1px solid #ddd; margin:10px 0"></div>

                        <?php
                        $_SESSION['ok']['last_viewed_users'][] = ob_get_contents();
                        ?>


                        <script>links.push('<?= $link; ?>');</script><?php


            ob_end_flush();
        }







        ?>

                    <script>

                        function open_ok_users() {

                            var time = 1;
                            for (index = 0; index < links.length; index++) {
                                var url = links[index];
                                setTimeout(function (url) {
                                    window.open(url, '_blank', 'left=300, top=100, width=900, height=800');
                                }, time, url);
                                time += 500;
                            }
                        }
                    </script>
                    <a style="cursor:pointer; border-radius:0; margin-top:10px; border:0; text-align:center;" class="list-group-item list-group-item-success" onclick='open_ok_users();'>Открыть всех</a>
                </div>
            </div>
            <!--<div class="alert alert-warning" role="alert" style="width: 640px;">Внимание! Пользователи выше выводятся только один раз.</div>-->
                    <?php
                }
                ?>
    </div>
                <?php }
            ?>

<?php

require('generic/ok_show_users.php');
?>
