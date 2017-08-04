<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Получить подписчиков в группу';
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic' . MY_DS . 'actions.php');
require_once('generic/functions.php');
$invite_table = 'ok_imports';
include('generic/auth_control.php');

function get_type_name($user_type, $excel = false)
{


    if ($excel) {
        if ($user_type == 1) {
            return 'Klass!';
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
        return 'поставил "Класс"';
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
// Экспорт в csv всех пользователей этого логина















if (isset($_POST['export_users'])) {

    if ($_POST['load_type'] == 1) {
        # Для загруженного мной
        $stmt = $connect->prepare("SELECT * FROM ok_imports WHERE user_id=$user_id order by id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $file_name = 'reports/ok_self_loaded_' . uniqid() . $user_id . '.csv';
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
                    'https://ok.ru/profile/' . $row['profile_id'],
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
                    'https://ok.ru/profile/' . $row['profile_id'],
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

    } else if (($_POST['load_type'] == 2) && ($category = (int)$_POST['category'])) {
        # Для импортированного из коллекции

        $stmt = $connect->prepare("SELECT ids,ids_not_invited FROM ok_collections_imports WHERE user_id=$user_id AND category_id=$category");
        $stmt->execute();
        $result = $stmt->fetch();

        $ids_not_invited_array = explode(',',$result['ids_not_invited']);
        $ids_condition = Kits_Converter::convert_to_intenvals($result['ids'], '', false);
        $ids_condition = $ids_condition['sql_condition'];


        $stmt = $connect->prepare("SELECT * FROM ok_collections_$category WHERE $ids_condition order by id DESC LIMIT " . MY_REPORT_USERS_COLLECTION_LIMIT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $file_name = 'reports/ok_imported_' . uniqid() . $user_id . '.csv';
        $fp = fopen($file_name, 'w');

        if ($_POST['report_type'] == 1) {
            // Excel
            fputcsv($fp, array(
                'Date import',
                'Url',
                'Is shown'), ';');
            foreach ($result as $key => $row) {
                $is_invited = !in_array($row['id'],$ids_not_invited_array);
                fputcsv($fp, array(
                    date("H:i:s d.m.Y", $row['created']),
                    'https://ok.ru/profile/' . $row['profile_id'],
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
                $is_invited = !in_array($row['id'],$ids_not_invited_array);
                fputcsv($fp, array(
                    date("H:i:s d.m.Y", $row['created']),
                    'https://ok.ru/profile/' . $row['profile_id'],
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
        $stmt = $connect->prepare("SELECT * FROM $invite_table WHERE profile_id=:profile_id AND user_id=:user_id");
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


            if ($data_array){
                 $data = json_encode($data_array, JSON_UNESCAPED_UNICODE);
            } else {
                $data = '';
            }


            $stmt = $connect->prepare("INSERT into $invite_table (

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


            if($data_array) {
                $data = json_encode($data_array, JSON_UNESCAPED_UNICODE);
            } else {
                $data = '';
            }


            $stmt = $connect->prepare("
                UPDATE
                    $invite_table
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
}



include('generic/header.php');
?>

<div class="well" style="text-align:center; margin-bottom:0; padding:10px; background-color: #ffebd6;">
    <div class="row">
        <div class="pull-left">
            <a href="/facebook<?php // echo($client_code); ?>"><img src="/img/fb_logo.jpg" width="50" style="opacity:0.5"></a>
            <img src="/img/ok_logo.jpg" width="50">
        </div>
        <div class="pull-right">
<?php include('generic/user_data.php'); ?>
        </div>
    </div>
</div>


<div class="row">
    <div class="pull-left" style="margin:10px;">
        <h5><b>ОДНОКЛАССНИКИ</b></h5>
    </div>
    <div style="margin:10px;">

        <?php $non_instruction = 1; require('generic/ok_instruction_menu.php'); ?>

    </div>
</div>





<div class="well well-lg" style="padding-top:10px !important;padding-bottom:10px !important; margin:0 !important;">
    <h3>Пригласить пользователей</h3><br>
    <div id='show_users_block'>
    <?php $button_1_added_text = ''; require('generic/ok_show_users.php'); ?>
    </div>
</div>







<?php $client_imported_categories = get_client_imported_categories(); ?>
<div class="well well-lg" style="padding-top:10px !important;padding-bottom:10px !important;  margin-bottom:0px !important; margin-top:20px !important;">
    <h3>Скачать отчет</h3>
    <br>
    <div style="width: 640px;">
        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a style="cursor:pointer" id='report_self_loaded_nav'>Загруженные мной</a></li>
            <?php if ($client_imported_categories) { ?>
                <li role="presentation"><a style="cursor:pointer"  id='report_collection_loaded_nav'>Взятые из коллекции</a></li>
            <?php } ?>
        </ul>
        <div class="bs-block">
            <div id="report_self_loaded_block">
                <div style="display:inline-block; width:200px; margin-right: 10px;margin-bottom: 5px;">Формат файла</div>
                <br>
                <form action='' method="post" style="margin-bottom: 0px;" class="form-inline">
                    <input type="hidden" name="load_type" value="1">
                    <select class="form-control" name="report_type" style="width:200px; margin-right: 10px;">
                        <option value="1">для Excel</option>
                        <option value="2">для CSV редакторов</option>
                    </select><button
                        name='export_users' type="submit" class="btn btn-info"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Скачать</button>
                </form>
            </div>
            <?php if ($client_imported_categories) { ?>
                <div id="report_collection_loaded_block" style="display:none">
                    <div style="display:inline-block; width:200px; margin-right: 10px;;margin-bottom: 5px;">Формат файла</div>
                    <div style="display:inline-block;;margin-bottom: 5px;">Категория</div>
                    <br>
                    <form action='' method="post" style="margin-bottom: 0px;" class="form-inline">
                        <input type="hidden" name="load_type" value="2">
                        <select class="form-control" name="report_type" style="width:200px; margin-right: 10px;">
                            <option value="1">для Excel</option>
                            <option value="2">для CSV редакторов</option>
                        </select><select
                            class="form-control" name="category" style="width:200px; margin-right: 10px;">
                            <?php foreach ($client_imported_categories as $client_imported_category) { ?>
                                <option value='<?php echo($client_imported_category['category_id']); ?>'><?php echo($client_imported_category['name']); ?></option>
                            <?php } ?>
                        </select><button
                            name='export_users' type="submit" class="btn btn-info"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Скачать</button>
                    </form>

                </div>
            <?php } ?>
        </div>
    </div>

</div>






























<div class="well well-lg" style="padding-top:10px !important;padding-bottom:10px !important;  margin-bottom:0px !important; margin-top:20px !important;">
    <h3>Импорт пользователей<span style="margin-left:20px" class="btn btn-primary" data-toggle="modal" data-target="#collectionsModal">Взять из готовой коллекции</span></h3><br>










<?php
if (isset($_POST['html_text']) && $_POST['html_text'] && $_POST['type_users']) {
    ?>

        <div class="col-md-4" style="padding-left:0 !important">


            <ul class="list-group">

                <li class="list-group-item list-group-item-info">
                    <span class="badge" style="background-color: #FFF;color:rgb(30, 30, 203);"><?php echo(count($users_result)); ?></span>
                    Найдено пользователей
                </li>

                <li class="list-group-item list-group-item-success">
                    <span class="badge" style="background-color: #FFF;color: #000;"><?php echo($i); ?></span>
                    Добавлено пользователей
                </li>

                <li class="list-group-item list-group-item-warning">
                    <span class="badge" style="background-color: #FFF;color:rgb(207, 38, 38);"><?php echo((count($users_result) - $i)); ?></span>
                    Обновлено пользователей
                </li>


            </ul></div>
<?php }
?>



    <table width="100%">
        <tr>
            <td width="50%" align="left" valign="top">
                <h4>HTML код</h4>
                <form action='' method="post">


                    <textarea cols="80" rows="5" name="html_text" class="form-control"></textarea>

                    <br>
                    <h4>Откуда взят HTML код:</h4>

<?php require('generic' . MY_DS . 'ok_users_types.php'); ?>
                    <div class="radio" style="display: block;">
                        <label>
                            <input type="radio" required value='3' name="type_users" data-name="Пользователи из результата поиска">
                            Пользователи из результата поиска
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" required value='4' name="type_users" data-name="Участники группы - мобильная версия сайта - http://m.ok.ru">
                            Участники группы - мобильная версия сайта - http://m.ok.ru
                        </label>
                    </div>
                    <br>




                    <h4>Комментарий:</h4>
                    <input type="text"  maxlength="50" name="comment" class="form-control" style="max-width:500px">
                    <br>







                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Импорт</button>

                </form>
            </td>
        </tr>
    </table>
</div>


















<!-- Modal -->
<div class="modal fade" id="collectionsModal" tabindex="-1" role="dialog" style="text-align:left;">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0;">
            <div class="modal-header" style="background-color:#4C77AF; color:#fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;
                        opacity: 1;
                        border: 0;
                        font-weight: 400;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Перенести пользователей из готовой коллекции</h4>
            </div>
            <div class="modal-body">




                <form action='' method="post"  id='collection_import_form' class="form-inline">

                    <h4>Категория:</h4>
                    <select required name="category_id" id='collection_category_selector' class="form-control" style="width:400px;">
                        <option value="0">Выберите категорию...
<?php
$stmt = $connect->prepare("SELECT * FROM ok_collections_categories order by name ASC");
$stmt->execute();
$categories = $stmt->fetchAll();
foreach ($categories as $key => $category) {
    ?>
                            <option value="<?php echo($category['id']); ?>" data-name="<?php echo($category['name']); ?>"><?php echo($category['name']); ?>
<?php }
?>

                    </select>

                    <div style="padding-top:10px">
<?php require('generic' . MY_DS . 'ok_users_types_checkbox.php'); ?>
                    </div>

                    <div id="collection_category_func_buttons" style="padding-top:5px;">
                        <div id="ok_get_category_type_users_count" style="margin-bottom: 10px;color: #4c77af;font-size: 21px;"></div>
                        <div id="ok_get_category_type_user_cost" style="margin-bottom: 10px;font-size: 16px;"></div>
                        <button type="submit" disabled class="btn btn-success" id="collection_importer">Импортировать</button>
                        <div class="form-group"><input style="width:100px;" id="collection_importer_count" class="form-control " value="" type="text">
                            <label for="collection_importer_count" id="collection_importer_count_people">человек</label> <b style="color:#4c77af" id="collection_importer_cost"></b>
                        </div>
                    </div>
                </form>


                <div style="margin-top:10px">
                    <img src="/img/balance.png" width="20"> <b style="color:#636363;">Баланс:</b> <big id='balance_import'></big> <a style="cursor:pointer;" data-toggle="collapse" data-target="#balance_import_expander">пополнить</a>

                    <div id="balance_import_expander" style="margin-top:10px" class="collapse">
<?php require('generic' . MY_DS . 'balance_deposit.php'); ?>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>



<script>



$( document ).ready(function(){

    var show_users_reset = 0;





    $(document).on('click', '#reset_users_list', function () {
        show_users_reset = 1;
        reset_users_list();
    });







    $('#report_self_loaded_nav').click(function () {
        $('#report_collection_loaded_nav').closest('li').removeClass('active');
        $('#report_self_loaded_nav').closest('li').addClass('active');

        $('#report_self_loaded_block').show();
        $('#report_collection_loaded_block').hide();
    });

    $('#report_collection_loaded_nav').click(function () {
        $('#report_collection_loaded_nav').closest('li').addClass('active');
        $('#report_self_loaded_nav').closest('li').removeClass('active');

        $('#report_self_loaded_block').hide();
        $('#report_collection_loaded_block').show();
    });



function reset_users_list() {

        $.ajax({
            url: "/ok_reset_users_list.php",
            data: {
            }
        }).done(function (data) {
            $("#show_users_block").html(data);
            $('#show_type_load').trigger('change');
            $('#show_imported_categories').trigger('change', ['reset_users_list']);
            show_users_reset = 0;
        });
}




function alert_about_change_selection_users_view(step,status){

    if (typeof(status) == 'undefined') {
        status = 'none';
    }








    var show_type_load = $("#show_type_load").val();
    var show_self_load_users_types = $("#show_self_load_users_types").val();


    var show_self_load_users_types_text = $("#show_self_load_users_types :selected").text();
    var show_imported_categories_text = $("#show_imported_categories :selected").text();






    if (step==1) {
        if (status_types_load_users_not_changed === false) {

            // если тип загрузки изменен
            // и теперь равен html
            if (show_type_load==1) {
                swal({
                    title: "Внимание",
                    text: 'Пользователи, <b style="color: #bc6060;">загруженные из коллекции</b> закончились.<br><br>Далее будут показываться пользователи, <br><b style="color: #6085bc;">загруженные вами</b>. <br><br>Выбранный по умолчанию тип пользователей: <br><b>' + show_self_load_users_types_text + '</b>',
                    html: true,
                    type: "warning"
                });
                return;
            }
        }
        // если тип пользователей изменен и тип загрузки == html
        if (((status_self_load_users_type_not_changed === false)
                || ((get_requested_show_self_load_users_types === '0' && show_self_load_users_types > 0))) && show_type_load==1) {
                swal({
                    title: "Внимание",
                    text: 'Произошла смена типа пользователей на: <br><b>' + show_self_load_users_types_text + '</b>',
                    html: true,
                    type: "warning"
                });
                return;
        }




    }


    // если после обновления списка пользователей (status === 'load_uses_list') и обновления типа пользователей (step ==2) для импортнутых ихз колллекции
    if ((step ==2) && (status === 'load_uses_list')) {
        var show_imported_categories_text = $("#show_imported_categories :selected").text();
        var show_imported_types_text = $("#show_imported_types :selected").text();


        if (status_types_load_users_not_changed === false) {
            if (show_type_load==2) {
                swal({
                    title: "Внимание",
                    text: 'Пользователи, <b style="color: #6085bc;">загруженные вами</b> закончились.<br><br>Далее будут показываться пользователи,<br> <b style="color: #bc6060;">загруженные из коллекции</b>.<br><br>Выбранная по умолчанию категория: <br><b>' + show_imported_categories_text + '</b><br><br>Выбранный по умолчанию тип пользователей: <br><b>' + show_imported_types_text + '</b>',
                    html: true,
                    type: "warning"
                });
            }
        }

        // если изменилась категория и тип загрузки == 2
        if ((status_client_imported_enabled_category_not_changed === false) && show_type_load==2) {
                swal({
                    title: "Внимание",
                    text: 'Произошла смена категории на: <br><b>' + show_imported_categories_text + '</b><br><br>Выбранный по умолчанию тип пользователей: <br><b>' + show_imported_types_text + '</b>',
                    html: true,
                    type: "warning"
                });
                return;
        }

        // если изменился тип пользователей и тип загрузки == 2
        if ((status_types_load_users_by_collection_not_changed === false)  && show_type_load==2) {
                swal({
                    title: "Внимание",
                    text: 'Произошла смена типа пользователей на: <br><b>' + show_imported_types_text + '</b>',
                    html: true,
                    type: "warning"
                });
                status_types_load_users_by_collection_not_changed = null;
                return;
            }


    }




}







    function load_uses_list() {

        var show_type_load = $("#show_type_load").val();
        var show_self_load_users_types = $("#show_self_load_users_types").val();
        var show_imported_categories = $("#show_imported_categories").val();
        var show_imported_types = $("#show_imported_types").val();
        var show_users_number = $("#show_users_number").val();


        if (!show_type_load) {
            show_type_load = 0;
        }


        $.ajax({
            url: "/ok_show_users.php",
            data: {
                'show_type_load': show_type_load,
                'show_self_load_users_types': show_self_load_users_types,
                'show_imported_categories': show_imported_categories,
                'show_imported_types': show_imported_types,
                'show_users_reset': show_users_reset,
                'show_users_number': show_users_number
            }
        }).done(function (data) {
            $("#show_users_block").html(data);


            alert_about_change_selection_users_view(1);





            $('#show_type_load').trigger('change');
            $('#show_imported_categories').trigger('change', ['load_uses_list']);


  $('[data-toggle="tooltip"]').tooltip();
  $('#loaded_users_buttons_up').html($('#loaded_users_buttons_down').html());
    $('#loaded_users_buttons_down').html('');




            show_users_reset = 0;
        });
    }




    $(document).on('click', '#show_users', function () {

        load_uses_list();

    });


    $(document).on('change', '#show_type_load', function () {
        var type_load = $(this).val();

        if (type_load == 1) {
            $('#show_self_load_users_types').closest('div').show();
            $('#show_imported_categories').closest('div').hide();
            $('#show_imported_types').closest('div').hide();
        } else if (type_load == 2) {
            $('#show_self_load_users_types').closest('div').hide();
            $('#show_imported_categories').closest('div').show();
            $('#show_imported_types').closest('div').show();
            $('#show_imported_categories').trigger('change', ['show_type_load']);
        }
    });








    $(document).on('change', '#show_imported_types', function () {
        setcookie('show_imported_type', $(this).val());
    });



    $(document).on('change', '#show_imported_categories', function (event, status) {


    if (typeof(status) == 'undefined') {
        status = 'none';
    }

        if ($("#show_type_load").val() != 2){
            return false;
        }



        var imported_category = parseFloat($(this).val());
        if (!(imported_category)) {
            return false;
        }
        //var current_type = $("#show_imported_types").val();
        $.ajax({
            url: "/ok_get_imported_types_users_by_category.php",
            data: {
                'category_id': imported_category,
            }
        }).done(function (data) {
            var result = JSON.parse(data);
            $('#show_imported_types').remove();

            if (result) {
                var select = $("<select></select>").attr("id", 'show_imported_types').attr("class", 'form-control');
                if (result.length > 1) {select.append("<option value='0'>Все пользователи</option>");}
                $("#block_imported_types").append(select);
                var old_type = getCookie('show_imported_type');

                if (status === 'load_uses_list') {
                    // не касается "все пользователи"
                    status_types_load_users_by_collection_not_changed = false;
                }

                $.each(result, function (index, value) {
                    var selected = '';
                    if ((old_type > 0) && (old_type == value[0])) {

                        if (status === 'load_uses_list') {
                            status_types_load_users_by_collection_not_changed = true;
                        }

                        selected = 'selected';
                    }

                    select.append("<option value='"+value[0]+"' " + selected + ">" + value[1] + "</option>");

                });
if (status === 'load_uses_list') {
console.log(getCookie('show_imported_type'));
                // если несколько раз покажем "все пользователи"
                if ((old_type == 0) && (status_types_load_users_by_collection_not_changed === false)) {
                    status_types_load_users_by_collection_not_changed = null;
                }

                // если со "все пользователи" перейдем на конкретный тип
                if ((old_type == 0) && ($('#show_imported_types').val() > 0)) {
                    status_types_load_users_by_collection_not_changed = false;
                }



                $('#show_imported_types').trigger('change');}

            }

if (status === 'load_uses_list') {
alert_about_change_selection_users_view(2, status);
}






        });
    });

    $("#balance_import").html($("#balance").html());





    $("#collection_importer_count").keyup(function () {
        var value;
        if (!$(this).val()) {
            value = 0;
        } else {
            value = $(this).val();
        }
        var users_count = parseFloat(value).toFixed(0);

        var cost = round_cost(users_count * get_ok_import_collection_request_cost_per_one_user());
        var users_max = Number($('#ok_get_category_type_users_count').attr('data-count'));

        if (users_count >= 1) {
            $('#collection_importer_count').val(users_count);
            $("#collection_importer_cost").html('= ' + cost + ' руб.');

            //если не хватает денег или превышено количество запрашиваемых пользователей -->
            if (cost > round_cost($('#balance').html())) {
                $("#collection_importer_cost").css('color', '#e43a3a');
            } else {
                $("#collection_importer_cost").css('color', '#4c77af');
            }

            if (users_count > users_max) {
                $("#collection_importer_count").val(users_max);
            }

            if (cost > round_cost($('#balance').html())) {
                $("#collection_importer").prop('disabled', true);
            } else {
                $("#collection_importer").prop('disabled', false);
            }
            // <--

        } else {
            $("#collection_importer_cost").html('');
            $("#collection_importer").prop('disabled', true);
        }
    });
    $("#collection_importer_count").trigger('keyup');










    $('input[type=checkbox][name=type_users_1],input[type=checkbox][name=type_users_2],input[type=checkbox][name=type_users_5],input[type=checkbox][name=type_users_6]').change(function() {
        $('input[type=checkbox][name=user_type_all]').removeAttr("checked");
});

    $('input[type=checkbox][name=user_type_all]').change(function() {
        $('input[type=checkbox][name=type_users_1],input[type=checkbox][name=type_users_2],input[type=checkbox][name=type_users_5],input[type=checkbox][name=type_users_6]').removeAttr("checked");
});




    $('#collection_category_selector,input[type=checkbox]').change(function () {


        var category_id = $('#collection_category_selector').val();

        var user_type_klass = $('input[type=checkbox][name=type_users_1]:checked', '#collection_import_form').val();
        var user_type_subscriber = $('input[type=checkbox][name=type_users_2]:checked', '#collection_import_form').val();
        var user_type_survey = $('input[type=checkbox][name=type_users_5]:checked', '#collection_import_form').val();
        var user_type_comment = $('input[type=checkbox][name=type_users_6]:checked', '#collection_import_form').val();
        var user_type_all = $('input[type=checkbox][name=user_type_all]:checked', '#collection_import_form').val();



        if (category_id == 0 ||
                (!user_type_klass && !user_type_survey && !user_type_comment && !user_type_subscriber && !user_type_all)) {
            $("#collection_category_func_buttons").hide();
            return false;
        }

        if (user_type_all) {
            user_type_klass = -1;
            user_type_subscriber = -1;
            user_type_survey = -1;
            user_type_comment = -1;
            $('input[type=checkbox][name=type_users_1]').removeAttr("checked");
            $('input[type=checkbox][name=type_users_2]').removeAttr("checked");
            $('input[type=checkbox][name=type_users_5]').removeAttr("checked");
            $('input[type=checkbox][name=type_users_6]').removeAttr("checked");
        }

        $("#collection_category_func_buttons").show();


//$('#ok_get_category_type_users_count').html('<span class="text-muted">идет подсчет...</span>');

        $.ajax({
            url: "/ok_get_category_type_users_count.php",
            data: {
                'category_id': category_id,
                'user_type_1': user_type_klass,
                'user_type_2': user_type_subscriber,
                'user_type_5': user_type_survey,
                'user_type_6': user_type_comment
            }
        }).done(function (data) {
            $('#ok_get_category_type_users_count').html('Доступно: ' + data);
            $('#ok_get_category_type_users_count').attr('data-count', data);

var cost = round_cost(get_ok_import_collection_request_cost_per_one_user());
$('#ok_get_category_type_user_cost').html('Стоимость: ' + cost + ' руб.');
            if (data > 0) {

//$("#collection_importer").prop('disabled', false);

                $("#collection_importer_count").show().val('');
                $("#collection_importer_count_people").show();

            } else {
                $("#collection_importer").prop('disabled', true);
                $("#collection_importer_count").hide().val(0);
                $("#collection_importer_count_people").hide();
            }


            $("#collection_importer_count").trigger('keyup');
        });


    });










    $('#collection_importer').click(function (e) {
        e.preventDefault;




        var users_count = $('#collection_importer_count').val();
        var category_id = $('#collection_category_selector').val();
        //var user_type = $('input[type=radio][name=type_users]:checked', '#collection_import_form').val();
        var category_name = $('#collection_category_selector').find('option:selected').data('name');

        //var user_type_name = $('input[type=radio][name=type_users]:checked').data('name');

        var user_type_klass = $('input[type=checkbox][name=type_users_1]:checked', '#collection_import_form').val();
        var user_type_subscriber = $('input[type=checkbox][name=type_users_2]:checked', '#collection_import_form').val();
        var user_type_survey = $('input[type=checkbox][name=type_users_5]:checked', '#collection_import_form').val();
        var user_type_comment = $('input[type=checkbox][name=type_users_6]:checked', '#collection_import_form').val();
        var user_type_all = $('input[type=checkbox][name=user_type_all]:checked', '#collection_import_form').val();


        if (category_id == 0 ||
                (!user_type_klass && !user_type_survey && !user_type_comment && !user_type_subscriber && !user_type_all)) {
            return false;
        }

        if (user_type_all) {
            user_type_klass = -1;
            user_type_subscriber = -1;
            user_type_survey = -1;
            user_type_comment = -1;
        }


        $.ajax({
            url: "/import_users_from_base.php",
            data: {
                'category_id': category_id,
                'user_type_1': user_type_klass,
                'user_type_2': user_type_subscriber,
                'user_type_5': user_type_survey,
                'user_type_6': user_type_comment,
                'users_count': users_count,
                'user_id': '<?php echo($user_id); ?>',
            }
        }).done(function (data) {

            if (data > 0) {

                $('#collection_category_selector').trigger('change');

                var text = "Импортировано <b>" + data + "</b> человек";
                if ((data > 1) && (data < 5)) {
                    text = "Импортировано <b>" + data + "</b> человека";
                }

                $('#balance').html((parseFloat($('#balance').html()) - data * get_ok_import_collection_request_cost_per_one_user()).toFixed(2));
                $("#balance_import").html($("#balance").html());


                if (!$('#loaded_users_list').length) {
                    show_users_reset = 1;
                    reset_users_list();
                } else {
                    $('#reset_users_list').show();
                }

                var user_type_name = '';
                var types_count = 0;
                var type_text;
                if (user_type_all) {
                    user_type_name = get_type_name_by_id(-1);
                    types_count++;
                } else {
                    if (user_type_klass) {
                        user_type_name += get_type_name_by_id(1) + '<br>';
                        types_count++;
                    }
                    if (user_type_subscriber) {
                        user_type_name += get_type_name_by_id(2) + '<br>';
                        types_count++;
                    }
                    if (user_type_survey) {
                        user_type_name += get_type_name_by_id(5) + '<br>';
                        types_count++;
                    }
                    if (user_type_comment) {
                        user_type_name += get_type_name_by_id(6) + '<br>';
                        types_count++;
                    }
                    user_type_name = user_type_name.slice(0,-4);
                }
                if (types_count>1) {
                    type_text = 'Типы';
                } else {
                    type_text = 'Тип';
                }
                swal({
                    title: "",
                    text: text + ' <br> Категория: <b>' + category_name + '</b><br>'+ type_text + ': <br><b>' + user_type_name + '</b>',
                    html: true,
                    type: "success"
                });
            }
        });

        return false;

    });



    $('#show_type_load').trigger('change');
    $('#show_imported_categories').trigger('change', ['background']);
    $('#collection_category_selector').trigger('change');
});

</script>











<table style="display:none;">
    <tr>
        <td align="center" valign="top" style="padding:10px;">

            <!--LiveInternet counter--><script type="text/javascript">
                document.write("<a href='//www.liveinternet.ru/click' " +
                        "target=_blank><img src='//counter.yadro.ru/hit?t25.2;r" +
                        escape(document.referrer) + ((typeof (screen) == "undefined") ? "" :
                        ";s" + screen.width + "*" + screen.height + "*" + (screen.colorDepth ?
                                screen.colorDepth : screen.pixelDepth)) + ";u" + escape(document.URL) +
                        ";" + Math.random() +
                        "' alt='' title='LiveInternet: показано число посетителей за" +
                        " сегодня' " +
                        "border='0' width='88' height='15'><\/a>")
            </script><!--/LiveInternet-->


        </td>
    </tr>
</table>

<?php include('generic/footer.php'); ?>