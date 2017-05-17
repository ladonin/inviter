<?php 

if (empty($non_instruction)) { ?>


<div class="pull-left"><h4><a href="/odnoklassniki">на основную страницу</a></h4></div>
<?php } ?>
<div class="btn-group pull-right">




        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Инструкция <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="/img/scheme.png" target="_blank">Наглядное описание работы</a></li>
            <li class="dropdown-header"><h4>Собираем в базу:</h4></li>
            <li><a href="/odnoklassniki/instruction1.html">участников группы, релевантной вашей</a></li>
            <li><a href="/odnoklassniki/instruction2.html">пользователей, поставивших "Kласс!"</a></li>
            <li><a href="/odnoklassniki/instruction3.html">пользователей из результата поиска</a></li>
            
            <li class="dropdown-header"><h4>Приглашаем в группу:</h4></li>
            
            <li><a href="/odnoklassniki/instruction4.html">собранных пользователей в свою группу в Одноклассниках</a></li>
            
            <li class="dropdown-header"><h4>Отчетность о проделанной работе:</h4></li>
            
            <li><a href="/odnoklassniki/instruction5.html">скачиваем отчет</a></li>
            
        </ul>
        <style>
        .dropdown-menu a{
            color:#337ab7 !important;
        }
        
        </style>
        
        
        </div>
       <?php if (empty($non_instruction)) { ?>
        <div class="clearfix"></div>
<?php } ?>



