<?php
session_start();


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

    $types_fields = array(
        'is_comment' =>6,
        'is_survey' =>5,
        'is_subscriber' =>2,
        'is_klass' =>1,
        'is_search' =>3
        );
    $types_fields_inv = array(
        6 =>'is_comment',
        5 =>'is_survey',
        2 =>'is_subscriber',
        1 =>'is_klass',
        3 =>'is_search'
        );



    /////////////////////////////////// get_available_types_from_base
// доступные типы загруженных через html пользователей
function get_available_loaded_types_from_base($table, $user_id, $is_invited = 0) {

    global $connect;
    global $types_fields;

    $return = array();

    foreach($types_fields as $type_field => $number_type){
        $stmt = $connect->prepare("SELECT count(*) as count FROM $table WHERE user_id=:user_id AND is_invited=$is_invited AND $type_field = 1");
        $stmt->execute(array('user_id' => $user_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result['count'] > 0) {
            $return[]=$number_type;
        }
    }
    return $return ?: array();
}




function ok_get_available_collection_imported_types_from_base_not_invited($user_id, $category_id) {

    global $connect;
    global $types_fields;

    $category_id = (int)$category_id;
    $return = array();

    // берем его неприглашенных пользователей из таблицы ok_collections_imports
    $sql = "SELECT ids_not_invited FROM ok_collections_imports WHERE user_id=:user_id AND category_id=$category_id";

    $stmt = $connect->prepare($sql);
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $ids = $result['ids_not_invited'];

    // преобразуем в условие
    $condition_query = Kits_Converter::convert_numbers_to_query($numbers_array = explode(',',$ids), $not = false);
    $condition_query = $condition_query ?: '';

    // теперь берем все записи из таблицы ok_collections_{$category_id} также как и из ok_imports
    foreach($types_fields as $type_field => $number_type){
        if ($type_field=='is_search') {
            continue;
        }
        $stmt = $connect->prepare("SELECT count(*) as count FROM ok_collections_{$category_id} WHERE ($condition_query) AND $type_field = 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result['count'] > 0) {
            $return[]=[$number_type, get_type_name_by_id($number_type)];
        }
    }
    return $return ?: array();
}


//берем доступные типы пользователей, которые клиент сам импортировал
function get_client_self_load_enabled_users_types(){
    global $user_id;

    $return = get_available_loaded_types_from_base($table='ok_imports', $user_id, $is_invited=0);

    return $return ?: array();
}



function ok_get_category_name($category){
    $stmt = $connect->prepare("SELECT name FROM ok_collections_categories WHERE category_id=$category");
    $stmt->execute();
    return $stmt->fetchColumn();
}









function get_client_imported_categories(){

    global $user_id;
    global $connect;


    // берем все категории клиента

    $stmt = $connect->prepare("
        SELECT
            ok_collections_imports.category_id,
            ok_collections_categories.name
        FROM ok_collections_imports
        LEFT JOIN
            ok_collections_categories ON ok_collections_categories.category_id=ok_collections_imports.category_id
        WHERE
            ok_collections_imports.user_id=:user_id");

    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result ?: array();
}


//берем доступные импортированные категории пользователей
function get_client_imported_enabled_categories(){

    global $user_id;
    global $connect;


    // берем все категории клиента, где еще есть непросмотренные пользователи

    $stmt = $connect->prepare("
        SELECT
            ok_collections_imports.category_id,
            ok_collections_categories.name
        FROM ok_collections_imports
        LEFT JOIN
            ok_collections_categories ON ok_collections_categories.category_id=ok_collections_imports.category_id
        WHERE
            ok_collections_imports.user_id=:user_id AND ok_collections_imports.ids_not_invited!=''");

    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result ?: array();
}


//берем доступные импортированные типы пользователей по категории
function get_client_imported_enabled_types_by_category($category_id){
    global $user_id;

    if ($category_id == 0) {
        // то есть все категории
        $category_id = false;
    }

    $return = ok_get_available_collection_imported_types_from_base_not_invited($user_id, $category_id);
    return $return ?: array();
}

function get_types_text($row, $excel = false){

    global $types_fields;
    $text_array = array();
    foreach($types_fields as $type_number) {

        if ($row[$types_field] == 1) {
            $text_array[]=get_type_name($type_number,$excel);
        }

    }

    return implode(',', $text_array);

}

function get_types_array($row){

    global $types_fields;
    $types_array = array();
    foreach($types_fields as $type_field => $type_number) {

        if (!empty($row[$type_field]) && $row[$type_field] == 1) {
            $types_array[]=$type_number;
        }
    }

return $types_array;

}








//берем доступные типы загрузки пользователей в ok
function ok_get_types_loads_users(){
    global $user_id;
    global $connect;

    $return = array();

    $stmt = $connect->prepare("SELECT count(*) as count FROM ok_imports WHERE user_id=:user_id AND is_invited=0");
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        $return[] = 1;// load
    }

    $stmt = $connect->prepare("SELECT count(*) as count FROM ok_collections_imports WHERE user_id=:user_id AND ids_not_invited!=''");
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result['count'] > 0) {
        $return[] = 2;// import
    }

    return $return;
}



    function get_type_name_by_id($user_type){

                    if ($user_type == 1) {
                        return 'Пользователи, поставившие Класс!';
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


    function get_type_code_by_id($user_type){

                    if ($user_type == 1) {
                        return 'klass';
                    } elseif ($user_type == 2) {
                        return 'subscribe';
                    } elseif ($user_type == 3) {
                        return 'search';
                    } elseif ($user_type == 5) {
                        return 'survey';
                    } elseif ($user_type == 6) {
                        return 'comment';
                    }

    }


    function get_load_type_name_by_id($id){

                    if ($id == 1) {
                        return 'Загруженные мной';
                    } elseif ($id == 2) {
                        return 'Взятые из коллекции';
                    }

    }



    function prepare_import_types_condition($user_type_klass, $user_type_subscriber, $user_type_survey, $user_type_comment){

        $sql = "";

        if ($user_type_klass == -1) {

        } else {

            if (!is_null($user_type_comment)){
                $user_type_comment = $user_type_comment ? 1 :0;
                $sql .= " AND is_comment = $user_type_comment";
            }
            if (!is_null($user_type_survey)){
                $user_type_survey = $user_type_survey ? 1 :0;
                $sql .= " AND is_survey = $user_type_survey";
            }
            if (!is_null($user_type_subscriber)){
                $user_type_subscriber = $user_type_subscriber ? 1 :0;
                $sql .= " AND is_subscriber = $user_type_subscriber";
            }
            if (!is_null($user_type_klass)){
                $user_type_klass = $user_type_klass ? 1 :0;
                $sql .= " AND is_klass = $user_type_klass";
            }
        }
        return $sql;
    }


    function import_needed_types_list($user_type_klass, $user_type_subscriber, $user_type_survey, $user_type_comment){

        $list = "";

        if ($user_type_klass == -1) {
            $list .= 'all';
        } else {

            if (!is_null($user_type_comment)){
                $user_type_comment = $user_type_comment ? 1 :0;
                $list .= "is_comment:$user_type_comment,";
            }
            if (!is_null($user_type_survey)){
                $user_type_survey = $user_type_survey ? 1 :0;
                $list .= " is_survey:$user_type_survey,";
            }
            if (!is_null($user_type_subscriber)){
                $user_type_subscriber = $user_type_subscriber ? 1 :0;
                $list .= "is_subscriber:$user_type_subscriber,";
            }
            if (!is_null($user_type_klass)){
                $user_type_klass = $user_type_klass ? 1 :0;
                $list .= "is_klass:$user_type_klass,";
            }
        }
        return trim($list, ',');
    }




    function ok_get_category_type_users_count_collections($category_id,$user_type_klass, $user_type_subscriber, $user_type_survey, $user_type_comment){

        global $types_fields_inv;
        global $user_id;
        global $connect;

        $category_id = (int)$category_id;


        $stmt = $connect->prepare("
            SELECT ids_condition
        FROM ok_collections_imports
            WHERE user_id = $user_id AND category_id = $category_id");

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);



        $sql = "SELECT
                COUNT(0) as count
            FROM
                ok_collections_$category_id
            WHERE 1 " . ($result['ids_condition'] ? (" AND " . $result['ids_condition']) : '') . " " . prepare_import_types_condition($user_type_klass, $user_type_subscriber, $user_type_survey, $user_type_comment);

        //echo($sql);
        $stmt = $connect->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'];
    }




class Kits_Converter {

    //$row_numbers_array - ключ = значениЮ
    public static function convert_numbers_to_ranges($numbers_array){

        $row_numbers_array = array();
        foreach($numbers_array as $value){
            if ($value) {
                $row_numbers_array[$value] = $value;
            }
        }

        $duplicate_array = $row_numbers_array;
        $new_intervals_array = array();

        foreach($row_numbers_array as $key => $current_value) {

            if ($current_value) {
                //для каждого числа

                unset($duplicate_array[$key]);
                // то есть сравниваем со следующими новыми числами, то есть не текущее с самим собой и не прошлые с текущим


                // интервал для $current_value
                foreach($duplicate_array as  $d_key => $compared_value) {
                    if ($compared_value) {
                        // сравниваемые числа дожны быть меньше на 1 или больше на 1
                        if (($current_value == ($compared_value + 1))
                                || ($current_value == ($compared_value - 1))){

                            // тогда оба числа - текущее и после него (или до него) - переходят в интервал
                            $new_intervals_array[$current_value]=$current_value;
                            $new_intervals_array[$compared_value]=$compared_value;
                        }
                    }
                }
            }
        }
        ksort($new_intervals_array);
        // в итоге получили массив $new_intervals_array от min до max чисел, которые имеют интервал
        // теперь нужно сформировать из них диапазоны
        $current_interval_value = '';
        $new_ranges_array = array();
        $counter_new_ranges = 1;
        foreach($new_intervals_array as $new_interval_key => $new_interval_value) {
            if ($new_interval_value) {
                // удаляем эти числа из $row_numbers_array, т.к. они теперь будут в диапазоне
                unset($row_numbers_array[$new_interval_key]);

                ### задача - получить двухмерный массив с наборами чисел
                if (!$current_interval_value) {
                    //с этой записи все начинается
                    $new_ranges_array[$counter_new_ranges][] = $new_interval_value;
                } else {
                    // далее думаем - в какой набор добавлять новое число
                    if ($current_interval_value == ($new_interval_value - 1)) { // ($new_interval_value + 1) тут не нужен, так как массив отсортирован по ASC
                        // означает продолжение текущего набора
                    } else {
                        // означает новый диапазон
                        $counter_new_ranges ++;
                    }
                    $new_ranges_array[$counter_new_ranges][] = $new_interval_value;
                }
                $current_interval_value = $new_interval_value;
            }
        }
        ksort($row_numbers_array);
        return array(
            'new_ranges_array' => $new_ranges_array,// - двухмерный массив с новыми диапазонами старых и новых чисел
            'row_numbers_array' => $row_numbers_array// - оставшиеся числа - старые и новые, что не стали интервалами
        );
    }



    public static function convert_to_intenvals($current_row, $additional_row, $not_query = true){
        // $current_row - строка из базы - в котором могут присутствовать интервалы и строки
        // $additional_row - строка с новыми числами

        // добавляем в конец новый ряд
        $current_row = $current_row ? ($current_row . ',' . $additional_row) : $additional_row;

        // в итоге получаем новый непреобразованный ряд в виде строки

        // преобразуем строковый ряд в массив
        $row_generic_array = explode(',', $current_row);

        //получили массив со строками с отдельными числами и интервалами

        // разделяем этот массив на 2 - с отдельными числами и интервалами
        // 1: с отдельными числами
        $row_numbers_array = array();
        foreach($row_generic_array as $key => $row_generic_value) {
            if ($row_generic_value) {
                if (strpos($row_generic_value, '-') === false) {
                    $row_numbers_array[$row_generic_value] = $row_generic_value;
                    unset($row_generic_array[$key]);
                }
            }
        }

        // 2: с интервалами
        // оставшиеся элементы - это интервалы
        $row_intervals_array = $row_generic_array;
        unset($row_generic_array);

        // в итоге имеем
        // $row_numbers_array - массив с числами
        // $row_intervals_array - массив с интервалами


        # задача - сделать как можно больше интервалов
        //(числа могут либо стать новыми интервалами, либо расширить существующий интервал)


        ## задача 1 - попробовать преобразовать числа в диапазоны
        $current_result = self::convert_numbers_to_ranges($row_numbers_array);
        $new_ranges_array = $current_result['new_ranges_array'];
        $row_numbers_array = $current_result['row_numbers_array'];
        // в итоге получили - двухмерный массив с новыми диапазонами старых (включительно) и новых чисел (заметьте - сформированные ранее диапазоны тут не упоминаются... только числа) и массив с просто числами за все время, не образующими диапазон ни с кем
        // ! все это сформировано из чисел - новых и старых
        // $new_ranges_array - двухмерный массив с новыми диапазонами старых и новых чисел
        // и $row_numbers_array - оставшиеся числа - старые и новые, что не стали интервалами

        // $row_intervals_array - массив со старыми интервалами
        //print_r($new_ranges_array);
        //print_r($row_numbers_array);


        ## задача 2 - внедрить новые интервалы в уже существующие (старые) - состыковать по возможности или просто добавить как новые
        $replaced_intervals = array();
        //! новые интервалы между собой не свяжутся, потому что, если бы это было бы так, то они бы уже связались при формировании $new_ranges_array
        // проходимся по старым интервалам и пытаемся связать каждый новый интервал с каждым старым
        foreach($row_intervals_array as &$row_interval_string) {
            if ($row_interval_string) {
                $range_min_max = explode('-', $row_interval_string);
                $min_current_range = $range_min_max[0];// - минимальное число из интервала
                $max_current_range = $range_min_max[1];// - максимальное число из интервала
                foreach($new_ranges_array as $new_range_key => $new_range_array) {
                    if ($new_range_array) {
                        if (array_key_exists($new_range_key, $replaced_intervals)) {
                            // этот новый интервал уже состыкован, потом попытаемся объединить между собой обновленные интервалы
                            continue;
                        }

                        $min_new_range = current($new_range_array);
                        $max_new_range = end($new_range_array);

                        // --> (продублировано ниже)
                        // какие варианты могут быть:
                        $was_replaced = false;
                        if ((($max_current_range + 1) == $min_new_range) // рядом по правую сторону от старой
                                || (($min_new_range <= $max_current_range) && ($min_new_range >= $min_current_range)))
                                // левая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона, либо поглощен старым диапазоном, либо дипазоны полностью идентичны
                        {
                            //echo "=1=";
                            $max_current_range = ($max_current_range < $max_new_range) ? $max_new_range : $max_current_range;
                            $was_replaced = true;
                        } elseif ((($min_current_range - 1) == $max_new_range) // рядом по левую сторону от старой
                                || (($max_new_range >= $min_current_range) && ($max_new_range <= $max_current_range)))
                                // правая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона, либо поглощен старым диапазоном, либо дипазоны полностью идентичны
                        {

                           //echo "=2=";
                           $min_current_range = ($min_current_range > $min_new_range) ? $min_new_range : $min_current_range;
                           $was_replaced = true;
                        } elseif (($max_current_range < $max_new_range) && ($min_current_range > $min_new_range)) {// новый диапазон перекрывает по всем сторонам старый, то ес тьрасширяет его с двух сторон
                            //echo "=3=";
                            $min_current_range = $min_new_range;
                            $max_current_range = $max_new_range;
                            $was_replaced = true;
                        }
                        // <-- (продублировано ниже)

                        if ($was_replaced == true) {
                            $replaced_intervals[$new_range_key] = $new_range_key;
                            // переписываем интервал
                            $row_interval_string = $min_current_range . '-' . $max_current_range;
                            // удаляем перенесенный интервал из $new_ranges_array
                            unset($new_ranges_array[$new_range_key]);
                        }
        //echo $min_current_range."\n";
        //echo $max_current_range."\n";
        //echo $min_new_range."\n";
        //echo $max_new_range."\n\n\n";
                    }
                }
            }
        }

        // добавляем новые несостыкованные интервалы к старым как отдельные
        foreach ($new_ranges_array as $new_range_array) {
            if ($new_range_array) {
                $row_intervals_array[] = current($new_range_array) . '-' . end($new_range_array);
            }
        }
        unset($new_ranges_array);
        // в итоге
        // перенесли интервалы из $new_ranges_array в $row_intervals_array, по возможности состыковав со старыми
        // имеем:
        // и $row_numbers_array - все числа
        // $row_intervals_array - массив со всеми интервалами


        ## задача 3 - попробовать объединить существующие интервалы между собой
        #! надо уничтожить лишние интервалы и, если один интервал состыковывается с несколькими, то не надо расширять все, достаточно расширить один, иначе будет сильное ненужное перекрытие
        $duplicate_array = $row_intervals_array;
        $replaced_intervals = array();
        foreach ($row_intervals_array as $key => &$current_value){
            if ($current_value) {
                //для каждого интервала
                unset($duplicate_array[$key]);

                if (array_key_exists($key, $replaced_intervals)) {
                    continue;
                }

                // то есть сравниваем со следующими новыми интервалами, то есть не текущий с самим собой и не прошлые с текущим

                // для $current_value
                $range_min_max_current = explode('-', $current_value);
                $min_current_range = $range_min_max_current[0];// - минимальное число из интервала
                $max_current_range = $range_min_max_current[1];// - максимальное число из интервала
                foreach($duplicate_array as  $d_key => $compared_value) {
                    if ($compared_value) {
                        if (array_key_exists($d_key, $replaced_intervals)) {
                            continue;
                        }
                        $range_min_max_compared = explode('-', $compared_value);
                        $min_compared_range = $range_min_max_compared[0];// - минимальное число из интервала
                        $max_compared_range = $range_min_max_compared[1];// - максимальное число из интервала

                        // --> (продублировано выше)
                        // какие варианты могут быть:
                        $was_replaced = false;
                        if ((($max_current_range + 1) == $min_compared_range) // рядом по правую сторону от старой
                                || (($min_compared_range <= $max_current_range) && ($min_compared_range >= $min_current_range)))
                                // левая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона, либо поглощен старым диапазоном, либо дипазоны полностью идентичны
                        {
                            //echo "=1=";
                            $max_current_range = ($max_current_range < $max_compared_range) ? $max_compared_range : $max_current_range;
                            $was_replaced = true;
                        } elseif ((($min_current_range - 1) == $max_compared_range) // рядом по левую сторону от старой
                                || (($max_compared_range >= $min_current_range) && ($max_compared_range <= $max_current_range)))
                                // правая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона, либо поглощен старым диапазоном, либо дипазоны полностью идентичны
                        {

                           //echo "=2=";
                           $min_current_range = ($min_current_range > $min_compared_range) ? $min_compared_range : $min_current_range;
                           $was_replaced = true;
                        } elseif (($max_current_range < $max_compared_range) && ($min_current_range > $min_compared_range)) {// новый диапазон перекрывает по всем сторонам старый, то есть расширяет его с двух сторон
                            //echo "=3=";
                            $min_current_range = $min_compared_range;
                            $max_current_range = $max_compared_range;
                            $was_replaced = true;
                        }
                        // <-- (продублировано выше)

                        // либо $current_value расширен либо $compared_value поглощен by $current_value
                        if ($was_replaced == true) {
                            $replaced_intervals[$d_key] = $d_key;
                            // переписываем интервал
                            $current_value = $min_current_range . '-' . $max_current_range;
                            // удаляем состыкованный интервал из $row_intervals_array
                            unset($row_intervals_array[$d_key]);
                        }
        //echo $min_current_range."\n";
        //echo $max_current_range."\n";
        //echo $min_compared_range."\n";
        //echo $max_compared_range."\n\n\n";
                    }
                }
            }
        }
        // в итоге получили
        // оптимизированный $row_intervals_array - часть интервалов попытались объединить
        // $row_numbers_array - все числа




        ## задача 4 - получившиеся новые интервалы мы уже попробовали состыковать со старыми,
        ## теперь попробуем состыковать оставшиеся числа с получившимися интервалами
        ## (чисел в конце алгоритма должно стать меньше, равно как и интервалов (если они "слились" между собой), если добавились, то не на много, не стоит бояться)


        // имеем - оптимизированные интервалы $row_intervals_array и числа $row_numbers_array
        // проходим по каждому интервалу в $row_intervals_array и состыковываем числа из $row_numbers_array с ними
        $replaced_numbers = array();
        foreach ($row_intervals_array as $key => &$current_value){
            if ($current_value) {
                // для $current_value
                $range_min_max_current = explode('-', $current_value);
                $min_current_range = $range_min_max_current[0];// - минимальное число из интервала
                $max_current_range = $range_min_max_current[1];// - максимальное число из интервала

                // проходим по каждому числу
                foreach ($row_numbers_array as $row_number_key => $row_number_value){
                    if ($row_number_key) {
                        if (array_key_exists($row_number_key, $replaced_numbers)) {
                            continue;
                        }

                        $was_replaced = false;


                        if ($row_number_value == ($min_current_range - 1)) {
                            //если присостыковывается слева
                            $min_current_range = $row_number_value;
                            $was_replaced = true;
                        } else if ($row_number_value == ($max_current_range + 1)) {
                            //если присостыковывается справа
                            $max_current_range = $row_number_value;
                            $was_replaced = true;
                        } elseif (($row_number_value >=$min_current_range) && ($row_number_value <=$max_current_range)){
                            // если внутри интервала
                            $was_replaced = true;
                        }

                        if ($was_replaced == true) {
                            $replaced_numbers[$row_number_key] = $row_number_key;
                            // переписываем интервал
                            $current_value = $min_current_range . '-' . $max_current_range;
                            // удаляем состыкованное число из $row_numbers_array
                            unset($row_numbers_array[$row_number_key]);
                        }
                    }
                }
            }
        }
        // в итоге имеем
        // $row_intervals_array - полностью оптимизированные интервалы (если еще раз прогнать их, то возможно станут более оптимизированнее, но это можно сделать при очередной прогонке после следующего импорта из коллекции)
        // $row_numbers_array - числа, которые еще не состыкуются с интервалами (возможно при следующем импорте они дополнятся новыми числами и образуют наконец свой интервал и по возможности сольются с существующими интервалами)


        ## задача 5 - сформировать строку
        $stroke = '';
        $intervals_stroke = implode(',',$row_intervals_array);
        $numbers_stroke = implode(',',$row_numbers_array);
        if ($numbers_stroke && $intervals_stroke) {
            $stroke = $intervals_stroke . ',' . $numbers_stroke;
        } elseif ($intervals_stroke) {
           $stroke = $intervals_stroke;
        } elseif ($numbers_stroke) {
            $stroke = $numbers_stroke;
        }


        ## задача 6 - сформировать SQL запрос
        $not = $not_query ? 'NOT' : '';
        $sql_condition = '';
        foreach($row_intervals_array as $row_interval_value){
            if ($row_interval_value) {
                $range_min_max_current = explode('-', $row_interval_value);
                $min_current_range = $range_min_max_current[0];// - минимальное число из интервала
                $max_current_range = $range_min_max_current[1];// - максимальное число из интервала
                $sql_condition .= " (id $not BETWEEN " . $min_current_range . ' AND ' . $max_current_range . ') AND';
            }
        }
        if (!$numbers_stroke) {
            $sql_condition = trim($sql_condition, 'AND');
        } else {
            $sql_condition .= " id $not IN (" . $numbers_stroke . ')';
        }

        return array(
            'stroke' => $stroke,
            'sql_condition' => $sql_condition
        );
    }

    /* тест
     *
     *
     *
     *
     *
     * задача 2
     * <?php

    $new_ranges_array = [0=>[215,216,217,218],1=>[109,110,111,112,113,114,115,116]];
    $row_intervals_array = ['10-12','114-119','251-309'];

         ## задача 2 - внедрить новые интервалы в уже существующие (старые) - состыковать по возможности или просто добавить как новые
        $replaced_intervals = array();
        //! новые интервалы между собой не свяжутся, потому что, если бы это было бы так, то они бы уже связались при формировании $new_ranges_array
        // проходимся по старым интервалам и пытаемся связать каждый новый интервал с каждым старым
        foreach($row_intervals_array as &$row_interval_string) {
            $range_min_max = explode('-', $row_interval_string);
            $min_current_range = $range_min_max[0];// - минимальное число из интервала
            $max_current_range = $range_min_max[1];// - максимальное число из интервала
            foreach($new_ranges_array as $new_range_key => $new_range_array) {


                if (array_key_exists($new_range_key, $replaced_intervals)) {
                    continue;
                }

                $min_new_range = current($new_range_array);
                $max_new_range = end($new_range_array);
                // какие варианты могут быть:
                $was_replaced = false;
                if ((($max_current_range + 1) == $min_new_range) // рядом по правую сторону от старой
                        || (($min_new_range <= $max_current_range) && ($min_new_range >= $min_current_range)))
                        // левая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона
                {
                    //echo "=1=";
                    $max_current_range = ($max_current_range < $max_new_range) ? $max_new_range : $max_current_range;
                    $was_replaced = true;
                } elseif ((($min_current_range - 1) == $max_new_range) // рядом по левую сторону от старой
                        || (($max_new_range >= $min_current_range) && ($max_new_range <= $max_current_range)))
                        // правая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона
                {
                   //echo "=2=";
                   $min_current_range = ($min_current_range > $min_new_range) ? $min_new_range : $min_current_range;
                   $was_replaced = true;
                } elseif (($max_current_range < $max_new_range) && ($min_current_range > $min_new_range)) {// новый диапазон перекрывает по всем сторонам старый, то ес тьрасширяет его с двух сторон
                    //echo "=3=";
                    $min_current_range = $min_new_range;
                    $max_current_range = $max_new_range;
                    $was_replaced = true;
                }

                if ($was_replaced == true) {
                    $replaced_intervals[$new_range_key] = $new_range_key;
                    // переписываем интервал
                    $row_interval_string = $min_current_range . '-' . $max_current_range;
                    unset($new_ranges_array[$new_range_key]);
                }
//echo $min_current_range."\n";
//echo $max_current_range."\n";
//echo $min_new_range."\n";
//echo $max_new_range."\n\n\n";
            }
        }
        // добавляем новые несостыкованные интервалы к старым как отдельные
        foreach ($new_ranges_array as $new_range_array) {
            $row_intervals_array[] = current($new_range_array) . '-' . end($new_range_array);
        }
        unset($new_ranges_array);
        print_r($row_intervals_array);
     *
     *
     *
     *
     *
     *
     *
     *
     *
     * задача 3
     * <?php
    $row_intervals_array = ['14-119','51-309','10-19'];
        ## задача 3 - попробовать объединить существующие интервалы между собой
        #! надо уничтожить лишние интервалы и, если один интервал состыковывается с несколькими, то не надо расширять все, достаточно расширить один, иначе будет сильное ненужное перекрытие
        $duplicate_array = $row_intervals_array;
        $replaced_intervals = array();
        foreach ($row_intervals_array as $key => &$current_value){
            //для каждого интервала
            unset($duplicate_array[$key]);

            if (array_key_exists($key, $replaced_intervals)) {
                continue;
            }

            // то есть сравниваем со следующими новыми интервалами, то есть не текущий с самим собой и не прошлые с текущим

            // для $current_value
            $range_min_max_current = explode('-', $current_value);
            $min_current_range = $range_min_max_current[0];// - минимальное число из интервала
            $max_current_range = $range_min_max_current[1];// - максимальное число из интервала
            foreach($duplicate_array as  $d_key => $compared_value) {
                if (array_key_exists($d_key, $replaced_intervals)) {
                    continue;
                }
                $range_min_max_compared = explode('-', $compared_value);
                $min_compared_range = $range_min_max_compared[0];// - минимальное число из интервала
                $max_compared_range = $range_min_max_compared[1];// - максимальное число из интервала

                // --> (продублировано выше)
                // какие варианты могут быть:
                $was_replaced = false;
                if ((($max_current_range + 1) == $min_compared_range) // рядом по правую сторону от старой
                        || (($min_compared_range <= $max_current_range) && ($min_compared_range >= $min_current_range)))
                        // левая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона
                {
                    //echo "=1=";
                    $max_current_range = ($max_current_range < $max_compared_range) ? $max_compared_range : $max_current_range;
                    $was_replaced = true;
                } elseif ((($min_current_range - 1) == $max_compared_range) // рядом по левую сторону от старой
                        || (($max_compared_range >= $min_current_range) && ($max_compared_range <= $max_current_range)))
                        // правая сторона нового диапазона внутри, либо соприкасаясь с границами старого диапазона
                {

                   //echo "=2=";
                   $min_current_range = ($min_current_range > $min_compared_range) ? $min_compared_range : $min_current_range;
                   $was_replaced = true;
                } elseif (($max_current_range < $max_compared_range) && ($min_current_range > $min_compared_range)) {// новый диапазон перекрывает по всем сторонам старый, то есть расширяет его с двух сторон
                    //echo "=3=";
                    $min_current_range = $min_compared_range;
                    $max_current_range = $max_compared_range;
                    $was_replaced = true;
                }
                // <-- (продублировано выше)

                // либо $current_value расширен либо $compared_value поглощен by $current_value
                if ($was_replaced == true) {
                    $replaced_intervals[$d_key] = $d_key;
                    // переписываем интервал
                    $current_value = $min_current_range . '-' . $max_current_range;
                    // удаляем состыкованный интервал из $row_intervals_array
                    unset($row_intervals_array[$d_key]);
                }
//echo $min_current_range."\n";
//echo $max_current_range."\n";
//echo $min_compared_range."\n";
//echo $max_compared_range."\n\n\n";
            }
            // в итоге получили сокращенный $row_intervals_array - часть интервалов попытались объединить
        }


        print_r($row_intervals_array);
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
<?php


    $row_intervals_array = ['14-119','121-309','10-120'];

$row_numbers_array = [1,5,19,190,310];
        ## задача 4 - получившиеся новые интервалы мы уже попробовали состыковать со старыми,
        ## теперь попробуем состыковать оставшиеся числа с получившимися интервалами
        ## (чисел в конце алгоритма должно стать меньше, равно как и интервалов (если они "слились" между собой), если добавились, то не на много, не стоит бояться)


        // имеем - оптимизированные интервалы $row_intervals_array и числа $row_numbers_array
        // проходим по каждому интервалу в $row_intervals_array и состыковываем числа из $row_numbers_array с ними
        $replaced_numbers = array();
        foreach ($row_intervals_array as $key => &$current_value){
            // для $current_value
            $range_min_max_current = explode('-', $current_value);
            $min_current_range = $range_min_max_current[0];// - минимальное число из интервала
            $max_current_range = $range_min_max_current[1];// - максимальное число из интервала

            // проходим по каждому числу
            foreach ($row_numbers_array as $row_number_key => $row_number_value){

                if (array_key_exists($row_number_key, $replaced_numbers)) {
                    continue;
                }

                $was_replaced = false;


                if ($row_number_value == ($min_current_range - 1)) {
                    //если присостыковывается слева
                    $min_current_range = $row_number_value;
                    $was_replaced = true;
                } else if ($row_number_value == ($max_current_range + 1)) {
                    //если присостыковывается справа
                    $max_current_range = $row_number_value;
                    $was_replaced = true;
                } elseif (($row_number_value >=$min_current_range) && ($row_number_value <=$max_current_range)){
                    // если внутри интервала
                    $was_replaced = true;
                }

                if ($was_replaced == true) {
                    $replaced_numbers[$row_number_key] = $row_number_key;
                    // переписываем интервал
                    $current_value = $min_current_range . '-' . $max_current_range;
                    // удаляем состыкованное число из $row_numbers_array
                    unset($row_numbers_array[$row_number_key]);
                }
            }
        }

        print_r($row_intervals_array);

        print_r($row_numbers_array);
     *
     *
     *
     *
     *
     *
     *
     *
     *
     */

    public static function add_to_kit($ids_current, $ids_new){
        $ids_current_array  = explode(',', $ids_current);
        $ids_new_array      = explode(',', $ids_new);

        $ids_result_array = array();
        foreach($ids_current_array as $ids_current_value) {
            if ($ids_current_value) {
                $ids_result_array[$ids_current_value] = $ids_current_value;
            }
        }
        foreach($ids_new_array as $ids_new_value) {
            if ($ids_new_value) {
                $ids_result_array[$ids_new_value] = $ids_new_value;
            }
        }

        return $ids_result_array;
    }


    public static function remove_from_kit($ids_current, $ids_to_remove){
        $ids_current_array      = explode(',', $ids_current);
        $ids_to_remove_array   = explode(',', $ids_to_remove);

        $ids_result_array = array();
        foreach($ids_current_array as $ids_current_value) {
            if ($ids_current_value) {
                $ids_result_array[$ids_current_value] = $ids_current_value;
            }
        }
        foreach($ids_to_remove_array as $ids_to_remove_value) {
            unset($ids_result_array[$ids_to_remove_value]);
        }

        return $ids_result_array;
    }

    public static function convert_numbers_to_not_query($numbers_array){
        return self::convert_numbers_to_query($numbers_array, $not = true);
    }

    //$numbers_array - может быть [1=>3] или [3=>3] - не важно - все равно массив приводится к требуемому состоянию в методе convert_numbers_to_ranges()
    public static function convert_numbers_to_query($numbers_array, $not = false){

        $not = $not ? 'NOT ' : '';

        $numbers_to_ranges_result = self::convert_numbers_to_ranges($numbers_array);
        $ranges_array = $numbers_to_ranges_result['new_ranges_array'];
        $numbers_array = $numbers_to_ranges_result['row_numbers_array'];
        // имеем
        // $ranges_array - двухмерный массив с новыми диапазонами старых и новых чисел
        // и $numbers_array - оставшиеся числа - старые и новые, что не стали интервалами
        $sql_condition = '';
        foreach($ranges_array as $range_key => $range_array) {
            if ($range_array) {
                $min = current($range_array);
                $max = end($range_array);
                $sql_condition .= ' (id' . $not . ' BETWEEN ' . $min . ' AND ' . $max . ') ' . ($not ? 'AND' : 'OR');
            }
        }

        $numbers_stroke = implode(',',$numbers_array);

        if (!$numbers_stroke) {
            $sql_condition = trim($sql_condition,  ($not ? 'AND' : 'OR'));
        } else {
            $sql_condition .= ' id' . $not . ' IN (' . $numbers_stroke . ')';
        }

        return $sql_condition;
    }

    public static function add_new_numbers_to_numbers($old_numbers, $new_numbers){

        $result = array();
        foreach($old_numbers as $value) {
            if ($value) {
                $result[$value] = $value;
            }
        }
        foreach($new_numbers as $new_numbers) {
            if ($new_numbers) {
                $result[$new_numbers] = $new_numbers;
            }
        }
        ksort($result);

        return $result;
    }

}



function get_not_invited_count()
{
    global $connect;
    global $user_id;

    /* $stmt = $connect->prepare("SELECT count(*) as count FROM ok_imports where user_id=$user_id and is_invited = 1");
      $stmt->execute(array('user_id' => $user_id));
      $count_invited_loaded = $stmt->fetchColumn(); */

    // для импортнутых из html
    $stmt = $connect->prepare("SELECT count(*) as count FROM ok_imports where user_id=$user_id and is_invited = 0");
    $stmt->execute(array('user_id' => $user_id));
    $count_non_invited_loaded = $stmt->fetchColumn();


    // для импортнутых из коллекции
    $count_non_invited_from_collection = 0;
    $stmt = $connect->prepare("SELECT id from ok_collections_categories");
    $stmt->execute();
    $all_categories = $stmt->fetchAll();

    $all_categories_list = '';
    foreach ($all_categories as $category) {
        $all_categories_list .= $category['id'] . ',';
    }
    $all_categories_list = trim($all_categories_list, ',');

    $stmt = $connect->prepare("SELECT * from ok_collections_imports where user_id = $user_id and category_id IN ($all_categories_list)");
    $stmt->execute();
    $all_categories = $stmt->fetchAll();

    foreach ($all_categories as $category) {
        if ($category['ids_not_invited']) {
            $count_non_invited_from_collection += count(explode(',', $category['ids_not_invited']));
        }
    }
    return $count_non_invited_loaded + $count_non_invited_from_collection;
}


// стоимость за одного пользователя по заданным параметрам
// > метод, позволяющий при импорте определять стоимость 1 пользователя по его типам ,что указали в чекбоксах, (чем больше в его составе типов, тем дороже)
//>> что делаем: берем самый дорогой тип, делаем цену = его стоимости, потом прибавляем к нему остальные помноженнные, скажем, на 30%
//>> т.е. стоимость составного типа = стоимость самого дорогого типа + (сумма стоимостей остальных типов)*0.3

//NOTE - есть такая же JS функция get_ok_import_collection_request_cost_per_one_user()
function get_ok_import_collection_request_cost_per_one_user($data){

    if (($data['KLASS'] == -1) && ($data['SUBSCRIBER'] == -1) && ($data['SURVEY'] == -1) && ($data['COMMENT'] == -1)) {
        return MY_USER_IMPORT_COST['GENERIC'];
    }


    $most_expensive = 0;
    $most_expensive_type = '';


    $count_types = 0;
    $tmp_type = '';
    foreach($data as $type => $value){
        if ($value) {
            $count_types++;
            $tmp_type = $type;
            if ($most_expensive < MY_USER_IMPORT_COST[$type]) {
                $most_expensive = MY_USER_IMPORT_COST[$type];
                $most_expensive_type = $type;
            }
        }
    }
    // если выбран 1 тип
    if ($count_types == 1) {
        return round(MY_USER_IMPORT_COST[$tmp_type], 2);
    }
    if ($count_types == 0) {
        return null;
    }

    // если выбрано 2+ типа
    $price = $most_expensive;

    foreach(MY_USER_IMPORT_COST as $type => $amount) {
        if ((!empty($data[$type])) && ($most_expensive_type != $type)) {
            $price += $amount * MY_USER_IMPORT_COST_ADDITIONAL_KOEF;
        }
    }
    return round($price, 2);
}