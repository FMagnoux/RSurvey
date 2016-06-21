<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 11:36
 */
abstract class SQL
{
    protected $db;

    /**
     * PDO constructor.
     */
    public function __construct() {
        try {
            $engine = 'mysql';
            $host = '127.0.0.1';
            $port ='3306';
            $database ='rsurvey';
            $user = 'root';
            $password = '';
            $dns = $engine.':port='.$port.';dbname='.$database.";host=".$host;
            $this->db = new PDO($dns, $user, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Helper pour créer une requête SQL et la lancer
     * @param type $config Colonnes de la table, table, jointure, limite...
     * @param type $values Valeur des conditions
     * @return array Résultat de la requête SQL
     */
    public function select($config, $values = null) {
        $sql = "SELECT " . $config["columns"] . " FROM " . $config["table"];
        if (!empty($config["join"])) {
            if (!empty($config["join"][0]) && is_array($config["join"][0])) {
                foreach ($config["join"] as $elt) {
                    $sql .= " INNER JOIN " . $elt["table"] . " ON " . $elt["table"] . "." . $elt["key"] . " = " . $config["table"] . "." . $elt["foreignKey"];
                }
            } else {
                $sql .= " INNER JOIN " . $config["join"]["table"] . " ON " . $config["join"]["table"] . "." . $config["join"]["key"] . " = " . $config["table"] . "." . $config["join"]["foreignKey"];

            }
        }
        if (!empty($config["where"])) {
            $sql .= " WHERE " . $config["where"];
        }
        if (!empty($config["group"])) {
            $sql .= " GROUP BY " . $config["group"];
        }
        if (!empty($config["order"])) {
            $sql .= " ORDER BY " . $config["order"];
        }
        if (!empty($config["limit"])) {
            $sql .= " LIMIT " . $config["limit"];
        }
        $req = $this->db->prepare($sql);
        $req->execute($values);
        //var_dump($req->queryString);
        //var_dump($array);
        if ((isset($config["limit"]) && $config["limit"] == 1) || isset($config["fetch"])) {
            return $req->fetch();
        }
        return $req->fetchAll();
    }

    /**
     * Helper pour créer une requête SQL d'insertion et la lancer
     * @param String $table
     * @param array $columns
     * @return boolean
     */
    public function insert($table, $columns) {
        $sql = "INSERT INTO " . $table . "(";
        $cpt = 1;
        foreach ($columns as $key => $elt) {
            $sql .= $key;
            if ($cpt != count($columns)) {
                $sql .= ", ";
            }
            $cpt++;
        }
        $sql .= ") VALUES(";
        $cpt = 1;
        foreach ($columns as $key => $elt) {
            $sql .= ":" . $key;
            if ($cpt != count($columns)) {
                $sql .= ", ";
            }
            $cpt++;
        }
        $sql .= ")";
        $req = $this->db->prepare($sql);
        /*var_dump($sql);
        var_dump($columns);*/
        return $req->execute($columns);
    }

    /**
     * Convertir un tableau en une liste de questions
     * @param $items
     * @return array
     */
    public function toObjects($items) {
        $objects = array();
        foreach($items as $a) {
            array_push($objects, $this->toObject($a));
        }
        return $objects;
    }

    /**
     * Liste paginée
     * @param $iMaxItems
     * @param $iCurrentPage
     * @param $aParams
     * @return array<Question>
     */
    public function getPaginatedList($iMaxItems, $iCurrentPage, $aParams, $aValues = null) {
        require_once './Model/Pagination.php';
        $pagination = new Pagination();
        $pagination->setPagination($iMaxItems, $iCurrentPage, $aParams, $aValues);
        $pagination->setAData($this->toObjects($pagination->getAData()));
        return $pagination;
    }
}
