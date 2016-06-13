<?php

/**
 * Created by PhpStorm.
 * User: licence
 * Date: 13/06/2016
 * Time: 15:57
 */
class AdminController extends SuperController
{
    private $oEntity;

    function __construct()
    {
        parent::__construct();
        // Vérifier que l'on soit bien connecté avec le bon rôle
        if(empty($_SESSION["iIdUser"]) || !empty($_SESSION["iRoleId"])) return;
        require_once './Model/User.php';
        $this->oEntity = new User();
        $_SESSION["iRoleId"] = $this->oEntity->getUserRoleById($_SESSION["iIdUser"]);
    }

    public function home() {
        $this->page = "admin/index";
        $this->view();
    }
    
    public function view($var = null) {
        if (isset($var)) {
            extract($var);
        }
        require_once './View/admin/default.php';
    }
}