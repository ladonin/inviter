
<script>







var MY_USER_IMPORT_COST = new Object();

<?php foreach(MY_USER_IMPORT_COST as $type => $amount) {?>
    MY_USER_IMPORT_COST.<?=$type;?> = <?=get_user_import_cost($type);?>;
<?php } ?>

function get_import_collection_request_cost_per_one_user() {

    var data = new Object();

    var user_type_repost = $('input[type=checkbox][name=type_users_4]:checked', '#collection_import_form').val();
    var user_type_klass = $('input[type=checkbox][name=type_users_1]:checked', '#collection_import_form').val();
    var user_type_subscriber = $('input[type=checkbox][name=type_users_2]:checked', '#collection_import_form').val();
    var user_type_survey = $('input[type=checkbox][name=type_users_5]:checked', '#collection_import_form').val();
    var user_type_comment = $('input[type=checkbox][name=type_users_6]:checked', '#collection_import_form').val();
    var user_type_all = $('input[type=checkbox][name=user_type_all]:checked', '#collection_import_form').val();

    var most_expensive = 0;
    var most_expensive_type = '';

    data['REPOST'] = user_type_repost;
    data['KLASS'] = user_type_klass;
    data['SUBSCRIBER'] = user_type_subscriber;
    data['SURVEY'] = user_type_survey;
    data['COMMENT'] = user_type_comment;

    if (user_type_all) {
        return round_cost(<?php echo(get_user_import_cost('GENERIC'));?>);
    }


    var count_types = 0;
    var tmp_type = '';
    $.each(data, function (type,value) {
        if (value) {
            count_types++;
            tmp_type = type;

            if (most_expensive < MY_USER_IMPORT_COST[type]) {
                most_expensive = MY_USER_IMPORT_COST[type];
                most_expensive_type = type;
            }
        }
    });
    // если выбран 1 тип
    if (count_types == 1) {
        return round_cost(MY_USER_IMPORT_COST[tmp_type]);
    }
    if (count_types == 0) {
        return null;
    }

    // если выбрано 2+ типа
    var price = most_expensive;

    $.each(MY_USER_IMPORT_COST, function (type,amount) {
        if ((data[type]) && (most_expensive_type != type)) {
            price += amount * <?=MY_USER_IMPORT_COST_ADDITIONAL_KOEF;?>;
        }
    });
    return round_cost(price);
}

</script>