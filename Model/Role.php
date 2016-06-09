<?php

/**
 * Created by PhpStorm.
 * User: FlorentMagnoux
 * Date: 09/06/2016
 * Time: 11:15
 */
class Role extends SQL
{
    private $iRoleId;
    private $sRoleLibel;

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
     * @return mixed
     */
    public function getSRoleLibel()
    {
        return $this->sRoleLibel;
    }

    /**
     * @param mixed $sRoleLibel
     * @return $this
     */
    public function setSRoleLibel($sRoleLibel)
    {
        $this->sRoleLibel = $sRoleLibel;
        return $this;
    }



}