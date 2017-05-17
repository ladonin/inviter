<?php
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
    $word_lenght =  11;
    $word_lenght--;
    for ($i = 0; $i < $word_lenght; $i++) {

        if (rand(0,1) == 1 && $sogl == 0 && $i != $word_lenght && ($i % 2 != 0)){
            $result .= $so[rand(0, 15)];
            $sogl = 1;
        }
        else if (($i+$sogl) % 2 == 0) {
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
    if ($exit) exit();
}



function go_out(){
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







function save_user_hash_data_in_cookies($id, $hash){
    setcookie("au",$hash,time()+(3600*24*31), '/');
    setcookie("du",$id,time()+(3600*24*31), '/');
    $_COOKIE['du'] = $id;
    $_COOKIE['au'] = $hash;
}


function auth_control(){
    global $user_id;
    //проверяем валидность данных в куках
    $user_id = !empty($_COOKIE['du']) ? (int)$_COOKIE['du'] : null;
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
    if($result['hash'] === $user_hash) {
        //id и hash в куках верные
        //обновляем время жизни кук
        save_user_hash_data_in_cookies($result['id'], $user_hash);
        return 1;

    } else{
       return -2;
    }
}

//берем доступные типы пользователей, которые клиент сам импортировал
function get_client_self_load_enabled_users_types(){
    global $user_id;
    global $connect;

    //ищем данные пользователя по id
    $stmt = $connect->prepare("SELECT user_type FROM ok_imports WHERE user_id=:user_id AND is_invited=0 AND is_imported=0 GROUP BY user_type");
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result ?: array();
}




//берем доступные импортированные категории пользователей
function get_client_imported_enabled_categories(){
    global $user_id;
    global $connect;

    //ищем данные пользователя по id
    $stmt = $connect->prepare("SELECT
    c.category_id,
    c.name
    FROM ok_imports e
    LEFT JOIN ok_collections_categories c ON e.category_id=c.category_id


    WHERE e.user_id=:user_id AND e.is_invited=0 AND e.is_imported =1 GROUP BY e.category_id");
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result ?: array();
}




//берем ВСЕ доступные импортированные типы пользователей
function get_client_imported_enabled_types(){
    global $user_id;
    global $connect;

    //ищем данные пользователя по id
    $stmt = $connect->prepare("SELECT user_type FROM ok_imports WHERE user_id=:user_id AND is_invited=0 AND is_imported=1 GROUP BY user_type");
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result ?: array();
}


//берем доступные импортированные типы пользователей по категории
function get_client_imported_enabled_types_by_category($category_id){
    global $user_id;
    global $connect;

    $sql = "SELECT user_type FROM ok_imports WHERE user_id=:user_id AND is_invited=0 AND is_imported=1";
    if ($category_id != 1) {
        $sql .= " AND category_id = :category_id";
    }
    $sql .= " GROUP BY user_type";

    $stmt = $connect->prepare($sql);
    $stmt->execute(array('user_id' => $user_id, 'category_id' => $category_id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result ?: array();
}




//берем доступные типы загрузки пользователей
function get_types_loads_users(){
    global $user_id;
    global $connect;

    $stmt = $connect->prepare("SELECT is_imported FROM ok_imports WHERE user_id=:user_id AND is_invited=0 GROUP BY is_imported");
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result ?: array();
}



    function get_type_name_by_id($user_type){

                    if ($user_type == 1) {
                        return 'Пользователи, поставившие "Класс!"';
                    } elseif ($user_type == 2) {
                        return 'Участники группы ';
                    } elseif ($user_type == 3) {
                        return 'Пользователи из результата поиска';
                    } elseif ($user_type == 5) {
                        return 'Пользователи, учавствующие в опросах';
                    } elseif ($user_type == 6) {
                        return 'Пользователи, оставляющие комментарии';
                    }

    }

    function get_load_type_name_by_id($id){

                    if ($id == 2) {
                        return 'Загруженные мной';
                    } elseif ($id == 3) {
                        return 'Взятые из коллекции';
                    }

    }






    function ok_get_category_type_users_count($category_id,$user_type){
        global $user_id;
        global $connect;
        // берем последний id импорта
        $stmt = $connect->prepare("SELECT data FROM collections_imports
        WHERE user_id=(SELECT id FROM users WHERE id = :user_id)");
        $stmt->execute(array('user_id' => $user_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $import_data = !empty($result['data']) ? $result['data'] : '';
        $import_data = json_decode($import_data, true);
        $last_id = !empty($import_data[$category_id][$user_type]['last_id']) ? $import_data[$category_id][$user_type]['last_id'] : 0;


        // считаем
        $stmt = $connect->prepare("
        SELECT COUNT(0) as count
        FROM ok_collections c
        LEFT JOIN ok_imports e ON c.profile_id = e.profile_id AND e.profile_id IS NULL AND e.user_id=:user_id
        WHERE
        c.category_id=:category_id AND c.user_type =:user_type
        AND c.id > " . $last_id);

        $stmt->execute(array('category_id' => $category_id, 'user_type' => $user_type, 'user_id'=>$user_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }



