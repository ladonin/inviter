<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Получить подписчиков в группу';
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');




    function get_type_name($user_type, $excel=false){


    if ($excel){
                    if ($user_type == 1) {
                        return 'Klass!';
                    } elseif ($user_type == 2) {
                        return 'group subscriber';
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
                        return 'учавствует в опросах';
                    } elseif ($user_type == 6) {
                        return 'комментарии';
                    }

    }


    if (isset($_POST['html_text']) && $_POST['html_text'] && $_POST['category_id']) {
        if ($_POST['type_users'] === 'classes') {

            preg_match_all("#class=\"photoWrapper\" href=\"/(?:profile/)?(.+?)\"(?:.+?)<img src=\"(.+?)\" alt=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
            $user_type = 1;
        }
        else if ($_POST['type_users'] === 'group_users') {

            preg_match_all("#class=\"photoWrapper\" href=\"/(?:profile/)?(.+?)\"(?:.+?)<img src=\"(.+?)\" alt=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
            $user_type = 2;
        }
        else if ($_POST['type_users'] === 'search_results') {

            preg_match_all("#href=\"/(?:profile/)?(.+?)\" class=\"dblock\" (?:.+?)<img class=\"photo_img\" src=\"(.+?)\" alt=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
            $user_type = 3;
        } else if ($_POST['type_users'] === 'group_users_mob') {

            preg_match_all("#<a href=\"/dk\?st\.cmd=friendMain&amp;st\.friendId=(.+?)&amp;(?:.+?)<div class=\"clickarea_content\">(?:.+?)<img (?:.+?)src=\"(.+?)\"(?:.+?)\"(?:.+?)<span class=\"emphased usr\">(.+?)</span>#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
            $user_type = 2;
        } else if ($_POST['type_users'] === 'surveys') {

            preg_match_all("#class=\"photoWrapper\" href=\"/(?:profile/)?(.+?)\"(?:.+?)<img src=\"(.+?)\" alt=\"(.+?)\"#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
            $user_type = 5;
        } else if ($_POST['type_users'] === 'comments') {

            preg_match_all("#<img uid=\"goToUserFromComment\"(?:.+?)data-query=\"\{&quot;userId&quot;:&quot;(.+?)&quot;\}\"(?:.+?)src=\"(.+?)\"(?:.+?)<span(?:.+?) uid=\"goToUserFromComment\"(?:.+?)>(.+?)</span>#is", $_POST['html_text'], $users_result, PREG_SET_ORDER);
            $user_type = 6;
        }


        $i = 0;
        $category_id = $_POST['category_id'];

        foreach ($users_result as $user) {
            $profile_id = $user[1];
            $user_avatar = strip_tags($user[2]);
            $user_fio = strip_tags($user[3]);

            // replace тут не подойдет


            $stmt = $connect->prepare("SELECT * FROM ok_collections WHERE profile_id=:profile_id AND category_id=:category_id");




            $stmt->execute(array('profile_id' => $profile_id, 'category_id' => $category_id));

            $result = $stmt->fetchColumn();
            if (!$result) {
                $i++;


                $stmt = $connect->prepare("INSERT into ok_collections (

                profile_id,
                user_fio,
                user_avatar,
                    user_type,
                     	category_id,
                    created

                ) VALUES(

                :profile_id,
                :user_fio,
                :user_avatar,
                    :user_type,
                    :category_id,
                    '".time()."'

                )");
                $stmt->execute(array(
                'profile_id' => $profile_id,
                'user_fio' => $user_fio,
                'user_avatar' => $user_avatar,
                'user_type' => $user_type,
                'category_id' => $category_id
                ));

            }
        }

    }






        $stmt = $connect->prepare("SELECT * FROM ok_collections_categories order by name ASC");
        $stmt->execute();
        $categories = $stmt->fetchAll();







include('generic/header.php');

?>


<div class="well" style="text-align:center; margin-bottom:0; padding:10px; background-color: #f07d0033;">
    <div class="row">
        <div class="pull-left">
            <img src="/img/ok_logo.jpg" width="50">
        </div>
    </div>
</div>



<div class="row">


    <div class="pull-left" style="margin:10px;">
    <h5><b>ОДНОКЛАССНИКИ - заполнение коллекции</b></h5>
    </div>


    </div>



<div class="well well-lg" style="padding-top:10px !important;padding-bottom:10px !important;  margin-bottom:0px !important; margin-top:0px !important;">
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

             <form action='' method="post">

                    <h4>Категория:</h4>
                <select name="category_id" class="form-control" style="width:400px;">
                <option value="">выберите категорию
                <?php

                foreach ($categories as $key => $category) {?>

                    <option value="<?php echo($category['id']);?>"><?php echo($category['name']);?>
                <?php }


                ?>

                </select>



            <br>
                <h4>HTML код</h4>








                    <textarea cols="80" rows="5" name="html_text" class="form-control"></textarea>

                    <br>
                    <h4>Откуда взят HTML код:</h4>

                    <div class="radio">
                        <label>
                            <input type="radio" required value='classes' name="type_users">
                            Пользователи, поставившие "класс!"
                        </label>
                    </div>
                    <div class="radio" style="display: block;">
                        <label>
                            <input type="radio" required value='surveys' name="type_users">
                            Пользователи, учавствующие в опросах
                        </label>
                    </div>
                    <div class="radio" style="display: block;">
                        <label>
                            <input type="radio" required value='comments' name="type_users">
                            Пользователи, пишущие комментарии
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" required value='group_users' name="type_users">
                            Подписчики
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" required value='search_results' name="type_users">
                            Пользователи из результата поиска
                        </label>
                    </div>

                    <div class="radio">
                        <label>
                            <input type="radio" required value='group_users_mob' name="type_users">
                            Подписчики - мобильная версия сайта
                        </label>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Импорт</button>

                </form>
            </td>
        </tr>
    </table>
</div>




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