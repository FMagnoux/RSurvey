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
}