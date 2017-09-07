<div id="loaded_users_buttons_down">

    <?php
    $count_non_invited = get_not_invited_count();
    $count_all_users = get_all_users_count();
    $status_types_load_users_not_changed = empty($show_type_load) ? 'null' : 'false';
    $status_self_load_users_type_not_changed = empty($show_self_load_users_types) ? 'null' : 'false';
    $status_client_imported_enabled_category_not_changed = empty($show_imported_categories) ? 'null' : 'false';
    $count_invited = $count_all_users - $count_non_invited;
    ?>

    <?php if ($count_invited) { ?>
    <div class="col-xs-12 p-0">

        <button class="btn btn-danger btn-sm mb-10" id="reset_promotion">Сбросить на начало</button>

    </div>
        <?php } ?>



    <div class="col-xs-12 col-md-6 pr-10-md p-0">


    <?php if ($count_non_invited) { ?>
        <div class="row">
            <div class="alert alert-info  p-10 col-xs-12" role="alert" style="/*width: 640px;*/">
                Показано: <b><?php echo($count_invited);?></b><br>
                Осталось: <b><?php echo($count_non_invited); ?></b>
            </div>
        </div>
    <?php } ?>
    <span class="btn btn-primary" style="margin-bottom:20px; display:none" id='reset_users_list'>Обновить</span>

    <?php if (!$count_non_invited) { ?>
        <div class="row">
            <div class="alert alert-danger  p-10 col-xs-12" role="alert" style="/*width: 640px;*/">Список пользователей пуст, добавьте их через форму или импортируйте из коллекции во вкладке "Пополнение"</div>
        </div>
    <?php
    }

    if ($count_non_invited) {
        ?>
        <div class="row" style="margin-bottom:5px">


            <div class="col-xs-8 col-sm-5 p-0">




                <div class="row">


                    <div class="col-xs-12 col-sm-12 p-0 pr-10 mb-10" style="/*111margin-right:10px;margin-bottom:5px;*/">
                        <select class="form-control" id="show_type_load">
                            <?php
                            $types_loads_users = get_types_loads_users($styled = true);
                            if ($types_loads_users) {

                                foreach ($types_loads_users as $type_load_users_id => $disabled) {
                                    ?><option value='<?php echo($type_load_users_id); ?>' <?=$disabled;?>
                                    <?php if (!empty($show_type_load) && $show_type_load == ($type_load_users_id) && !$disabled) {
                                        $status_types_load_users_not_changed = 'true';
                                        ?>selected<?php } ?>><?php echo(get_load_type_name_by_id($type_load_users_id)); ?></option>

                                <?php } ?>
    <?php } ?>

                        </select>
                    </div>
                    <?php
                    $self_load_users_types = get_client_self_load_enabled_users_types();
                    if ($self_load_users_types) {
                        ?><div class="col-xs-12 col-sm-12 p-0 pr-10 mb-10" style="/*2222margin-right:10px;margin-bottom:5px;*/">

                            <select class="form-control" id="show_self_load_users_types">
                                <?php if (count($self_load_users_types) > 1) { ?><option value='0'>Все пользователи</option><?php } ?>
                                <?php foreach ($self_load_users_types as $self_load_users_type) { ?>
                                    <option value='<?php echo($self_load_users_type); ?>'
                                            <?php if (!empty($show_self_load_users_types) && $show_self_load_users_types == $self_load_users_type) {
                                                $status_self_load_users_type_not_changed = 'true';
                                                ?>selected<?php } ?>
                                            ><?php echo(get_type_name_by_id($self_load_users_type)); ?></option>
                        <?php } ?>
                            </select>
                        </div>
                        <?php } ?>


                        <?php include('generic/client_imported_enabled_categories.php'); ?>

                    <div class="col-xs-12 col-sm-12 p-0 pr-10 mb-10" style="/*444*/margin-bottom:5px; height:34px;" id="block_imported_types">
                            <select class="form-control" style="color:#c8c8c8 !important">
                                <option value='0' disabled selected>Обновление...</option>
                            </select>
                    </div>
                </div>
            </div>





            <div class="col-xs-4 col-sm-7 p-0">




                <div class="row">
                    <div class="col-xs-12 col-sm-7 pr-10-sm  p-0 mb-10">


                            <select class="form-control" id="show_users_number">
                                <option value='5' <?php if (!empty($show_users_number) && $show_users_number == 5) { ?>selected<?php } ?>>5 человек</option>
                                <option value='10'<?php if (!empty($show_users_number) && $show_users_number == 10) { ?>selected<?php } ?>>10 человек</option>
                            </select>

                    </div>
                    <div class="col-xs-12 col-sm-5 p-0" style="padding-left:0; padding-right:0;">

    <?php //echo $button_1_added_text;     ?>
                        <span class="input-group-btn"><button id='show_users' class="btn btn-success" style="border-radius:4px; width:100%">Показать</button></span>
                    </div>
                </div>
            </div>


















        </div>





















<?php } ?>

</div>
</div>












<script>
    var get_requested_show_self_load_users_types = '<?php echo((isset($_GET['show_self_load_users_types'])) ? (int) $_GET['show_self_load_users_types'] : ''); ?>';
    var status_types_load_users_by_collection_not_changed = null; //  определяется в $(document).on('change', '#show_imported_categories')
    var status_types_load_users_not_changed = <?php echo($status_types_load_users_not_changed); ?>;
    var status_self_load_users_type_not_changed = <?php echo($status_self_load_users_type_not_changed); ?>;
    var status_client_imported_enabled_category_not_changed = <?php echo($status_client_imported_enabled_category_not_changed); ?>;
</script>

