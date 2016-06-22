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

    const ERROR = "error";
    const SUCCESS = "success";
    const ERROR_NOTFOUND = "Aucun résultat n'a été trouvé";
    const ERROR_DESACTIVATE = "La zone n'a pas été désactivée.";
    const SUCCESS_DESACTIVATE = "La zone a été désactivée.";
    const ERROR_ACTIVATE = "La zone n'a pas été activée.";
    const SUCCESS_ACTIVATE = "La zone a été activée.";

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
        if(!$this->isAdmin()) return false;
        $this->page = "admin/listZones";
        $oPagination = $this->oEntity->getPaginatedZoneList(10, isset($_GET["page"]) ? $_GET["page"] : 1);
        if(count($oPagination->getAData()) == 0) {
            $this->page = "admin/error";
            $this->view(array(self::ERROR => self::ERROR_NOTFOUND));
            return false;
        }
        echo $this->view(array("oPagination" => $oPagination));
    }

    public function desactivateSubdivision() {
        if(!$this->isAdmin()) return false;
        $iId = $this->checkPostId();
        if($iId == 0 || !$this->oEntity->activateDesactivate($iId, 0)) {
            echo json_encode(array(self::ERROR, self::ERROR_DESACTIVATE));
            return false;
        }
        echo json_encode(array(self::SUCCESS, self::SUCCESS_DESACTIVATE));
        return true;
    }
    
    public function activateSubdivision() {
        if(!$this->isAdmin()) return false;
        $iId = $this->checkPostId();
        if($iId == 0 || !$this->oEntity->activateDesactivate($iId, 1)) {
            echo json_encode(array(self::ERROR, self::ERROR_ACTIVATE));
            return false;
        }
        echo json_encode(array(self::SUCCESS, self::SUCCESS_ACTIVATE));
        return true;
    }
}