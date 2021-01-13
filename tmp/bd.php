<?php 
require "RedBeanPHP/rb.php";

$arrayAllConfig = parse_ini_file("config.ini"); // Получаем все параметры конфига

$host = $arrayAllConfigp[host];
$pass = $arrayAllConfig[password];
$username = $arrayAllConfig[username];
$dbname = $arrayAllConfig[dbname];

R::setup( 'mysql:host='.$host.'; dbname='.$dbname.'' ,''.$username.'', ''.$pass.'');

if ( !R::testConnection() )
{
        exit ('Нет соединения с базой данных');
}

session_start();