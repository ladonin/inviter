<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic/functions.php');


$sql = "

DELETE FROM `ok_collections_imports`;







INSERT INTO `ok_collections_imports` (`id`, `user_id`, `ids`, `ids_condition`, `ids_not_invited`, `category_id`, `created`, `modified`) VALUES
(59, 1, '1-50', ' (id NOT BETWEEN 1 AND 50) ', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50', 2, 1500715098, 1500715098),
(60, 1, '1-8', ' (id NOT BETWEEN 1 AND 8) ', '1,2,3,4,5,6,7,8', 8, 1500715104, 1500715104),
(61, 1, '1-21', ' (id NOT BETWEEN 1 AND 21) ', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21', 6, 1500715108, 1500715108);

update `ok_imports` set is_invited=0;

update `fb_imports` set is_invited=0;


";

$stmt = $connect->prepare($sql);
$stmt->execute();
