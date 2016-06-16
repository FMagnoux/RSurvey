<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 11:25
 */
class Choix extends SQL implements JsonSerializable
{
    private $iChoixId;
    private $sChoixLibel;
    private $iQuestionId;
    private $bChoixActive;

    private static $bActive = 1;

    /**
     * @return mixed
     */
    public function getBChoixActive()
    {
        return $this->bChoixActive;
    }

    /**
     * @param mixed $bChoixActive
     * @return Choix
     */
    public function setBChoixActive($bChoixActive)
    {
        $this->bChoixActive = $bChoixActive;
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
        return $requete->execute (array(
            ':choix_id'=>$this->getIChoixId(),
            ':choix_active'=>$this->getBChoixActive(),
        ));

    }

    public function getChoixQuestion(){
        $requete = $this->db->prepare('select choix_id , choix_libel from Choix where question_id = :question_id and choix_active = :choix_active') ;
        $requete->execute (array(
            ':question_id'=>$this->getIQuestionId(),
            ':choix_active'=>self::$bActive,
        ));
        $results = $requete->fetchAll();
        if(empty($results)){
            return false;
        }
        else {
            $aChoix = array();
            foreach ($results as $result){
                $oChoix = new Choix();
                $oChoix->setIChoixId($result['choix_id']);
                $oChoix->setSChoixLibel($result['choix_libel']);

                array_push($aChoix,$oChoix);
            }
            return $aChoix;
        }
    }

    public function updateChoix(){
        $requete = $this->db->prepare('update Choix set choix_libel = :choix_libel where choix_id = :choix_id and choix_active = :choix_active') ;
        return $requete->execute (array(
            ':question_id'=>$this->getIQuestionId(),
            ':choix_active'=>self::$bActive,
            ':choix_libel'=>$this->getSChoixLibel(),
        ));
    }

    function jsonSerialize()
    {
        return [
            'iChoixId' => $this->iChoixId,
            'sChoixLibel' => utf8_encode($this->sChoixLibel),
            'iQuestionId' => $this->iQuestionId,
            'bChoixActive' => $this->bChoixActive,
        ];
    }

}