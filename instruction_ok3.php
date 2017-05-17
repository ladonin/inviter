<html>
    <head>
        <title>Получение, затем приглашение в свою группу пользователей из результата поиска</title>
        <link rel="stylesheet" href="/css/generic/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="/css/desctop/generic.css"/>

        <script src="/javascript/generic/jQuery/jquery.min.js"></script>
        <script src="/javascript/generic/bootstrap.min.js"></script>
    </head>
    
    
    
    
    <style>
        img{
        border:1px solid #aaa;
      
        }
    </style>
    
    
    
    <body>
    
    
    <div style="margin:10px 20px;">

    
    <?php require('generic/ok_instruction_menu.php');?>
    
    
    
    </div>
    <div style="margin:10px 20px;">
<h1>Как собрать, а затем пригласить в свою группу в Одноклассниках пользователей из результата поиска</h1>

<h3>Шаг 1. Ищем подходящий источник.</h3>

<p>Заходим на страницу поиска и вводим нужные параметры поиска.</p>
<p><a href='/img/search_1.png' target='_blank'><img src='/img/search_1.png'></a></p>
<p>Прокручиваем полученный результат вниз, чтобы появилась кнопка "показать еще". Этой кнопкой можете подгрузить еще некоторое количество пользователей.</p>




<h3>Шаг 2 (самое сложное). Получаем непонятный, но ценный код.</h3>
<p>Нажимаем правой кнопкой мыши на белый фон под кнопкой "показать ещё" в месте, указанном цифрой 1, чтобы вызвать всплывающее меню (нужная нам опция меню помечена зеленой стрелкой).</p>
<p><a href='/img/search_2.png' target='_blank'><img src='/img/search_2.png'></a></p>

<div class="bs-callout bs-callout-info">
<h4>Совет по выбору нужной опции</h4>
<p>Если у вас браузер Google Chrome, то выбираем "Просмотреть код"</p>
<p>Если Mozilla Firefox, то "Исследовать элемент" (на скриншоте)</p>
<p>Если Opera, то "Просмотреть код элемента"</p>
</div>
<p>В появившемся окне с кодом вы увидите следующий текст: <b>&lt;div id="hook_Loader_PortalSearchResultsLoader"...</b>. Он будет выделен.</p>
<p><a href='/img/search_3.png' target='_blank'><img src='/img/search_3.png'></a></p>
<p>Кликаете на него правой кнопкой мыши. Появится всплывающее меню браузера.</p>
<div class="bs-callout bs-callout-info">
<h4>Совет по выбору нужной опции</h4>
<p>Если у вас браузер Google Chrome, то выбираем "Edit as HTML"</p>
<p>Если Mozilla Firefox, то "Править как HTML" (на скриншоте)</p>
<p>Если Opera, то "Edit as HTML"</p>
</div>
<p>Тот непонятный код, что в итоге раскроется, нам и нужен. В нем как раз и хранятся те самые пользователи.</p>
<p>Выделяем его полностью. Проще это сделать через сочетание клавиш Ctrl + A (если компьютер слабый, то выделит не сразу, придется подождать несколько секунд), затем копируем в память компьютера (это лучше сделать через сочетание клавиш Ctrl + C, поскольку команда "копировать" тут отличается от классической в Windows).</p>
<p><a href='/img/search_4.png' target='_blank'><img src='/img/search_4.png'></a></p>


<h3>Шаг 3 (самое страшное позади). Импортируем пользователей в свою базу.</h3>
<p>Заходим на сайт.</p>
<p>Вставляем код в поле "HTML код".</p>
<p><a href='/img/search_5.png' target='_blank'><img src='/img/search_5.png'></a></p>
<p>Выбираем ниже в поле "Откуда взят HTML код:" опцию "Пользователи из результата поиска"</p>
<p>Нажимаем "Импорт"</p>
<?php include('instruction_general_ok1.html');?>
    </div>
    <div style="margin:10px 20px;" class="dropup">

    
    <?php require('generic/ok_instruction_menu.php');?>
    
    
    
    </div><br>
</body>
</html>