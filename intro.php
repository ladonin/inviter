rtyrty<?php

function my_pre($data = null, $exit = true)
{
    if (is_string($data) && strlen($data) > 0)
        $data = 'string(' . strlen($data) . ') "' . $data . '"';

    if (is_bool($data)) {
        if ($data === true)
            $data = 'boolean (true)';
        else
            $data = 'boolean (false)';
    }

    if (is_null($data))
        $data = 'null';

    if (is_string($data) && strlen($data) === 0)
        $data = 'string(o) ""';

    if (PHP_SAPI === 'cli') {
        if ($return)
            return print_r($data, true);
        else
            return print_r($data) . PHP_EOL;
    }


        echo '<pre style="white-space: pre-wrap; border: 1px solid #c1c1c1; border-radius: 10px; margin: 10px; padding: 10px; background-color: #fff; font-size: 11px; font-family: Tahoma; line-height: 15px;">' . htmlspecialchars(print_r($data, true)) . '</pre>';
    if ($exit) exit();
}



error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Как за 5 минут пригласить в свою группу до 40 активных и релевантных пользователей сети "одноклассники" с одного аккаунта?';
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
$invite_table = 'odnoklassniki_group_admins_collector';



include('generic/header.php');



            $stmt = $connect->prepare("SELECT * FROM $invite_table WHERE code=:code");
            $stmt->execute(array('code' => $_GET['code']));
            $result = $stmt->fetch();
            if (!$result) {
                exit();
            }
            
            if ($result['admin_name']) {
                $header = 'Приветствую Вас, '.$result['admin_name'].'!';
            } else {
                $header = 'Приветствую Вас!';
            }


?>

<div class="well well-lg" style="background-color: #fffff9; margin: 10px 50px;">
    <h3><?php echo($title);?> </h3><br>
    
    
    
    
     <div class="btn-group" style="margin-bottom:10px; float:left">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Инструкции по пользованию проектом <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="/img/scheme.png" target="_blank">Наглядное описание работы</a></li>
            <li class="dropdown-header"><h4>Как получить, а затем пригласить в свою группу:</h4></li>
            <li><a href="/gatherer/instruction1.html" target="_blank">участников группы, релевантной вашей</a></li>
            <li><a href="/gatherer/instruction2.html" target="_blank">пользователей, поставивших "Kласс!"</a></li>
            <li><a href="/gatherer/instruction3.html" target="_blank">пользователей из результата поиска</a></li>
        </ul>
    </div> 
       <div style="margin-bottom:10px; float:right">
                <a class="btn btn-success dropdown-toggle" href="http://inviter.mapstore.org/gatherer/<?php echo($result['code']);?>"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>&nbsp;&nbsp;Ссылка на вашу страницу</a>
                
            </div> 
<div class="clearfix"></div>

    <table width="100%">
        <tr>
            <td align="center" valign="top">
                <h3><?php echo($header);?></h3>
               </td>
</tr>               
                
        <tr>
            <td align="left" valign="top">           

Меня зовут Александр, я web-разработчик.
<br><br>
Вам, как владельцу своей группы в одноклассниках, предлагаю бесплатно опробовать мой новый стартап по привлечению новых подписчиков в свою группу.
Сам процесс ручной, не автоматизированный, но очень удобный и эффективный, так что вы не нарушите <b>Регламент сайта ok.ru</b>.
<br><br>
Изначально я делал этот проект только для своей группы <a href="https://ok.ru/world.landmarks">https://ok.ru/world.landmarks</a>, но потом решил сделать его общим для всех.
Для первых 50 "первопроходцев" (в частности и для вас) проект останется бесплатным.
Вы просто "собираете" подходящих пользователей, а затем приглашаете их к себе, но делаете это на порядок быстрее, если бы делали это самостоятельно.

