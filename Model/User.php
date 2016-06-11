<?php

/**
 * Created by PhpStorm.
 * User: FlorentMagnoux
 * Date: 09/06/2016
 * Time: 10:38
 */
require_once ("SQL.php");
class User extends SQL
{
    private $iUsrId;
    private $sUsrPseudo;
    private $sUsrMail;
    private $sUsrPassword;
    private $sUsrToken;
    private $bUsrActive;
    private $iRoleId;

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
    
}