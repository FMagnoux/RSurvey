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

    const SUCCESS_UPDATE = "Votre profil a bien été mis à jour.";

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
            $returnjson = array(self::ERROR,self::ERROR_EMPTYMAIL);
            return json_encode($returnjson);
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

    public function updateUser(){
        if($this->checkEmail()){
            if ($this->checkPassword(false)){
                $this->oEntity->setSUsrPseudo(htmlspecialchars($_POST['sUsrPseudo']))
                    ->setSUsrMail(htmlspecialchars($_POST['sUsrMail']))
                    ->setSUsrPassword($this->cryptPassword(htmlspecialchars($_POST['sUsrPassword'])))
                    ->setIUsrId($_SESSION['iIdUser']);
                if ($this->oEntity->updateUser()){
                    $returnjson = array(self::SUCCESS,self::SUCCESS_UPDATE);
                    return json_encode($returnjson);
                }
                else {
                    $returnjson = array(self::ERROR,self::ERROR_INTERNAL);
                    return json_encode($returnjson);
                }
            }
            else {
                $this->checkPassword(false);
            }
        }
        else {
            return $this->checkEmail();
        }
    }

    /**
     * Envoi d'un mail lorsque l'utilisateur oublie son mot de passe
     * @return bool
     */
    public function forgottenPassword() {
        if($this->filterEmail($_POST["sMail"]) !== true) {
            return false;
        }
        // Vérifier que le mail renvoie une id
        $id = $this->oEntity->getIdByEmail($_POST["sMail"]);
        if(empty($id)) return false;
        // Génrer le token
        $sToken = $this->generateToken();
        if(!$this->oEntity->setTokenById($sToken, $id)) return false;
        // Envoyer le mail
        require_once './Model/Mail.php';
        $mail = new Mail(
            "R Survey",
            "no-reply@r-survey.com",
            $_POST["sMail"],
            "Réinitialisation du mot de passe",
            "<p><a href='http://r-survey.com/mot-de-passe-oublie/".$id."/".$sToken."'>Cliquez ici</a> pour réinitialiser votre mot de passe</p>");
        $mail->sendMail();
        return true;
    }

    /**
     * Générer un nouveau mot de passe pour un utilisateur
     * @return bool
     */
    public function generateNewPassword() {
        // Vérifier que tous les champs requis soient renseignés
        if(empty($_POST["submit"]) || empty($_POST['sUsrPassword']) || empty($_POST['sUsrConfirmPassword']) || $_POST['sUsrPassword'] != $_POST['sUsrConfirmPassword'] || empty($_GET["token"]) || empty($_GET["id"])) return false;
        $id = $this->decrypt($_GET["id"]);
        $id = intval($id);
        if($id <= 0) return false;
        // Vérifier que le token en BDD est le même que celui dans l'url
        $token = $this->oEntity->getTokenById($id);
        if(empty($token) || $token != $_GET["token"]) return false;
        // Changer le mot de passe et le token
        return $this->oEntity->setPasswordById($id, $this->cryptPassword(htmlspecialchars($_POST["sUsrPassword"])), $this->generateToken());        
    }

    /**
     * Générer un token
     * @return string
     */
    public function generateToken() {
        return md5(uniqid(rand(10, 1000), true));
    }
}