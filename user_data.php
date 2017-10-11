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
?>


    <h3>Информация о пользователе</h3><br>


    <?php
    $user = get_imported_user_data($_GET['category_id'], $_GET['profile_id']);
    $link = prepare_link_to_user($user['profile_id']);
    $avatar = $user['user_avatar'] ? $user['user_avatar'] : '/img/no-photo.png';
    ?>

            <div class="alert alert-info" role="alert" style="max-width: 300px;">
                <div class="media mb-0">
                    <div class="media-left">

                        <a href="<?= $link; ?>" onclick="window.open('<?= $link; ?>', '_blank', 'left=300, top=100, width=1000, height=800');
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


