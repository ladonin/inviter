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


function real_ip()
{
    $header_checks = array(
        'HTTP_CLIENT_IP',
        'HTTP_PRAGMA',
        'HTTP_XONNECTION',
        'HTTP_CACHE_INFO',
        'HTTP_XPROXY',
        'HTTP_PROXY',
        'HTTP_PROXY_CONNECTION',
        'HTTP_VIA',
        'HTTP_X_COMING_FROM',
        'HTTP_COMING_FROM',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'ZHTTP_CACHE_CONTROL',
        'REMOTE_ADDR'
    );

    foreach ($header_checks as $key)
    {
        if (array_key_exists($key, $_SERVER) === true)
        {
            foreach (explode(',', $_SERVER[$key]) as $ip)
            {
                $ip = trim($ip);

                //filter the ip with filter functions
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false)
                {
                    return $ip;
                }
            }
        }
    }
}

function isSearchBot($ip=''){
global $user_id;


echo($user_id);


    if (!$ip) {
        $ip = real_ip();
    }
    $ips = array(
        '77.88.22.',
        '77.88.23.',
        '87.250.224.',
        '87.250.255.',
        '93.158.12',
        '93.158.13',
        '93.158.14',
        '93.158.15',
        '93.158.16',
        '93.158.17',
        '93.158.18',
        '93.158.19',
        '95.108.12',
        '95.108.13',
        '95.108.14',
        '95.108.15',
        '95.108.16',
        '95.108.17',
        '95.108.18',
        '95.108.19',
        '95.108.20',
        '95.108.21',
        '95.108.22',
        '95.108.23',
        '95.108.24',
        '95.108.25',
        '213.180.19',
        '213.180.20',
        '213.180.21',
        '64.68.80.',
        '64.68.81.',
        '64.68.82.',
        '64.68.83.',
        '64.68.84.',
        '64.68.85.',
        '64.68.86.',
        '64.68.87.',
        '64.233.160.',
        '64.233.161.',
        '64.233.162.',
        '64.233.163.',
        '64.233.164.',
        '64.233.165.',
        '64.233.166.',
        '64.233.167.',
        '64.233.168.',
        '64.233.169.',
        '64.233.170.',
        '64.233.171.',
        '64.233.172.',
        '64.233.173.',
        '64.233.174.',
        '64.233.175.',
        '66.102.0.',
        '66.102.1.',
        '66.102.2.',
        '66.102.3.',
        '66.102.4.',
        '66.102.5.',
        '66.102.6.',
        '66.102.7.',
        '66.102.8.',
        '66.102.9.',
        '66.102.10.',
        '66.102.11.',
        '66.102.12.',
        '66.102.13.',
        '66.102.14.',
        '66.102.15.',
        '66.231.188.',
        '66.249.6',
        '66.249.7',
        '66.249.8',
        '66.249.9',
        '72.14.19',
        '72.14.20',
        '72.14.21',
        '72.14.22',
        '72.14.23',
        '72.14.24',
        '72.14.25',
        '209.85.1',
        '209.85.2',
        '216.239.3',
        '216.239.4',
        '216.239.5',
        '216.239.6',
        '67.195.',
        '69.147.6',
        '69.147.7',
        '69.147.8',
        '69.147.9',
        '69.147.10',
        '69.147.11',
        '69.147.12',
        '72.30.',
        '74.6.',
        '81.19.64.',
        '81.19.65.',
        '81.19.66.',
        '94.100.17',
        '94.100.18',
        '195.239.211.',
        '65.52.',
        '65.53.',
        '65.54.',
        '65.55.',
        '207.46.',
        '88.212.202.',
        '77.91.224.',
        );

        $pattern = implode('|', $ips);
        $pattern = str_replace('.', '\.', $pattern);

        return (boolean)preg_match("#$pattern#", $ip);
}