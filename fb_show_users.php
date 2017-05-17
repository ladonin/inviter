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

        <div class="row">
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


                    echo('

<div class="media list-group-item" style="margin-top:0;">
  <div class="media-left">

      <a target="_blank" href="'.$user['user_url'].'"><img class="media-object" style="width:57px; border-radius:5px;" src="' . $user['user_avatar'] . '" title="' . $user['user_fio'] . '"></a>
     </div>
  <div class="media-body">

  <div class="row">
  <div class="pull-left" style="width: 180px;">
    <input type="text" style="width:155px;margin-bottom:10px;" id="user_fio_'.$key.'" value="' . $user['user_fio'] . '">
    <div>
    <button style="background-color:#3b5998; color:#fff;" class="btn btn-primary btn-xs btn-clipboard" data-clipboard-target="#user_fio_'.$key.'" title="cкопировать в буфер обмена"><i class="glyphicon glyphicon-arrow-down"></i> cкопировать</button>

    </div>
    </div>
    ');
    if($user['profile_id']){
    echo('
      <div class="pull-right" style="width: 200px;">
        <div class="pull-left text-muted">или</div>


      <div class="pull-right">

      <input type="text" style="width:155px;margin-bottom:10px;" id="user_id_'.$key.'" value="' . $user['profile_id'] . '">
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

                    $stmt = $connect->prepare("update fb_imports set is_invited=1, modified = '".time()."' where profile_id = :profile_id AND user_id=:user_id");
                    $stmt->execute(array('profile_id' => $user['profile_id'], 'user_id' => $user_id));
                }
                ?>

                    <script>


new Clipboard('.btn-clipboard');

                    </script>
</div>
            </div>
                    <?php
                }
                ?>
            </div>
        <?php

$button_1_added_text = ' следующие ';
require('generic/fb_show_users.php');