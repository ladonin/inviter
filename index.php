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
report_init();
$load_users_result = load_users_init();
include('generic/header.php');
?>
<?php include('generic/net_js.php'); ?>
<div class="well p-20-sm p-10" style="text-align:center; margin-bottom:0; background-color:#<?=get_net_header_background_color();?> ">
    <div class="row">
        <div class="pull-left">
            <a href="/odnoklassniki"><img src="/img/ok_logo.jpg" width="35" style="opacity:1; border-radius: 1000px;border: 2px solid #fff;"></a>
            <a href="/facebook"><img src="/img/fb_logo.jpg" width="35" style="opacity:1; border-radius: 1000px;border: 2px solid #fff;"></a>
            <a href="/vkontakte"><img src="/img/vk_logo.jpg" width="35" style="opacity:1; border-radius: 1000px;border: 2px solid #fff;"></a>
        </div>
        <div class="pull-right">







            <?php include('generic/personal_data.php'); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="pull-left mh-20-sm mh-10 mv-10">
        <div class="pull-left mr-10"><img src="/img/<?=$net_code;?>_logo.jpg" width="26" style="border-radius: 1000px; width: 24px; margin: 5px 0;">
        </div>
<div class="pull-left"><h5><b><?=get_net_title();?></b></h5></div><div class="clearfix"></div>


    </div>
    <div class="  mh-20-sm mh-10 mt-10 pull-right">











        <?php //$non_instruction = 1; require("generic/{$net_code}_instruction_menu.php"); ?>
    </div>
</div>
    <div class="ph-20 ph-10-xs">
<div class="row">

    <div class="col-xs-12 col-lg-6 pr-10-lg p-0">
<ul class="nav nav-tabs main-panel  nav-justified no-border-bottom-xs">
  <li role="presentation" class="active"><a class="pointer" id="promotion_nav">Продвижение</a></li>
  <li role="presentation"><a class="pointer" id="import_nav">Пополнение</a></li>
  <li role="presentation"><a class="pointer" id="my_base_nav">Моя&nbsp;база</a></li>
    <!--<li role="presentation"><a class="pointer" id="help_nav">Помощь</a></li>-->
</ul>
</div>
</div>
</div>




<div id="promotion_block" class="well well-lg p-10-xs" style="padding-top:10px !important;padding-bottom:20px !important; margin:0 !important;">
    <h3 class="mb-20">Пригласить пользователей</h3>
    <div class="row">
        <div id='show_users_block' class="col-xs-12 col-md-12 col-lg-12 p-0">
        <?php $button_1_added_text = ''; require("generic/show_users.php"); ?>
        </div>
    </div>
</div>


<div id="import_block" class="well well-lg p-10-xs" style="display:none; padding-top:10px !important;padding-bottom:20px !important;  margin-bottom:0px !important; margin-top:0px !important;">
    <h3>Импорт пользователей</h3>
    <div class="pv-10"><span class="btn btn-primary" data-toggle="modal" data-target="#collectionsModal">Взять из готовой коллекции</span></div>
    <div class="row">
        <div class="col-xs-12 col-md-6 pr-10-md p-0">
<?php
if ($load_users_result) {
    ?>
        <div>
            <ul class="list-group">

                <li class="list-group-item list-group-item-info">
                    <span class="badge" style="background-color: #FFF;color:rgb(30, 30, 203);"><?php echo(count($load_users_result['users_result'])); ?></span>
                    Найдено пользователей
                </li>

                <li class="list-group-item list-group-item-success">
                    <span class="badge" style="background-color: #FFF;color: #000;"><?php echo($load_users_result['inserts_count']); ?></span>
                    Добавлено пользователей
                </li>

                <li class="list-group-item list-group-item-warning">
                    <span class="badge" style="background-color: #FFF;color:rgb(207, 38, 38);"><?php echo((count($load_users_result['users_result']) - $load_users_result['inserts_count'])); ?></span>
                    Уже присутствующих пользователей обновлено
                </li>
            </ul></div>
<?php }
?>



                <h4>HTML код</h4>
                <form action='' method="post">

                    <textarea cols="80" rows="5" name="html_text" class="form-control"></textarea>
                    <br>
                    <h4>Откуда взят HTML код:</h4>
                    <div class="form-inline">
                    <?php require('generic' . MY_DS . $net_code . '_users_types.php'); ?>
                    </div>
                    <br>
                    <h4>Комментарий:</h4>
                    <input type="text"  maxlength="200" name="comment" class="form-control">
                    <br>
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Импорт</button>
                </form>

</div>
</div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="collectionsModal" tabindex="-1" role="dialog" style="text-align:left;">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0;">
            <div class="modal-header" style="background-color:#4C77AF; color:#fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;
                        opacity: 1;
                        border: 0;
                        font-weight: 400;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Перенести пользователей из готовой коллекции</h4>
            </div>
            <div class="modal-body">
                <form action='' method="post"  id='collection_import_form' class="form-inline">
                    <h4>Категория:</h4>
                    <select required name="category_id" id='collection_category_selector' class="form-control" style="width:100%; max-width: 400px;">
                        <option value="0">Выберите категорию...
<?php
$stmt = $connect->prepare("SELECT * FROM {$net_code}_collections_categories order by name ASC");
$stmt->execute();
$categories = $stmt->fetchAll();
foreach ($categories as $key => $category) {
    ?>
                            <option value="<?php echo($category['id']); ?>" data-name="<?php echo($category['name']); ?>"><?php echo($category['name']); ?>
<?php }
?>

                    </select>


<?php $for_import_status = true; require('generic' . MY_DS . $net_code . '_users_types_checkbox.php'); $for_import_status = false;?>


                    <div id="collection_category_func_buttons" style="padding-top:5px;">
                        <div id="get_category_type_users_count" style="margin-bottom: 10px;color: #4c77af;font-size: 21px;"></div>
                        <div id="get_category_type_user_cost" style="margin-bottom: 10px;font-size: 16px;"></div>
                        <button type="submit" disabled class="btn btn-success" id="collection_importer">Импортировать</button>
                        <div class="form-group" style="display: inline-block;"><input style="width:100px;" id="collection_importer_count" class="form-control" value="" type="text">
                            <label for="collection_importer_count" id="collection_importer_count_people">человек</label> <b style="color:#4c77af" id="collection_importer_cost"></b>
                        </div>
                    </div>
                </form>


                <div style="margin-top:10px">
                    <img src="/img/balance.png" width="20"> <b style="color:#636363;">Баланс:</b> <big id='balance_import'></big> <a style="cursor:pointer;" data-toggle="collapse" data-target="#balance_import_expander">пополнить</a>

                    <div id="balance_import_expander" style="margin-top:10px" class="collapse">
