<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 16:32
 */
class Pagination extends SQL implements JsonSerializable
{
    private $iCurrentPage;
    private $iNbPages;
    private $iNbLines;
    private $aData;
    
    /**
     * Compter le nombre de lignes
     * @param String $config
     * @param array $values
     * @return int
     */
    function getiNbLines($config, $values) {
        $config["columns"] = 'COUNT(*) AS nb';
        $config["fetch"] = true;
        return parent::select($config, $values)["nb"];
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
     */
    public function setPagination($max, $page, $config, $values = null) {

        // Contr�ler la page courante
        $this->iCurrentPage = $this->controliCurrentPage($page);

        // Connaitre la position actuelle
        $position = $this->iCurrentPage * $max - $max;

        // Connaitre le nombre de lignes dans la table
        if(empty($config["where"])) {
            $config["where"] = null;
        }
        $this->iNbLines = $this->getiNbLines($config, $values);

        // Extraire les donn�es voulues
        if ($this->iNbLines > $position) {
            $config["limit"] = $position . "," . $max;
            $this->aData = $this->getPaginatedData($config, $values);
        } else {
            $config["limit"] = "0," . $max;
            $this->aData = $this->getPaginatedData($config, $values);
        }

        // Faire en sorte que le résultat soit un tableau contenant les items même dans le cas où il n'y a qu'un item par page
        if($config["limit"] == 1) {
            $this->aData = array($this->aData);
        }

        // Savoir combien il y a de pages
        $this->iNbPages = ceil($this->iNbLines / $max);
    }

    /**
     * Contrôler le nombre envoyé comme page courante
     * @param int $page
     * @return int
     */
    private function controliCurrentPage($page) {
        $page = intval($page);
        if ($page <= 0)
            return 1;
        return $page;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            'iCurrentPage' => $this->iCurrentPage,
            'iNbPages' => $this->iNbPages,
            'iNbLines' => $this->iNbLines,
            'aData' => $this->aData
        ];
    }

    /**
     * @return mixed
     */
    public function getAData()
    {
        return $this->aData;
    }

    /**
     * @param mixed $aData
     */
    public function setAData($aData)
    {
        $this->aData = $aData;
        //return this;
    }

    /**
     * Créer une barre de navigation pour la pagination
     * @param String $url Url de la page courante avec les arguments sauf $page
     * @param String $link Argument dans l'url pour définir la page courante
     * @param type $adj
     */
    public function view($urlStart, $urlEnd, $adj = 3) {
        require_once './View/commun/pagination.php';
    }
}