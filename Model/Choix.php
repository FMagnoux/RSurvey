<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 11:25
 */
class Choix extends SQL
{
    private $iChoixId;
    private $sChoixLibel;
    private $iQuestionId;

    /**
     * @return mixed
     */
    public function getIChoixId()
    {
        return $this->iChoixId;
    }

    /**
     * @param mixed $iChoixId
     * @return $this
     */
    public function setIChoixId($iChoixId)
    {
        $this->iChoixId = $iChoixId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSChoixLibel()
    {
        return $this->sChoixLibel;
    }

    /**
     * @param mixed $sChoixLibel
     * @return $this
     */
    public function setSChoixLibel($sChoixLibel)
    {
        $this->sChoixLibel = $sChoixLibel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIQuestionId()
    {
        return $this->iQuestionId;
    }

    /**
     * @param mixed $iQuestionId
     */
    public function setIQuestionId($iQuestionId)
    {
        $this->iQuestionId = $iQuestionId;
        return $this;
    }

    public function createChoix(){
        $requete = $this->db->prepare('insert into Choix (choix_libel, question_id)values(:choix_libel, :question_id)') ;
        return $requete->execute (array(
            ':choix_libel'=>$this->getSChoixLibel(),
            ':question_id'=>$this->getIQuestionId(),
        ));
    }

    public function desactiveChoix(){
        $requete = $this->db->prepare('update Choix set choix_active = :choix_active where choix_id = :choix_id') ;
        $requete->execute (array(
            ':choix_id'=>$this->getIChoixId(),
        ));

    }


}