<?php

/**
 * Created by PhpStorm.
 * User: FlorentMagnoux
 * Date: 09/06/2016
 * Time: 14:18
 */
require_once ("../Controller/SuperController.php");
class UserController extends SuperController
{
    private $oEntity;
    private static $sCleSalage = "=9Y[Ec9i";

    public function __construct() {
        parent::__construct();
        require_once "../Model/User.php";
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

    /**
     * @return bool
     */
    public function checkEmail(){
        if (isset($_POST['sUsrMail'])){
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
        if (filter_var($sUsrMail,FILTER_VALIDATE_EMAIL)){
            return true;
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

}