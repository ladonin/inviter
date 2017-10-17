clearInterval(intervalLoad);
clearInterval(intervalLog);
actual_html;


https://ok.ru/poleznye.sovetu/topic/66661048584970

// ok
// опросы, классы
var intervalLoad = setInterval(function() {
$('.js-show-more.link-show-more').trigger('click');
$('.modal_main').scrollTop(999999999);
}, 1000);

var actual_html = '';
var count = 0;
var intervalLog = setInterval(function() {

    actual_html += $('ul.cardsList').html();
    count += $('.cardsList_li').length;
    $('ul.cardsList').html('');

    console.log(actual_html.length + ' Bytes');
    console.log(count);
    if (count > 30000) {
        console.log('Предел достигниут.....................');
        clearInterval(intervalLoad);
        clearInterval(intervalLog);
    }
}, 10000);


// репост
var intervalLoad = setInterval(function() {
$('.js-show-more.link-show-more').trigger('click');
$('#modal_main').scrollTop(999999999);
}, 1000);

var actual_html = '';
var count = 0;
var intervalLog = setInterval(function() {

    actual_html += $('ul.cardsList').html();
    count += $('.cardsList_li').length;
    $('ul.cardsList').html('');

    console.log(actual_html.length + ' Bytes');
    console.log(count);
    if (count > 30000) {
        console.log('Предел достигниут.....................');
        clearInterval(intervalLoad);
        clearInterval(intervalLog);
    }
}, 10000);








// подписчики
var intervalLoad = setInterval(function() {
$('.js-show-more.link-show-more').trigger('click');
$(window).scrollTop(999999999);
}, 2000);

var actual_html = '';
var count = 0;
var intervalLog = setInterval(function() {

    actual_html += $('ul.cardsList').html();
    count += $('.cardsList_li').length;
    $('ul.cardsList').html('');

    console.log(actual_html.length + ' Bytes');
    console.log(count);
    if (count > 5000) {
        console.log('Предел достигниут.....................');
        clearInterval(intervalLoad);
        clearInterval(intervalLog);
    }
}, 10000);












// vk
//подписчики
var intervalLoad = setInterval(function() {
document.getElementById("box_layer_wrap").scrollTop=9999999;
}, 300);

var actual_html = '';
var count = 0;
var intervalLog = setInterval(function() {

    actual_html += document.querySelector('#fans_rowsmembers').innerHTML;

    count += document.querySelectorAll('#fans_rowsmembers .fans_fan_row').length;
    document.querySelector('#fans_rowsmembers').innerHTML='';
    console.clear();
    console.log('vk подписчики');
    console.log(count);

    if (count > 75000) {
        console.log('Предел достигниут.....................');
        clearInterval(intervalLoad);
        clearInterval(intervalLog);
        console.clear();
        console.log(actual_html);
    }
}, 2000);






// vk
//нравится
var intervalLoad = setInterval(function() {
document.getElementById("wk_layer_wrap").scrollTop=9999999;
}, 300);

var actual_html = '';
var count = 0;
var intervalLog = setInterval(function() {

    actual_html += document.querySelector('#wk_likes_rows').innerHTML;

    count += document.querySelectorAll('#wk_likes_rows .fans_fan_row').length;
    document.querySelector('#wk_likes_rows').innerHTML='';
    console.clear();
    console.log('vk подписчики');
    console.log(count);

    if (count > 75000) {
        console.log('Предел достигниут.....................');
        clearInterval(intervalLoad);
        clearInterval(intervalLog);
        console.clear();
        console.log(actual_html);
    }
}, 5000);


// vk
//репост
var intervalLoad = setInterval(function() {
document.getElementById("wk_layer_wrap").scrollTop=9999999;
}, 300);

var actual_html = '';
var count = 0;
var intervalLog = setInterval(function() {

    actual_html += document.querySelector('#wk_likes_content').innerHTML;

    count += document.querySelectorAll('#wk_likes_content ._post_content').length;
    document.querySelector('#wk_likes_content .fans_rows').innerHTML='';
    console.clear();
    console.log('vk подписчики');
    console.log(count);

    if (count > 75000) {
        console.log('Предел достигниут.....................');
        clearInterval(intervalLoad);
        clearInterval(intervalLog);
        console.clear();
        console.log(actual_html);
    }
}, 1000);






// fb
//подписчики
var intervalLoad = setInterval(function() {
document.querySelector('body').scrollTop=99999999;
document.querySelector('.uiMorePagerPrimary').click();
}, 2000);

var actual_html = '';
var count = 0;
var intervalLog = setInterval(function() {

    actual_html += document.querySelector('.fbProfileBrowserListContainer').innerHTML;
    count += document.querySelectorAll('[data-name="GroupProfileGridItem"]').length;


document.querySelectorAll('.fbProfileBrowserListContainer .uiGrid').forEach(function(item, i, arr) {
item.remove();
});
    console.log(actual_html.length + ' Bytes');
    console.log(count);
    if (count > 90000) {
        console.log('Предел достигниут.....................');
        clearInterval(intervalLoad);
        clearInterval(intervalLog);
    }
}, 10000);






<?php
// ok.ru не дает передавать данные на http
// для других соцсетей не пробовал

$path = 'import_loads_files';


$new_html = $_POST['html'];
$url = $_POST['url'];
$net = $_POST['net'];
$category = $_POST['category'];
$user_type = $_POST['user_type'];

$file_name = $net . '_' . $category . '_' . $user_type . '.txt';
$file = $path . '/' . $file_name;

// он уже есть?
$old_html = @file_get_contents($file) ? : '';

// новое содержимое должно быть больше, чем старое
if (strlen($new_html) > strlen($old_html)) {

    $descr = '';
    $descr .= 'соцсеть: ' . $net . '\n';
    $descr .= 'категория: ' . $category . '\n';
    $descr .= 'тип пользователя: ' . $user_type . '\n';
    $descr .= 'url: ' . $url . '\n\n';

    file_put_contents($file, $descr . $new_html);
}
