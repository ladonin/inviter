<?php


            $stmt = $connect->prepare("SELECT count(*) as count FROM ok_imports WHERE is_invited=1 AND user_id=:user_id");
            $stmt->execute(array('user_id' => $user_id));
            $count_invited = $stmt->fetchColumn();


            $stmt = $connect->prepare("SELECT count(*) as count FROM ok_imports WHERE is_invited=0 AND user_id=:user_id");
            $stmt->execute(array('user_id' => $user_id));
            $count_non_invited = $stmt->fetchColumn();
    ?>

            <div class="row">
                <div class="alert alert-info col-md-5" role="alert" style="width: 640px;">
                Всего показано: <?php echo($count_invited);?><br>
                Осталось: <?php echo($count_non_invited);?>
                </div>
            </div>

    <span class="btn btn-primary" style="margin-bottom:20px; display:none" id='reset_users_list'>Обновить</span>




    <?php


    if (!$count_non_invited) {?>


                     <div class="row">
                        <div class="alert alert-danger col-md-5" role="alert" style="width: 640px;">Список пользователей пуст, добавьте их через форму ниже</div>

                    </div>


    <?php }

if($count_non_invited){


    ?>
<div class="row" style="margin-bottom:5px">


<div class="pull-left" style="margin-right:10px;margin-bottom:5px;">
<select class="form-control" id="show_type_load">
    <option value='1'>Все</option>

    <?php $types_loads_users = get_types_loads_users();
    if($types_loads_users) { ?>
        <?php foreach($types_loads_users as $type_loads_users) {
        $type_load_users_id = $type_loads_users['is_imported'] + 2;
        ?>

    <option value='<?php echo($type_load_users_id);?>' <?php if(!empty($show_type_load) && $show_type_load==($type_load_users_id)){?>selected<?php } ?>><?php echo(get_load_type_name_by_id($type_load_users_id));?></option>

        <?php } ?>
    <?php } ?>

  </select>
</div>
<div class="pull-left" style="margin-right:10px;margin-bottom:5px;">
    <?php $self_load_users_types = get_client_self_load_enabled_users_types();
    if($self_load_users_types) { ?>

    <select class="form-control" id="show_self_load_users_types">
        <option value='1'>Все пользователи</option>
        <?php foreach($self_load_users_types as $self_load_users_type) { ?>
            <option value='<?php echo($self_load_users_type['user_type']);?>'
            <?php if(!empty($show_self_load_users_types) && $show_self_load_users_types==$self_load_users_type['user_type']){?>selected<?php } ?>
            ><?php echo(get_type_name_by_id($self_load_users_type['user_type']));?></option>
        <?php } ?>
    </select>
    <?php } ?>
</div>
<div class="pull-left" style="margin-right:10px;margin-bottom:5px;">
    <?php $client_imported_enabled_categories = get_client_imported_enabled_categories();
    if($client_imported_enabled_categories) { ?>
    <select class="form-control" id="show_imported_categories">
        <option value='1'>Все категории</option>
        <?php foreach($client_imported_enabled_categories as $client_imported_enabled_category) { ?>
            <option value='<?php echo($client_imported_enabled_category['category_id']);?>'
            <?php if(!empty($show_imported_categories) && $show_imported_categories==$client_imported_enabled_category['category_id']){?>selected<?php } ?>
            ><?php echo($client_imported_enabled_category['name']);?></option>
        <?php } ?>
    </select>
    <?php } ?>
</div>
<div class="pull-left" style="margin-bottom:5px;">

    <?php $client_imported_enabled_types = get_client_imported_enabled_types();
    if($client_imported_enabled_types) { ?>
    <select class="form-control" id="show_imported_types">
        <option value='1'>Все пользователи</option>
        <?php foreach($client_imported_enabled_types as $client_imported_enabled_type) { ?>
            <option value='<?php echo($client_imported_enabled_type['user_type']);?>'
            <?php if(!empty($show_imported_types) && $show_imported_types==$client_imported_enabled_type['user_type']){?>selected<?php } ?>
            ><?php echo(get_type_name_by_id($client_imported_enabled_type['user_type']));?></option>
        <?php }
        ?>
    </select>
    <?php } ?>
</div>
    </div>





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


