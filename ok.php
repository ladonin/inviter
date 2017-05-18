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

    $stmt = $connect->prepare("SELECT t1.*, t2.name as category_name FROM $invite_table t1
        LEFT JOIN ok_collections_categories t2 ON t1.category_id = t2.category_id


        WHERE user_id=:user_id order by is_invited DESC, modified DESC, is_imported ASC, category_id ASC, user_type ASC, id ASC");
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetchAll();
    $file_name = 'reports/odnoklassniki_' . $user_id . '.csv';
    $fp = fopen($file_name, 'w');
    fputcsv($fp, array(
        'Номер',
        'Дата сохранения',
        'Ссылка на профиль',
        'ФИО',
        'Тип пользователя',
        'Взят из коллекции',
        'Категория',
        'Показан?',
        'Дата показа'), ';');

    foreach ($result as $key => $row) {
        fputcsv($fp, array(
            $key + 1,
            date("Y.m.d H:i:s", $row['created']),
            'https://ok.ru/profile/' . $row['profile_id'],
            $row['user_fio'],
            get_type_name($row['user_type']),
            $row['is_imported'] ? 'Да' : ' ',
            $row['category_name'] ? : ' ',
            $row['is_invited'] ? 'Да' : ' ',
            !$row['is_invited'] ? ' ' : ($row['modified'] ? date("Y.m.d H:i:s", $row['modified']) : '')
                ), ';'
        );
    }
    fclose($fp);

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=" . $file_name);
    header("Content-Length: " . filesize($file_name));
    readfile($file_name);
    exit();
}






