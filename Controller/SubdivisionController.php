<?php

/**
 * Created by PhpStorm.
 * User: licence
 * Date: 10/06/2016
 * Time: 14:03
 */
class SubdivisionController extends SuperController
{
    private $oEntity;

    public function __construct() {
        parent::__construct();
        require_once "./Model/Subdivision.php";
        require_once './Model/Zone.php';
        $this->oEntity = new Subdivision();
    }

    /**
     * Liste de zones paginées
     */
    public function listSubdivisions() {
        $this->setJsonData();
        echo json_encode($this->oEntity->getPaginatedZoneList(10, isset($_GET["page"]) ? $_GET["page"] : 1));
    }

    public function desactivateSubdivision() {
        $id = $this->checkId();
        if($id == 0) return false;
        return $this->oEntity->activateDesactivate($id, 0);
    }
    
    public function activateSubdivision() {
        $id = $this->checkId();
        if($id == 0) return false;
        return $this->oEntity->activateDesactivate($id, 1);
    }
}