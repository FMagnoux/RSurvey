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
        $requete = $this->db->prepare('select usr_mail from User where usr_mail = :usr_mail');
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
    public function checkPseudo($sUsrPseudo){
        $requete = $this->db->prepare('select usr_pseudo from User where usr_pseudo = :usr_pseudo');
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
        $requete = $this->db->prepare('insert into User (usr_pseudo , usr_mail , usr_password)values(:usr_pseudo , :usr_mail , :usr_password)') ;
        return $requete->execute (array(
            ':usr_pseudo'=>$this->getSUsrPseudo(),
            ':usr_mail'=>$this->getSUsrMail(),
            ':usr_password'=>$this->getSUsrPassword(),
        ));

    }

    public function loginUser(){
        $requete = $this->db->prepare('select usr_id from User where usr_mail = :usr_mail and usr_password = :usr_password');
        $requete->execute (array(
            ':usr_mail'=>$this->getSUsrMail(),
            ':usr_password'=>$this->getSUsrPassword(),
        ));
        $results = $requete->fetchAll();
        if (empty($results)){
            return false;
        }
        else {
            return true;
        }
    }

    public function updateUser(){
        $requete = $this->db->prepare('update User set usr_mail = :usr_mail , usr_password = :usr_password where usr_id = :usr_id');
        return $requete->execute (array(
            ':usr_mail'=>$this->getSUsrMail(),
            ':usr_password'=>$this->getSUsrPassword(),
            ':usr_id'=>$this->getIUsrId(),
        ));
    }

    public function activateDesactivate($iId, $iActive) {
        $query = $this->db->prepare("UPDATE ".$this->sTable." SET usr_active = :active WHERE usr_id = :id");
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
    public function setTokenById($sToken, $sMail) {
        $query = $this->db->prepare("UPDATE ".$this->sTable." SET usr_token = :token WHERE usr_id = :mail");
        return $query->execute(array(
            "token" => $sToken,
            "mail" => $sMail
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
        $query = $this->db->prepare("UPDATE ".$this->sTable." SET usr_token = :token, usr_password = :password WHERE usr_id = :id");
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
     * Liste paginée des users
     * @param $iMaxItems
     * @param $iCurrentPage
     * @return array<User>
     */
    public function getPaginatedUserList($iMaxItems, $iCurrentPage) {
        return parent::getPaginatedList($iMaxItems, $iCurrentPage, array(
            "columns" => '*',
            "table" => $this->sTable,
            null
        ));
    }

    /**
     * Convertir un tableau en un objet Question
     * @param $array
     * @return $this
     */
    public function toObject($array) {
        return (new User())
            ->setIUsrId($array["usr_id"])
            ->setSUsrPseudo($array["usr_pseudo"])
            ->setSUsrMail($array["usr_mail"])
            ->setSUsrPassword($array["usr_password"])
            ->setSUsrToken($array["usr_token"])
            ->setBUsrActive($array["usr_active"])
            ->setIRoleId($array["role_id"])
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