<?php require('generic' . MY_DS . 'balance_deposit.php'); ?>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>


<!--
<div id="help_block" class="well well-lg p-10-xs" style="display:none; padding-top:10px !important;padding-bottom:10px !important; margin:0 !important;">
    <h3>Помощь</h3><br>
    <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12 p-0">

        </div>
    </div>
</div>
-->

<?php
//$client_imported_categories = get_client_imported_categories();
//$has_loaded_users = has_loaded_users();
//$has_imported_users = $client_imported_categories ? true : false;
?>
<div id="my_base_block" class="well well-lg p-10-xs" style="display:none;padding-top:10px !important;padding-bottom:20px !important;  margin:0 !important;">


    <?php /*
      <div style="">
      <ul class="nav nav-tabs">
      <?php if ($has_loaded_users) { ?>
      <li role="presentation"><a style="cursor:pointer" id='report_self_loaded_nav'>Загруженные мной</a></li>
      <?php } ?>

      <?php if ($has_imported_users) { ?>
      <li role="presentation"><a style="cursor:pointer"  id='report_collection_loaded_nav'>Из коллекции</a></li>
      <?php } ?>
      </ul>
      <div class="bs-block">
      <?php if ($has_loaded_users) { ?>
      <div id="report_self_loaded_block">
      <!--<div style="display:inline-block; width:200px; margin-right: 10px;margin-bottom: 5px;">Формат файла</div>
      <br>-->
      <form action='' method="post" style="margin-bottom: 0px;" class="form-inline">
      <input type="hidden" name="load_type" value="1">
      <select class="form-control" name="report_type" style="width:200px; margin-right: 10px;">
      <option value="1">для Excel</option>
      <option value="2">для CSV редакторов</option>
      </select><button
      name='export_users' type="submit" class="btn btn-info mt-10-xs"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Скачать</button>
      </form>
      </div>
      <?php } ?>
      <?php if ($has_imported_users) { ?>
      <div id="report_collection_loaded_block">
      <!--<div style="display:inline-block; width:200px; margin-right: 10px;;margin-bottom: 5px;">Формат файла</div>
      <div style="display:inline-block;;margin-bottom: 5px;">Категория</div>
      <br>-->
      <form action='' method="post" style="margin-bottom: 0px;" class="form-inline">
      <input type="hidden" name="load_type" value="2">
      <select class="form-control" name="report_type" style="width:200px; margin-right: 10px;">
      <option value="1">для Excel</option>
      <option value="2">для CSV редакторов</option>
      </select><select
      class="form-control mt-10-xs" name="category" style="width:200px; margin-right: 10px;">
      <?php foreach ($client_imported_categories as $client_imported_category) { ?>
      <option value='<?php echo($client_imported_category['category_id']); ?>'><?php echo($client_imported_category['name']); ?></option>
      <?php } ?>
      </select><button
      name='export_users' type="submit" class="btn btn-info mt-10-xs"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Скачать</button>
      </form>
      </div>
      <?php } ?>
      </div>
      </div> */ ?>
</div>


<!-- Modal -->
<div class="modal fade" id="user_imported_dataModal" tabindex="-1" role="dialog" style="text-align:left;">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0;">
            <div class="modal-header" style="background-color:#4C77AF; color:#fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;
                        opacity: 1;
                        border: 0;
                        font-weight: 400;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Данные о пользователе</h4>
            </div>
            <div class="modal-body">






            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>




<script type="text/javascript">


