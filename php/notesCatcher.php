<?php
/**
 * Created by PhpStorm.
 * User: codev
 * Date: 28/03/2019
 * Time: 14:00
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'Database.php';
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

class NotesCatcher
{
    const SERVER = "https://alexispoupelin.me"; //Serveur API
    private $id;
    private $pass;
    private $dep;

    public function __construct($id = null, $pass = null, $dep = null)
    {
        $this->id = $id;
        $this->pass = $pass;
        $this->dep = $dep;
    }

    /**
     * Envoie la demande de json au serveur
     */
    private function restRequest($server_url)
    {
        //Construction de l'URL de requête
        $request_url = $server_url . "/getAllNotes?id=" . $this->id . "&pass=" . $this->pass . "&dep=" . $this->dep;

        //Envoi de la requête
        $json = @file_get_contents($request_url);

        //Check http return codes
        switch ($http_response_header[0]) {
            case "HTTP/1.1 501 Not Implemented":
                //Error : mauvais identifiant ou mot de passe
                throw new Exception('501');
                break;
        }

        return $json;
    }

    /**
     * Génère un tableau contenant l'identifiant, le mot de passe et le nom du département.
     * Tous les paramètres sont encryptés avec une clé secrète
     */
    private function generateEncryptedInfo(){
        //Récupération de la clé de cryptage / décryptage
        $keyContents = file_get_contents(__DIR__ . '/../../keyfile');
        $key = Key::loadFromAsciiSafeString($keyContents);

        //Cryptage des données du cookie
        $id_encrypted = Crypto::encrypt($this->id,$key);
        $pass_encrypted = Crypto::encrypt($this->pass,$key);
        $dep_encrypted = Crypto::encrypt($this->dep,$key);

        $cookie_content = array(
            'id' => $id_encrypted,
            'pass' => $pass_encrypted,
            'dep' => $dep_encrypted
        );

        return $cookie_content;
    }

    public function decryptCookie($cookie_content){
        $keyContents = file_get_contents(__DIR__ . '/../../keyfile');
        $key = Key::loadFromAsciiSafeString($keyContents);

        //Décryptage des données du cookie
        $id_decrypted = Crypto::decrypt($cookie_content['id'],$key);
        $pass_decrypted = Crypto::decrypt($cookie_content['pass'],$key);
        $dep_decrypted = Crypto::decrypt($cookie_content['dep'],$key);

        $cookie_new_content = array(
            'id' => $id_decrypted,
            'pass' => $pass_decrypted,
            'dep' => $dep_decrypted
        );

        return $cookie_new_content;
    }

    /**
     * Créé un cookies :
     *  user_info - contenant les informations de connection de l'utilisateur (encrypté) pour mettre à jour les notes
     * Ajoute dans une base de donnée locale :
     *  user_notes - contenant le json des notes
     *
     *  Paramètre :
     *      rememberMe (boolean) : Si vrai, fait perdurer le cookie user_info pendant 30jours
     *                             Si faux, le cookie sera supprimé à la fermeture du navigateur
     */
    public function generateCookie($id,$rememberMe = FALSE){
        $cookie_content = $this->generateEncryptedInfo();
        $notes_json = $this->restRequest(self::SERVER);
        var_dump($notes_json,$cookie_content);
        if(empty($notes_json) || empty($cookie_content)){
            throw new Exception('Message empty');
        }

        //Enregistrement du json sur la base de donnée
        $db = new Database();
        $db->saveJson($id,$notes_json);

        if(isset($_COOKIE['user_info'])) unset($_COOKIE['user_info']);

        if($rememberMe){
            setcookie("user_info", serialize($cookie_content), time() +60*60*24*30);
        }else{
            setcookie("user_info", serialize($cookie_content));
        }
    }

    /**
     * Met à jour la base de donnée avec un cookie existant
     * @param $cookie_content
     * @throws Exception
     */
    public function updateNotes($cookie_content){
        //Vérification du contenu
        if(!array_key_exists('id',$cookie_content) && !array_key_exists('pass',$cookie_content) && !array_key_exists('dep',$cookie_content)){
            throw new Exception('Invalid cookie');
        }

        //Décryptage
        $cookie_content_decrypted = $this->decryptCookie($cookie_content);
        $this->id = $cookie_content_decrypted['id'];
        $this->dep = $cookie_content_decrypted['dep'];
        $this->pass = $cookie_content_decrypted['pass'];

        //Mise à jour
        $notes_json = $this->restRequest(self::SERVER);

        //Enregistrement du json sur la base de donnée
        $db = new Database();
        $db->saveJson($this->id,$notes_json);
    }
}