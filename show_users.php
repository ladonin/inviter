<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');

require_once('generic/generic_functions.php');require_once('generic/net_functions.php');
require_once("generic/{$net_code}_functions.php");

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
<div class="row">
<div id="loaded_users_buttons_up"></div><?php


if (!$show_users_reset) {

    //разделяем логику по типам загрузки пользователей
    // если загруженные мной
    if ($show_type_load == 1) {


        $data = show_users_get_loaded_users();
        $result = $data['result'];
        $count_reserve = $data['count_reserve'];

        $is_loaded_status = 1;
    } elseif (($show_type_load == 2) && $show_imported_categories) {
        $data = show_users_get_imported_users_from_collection();
        $result = $data['result'];
        $count_reserve = $data['count_reserve'];

        $from_collection_status = 1;
    } else {
       exit();
    }
    ?>


<div class="col-xs-12 col-md-6 p-0">
    <div class="row" id='loaded_users_list'>

        <div class="col-xs-12 p-0">


    <div class="alert alert-warning p-10" role="alert" style="/*width: 640px;*/">Осталось пользователей по данному критерию: <b><?php echo($count_reserve);?></b></div>







<?php if (!empty($_SESSION[$net_code]['last_viewed_users'])) { ?>
<div style="padding-bottom:10px;">
  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
    Предыдущие пользователи
  </button>
</div>
<div class="collapse" id="collapseExample">

            <div class="row">
                <div class="list-group col-xs-12" style="padding-right:0; /*width: 640px;*/ border-radius: 4px;border: 1px solid #4380be; padding: 10px;background-color: #fff;">
                    <?php
                    foreach ($_SESSION[$net_code]['last_viewed_users'] as $last_viewed_user) {
                        echo($last_viewed_user);
                    }
                    ?>
                    <div class="pull-left"><i class="glyphicon glyphicon-arrow-up"></i><span style='margin-left:10px;'>Предыдущие пользователи</span></div>
                    <div class="pull-right" style="cursor:pointer" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><span style='margin-left:10px; color:#4380be'>свернуть</span></div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <?php
            //затем их затираем, чтобы новых записать
            $_SESSION[$net_code]['last_viewed_users'] = null;

        ?>
</div>
<?php } ?>
















    <?php if ($result) { ?>



            <div class="row">
                <script>var links = new Array();</script>
                <div class="list-group col-xs-12" style="padding-right:0; /*width: 640px;*/ border-radius: 4px;border: 1px solid #ddd; padding: 10px;background-color: #fff;">
        <?php

$_SESSION[$net_code]['showed_count'][date('z')] = $_SESSION[$net_code]['showed_count'][date('z')] ?? 0;


        foreach ($result as $user) {

$_SESSION[$net_code]['showed_count'][date('z')]++;

$buffer = true;

require('generic/user_in_list.php');




        }










        ?>

                    <script>

                        function open_users() {

                            var time = 1;
                            for (index = 0; index < links.length; index++) {
                                var url = links[index];
                                setTimeout(function (url) {
                                    window.open(url, '_blank', 'left=300, top=100, width=1000, height=800');
                                }, time, url);
                                time += 500;
                            }
                            $('.list-group .user_seen_label').show();
                        }
                    </script>
                    <a style="cursor:pointer; border-radius:0; margin-top:10px; border:0; text-align:center;" class="list-group-item list-group-item-success" onclick='open_users();'>Открыть всех</a>
                </div>
            </div>
            <!--<div class="alert alert-warning" role="alert" style="width: 640px;">Внимание! Пользователи выше выводятся только один раз.</div>-->
                    <?php
                }
                ?>
    </div>
        </div>
                <?php }
            ?>

<?php

require('generic/show_users.php');
?>
    </div>
</div>