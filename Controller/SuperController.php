<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 11:59
 */
class SuperController
{
    protected $page;

    function __construct() {

    }

    public function home() {
        $this->page = "commun/index";
        $this->view();
    }

    public function error() {
        $this->page = "commun/404";
        $this->view();
    }

    public function callController($ctrl, $action) {
        require_once("./Controller/" . $ctrl . "Controller.php");
        $ctrl = $ctrl . "Controller";
        $controller = new $ctrl();
        $controller->$action();
    }

    public function view($var = null) {
        if (isset($var)) {
            extract($var);
        }
        require_once './View/commun/default.php';
    }
}