<?php
require_once 'notesCatcher.php';
require_once 'Database.php';
/**
 * Created by PhpStorm.
 * User: codev
 * Date: 31/03/2019
 * Time: 12:52
 */

if(!isset($_COOKIE['user_info'])) header('Location:../index.html');
//Suppression de l'entrée dans la base de donnée
$catcher = new NotesCatcher();
$cookie_content = $catcher->decryptCookie(unserialize($_COOKIE['user_info']));

$db = new Database();
$db->removeJson($cookie_content['id']);

//Suppression du cookie
$cookie_name = 'user_info';
unset($_COOKIE[$cookie_name]);
// empty value and expiration one hour before
$res = setcookie($cookie_name, '', time() - 3600);

header('Location:../index.html');

