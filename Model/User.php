<?php

/**
 * Created by PhpStorm.
 * User: FlorentMagnoux
 * Date: 09/06/2016
 * Time: 10:38
 */

class User extends SQL implements JsonSerializable

{
    private $iUsrId;
    private $sUsrPseudo;
    private $sUsrMail;
    private $sUsrPassword;
    private $sUsrToken;
    private $bUsrActive;
    private $iRoleId;
    private $sTable = "User";

    private static $active = 1;

    /**
     * @return mixed
     */
    public function getIUsrId()
    {
        return $this->iUsrId;
    }

    /**
     * @param mixed $iUsrId
     * @return $this
     */
    public function setIUsrId($iUsrId)
    {
        $this->iUsrId = $iUsrId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSUsrPseudo()
    {
        return $this->sUsrPseudo;
    }

    /**
     * @param mixed $sUsrPseudo
     * @return $this
     */
    public function setSUsrPseudo($sUsrPseudo)
    {
        $this->sUsrPseudo = $sUsrPseudo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSUsrMail()
    {
        return $this->sUsrMail;
    }

    /**
     * @param mixed $sUsrMail
     * @return $this
     */
    public function setSUsrMail($sUsrMail)
    {
        $this->sUsrMail = $sUsrMail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSUsrPassword()
    {
        return $this->sUsrPassword;
    }

    /**
     * @param mixed $sUsrPassword
     * @return $this
     */
    public function setSUsrPassword($sUsrPassword)
    {
        $this->sUsrPassword = $sUsrPassword;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSUsrToken()
    {
        return $this->sUsrToken;
    }

    /**
     * @param mixed $sUsrToken
     * @return $this
     */
    public function setSUsrToken($sUsrToken)
    {
        $this->sUsrToken = $sUsrToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBUsrActive()
    {
        return $this->bUsrActive;
    }

    /**
     * @param mixed $bUsrActive
     * @return $this
     */
    public function setBUsrActive($bUsrActive)
    {
        $this->bUsrActive = $bUsrActive;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIRoleId()
    {
        return $this->iRoleId;
    }

    /**
     * @param mixed $iRoleId
     * @return $this
     */
    public function setIRoleId($iRoleId)
    {
        $this->iRoleId = $iRoleId;
        return $this;
    }

    /**
     * @param $sUsrEmail
     * @return bool
     */
    public function checkEmail($sUsrEmail){
        $requete = self::$db->prepare('select usr_mail from User where usr_mail = :usr_mail');
        $requete->execute (array(
            ':usr_mail'=>$sUsrEmail,
        ));
        $results = $requete->fetchAll();
        if (empty($results)){
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param $sUsrPseudo
     * @return bool
     */

    public function getUser(){
        $requete = self::$db->prepare('select usr_id , usr_pseudo , usr_mail  from User where usr_id = :usr_id and usr_active = :usr_active');
        $requete->execute (array(
            ':usr_id'=>$this->getIUsrId(),
            ':usr_active'=>self::$active,
        ));
        $results = $requete->fetchAll();
        if (empty($results)){
            return false;
        }
        else {
            foreach ($results as $result){
                $oUser = new User();

                $oUser->setIUsrId($result['usr_id']);
                $oUser->setSUsrPseudo($result['usr_pseudo']);
                $oUser->setSUsrMail($result['usr_mail']);

                return $oUser;
            }
        }
    }

    public function checkPseudo($sUsrPseudo){
        $requete = self::$db->prepare('select usr_pseudo from User where usr_pseudo = :usr_pseudo');
        $requete->execute (array(
            ':usr_pseudo'=>$sUsrPseudo,
        ));
        $results = $requete->fetchAll();
        if (empty($results)){
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function signinUser(){
        $requete = self::$db->prepare('insert into User (usr_pseudo , usr_mail , usr_password, usr_token)values(:usr_pseudo , :usr_mail , :usr_password, :usr_token)') ;
        if(!$requete->execute (array(
            ':usr_pseudo'=>$this->getSUsrPseudo(),
            ':usr_mail'=>$this->getSUsrMail(),
            ':usr_password'=>$this->getSUsrPassword(),
            ':usr_token' => $this->getSUsrToken()
        ))) return false;
        // Récupérer l'id de l'user qui vient d'être ajouté
        $this->iUsrId = parent::select(
            array(
                "columns" => "usr_id",
                "table" => $this->sTable,
                "order" => "usr_id desc",
                "limit" => 1
            )
        )["usr_id"];
        return true;
    }

    public function loginUser(){
        $requete = self::$db->prepare('select usr_id, role_id from User where usr_mail = :usr_mail and usr_password = :usr_password and usr_active = 1');
        $requete->execute (array(
            ':usr_mail'=>$this->getSUsrMail(),
            ':usr_password'=>$this->getSUsrPassword(),
        ));
        $results = $requete->fetch();
        if (empty($results)){
            return false;
        }
        else {
          $this->setIUsrId($results['usr_id']);
          $this->setIRoleId($results['role_id']);
          return true;
        }
    }

    public function updateUser(){
        $requete = self::$db->prepare('update User set usr_mail = :usr_mail , usr_password = :usr_password where usr_id = :usr_id');
        return $requete->execute (array(
            ':usr_mail'=>$this->getSUsrMail(),
            ':usr_password'=>$this->getSUsrPassword(),
            ':usr_id'=>$this->getIUsrId(),
        ));
    }

    public function activateDesactivate($iId, $iActive) {
        $query = self::$db->prepare("UPDATE ".$this->sTable." SET usr_active = :active WHERE usr_id = :id");
        return $query->execute(array(
            "id" => $iId,
            "active" => $iActive
        ));
    }

    /**
     * Changer le token d'un utilisateur en fonction de son adresse mail
     * @param $sToken
     * @param $sMail
     * @return bool
     */
    public function setTokenById($sToken, $iId) {
        $query = self::$db->prepare("UPDATE ".$this->sTable." SET usr_token = :token WHERE usr_id = :mail");
        return $query->execute(array(
            "token" => $sToken,
            "mail" => $iId
        ));
    }

    public function getTokenById($iId) {
        return parent::select(
            array(
                "columns" => "usr_token",
                "table" => $this->sTable,
                "where" => "usr_id = :id",
                "fetch" => true
            ),
            array(
                "id" => $iId
            )
        )["usr_token"];
    }

    /**
     * Mettre à jour le mot de passe dun user
     * @param $iId
     * @param $sPassword
     * @param $sToken
     * @return bool
     */
    public function setPasswordById($iId, $sPassword, $sToken) {
        $query = self::$db->prepare("UPDATE ".$this->sTable." SET usr_token = :token, usr_password = :password WHERE usr_id = :id");
        return $query->execute(array(
            "token" => $sToken,
            "id" => $iId,
            "password" => $sPassword
        ));
    }

    /**
     * Retourner l'id correspond à un mail
     * @return array
     */
    public function getIdByEmail($sMail) {
        return parent::select(
            array(
                "columns" => "usr_id",
                "table" => $this->sTable,
                "where" => "usr_mail = :mail",
                "fetch" => true
          ),
            array(
                "mail" => $sMail
            )
        )["usr_id"];
    }

    /**
     * Retourner l'id correspond à un mail
     * @return array
     */
    public function getEmailById($iId) {
        return parent::select(
            array(
                "columns" => "usr_mail",
                "table" => $this->sTable,
                "where" => "usr_id = :id",
                "fetch" => true
            ),
            array(
                "id" => $iId
            )
        )["usr_mail"];
    }

    /**
     * Retourne un objet User contenant le rôle
     * @param $iId
     * @return User
     */
    public function getUserRoleById($iId) {
         return parent::select(
            array(
                "columns" => "role_id",
                "table" => $this->sTable,
                "where" => "usr_id = :id",
                "fetch" => true
            ),
            array(
                "id" => $iId
            )
        )["role_id"];
    }
    
    private function getPaginatedConfig() {
        return array(
            "columns" => 'usr_id, usr_pseudo, usr_mail, usr_active',
            "table" => $this->sTable,
            "where" => "usr_active = 1 and usr_id !=".$_SESSION['iIdUser']
        );
    }

    /**
     * Liste paginée des users
     * @param $iMaxItems
     * @param $iCurrentPage
     * @return array<User>
     */
    public function getPaginatedUserList($iMaxItems, $iCurrentPage) {
        return parent::getPaginatedList($iMaxItems, $iCurrentPage, $this->getPaginatedConfig());
    }

    public function getPaginatedUserListByPseudo($iMaxItems, $iCurrentPage, $sPseudo) {
        $aConfig = $this->getPaginatedConfig();
        $aConfig["where"] .= " AND usr_pseudo LIKE :pseudo";
        $aValues = array(
            "pseudo" => !empty($sPseudo) ? "%" . $sPseudo . "%" : "%"
        );
        return parent::getPaginatedList($iMaxItems, $iCurrentPage, $aConfig, $aValues);
    }

    /**
     * Convertir un tableau en un objet Question
     * @param $array
     * @return $this
     */
    public function toObject($array) {
        return (new User())
            ->setIUsrId(isset($array["usr_id"]) ? $array["usr_id"] : null)
            ->setSUsrPseudo(isset($array["usr_pseudo"]) ? $array["usr_pseudo"] : null)
            ->setSUsrMail(isset($array["usr_mail"]) ? $array["usr_mail"] : null)
            ->setSUsrPassword(isset($array["usr_password"]) ? $array["usr_password"] : null)
            ->setSUsrToken(isset($array["usr_token"]) ? $array["usr_token"] : null)
            ->setBUsrActive(isset($array["usr_active"]) ? $array["usr_active"] : null)
            ->setIRoleId(isset($array["role_id"]) ? $array["role_id"] : null)
            ;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            'iUsrId' => $this->iUsrId,
            'sUsrPseudo' => $this->sUsrPseudo,
            'sUsrMail' => $this->sUsrMail,
            'sUsrPassword' => $this->sUsrPassword,
            'sUsrToken' => $this->sUsrToken,
            'bUsrActive' => $this->bUsrActive,
            'iRoleId' => $this->iRoleId,
        ];
    }

}
