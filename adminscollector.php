<?php



























error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Собрать/показать админов групп из одноклассников';
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic/generic_functions.php');
$invite_table = 'odnoklassniki_group_admins_collector';



////////////////




    //Обновляем имя и снова показываем его
    if (isset($_POST['update_name'])){


        $stmt = $connect->prepare("update $invite_table set status=0, admin_name = '".$_POST['update_name']."' where id = '".$_POST['id']."'");
        $stmt->execute();

        $_POST['show_group']=1;



    }













    // Экспорт в csv всех пользователей этого логина
    if (isset($_POST['export'])) {

        $stmt = $connect->prepare("SELECT * FROM $invite_table order by id ASC");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $file_name = 'reports/odnoklassniki_groups.csv';
        $fp = fopen($file_name, 'w');
        fputcsv($fp, array(
        'Number',
        'Group Link',
        'Name',
        'Admin FIO',
        'Admin Name',
        'Admin ok url',
        'Group users number',
        'Admin code',
        'Admin site url',
        'Invited',
        'Date showing',
        'Date upload'
        ), ';');

        foreach ($result as $key => $row) {
            fputcsv($fp, array(
                $key + 1,
                'https://ok.ru/group/' . $row['ok_group_id'],
                $row['ok_group_name'],
                $row['admin_fio'],
                $row['admin_name'],
                'https://ok.ru/profile/' . $row['admin_id'],
                $row['users_number'],
                $row['code'],
                'http://inviter.mapstore.org/gathering/' . $row['code'],
                $row['status'] ? 'yes' : 'no',
                $row['modified'] ? date("Y.m.d H:i:s", $row['modified']) : '' ,
                date("Y.m.d H:i:s", $row['created'])
                ), ';'
            );
        }
        fclose($fp);

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=".$file_name);
        header("Content-Length: " . filesize($file_name));
        readfile($file_name);
        exit();
    }
//////////////


/// парсим html код
    if (isset($_POST['html_text']) && $_POST['html_text']) {


            preg_match_all("#class=\"o\" hrefattrs=\"st\.cmd=altGroupMain&amp;st\.groupId=([0-9]+)&amp;(?:.+?)>(.+?)<\/a>#", $_POST['html_text'], $groups_result, PREG_SET_ORDER);





$i = 0;
        foreach ($groups_result as $group) {


            $group_id = $group[1];
            $group_name = $group[2];

            $stmt = $connect->prepare("SELECT * FROM $invite_table WHERE ok_group_id=:ok_group_id");
            $stmt->execute(array('ok_group_id' => $group_id));
            $result = $stmt->fetchColumn();
            if (!$result) {
                $content = file_get_contents('https://ok.ru/group/' . $group_id);

                $html = $content;
                //sleep(1);

                preg_match_all("#<a data-l=\"t,uL\"(?:.+?)href=\"(.+?)\"(?:.+?)>(.+?)<\/a><\/span>#", $html, $admin_result, PREG_SET_ORDER);
                $admin_id = @$admin_result[0][1];
                //$admin_id = preg_replace('/\?(.+?)/', '', $admin_id);

                if ($admin_id) {
                    $i++;

                    $admin_fio = $admin_result[0][2];
                    $admin_name = explode(' ', $admin_fio);
                    $admin_name = $admin_name[0];
                    //preg_match_all("#<div class=\"mt-x\">(.+?)участников<\/div>#", $html, $number_result, PREG_SET_ORDER);
                    //$group_users_number = str_replace('&nbsp;','',$number_result[0][1]);

                    //echo('<br>admin_id:');my_pre($admin_id, false);
                    //echo('<br>admin_fio:');my_pre($admin_fio, false);
                    //echo('<br>admin_name:');my_pre($admin_name, false);
                    //echo('<br>group_users_number:');my_pre($group_users_number, false);

                    $code=my_create_password();

                    $stmt = $connect->prepare("INSERT into $invite_table (
                    ok_group_id,
                    ok_group_name,
                    admin_fio,
                    admin_name,
                    admin_id,
                    users_number,
                    code,
                    status,
                    created) VALUES(
                    :group_id,
                    :group_name,
                    :admin_fio,
                    :admin_name,
                    :admin_id,
                    :group_users_number,
                    :code,
                    0,
                    '".time()."'
                    )");
                    $stmt->execute(
                    array(
                    'group_id' => $group_id,
                    'group_name' => $group_name,
                    'admin_fio' => $admin_fio,
                    'admin_name' => $admin_name,
                    'admin_id' => $admin_id,
                    'group_users_number' => 0,
                    'code' => $code
                    )
                    );
                }
            }
        }
    }

include('generic/header.php');

?>


