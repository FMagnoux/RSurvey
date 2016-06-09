<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 11:29
 */
class Reponse extends SQL
{
    private $iReponseId;
    private $iReponseVotes;
    private $iReponseSubcode;
    private $iChoixId;

    /**
     * @return mixed
     */
    public function getIReponseId()
    {
        return $this->iReponseId;
    }

    /**
     * @param mixed $iReponseId
     * @return Reponse
     */
    public function setIReponseId($iReponseId)
    {
        $this->iReponseId = $iReponseId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIReponseVotes()
    {
        return $this->iReponseVotes;
    }

    /**
     * @param mixed $iReponseVotes
     * @return Reponse
     */
    public function setIReponseVotes($iReponseVotes)
    {
        $this->iReponseVotes = $iReponseVotes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIReponseSubcode()
    {
        return $this->iReponseSubcode;
    }

    /**
     * @param mixed $iReponseSubcode
     * @return Reponse
     */
    public function setIReponseSubcode($iReponseSubcode)
    {
        $this->iReponseSubcode = $iReponseSubcode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIChoixId()
    {
        return $this->iChoixId;
    }

    /**
     * @param mixed $iChoixId
     * @return Reponse
     */
    public function setIChoixId($iChoixId)
    {
        $this->iChoixId = $iChoixId;
        return $this;
    }
}