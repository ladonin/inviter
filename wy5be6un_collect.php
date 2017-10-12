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

    if (((isset($_POST['html_text']) && $_POST['html_text']) || !empty($_FILES['file'])) && $_POST['category_id']) {



        if (!empty($_FILES['file']['tmp_name'])) {
            $fp = file_get_contents($_FILES['file']['tmp_name']);
            $_POST['html_text'] = $fp;




        }




        $load_data = prepare_load_data();
        $users_result = $load_data['users_result'];
        $user_type = $load_data['user_type'];

        $i = 0;
        $category_id = (int) $_POST['category_id'];
        $url = strip_tags($_POST['url']);
        $url = rtrim($url, '/');
        if (!$url) {
            echo 'забыл url';
            exit();
        }
        foreach ($users_result as $user) {
            $profile_id = $user[1];
            $user_avatar = $user[2];
            $user_fio = $user[3];

            // replace тут не подойдет


            $stmt = $connect->prepare("SELECT * FROM {$net_code}_collections_temp_$category_id WHERE profile_id=:profile_id");
            $stmt->execute(array('profile_id' => $profile_id));

            $result = $stmt->fetch();
            if (!$result) {
                $i++;

                //тут он будет писаться с нуля
                $data_array = array();

                if ($url) {
                    $data_array['urls'][$user_type][] = $url;
                }


                if ($data_array) {
                    $data = json_encode($data_array, JSON_UNESCAPED_UNICODE);
                } else {
                    $data = '';
                }

                $stmt = $connect->prepare("INSERT into {$net_code}_collections_temp_$category_id (

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
                //обновляем user_type и добавляем новые данные data (не обновляем старые)

                $data_array = json_decode($result['data'], true);

                if ($url) {
                    // [Имеем новый url]
                    // Надо проверить, что его нет среди существующих urls
                    if (!isset($data_array['urls'][$user_type]) || !in_array($url, $data_array['urls'][$user_type])){
                        $data_array['urls'][$user_type][] = $url;
                    }
                }


                if ($data_array) {
                    $data = json_encode($data_array, JSON_UNESCAPED_UNICODE);
                } else {
                    $data = '';
                }


                $stmt = $connect->prepare("
                UPDATE
                    {$net_code}_collections_temp_$category_id
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
    return false;
}





function publish_temp_init(){

    global $net_code;
    global $connect;
    global $nets;

    if (isset($_POST['publish_temp']) && $_POST['publish_temp'] == 1) {
        $result = array();

        // по каждой соцсети
        foreach($nets as $net) {
            // по каждой категории
            $stmt = $connect->prepare("SELECT * FROM {$net}_collections_categories");
            $stmt->execute();
            $categories = $stmt->fetchAll();
            foreach($categories as $category) {
                // берем всех пользователей категории
                $stmt2 = $connect->prepare("SELECT * FROM {$net}_collections_temp_{$category['id']} limit 20000");
                $stmt2->execute();
                $users = $stmt2->fetchAll();
                foreach($users as $user) {
                    // пишем в массив
                    $result[$net][$category['id']][] = $user;
                }
            }
        }

        // имеем массив пользователей, отсортированных по соцсетям и категориям

        $added = 0;
        $updated = 0;

        foreach($result as $net => $categories) {

            foreach($categories as $category_id => $users) {

                foreach($users as $user) {

                    // replace тут не подойдет

                    // Ишем - есть ли уже такой пользователь среди опубликованных
                    $stmt = $connect->prepare("SELECT * FROM {$net}_collections_{$category_id} WHERE profile_id=:profile_id");
                    $stmt->execute(array('profile_id' => $user['profile_id']));

                    $enabled_user = $stmt->fetch();
                    if (!$enabled_user) {
                        $added++;

                        $stmt = $connect->prepare("INSERT into {$net}_collections_{$category_id} (
                            profile_id,
                            user_fio,
                            user_avatar,
                            is_comment,
                            is_survey,
                            is_subscriber,
                            is_klass,
                            is_repost,
                            data,
                            created

                        ) VALUES(
                            :profile_id,
                            :user_fio,
                            :user_avatar,
                            :is_comment,
                            :is_survey,
                            :is_subscriber,
                            :is_klass,
                            :is_repost,
                            :data,
                            '" . time() . "'
                        )");
                        $stmt->execute(array(
                            'profile_id' => $user['profile_id'],
                            'user_fio' => $user['user_fio'],
                            'user_avatar' => $user['user_avatar'],
                            'is_comment' => $user['is_comment'],
                            'is_survey' => $user['is_survey'],
                            'is_subscriber' => $user['is_subscriber'],
                            'is_klass' => $user['is_klass'],
                            'is_repost' => $user['is_repost'],
                            'data' => $user['data']
                        ));

                    } else {
                        $updated++;
                        //если есть, то обновляем его
                        //обновляем user_type и добавляем новые данные data (не обновляем старые)

                        $data_array_published = json_decode($enabled_user['data'], true);
                        $data_array_temp = json_decode($user['data'], true);

                        // проходимся по temp и смотрим - есть ли уже какие данные в published по типам из temp
                        foreach($data_array_temp['urls'] as $temp_type => $temp_urls){
                            // $temp_urls - темповые урлы каждого типа, которые мы хотим добавить
                            foreach ($temp_urls as $temp_url) {
                                // для каждого $temp_url смотрим - есть ли он уже среди опубликованных
                                if (!isset($data_array_published['urls'][$temp_type]) || !in_array($temp_url, $data_array_published['urls'][$temp_type])){
                                    $data_array_published['urls'][$temp_type][] = $temp_url;
                                }
                            }
                        }

                        if ($data_array_published) {
                            $new_data = json_encode($data_array_published, JSON_UNESCAPED_UNICODE);
                        } else {
                            $new_data = '';
                        }

                        $is_comment_new = $enabled_user['is_comment'] == 1 ?: $user['is_comment'];
                        $is_survey_new = $enabled_user['is_survey'] == 1 ?: $user['is_survey'];
                        $is_subscriber_new = $enabled_user['is_subscriber'] == 1 ?: $user['is_subscriber'];
                        $is_klass_new = $enabled_user['is_klass'] == 1 ?: $user['is_klass'];
                        $is_repost_new = $enabled_user['is_repost'] == 1 ?: $user['is_repost'];

                        $stmt = $connect->prepare("
                        UPDATE
                            {$net}_collections_{$category_id}
                        SET
                            user_fio = :user_fio,
                            user_avatar = :user_avatar,
                            is_comment = :is_comment,
                            is_survey = :is_survey,
                            is_subscriber = :is_subscriber,
                            is_klass = :is_klass,
                            is_repost = :is_repost,
                            data = :data,
                            modified = '" . time() . "'
                        WHERE
                            profile_id=:profile_id
                        ");
                        $stmt->execute(array(
                            'profile_id' => $user['profile_id'],
                            'user_fio' => $user['user_fio'],
                            'user_avatar' => $user['user_avatar'],
                            'is_comment' => $is_comment_new,
                            'is_survey' => $is_survey_new,
                            'is_subscriber' => $is_subscriber_new,
                            'is_klass' => $is_klass_new,
                            'is_repost' => $is_repost_new,
                            'data' => $new_data
                        ));
                    }

                    $stmt = $connect->prepare("
                    DELETE FROM {$net}_collections_temp_{$category_id}
                    WHERE
                        profile_id=:profile_id
                    ");
                    $stmt->execute(array(
                        'profile_id' => $user['profile_id']
                    ));

                }
            }
        }
        return array(
            'added' => $added,
            'updated' => $updated
        );
    }
    return false;
}

$publish_temp_init = publish_temp_init();

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
    if ($load_users_collection_result) {
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

             <form action='' method="post" enctype="multipart/form-data">

                    <h4>Категория:</h4>
                <select name="category_id" class="form-control" required style="width:400px;">
                <option value="">выберите категорию
                <?php

                foreach ($categories as $key => $category) {


                    if (1) {/////////////////////////////////////////////////////////





                    ?>

                    <option value="<?php echo($category['id']);?>"><?php echo($category['name']);?>
                <?php }
                }


                ?>

                </select>



            <br>
                <h4>HTML код</h4>








                    <textarea cols="80" rows="5" name="html_text" class="form-control"></textarea>

                     <br>
                    <h4>Файл:</h4>
                    <input type="file" name="file">








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

<div class="well well-lg" style="padding-top:10px !important;padding-bottom:10px !important;  margin-bottom:0px !important; margin-top:20px !important;">
    <h3>Опубликовать данные</h3><br>

    <table width="100%">
        <tr>
            <td align="right" valign="top">

             <form action='' method="post">

                 <input type="hidden" name="publish_temp" value='1'>
                    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-export" aria-hidden="true"></span> Вперед!</button>

                </form>

            </td>
        </tr>

        <?php if ($publish_temp_init) { ?>
            <tr>
                <td align="left" valign="top">
                    <div class="col-md-4 mt-20" style="padding-left:0 !important">


                        <ul class="list-group">

                            <li class="list-group-item list-group-item-info">
                                <span class="badge" style="background-color: #FFF;color:rgb(30, 30, 203);"><?php echo($publish_temp_init['added'] + $publish_temp_init['updated']); ?></span>
                                Всего
                            </li>

                            <li class="list-group-item list-group-item-success">
                                <span class="badge" style="background-color: #FFF;color: #000;"><?php echo($publish_temp_init['added']); ?></span>
                                Добавлено новых пользователей
                            </li>

                            <li class="list-group-item list-group-item-warning">
                                <span class="badge" style="background-color: #FFF;color:rgb(207, 38, 38);"><?php echo($publish_temp_init['updated']); ?></span>
                                Обновлено пользователей
                            </li>

                        </ul></div>
                </td>
            </tr>
        <?php } ?>

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