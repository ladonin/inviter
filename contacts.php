<?php




error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Контакты';
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');

require_once('generic/generic_functions.php');


include('generic/header.php');?>




<div class="row">

<div class="col-xs-1 col-sm-4">
</div>

<div class="col-xs-10 col-sm-4">

    <div class="text-center"><a href="/" style="border:0; text-decoration:none;"><img src="/img/logo.png"></a></div>

<h1 class="page-header mt-20" id="glyphicons">Контакты</h1>

<div style="margin-bottom:10px;">
    <div class="pull-left" style="width:130px"><img src="/img/mail.png" style="margin-right:10px;border-radius: 5px;" width="30"> Email:</div>
    <div class="pull-left" style="color: #4380be ; line-height:30px">service@inviter.biz</div>
    <div class="clearfix"></div>
</div>
<div style="margin-bottom:10px;">
    <div class="pull-left" style="width:130px"><img src="/img/phone.png" style="margin-right:10px;border-radius: 5px;" width="30"> Телефон:</div>
    <div class="pull-left" style="color: #333; line-height:30px">+79190225620</div>
    <div class="clearfix"></div>
</div>


<div style="margin-bottom:20px;">
    <div class="pull-left" style="width:130px"><img src="/img/skype.png" style="margin-right:10px;border-radius: 5px;" width="30"> Skype:</div>
    <div class="pull-left" style="color: #4380be ; line-height:30px">ladoninsasha</div>
    <div class="clearfix"></div>
</div>





<div style="margin-bottom:10px;">
    <div class="pull-left" style="margin-right:10px;">

<a href="https://ok.ru/profile/509040024720" target="_blank"><img src="/img/ok_circle.png" width="30" style="border-radius: 1000px;"/></a>

    </div>
    <div class="pull-left" style="margin-right:10px;">

<a href="https://www.facebook.com/alexander.ladonin" target="_blank"><img src="/img/facebook_circle.png" width="30" style="border-radius: 1000px;"/></a>

    </div>
    <div class="pull-left">

<a href="https://vk.com/ladonin" target="_blank"><img src="/img/vk_logo.jpg" width="30" style="border-radius: 1000px;"/></a>

    </div>


    <div class="clearfix"></div>
</div>




</div>

<div class="col-xs-1 col-sm-4">
</div>
</div>




















<?php include('generic/footer.php'); ?>