<?php
                        $client_imported_enabled_categories = get_client_imported_enabled_categories();
                        if ($client_imported_enabled_categories) {
                            ?>
                    <div class="col-xs-12 col-sm-12 p-0 pr-10 mb-10" style="/*333margin-right:10px;margin-bottom:5px;*/">
                            <select class="form-control" id="show_imported_categories" name="imported_category">
                                <?php foreach ($client_imported_enabled_categories as $client_imported_enabled_category) { ?>
                                    <option value='<?php echo($client_imported_enabled_category['category_id']); ?>'
                                            <?php if (!empty($show_imported_categories) && $show_imported_categories == $client_imported_enabled_category['category_id']) {
                                                $status_client_imported_enabled_category_not_changed = 'true';
                                                ?>selected<?php } ?>
                                            ><?php echo($client_imported_enabled_category['name']); ?></option>
        <?php } ?>
                            </select>
                    </div>
    <?php } ?>