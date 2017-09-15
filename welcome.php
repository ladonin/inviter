<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Inviter.biz - база активных пользователей.';
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
$invite_table = 'odnoklassniki_group_admins_collector';

require_once('generic/generic_functions.php');

include('generic/header.php');

$code = my_create_password();
?>




<table class="panel-1" style="position:relative">
    <tr>
        <td valign="middle" align="center">
            <div class="pv-20">
                <div class="logo">
                    <img src="/img/logo.png"/>
                </div>

                <div class="slogan mt-20">
                    Собирать подписчиков теперь станет проще
                </div>


                <div class="mt-20">
                    <a class="button-register btn btn-info dropdown-toggle" href="/registration"></span>Создать аккаунт</a>
                </div>
                <div class="mt-20">
                    <a class="button-login btn btn-success dropdown-toggle" href="/enter">Войти</a>
                </div>
                <div class="nav-down mt-20">
                    <div>
                        подробнее <br><i class="glyphicon glyphicon-triangle-bottom"></i>
                    </div>
                </div> </div>
        </td>
    </tr>
</table>
<script>




    var screenHeight = $(window).height();
    $('.panel-1').height(screenHeight);



</script>



<style>
    body{
        color: #333;
    }
    #title{
        cursor:pointer;
    }
    .panel-1{

        width: 100%;
        background-image: url('/img/background.jpg');
        background-repeat: repeat;
        background-color: #788799;






    }
    .slogan,
    .nav-down,
    .contacts {
        color:#efefef;
        text-shadow: 1px 1px 1px rgb(86, 86, 86);
    }
    .button-register,
    .button-login,
    .logo{
        text-align:center;
        position:relative;

        z-index:2;
    }
    .slogan{
        text-align:center;
        position:relative;
        width:100%;
        font-size: 21px;z-index:3;






        font-size: 32px;
        line-height: 40px;
        font-weight: 400;-webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;



    }


    .nav-up,
    .nav-down{
        text-align: center;
        width: 100%;
        font-size: 21px;
    }
    .nav-down
    {

        position: relative;z-index:4;



    }


    .nav-down div,
    .nav-up div{
        width:150px;
        cursor:pointer;
        margin:0 auto;
    }

    .logo{
        padding: 15px;
        background-color: #fff;
        width: 180px;
        border-radius: 1000px;

    }

    .button-register,
    .button-login{
        width:170px;
    }










</style>

















<div class="panel-2 p-10-xs  p-20-sm" style="
     background-color: #fff;">







    <div class="clearfix"></div>








    <div  style="max-width:1118px; margin:0 auto; font-size: 15px;    line-height: 23px;">

        <div class="row">
            <div class="col-xs-12 mb-20 text-center p-0">
                <img src='/img/index_image.png' style="max-width: 100%;" >
            </div>
            <div class="col-xs-12 mb-20 p-0 text-center"  style="    border-bottom: 1px solid #dadfe8;
    padding-bottom: 30px !important;">
                <h3 id="title" style="color:#36689b;
                    font-size: 32px;
                    line-height: 40px;
                    font-weight: 400;
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                    text-rendering: optimizeLegibility;"><?php echo($title); ?> </h3>