<h3>Преимущества проекта:</h3>
<ul>
<li style='margin-left:-20px'>Вы никогда не вышлите приглашение пользователю более одного раза, тем самым обезопасите себя от лишних жалоб и вероятных санкций от администрации сайта ok.ru.</li>
<li style='margin-left:-20px'>Вы храните всех пользователей в одной базе.</li>
<li style='margin-left:-20px'>Программа ведет подробный отчет о собранных пользователях и при необходимости предоставляет его в виде Excel файла.</li>
</ul>
<h3>Сколько подписчиков можно получить в день?</h3>
<ul>
<li style='margin-left:-20px'>Если у вас один аккаунт - то до 40 человек (можно и больше, но правила ok.ru не рекомендуют злоупотреблять этим).</li>
<li style='margin-left:-20px'>Если у вас несколько аккаунтов или просто есть друзья, готовые вам помочь, то в разы больше.</li>
</ul>
<h3>Как все это работает?</h3>
<ul>
<li style='margin-left:-20px'>Вы просто "собираете" (об этом ниже) самых активных и релевантных для вашей группы пользователей, а затем через удобный интерфейс приглашаете их в свою группу.</li>
</ul>
<h3>Каких пользователей я могу собрать?</h3>
<ul>
<li style='margin-left:-20px'>Тех, кто ставит "Классы" к постам в какой-нибудь другой уже раскрученной группе (желательно соответствующей вашей по тематике).</li>
<li style='margin-left:-20px'>Просто подписчиков другой группы.</li>
<li style='margin-left:-20px'>Пользователей из результата поиска (например, вам нужны подписчики из города Саратов в возрасте от 18 лет, только девушки и т.д.).</li>
</ul>
<h3>Как я могу пригласить пользователей из свой базы?</h3>
<ul>
<li style='margin-left:-20px'>Просто нажимаю на кнопку "показать пользователей" и мне открываются компактные окна браузера со страницами профилей, где в каждой я просто нажимаю "пригласить в группу", а затем сочетанием клавиш Ctrl + W также быстро закрываю.</li>
</ul>
<h3>Сколько времени тратится на все?</h3>
<ul>
<li style='margin-left:-20px'>На сбор 1000 пользователей - до 5 минут, учитывая, что вы уже нашли источник.</li>
<li style='margin-left:-20px'>На приглашение в группу пользователей в количестве 40 человек (больше 40 приглашений с одного аккаунта в сутки адмнистрация ok.ru не рекомендует) - требуется в среднем 5 минут. Однако рекомендуется приглашать их в течение дня, а не всех сразу.</li>
</ul>
<h3>На каком браузере лучше всего работать? </h3>
<ul>
<li style='margin-left:-20px'>На любом, но приглашать лучше через Chrome (т.к. он быстрее всех открывает окошки с профилем)</li>
</ul>

</td>
</tr>

        <tr>
            <td align="center" valign="top">
      



 

       
                
                
                
     <div class="btn-group" style="margin-top:10px; float:left">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Инструкции по пользованию проектом <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="/img/scheme.png" target="_blank">Наглядное описание работы</a></li>
            <li class="dropdown-header"><h4>Как получить, а затем пригласить в свою группу:</h4></li>
            <li><a href="/gatherer/instruction1.html" target="_blank">участников группы, релевантной вашей</a></li>
            <li><a href="/gatherer/instruction2.html" target="_blank">пользователей, поставивших "Kласс!"</a></li>
            <li><a href="/gatherer/instruction3.html" target="_blank">пользователей из результата поиска</a></li>
        </ul>
    </div> 
       <div style="margin-top:10px; float:right">
                <a class="btn btn-success dropdown-toggle" href="http://inviter.mapstore.org/gatherer/<?php echo($result['code']);?>"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>&nbsp;&nbsp;Ссылка на вашу страницу</a>
                
            </div>     
                <div class="clearfix"></div>
                
            </td>
        </tr>
        
        
        
        
        
        <tr>
            <td align="center" valign="top" style="padding-top:10px;">   
        
        
<!--LiveInternet counter--><script type="text/javascript">
document.write("<a href='//www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t26.1;r"+
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
</div>
<?php include('generic/footer.php'); ?>