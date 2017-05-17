<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);


ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '64M');



date_default_timezone_set('UTC');
define('MY_SERVICES_NAME', 'services');

define('MY_DOCROOT', realpath(dirname(__FILE__)));
define('MY_PROTOCOL', 'http://');
if (isset($_SERVER['SERVER_NAME'])) {
    define('MY_DOMEN_NAME', $_SERVER['SERVER_NAME']);
    define('MY_DOMEN', MY_PROTOCOL . MY_DOMEN_NAME);
    define('MY_FILES_URL', MY_DOMEN . '/files/');
    define('MY_FILES_MAP_URL', MY_DOMEN . '/files/map/');
}

define('MY_APPLICATION_DIR', MY_DOCROOT . 'application' . MY_DS);
define('MY_VIEWS_DIR', MY_APPLICATION_DIR . 'views' . MY_DS);
define('MY_JS_DIR', MY_DOCROOT . 'javascript' . MY_DS);
define('MY_LOG_MYSQL_PATH', MY_DOCROOT . 'log' . MY_DS . 'mysql.log');
define('MY_LOG_APPLICATION_PATH', MY_DOCROOT . 'log' . MY_DS . 'application.log');
define('MY_LOG_APPLICATION_TYPE', 'app');
define('MY_LOG_MYSQL_TYPE', 'mysql');
define('MY_SERVICE_VAR_NAME', 'type');
define('MY_CRYPT_HASH_ALGORYTM_CODE', '$6$');
define('MY_FILES_DIR_NAME', 'files');
define('MY_FILES_DIR', MY_DOCROOT . MY_FILES_DIR_NAME . MY_DS);
define('MY_TEMP_FILES_DIR', MY_FILES_DIR . 'temp' . MY_DS);
define('MY_FUNCTIONS_DIR', MY_APPLICATION_DIR . 'functions' . MY_DS);
define('MY_LOG_DIR', MY_APPLICATION_DIR . 'log' . MY_DS);

define('MY_SERVICES_DIR', MY_APPLICATION_DIR . MY_SERVICES_NAME . MY_DS);
define('MY_UNDEFINED_VALUE', 'undefined');
define('MY_NONE_CATEGORY_CODE', 'none');
define('MY_LANGUAGE_RU', 'ru');
define('MY_LANGUAGE_EN', 'en');
define('MY_FORM_TEXT_TAG_CODE_A', 'a');
define('MY_FORM_TEXT_TAG_CODE_B', 'b');
define('MY_FORM_TEXT_TAG_CODE_STRONG', 'strong');
define('MY_FORM_TEXT_TAG_CODE_IMAGE_ADVANCED', 'image_advanced');
define('MY_FORM_TEXT_TAG_CODE_P', 'p');
define('MY_SERVICE_IMGS_URL_CATEGORIES', '/imgs/categories/');


define('MY_MODULE_NAME_SERVICE', 'service');
define('MY_FTP_DEFAULT_SERVER_NAME', 'default_server');








// стоимость 1 импортированного пользователя
define('MY_USER_IMPORT_COST', 0.25);

// стоимость 1 дня пользования
define('MY_USER_DAILY_FEE_COST', 3.3);



