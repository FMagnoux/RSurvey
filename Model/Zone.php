<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 11:34
 */
class Zone extends SQL
{
    private $iZoneId;
    private $sZoneLibel;
    private $bZoneActive;

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
}