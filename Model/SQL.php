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
            $this->db = new PDO('mysql:host=localhost;dbname=rsurvey;charset=utf8', 'root', '');
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}