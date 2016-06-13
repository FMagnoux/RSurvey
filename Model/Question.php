<?php

/**
 * Created by PhpStorm.
 * User: FlorentMagnoux
 * Date: 09/06/2016
 * Time: 11:18
 */
class Question extends SQL implements JsonSerializable
{

    private $iQuestionId;
    private $sQuestionLibel;
    private $dQuestionDate;
    private $bQuestionActive;
    private $bQuestionClose;
    private $oUsrId;
    private $oZoneId;
    private $table = "Question";

    /**
     * @return mixed
     */
    public function getIQuestionId()
    {
        return $this->iQuestionId;
    }

    /**
     * @param mixed $iQuestionId
     * @return $this
     */
    public function setIQuestionId($iQuestionId)
    {
        $this->iQuestionId = $iQuestionId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSQuestionLibel()
    {
        return $this->sQuestionLibel;
    }

    /**
     * @param mixed $sQuestionLibel
     * @return $this
     */
    public function setSQuestionLibel($sQuestionLibel)
    {
        $this->sQuestionLibel = $sQuestionLibel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDQuestionDate()
    {
        return $this->dQuestionDate;
    }

    /**
     * @param mixed $dQuestionDate
     * @return $this
     */
    public function setDQuestionDate($dQuestionDate)
    {
        $this->dQuestionDate = $dQuestionDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBQuestionActive()
    {
        return $this->bQuestionActive;
    }

    /**
     * @param mixed $bQuestionActive
     * @return $this
     */
    public function setBQuestionActive($bQuestionActive)
    {
        $this->bQuestionActive = $bQuestionActive;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBQuestionClose()
    {
        return $this->bQuestionClose;
    }

    /**
     * @param mixed $bQuestionClose
     * @return $this
     */
    public function setBQuestionClose($bQuestionClose)
    {
        $this->bQuestionClose = $bQuestionClose;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getoUsrId()
    {
        return $this->oUsrId;
    }

    /**
     * @param mixed $oUsrId
     * @return $this
     */
    public function setoUsrId($oUsrId)
    {
        $this->oUsrId = $oUsrId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getoZoneId()
    {
        return $this->oZoneId;
    }

    /**
     * @param mixed $oZoneId
     * @return $this
     */
    public function setoZoneId($oZoneId)
    {
        $this->oZoneId = $oZoneId;
        return $this;
    }

    public function createQuestion(){
        $bStatutRequete = false;
        $requete = $this->db->prepare('insert into Question (question_libel , question_date , usr_id , zone_id)values(:question_libel , :question_date , :usr_id , :zone_id)') ;
        if ($requete->execute (array(
            ':question_libel'=>$this->getSQuestionLibel(),
            ':question_date'=>$this->getDQuestionDate()->format('Y-m-d H:i:s'),
            ':usr_id'=>$this->getoUsrId(),
            ':zone_id'=>$this->getoZoneId(),
        ))){
            $bStatutRequete = true;
            return $this->db->lastInsertId();
        }
        else {
            return $bStatutRequete;
        }
    }
    
    public function desactivateQuestion($id) {
        $query = $this->db->prepare("UPDATE ".$this->table." SET question_active = 0 WHERE question_id = :id");
        return $query->execute(array(
           "id" => $id 
        ));
    }

    /**
     * Liste paginée des questions
     * @param $iMaxItems
     * @param $iCurrentPage
     * @return array<Question>
     */
    public function getPaginatedQuestionList($iMaxItems, $iCurrentPage, $iId = null) {
        $values = null;
        $aConfig = array(
            "columns" => '*',
            "table" => $this->table,
        );
        if(!empty($iId)) {
            $aConfig["where"] = "usr_id = :id";
            $values = array("id" => $iId);
        }
        return parent::getPaginatedList($iMaxItems, $iCurrentPage, $aConfig, $values);
    }

    /**
     * Convertir un tableau en un objet Question
     * @param $array
     * @return $this
     */
    public function toObject($array) {
        return (new Question())
            ->setIQuestionId($array["question_id"])
            ->setSQuestionLibel($array["question_libel"])
            ->setDQuestionDate($array["question_date"])
            ->setBQuestionActive($array["question_active"])
            ->setBQuestionClose($array["question_close"])
            ->setoUsrId($array["usr_id"])
            ->setoZoneId($array["zone_id"])
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
            'iQuestionId' => $this->iQuestionId,
            'sQuestionLibel' => $this->sQuestionLibel,
            'dQuestionDate' => $this->dQuestionDate,
            'bQuestionActive' => $this->bQuestionActive,
            'bQuestionClose' => $this->bQuestionClose,
            'oUsrId' => $this->oUsrId,
            'oZoneId' => $this->oZoneId,
        ];
    }
}