<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Получить подписчиков в группу';
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic/generic_functions.php');
require_once('generic/net_functions.php');
require_once("generic/{$net_code}_functions.php");








function load_users_collection_init()
{
    global $types_fields_inv;
    global $net_code;
    global $connect;
    global $user_id;

    if (isset($_POST['html_text']) && $_POST['html_text'] && $_POST['category_id']) {
        $load_data = prepare_load_data();
        $users_result = $load_data['users_result'];
        $user_type = $load_data['user_type'];

        $i = 0;
        $category_id = (int) $_POST['category_id'];
        $url = strip_tags($_POST['url']);
        if (!$url) {
            echo 'забыл url';
            exit();
        }
        foreach ($users_result as $user) {
            $profile_id = $user[1];
            $user_avatar = $user[2];
            $user_fio = $user[3];

            // replace тут не подойдет


            $stmt = $connect->prepare("SELECT * FROM {$net_code}_collections_$category_id WHERE profile_id=:profile_id");
            $stmt->execute(array('profile_id' => $profile_id));

            $result = $stmt->fetch();
            if (!$result) {
                $i++;

                //тут он будет писаться с нуля
                $data_array = array();

                if ($url) {
                    $data_array['urls'][$user_type] = $url;
                }


                if ($data_array) {
                    $data = json_encode($data_array, JSON_UNESCAPED_UNICODE);
                } else {
                    $data = '';
                }

                $stmt = $connect->prepare("INSERT into {$net_code}_collections_$category_id (

                    profile_id,
                    user_fio,
                    user_avatar,
                    {$types_fields_inv[$user_type]},
                    data,
                    created

                ) VALUES(

                    :profile_id,
                    :user_fio,
                    :user_avatar,
                    1,
                    :data,
                    '" . time() . "'

                )");
                $stmt->execute(array(
                    'profile_id' => $profile_id,
                    'user_fio' => $user_fio,
                    'user_avatar' => $user_avatar,
                    'data' => $data
                ));
            } else {
                //если есть, то обновляем его
                //обновляем user_type и data

                $data_array = json_decode($result['data'], true);


                if ($url) {
                    $data_array['urls'][$user_type] = $url;
                }


                if ($data_array) {
                    $data = json_encode($data_array, JSON_UNESCAPED_UNICODE);
                } else {
                    $data = '';
                }


                $stmt = $connect->prepare("
                UPDATE
                    {$net_code}_collections_$category_id
                SET
                    {$types_fields_inv[$user_type]} = 1,
                    data = :data,
                    modified = '" . time() . "'
                WHERE
                    profile_id=:profile_id
                ");
                $stmt->execute(array(
                    'data' => $data,
                    'profile_id' => $profile_id
                ));
            }
        }
        return array(
            'users_result' => $users_result,
            'inserts_count' => $i
        );
    }
}



$load_users_collection_result = load_users_collection_init();







        $stmt = $connect->prepare("SELECT * FROM {$net_code}_collections_categories order by name ASC");
        $stmt->execute();
        $categories = $stmt->fetchAll();







include('generic/header.php');

?>


<div class="well" style="text-align:center; margin-bottom:0; padding:10px; background-color:#<?=get_net_header_background_color();?> ">
    <div class="row">
        <div class="pull-left">
            <a href="/wy5be6un_collect/ok"><img src="/img/ok_logo.jpg" width="50" style="opacity:1"></a>
            <a href="/wy5be6un_collect/fb"><img src="/img/fb_logo.jpg" width="50" style="opacity:1"></a>
            <a href="/wy5be6un_collect/vk"><img src="/img/vk_logo.jpg" width="50" style="opacity:1"></a>
        </div>
    </div>
</div>
<div class="row">
    <div class="pull-left" style="margin:10px;">
        <h5><b><?=get_net_title();?> - заполнение коллекции</b></h5>
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
                    <span class="badge" style="background-color: #FFF;color:rgb(30, 30, 203);"><?php echo(count($load_users_collection_result['users_result'])); ?></span>
                    Найдено пользователей
                </li>

                <li class="list-group-item list-group-item-success">
                    <span class="badge" style="background-color: #FFF;color: #000;"><?php echo($load_users_collection_result['inserts_count']); ?></span>
                    Добавлено пользователей
                </li>

                <li class="list-group-item list-group-item-warning">
                    <span class="badge" style="background-color: #FFF;color:rgb(207, 38, 38);"><?php echo((count($load_users_collection_result['users_result']) - $load_users_collection_result['inserts_count'])); ?></span>
                    Обновлено пользователей
                </li>


            </ul></div>
    <?php }
    ?>



    <table width="100%">
        <tr>
            <td width="50%" align="left" valign="top">

             <form action='' method="post">

                    <h4>Категория:</h4>
                <select name="category_id" class="form-control" required style="width:400px;">
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

                    <?php require('generic' . MY_DS . $net_code . '_users_types.php'); ?>
                    <br>


                    <h4>URL:</h4>
                    <input type="text"  required maxlength="200" name="url" class="form-control" style="max-width:500px">
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