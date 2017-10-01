<?php

//\home\q\quitecorg\public_html\instorage\public_html\map\public_html\application\services\landmarks\blocks\desctop\_pages\generic\bottom_side.php
//http://mutagen.ru/?key=%D0%BD%D0%B0%D0%BA%D1%80%D1%83%D1%82%D0%BA%D0%B0+%D0%BF%D0%BE%D0%B4%D0%BF%D0%B8%D1%81%D1%87%D0%B8%D0%BA%D0%BE%D0%B2+%D0%B2+%D0%B3%D1%80%D1%83%D0%BF%D0%BF%D1%83+%D0%B2%D0%BA%D0%BE%D0%BD%D1%82%D0%B0%D0%BA%D1%82%D0%B5&form_id=form_key
//https://wordstat.yandex.ru/#!/?geo=&text_geo=&words=%D0%BD%D0%B0%D0%BA%D1%80%D1%83%D1%82%D0%BA%D0%B0%20%D0%BF%D0%BE%D0%B4%D0%BF%D0%B8%D1%81%D1%87%D0%B8%D0%BA%D0%BE%D0%B2%20%D0%B2%20%D0%B3%D1%80%D1%83%D0%BF%D0%BF%D1%83

//https://www.textsale.ru/raskrutka-i-prodvijenie-saytov/vkontakte-besplatnye-sposoby-raskrutki-prodvijeniya-gruppy-v-quotvkontaktequot-tov3980686.html
//https://www.etxt.ru/articles/?search_words=%EF%EE%E4%EF%E8%F1%F7%E8%EA+%E2%EA&price_from=&price_to=&size_from=&size_to=&uniq_from=&uniq_to=&rate_from=&sort=date&order=desc&id_language=15&id_subtype=0&tmoder=0&search=1

//https://webmaster.yandex.ru/site/http:inviter.biz:80/indexing/reindex/
//https://www.google.com/webmasters/tools/googlebot-fetch?hl=ru&authuser=1&siteUrl=http://inviter.biz/





/*
 * Добавление новой статьи
 *
 * 1. ищем подходящий заголовок
 * 2. ищем на textsales статью по этому заголовку
 * 3. доводим её до ума
 * 4. добавляем в базу данных статью
 * 5. пишем keywords, description, url
 * 6. добавляем ссылки в статьи на неё и в неё ссылки на прошлые статьи
 * 7. добавляем её в sitemap
 * 8. добавляем её ссылку в worlds-landmarks
 * 9. индексируем её в яндексе (важные страницы и переобход) и гугле (посмотреть как гугл-бот)
 */





error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);

require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic' . MY_DS . 'actions.php');

require_once('generic/generic_functions.php');
if (!empty($_GET['account'])) {
include('generic/auth_control.php');
}

$url_name = $_GET['url_name'] ?? '';


if ($url_name) {
    $article = get_article($_GET['url_name']);
    $keywords = $article['keywords'];
    $title = $article['title'];
    $description = $article['description'];
}






include('generic/header.php');

if (!empty($_GET['account'])) {
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
<?php } else { ?>


<div class="p-20-sm p-10" style="text-align:center; margin-bottom:0; background: linear-gradient(135deg, rgba(42,81,122,1) 0%, rgba(67,129,191,1) 100%);">
    <div class="row">
        <div class="pull-left">
            <a href="/" class="mr-5"><img src="/img/vk_logo.jpg" width="35" style="opacity:1; border-radius: 1000px;border: 2px solid #fff;"></a><a href="/" class="mr-5"><img src="/img/ok_logo.jpg" width="35" style="opacity:1; border-radius: 1000px;border: 2px solid #fff;"></a><a class="mr-5" href="/"><img src="/img/fb_logo.jpg" width="35" style="opacity:1; border-radius: 1000px;border: 2px solid #fff;"></a>

        </div>
        <div class="pull-right">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="outline: none; border: 0;padding: 0;font-size: 30px; line-height: 30px; background-color: rgba(0, 120, 201, 0);color: #fff; box-shadow: none;">
                    <span style="outline:none" aria-hidden="true" class="glyphicon glyphicon-menu-hamburger"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                    <li><a class="pv-10" style="cursor:pointer" href="/enter" ><span class="glyphicon glyphicon-log-in" style="margin-right:10px;"></span>Войти</a></li>
<li><a href="/registration" class="pv-10" style="cursor:pointer"><span class="glyphicon glyphicon-user" style="margin-right:10px;"></span>Регистрация</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a class="pv-10" href="/help"><span class="glyphicon glyphicon-question-sign" style="margin-right:10px;"></span>Помощь</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

    <?php
}
?>

<div class="row">
    <div class="col-xs-12 col-sm-6 p-0">
        <div class="row" style="background: linear-gradient(90deg, #24adc2 0%, #fff 100%); color:#fff; border-top:3px solid #f5f5f5">
            <div class="pull-left mh-20-sm mh-10 mv-5">
                <div class="pull-left mr-10 pv-5" style="color:#fff;"><span class="glyphicon glyphicon-pencil" style="margin-right:5px; font-size:20px"></span>
                </div>
                <div class="pull-left"><h5><b>СТАТЬИ</b></h5></div><div class="clearfix"></div>
            </div>
            <div class="  mh-20-sm mh-10 mt-10 pull-right">
            </div>
        </div>
    </div>
</div>



<?php


if (!empty($article)) {


    ?>
    <div class="m-20">
        <h2><?= $article['title']; ?></h2>

    <?= $article['content']; ?></div>


    <?php } ?>












<div  class="well well-lg p-10-xs" style="padding-top:10px !important;padding-bottom:10px !important;  margin-bottom:0px !important; margin-top:0px !important;">



    <table width="100%">
        <tbody>

<?php
if (empty($_GET['account'])) {?>
            <tr><td align="center">
                    <div class="mv-10">
                        <a class="button-login btn btn-success" href="/registration"><b>Начать работу</b></a>
                </div>

</td></tr><?php } ?>




        <?php if(isSearchBot()) { ?>
            <tr>
                <td valign="top" align="left">

                    <div class="mt-10">

<?php if ($url_name) { ?>


                        <?php
                        }




                        $articles = get_articles($url_name);
                        $i = 0;
                        foreach ($articles as $article) {
                            $i++;
                            ?><div class="mb-10">
                            <a style="font-size:17px" href="/articles/<?= $article['url_name']; ?><?=!empty($user_id) ? '?account=1' : ''?>"><b><?= $article['title']; ?></b></a>
                        </div><?php } ?>





                    </div>







                </td>
            </tr>
        <?php } ?>
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