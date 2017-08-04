<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic/functions.php');

if (auth_control() !==1){
    exit();
}


$show_users_number = !empty($_GET['show_users_number']) ? (int)$_GET['show_users_number'] : 5;


if($show_users_number>10) {$show_users_number=10;}

        $stmt = $connect->prepare("SELECT * FROM fb_imports WHERE is_invited=0 AND user_id=:user_id order by id ASC limit " . $show_users_number);
        $stmt->execute(array('user_id' => $user_id));
        $result = $stmt->fetchAll();




        ?>
<div id="loaded_users_buttons_up">
</div>
        <div class="row">







<?php if (!empty($_SESSION['fb']['last_viewed_users'])) { ?>
<div style="padding-bottom:10px;">
  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
    Предыдущие пользователи
  </button>
</div>
<div class="collapse" id="collapseExample">

            <div class="row">
                <div class="list-group col-md-5" style="padding-right:0; width: 640px; border-radius: 4px;border: 1px solid #337ab7; padding-bottom:10px;background-color: #fff;">
                    <?php
                    foreach ($_SESSION['fb']['last_viewed_users'] as $last_viewed_user) {
                        $last_viewed_user = preg_replace('#id=\"user_fio_.*?\"#', '',$last_viewed_user);
                        $last_viewed_user = preg_replace('#id=\"user_id_.*?\"#', '',$last_viewed_user);
                        $last_viewed_user = preg_replace('#<button .*? data-clipboard-target.*?</button>#', '',$last_viewed_user);
                        echo($last_viewed_user);
                    }
                    ?>
                    <div class="pull-left" style="padding:10px 0 0 10px"><i class="glyphicon glyphicon-arrow-up"></i><span style='margin-left:10px;'>Предыдущие пользователи</span></div>
                    <div class="pull-right" style="cursor:pointer;padding:10px 10px 0 0" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><span style='margin-left:10px; color:#337ab7'>свернуть</span></div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <?php
            //затем их затираем, чтобы новых записать
            $_SESSION['fb']['last_viewed_users'] = null;

        ?>
</div>
<?php } ?>



<div style="padding-bottom:10px;">
</div>

















        <div class="row">
            <div class="alert alert-warning col-md-5" role="alert" style="width: 640px;">Внимание! Пользователи ниже выводятся только один раз.</div>
            </div>

                <?php
                if ($result) { ?>



            <div class="row">
            <script>var links = new Array();</script>
            <div class="list-group col-md-5" style="width:640px; padding-right:0;">
                <?php



                foreach ($result as $key => $user) {
ob_start();

                    echo('

<div class="media list-group-item" style="margin-top:0;">
  <div class="media-left">

      <a target="_blank" href="'.$user['user_url'].'"><img class="media-object" style="width:57px; border-radius:5px;" src="' . $user['user_avatar'] . '" title="' . $user['user_fio'] . '"></a>
     </div>
  <div class="media-body">

  <div class="row">
  <div class="pull-left" style="width: 200px;">
    <input type="text" style="width:175px;margin-bottom:10px;" id="user_fio_'.$key.'" value="' . $user['user_fio'] . '">
    <div>
    <button style="background-color:#3b5998; color:#fff;" class="btn btn-primary btn-xs btn-clipboard" data-clipboard-target="#user_fio_'.$key.'" title="cкопировать в буфер обмена"><i class="glyphicon glyphicon-arrow-down"></i> cкопировать</button>

    </div>
    </div>
    ');
    if($user['profile_id']){
    echo('
      <div class="pull-right" style="width: 220px;">
        <div class="pull-left text-muted">или</div>


      <div class="pull-right">

      <input type="text" style="width:175px;margin-bottom:10px;" id="user_id_'.$key.'" value="' . $user['profile_id'] . '">
    <div>
    <button style="background-color:#3b5998; color:#fff;" class="btn btn-primary btn-xs btn-clipboard" data-clipboard-target="#user_id_'.$key.'" title="cкопировать в буфер обмена"><i class="glyphicon glyphicon-arrow-down"></i> cкопировать</button>

    </div>
    </div>
    </div>
    ');

    }
        echo('
    </div>
  </div>
</div>

                    </a>');

                    $stmt = $connect->prepare("update fb_imports set is_invited=1, modified = '".time()."' where user_url = :user_url AND user_id=:user_id");
                    $stmt->execute(array('user_url' => $user['user_url'], 'user_id' => $user_id));



                        $_SESSION['fb']['last_viewed_users'][] = ob_get_contents();


ob_end_flush();

                }
                ?>




                    <script>new Clipboard('.btn-clipboard');

                    </script>
</div>
            </div>
                    <?php
                }
                ?>
            </div>

<?php
require('generic/fb_show_users.php');
?>

<script>
          $('#loaded_users_buttons_up').html($('#loaded_users_buttons_down').html());
    $('#loaded_users_buttons_down').html('');
    </script>