if (isset($_POST['export_users_excel'])) {

    $stmt = $connect->prepare("SELECT t1.*, t2.name_en as category_name FROM $invite_table t1
        LEFT JOIN ok_collections_categories t2 ON t1.category_id = t2.category_id

        WHERE user_id=:user_id order by is_invited DESC, modified DESC, is_imported ASC, category_id ASC, user_type ASC, id ASC");
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetchAll();
    $file_name = 'reports/odnoklassniki_' . $user_id . '.csv';
    $fp = fopen($file_name, 'w');
    fputcsv($fp, array(
        'Number',
        'Date upload',
        'Url',
        'Type',
        'From collection',
        'Category',
        'Is shown',
        'Date show'), ';');

    foreach ($result as $key => $row) {
        fputcsv($fp, array(
            $key + 1,
            date("Y.m.d H:i:s", $row['created']),
            'https://ok.ru/profile/' . $row['profile_id'],
            get_type_name($row['user_type'], true),
            $row['is_imported'] ? '+' : ' ',
            $row['category_name'] ? : ' ',
            $row['is_invited'] ? '+' : ' ',
            !$row['is_invited'] ? ' ' : ($row['modified'] ? date("Y.m.d H:i:s", $row['modified']) : '')
                ), ';'
        );
    }
    fclose($fp);

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=" . $file_name);
    header("Content-Length: " . filesize($file_name));
    readfile($file_name);
    exit();
}

if (isset($_POST['html_text']) && $_POST['html_text']) {
    if ($_POST['type_users'] == 1) {//classes
        preg_match_all("#class=\"photoWrapper\" href=\"/(?:profile/)?(.+?)\"(?:.+?)<img src=\"(.+?)\" alt=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
        $user_type = 1;
    } else if ($_POST['type_users'] == 2) {//group_users
        preg_match_all("#st\.groupId=(.+?)&amp;(?:.+?)class=\"photoWrapper\" href=\"/(?:profile/)?(.+?)\"(?:.+?)<img src=\"(.+?)\" alt=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);

        foreach ($users_result as &$user) {
            /*
             $group_id - необязательный, но первый элемент, поэтому надо переместить его в конец
             */
            $var1 = $user[1];
            $var2 = $user[2];
            $var3 = $user[3];
            $var4 = $user[4];

            $user[1] = $var2;
            $user[2] = $var3;
            $user[3] = $var4;
            $user[4] = $var1;
        }
        $user_type = 2;
    } else if ($_POST['type_users'] == 3) {//search_results
        preg_match_all("#href=\"/(?:profile/)?(.+?)\" class=\"dblock\" (?:.+?)<img class=\"photo_img\" src=\"(.+?)\" alt=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
        $user_type = 3;
    } else if ($_POST['type_users'] == 4) {//group_users mobile
        preg_match_all("#<a href=\"/dk\?st\.cmd=friendMain&amp;st\.friendId=(.+?)&amp;(?:.+?)<div class=\"clickarea_content\">(?:.+?)<img (?:.+?)src=\"(.+?)\"(?:.+?)\"(?:.+?)<span class=\"emphased usr\">(.+?)</span>#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
        $user_type = 2;

    } else if ($_POST['type_users'] == 5) {//surveys
        preg_match_all("#class=\"photoWrapper\" href=\"/(?:profile/)?(.+?)\"(?:.+?)<img src=\"(.+?)\" alt=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
        $user_type = 5;
    } else if ($_POST['type_users'] == 6) {//comments
        preg_match_all("#<img uid=\"goToUserFromComment\"(?:.+?)data-query=\"\{&quot;userId&quot;:&quot;(.+?)&quot;\}\"(?:.+?)src=\"(.+?)\"(?:.+?)<span(?:.+?) uid=\"goToUserFromComment\"(?:.+?)>(.+?)</span>#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
        $user_type = 6;
    }


my_pre($users_result);



    $i = 0;
    foreach ($users_result as $user) {
        $profile_id = $user[1];
        $user_avatar = strip_tags($user[2]);
        $user_fio = strip_tags($user[3]);

        // replace тут не подойдет


        $stmt = $connect->prepare("SELECT * FROM $invite_table WHERE profile_id=:profile_id AND user_id=:user_id");
        $stmt->execute(array('profile_id' => $profile_id, 'user_id' => $user_id));

        $result = $stmt->fetchColumn();
        if (!$result) {
            $i++;


            $stmt = $connect->prepare("INSERT into $invite_table (

                profile_id,
                user_fio,
                user_avatar,
                    user_type,
                    user_id,
                    created

                ) VALUES(

                :profile_id,
                :user_fio,
                :user_avatar,
                    :user_type,
                    :user_id,
                    '" . time() . "'

                )");
            $stmt->execute(array(
                'profile_id' => $profile_id,
                'user_fio' => $user_fio,
                'user_avatar' => $user_avatar,
                'user_type' => $user_type,
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



<?php $non_instruction = 1;
require('generic/ok_instruction_menu.php'); ?>



    </div>
</div>





<div class="well well-lg" style="padding-top:10px !important;padding-bottom:10px !important; margin:0 !important;">
    <h3>Пригласить пользователей</h3><br>
    <div id='show_users_block'>
<?php $button_1_added_text = '';
require('generic/ok_show_users.php'); ?>


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
                    Пользователей, уже присутствующих в базе
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
<?php require('generic' . MY_DS . 'ok_users_types.php'); ?>
                    </div>

                    <div id="collection_category_func_buttons" style="padding-top:5px;">
                        <div id="ok_get_category_type_users_count" style="margin-bottom: 10px;color: #4c77af;font-size: 21px;"></div>
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

    var show_users_reset = 0;





    $(document).on('click', '#reset_users_list', function () {
        show_users_reset = 1;
        load_uses_list();
    });


    function load_uses_list() {

        var show_type_load = $("#show_type_load").val();
        var show_self_load_users_types = $("#show_self_load_users_types").val();
        var show_imported_categories = $("#show_imported_categories").val();
        var show_imported_types = $("#show_imported_types").val();
        var show_users_number = $("#show_users_number").val();


        if (!show_type_load) {
            show_type_load = 1;
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
            $('#show_type_load').trigger('change');
            $('#show_imported_categories').trigger('change');
            show_users_reset = 0;
        });
    }




    $(document).on('click', '#show_users', function () {

        load_uses_list();

    });


    $(document).on('change', '#show_type_load', function () {
        var type_load = $(this).val();

        if (type_load == 1) {
            $('#show_self_load_users_types').closest('div').hide();
            $('#show_imported_categories').closest('div').hide();
            $('#show_imported_types').closest('div').hide();
        } else if (type_load == 2) {
            $('#show_self_load_users_types').closest('div').show();
            $('#show_imported_categories').closest('div').hide();
            $('#show_imported_types').closest('div').hide();
        } else if (type_load == 3) {
            $('#show_self_load_users_types').closest('div').hide();
            $('#show_imported_categories').closest('div').show();
            $('#show_imported_types').closest('div').show();
        }
    });

    $(document).on('change', '#show_imported_categories', function () {
        var imported_category = $(this).val();
        var current_type = $("#show_imported_types").val();
        $.ajax({
            url: "/ok_get_loaded_types_users_count_by_category.php",
            data: {
                'category_id': imported_category,
            }
        }).done(function (data) {
            var result = JSON.parse(data);
            $("#show_imported_types").val(1);
            $("#show_imported_types option").hide();
            $("#show_imported_types option[value=1]").show();
            $.each(result, function (index, value) {
                $("#show_imported_types option[value=" + value.user_type + "]").show();
                if (current_type == value.user_type) {
                    $("#show_imported_types").val(value.user_type);
                }
            });


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
        var cost = round_cost(users_count * <?php echo(MY_USER_IMPORT_COST); ?>);
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



    $('#collection_category_selector,input[type=radio][name=type_users]').change(function () {


        var category_id = $('#collection_category_selector').val();
        var user_type = $('input[type=radio][name=type_users]:checked', '#collection_import_form').val();

        if (category_id == 0 || !user_type) {
            $("#collection_category_func_buttons").hide();
            return false;
        }




        $("#collection_category_func_buttons").show();


//$('#ok_get_category_type_users_count').html('<span class="text-muted">идет подсчет...</span>');


        $.ajax({
            url: "/ok_get_category_type_users_count.php",
            data: {
                'category_id': category_id,
                'user_type': user_type,
            }
        }).done(function (data) {
            $('#ok_get_category_type_users_count').html('Доступно: ' + data);
            $('#ok_get_category_type_users_count').attr('data-count', data);

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
        var user_type = $('input[type=radio][name=type_users]:checked', '#collection_import_form').val();
        var category_name = $('#collection_category_selector').find('option:selected').data('name');
        var user_type_name = $('input[type=radio][name=type_users]:checked').data('name');


        if (category_id == 0 || !user_type) {
            return false;
        }



        $.ajax({
            url: "/import_users_from_base.php",
            data: {
                'category_id': category_id,
                'user_type': user_type,
                'users_count': users_count,
                'user_id': '<?php echo($user_id); ?>',
            }
        }).done(function (data) {

            if (data > 0) {

                $('#collection_category_selector').trigger('change');

                var text = "Импортировано <b>" + data + "</b> человек";
                if (data == 1) {
                    text = "Импортирован <b>" + data + "</b> человек";
                } else if ((data > 1) && (data < 5)) {
                    text = "Импортировано <b>" + data + "</b> человека";
                }

                $('#balance').html((parseFloat($('#balance').html()) - data * <?php echo(MY_USER_IMPORT_COST); ?>).toFixed(2));
                $("#balance_import").html($("#balance").html());


                if (!$('#loaded_users_list').length) {
                    show_users_reset = 1;
                    load_uses_list();
                } else {
                    $('#reset_users_list').show();
                }





                swal({
                    title: "",
                    text: text + ' <br> Категория: <b>' + category_name + '</b><br> Тип: <b>' + user_type_name + '</b>',
                    html: true,
                    type: "success"
                });







            }




        });



        return false;

    });



    $('#show_type_load').trigger('change');
    $('#show_imported_categories').trigger('change');
    $('#collection_category_selector').trigger('change');


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