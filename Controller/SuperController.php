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
    const URLKEY = "9wdg22i2WU2tCButPLHN736aK68vgDv5";

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
    
    public function setJsonData() {
        header('Content-Type: application/json; charset=utf-8');
    }
    
    public function checkPostId() {
        if(empty($_POST["id"])) {
            return 0;
        }
        return $this->checkId($_POST["id"]);
    }
    
    public function checkId($id) {
        $id = intval($id);
        if($id > 0) return $id;
        return 0;
    }

    public function checkGetId() {
        if(empty($_GET["id"])) {
            return 0;
        }
        return $this->checkId($_GET["id"]);
    }

    /**
     * Chiffrer une chaine de caractères
     * @param $text
     * @return string
     */
    function encrypt($sText)
    {
        return urlencode(trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, self::URLKEY, $sText, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND)))));
    }

    /**
     * Déchiffrer une chaine de caractères
     * @param $text
     * @return string
     */
    function decrypt($sText)
    {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, self::URLKEY, base64_decode(urldecode($sText)), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }
}