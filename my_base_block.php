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

    $count_non_invited = get_not_invited_count();
    $count_all_users = get_all_users_count();
    $count_invited = $count_all_users - $count_non_invited;
?>








<h3 class="mt-10-xs mb-20 mt-20-sm">Моя база</h3>
    <div class="row pr-20">
        <div class="alert alert-info  p-10 col-xs-12 col-sm-6" role="alert" style="/*width: 640px;*/">
            Всего: <b><?php echo($count_invited + $count_non_invited); ?></b><br>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-lg-6 pl-0 pr-0-xs pr-0-sm pr-10-lg">
            <ul class="nav nav-tabs border_bottom_self_loaded">
                <li role="presentation" class="active"><a class="self_loaded_nav" id='my_base_block_self_loaded_nav'>Загруженные мной</a></li>
                <li role="presentation"><a class="" id='my_base_block_imported_nav'>Из коллекции</a></li>
            </ul>
            <form id="my_users_list_search_form" class="form-inline" action="/load_users_base.php">
                <input type="hidden" name="net_code" value="<?= $net_code; ?>">
                <input type="hidden" name="load_type" value="1">
                <div class="well well-lg p-10-xs mb-10" style="border-top: 0;background-color: #fff;">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 pl-5">
                            <h4 style="margin-left:-5px">Типы пользователя:</h4>
                            <div style="margin-left:-5px"><?php $for_import_status = false; require('generic' . MY_DS . $net_code . '_users_types_checkbox.php'); ?></div>
                        </div>
                        <div class="col-xs-12 col-sm-6 pl-5">
                            <h4 style="margin-left:-5px">Статус пользователя:</h4>
                            <div class="radio" style="display: block;">
                                <label>
                                    <input type="radio" required value='1' name="user_status_showed">
                                    Приглашен
                                </label>
                            </div>
                            <div class="radio" style="display: block;">
                                <label>
                                    <input type="radio" required value='2' name="user_status_showed">
                                    Не приглашен
                                </label>
                            </div>
                            <div class="radio" style="display: block;">
                                <label>
                                    <input type="radio" required checked value='3' name="user_status_showed">
                                    Все
                                </label>
                            </div>
                            <div class="row" style="margin-right:-20px !important; display:none;" id="my_base_block_imported_categories">
                                <h4 style="margin-left:-5px">Категория:</h4>
                                <?php include('generic/client_imported_enabled_categories.php'); ?>
                            </div>
                            <div class="row" style="margin-right: -10px !important;">
                                <div class="col-xs-12 p-0 mb-5">
                                    <h4 style="margin-left:-5px">Имя:</h4>
                                    <input type="text" class="form-control" value='' name="user_fio">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 p-0">
                            <div class="alert p-10 alert-info mt-10 mb-0" id='my_users_list_type_condition_descr' style="display:none"></div>
                        </div>
                    </div>
                </div>
                <div class='mb-20'>
                    <div class='pull-left'>
                        <select class="form-control" name="sort_type" style="margin-right: 10px;">
                            <option value="1">Сначала новые</option>
                            <option value="2">Сначала первые</option>
                            <option value="3">По имени (А-Я)</option>
                            <option value="4">По имени (Я-А)</option>
                        </select>
                    </div>
                    <div class='pull-right'>
                        <span class="btn btn-success" id="my_users_list_search_btn">Поиск</span>
                    </div>
                    <div class='clearfix'></div>
                    <div class='pull-left mt-10'>
                        <button class="btn btn-info">Скачать .CSV файлом</button>
                    </div>
                    <div class='clearfix'></div>
                </div>
            </form>
            <div id="my_users_list_count" class="mb-10 ml-0"><h4>Найдено: <span></span></h4></div>
            <div class="list-group col-xs-12" style="padding-right:0; display:none; /*width: 640px;*/ border-radius: 4px;border: 1px solid #ddd; padding: 10px;background-color: #fff;" id='my_users_list'>
            </div>
            <div class="alert p-10 alert-warning col-xs-12 col-sm-6 mt-0 mb-0" id='my_users_list_empty_descr' style="display:none;">Не найдено</div>
        </div>
    </div>