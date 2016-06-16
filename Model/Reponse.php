<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 11:29
 */
class Reponse extends SQL implements JsonSerializable
{
    private $iReponseId;
    private $iReponseVotes;
    private $iReponseSubcode;
    private $iChoixId;

    private static $iResetVotes = 0;

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

    public function createReponse(){
        $requete = $this->db->prepare('insert into Reponse (reponse_votes, reponse_subcode, choix_id)values(:reponse_votes, :reponse_subcode, :choix_id)') ;
        return $requete->execute (array(
            ':reponse_votes'=>$this->getIReponseVotes(),
            ':reponse_subcode'=>$this->getIReponseSubcode(),
            ':choix_id'=>$this->getIChoixId(),
        ));
    }

    public function updateReponse(){
        $requete = $this->db->prepare('update Reponse set reponse_votes = :reponse_votes where reponse_id = :reponse_votes and reponse_subcode = :reponse_subcode') ;
        return $requete->execute (array(
            ':reponse_votes'=>$this->getIReponseVotes(),
            ':reponse_subcode'=>$this->getIReponseSubcode(),
            ':choix_id'=>$this->getIChoixId(),
        ));
    }

    public function getReponse(){
        $requete = $this->db->prepare('select reponse_id from Reponse where reponse_subcode = :reponse_subcode and choix_id = :choix_id') ;
        $requete->execute (array(
            ':reponse_subcode'=>$this->getIReponseSubcode(),
            ':choix_id'=>$this->getIChoixId(),
        ));
        $results = $requete->fetchAll();
        if (empty($results)){
            return 0;
        }
        else {
            foreach ($results as $result) {
                return $result['reponse_id'];
            }
        }
    }

    public function getLastVotes(){
        $requete = $this->db->prepare('select reponse_votes from Reponse where reponse_subcode = :reponse_subcode and choix_id = :choix_id') ;
        $requete->execute (array(
            ':reponse_subcode'=>$this->getIReponseSubcode(),
            ':choix_id'=>$this->getIChoixId(),
        ));
        $results = $requete->fetchAll();
        foreach ($results as $result) {
            return $result['reponse_votes'];
        }
    }

    public function getReponseQuestion(){
        $requete = $this->db->prepare('select reponse_id , reponse_votes , reponse_subcode , choix_id from Reponse where choix_id = :choix_id') ;
        $requete->execute (array(
            ':choix_id'=>$this->getIChoixId(),
        ));
        $results = $requete->fetchAll();
        if (empty($results)){
            return false;
        }
        else {
            $aReponse = array();
            foreach ($results as $result) {
                $oReponse = new Reponse();
                $oReponse->setIReponseId($result['reponse_id']);
                $oReponse->setIReponseVotes($result['reponse_votes']);
                $oReponse->setIReponseSubcode($result['reponse_subcode']);
                $oReponse->setIChoixId($result['choix_id']);

                array_push($aReponse,$oReponse);
            }
            return $aReponse;
        }
    }

    public function resetVotes(){
        $requete = $this->db->prepare('update Reponse set reponse_votes = :reponse_votes where choix_id = :choix_id') ;
        $requete->execute (array(
            ':choix_id'=>$this->getIChoixId(),
            ':reponse_votes'=>self::$iResetVotes,
        ));
    }

    function jsonSerialize()
    {
        return [
            'iReponseId' => $this->iReponseId,
            'iReponseVotes' => $this->iReponseVotes,
            'iReponseSubcode' => $this->iReponseSubcode,
            'iChoixId' => $this->iChoixId,
        ];
    }
}