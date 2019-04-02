<?php
/**
 * Created by PhpStorm.
 * User: codev
 * Date: 31/03/2019
 * Time: 11:41
 */
require_once 'notesCatcher.php';

//Vérification de la présence du cookie d'information
if(!isset($_COOKIE['user_info'])){
    $json = array();
    header('Content-type: application/json');
    echo json_encode($json);
    exit;
}
$cookie_content_encrypted = unserialize($_COOKIE['user_info']);

$catcher = new NotesCatcher();
$cookie_content = $catcher->decryptCookie($cookie_content_encrypted);

$info = array(
  'id' => $cookie_content['id'],
  'dep' => $cookie_content['dep']
);

header('Content-type: application/json');
echo json_encode($info);