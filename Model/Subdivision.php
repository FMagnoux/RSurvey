<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 11:31
 */
class Subdivision extends SQL implements JsonSerializable
{
    private $iSubId;
    private $sSubLibel;
    private $bSubActive;
    private $oZoneId;
    private $sTable = "Subdivision";

    /**
     * @return mixed
     */
    public function getISubId()
    {
        return $this->iSubId;
    }

    /**
     * @param mixed $iSubId
     * @return Subdivision
     */
    public function setISubId($iSubId)
    {
        $this->iSubId = $iSubId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSSubLibel()
    {
        return $this->sSubLibel;
    }

    /**
     * @param mixed $sSubLibel
     * @return Subdivision
     */
    public function setSSubLibel($sSubLibel)
    {
        $this->sSubLibel = $sSubLibel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBSubActive()
    {
        return $this->bSubActive;
    }

    /**
     * @param mixed $bSubActive
     * @return Subdivision
     */
    public function setBSubActive($bSubActive)
    {
        $this->bSubActive = $bSubActive;
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
     * @return Subdivision
     */
    public function setoZoneId($oZoneId)
    {
        $this->oZoneId = $oZoneId;
        return $this;
    }

    public function activateDesactivate($iId, $iActive) {
        $query = $this->db->prepare("UPDATE ".$this->sTable." SET sub_active = :active WHERE sub_id = :id");
        return $query->execute(array(
            "id" => $iId,
            "active" => $iActive
        ));
    }

    /**
     * Liste paginée des subdivisions
     * @param $iMaxItems
     * @param $iCurrentPage
     * @return array<Subdivision>
     */
    public function getPaginatedZoneList($iMaxItems, $iCurrentPage) {
        return parent::getPaginatedList($iMaxItems, $iCurrentPage, array(
            "columns" => '*',
            "table" => $this->sTable,
            "join" => array(
                "table" => "Zone",
                "key" => "zone_id",
                "foreignKey" => "zone_id"
            )
        ));
    }

    /**
     * Convertir un tableau en un objet Question
     * @param $array
     * @return $this
     */
    public function toObject($array) {
        require_once "./Model/Zone.php";
        $oZone = new Zone();
        $oZone = $oZone->toObject($array);
        return (new Subdivision())
            ->setISubId(isset($array["sub_id"]) ? $array["sub_id"] : null)
            ->setSSubLibel(isset($array["sub_libel"]) ? $array["sub_libel"] : null)
            ->setBSubActive(isset($array["sub_active"]) ? $array["sub_active"] : null)
            ->setoZoneId($oZone)
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
            'iSubId' => $this->iSubId,
            'sSubLibel' => utf8_encode($this->sSubLibel),
            'bSubActive' => $this->bSubActive,
            'oZoneId' => $this->oZoneId
        ];
    }
}