<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Получить подписчиков в группу';
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
$invite_table = 'ok_imports';
$client_code = !empty($_GET['client_code']) ? $_GET['client_code'] : null;

require_once('generic/functions.php');

    function get_type_name($user_type, $excel=false){


    if ($excel){
                    if ($user_type == 1) {
                        return 'Klass!';
                    } elseif ($user_type == 2) {
                        return 'group subscriber';
                    } elseif ($user_type == 3) {
                        return 'from search';
                    }


    }







                    if ($user_type == 1) {
                        return 'поставил "Класс"';
                    } elseif ($user_type == 2) {
                        return 'участник группы';
                    } elseif ($user_type == 3) {
                        return 'из результатов поиска';
                    }

    }




    // Экспорт в csv всех пользователей этого логина



    if (isset($_POST['export_users'])) {

        $stmt = $connect->prepare("SELECT * FROM $invite_table WHERE client_code=:client_code order by id ASC");
        $stmt->execute(array('client_code' => $client_code));
        $result = $stmt->fetchAll();
        $file_name = 'reports/odnoklassniki_'.$client_code.'.csv';
        $fp = fopen($file_name, 'w');
        fputcsv($fp, array(
        'Номер',
        'Ссылка на профиль',

        'ФИО',
        'Тип пользоватлея',

        'Дата сохранения',
        'Показан?',
        'Дата показа'), ';');

        foreach ($result as $key => $row) {
            fputcsv($fp, array(
                $key + 1,
                'https://ok.ru/profile/' . $row['profile_id'],

                $row['user_fio'],

                get_type_name($row['user_type']),


                date("Y.m.d H:i:s", $row['created']),
                $row['is_invited'] ? 'да' : 'нет',
                $row['modified'] ? date("Y.m.d H:i:s", $row['modified']) : ''
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






    if (isset($_POST['export_users_excel'])) {

        $stmt = $connect->prepare("SELECT * FROM $invite_table WHERE client_code=:client_code order by id ASC");
        $stmt->execute(array('client_code' => $client_code));
        $result = $stmt->fetchAll();
        $file_name = 'reports/odnoklassniki_'.$client_code.'.csv';
        $fp = fopen($file_name, 'w');
        fputcsv($fp, array(
        'Number',
        'Url',

        'Type',

        'Date upload',
        'Shown',
        'Date showing'), ';');

        foreach ($result as $key => $row) {
            fputcsv($fp, array(
                $key + 1,
                'https://ok.ru/profile/' . $row['profile_id'],



                get_type_name($row['user_type'], true),


                date("Y.m.d H:i:s", $row['created']),
                $row['is_invited'] ? 'yes' : 'no',
                $row['modified'] ? date("Y.m.d H:i:s", $row['modified']) : ''
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

    if (isset($_POST['html_text']) && $_POST['html_text']) {
        if ($_POST['type_users'] === 'classes') {

            preg_match_all("#class=\"photoWrapper\" href=\"/profile/([0-9]+)\"(?:.+?)<img src=\"(.+?)\" alt=\"(.+?)\"#", $_POST['html_text'], $users_result, PREG_SET_ORDER);
            $user_type = 1;
        }
        else if ($_POST['type_users'] === 'group_users') {

            preg_match_all("#class=\"photoWrapper\" href=\"/profile/([0-9]+)\"(?:.+?)<img src=\"(.+?)\" alt=\"(.+?)\"#", $_POST['html_text'], $users_result, PREG_SET_ORDER);
            $user_type = 2;
        }
        else if ($_POST['type_users'] === 'search_results') {

            preg_match_all("#href=\"/profile/([0-9]+)\" class=\"dblock\" (?:.+?)<img class=\"photo_img\" src=\"(.+?)\" alt=\"(.+?)\"#", $_POST['html_text'], $users_result, PREG_SET_ORDER);
            $user_type = 3;
        }

        $i = 0;
        foreach ($users_result as $user) {
            $profile_id = $user[1];
            $user_avatar = strip_tags($user[2]);
            $user_fio = strip_tags($user[3]);

            // replace тут не подойдет


            $stmt = $connect->prepare("SELECT * FROM $invite_table WHERE profile_id=:profile_id AND client_code=:client_code");
            $stmt->execute(array('profile_id' => $profile_id, 'client_code' => $client_code));

            $result = $stmt->fetchColumn();
            if (!$result) {
                $i++;


                $stmt = $connect->prepare("INSERT into $invite_table (

                profile_id,
                user_fio,
                user_avatar,
                    user_type,
                    client_code,
                    created

                ) VALUES(

                :profile_id,
                :user_fio,
                :user_avatar,
                    :user_type,
                    :client_code,
                    '".time()."'

                )");
                $stmt->execute(array(
                'profile_id' => $profile_id,

                'user_fio' => $user_fio,
                'user_avatar' => $user_avatar,
                'user_type' => $user_type,

                'client_code' => $client_code
                ));

            }
        }

    }






include('generic/header.php');

?>
<div class="alert alert-info" role="alert" style="text-align:center; margin-bottom:0;">Ваш персональный код: <b><big><?php echo($client_code);?></big></b></div>

    <div class="btn-group" style="margin:10px;">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Инструкция <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="/img/scheme.png" target="_blank">Наглядное описание работы</a></li>
            <li class="dropdown-header"><h4>Как получить, а затем пригласить в свою группу:</h4></li>
            <li><a href="/odnoklassniki/instruction1.html" target="_blank">участников группы, релевантной вашей</a></li>
            <li><a href="/odnoklassniki/instruction2.html" target="_blank">пользователей, поставивших "Kласс!"</a></li>
            <li><a href="/odnoklassniki/instruction3.html" target="_blank">пользователей из результата поиска</a></li>
        </ul>
    </div>



<?php if (!$client_code) { ?>



<script type="text/javascript">

swal({
  title: "Добро пожаловать!",
  text: "Введите свой персональный код:",
  type: "input",
  showCancelButton: true,
  closeOnConfirm: false,
  animation: "slide-from-top",
  inputPlaceholder: ""
},
function(inputValue){
  if (inputValue === false) return false;

  if (inputValue) {

    document.location.href = 'http://inviter.mapstore.org/odnoklassniki/' + inputValue;

  }
  return false;

});
</script>
<div class="row">

    <div class="alert alert-warning col-xs-12" role="alert" style="text-align:center;">Необходимо указать ваш персональный код. Если вы его забыли, можете написать в
    <a href="https://ok.ru/messages/509040024720" target="_blank">поддержку</a>, вам обязательно помогут.</div>
</div>

            <div class="row">
<div class="сol-xs-12" style="text-align:center;">
<a class="btn btn-success dropdown-toggle" href="http://inviter.mapstore.org/odnoklassniki/<?php echo(my_create_password());?>?firstly=1"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;Создать аккаунт</a>
</div>
</div>


<?php }  else { ?>




<?php if (!empty($_GET['firstly'])) { ?>
  <script type="text/javascript">
swal({
  title: "Добро пожаловать на вашу персональную страницу!",
  text: "Вы сможете заходить на неё по прямой ссылке или со страницы <a href='http://inviter.mapstore.org/odnoklassniki'>inviter.mapstore.org/odnoklassniki</a>, используя ваш персональный код: <b><?php echo($client_code);?></b><br><i>(код присутствует в адресе)</i><br><br>Пожалуйста, запомните его.",
  html: true
},function(){
  document.location.href = 'http://inviter.mapstore.org/odnoklassniki/<?php echo($client_code);?>';
  });
</script>
 <?php } ?>


















<div class="well well-lg" style="padding-top:10px !important;padding-bottom:10px !important; margin:0 !important;">
    <h3>Сохраненные пользователи</h3><br>
    <?php







    $button_1_added_text = '';
    if (isset($_POST['show_users'])) {
        $button_1_added_text = ' следующие';

        //$sql = "SELECT * FROM $invite_table WHERE is_invited=0 order by id ASC limit 10";
        $stmt = $connect->prepare("SELECT * FROM $invite_table WHERE is_invited=0 AND client_code=:client_code order by id ASC limit 5");
        $stmt->execute(array('client_code' => $client_code));
        $result = $stmt->fetchAll();




        ?>

        <div class="row">
        <div class="row">
            <div class="alert alert-warning col-md-6" role="alert">Внимание! Пользователи ниже выводятся только один раз.</div>
            </div>

                <?php
                if ($result) { ?>



            <div class="row">
            <script>var links = new Array();</script>
            <div class="list-group col-md-5" style="padding-right:0;">
                <?php



                foreach ($result as $user) {
                    $link = 'https://ok.ru/profile/' . $user['profile_id'];


                    $type_name = get_type_name($user['user_type']);


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

                    $stmt = $connect->prepare("update $invite_table set is_invited=1, modified = '".time()."' where profile_id = :profile_id AND client_code=:client_code");
                    $stmt->execute(array('profile_id' => $user['profile_id'], 'client_code' => $client_code));
                }
                ?>

                    <script>
                        function open_users() {

                            for (index = 0; index < links.length; index++) {
                                window.open(links[index], '_blank', 'left=300, top=100, width=900, height=800');
                            }


                        }
                    </script>
                    <a style="cursor:pointer" class="list-group-item list-group-item-success" onclick='open_users();'>Открыть всех</a>
</div>
            </div>
                    <?php
                }
                ?>
            </div>
        <?php
    }
            $stmt = $connect->prepare("SELECT count(*) as count FROM $invite_table WHERE is_invited=1 AND client_code=:client_code");
            $stmt->execute(array('client_code' => $client_code));
            $count_invited = $stmt->fetchColumn();


            $stmt = $connect->prepare("SELECT count(*) as count FROM $invite_table WHERE is_invited=0 AND client_code=:client_code");
            $stmt->execute(array('client_code' => $client_code));
            $count_non_invited = $stmt->fetchColumn();
    ?>

            <div class="row">
                <div class="alert alert-info col-md-5" role="alert">
                <?php //Всего показано: <?php echo($count_invited);<br>?>
                Осталось: <?php echo($count_non_invited);?>
                </div>
            </div>





    <?php


    if (!$count_non_invited) {?>


                     <div class="row">
                        <div class="alert alert-danger col-md-6" role="alert">Список пользователей пуст, добавьте их через форму ниже</div>

                    </div>


    <?php }

if($count_non_invited){


    ?>
<div class="row">
    <form action='' method="post">



<button name='show_users' type="submit" class="btn btn-success" style="margin-bottom:20px;"><span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span> Показать <?php echo $button_1_added_text; ?> 5 сохраненных пользователей</button>


    </form>
</div>

<div class="row">
    <form action='' method="post">
        <button name='export_users_excel' type="submit" class="btn btn-info"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Отчет (для Excel)</button>
    </form>
    <form action='' method="post">
        <button name='export_users' type="submit" class="btn btn-info"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Отчет (для CSV редакторов)</button>
    </form>

</div>


<?php } ?>

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
                    <h4>Откуда взят HTML код:</h4>

                    <div class="radio">
                        <label>
                            <input type="radio" value='classes' name="type_users" checked>
                            Пользователи, поставившие "класс!"
                        </label>
                    </div>

                    <div class="radio">
                        <label>
                            <input type="radio" value='group_users' name="type_users">
                            Список пользователей группы
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" value='search_results' name="type_users">
                            Пользователи из результата поиска
                        </label>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Импорт</button>

                </form>
            </td>
        </tr>
    </table>
</div>


<?php } ?>

<table style="display:none;">
        <tr>
            <td align="center" valign="top" style="padding:10px;">

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