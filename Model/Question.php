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
    private $iUsrId;
    private $iZoneId;
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
    public function getIZoneId()
    {
        return $this->iZoneId;
    }

    /**
     * @param mixed $iZoneId
     * @return $this
     */
    public function setIZoneId($iZoneId)
    {
        $this->iZoneId = $iZoneId;
        return $this;
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
    public function getPaginatedList($iMaxItems, $iCurrentPage) {
        require_once './Model/Pagination.php';
        $pagination = new Pagination();
        $pagination->setPagination($iMaxItems, $iCurrentPage, array(
            "columns" => '*',
            "table" => $this->table,
            null
        ));
        $pagination->setAData($this->toObjects($pagination->getAData()));
        return $pagination;
    }

    /**
     * Convertir un tableau en une liste de questions
     * @param $items
     * @return array
     */
    public function toObjects($items) {
        $objects = array();
        foreach($items as $a) {
            array_push($objects, $this->toObject($a));
        }
        return $objects;
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
            ->setIUsrId($array["usr_id"])
            ->setIZoneId($array["zone_id"])
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
            'iUsrId' => $this->iUsrId,
            'iZoneId' => $this->iZoneId,
        ];
    }
}