<div class="well well-lg" style="padding-top:10px !important;padding-bottom:10px !important; margin:0 !important;">
    <h3>Сохраненные группы</h3><br>
    <?php


    if (isset($_POST['show_group'])) {

        $stmt = $connect->prepare("SELECT * FROM $invite_table WHERE status=0 order by id ASC limit 5");
        $stmt->execute();
        $result = $stmt->fetchAll();
        ?>

        <div class="row">
        <div class="row">
            </div>
                <?php
                if ($result) { ?>


            <div class="row">
            <script>var links = new Array();</script>
            <div class="list-group col-md-6" style="padding-right:0;">
                <?php

                foreach ($result as $group) {





            if ($group['admin_name']) {
                $header = 'Здравствуйте, '.$group['admin_name'].'.';
            } else {
                $header = 'Здравствуйте.';
            }



                    //echo('Имя: <form method="POST"><input type="hidden" name="id" value="' . $group['id'] . '"><input type="text" style="width:50%;" name="update_name" value="' . $group['admin_name'] . '"/> <input type="submit" value="Обновить"></form>');

                    $link = 'https://ok.ru/group/' . $group['ok_group_id'];
                    echo('<a target="_blank" style="color:#6085bc !important" class="list-group-item" href="' . $link . '" onclick="window.open(\'' . $link . '\',\'_blank\',\'left=300, top=100, width=900, height=800\'); return false"><b>Группа:</b> ' . $group['ok_group_name'] . '</a><script>links.push(\'' . $link . '\');</script>');




                    $link = 'https://ok.ru' . $group['admin_id'];
                    echo('<a target="_blank" style="color:#6085bc !important" class="list-group-item" href="' . $link . '" onclick="window.open(\'' . $link . '\',\'_blank\',\'left=300, top=100, width=1050, height=800\'); return false"><b>Профиль админа:</b> ' . $group['admin_fio'] . '</a><script>links.push(\'' . $link . '\');</script>');

                   $text=$header . "
Вам, как администратору группы, хочу предложить попробовать мой новый и очень полезный интернет-сервис.
Он представляет собой сайт в виде одной страницы.
Сервис позволяет быстро и без особых усилий собрать наиболее подходящих вашей группе пользователей (каких именно - решаете вы сами):
1) тех, кто ставит классы,
2) подписан на релевантную вашей группу,
3) найден по поиску,
чтобы потом предоставить вам персональную страницу каждого из них. Как приглашают пользователей с их профиля, уверен, вы знаете.
В итоге все \"интересные\" вам пользователи будут храниться в вашей базе и вы в любое время можете начать приглашать их одним нажатием кнопки.
Сразу скажу - Регламент ok.ru не нарушается. Вы приглашаете их сами, просто всю рутину за вас делает сервис.

Если я вас заинтересовал, то информация ниже позволит вам узнать о нем больше:
Первый месяц - бесплатно, далее - 100 рублей в месяц.
Группа с описанием проекта: https://ok.ru/group/53141782921360.
Сайт проекта: http://inviter.mapstore.org.

Если будут вопросы, пишите. Буду рад помочь.";
                    //echo('<textarea class="col-xs-12" style="margin-top:10px" rows="19">'.$text . '</textarea>');


echo('<br><br>');



                    $stmt = $connect->prepare("update $invite_table set status=1, modified = '".time()."' where ok_group_id = :ok_group_id");
                    $stmt->execute(array('ok_group_id' => $group['ok_group_id']));
                }
                ?>
                    <script>
                        function open_users() {

                            for (index = 0; index < links.length; index++) {
                                window.open(links[index], '_blank', 'left=300, top=100, width=1200, height=800');
                            }


                        }
                    </script>

</div>
            </div>
                    <?php
                }
                ?>
            </div>
        <?php
    }

                $stmt = $connect->prepare("SELECT count(*) as count FROM $invite_table WHERE status=1");
            $stmt->execute();
            $count_invited = $stmt->fetchColumn();


            $stmt = $connect->prepare("SELECT count(*) as count FROM $invite_table WHERE status=0");
            $stmt->execute();
            $count_non_invited = $stmt->fetchColumn();
    ?>

            <div class="row">
                <div class="alert alert-success col-md-6" role="alert">
                Всего показано: <?php echo($count_invited);?><br>
                Осталось: <?php echo($count_non_invited);?>
                </div>
            </div>





    <?php


    if (!$count_non_invited) {?>


                     <div class="row">
                        <div class="alert alert-danger col-md-6" role="alert">Список групп пуст, добавьте их через форму ниже</div>

                    </div>


    <?php }
if($count_non_invited){


    ?>
<div class="row">
    <form action='' method="post">


<button name='show_group' type="submit" class="btn btn-success"><span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span> Показать</button>

    </form>
</div>





<!--
<div class="row">
    <form action='' method="post">
        <button name='export' type="submit" class="btn btn-info"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Экспорт всех групп</button>
    </form>
</div>-->


<?php } ?>

</div>



<div class="well well-lg" style="padding-top:10px !important;padding-bottom:10px !important;  margin-bottom:0px !important; margin-top:20px !important;">
    <h3>Импорт </h3><br>
    <?php
    if (isset($_POST['html_text']) && $_POST['html_text']) {
        ?>

        <div class="col-md-4" style="padding-left:0 !important">


            <ul class="list-group">

                <li class="list-group-item list-group-item-info">
                    <span class="badge" style="background-color: #FFF;color:rgb(30, 30, 203);"><?php echo(count($groups_result)); ?></span>
                    Найдено
                </li>

                <li class="list-group-item list-group-item-success">
                    <span class="badge" style="background-color: #FFF;color: #000;"><?php echo($i); ?></span>
                    Добавлено
                </li>

                <li class="list-group-item list-group-item-warning">
                    <span class="badge" style="background-color: #FFF;color:rgb(207, 38, 38);"><?php echo((count($groups_result) - $i)); ?></span>
                    Уже присутствующих в базе
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
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Импорт</button>

                </form>
            </td>
        </tr>
    </table>
</div>
<?php include('generic/footer.php'); ?>