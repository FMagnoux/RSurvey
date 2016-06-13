<?php

/**
 * Created by PhpStorm.
 * User: FlorentMagnoux
 * Date: 09/06/2016
 * Time: 14:18
 */
class UserController extends SuperController
{
    private $oEntity;
    private static $sCleSalage = "=9Y[Ec9i";

    public function __construct() {
        parent::__construct();
        require_once "./Model/User.php";
        $this->oEntity = new User();
    }

    /**
     * @return bool
     */
    public function createUser() {

        if ($this->checkEmail() && $this->checkPseudo() && $this->checkPassword(false)){
            $this->oEntity->setSUsrPseudo($_POST['sUsrPseudo'])
                ->setSUsrMail($_POST['sUsrMail'])
                ->setSUsrPassword($this->cryptPassword($_POST['sUsrPassword']));
            return $this->oEntity->signinUser();
        }
        else {
            return false;
        }

    }

    public function loginUser(){
        if($this->checkPassword() && $this->filterEmail($_POST['sUsrMail'])){
            $this->oEntity->setSUsrMail($_POST['sUsrMail'])
                ->setSUsrPassword($this->cryptPassword($_POST['sUsrPassword']));
            if ($this->oEntity->loginUser()){
                $_SESSION['iIdUser'] = $this->oEntity->getIUsrId();
                return true;
            }
            else {
                return false;
            }

        }
        else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function checkEmail(){
        if (isset($_POST['sUsrMail']) && !empty($_POST['sUsrMail'])){
            $sUsrMail = $_POST['sUsrMail'];
            if ($this->filterEmail($sUsrMail)){
                if($this->oEntity->checkEmail($sUsrMail)){
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }

        }
        else {
            return false;
        }
    }

    public function filterEmail($sUsrMail){
        if(!empty($sUsrMail)){
            if (filter_var($sUsrMail,FILTER_VALIDATE_EMAIL)){
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }

    }

    /**
     * @return bool
     */
    public function checkPseudo(){
        if (isset($_POST['sUsrPseudo']) && !empty($_POST['sUsrPseudo'])){
            $sUsrPseudo = $_POST['sUsrPseudo'];
            if($this->oEntity->checkPseudo($sUsrPseudo)){
                return true;
            }
            else {
                return false;
            }

        }
        else {
            return false;
        }
    }

    /**
     * @param bool $bLogin
     * @return bool
     */
    public function checkPassword($bLogin = true){
        if($bLogin){
            if(isset($_POST['sUsrPassword']) && !empty($_POST['sUsrPassword'])){
                return true;
            }
            else {
                return false;
            }
        }
        else {
            // Vérifie correspondance password et confirm
            if(isset($_POST['sUsrPassword']) && !empty($_POST['sUsrPassword']) && isset($_POST['sUsrConfirmPassword']) && !empty($_POST['sUsrConfirmPassword'])){
                $sUsrPassword = $_POST['sUsrPassword'];
                $sUsrConfirmPassword = $_POST['sUsrConfirmPassword'];
                if($sUsrPassword === $sUsrConfirmPassword){
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
        }

    }

    /**
     * @param $sPassword
     * @return string
     */
    public function cryptPassword($sPassword){
        return sha1($sPassword.self::$sCleSalage);
    }

    /**
     * Liste de users paginés
     */
    public function listUsers() {
        $this->setJsonData();
        echo json_encode($this->oEntity->getPaginatedUserList(10, isset($_GET["page"]) ? $_GET["page"] : 1));
    }

    /**
     * Désactiver un utilisateur
     * @return bool
     */
    public function desactivateUser() {
        $id = $this->checkId();
        if($id == 0) return false;
        return $this->oEntity->activateDesactivate($id, 0);
    }

    /**
     * Activer un utilisateur
     * @return bool
     */
    public function activateUser() {
        $id = $this->checkId();
        if($id == 0) return false;
        return $this->oEntity->activateDesactivate($id, 1);
    }

}