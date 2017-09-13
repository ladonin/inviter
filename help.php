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
if (empty($_GET['noaccount'])) {
include('generic/auth_control.php');
}

include('generic/header.php');

if (empty($_GET['noaccount'])) {
?>



<div class="p-20-sm p-10" style="text-align:center; margin-bottom:0; background: linear-gradient(135deg, rgba(42,81,122,1) 0%, rgba(67,129,191,1) 100%);">
    <div class="row">
        <div class="pull-left">
            <a href="/vkontakte" class="mr-5"><img src="/img/vk_logo.jpg" width="35" style="opacity:1; border-radius: 1000px;border: 2px solid #fff;"></a><a href="/odnoklassniki" class="mr-5"><img src="/img/ok_logo.jpg" width="35" style="opacity:1; border-radius: 1000px;border: 2px solid #fff;"></a><a class="mr-5" href="/facebook"><img src="/img/fb_logo.jpg" width="35" style="opacity:1; border-radius: 1000px;border: 2px solid #fff;"></a>

        </div>
        <div class="pull-right">
            <?php include('generic/personal_data.php'); ?>
        </div>
    </div>
</div>
<?php } ?>
<div class="row">
    <div class="col-xs-12 col-sm-6 p-0">
<div class="row" style="background: linear-gradient(90deg, #24adc2 0%, #fff 100%); color:#fff; border-top:3px solid #f5f5f5">
    <div class="pull-left mh-20-sm mh-10 mv-5">
        <div class="pull-left mr-10 pv-5" style="color:#fff;"><span class="glyphicon glyphicon-question-sign" style="margin-right:5px; font-size:20px"></span>
        </div>
        <div class="pull-left"><h5><b>ПОМОЩЬ</b></h5></div><div class="clearfix"></div>
    </div>
    <div class="  mh-20-sm mh-10 mt-10 pull-right">
    </div>
</div>
</div>
</div>



<div  class="well well-lg p-10-xs" style="padding-top:10px !important;padding-bottom:20px !important;  margin-bottom:0px !important; margin-top:0px !important;">



<table width="100%">
        <tbody>


        <tr>
            <td valign="top" align="left">



                <h2 class="mb-20">Пополнение</h2>
                <div class="ml-20">
                <h3>1. <a href="https://youtu.be/lOqnHtNgT0A" target="blank">Импорт из готовой коллекции</a></h3>

                <h3 class="mb-20" onclick="$('#load_users').toggleClass('hidden2');">2. <a class="pointer">Загружаем пользователей из соцсети</a></h3>

                <ul id="load_users" class="hidden2">
                    <li style="margin-left:-20px"><h4>Опросы, голосования</h4>
                    <ul>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/DeyPGxDsgM8" target="blank">Вконтакте</a></h5></li>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/oFfHvm6IU9U" target="blank">Одноклассники</a></h5></li>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/r7Y_Z5pST7g" target="blank">Facebook</a></h5></li>
                    </ul>
                    </li>

                    <li style="margin-left:-20px"><h4>Подписчики</h4>
                    <ul>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/pLnRk8EAkOI" target="blank">Вконтакте</a></h5></li>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/lKz6po83ZEU" target="blank">Одноклассники</a></h5></li>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/Sw8b6f8z9qw" target="blank">Facebook</a></h5></li>
                    </ul>
                    </li>

                    <li style="margin-left:-20px"><h4>Результаты поиска</h4>
                    <ul>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/c0KI9Gs61PQ" target="blank">Вконтакте</a></h5></li>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/busREN1RVEM" target="blank">Одноклассники</a></h5></li>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/DbyDXW_e1xI" target="blank">Facebook</a></h5></li>
                    </ul>
                    </li>

                    <li style="margin-left:-20px"><h4>Репосты</h4>
                    <ul>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/DNC6NeXVbr4" target="blank">Вконтакте</a></h5></li>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/wWycnkPeDwM" target="blank">Одноклассники</a></h5></li>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/aKCRf8bhag8" target="blank">Facebook</a></h5></li>
                    </ul>
                    </li>

                    <li style="margin-left:-20px"><h4>Like, "Мне нравится", "Класс!"</h4>
                    <ul>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/WfAg026aQhI" target="blank">Вконтакте</a></h5></li>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/GBCL4R0aiDg" target="blank">Одноклассники</a></h5></li>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/SKfuWY8kGUE" target="blank">Facebook</a></h5></li>
                    </ul>
                    </li>

                    <li style="margin-left:-20px"><h4>Комментарии</h4>
                    <ul>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/HyKajEtkUP0" target="blank">Вконтакте</a></h5></li>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/Qfsxxi8Ml68" target="blank">Одноклассники (способ 1)</a></h5></li>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/G7hho_fg5wE" target="blank">Одноклассники (способ 2)</a></h5></li>
                        <li style="margin-left:-20px"><h5><a href="https://youtu.be/XrtudIvyplw" target="blank">Facebook</a></h5></li>
                    </ul>
                    </li>
                </ul>









                <h3 class="mb-20" onclick="$('#promotion').toggleClass('hidden2');">3. <a class="pointer">Вкладка "Продвижение"</a></h3>

                <ul id="promotion" class="hidden2">
                    <li style="margin-left:0px"><h4 onclick="$('#promotion1').toggleClass('hidden2');"><a class="pointer">Общее описание</a></h4>
                        <div class="hidden2" id="promotion1" style="margin-left:0px">
<img src="/img/promotion_describe.png" style="border:1px solid #333">
                        </div>
                    </li>

                    <li style="margin-left:0px"><h4 onclick="$('#promotion2').toggleClass('hidden2');"><a class="pointer">Прикрепленные комментарии при самостоятельной загрузке</a></h4>
                        <div class="hidden2" id="promotion2" style="margin-left:0px">
<img src="/img/ok_search_load_users_comment_example.png" style="border:1px solid #333">
                        </div>
                    </li>
                    </ul>








                </div>







            </td>
        </tr>


    </tbody></table>

</div>



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