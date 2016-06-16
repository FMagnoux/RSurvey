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
    const ERROR_LOGIN = "L'utilisateur n'est pas authentifié.";

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

    public function checkLogin()
    {
        if(isset($_SESSION['iIdUser']) && !empty($_SESSION['iIdUser'])){
            return true;
        }
        else {
            return false;
        }
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
    
    public function checkPage() {
        return isset($_GET["page"]) ? $_GET["page"] : 1;
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

    /**
     * Insérer du js ou du css dans la page
     * @param $aFiles Tableau contenant la référence des fichiers
     * @param $aFileNames Tableau contenant les noms des fichiers
     * @param $sPre Chaine à mettre avant le fichier
     * @param $sPost Chaine à mettre après le fichier
     */
    public function insertFiles($aFiles, $aFileNames, $sPre, $sPost) {
        if(!empty($aFiles[$this->page])) {
            foreach ($aFiles[$this->page] as $a) {
                echo $sPre . $aFileNames[$a] . $sPost;
            }
        }
    }
}