$(document).ready(function(){












var my_base_block_type_users_3_was_checked = false;
var my_base_block_sort_type_in_self_loaded = 1;


function update_my_base_types_checkboxes() {
    var user_type_klass = $('input[type=checkbox][name=type_users_1]:checked', '#my_base_block').val();
    var user_type_subscriber = $('input[type=checkbox][name=type_users_2]:checked', '#my_base_block').val();
    var user_type_survey = $('input[type=checkbox][name=type_users_5]:checked', '#my_base_block').val();
    var user_type_comment = $('input[type=checkbox][name=type_users_6]:checked', '#my_base_block').val();
    var user_type_repost = $('input[type=checkbox][name=type_users_4]:checked', '#my_base_block').val();
    var user_type_search = $('input[type=checkbox][name=type_users_3]:checked', '#my_base_block').val();
    var user_type_all = $('input[type=checkbox][name=user_type_all]:checked', '#my_base_block').val();

    if (user_type_klass
            || user_type_subscriber
            || user_type_survey
            || user_type_comment
            || user_type_repost
            || user_type_search) {
        $('input[type=checkbox][name=user_type_all]', '#my_base_block').removeAttr("checked");
        $('#my_users_list_type_condition_descr').html(prepare_user_type_condition_text(user_type_klass, user_type_repost, user_type_survey, user_type_comment, user_type_subscriber, user_type_search)).show();

    } else {
        $('input[type=checkbox][name=user_type_all]', '#my_base_block').prop('checked', true);
        $('#my_users_list_type_condition_descr').hide();
    }

}










$(document).on('change', '#my_base_block input[type=checkbox][name=user_type_all]', function () {
    $('input[type=checkbox][name=type_users_1],input[type=checkbox][name=type_users_3],input[type=checkbox][name=type_users_2],input[type=checkbox][name=type_users_4],input[type=checkbox][name=type_users_5],input[type=checkbox][name=type_users_6]', '#my_base_block').removeAttr("checked");
});

$(document).on('change', '#my_base_block input[type=checkbox][name=type_users_1],#my_base_block input[type=checkbox][name=type_users_3],#my_base_block input[type=checkbox][name=type_users_4],#my_base_block input[type=checkbox][name=type_users_2],#my_base_block input[type=checkbox][name=type_users_5],#my_base_block input[type=checkbox][name=type_users_6],#my_base_block input[type=checkbox][name=user_type_all]', function () {
    update_my_base_types_checkboxes();
});
$(document).on('click', '#my_base_block #my_base_block_self_loaded_nav', function () {
    $('#my_base_block_self_loaded_nav').closest('li').addClass('active').find('a').addClass('self_loaded_nav');
    $('#my_base_block_imported_nav').closest('li').removeClass('active').find('a').removeClass('imported_nav');
    $(this).closest('.nav').removeClass('border_bottom_collection').addClass('border_bottom_self_loaded');
    $('.checkbox.type_3', '#my_base_block').show();
    $('#my_base_block_imported_categories').hide();

    if (my_base_block_type_users_3_was_checked == true) {
        $('input[type=checkbox][name=type_users_3]', '#my_base_block').prop('checked', true);
    }

    $('#my_users_list_search_form select[name=sort_type] option[value=1]').attr('disabled', false);
    $('#my_users_list_search_form select[name=sort_type] option[value=2]').attr('disabled', false);
    $("#my_users_list_search_form select[name=sort_type]").val(my_base_block_sort_type_in_self_loaded);
    $('#my_users_list_search_form input[name=load_type]').val(1);
    update_my_base_types_checkboxes();
});
$(document).on('click', '#my_base_block #my_base_block_imported_nav', function () {

    $('#my_base_block_self_loaded_nav').closest('li').removeClass('active').find('a').removeClass('self_loaded_nav');

    $('#my_base_block_imported_nav').closest('li').addClass('active').find('a').addClass('imported_nav');
    $(this).closest('.nav').removeClass('border_bottom_self_loaded').addClass('border_bottom_collection');

    if (typeof ($('input[type=checkbox][name=type_users_3]:checked', '#my_base_block').val()) == 'undefined') {
        my_base_block_type_users_3_was_checked = false;
    } else {
        my_base_block_type_users_3_was_checked = true;

    }

    $('input[type=checkbox][name=type_users_3]', '#my_base_block').removeAttr("checked");
    $('.checkbox.type_3', '#my_base_block').hide();
    $('#my_base_block_imported_categories').show();

    my_base_block_sort_type_in_self_loaded = $("#my_users_list_search_form select[name=sort_type]").val();
    if (my_base_block_sort_type_in_self_loaded < 3) {
        $("#my_users_list_search_form select[name=sort_type]").val(3);
    }
    $('#my_users_list_search_form select[name=sort_type] option[value=1]').attr('disabled', true);
    $('#my_users_list_search_form select[name=sort_type] option[value=2]').attr('disabled', true);


    $('#my_users_list_search_form input[name=load_type]').val(2);
    update_my_base_types_checkboxes();
});












var my_users_list = (function () {

    var fill_my_users_list_block = false; // защита от доп. подгрузок при скроллинге

    var fill_my_users_list = function (reset) {
        if (typeof (reset) == 'undefined') {
            var reset = 0;
        } else {
            var reset = 1;
        }

        var form = $("#my_users_list_search_form").serialize();


        $.ajax({
            url: '/user_in_list.php?' + form,
            type: 'POST',
            data: {
                reset: reset
            }
        }).done(function (data) {
            if (data) {
                var data = JSON.parse(data);
                if (typeof (reset) !== 'undefined' && reset) {
                    $('#my_users_list').html('');
                    $('#my_users_list_count').hide();
                }
                if (data.list_empty == '0') {
                    $('#my_users_list_empty_descr').hide();
                    $('#my_users_list').append(data.html).show();
                    $('#my_users_list_count').show();
                    $('#my_users_list_count span').html(data.count);
                    fill_my_users_list_block = false;
                    $('[data-toggle="tooltip"]').tooltip();
                } else {

                    $('#my_users_list_empty_descr').show();
                    $('#my_users_list').hide();
                    $('#my_users_list_count').hide();

                }
            }
        });
    }

    var interface = {
        init: function () {

            $(window).scroll(function () {
                if ($('.active #my_base_nav').length) {// если мы на странице списка пользователей
                    if (($(window).height() + $(window).scrollTop() + 2000 >= $(document).height()) && !fill_my_users_list_block) {
                        fill_my_users_list_block = true;
                        fill_my_users_list();
                    }
                }
            });
            $(document).on('click','#my_users_list_search_btn', function () {
                fill_my_users_list(true);
            });
            fill_my_users_list(true);
        },
        fill_my_users_list: function (reset) {
            fill_my_users_list(reset);
        },
        reset_scrol_blocking: function () {
            fill_my_users_list_block = false;
        }
    }
    return interface;
})();













function load_my_base_block(){
                $.ajax({
                    url: '/my_base_block.php?net_code=<?= $net_code; ?>',
                }).done(function (data) {
                        $('#my_base_block').html(data);





                        $('input[type=checkbox][name=user_type_all]', '#my_base_block').prop('checked', true).trigger('change');
$('#my_base_block input[name=user_status_showed]', '#my_base_block').val(3);


                });

}

load_my_base_block();
my_users_list.init();



























    $(document).on('click', '.user_data_type_link', function () {
        var url=$(this).attr('data-url');
        $.ajax({
            url: url,
            data: {}
        }).done(function (data) {
            $('#user_imported_dataModal .modal-body').html(data);
            $('#user_imported_dataModal').modal('show');
        });
    });


$('#promotion_nav').click(function(){
    $('#promotion_block').show();
    $('#promotion_nav').closest('li').addClass('active');

    $('#import_block').hide();
    $('#import_nav').closest('li').removeClass('active');

    $('#my_base_block').hide();
    $('#my_base_nav').closest('li').removeClass('active');


    $('#help_block').hide();
    $('#help_nav').closest('li').removeClass('active');

});

$('#import_nav').click(function(){
    $('#promotion_block').hide();
    $('#promotion_nav').closest('li').removeClass('active');

    $('#import_block').show();
    $('#import_nav').closest('li').addClass('active');


    $('#my_base_block').hide();
    $('#my_base_nav').closest('li').removeClass('active');

    $('#help_block').hide();
    $('#help_nav').closest('li').removeClass('active');
});

$('#my_base_nav').click(function(){
    $('#promotion_block').hide();
    $('#promotion_nav').closest('li').removeClass('active');

    $('#import_block').hide();
    $('#import_nav').closest('li').removeClass('active');



    $('#my_base_block').show();
    $('#my_base_nav').closest('li').addClass('active');

    $('#help_block').hide();
    $('#help_nav').closest('li').removeClass('active');
});



$('#help_nav').click(function(){
    $('#promotion_block').hide();
    $('#promotion_nav').closest('li').removeClass('active');

    $('#import_block').hide();
    $('#import_nav').closest('li').removeClass('active');



    $('#my_base_block').hide();
    $('#my_base_nav').closest('li').removeClass('active');

    $('#help_block').show();
    $('#help_nav').closest('li').addClass('active');
});





    if ($('#report_self_loaded_nav').length){
        $('#report_self_loaded_nav').closest('li').addClass('active');
        $('#report_collection_loaded_block').hide();
    } else if($('#report_collection_loaded_nav').length){
        $('#report_collection_loaded_nav').closest('li').addClass('active');
        $('#report_self_loaded_block').hide();
    }

    var show_users_reset = 0;

    $(document).on('click', '#reset_users_list', function () {
        show_users_reset = 1;
        reset_users_list();
    });

    $('#report_self_loaded_nav').click(function () {
        $('#report_collection_loaded_nav').closest('li').removeClass('active');
        $('#report_self_loaded_nav').closest('li').addClass('active');

        $('#report_self_loaded_block').show();
        $('#report_collection_loaded_block').hide();
    });

    $('#report_collection_loaded_nav').click(function () {
        $('#report_collection_loaded_nav').closest('li').addClass('active');
        $('#report_self_loaded_nav').closest('li').removeClass('active');

        $('#report_self_loaded_block').hide();
        $('#report_collection_loaded_block').show();
    });

//обновление доступных для импорта категорий
//$('#collectionsModal').on('shown.bs.modal', function() {
//    prepare_available_imported_categories();
//})
//
//function prepare_available_imported_categories(){
//
//    $.ajax({
//        url: "/get_available_imported_categories.php?net_code=<?= $net_code; ?>",
//        data: {}
//    }).done(function (data) {
//
//        var data = JSON.parse(data);
//        var selected_id = $("#collection_category_selector :selected").val();
//        $("#collection_category_selector").empty();
//        var select = $('#collection_category_selector');
//
//        $.each(data, function (index, value) {
//            var is_selected = '';
//            if (selected_id == value.category_id) {
//                is_selected = 'selected';
//            }
//            select.append("<option value='"+value.category_id+"' " + is_selected + ">" + value.name + "</option>");
//        });
//    });
//}



function prepare_available_imported_category_types(){

    var old_checkbox_is_all = $('#collection_import_form .type_users_checkbox .checkbox input[type=checkbox]:checked').val() === 'all' ? true :false;

    var category_id = $('#collection_category_selector').val();
    if (category_id == 0){
        $('#collection_import_form .condition').hide();
        $("#collection_category_func_buttons").hide();
        $('#collection_import_form .type_users_checkbox .checkbox').hide();
        $('#collection_import_form input[type=checkbox]').removeAttr("checked");
        $('#collection_import_form .notice').html('Выберите категорию выше').show();
    } else {

    $('#collection_import_form_status').html('<div class="mt-10">загрузка...</div>');

    $('#collection_import_form .condition').css('opacity', 0.5);
    $('#collection_import_form .notice').hide();
    $('#collection_import_form .type_users_checkbox').css('opacity', 0.5);
    $('#collection_import_form #collection_category_func_buttons').css('opacity', 0.5);
$("#collection_importer_count").prop('disabled', true);
    $.ajax({
        url: "/get_available_collection_imported_types_from_base_for_import.php?net_code=<?= $net_code; ?>",
        data: {category_id: category_id}
    }).done(function (data) {
//console.log(data);
        $('#collection_import_form_status').html('');
        var data = JSON.parse(data);


        var has_types = false;
        var types_count = 0;

        $('#collection_import_form .type_users_checkbox .checkbox').hide();

        // снимаем выбор с выбранных (если all = checked, то он тут снимается, с ним работаем ниже)
        $.each($('#collection_import_form .type_users_checkbox .checkbox input[type=checkbox]:checked'), function (index, value) {
            // если чекбокс пустой, то снимаем с него checked
            if(!data[$(this).val()]) {
                $(this).removeAttr("checked");
            }
        });
        // в итоге теперь со всех пустых снят checked

        // открываем непустые
        $.each(data, function (index, value) {
            $('#collection_import_form .type_users_checkbox .checkbox.type_'+value[0]).show();
            has_types = true;
            types_count++;
        });


        if (types_count > 1) {
            $('#collection_import_form .type_users_checkbox .checkbox.type_0').show();
            if (old_checkbox_is_all) {
                $('#collection_import_form .type_users_checkbox .checkbox.type_0 input[type=checkbox]').prop("checked", true);
            }
        }


        if (!has_types) {
            $('#collection_import_form .condition').hide();
        $('#collection_import_form .notice').html('Новых пользователей по данной категории не найдено. Информация поступила в обработку. <br>Приносим свои извинения, вскоре данная коллекция будет пополнена.').show();
        } else {
                $('#collection_import_form .notice').hide();
            }
            $("#collection_importer_count").prop('disabled', false);
    $('#collection_import_form .condition').css('opacity', 1);
    $('#collection_import_form .type_users_checkbox').css('opacity', 1);
    $('#collection_import_form #collection_category_func_buttons').css('opacity', 1);
calculate_price();
    });
    }
}






    $(document).on('click', '#reset_promotion', function () {




        swal({
            title: 'Сброс на начало',
            text: "Вы уверены, что хотите начать просмотр сначала?",
            html: true,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#64b5f6",
            confirmButtonText: 'Да',
            cancelButtonText: 'Нет'
        },
        function (isConfirm) {
            if (isConfirm) {


    $.ajax({
        url: "/reset_promotion.php?net_code=<?=$net_code;?>",
    }).done(function (data) {
        reset_users_list();
    });





            }
        });
    });









function reset_users_list() {
    $.ajax({
        url: "/reset_users_list.php?net_code=<?=$net_code;?>",
        data: {
            'show_type_load': $('#show_type_load').val()
        }
    }).done(function (data) {
        $("#show_users_block").html(data);


        if (prepare_imported_categories_select() === true) {
            $('#show_imported_categories').trigger('change', ['reset_users_list']);
        }
        $('#show_type_load').trigger('change');
        show_users_reset = 0;
    });
}




function alert_about_change_selection_users_view(step,status){

    if (typeof(status) == 'undefined') {
        status = 'none';
    }

    var show_type_load = $("#show_type_load").val();
    var show_self_load_users_types = $("#show_self_load_users_types").val();

    var show_self_load_users_types_text = $("#show_self_load_users_types :selected").text();
    var show_imported_categories_text = $("#show_imported_categories :selected").text();

    if (step==1) {
        if (status_types_load_users_not_changed === false) {
                                                                                                                    console.log(step);
                                                                                                                    console.log(status_types_load_users_not_changed);
            // если тип загрузки изменен
            // и теперь равен html
            if (show_type_load==1) {
                swal({
                    title: "Внимание",
                    text: 'Пользователи, <b style="color: #bc6060;">загруженные из коллекции</b> закончились.<br><br>Далее будут показываться пользователи, <br><b style="color: #6085bc;">загруженные вами</b>. <br><br>Выбранный по умолчанию тип пользователей: <br><b>' + show_self_load_users_types_text + '</b>',
                    html: true,
                    type: "warning"
                });
                return;
            }
        }
        // если тип пользователей изменен и тип загрузки == html
        if (((status_self_load_users_type_not_changed === false)
                || ((get_requested_show_self_load_users_types === '0' && show_self_load_users_types > 0))) && show_type_load==1) {
                                                                                                                    console.log(get_requested_show_self_load_users_types);
                                                                                                                    console.log(show_self_load_users_types);
                                                                                                                    console.log(show_type_load);
                swal({
                    title: "Внимание",
                    text: 'Произошла смена типа пользователей на: <br><b>' + show_self_load_users_types_text + '</b>',
                    html: true,
                    type: "warning"
                });
                return;
        }
    }


    // если после обновления списка пользователей (status === 'load_uses_list') и обновления типа пользователей (step ==2) для импортнутых ихз колллекции
    if ((step ==2) && (status === 'load_uses_list')) {
        var show_imported_categories_text = $("#show_imported_categories :selected").text();
        var show_imported_types_text = $("#show_imported_types :selected").text();



        if (status_types_load_users_not_changed === false) {
            if (show_type_load==2) {

            console.log(status_types_load_users_not_changed);
            console.log(show_type_load);

                swal({
                    title: "Внимание",
                    text: 'Пользователи, <b style="color: #6085bc;">загруженные вами</b> закончились.<br><br>Далее будут показываться пользователи,<br> <b style="color: #bc6060;">загруженные из коллекции</b>.<br><br>Выбранная по умолчанию категория: <br><b>' + show_imported_categories_text + '</b><br><br>Выбранный по умолчанию тип пользователей: <br><b>' + show_imported_types_text + '</b>',
                    html: true,
                    type: "warning"
                });
            }
        }

        // если изменилась категория и тип загрузки == 2
        if ((status_client_imported_enabled_category_not_changed === false) && show_type_load==2) {


            console.log(status_client_imported_enabled_category_not_changed);
            console.log(show_type_load);
                swal({
                    title: "Внимание",
                    text: 'Произошла смена категории на: <br><b>' + show_imported_categories_text + '</b><br><br>Выбранный по умолчанию тип пользователей: <br><b>' + show_imported_types_text + '</b>',
                    html: true,
                    type: "warning"
                });
                return;
        }

        // если изменился тип пользователей и тип загрузки == 2
        if ((status_types_load_users_by_collection_not_changed === false)  && show_type_load==2) {
            console.log(status_types_load_users_by_collection_not_changed);
            console.log(show_type_load);

                swal({
                    title: "Внимание",
                    text: 'Произошла смена типа пользователей на: <br><b>' + show_imported_types_text + '</b>',
                    html: true,
                    type: "warning"
                });
                status_types_load_users_by_collection_not_changed = null;
                return;
            }
    }
}
var loaded_users_status = true;
    function load_uses_list() {

        if (loaded_users_status === true) {
            loaded_users_status = false;
            var show_type_load = $("#show_type_load").val();
            var show_self_load_users_types = $("#show_self_load_users_types").val();
            var show_imported_categories = $("#show_imported_categories").val();
            var show_imported_types = $("#show_imported_types").val();
            var show_users_number = $("#show_users_number").val();


            if (!show_type_load) {
                show_type_load = 0;
            }
            $('#show_users').attr('disabled', true);

            $.ajax({
                url: "/show_users.php?net_code=<?=$net_code;?>",
                data: {
                    'show_type_load': show_type_load,
                    'show_self_load_users_types': show_self_load_users_types,
                    'show_imported_categories': show_imported_categories,
                    'show_imported_types': show_imported_types,
                    'show_users_reset': show_users_reset,
                    'show_users_number': show_users_number
                }
            }).done(function (data) {
                $("#show_users_block").html(data);

                alert_about_change_selection_users_view(1);

                $('#show_type_load').trigger('change', ['load_uses_list']);
                //////////$('#show_imported_categories').trigger('change', ['load_uses_list']);


      $('[data-toggle="tooltip"]').tooltip();
      $('#loaded_users_buttons_up').html($('#loaded_users_buttons_down').html());
        $('#loaded_users_buttons_down').html('');




                show_users_reset = 0;
                $('#show_users').attr('disabled', false);
                loaded_users_status = true;
            });
        }
    }




    $(document).on('click', '#show_users', function () {

        load_uses_list();

    });


    $(document).on('change', '#show_type_load', function (event, status) {
        var type_load = $(this).val();

        if (type_load == 1) {
            $('#show_self_load_users_types').closest('div').show();
            $('#show_imported_categories').closest('div').hide();
            $('#show_imported_types').closest('div').hide();
            $('#block_imported_types').hide();
        } else if (type_load == 2) {
            $('#show_self_load_users_types').closest('div').hide();
            $('#show_imported_categories').closest('div').show();
            $('#show_imported_types').closest('div').show();


                if (typeof(status) == 'undefined') {
                    status = 'show_type_load';
                }

            $('#show_imported_categories').trigger('change', [status]);
        }

    });








    $(document).on('change', '#show_imported_types', function () {
        setcookie('show_imported_type_<?=$net_code;?>', $(this).val());
    });



    $(document).on('change', '#show_imported_categories', function (event, status) {

        if (typeof(status)==='undefined'){

        setcookie('show_imported_category_<?=$net_code;?>', $(this).val());
    }

    if (typeof(status) == 'undefined') {
        status = 'none';
    }

        if ($("#show_type_load").val() != 2){
            return false;
        }



        var imported_category = parseFloat($(this).val());
        if (!(imported_category)) {
            return false;
        }
////////////alert(status);
        //var current_type = $("#show_imported_types").val();
        $.ajax({
            url: "/get_imported_types_users_by_category.php?net_code=<?=$net_code;?>",
            data: {
                'category_id': imported_category,
            }
        }).done(function (data) {
            var result = JSON.parse(data);
            $('#show_imported_types').remove();
            $('#block_imported_types').show().html('');
            if (result) {
                var select = $("<select></select>").attr("id", 'show_imported_types').attr("class", 'form-control');
                if (result.length > 1) {select.append("<option value='0'>Все пользователи</option>");}
                $("#block_imported_types").append(select);
                var old_type = getCookie('show_imported_type_<?=$net_code;?>', 0);
console.log(status);
console.log('old_type [show_imported_type_<?=$net_code;?>]:'+old_type);


                if (status === 'load_uses_list') {console.log('3455895748');
                    // не касается "все пользователи"
                    status_types_load_users_by_collection_not_changed = false;
                }

                $.each(result, function (index, value) {
                    var selected = '';
                    if ((old_type > 0) && (old_type == value[0])) {

                        if (status === 'load_uses_list') {console.log('234324');
                            status_types_load_users_by_collection_not_changed = true;
                        }

                        selected = 'selected';
                    }

                    select.append("<option value='"+value[0]+"' " + selected + ">" + value[1] + "</option>");

                });
        if (status === 'load_uses_list') {
                //console.log(getCookie('show_imported_type_<?=$net_code;?>'));
                // если несколько раз покажем "все пользователи"
                if ((old_type == 0) && (status_types_load_users_by_collection_not_changed === false)) {console.log('765484');
                    status_types_load_users_by_collection_not_changed = null;
                }

                // если со "все пользователи" перейдем на конкретный тип
                if ((old_type == 0) && ($('#show_imported_types').val() > 0)) {console.log('1455474');
                    status_types_load_users_by_collection_not_changed = false;
                }



                $('#show_imported_types').trigger('change');}

            }

            if (status === 'load_uses_list') {
                alert_about_change_selection_users_view(2, status);
            }

        });
    });

    $("#balance_import").html($("#balance").html());





    $("#collection_importer_count").keyup(function () {
        var value;
        if (!$(this).val()) {
            value = 0;
        } else {
            value = $(this).val();
        }
        var users_count = parseFloat(value).toFixed(0);

        var cost = 0;

        var users_max = Number($('#get_category_type_users_count').attr('data-count'));

        if (users_count >= 1) {

            if (users_count > users_max) {
                users_count = users_max;
            }
            if (users_count > <?=MAX_USERS_COUNT_PER_IMPORT?>) {
                users_count = <?=MAX_USERS_COUNT_PER_IMPORT?>;
            }



            $('#collection_importer_count').val(users_count);

            cost = round_cost(users_count * get_import_collection_request_cost_per_one_user());
            $("#collection_importer_cost").html('= ' + cost + ' руб.');

            //если не хватает денег или превышено количество запрашиваемых пользователей -->
            if (cost > round_cost($('#balance').html())) {
                $("#collection_importer_cost").css('color', '#e43a3a');
            } else {
                $("#collection_importer_cost").css('color', '#4c77af');
            }



            if (cost > round_cost($('#balance').html())) {
                $("#collection_importer").prop('disabled', true);
            } else {
                $("#collection_importer").prop('disabled', false);
            }
            // <--

        } else {
            $("#collection_importer_cost").html('');
            $("#collection_importer").prop('disabled', true);
        }
    });

    $("#collection_importer_count").trigger('keyup');










    $('input[type=checkbox][name=type_users_1],input[type=checkbox][name=type_users_3],input[type=checkbox][name=type_users_4],input[type=checkbox][name=type_users_2],input[type=checkbox][name=type_users_5],input[type=checkbox][name=type_users_6]', '#collection_import_form').change(function() {
        $('input[type=checkbox][name=user_type_all]', '#collection_import_form').removeAttr("checked");
    });

    $('input[type=checkbox][name=user_type_all]', '#collection_import_form').change(function() {
        $('input[type=checkbox][name=type_users_1],input[type=checkbox][name=type_users_3],input[type=checkbox][name=type_users_2],input[type=checkbox][name=type_users_4],input[type=checkbox][name=type_users_5],input[type=checkbox][name=type_users_6]', '#collection_import_form').removeAttr("checked");
    });

    $('input[type=checkbox]', '#collection_import_form').change(function () {
        var category_id = $('#collection_category_selector', '#collection_import_form').val();
        var user_type_klass = $('input[type=checkbox][name=type_users_1]:checked', '#collection_import_form').val();
        var user_type_subscriber = $('input[type=checkbox][name=type_users_2]:checked', '#collection_import_form').val();
        var user_type_survey = $('input[type=checkbox][name=type_users_5]:checked', '#collection_import_form').val();
        var user_type_comment = $('input[type=checkbox][name=type_users_6]:checked', '#collection_import_form').val();
        var user_type_all = $('input[type=checkbox][name=user_type_all]:checked', '#collection_import_form').val();
        var user_type_repost = $('input[type=checkbox][name=type_users_4]:checked', '#collection_import_form').val();

        if (category_id == 0 ||
                (!user_type_klass && !user_type_survey && !user_type_comment && !user_type_subscriber && !user_type_repost && !user_type_all)) {
            $("#collection_category_func_buttons").hide();
            $('#collection_import_form .condition').hide();
            return false;
        }

        if (user_type_all) {

        } else {








            var condition_text='';

            if (user_type_klass) {
                condition_text += get_type_name_by_id(user_type_klass) + ' <b>И</b> ';
            }
            if (user_type_repost) {
                condition_text += get_type_name_by_id(user_type_repost) + ' <b>И</b> ';
            }
            if (user_type_survey) {
                condition_text += get_type_name_by_id(user_type_survey) + ' <b>И</b> ';
            }
            if (user_type_comment) {
                condition_text += get_type_name_by_id(user_type_comment) + ' <b>И</b> ';
            }
            if (user_type_subscriber) {
                condition_text += get_type_name_by_id(user_type_subscriber) + ' <b>И</b> ';
            }
            $('#collection_import_form .condition').html(prepare_user_type_condition_text(user_type_klass, user_type_repost, user_type_survey, user_type_comment, user_type_subscriber)).show();










        }
        $("#collection_category_func_buttons").show();
        calculate_price();
    });




function prepare_user_type_condition_text(user_type_klass, user_type_repost, user_type_survey, user_type_comment, user_type_subscriber, user_type_search){

        if (typeof(user_type_search) === 'undefined') {
            user_type_search = '';
        }
            var condition_text='';

            if (user_type_klass) {
                condition_text += get_type_name_by_id(user_type_klass) + ' <b>И</b> ';
            }
            if (user_type_repost) {
                condition_text += get_type_name_by_id(user_type_repost) + ' <b>И</b> ';
            }
            if (user_type_survey) {
                condition_text += get_type_name_by_id(user_type_survey) + ' <b>И</b> ';
            }
            if (user_type_comment) {
                condition_text += get_type_name_by_id(user_type_comment) + ' <b>И</b> ';
            }
            if (user_type_subscriber) {
                condition_text += get_type_name_by_id(user_type_subscriber) + ' <b>И</b> ';
            }
            if (user_type_search) {
                condition_text += get_type_name_by_id(user_type_search) + ' <b>И</b> ';
            }
            return condition_text.slice(0, condition_text.length -10);
    }




    $('#collection_import_form #collection_category_selector').change(function () {
        prepare_available_imported_category_types();
    });

$('input[type=checkbox]', '#collection_import_form').trigger('change');














function calculate_price(){
        var category_id = $('#collection_category_selector').val();
        var user_type_klass = $('input[type=checkbox][name=type_users_1]:checked', '#collection_import_form').val();
        var user_type_subscriber = $('input[type=checkbox][name=type_users_2]:checked', '#collection_import_form').val();
        var user_type_survey = $('input[type=checkbox][name=type_users_5]:checked', '#collection_import_form').val();
        var user_type_comment = $('input[type=checkbox][name=type_users_6]:checked', '#collection_import_form').val();
        var user_type_all = $('input[type=checkbox][name=user_type_all]:checked', '#collection_import_form').val();
        var user_type_repost = $('input[type=checkbox][name=type_users_4]:checked', '#collection_import_form').val();
//$('#get_category_type_users_count').html('<span class="text-muted">идет подсчет...</span>');



    if (!category_id || (!user_type_klass && !user_type_repost && !user_type_subscriber && !user_type_survey && !user_type_comment && !user_type_all)) {

                $('#collection_import_form .condition').hide();
                $("#collection_category_func_buttons").hide();
                return false;
    }


        if (user_type_all) {
            user_type_klass = -1;
            user_type_subscriber = -1;
            user_type_survey = -1;
            user_type_comment = -1;
            user_type_repost = -1;
            $('input[type=checkbox][name=type_users_1]', '#collection_import_form').removeAttr("checked");
            $('input[type=checkbox][name=type_users_2]', '#collection_import_form').removeAttr("checked");
            $('input[type=checkbox][name=type_users_5]', '#collection_import_form').removeAttr("checked");
            $('input[type=checkbox][name=type_users_6]', '#collection_import_form').removeAttr("checked");
            $('input[type=checkbox][name=type_users_4]', '#collection_import_form').removeAttr("checked");
            $('#collection_import_form .condition').hide();
        }



        $('#collection_category_func_buttons').css('opacity', 0.5);
        $("#collection_importer_count").prop('disabled', true);


        $.ajax({
            url: "/get_category_type_users_count.php?net_code=<?=$net_code;?>",
            data: {
                'category_id': category_id,
                'user_type_1': user_type_klass,
                'user_type_4': user_type_repost,
                'user_type_2': user_type_subscriber,
                'user_type_5': user_type_survey,
                'user_type_6': user_type_comment
            }
        }).done(function (data) {
            $('#collection_category_func_buttons').css('opacity', 1);
            $("#collection_importer_count").prop('disabled', false);
            $('#get_category_type_users_count').html('Доступно: ' + data);

            if (data == 0) {
                $('#collection_importer').css('opacity', 0.5);
            } else {
                $('#collection_importer').css('opacity', 1);
            }


            $('#get_category_type_users_count').attr('data-count', data);

var cost = round_cost(get_import_collection_request_cost_per_one_user());
$('#get_category_type_user_cost').html('Стоимость: ' + cost + ' руб.');
            if (data > 0) {

//$("#collection_importer").prop('disabled', false);

                $("#collection_importer_count").show().val('');
                $("#collection_importer_count_people").show();
                $('#get_category_type_users_count').removeClass('text-muted');
            } else {

                $('#get_category_type_users_count').addClass('text-muted');

                $("#collection_importer").prop('disabled', true);
                $("#collection_importer_count").hide().val(0);
                $("#collection_importer_count_people").hide();
            }


            $("#collection_importer_count").trigger('keyup');
        });




    }





    $('#collection_importer').click(function (e) {
        e.preventDefault;


        $('#collection_importer').attr('disabled', true);
        $("#collection_importer_count").prop('disabled', true);
        $("#collection_category_func_buttons").css('opacity', 0.5);


















        var users_count = $('#collection_importer_count').val();
        var category_id = $('#collection_category_selector').val();
        //var user_type = $('input[type=radio][name=type_users]:checked', '#collection_import_form').val();
        var category_name = $('#collection_category_selector').find('option:selected').data('name');

        //var user_type_name = $('input[type=radio][name=type_users]:checked').data('name');

        var user_type_klass = $('input[type=checkbox][name=type_users_1]:checked', '#collection_import_form').val();
        var user_type_subscriber = $('input[type=checkbox][name=type_users_2]:checked', '#collection_import_form').val();
        var user_type_survey = $('input[type=checkbox][name=type_users_5]:checked', '#collection_import_form').val();
        var user_type_comment = $('input[type=checkbox][name=type_users_6]:checked', '#collection_import_form').val();
        var user_type_all = $('input[type=checkbox][name=user_type_all]:checked', '#collection_import_form').val();
        var user_type_repost = $('input[type=checkbox][name=type_users_4]:checked', '#collection_import_form').val();

        if (category_id == 0 ||
                (!user_type_klass && !user_type_survey && !user_type_comment && !user_type_subscriber && !user_type_repost && !user_type_all)) {
            return false;
        }

        if (user_type_all) {
            user_type_klass = -1;
            user_type_repost = -1;
            user_type_subscriber = -1;
            user_type_survey = -1;
            user_type_comment = -1;
        }


        $.ajax({
            url: "/import_users_from_base.php?net_code=<?=$net_code;?>",
            data: {
                'category_id': category_id,
                'user_type_1': user_type_klass,
                'user_type_4': user_type_repost,
                'user_type_2': user_type_subscriber,
                'user_type_5': user_type_survey,
                'user_type_6': user_type_comment,
                'users_count': users_count,
                'user_id': '<?php echo($user_id); ?>',
            }
        }).done(function (data) {

            if (data > 0) {

                $('#collection_category_selector').trigger('change');

                var text = "Импортировано <b>" + data + "</b> человек";
                if ((data > 1) && (data < 5)) {
                    text = "Импортировано <b>" + data + "</b> человека";
                }

                $('#balance').html((parseFloat($('#balance').html()) - data * get_import_collection_request_cost_per_one_user()).toFixed(2));
                $("#balance_import").html($("#balance").html());


                if (!$('#loaded_users_list').length) {
                    show_users_reset = 1;
                    reset_users_list();
                } else {
                    $('#reset_users_list').show();
                }

                var user_type_name = '';
                var types_count = 0;
                var type_text;
                if (user_type_all) {
                    user_type_name = get_type_name_by_id(-1);
                    types_count++;
                } else {
                    if (user_type_klass) {
                        user_type_name += get_type_name_by_id(1) + '<br>';
                        types_count++;
                    }
                    if (user_type_subscriber) {
                        user_type_name += get_type_name_by_id(2) + '<br>';
                        types_count++;
                    }
                    if (user_type_survey) {
                        user_type_name += get_type_name_by_id(5) + '<br>';
                        types_count++;
                    }
                    if (user_type_comment) {
                        user_type_name += get_type_name_by_id(6) + '<br>';
                        types_count++;
                    }
                    if (user_type_repost) {
                        user_type_name += get_type_name_by_id(4) + '<br>';
                        types_count++;
                    }
                    user_type_name = user_type_name.slice(0,-4);
                }
                if (types_count>1) {
                    type_text = 'Типы';
                } else {
                    type_text = 'Тип';
                }
                swal({
                    title: "",
                    text: text + ' <br> Категория: <b>' + category_name + '</b><br>'+ type_text + ': <br><b>' + user_type_name + '</b>',
                    html: true,
                    type: "success"
                });
                $('#collection_importer').attr('disabled', false);
        $("#collection_importer_count").prop('disabled', false);
        $("#collection_category_func_buttons").css('opacity', 1);


load_my_base_block();


            }
        });

        return false;

    });

function prepare_imported_categories_select() {
        if($("#show_imported_categories").length) {
            var show_imported_category = getCookie('show_imported_category_<?=$net_code;?>', 0);
            if (show_imported_category) {


                if ($('select#show_imported_categories option[value='+show_imported_category+']').length) {


                    $("#show_imported_categories").val(show_imported_category);
                }
                return true;
            }
        }
        return false;
}
prepare_imported_categories_select();
    $('#show_type_load').trigger('change');
    ///////////////////////$('#show_imported_categories').trigger('change', ['background']);
    $('#collection_category_selector').trigger('change');









    $(document).on('click','.note_comment_icon', function(){
        $(this).toggleClass('rotate_m90');
        $(this).closest('.row').find('.note_comments').toggle();

    });







    $(document).on('click','#loaded_users_list .user_link,#my_users_list .user_link', function(){
        $(this).closest('.list-group-item').find('.user_seen_label').show();
    });







                function get_type_name_by_id(user_type) {
                    if (user_type == -1) {
                        return "Любой";
                    } else if (user_type == 1) {
                        return "<?=get_type_name_by_id(1);?>";
                    } else if (user_type == 2) {
                        return "<?=get_type_name_by_id(2);?>";
                    } else if (user_type == 3) {
                        return "<?=get_type_name_by_id(3);?>";
                    } else if (user_type == 5) {
                        return "<?=get_type_name_by_id(5);?>";
                    } else if (user_type == 6) {
                        return "<?=get_type_name_by_id(6);?>";
                    } else if (user_type == 4) {
                        return "<?=get_type_name_by_id(4);?>";
                    }
                }

    <?php if ($load_users_result) { ?>
        $('#import_nav').trigger('click');
    <?php } ?>

});

</script>











<table style="display:none;">
    <tr>
        <td align="center" valign="top" style="padding:10px;">

            <!--LiveInternet counter--><script type="text/javascript">
                document.write("<a href='//www.liveinternet.ru/click' " +
                        "target=_blank><img src='//counter.yadro.ru/hit?t25.2;r" +
                        escape(document.referrer) + ((typeof (screen) == "undefined") ? "" :
                        ";s" + screen.width + "*" + screen.height + "*" + (screen.colorDepth ?
                                screen.colorDepth : screen.pixelDepth)) + ";u" + escape(document.URL) +
                        ";" + Math.random() +
                        "' alt='' title='LiveInternet: показано число посетителей за" +
                        " сегодня' " +
                        "border='0' width='88' height='15'><\/a>")
            </script><!--/LiveInternet-->


        </td>
    </tr>
</table>

<?php include('generic/footer.php'); ?>