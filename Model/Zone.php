<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 11:34
 */
class Zone extends SQL implements JsonSerializable
{
    private $iZoneId;
    private $sZoneLibel;
    private $bZoneActive;
    private $sTable= "Zone";

    /**
     * @return mixed
     */
    public function getIZoneId()
    {
        return $this->iZoneId;
    }

    /**
     * @param mixed $iZoneId
     * @return Zone
     */
    public function setIZoneId($iZoneId)
    {
        $this->iZoneId = $iZoneId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSZoneLibel()
    {
        return $this->sZoneLibel;
    }

    /**
     * @param mixed $sZoneLibel
     * @return Zone
     */
    public function setSZoneLibel($sZoneLibel)
    {
        $this->sZoneLibel = $sZoneLibel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBZoneActive()
    {
        return $this->bZoneActive;
    }

    /**
     * @param mixed $bZoneActive
     * @return Zone
     */
    public function setBZoneActive($bZoneActive)
    {
        $this->bZoneActive = $bZoneActive;
        return $this;
    }

    /**
     * Liste paginée des zones
     * @param $iMaxItems
     * @param $iCurrentPage
     * @return array<Question>
     */
    public function getPaginatedZoneList($iMaxItems, $iCurrentPage) {
        return parent::getPaginatedList($iMaxItems, $iCurrentPage, array(
            "columns" => '*',
            "table" => $this->sTable
        ));
    }

    /**
     * Convertir un tableau en un objet Question
     * @param $array
     * @return $this
     */
    public function toObject($array) {
        return (new Zone())
            ->setIZoneId(isset($array["zone_id"]) ? $array["zone_id"] : null)
            ->setSZoneLibel(isset($array["zone_libel"]) ? $array["zone_libel"] : null)
            ->setBZoneActive(isset($array["zone_active"]) ? $array["zone_active"] : null)
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
            'iZoneId' => $this->iZoneId,
            'sZoneLibel' => $this->sZoneLibel,
            'bZoneActive' => $this->bZoneActive,
        ];
    }
}