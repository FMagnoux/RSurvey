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
        if(empty($_SESSION["iIdUser"])) {
            return;
        }

        require_once './Model/User.php';
        $this->oEntity = new User();
        $this->oEntity->setIUsrId($_SESSION["iIdUser"]);
        $this->oEntity->getUserRoleById($_SESSION["iIdUser"]);
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