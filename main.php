<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Получить подписчиков в группу';
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic' . MY_DS . 'actions.php');
$invite_table = 'ok_imports';

require_once('generic/functions.php');


///////////include('generic/auth_control.php');
include('generic/header.php');
?>





        <div class="row">
<div class="col-xs-12 text-center" style="padding-bottom:10px"><h3>Выберите соцсеть</h3></div>
            <div class="col-xs-6 text-right"><a href="/odnoklassniki<?php //echo($client_code ? '/' . $client_code : '');?>"><img src="/img/odnoklassniki_logo.jpg" style="border-radius:5px;" width="200"></a></div>
            <div class="col-xs-6 text-left"><a href="/facebook<?php //echo($client_code ? '/' . $client_code : '');?>"><img src="/img/facebook_logo.jpg" style="border-radius:5px;" width="200"></a></div>
        </div>


<table style="display:none;">
        <tr>
            <td align="center" valign="top" style="padding:10px;">

<!--LiveInternet counter--><script type="text/javascript">
document.write("<a href='//www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t25.2;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показано число посетителей за"+
" сегодня' "+
"border='0' width='88' height='15'><\/a>")
</script><!--/LiveInternet-->


            </td>
        </tr>
</table>

<?php include('generic/footer.php'); ?>