<div class="type_users_checkbox pl-5">
    <div class="mt-10">
        <div class="checkbox type_1" style="display: block;">
            <label>
                <input type="checkbox" value='1' name="type_users_1" data-name='<?= get_type_name_by_id(1); ?>'>
                <?= get_type_name_by_id(1); ?>
            </label>
        </div>
        <div class="checkbox type_4" style="display: block;">
            <label>
                <input type="checkbox"  value='4' name="type_users_4" data-name="<?= get_type_name_by_id(4); ?>">
                <?= get_type_name_by_id(4); ?>
            </label>
        </div>
        <div class="checkbox type_5" style="display: block;">
            <label>
                <input type="checkbox"  value='5' name="type_users_5" data-name="<?= get_type_name_by_id(5); ?>">
                <?= get_type_name_by_id(5); ?>
            </label>
        </div>
        <div class="checkbox type_6" style="display: block;">
            <label>
                <input type="checkbox"  value='6' name="type_users_6" data-name="<?= get_type_name_by_id(6); ?>">
                <?= get_type_name_by_id(6); ?>
            </label>
        </div>
        <div class="checkbox type_2" style="display: block;">
            <label>
                <input type="checkbox"  value='2' name="type_users_2" data-name="<?= get_type_name_by_id(2); ?>">
                <?= get_type_name_by_id(2); ?>
            </label>
        </div>
        <?php if (empty($for_import_status)) { ?>
            <div class="checkbox type_3" style="display: block;">
                <label>
                    <input type="checkbox"  value='3' name="type_users_3" data-name="<?= get_type_name_by_id(3); ?>">
                    <?= get_type_name_by_id(3); ?>
                </label>
            </div>
        <?php } ?>
        <div class="checkbox type_0" style="display: block;">
            <label>
                <input type="checkbox"  value='all' name="user_type_all" data-name="Любой">
                Любой
            </label>
        </div>
    </div>
</div>
<?php if ($for_import_status) { ?>
    <div id="collection_import_form_status" class="pl-5" style="width:100%; max-width: 400px;"></div>
    <div class="alert p-10 alert-info condition mt-10 mb-0" style="width:100%; max-width: 400px; display:none"></div>
    <div class="alert p-10 alert-warning notice mt-10 mb-0" style="width:100%; max-width: 400px;"></div>
<?php } ?>