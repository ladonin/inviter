<div id="loaded_users_buttons_down">        <?php












            $stmt = $connect->prepare("SELECT count(*) as count FROM fb_imports WHERE is_invited=1 AND user_id=:user_id");
            $stmt->execute(array('user_id' => $user_id));
            $count_invited = $stmt->fetchColumn();


            $stmt = $connect->prepare("SELECT count(*) as count FROM fb_imports WHERE is_invited=0 AND user_id=:user_id");
            $stmt->execute(array('user_id' => $user_id));
            $count_non_invited = $stmt->fetchColumn();
    ?>

            <div class="row">
                <div class="alert alert-info col-md-5" role="alert" style="width: 640px;">
                <?php //Всего показано: <?php echo($count_invited);<br>?>
                    Осталось всего: <b><?php echo($count_non_invited);?></b>
                </div>
            </div>





    <?php


    if (!$count_non_invited) {?>


                     <div class="row">
                        <div class="alert alert-danger col-md-5" role="alert" style="width: 640px;">Список пользователей пуст, добавьте их через форму ниже</div>

                    </div>


    <?php }

if($count_non_invited){


    ?>

        <div class="row">
            <div class="col-xs-4" style="padding-left:0; padding-right:0;">
                <div class="input-group" style="margin-bottom:10px;">

                    <select class="form-control" id="show_users_number" style="width:200px">
                        <option value='5' <?php if (!empty($show_users_number) && $show_users_number == 5) { ?>selected<?php } ?>>5 пользователей</option>
                        <option value='10'<?php if (!empty($show_users_number) && $show_users_number == 10) { ?>selected<?php } ?>>10 пользователей</option>
                    </select>
    <?php //echo $button_1_added_text;   ?>
                    <span class="input-group-btn pull-left"><button id='show_users' class="btn btn-success" style="margin-left:10px; border-radius:4px;"><span class="glyphicon glyphicon-chevron-down" style="top:2px; padding-right:5px" aria-hidden="true"></span>Показать</button></span>
                </div>
            </div>
        </div>








<?php } ?>

    </div>