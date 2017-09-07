
$('input[type=checkbox][name=user_type_all]', '#my_base_block').change(function () {
    $('input[type=checkbox][name=type_users_1],input[type=checkbox][name=type_users_3],input[type=checkbox][name=type_users_2],input[type=checkbox][name=type_users_4],input[type=checkbox][name=type_users_5],input[type=checkbox][name=type_users_6]', '#my_base_block').removeAttr("checked");
});

$('input[type=checkbox][name=type_users_1],input[type=checkbox][name=type_users_3],input[type=checkbox][name=type_users_4],input[type=checkbox][name=type_users_2],input[type=checkbox][name=type_users_5],input[type=checkbox][name=type_users_6],input[type=checkbox][name=user_type_all]', '#my_base_block').change(function () {
    update_my_base_types_checkboxes();
});
$('#my_base_block #my_base_block_self_loaded_nav').click(function () {
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
var my_base_block_type_users_3_was_checked = false;
var my_base_block_sort_type_in_self_loaded = 1;
$('#my_base_block #my_base_block_imported_nav').click(function () {

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
$('input[type=checkbox][name=user_type_all]', '#my_base_block').prop('checked', true).trigger('change');
$('#my_base_block input[name=user_status_showed]', '#my_base_block').val(3);



var my_users_list = (function () {

    var fill_my_users_list_block = false; // защита от доп. подгрузок при скроллинге

    var fill_my_users_list = function (reset) {
        if (typeof (reset) == 'undefined') {
            var reset = 0;
        } else {
            var reset = 1;
        }

        var form = jQuery("#my_users_list_search_form").serialize();


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
            $('#my_users_list_search_btn').click(function () {
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