<h4 style="
                    color: #5c6e80;
                    /* font-size: 32px; */
                    line-height: 40px;
                    font-weight: 400;
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                    text-rendering: optimizeLegibility;
                    ">Работает с Facebook, Одноклассники и Вконтакте. Скоро и с Telegram.</h4>
                  <div class="alert alert-info mt-20 p-10" role="alert" style=" margin:0 auto;max-width: 680px; background-color: #f5f5f5; border:0">
                      <h3 style="color:#36689b; margin-top:10px"><span class="glyphicon glyphicon-ok mr-10" aria-hidden="true" style="color:#5cb85c"></span>Уже более <b style="color: #5c6e80;">1 000 000</b> активных пользователей в базе</h3>
            </div>




            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-4 mb-20 p-0-xs p-0-sm p-10-md text-center">
                <h3 style="color:#36689b;">Что проект делает?</h3>
                <div class="text-left pb-20">Проект предоставляет, а также позволяет самостоятельно загружать активных пользователей социальных сетей <b>Одноклассники</b>, <b>Facebook</b> и <b>Вконтакте</b>.</div>



                <h3 style="color:#36689b;">Зачем это нужно?</h3>
                <div class="text-left">Раскрутка групп, сообществ, персональных страниц.</div>



            </div>








            <div class="col-xs-12 col-md-4 mb-20 p-0-xs p-0-sm p-10-md text-center">
                <h3 style="color:#36689b;">Какие бывают пользователи?</h3>

                <div class="text-left">Пользователя можно загрузить вручную из нужного поста или другого источника (как загружать - можно посмотреть в <a href="/help?noaccount=1" target="_blank">инструкции</a>, п.2) или приобрести из готовой коллекции. <div class="mv-10">Все пользователи делятся на категории по интересам.</div>
                    <div class="mb-10">
                        Каждый пользователь имеет свои набор "атрибутов активности":</div>

                    <div style="color:#36689b;" class="mv-10">
                        <div class="text-left mt-10"><img style="margin-right:10px;" src="/img/klass.png" width="25">ставит Like, "Класс" или "Мне нравится"</div>
                        <div class="text-left mt-10"><img style="margin-right:10px;" src="/img/repost.png" width="25">делает репост</div>
                        <div class="text-left mt-10"><img style="margin-right:10px;" src="/img/comment.png" width="25">оставляет комментарии в постах</div>
                        <div class="text-left mt-10"><img style="margin-right:10px;" src="/img/survey.png" width="25">учавствует в опросах, голосованиях</div>
                        <div class="text-left mt-10"><img style="margin-right:10px;" src="/img/subscribe.png" width="25">подписывается на группы, сообщества</div>
                    </div>

                    Также есть дополнительный атрибут:
                    <div style="color:#36689b;" class="mv-10">
                        <img style="margin-right:10px;" src="/img/search.png" width="25">пользователи из результата поиска
                    </div>
                    Он доступен для тех, кто загружен вручную.


                </div>

            </div>

            <div class="col-xs-12 col-md-4 mb-20 p-0-xs p-0-sm p-10-md text-center">
                <h3 style="color:#36689b;">Преимущества проекта:</h3>

                <div class="text-left mb-10"><span class="glyphicon glyphicon-ok mr-10" aria-hidden="true" style="color:#5cb85c"></span>Вы никогда не вышлите приглашение пользователям два и более раз подряд, тем самым обезопасите себя от лишних жалоб и вероятных санкций от администрации соцсети.</div>
                <div class="text-left mb-10"><span class="glyphicon glyphicon-ok mr-10" aria-hidden="true" style="color:#5cb85c"></span>Можно работать сразу с 3 соцсетями - <b>Одноклассники</b>, <b>Facebook</b> и <b>Вконтакте</b>.</div>
                <div class="text-left"><span class="glyphicon glyphicon-ok mr-10" aria-hidden="true" style="color:#5cb85c"></span>Программа ведет подробный отчет о собранных пользователях и при необходимости предоставляет его в виде CSV файла.</div>
            </div>

        </div>


        <div class="row">



            <div class="col-xs-12 col-md-4 mb-20 p-0-xs p-0-sm p-10-md text-center">
                <h3 style="color:#36689b;">Как все это работает?</h3>
                <div class="text-left mt-10">Процесс ручной, не автоматизированный, так что вы не нарушите Регламент вышеупомянутых соцсетей.</div>
                <div class="text-left mt-10">Вы сохраняете в своей базе пользователей из самой подходящей на ваш взгляд категории и с нужными "атрибутами активности".</div>
                <div class="text-left mt-10">Затем приглашаете их к себе, но делаете это на порядок быстрее, если бы делали это самостоятельно.</div>
            </div>








            <div class="col-xs-12 col-md-4 mb-20 p-0-xs p-0-sm p-10-md text-center">
                <h3 style="color:#36689b;">Сколько подписчиков можно получить в день?</h3>

                <div class="text-left mt-10">Столько, сколько разрешают правила соцсети.</div>
                <div class="text-left mt-10">Если у вас несколько аккаунтов или просто есть друзья, готовые вам помочь, то намного больше.</div>
            </div>




            <div class="col-xs-12 col-md-4 mb-20 p-0-xs p-0-sm p-10-md text-center">
                <h3 style="color:#36689b;">Стоимость:</h3>

                <div class="text-left"><img style="margin-right:10px;" src="/img/balance.png" width="25">При регистрации сейчас:

                    <div class="text-left mt-10"><span class="glyphicon glyphicon-ok mr-10" aria-hidden="true" style="color:#5cb85c"></span><b>вы получаете 2000 рублей в подарок</b></div>
                    <div class="text-left mt-10"><span class="glyphicon glyphicon-ok mr-10" aria-hidden="true" style="color:#5cb85c"></span><b>никакой абонентской платы</b></div>

                    <div class="text-left mt-10"><span class="glyphicon glyphicon-ok mr-10" aria-hidden="true" style="color:#5cb85c"></span><b>загрузка пользователей самостоятельно - бесплатно</b></div>

                    <div class="text-left mt-10">Платный только импорт пользователей из готовой коллекции</div>


                </div>
            </div>

        </div>




    </div>

















    <table style="display:none;">
        <tr>
            <td align="center" valign="top" style="padding-top:10px;">


                <!--LiveInternet counter--><script type="text/javascript">
                    document.write("<a href='//www.liveinternet.ru/click' " +
                            "target=_blank><img src='//counter.yadro.ru/hit?t26.1;r" +
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










    <div class="nav-up">
        <div>
            <i class="glyphicon glyphicon-triangle-top"></i><br>наверх
        </div>
    </div>






</div>



















<script>





    $('.nav-down div, #title').click(function () {
        $('body,html').animate({scrollTop: screenHeight}, '500');
    });

    $('.nav-up div').click(function () {
        $('body,html').animate({scrollTop: 0}, '500');
    });




</script>
<?php include('generic/footer.php'); ?>