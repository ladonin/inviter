        <?php












            $stmt = $connect->prepare("SELECT count(*) as count FROM fb_imports WHERE is_invited=1 AND user_id=:user_id");
            $stmt->execute(array('user_id' => $user_id));
            $count_invited = $stmt->fetchColumn();


            $stmt = $connect->prepare("SELECT count(*) as count FROM fb_imports WHERE is_invited=0 AND user_id=:user_id");
            $stmt->execute(array('user_id' => $user_id));
            $count_non_invited = $stmt->fetchColumn();
    ?>

            <div class="row">
                <div class="alert alert-info col-md-5" role="alert" style="width: 640px;">
                Всего показано: <?php echo($count_invited);?><br>
                Осталось: <?php echo($count_non_invited);?>
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



<div style="margin-bottom:20px;">
    <button id='show_users' class="btn btn-success"><span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span> Вывести</button>
    <select class="form-control" id="show_users_number" style="width:84px; display:inline;">
        <option value='5' <?php if(!empty($show_users_number) && $show_users_number==5){?>selected<?php } ?>>5</option>
        <option value='10'<?php if(!empty($show_users_number) && $show_users_number==10){?>selected<?php } ?>>10</option>
    </select>
<?php //echo $button_1_added_text; ?> пользователей
</div>





<div class="row">
    <form action='' method="post" style="margin-bottom: 10px;">
        <button name='export_users_excel' type="submit" class="btn btn-info"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Отчет (для Excel)</button>
    </form>
    <form action='' method="post" style="margin-bottom: 10px;">
        <button name='export_users' type="submit" class="btn btn-info"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Отчет (для CSV редакторов)</button>
    </form>

</div>


<?php } ?>