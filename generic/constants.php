<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);


ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '64M');

date_default_timezone_set('UTC');


define('MY_DOCROOT', realpath(dirname(__FILE__)));


define('MY_CRYPT_HASH_ALGORYTM_CODE', '$6$');


define('MY_LANGUAGE_RU', 'ru');
define('MY_LANGUAGE_EN', 'en');





if (isset($_SERVER['SERVER_NAME'])) {
    define('MY_PROTOCOL', 'http://');
    define('MY_DOMEN_NAME', $_SERVER['SERVER_NAME']);
    define('MY_DOMEN', MY_PROTOCOL . MY_DOMEN_NAME);
}

define('MAX_USERS_COUNT_PER_IMPORT', '5000');
define('BALANCE_DEFAULT_NEW_USER', 1000);

define('NET_HEADER_BACKGROUND_COLOR_OK', 'f07d00');
define('NET_HEADER_BACKGROUND_COLOR_FB', '3b5999');
define('NET_HEADER_BACKGROUND_COLOR_VK', '4d76a4');


define('NET_TITLE_OK', 'одноклассники');
define('NET_TITLE_FB', 'facebook');
define('NET_TITLE_VK', 'вконтакте');



define('NET_CODE_OK', 'ok');
define('NET_CODE_FB', 'fb');
define('NET_CODE_VK', 'vk');
// ###MORE NETS

define('PROFILE_URL_OK', 'https://ok.ru/profile/');
define('PROFILE_URL_FB', 'https://www.facebook.com/profile.php?id=');
define('PROFILE_URL_VK', 'https://vk.com/id');
// ###MORE NETS



define('GROUP_URL_OK', 'https://ok.ru/group/');
define('GROUP_URL_FB', 'FIXME');
define('GROUP_URL_VK', 'FIXME');








// стоимость 1 дня пользования
define('MY_REPORT_USERS_COLLECTION_LIMIT', 5000);

//разница от осноной стоимости тип пользователя относительно типа соцсети
define('MY_IMPORT_USER_CORRECTION_OK', 0);
define('MY_IMPORT_USER_CORRECTION_VK', 0.1);
define('MY_IMPORT_USER_CORRECTION_FB', 0.05);

// стоимость 1 импортированного пользователя
define('MY_USER_IMPORT_COST', array(
    'GENERIC'       => 0.25,
    'COMMENT'       => 0.30,
    'SURVEY'        => 0.25,
    'SUBSCRIBER'    => 0.15,
    'KLASS'         => 0.20,
    'REPOST'        => 0.30,
    'SEARCH'        => 0.15));

// коэффициент суммы 1 дополнительного типа к самому дорогому типу в составной цене
define('MY_USER_IMPORT_COST_ADDITIONAL_KOEF', 0.25);

// стоимость 1 дня пользования
define('MY_USER_DAILY_FEE_COST', 3.3);


// лимит пользователей в файле отчета
define('MY_REPORT_USERS_COUNTT_LIMIT', 10000);
