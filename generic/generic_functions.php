<?php

$types_fields = array(
    'is_comment' => 6,
    'is_survey' => 5,
    'is_subscriber' => 2,
    'is_klass' => 1,
    'is_search' => 3,
    'is_repost' => 4,
);
$types_fields_inv = array(
    6 => 'is_comment',
    5 => 'is_survey',
    2 => 'is_subscriber',
    1 => 'is_klass',
    3 => 'is_search',
    4 => 'is_repost'
);
$nets = array(
    1 => NET_CODE_OK,
    2 => NET_CODE_FB,
    3 => NET_CODE_VK
);


function unescapeUTF8EscapeSeq($str) {
    return preg_replace_callback("/\\\u([0-9a-f]{4})/i",
        create_function('$matches',
            'return html_entity_decode(\'&#x\'.$matches[1].\';\', ENT_QUOTES, \'UTF-8\');'
        ), $str);
}







function my_create_password()
{
    $gl = array(
        'a',
        'e',
        'i',
        'o',
        'u',
    );

    $so = array(
        'b',
        //'c',
        'd',
        'f',
        'g',
        'h',
        //'j',
        'k',
        'l',
        'm',
        'n',
        'p',
        //'q',
        'r',
        's',
        't',
        'v',
        //'w',
        'x',
        //'y',
        'z',
    );

    $result = '';
    $sogl = 0;
    $word_lenght = 11;
    $word_lenght--;
    for ($i = 0; $i < $word_lenght; $i++) {

        if (rand(0, 1) == 1 && $sogl == 0 && $i != $word_lenght && ($i % 2 != 0)) {
            $result .= $so[rand(0, 15)];
            $sogl = 1;
        } else if (($i + $sogl) % 2 == 0) {
            $result .= $so[rand(0, 15)];
        } else {
            $result .= $gl[rand(0, 4)];
        }
    }

    return $result;
}


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
    if ($exit)
        exit();
}


function go_out()
{
    require('exit.php');
    header("location:/");
    exit();
}
/*
 * Хеширует введенное значение
 *
 * @param string $value - хешируемое значение
 *
 * @return string - хешированное значение
 */


function hashing($value)
{
    $salt = generate_salt();
    $hash = crypt($value, $salt);
    return $hash;
}
/*
 * Возвращает соль для хеширования
 *
 * @return string - соль
 */


function generate_salt()
{
    $random = return_random();
    return '$6$' . $random;
}


function is_same($a, $b)
{
    if (!is_string($a) || !is_string($b))
        return false;

    $mb = function_exists('mb_strlen');
    $length = $mb ? mb_strlen($a, '8bit') : strlen($a);
    if ($length !== ($mb ? mb_strlen($b, '8bit') : strlen($b)))
        return false;

    $check = 0;
    for ($i = 0; $i < $length; $i+=1)
        $check|=(ord($a[$i]) ^ ord($b[$i]));

    return $check === 0;
}
/*
 * Проверяет соответствие пароля хешу
 *
 * @param string $value - сравниваемое значение
 * @param string $hash - хеш
 *
 * @return boolean
 */


function hash_equals_to_value($value, $hash)
{
    $test = crypt($value, $hash);
    return is_same($test, $hash);
}
/*
 * Возвращает случайное слово
 *
 * @param integer $lenght - длина слова
 *
 * @return string - случайное слово
 */


function return_random($lenght = 3)
{
    $random = '';
    for ($i = 0; $i < $lenght; $i++) {
        $random.=chr(rand(97, 122));
    }
    return $random;
}


function save_user_hash_data_in_cookies($id, $hash)
{
    setcookie("au", $hash, time() + (3600 * 24 * 31), '/');
    setcookie("du", $id, time() + (3600 * 24 * 31), '/');
    $_COOKIE['du'] = $id;
    $_COOKIE['au'] = $hash;
}


function auth_control()
{
    global $user_id;
    //проверяем валидность данных в куках
    $user_id = !empty($_COOKIE['du']) ? (int) $_COOKIE['du'] : null;
    $user_hash = !empty($_COOKIE['au']) ? $_COOKIE['au'] : null;
    global $connect;
    if (!$user_id || !$user_hash) {
        return 0;
    }

    //ищем данные пользователя по id
    $stmt = $connect->prepare("SELECT * FROM users WHERE id=:user_id");
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        return -1;
    }

    //проверяем соответствие пароля хешу
    if ($result['hash'] === $user_hash) {
        //id и hash в куках верные
        //обновляем время жизни кук
        save_user_hash_data_in_cookies($result['id'], $user_hash);
        return 1;
    } else {
        return -2;
    }
}


function write_to_log($message)
{
    $file = MY_DOCROOT . '/../log/log.txt';
    file_put_contents($file, date(DATE_RFC2822) . ': ' . $message . "\n\r", FILE_APPEND);
}





function get_articles($besides = '')
{
    global $connect;

    $sql = "SELECT * FROM seo_articles WHERE url_name != :url_name";

    $stmt = $connect->prepare($sql);
    $stmt->execute(['url_name'=>$besides]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


function get_article($url_name)
{    global $connect;
    $stmt = $connect->prepare("SELECT * FROM seo_articles WHERE url_name=:url_name");
    $stmt->execute(['url_name'=>$url_name]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}
