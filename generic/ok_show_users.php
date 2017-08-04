<div id="loaded_users_buttons_down"><?php
    $count_non_invited = get_not_invited_count();
    $status_types_load_users_not_changed = empty($show_type_load) ? 'null' : 'false';
    $status_self_load_users_type_not_changed = empty($show_self_load_users_types) ? 'null' : 'false';
    $status_client_imported_enabled_category_not_changed = empty($show_imported_categories) ? 'null' : 'false';



    ?>

    <div class="row">
        <div class="alert alert-info col-md-5" role="alert" style="width: 640px;">
            <?php //Всего показано: <?php echo($count_invited);<br> ?>
            Осталось всего: <?php echo($count_non_invited); ?>
        </div>
    </div>

    <span class="btn btn-primary" style="margin-bottom:20px; display:none" id='reset_users_list'>Обновить</span>

    <?php if (!$count_non_invited) { ?>
        <div class="row">
            <div class="alert alert-danger col-md-5" role="alert" style="width: 640px;">Список пользователей пуст, добавьте их через форму ниже или импортируйте из коллекции</div>
        </div>
    <?php }

    if ($count_non_invited) { ?>
        <div class="row" style="margin-bottom:5px">
            <div class="pull-left" style="margin-right:10px;margin-bottom:5px; width:200px">
                <select class="form-control" id="show_type_load">
                    <?php








                    $types_loads_users = ok_get_types_loads_users();
                    if ($types_loads_users) {
                        ?>
                        <?php

                        foreach ($types_loads_users as $type_loads_users) {
                            $type_load_users_id = $type_loads_users;
                            ?>

                            <option value='<?php echo($type_load_users_id); ?>' <?php if (!empty($show_type_load) && $show_type_load == ($type_load_users_id)) { $status_types_load_users_not_changed = 'true'; ?>selected<?php } ?>><?php echo(get_load_type_name_by_id($type_load_users_id)); ?></option>

                        <?php } ?>
    <?php } ?>

                </select>
            </div>

            <?php
            $self_load_users_types = get_client_self_load_enabled_users_types();
            if ($self_load_users_types) {

                ?><div class="pull-left" style="margin-right:10px;margin-bottom:5px;">

                    <select class="form-control" id="show_self_load_users_types">
                        <?php if (count($self_load_users_types) > 1) { ?><option value='0'>Все пользователи</option><?php } ?>
                                <?php foreach ($self_load_users_types as $self_load_users_type) { ?>
                            <option value='<?php echo($self_load_users_type); ?>'
                                    <?php if (!empty($show_self_load_users_types) && $show_self_load_users_types == $self_load_users_type) { $status_self_load_users_type_not_changed = 'true';?>selected<?php } ?>
                                    ><?php echo(get_type_name_by_id($self_load_users_type)); ?></option>
        <?php } ?>
                    </select>


                </div>
    <?php } ?>

            <div class="pull-left" style="margin-right:10px;margin-bottom:5px;">
                <?php
                $client_imported_enabled_categories = get_client_imported_enabled_categories();
                if ($client_imported_enabled_categories) {
                    ?>
                    <select class="form-control" id="show_imported_categories">
                                <?php foreach ($client_imported_enabled_categories as $client_imported_enabled_category) { ?>
                            <option value='<?php echo($client_imported_enabled_category['category_id']); ?>'
                                    <?php if (!empty($show_imported_categories) && $show_imported_categories == $client_imported_enabled_category['category_id']) { $status_client_imported_enabled_category_not_changed = 'true';?>selected<?php } ?>
                                    ><?php echo($client_imported_enabled_category['name']); ?></option>
                    <?php } ?>
                    </select>
    <?php } ?>
            </div>
            <div class="pull-left" style="margin-bottom:5px;" id="block_imported_types">

            </div>
        </div>

        <div class="row">
            <div class="col-xs-4" style="padding-left:0; padding-right:0;">
                <div class="input-group" style="margin-bottom:20px;">

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












<script>
var get_requested_show_self_load_users_types = '<?php echo((isset($_GET['show_self_load_users_types'])) ? (int)$_GET['show_self_load_users_types'] : '');?>';
var status_types_load_users_by_collection_not_changed = null; //  определяется в $(document).on('change', '#show_imported_categories')
var status_types_load_users_not_changed = <?php echo($status_types_load_users_not_changed);?>;
var status_self_load_users_type_not_changed = <?php echo($status_self_load_users_type_not_changed);?>;
var status_client_imported_enabled_category_not_changed = <?php echo($status_client_imported_enabled_category_not_changed);?>;
</script>

