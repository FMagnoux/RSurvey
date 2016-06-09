<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 11:31
 */
class Subdivision extends SQL
{
    private $iSubId;
    private $sSubLibel;
    private $bSubActive;
    private $iZoneId;

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
    public function getIZoneId()
    {
        return $this->iZoneId;
    }

    /**
     * @param mixed $iZoneId
     * @return Subdivision
     */
    public function setIZoneId($iZoneId)
    {
        $this->iZoneId = $iZoneId;
        return $this;
    }
}