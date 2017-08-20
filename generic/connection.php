<?php

try {
    $pdo = new \PDO(
            //'mysql:host=localhost;dbname=quitecorg_invite;charset=UTF8', 'quitecorg_invite', 'neirvaafem', array(
            'mysql:host=localhost;dbname=quitecorg_invite;charset=UTF8', 'root', '', array(
        \PDO::ATTR_PERSISTENT => false,
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
    );
    $pdo->exec('set names UTF8');
} catch (\PDOException $e) {
    echo '[connect error]';
    exit();
}
if (@$pdo) {
    $connect = $pdo;
} else {
    echo '[connect error]';
    exit();
}