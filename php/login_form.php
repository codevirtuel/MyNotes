<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'notesCatcher.php';

//Script de récupération et vérifications des informations de connections
$api_url = "https://alexispoupelin.me";
function error($num)
{
    header('Location:../index.html?error=' . $num);
}

//Check input
if (!isset($_POST['id'])) error(1);
if (!isset($_POST['pass'])) error(1);
if (!isset($_POST['dep'])) error(2);

//Sanitize input
$id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
$pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
$dep = filter_var($_POST['dep'], FILTER_SANITIZE_STRING);
if (isset($_POST['remember'])) {
    $remember = filter_var($_POST['remember'], FILTER_SANITIZE_STRING);
}

//Validation des données
//-- id
if (preg_match('/^i[0-9]+/', $id) == FALSE) {
    //Erreur : id non valide
    error(1);
}

//-- dep
$dep_array = array(
    'INFO', 'MMI', 'GB', 'TC'
);

if (in_array($dep, $dep_array) == FALSE) {
    //Erreur : Département non valide
    error(2);
}

$catcher = new NotesCatcher($id, $pass, $dep);

try {
    if (isset($remember) && strcmp($remember, "on") == 0) {
        $catcher->generateCookie($id,TRUE);
        header('Location:../notes.html');
    } else {
        $catcher->generateCookie($id,FALSE);
        header('Location:../notes.html');
    }
} catch (Exception $e) {
    error(3);
}






