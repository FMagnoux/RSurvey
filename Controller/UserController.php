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
    private static $lenght = 255;

    const SUCCESS = "success";
    const ERROR = "error";

    const ERROR_PSEUDO = "Le pseudo indiqué est déjà utilisé par un autre utilisateur.";
    const ERROR_EMPTYPSEUDO = "Le pseudo n'est pas renseigné.";
    const ERROR_LENGHTPSEUDO = "Le pseudo renseigné est trop grand.";

    const ERROR_MAIL = "L'adresse email indiquée est déjà utilisée par un autre utilisateur.";
    const ERROR_FILTERMAIL = "L'adresse email indiquée ne respecte pas le bon format d'email.";
    const ERROR_EMPTYMAIL = "L'adresse email n'est pas renseignée.";
    const ERROR_NOTEXISTMAIL = "L'email que vous avez renseigné n'existe pas.";
    const ERROR_LENGHTMAIL = "L'email que vous avez renseigné est trop grande.";
    const ERROR_SENDMAIL = "Votre compte a été créé mais un problème est survenu lors de l'envoi de l'email de confirmation. Veuillez contacter un administrateur.";
    const SUCCESS_MAILSENT = "Suivez les instructions indiqués dans l'e-mail qui vous a été envoyé.";

    const ERROR_LOGIN = "Adresse email ou mot de passe incorrect.";
    const SUCCESS_USERCONFIRMED = "Votre compte a été activé. Connectez-vous pour créer des sondages.";
    const SUCCESS_PASSWORDCHANGED = "Votre mot de passe a été mis à jour.";

    const ERROR_CHECKPASSWORD = "Les deux mots de passe ne sont pas identiques.";
    const ERROR_EMPTYPASSWORD = "Le mot de passe n'est pas correctement renseigné.";

    const SUCCESS_LOGIN = "Vous êtes authentifié.";
    const SUCCESS_SIGNIN = "Vous allez recevoir un email de confirmation pour valider votre compte.";

    const SUCCESS_UPDATE = "Votre profil a bien été mis à jour.";

    const ERROR_INTERNAL = "Une erreur interne a été détectée , merci de contacter l'administrateur.";

    const ERROR_BROKENLINK = "Le lien a expiré.";

    const ERROR_NOTFOUND ="Aucun résultat n'a été trouvé.";

    const ERROR_DESACTIVATE = "L'utilisateur n'a pas été désactivé.";
    const SUCCESS_DESACTIVATE = "L'utilisateur a été désactivé.";

    //private static $sSender = "R Survey";
    //private static $sFrom = "no-reply@r-survey.com";

    public function __construct() {
        parent::__construct();
        require_once "./Model/User.php";
        $this->oEntity = new User();
    }

    public function getUser($iIdUser = null){
        if (isset($_POST['iIdUser']) && !empty($_POST['iIdUser'])){
            $iIdUser = $_POST['iIdUser'];
        }
        $this->oEntity->setIUsrId($iIdUser);
        return $this->oEntity->getUser();
    }

    /**
     * @return bool
     */
    public function createUser() {
        $this->setJsonData();
        if ($this->checkEmail() && !is_string($this->checkEmail())){
            if ($this->checkPseudo() && !is_string($this->checkPseudo())) {
                if ($this->checkPassword(false) && !is_string($this->checkPassword(false))){
                    $this->oEntity->setSUsrPseudo(htmlspecialchars($_POST['sUsrPseudo']))
                        ->setSUsrMail(htmlspecialchars($_POST['sUsrMail']))
                        ->setSUsrPassword($this->cryptPassword(htmlspecialchars($_POST['sUsrPassword'])))
                        ->setSUsrToken($this->generateToken())
                    ;
                    if($this->oEntity->signinUser()){
                        $returnjson = array(self::SUCCESS,self::SUCCESS_SIGNIN);
                        require_once './Model/Mail.php';
                        // Générer le mail
                        $aMail = $this->mailInscription($this->encrypt($this->oEntity->getIUsrId()), $this->oEntity->getSUsrToken());
                        // Envoyer le mail
                        $oMail = new Mail(
                            $aMail["fromName"],
                            $aMail["from"],
                            $this->oEntity->getSUsrMail(),
                            $aMail["subject"],
                            $aMail["message"]);
                        try {
                            $oMail->sendMail();
                        }
                        catch(Exception $e) {
                            echo json_encode(array(self::ERROR, self::ERROR_SENDMAIL));
                            return false;
                        }
                        echo json_encode($returnjson);
                        return true;
                    }
                    else {
                        $returnjson = array(self::ERROR,self::ERROR_INTERNAL);
                        echo json_encode($returnjson);
                        return false;
                    }
                }
                else {
                    echo  $this->checkPassword(false);
                    return false;
                }
            }
            else {
                echo $this->checkPseudo();
                return false;
            }
        }
        else {
            echo $this->checkEmail();
            return false;
        }
    }

    /**
     * Générer le text du mail d'inscription
     * @param $sId
     * @param $sToken
     * @return string
     */
    private function mailInscription($sId, $sToken) {
        $aMail = "";
        require_once "./View/user/mailInscription.php";
        return $aMail;
    }

    /**
     * Confirmer l'user une fois qu'il a cliqué sur le lien dans son mail
     * @return bool
     */
    public function confirmUser() {
        $this->page = "user/confirmUser";
        $id = $this->checkToken();
        if(!$id) {
            $this->view(array(self::ERROR =>self::ERROR_BROKENLINK));
            return false;
        }
        if(!$this->oEntity->activateDesactivate($id, 1)) {
            $this->view(array(self::ERROR =>self::ERROR_INTERNAL));
            return false;
        }
        if(!$this->oEntity->setTokenById($this->generateToken(), $id)) {
            $this->view(array(self::ERROR =>self::ERROR_INTERNAL));
            return false;
        }
        $this->view(array(self::SUCCESS =>self::SUCCESS_USERCONFIRMED));
        return true;
    }

    public function loginUser()
    {
        $this->setJsonData();
        if ($this->checkPassword() && !is_string($this->checkPassword())){
            if($this->filterEmail($_POST['sUsrMail']) && !is_string($this->filterEmail($_POST['sUsrMail']))) {
                $this->oEntity->setSUsrMail($_POST['sUsrMail'])
                    ->setSUsrPassword($this->cryptPassword($_POST['sUsrPassword']));
                if ($this->oEntity->loginUser()){

                    $_SESSION['iIdUser'] = $this->oEntity->getIUsrId();
                    $_SESSION['iIdRole'] = $this->oEntity->getIRoleId();
                    $returnjson = array(self::SUCCESS,self::SUCCESS_LOGIN);
                    echo json_encode($returnjson);
                    return true;
                }
                else {
                    $returnjson = array(self::ERROR,self::ERROR_LOGIN);
                    echo json_encode($returnjson);
                    return false;
                }
            }
            else {
                echo $this->filterEmail($_POST['sUsrMail']);
                return false;
            }
        }
        else {
            echo $this->checkPassword();
            return false;
        }

    }

    /**
     * @return bool
     */
    public function checkEmail(){
        if (isset($_POST['sUsrMail']) && !empty($_POST['sUsrMail'])){
            $sUsrMail = $_POST['sUsrMail'];
            if ($this->filterEmail($sUsrMail) && !is_string($this->filterEmail($sUsrMail))){
                if($this->oEntity->checkEmail($sUsrMail)){
                    return true;
                }
                else {
                    return json_encode(array(self::ERROR, self::ERROR_MAIL));
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
            if(strlen($sUsrMail)>self::$lenght){
                $returnjson = array(self::ERROR,self::ERROR_LENGHTMAIL);
                return json_encode($returnjson);
            }
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
            if(strlen($_POST['sUsrPseudo'])>self::$lenght){
                $returnjson = array(self::ERROR,self::ERROR_LENGHTPSEUDO);
                return json_encode($returnjson);
            }
            $sUsrPseudo = $_POST['sUsrPseudo'];
            if($this->oEntity->checkPseudo(htmlspecialchars($sUsrPseudo))){
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
        if(!$this->isAdmin()) return false;
        $this->page = "admin/listUsers";
        $oPagination = $this->oEntity->getPaginatedUserList(10, isset($_GET["page"]) ? $_GET["page"] : 1);
        if(count($oPagination->getAData()) == 0) {
            $this->page = "admin/error";
            $this->view(array(self::ERROR => self::ERROR_NOTFOUND));
            return false;
        }
        $this->view(array("oPagination" => $oPagination, "sUrlStart" => "./administration-users/page-"));
    }
    
    public function searchUsersByPseudo() {
        if(!$this->isAdmin()) return false;
        if(!empty($_POST)) extract($_POST);
        else if(!empty($_GET)) extract($_GET);
        $this->page = "admin/errorFilterUsers";
        if(empty($sPseudo)) {
            $sPseudo = "";
        }
        $oPagination = $this->oEntity->getPaginatedUserListByPseudo(10, isset($_GET["page"]) ? $_GET["page"] : 1, htmlspecialchars($sPseudo));
        if(count($oPagination->getAData()) == 0) {
            $this->view(array(self::ERROR => self::ERROR_NOTFOUND));
            return false;
        }
        $this->page = "admin/listUsers";
        $this->view(array("oPagination" => $oPagination, "sPseudo" => $sPseudo, "sUrlStart" => "./administration-filtre-users/pseudo:".$sPseudo."/page-"));
    }

    /**
     * Désactiver un utilisateur
     * @return bool
     */
    public function desactivateUser() {
        if(!$this->isAdmin()) return false;
        $iId = $this->checkPostId();
        if($iId == 0 || !$this->oEntity->activateDesactivate($iId, 0)) {
            echo json_encode(array(self::ERROR, self::ERROR_DESACTIVATE));
            return false;
        }
        echo json_encode(array(self::SUCCESS, self::SUCCESS_DESACTIVATE));
        return true;
    }

    /**
     * Activer un utilisateur
     * @return bool
     */
    public function activateUser() {
        if(!$this->isAdmin()) return false;
        $id = $this->checkPostId();
        if($id == 0) return false;
        return $this->oEntity->activateDesactivate($id, 1);
    }

    public function updateUser(){
        if($this->checkEmail() && !is_string($this->checkEmail())){
            if ($this->checkPassword(false) && !is_string($this->checkPassword(false))){
                $this->oEntity
                    ->setSUsrMail(htmlspecialchars($_POST['sUsrMail']))
                    ->setSUsrPassword($this->cryptPassword(htmlspecialchars($_POST['sUsrPassword'])))
                    ->setIUsrId($_SESSION['iIdUser']);
                if ($this->oEntity->updateUser()){
                    $returnjson = array(self::SUCCESS,self::SUCCESS_UPDATE);
                    echo json_encode($returnjson);
                    return true;
                }
                else {
                    $returnjson = array(self::ERROR,self::ERROR_INTERNAL);
                    echo json_encode($returnjson);
                    return false;
                }
            }
            else {
                echo $this->checkPassword(false);
                return false;
            }
        }
        else {
            echo $this->checkEmail();
            return false;
        }
    }

    /**
     * Envoi d'un mail lorsque l'utilisateur oublie son mot de passe
     * @return bool
     */
    public function forgottenPassword() {
        $this->setJsonData();
        if(empty($_POST["sMail"]) || $this->filterEmail($_POST["sMail"]) !== true) {
            echo json_encode(array(self::ERROR, self::ERROR_FILTERMAIL));
            return false;
        }
        // Vérifier que le mail renvoie une id
        $id = $this->oEntity->getIdByEmail($_POST["sMail"]);
        if(empty($id)) {
            echo json_encode(array(self::ERROR, self::ERROR_NOTEXISTMAIL));
            return false;
        }
        // Génrer le token
        $sToken = $this->generateToken();
        if(!$this->oEntity->setTokenById($sToken, $id)) {
            echo json_encode(array(self::ERROR, self::ERROR_INTERNAL));
            return false;
        }
        // Générer le mail
        $aMail = $this->mailForgottenPassword($this->encrypt($id), $sToken);
        // Envoyer le mail
        require_once './Model/Mail.php';
        $mail = new Mail(
            $aMail["fromName"],
            $aMail["from"],
            $_POST["sMail"],
            $aMail["subject"],
            $aMail["message"]);
        try {
            $mail->sendMail();
        }
        catch(Exception $e) {
            echo json_encode(array(self::ERROR, self::ERROR_SENDMAIL));
            return false;
        }
        echo json_encode(array(self::SUCCESS, self::SUCCESS_MAILSENT));
        return true;
    }

    /**
     * Générer le text du mail d'oublie de mot de passe
     * @param $sId
     * @param $sToken
     * @return string
     */
    private function mailForgottenPassword($sId, $sToken) {
        $aMail = "";
        require_once "./View/user/mailForgottenPassword.php";
        return $aMail;
    }

    /**
     * Générer un nouveau mot de passe pour un utilisateur
     * @return bool
     */
    public function generateNewPassword() {
        $this->page = "user/generateNewPassword";
        // Vérifier que tous les champs requis soient renseignés
        if(
            empty($_GET["token"])
            || empty($_GET["id"])
        ) {
            $this->view(array(self::ERROR => self::ERROR_BROKENLINK));
            return false;
        }
        if(empty($_POST["submit"])) {
            return $this->view();
            return false;
        }
        if(
            empty($_POST['sUsrPassword'])
            || empty($_POST['sUsrConfirmPassword'])
            || $_POST['sUsrPassword'] != $_POST['sUsrConfirmPassword']
        )
        {
            $this->view(array(self::ERROR => self::ERROR_CHECKPASSWORD));
            return false;
        }
        $id = $this->checkToken();
        if(!$id) {
            $this->view(array(self::ERROR => self::ERROR_BROKENLINK));
            return false;
        }
        // Changer le mot de passe et le token
        if(!$this->oEntity->setPasswordById($id, $this->cryptPassword(htmlspecialchars($_POST["sUsrPassword"])), $this->generateToken())) {
            $this->view(array(self::ERROR => self::ERROR_INTERNAL));
            return false;
        }
        $this->view(array(self::SUCCESS => self::SUCCESS_PASSWORDCHANGED));
        return true;
    }

    /**
     * Générer un token
     * @return string
     */
    public function generateToken() {
        return md5(uniqid(rand(10, 1000), true));
    }

    /**
     * Vérifier que le token soit bien celui que l'utilisateur a en base de données et que l'id liée au token soit correcte
     * @return bool|int
     */
    public function checkToken(){
        $id = $this->decrypt($_GET["id"]);
        $id = intval($id);
        if($id <= 0) return false;
        // Vérifier que le token en BDD est le même que celui dans l'url
        $token = $this->oEntity->getTokenById($id);
        if(empty($token) || $token != $_GET["token"]) return false;
        return $id;
    }
}
