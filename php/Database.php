<?php
/**
 * Created by PhpStorm.
 * User: codev
 * Date: 28/03/2019
 * Time: 20:10
 */
require '../vendor/autoload.php';

use Medoo\Medoo;

class Database
{
    private $db;

    public function __construct()
    {
        //Ouverture de la base de donnée SQLite
        $this->db = new Medoo([
            'database_type' => 'sqlite',
            'database_file' => '../database.db'
        ]);

        //Création de la table
        $this->db->query("CREATE TABLE IF NOT EXISTS user_info (id VARCHAR,json TEXT, date DATE)");
        $this->db->query("CREATE TABLE IF NOT EXISTS user_update (id VARCHAR, lastUpdate DATE)");
    }

    //-- UPDATE --
    public function addUpdate($id)
    {
        //Ajout du nouvel enregistrement
        if ($this->getLastUpdate($id) != null) {
            //Mise à jour de l'enregistrement
            $this->db->update("user_update", [
                "lastUpdate" => time()
            ], [
                "id" => $id
            ]);
        } else {
            //ajout d'un nouvel enregistrement
            $this->db->insert("user_update", [
                "id" => $id,
                "lastUpdate" => time()
            ]);
        }
    }

    public function getLastUpdate($id)
    {
        $data = $this->db->select("user_update", [
            "lastUpdate",
        ], [
            "id" => $id
        ]);
        try {
            $result = @$data[0]['lastUpdate'];
        } catch (Exception $e) {
            return null;
        }
        return $result;
    }

    public function canUserUpdate($id)
    {
        $lastUpdate = $this->getLastUpdate($id);
        if ($lastUpdate == null) return 'true';
        else {
            $end = $lastUpdate + 1800;

            //Calcul du temps restant
            $seconds_diff = $end - time();

            if ($seconds_diff > 0) return gmdate("i:s ", $seconds_diff);
            else return 'true';
        }
    }

    //-- JSON --
    public function saveJson($id, $json)
    {
        //Vérification de l'existance d'un enregistrement
        $data = $this->db->select("user_info", [
            "id",
        ], [
            "id" => $id
        ]);

        if (!empty($data)) {
            //Il existe un enregistrement, on le supprime
            $this->removeJson($id);
        }

        $this->db->insert("user_info", [
            "id" => $id,
            "json" => $json,
            "date" => time()
        ]);
    }

    public function removeJson($id)
    {
        $this->db->delete("user_info", [
            "AND" => [
                "id" => $id
            ]
        ]);
    }

    public function getJson($id)
    {
        $data = $this->db->select("user_info", [
            "id",
            "json",
            "date"
        ], [
            "id" => $id
        ]);
        return $data[0]['json'];
    }
}