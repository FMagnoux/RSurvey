<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 16:32
 */
class Pagination extends SQL
{
    private $currentPage;
    private $nbPages;
    private $nbLines;
    
    /**
     * Compter le nombre de lignes
     * @param String $table
     * @param String $where
     * @param array $values
     * @return int
     */
    function getNbLines($table, $where, $values) {
        return parent::select(array(
            "columns" => 'COUNT(*) AS nb',
            "table" => $table,
            "where" => $where,
            "fetch" => true
        ), $values)["nb"];
    }

    /**
     * Récupérer les lignes de la table en fonction de la page courante
     * @param array $config
     * @param array $values
     * @return array
     */
    function getPaginatedData($config, $values) {
        return parent::select($config, $values);
    }

    /**
     * Obtenir les données de pagination
     * @param int $max Nombre de lignes par page
     * @param int $page Page courante
     * @param type $config Configuration de la requête SQL
     * @param type $values Valeur des conditions de la requête SQL
     * @return array Données paginées
     */
    public function getData($max, $page, $config, $values = null) {

        // Contr�ler la page courante
        $this->currentPage = $this->controlCurrentPage($page);

        // Connaitre la position actuelle
        $position = $this->currentPage * $max - $max;

        // Connaitre le nombre de lignes dans la table
        $this->nbLines = $this->getNbLines($config["table"], $config["where"], $values);

        // Extraire les donn�es voulues
        if ($this->nbLines >= $position) {
            $config["limit"] = $position . "," . $max;
            $data = $this->getPaginatedData($config, $values);
        } else {
            //$data = $this->getPaginatedData($select, $table, $where, 0, $max);
            $config["limit"] = "0," . $max;
            $data = $this->getPaginatedData($config, $values);
        }

        // Savoir combien il y a de pages
        $this->nbPages = ceil($this->nbLines / $max);

        return $data;
    }

    /**
     * Contrôler le nombre envoyé comme page courante
     * @param int $page
     * @return int
     */
    private function controlCurrentPage($page) {
        $page = intval($page);
        if ($page <= 0)
            return 1;
        return $page;
    }
}