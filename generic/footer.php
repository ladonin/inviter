</div>



<div id="footer" class="row" style="padding:15px 10px;
/*background-image: url('http://www.httpdebugger.com/content/images/bottom-bg.jpg');*/
background-repeat: repeat;
background-color: #495666;
color: #e6e6e6;">

    <div class="pull-left" style="width:100px;">
        <div class="text-left contacts" style="margin-bottom:5px"><a href="/help" target="_blank" style="color:#fff">Помощь</a></div>
        <div class="text-left contacts" style="margin-bottom:10px"><a href="/contacts" target="_blank" style="color:#fff">Контакты</a></div>
    </div>



    <div class="clearfix"></div>





    <div style="text-align: center;
    width: 100%;
    color:#e6e6e6;
    border-top: 1px solid #6b7887;
    padding-top: 11px;
    margin-top: 0;">

    <div  style="text-align:center; margin-bottom:10px; width:100%">



            Copyright © 2017 Inviter.biz. Все права защищены.



    </div>
<!-- begin WebMoney Transfer : accept label -->
<a href="http://www.megastock.com/" target="_blank"><img src="/img/acc_blue_on_white_ru.png" alt="www.megastock.com" border="0"/></a>
<!-- end WebMoney Transfer : accept label -->
<!-- begin WebMoney Transfer : attestation label -->
<a href="https://passport.webmoney.ru/asp/certview.asp?wmid=368278032550" target="_blank"><img src="/img/v_blue_on_white_ru.png" alt="Здесь находится аттестат нашего WM идентификатора 368278032550" border="0" /><br/><span style="font-size: 0,7em; color:#fff;">Проверить аттестат</span></a>
<!-- end WebMoney Transfer : attestation label -->

    </div>






</div>










<script>



    var screenHeight = document.body.clientHeight;
    $('#content').css('min-height',(screenHeight - $('#footer').height() - 30) + 'px');



var MY_USER_IMPORT_COST = new Object();

<?php foreach(MY_USER_IMPORT_COST as $type => $amount) {?>
    MY_USER_IMPORT_COST.<?=$type;?> = <?=$amount;?>;
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
        return round_cost(MY_USER_IMPORT_COST['GENERIC']);
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
</body>
</html>