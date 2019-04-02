<?php
require_once 'Database.php';
require_once 'notesCatcher.php';
/**
 * Created by PhpStorm.
 * User: codev
 * Date: 31/03/2019
 * Time: 10:33
 */

//Vérification de la présence du cookie d'information
if(!isset($_COOKIE['user_info'])){
    header('Content-type: application/json');
    $json = array();
    echo json_encode($json);
    exit;
}
$cookie_content_encrypted = unserialize($_COOKIE['user_info']);

$catcher = new NotesCatcher();
$cookie_content = $catcher->decryptCookie($cookie_content_encrypted);

//Récupération du json
$db = new Database();
$json = $db->getJson($cookie_content['id']);

//Envoi du json
header('Content-type: application/json');
echo json_encode(json_decode($json));