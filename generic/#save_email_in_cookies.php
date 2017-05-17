<?php

if (empty($email)) {}

$email = !empty($_GET['email']) ? $_GET['email'] : '';
}

setcookie("user_email",$email,time()+(3600*24*7*31), '/');


?>