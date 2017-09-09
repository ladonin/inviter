<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Online сервис, существенно упрощающий процесс привлечения новых подписчиков через прямые приглашения.';
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

















<div class="panel-2" style="padding: 20px;
     background-color: #fff; font-family: 'Segoe UI',Helvetica,Arial,sans-serif;">







    <div class="clearfix"></div>

    <table width="100%">
        <tr>
            <td align="center" >

                <h3 id="title"><?php echo($title); ?> </h3>
                <br>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">

                <img src='/img/scheme.png' width="100%" style="max-width:800px;">

            </td>
        </tr>


        <tr>
            <td align="center" valign="top">

            </td>
        </tr>

        <tr>
            <td align="left" valign="top">



                <h3>Для кого этот проект?</h3>

                <ul>
                    <li style='margin-left:-20px'>Данный проект идеально подходит администраторам и владельцам групп в сети Одноклассники и Facebook.</li>

                </ul>






                <h3>Кого я могу пригласить?</h3>

                <h4>Facebook</h4>
                <ul>
                    <li style='margin-left:-20px'>Ваши друзья или люди, связанные с вами через ваших друзей.</li>
                </ul>

                <h4>Одноклассники</h4>
                <ul>
                    <li style='margin-left:-20px'>Тех, кто ставит "Классы" к постам в какой-нибудь другой уже раскрученной группе (желательно соответствующей вашей по тематике).</li>
                    <li style='margin-left:-20px'>Тех, кто принимает участие в опросах.</li>
                    <li style='margin-left:-20px'>Просто подписчиков другой группы.</li>
                    <li style='margin-left:-20px'>Пользователей из результата поиска (например, вам нужны подписчики из города Саратов в возрасте от 18 лет, только девушки и т.д.).</li>
                </ul>






                <h3>Как все это работает?</h3>
                <ul>
                    <li style='margin-left:-20px'>Процесс ручной, не автоматизированный, так что вы не нарушите <b>Регламент</b> вышеупомянутых соцсетей.</li>
                    <li style='margin-left:-20px'>Вы сохраняете в своей базе пользователей из самой доходящей на ваш взгляд категории (см. п. "Кого я могу пригласить?")</li>
                    <li style='margin-left:-20px'>Затем приглашаете их к себе, но делаете это на порядок быстрее, если бы делали это самостоятельно.</li>

                </ul>




                <h3>Преимущества проекта:</h3>
                <ul>
                    <li style='margin-left:-20px'>Вы никогда не вышлите приглашение пользователю два и более раз, тем самым обезопасите себя от лишних жалоб и вероятных санкций от администрации соцсети.</li>
                    <li style='margin-left:-20px'>Вы храните всех пользователей каждой соцсети в одной базе.</li>
                    <li style='margin-left:-20px'>Программа ведет подробный отчет о собранных пользователях и при необходимости предоставляет его в виде CSV файла.</li>
                </ul>



                <h3>Сколько подписчиков можно получить в день?</h3>
                <ul>
                    <li style='margin-left:-20px'>Столько, сколько разрешают правила соцсети, в которой ваша группа находится.</li>
                    <li style='margin-left:-20px'>Если у вас несколько аккаунтов или просто есть друзья, готовые вам помочь, то в разы больше.</li>
                </ul>


                <h3>Как я могу пригласить пользователей из свой базы?</h3>
                <div class="dropup">
                    <div class="btn-group" style="margin-bottom:10px;">
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Инструкции по пользованию проектом <span class="caret"></span></button>

                        <ul class="dropdown-menu">
                            <li class="dropdown-header"><h4>Как собрать, а затем пригласить в свою группу в Одноклассниках:</h4></li>
                            <li><a href="/odnoklassniki/instruction1.html" target="_blank">участников группы, релевантной вашей</a></li>
                            <li><a href="/odnoklassniki/instruction2.html" target="_blank">пользователей, поставивших "Kласс!"</a></li>
                            <li><a href="/odnoklassniki/instruction3.html" target="_blank">пользователей из результата поиска</a></li>
                            <li class="dropdown-header"><h4>Как собрать, а затем пригласить в свою группу в Facebook:</h4></li>
                            <li><a href="/facebook/instruction1.html" target="_blank">своих друзей, а также друзей ваших друзей</a></li>
                        </ul>
                    </div>
                </div>




                <h3>Сколько времени тратится на все?</h3>
                <ul>
                    <li style='margin-left:-20px'>На сбор 1000 пользователей - до 5 минут, учитывая, что вы уже нашли источник.</li>
                    <li style='margin-left:-20px'>На приглашение в группу пользователей в количестве 40 человек - требуется в среднем 5 минут. Однако рекомендуется приглашать пользователей в течение дня, а не всех сразу.</li>
                </ul>
                <h3>На каком браузере лучше всего работать? </h3>
                <ul>
                    <li style='margin-left:-20px'>На любом, но приглашать лучше через Chrome (т.к. он быстрее всех открывает окошки с профилем)</li>
                </ul>


                <h3>Стоимость:</h3>
                <ul>
                    <li style='margin-left:-20px'>Первый месяц - бесплатно, далее - 100 рублей в месяц.</li>
                </ul>




            </td>
        </tr>


    </table>















</table>




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




    var screenHeight = $(window).height();
    $('.panel-1').height(screenHeight);


    $('.nav-down div, #title').click(function () {
        $('body,html').animate({scrollTop: screenHeight}, '500');
    });

    $('.nav-up div').click(function () {
        $('body,html').animate({scrollTop: 0}, '500');
    });




</script>
<?php include('generic/footer.php'); ?>