<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Получить подписчиков в группу';
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic' . MY_DS . 'actions.php');
require_once('generic/generic_functions.php');
$invite_table = 'fb_imports';

include('generic/auth_control.php');

if (isset($_POST['export_users'])) {

        $stmt = $connect->prepare("SELECT * FROM fb_imports WHERE user_id=$user_id order by id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $file_name = 'reports/fb_friends_' . uniqid() . $user_id . '.csv';
        $fp = fopen($file_name, 'w');

        if ($_POST['report_type'] == 1) {
            // Excel
            fputcsv($fp, array(
                'Date upload',
                'Login',
                'Url',
                'Is shown',
                'Date show'), ';');

            foreach ($result as $key => $row) {
                fputcsv($fp, array(
                    date("H:i:s d.m.Y", $row['created']),
                    $row['profile_id'] ?: ' ',
                    $row['user_url'],
                    $row['is_invited'] ? '+' : ' ',
                    !$row['is_invited'] ? ' ' : ($row['modified'] ? date("H:i:s d.m.Y", $row['modified']) : '')
                    ), ';'
                );
            }

        } else if ($_POST['report_type'] == 2) {
            // CSV
            fputcsv($fp, array(
                'Дата сохранения',
                'Логин',
                'Ссылка на профиль',
                'ФИО',
                'Показан?',
                'Дата показа'), ';');

            foreach ($result as $key => $row) {
                fputcsv($fp, array(
                    date("H:i:s d.m.Y", $row['created']),
                    $row['profile_id'] ?: ' ',
                    $row['user_url'],
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

    }


    if (isset($_POST['html_text']) && $_POST['html_text']) {


        preg_match_all("#\"friend_list_item\">(?:.+?)<img(?:.+?) src=\"(.+?)\"(?:.+?)<a href=\"https\:\/\/www\.facebook\.com\/(.+?)\?(?:.+?)data-hovercard-prefer-more-content-show=\"1\"(?:.*?)>(.+?)<\/a>#", $_POST['html_text'], $users_result, PREG_SET_ORDER);


        preg_match_all("#\"friend_list_item\">(?:.*?)<a(?:.+?) href=\"(.+?)\"#", $_POST['html_text'], $users_result2, PREG_SET_ORDER);

        $i = 0;
        foreach ($users_result as $key => $user) {

            $user_avatar = htmlspecialchars_decode($user[1]);
            $profile_id = strip_tags($user[2]);
            if ($profile_id == 'profile.php') {
                $profile_id='';
            }
            $user_fio = strip_tags($user[3]);
            $user_url = htmlspecialchars_decode($users_result2[$key][1]);
            $user_url = preg_replace('#([\?\&]fref.*)#','',$user_url);


            $stmt = $connect->prepare("SELECT * FROM $invite_table WHERE user_url=:user_url AND user_id=:user_id");
            $stmt->execute(array('user_url' => $user_url, 'user_id' => $user_id));

            $result = $stmt->fetchColumn();
            if (!$result) {
                $i++;


                $stmt = $connect->prepare("INSERT into $invite_table (
                    user_id,
                    profile_id,
                    user_fio,
                    user_avatar,
                    user_url,
                    created

                    ) VALUES(
                    :user_id,
                    :profile_id,
                    :user_fio,
                    :user_avatar,
                    :user_url,
                    '".time()."'

                )");
                $stmt->execute(array(
                    'profile_id' => $profile_id,
                    'user_fio' => $user_fio,
                    'user_avatar' => $user_avatar,
                    'user_url' => $user_url,
                    'user_id' => $user_id
                ));
            }
        }
    }




include('generic/header.php');

?>

<div class="well" style="text-align:center; margin-bottom:0; padding:10px; background-color: #dbe2f3;">
    <div class="row">
        <div class="pull-left">
            <img src="/img/fb_logo.jpg" width="50">
            <a href="/odnoklassniki"><img src="/img/ok_logo.jpg" width="50" style="opacity:0.5"></a>
        </div>
        <div class="pull-right">
            <?php include('generic/user_data.php');?>
        </div>
    </div>
</div>

<div class="row">
    <div class="pull-left" style="margin:10px;">
    <h5><b>FACEBOOK</b></h5>
    </div>
    <div class="btn-group pull-right" style="margin:10px;">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Инструкция <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="/img/scheme.png" target="_blank">Наглядное описание работы</a></li>
            <li class="dropdown-header"><h4>Как собрать, а затем пригласить в свою группу в Facebook:</h4></li>
            <li><a href="/facebook/instruction.html" target="_blank">своих друзей, а также друзей друзей</a></li>
        </ul>
    </div>
    </div>




<div class="well well-lg" style="padding-top:10px !important;padding-bottom:10px !important; margin:0 !important;">
    <h3>Пригласить пользователей</h3>

    <div id='show_users_block'>
        <?php $button_1_added_text = ''; require('generic/fb_show_users.php'); ?>
    </div>


</div>
























<div class="well well-lg" style="padding-top:10px !important;padding-bottom:10px !important; margin-top:20px !important;">
    <h3>Скачать отчет</h3>
    <br>

            <div id="report_self_loaded_block">
                <div style="display:inline-block; width:200px; margin-right: 10px;margin-bottom: 5px;">Формат файла</div>
                <br>
                <form action='' method="post" style="margin-bottom: 10px;" class="form-inline">
                    <input type="hidden" name="load_type" value="1">
                    <select class="form-control" name="report_type" style="width:200px; margin-right: 10px;">
                        <option value="1">для Excel</option>
                        <option value="2">для CSV редакторов</option>
                    </select><button
                        name='export_users' type="submit" class="btn btn-info"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Скачать</button>
                </form>
            </div>

</div>


























<div class="well well-lg" style="padding-top:10px !important;padding-bottom:10px !important;  margin-bottom:0px !important; margin-top:20px !important;">
    <h3>Импорт пользователей</h3><br>
    <?php
    if (isset($_POST['html_text']) && $_POST['html_text']) {
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
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Импорт</button>

                </form>
            </td>
        </tr>
    </table>
</div>

<script type="text/javascript">
$(document).on('click','#show_users', function(){

    var show_users_number = $("#show_users_number").val();

    $.ajax({
        url: "/fb_show_users.php",
        data: {
                'show_users_number': show_users_number
            }
            }).done(function(data) {
                $("#show_users_block").html(data);
            });

});

</script>

<table style="display:none;">
        <tr>
            <td align="center" valign="top" style="padding:10px">

<!--LiveInternet counter--><script type="text/javascript">
document.write("<a href='//www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t25.2;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показано число посетителей за"+
" сегодня' "+
"border='0' width='88' height='15'><\/a>")
</script><!--/LiveInternet-->


            </td>
        </tr>
</table>

<?php include('generic/footer.php'); ?>