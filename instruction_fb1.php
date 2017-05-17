<html>
    <head>
        <title>Как загрузить, а затем пригласить в свою группу в Facebook своих друзей, а также друзей ваших друзей</title>
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

    
    <?php require('generic/fb_instruction_menu.php');?>
    
    
    
    </div>
    
    
    
    
    
    <div style="margin:10px 20px;">
<h1>Как собрать, а затем пригласить в свою группу в Facebook своих друзей, а также друзей ваших друзей</h1>


<h3>Шаг 1. Ищем источник.</h3>

<p>Как вы знаете в группу Facebook мы можем приглашать только тех пользователей, которые имеют с вами некую связь, то есть являются вашим другом или другом вашего друга.
<p>Процедура сбора своих друзей или друзей своего друга абсолютно одинакова.</p>
<p>Для примера возьмем страницу своего друга.</p>
<p>Выбираем друга, у которого больше всех друpей.</p>
<p><a href='/img/facebook-1.jpg' target='_blank'><img src='/img/facebook-1.jpg'></a></p>
<p>Выбираем у него вкладку "Друзья".</p>



<h3>Шаг 2 (самое сложное). Получаем непонятный, но ценный код.</h3>
<p>Прокручиваем страницу вниз, чтобы подгрузить как можно больше пользователей. Прогружать всех необязательно.</p>
<p>Нажимаем правой кнопкой мыши на белый фон справа от списка друзей в месте, указанном цифрой 1, чтобы вызвать всплывающее меню (нужная нам опция меню помечена зеленой стрелкой).</p>
<p><a href='/img/facebook-2.jpg' target='_blank'><img src='/img/facebook-2.jpg'></a></p>
<div class="bs-callout bs-callout-info">
<h4>Совет по выбору нужной опции</h4>
<p>Если у вас браузер Google Chrome, то выбираем "Просмотреть код"</p>
<p>Если Mozilla Firefox, то "Исследовать элемент" (на скриншоте)</p>
<p>Если Opera, то "Просмотреть код элемента"</p>
</div>

<p>В появившемся окне с кодом вы увидите следующий текст: <b>&lt;div id="collection_wrapper...</b>. Он будет выделен.</p>
<p><a href='/img/facebook-3.jpg' target='_blank'><img src='/img/facebook-3.jpg'></a></p>














<p>Кликаете на него правой кнопкой мыши. Появится всплывающее меню браузера.</p>
<div class="bs-callout bs-callout-info">
<h4>Совет по выбору нужной опции</h4>
<p>Если у вас браузер Google Chrome, то выбираем "Edit as HTML"</p>
<p>Если Mozilla Firefox, то "Править как HTML" (на скриншоте)</p>
<p>Если Opera, то "Edit as HTML"</p>
</div>
<p>Тот непонятный код, что в итоге раскроется, нам и нужен. В нем как раз и хранятся те самые пользователи.</p>
<p>Выделяем его полностью. Проще это сделать через сочетание клавиш Ctrl + A (если компьютер слабый, то выделит не сразу, придется подождать несколько секунд), затем копируем в память компьютера (это лучше сделать через сочетание клавиш Ctrl + C, поскольку команда "копировать" тут отличается от классической в Windows).</p>
<p><a href='/img/facebook-4.jpg' target='_blank'><img src='/img/facebook-4.jpg'></a></p>



<h3>Шаг 3 (самое страшное позади). Импортируем пользователей в свою базу.</h3>
<p>Заходим на сайт.</p>
<p>Вставляем код в поле "HTML код".</p>

<p><a href='/img/facebook-5.jpg' target='_blank'><img src='/img/facebook-5.jpg'></a></p>
<p>Нажимаем "Импорт"</p>


<p><a href='/img/facebook-6.jpg' target='_blank'><img src='/img/facebook-6.jpg' style='width:50%; max-width:786px;'></a></p>
<p>В итоге мы импортировали 965 человек. </p>

<div class="bs-callout bs-callout-warning">
<h4>Обратите внимание</h4>
<p>При следующих добавлениях некоторые пользователи могут уже быть в базе и во избежание дублирования, они не будут добавлены в базу (см. ниже).</p>
</div>
<p><a href='/img/klass_8.png' target='_blank'><img src='/img/klass_8.png'></a></p>




<?php include('instruction_general_fb.html');?>









    </div>
    <div style="margin:10px 20px;" class="dropup">

    
    <?php require('generic/fb_instruction_menu.php');?>
    
    
    
    </div><br>
</body>
</html>