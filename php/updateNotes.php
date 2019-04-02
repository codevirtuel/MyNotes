<?php
require_once 'notesCatcher.php';
require_once 'Database.php';
/**
 * Created by PhpStorm.
 * User: codev
 * Date: 31/03/2019
 * Time: 10:35
 */

//Vérification de la présence du cookie d'info utilisateur
//if(!isset($_COOKIE['user_info']) || isset($_GET['page'])) header('Location:../index.html');

//Récupération du contenu
$cookie_content = unserialize($_COOKIE['user_info']);

//-- Traitement --
//Vérification si la dernière mise à jour à eu lieu il y'a plus de 30min
$db = new Database();
$result = $db->canUserUpdate($cookie_content['id']);

if(strcmp($result,'true') == 0){
    $db->addUpdate($cookie_content['id']);
}else{
    $page = $_GET['page'];
    header('Location:../'.$page.".html?updateError=".$result);
    exit;
}

//Mise à jour
$catcher = new NotesCatcher();
try{
    $catcher->updateNotes($cookie_content);
}catch(Exception $e){
    header('Location:../index.html');
}

header('Location:../notes.html');
