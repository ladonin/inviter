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
require_once('generic/net_functions.php');
require_once("generic/{$net_code}_functions.php");
include('generic/auth_control.php');
report_init();
$load_users_result = load_users_init();
include('generic/header.php');
?>
<?php include('generic/net_js.php'); ?>






<div class="well p-20-sm p-10" style="text-align:center; margin-bottom:0; background-color:#<?= get_net_header_background_color(); ?> ">
    <div class="row">
        <div class="pull-left">
            <a href="/odnoklassniki"><img src="/img/ok_logo.jpg" width="35" style="opacity:1; border-radius: 1000px;border: 2px solid #fff;"></a>
            <a href="/facebook"><img src="/img/fb_logo.jpg" width="35" style="opacity:1; border-radius: 1000px;border: 2px solid #fff;"></a>
            <a href="/vkontakte"><img src="/img/vk_logo.jpg" width="35" style="opacity:1; border-radius: 1000px;border: 2px solid #fff;"></a>
        </div>
        <div class="pull-right">
            <?php include('generic/user_data.php'); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="pull-left mh-20-sm m-10">
        <div class="pull-left mr-10"><img src="/img/<?= $net_code; ?>_logo.jpg" width="26" style="border-radius: 1000px; width: 24px; margin: 5px 0;"></div><div class="pull-left"><h5><b><?= get_net_title(); ?></b></h5></div><div class="clearfix"></div>
    </div>
    <div class="mh-20-sm m-10 pull-right">
        <h4 style="margin:5px 0 5px 0;line-height: 24px;"><span style="font-weight:light;"><small>Категория:</small></span> <b><?php echo(get_category_name($_GET['category_id']));?></b></h4>
    </div>
</div>

<div class="well well-lg p-10-xs" style="padding-top:10px !important; margin:0 !important;">


    <h3>Информация о пользователе</h3><br>


    <?php
    $user = get_imported_user_data($_GET['category_id'], $_GET['profile_id']);
    $link = prepare_link_to_user($user['profile_id']);
    $avatar = $user['user_avatar'] ? $user['user_avatar'] : '/img/no-photo.png';
    ?>
    <div class=row">
        <div class="col-xs-12 col-sm-6 col-md-5 col-lg-4 pr-10-md p-0">
            <div class="alert alert-info" role="alert">
                <div class="media mb-0">
                    <div class="media-left">

                        <a href="<?= $link; ?>" onclick="window.open('<?= $link; ?>', '_blank', 'left=300, top=100, width=900, height=800');
                    return false;">
                            <img class="media-object" style="width:50px; border-radius:5px;" src="<?= $avatar; ?>" title="<?= $user['user_fio']; ?>">
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading" style="margin-top: 5px;"><a href="<?= $link; ?>" onclick="window.open('<?= $link; ?>', '_blank', 'left=300, top=100, width=900, height=800');
                    return false;"><?= ($user['user_fio'] ? unescapeUTF8EscapeSeq($user['user_fio']) : $user['profile_id']); ?></a></h4>






                    </div>
                </div>

</div>

<h4>Активность</h4>

                <?php
                $data_array = json_decode($user['data'], true);



$hidden_links = array();
                foreach ($data_array['urls'] as $type_id => $type_urls) {

                    $i = 0;
                    foreach ($type_urls as $key => $type_url) {
                        $i++;
                        if ($i > 2) {
                            $hidden_links[$type_id][$key] = $type_url;
                            unset($data_array['urls'][$type_id][$key]);
                        }
                    }
                }


foreach ($data_array['urls'] as $type_id => $type_urls) {

                    ?>


                    <div id="type_<?php echo($type_id);?>" class="alert alert-warning mb-0 mt-20" role="alert" style="max-width: 300px;"><?php echo('<img style="margin-right:5px;" src="/img/' . get_type_code_by_id($type_id) . '.png" width="25"> <b>' . get_type_name_by_id($type_id) . '</b>'); ?>

                        <?php
                        foreach ($type_urls as $key => $type_url) {
                            echo("<div class='mt-10'><a target='blank' href='$type_url'>Ссылка #" . ($key + 1) . '</a></div>');
                        }

if(!empty($hidden_links[$type_id])){
                        ?>

                        <div class="mt-10 pointer" onclick="$('#hidden_links_<?php echo($type_id);?>').show(); $(this).hide()"><a>Показать все</a></div>

                        <div style="display:none" id="hidden_links_<?php echo($type_id);?>">
                            <?php
                        foreach ($hidden_links[$type_id] as $key => $type_url) {
                            echo("<div class='mt-10'><a target='blank' href='$type_url'>Ссылка #" . ($key + 1) . '</a></div>');
                        }



                            ?>

                        </div>

<?php } ?>



                        </div><?php
                    }
                    ?>


        </div>
    </div>
    <div class="clearfix"></div>
</div>