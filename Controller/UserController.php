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

    const SUCCESS = "success";
    const ERROR = "error";

    const ERROR_PSEUDO = "Le pseudo indiqué est déjà utilisé par un autre utilisateur.";
    const ERROR_EMPTYPSEUDO = "Le pseudo n'est pas renseigné.";

    const ERROR_MAIL = "L'adresse email indiquée est déjà utilisée par un autre utilisateur.";
    const ERROR_FILTERMAIL = "L'adresse email indiquée ne respecte pas le bon format d'email.";
    const ERROR_EMPTYMAIL = "L'adresse email n'est pas renseignée.";

    const ERROR_LOGIN = "Adresse email ou pseudo incorrect.";

    const ERROR_CHECKPASSWORD = "Les deux mots de passe ne sont pas identiques.";
    const ERROR_EMPTYPASSWORD = "Les mots de passe ne sont pas renseignés.";

    const SUCCESS_LOGIN = "Vous êtes authentifié.";
    const SUCCESS_SIGNIN = "Vous allez recevoir un email de confirmation pour valider votre compte.";

    const ERROR_INTERNAL = "Une erreur interne a été détectée , merci de contacter l'administrateur.";


    public function __construct() {
        parent::__construct();
        require_once "./Model/User.php";
        $this->oEntity = new User();
    }

    /**
     * @return bool
     */
    public function createUser() {

        if ($this->checkEmail()){
            if ($this->checkPseudo()) {
                if ($this->checkPassword(false)){
                    $this->oEntity->setSUsrPseudo(htmlspecialchars($_POST['sUsrPseudo']))
                        ->setSUsrMail(htmlspecialchars($_POST['sUsrMail']))
                        ->setSUsrPassword($this->cryptPassword(htmlspecialchars($_POST['sUsrPassword'])));
                    if($this->oEntity->signinUser()){
                        $returnjson = array(self::SUCCESS,self::SUCCESS_SIGNIN);
                        return json_encode($returnjson);
                    }
                    else {
                        $returnjson = array(self::ERROR,self::ERROR_INTERNAL);
                        return json_encode($returnjson);
                    }
                }
                else {
                    return $this->checkPassword();
                }
            }
            else {
                return $this->checkPseudo();
            }
        }
        else {
            return $this->checkEmail();
        }
    }

    public function loginUser()
    {
        if ($this->checkPassword()){
            if($this->filterEmail($_POST['sUsrMail'])) {
                $this->oEntity->setSUsrMail($_POST['sUsrMail'])
                    ->setSUsrPassword($this->cryptPassword($_POST['sUsrPassword']));
                if ($this->oEntity->loginUser()){
                    $_SESSION['iIdUser'] = $this->oEntity->getIUsrId();
                    $returnjson = array(self::SUCCESS,self::SUCCESS_LOGIN);
                    return json_encode($returnjson);
                }
                else {
                    $returnjson = array(self::ERROR,self::ERROR_INTERNAL);
                    return json_encode($returnjson);
                }
            }
            else {
                return $this->filterEmail($_POST['sUsrMail']);
            }
        }

        else {
            return $this->checkPassword();
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
                    return $this->oEntity->checkEmail($sUsrMail);
                }
            }
            else {
                return $this->filterEmail($sUsrMail);
            }

        }
        else {
            $returnjson = array(self::ERROR,self::ERROR_EMPTYMAIL);
            return json_encode($returnjson);
        }
    }

    public function filterEmail($sUsrMail){
        if(!empty($sUsrMail)){
            if (filter_var($sUsrMail,FILTER_VALIDATE_EMAIL)){
                return true;
            }
            else {
                $returnjson = array(self::ERROR,self::ERROR_FILTERMAIL);
                return json_encode($returnjson);
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
                $returnjson = array(self::ERROR,self::ERROR_PSEUDO);
                return json_encode($returnjson);
            }

        }
        else {
            $returnjson = array(self::ERROR,self::ERROR_EMPTYPSEUDO);
            return json_encode($returnjson);
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
                $returnjson = array(self::ERROR,self::ERROR_EMPTYPASSWORD);
                return json_encode($returnjson);
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
                    $returnjson = array(self::ERROR,self::ERROR_CHECKPASSWORD);
                    return json_encode($returnjson);
                }
            }
            else {
                $returnjson = array(self::ERROR,self::ERROR_EMPTYPASSWORD);
                return json_encode($returnjson);
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
        $id = $this->checkPostId();
        if($id == 0) return false;
        return $this->oEntity->activateDesactivate($id, 0);
    }

    /**
     * Activer un utilisateur
     * @return bool
     */
    public function activateUser() {
        $id = $this->checkPostId();
        if($id == 0) return false;
        return $this->oEntity->activateDesactivate($id, 1);
    }

}