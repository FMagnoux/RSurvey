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
    private $oUsr;
    private $oSub;
    private $table = "Question";

    private static $active = 1;

    /**
     * @return mixed
     */
    public function getIQuestionId()
    {
        return $this->iQuestionId;
    }

    /**
     * @param mixed $iQuestionId
     * @return Question
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
     * @return Question
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
     * @return Question
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
     * @return Question
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
     * @return Question
     */
    public function setBQuestionClose($bQuestionClose)
    {
        $this->bQuestionClose = $bQuestionClose;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOUsr()
    {
        return $this->oUsr;
    }

    /**
     * @param mixed $oUsr
     * @return Question
     */
    public function setOUsr($oUsr)
    {
        $this->oUsr = $oUsr;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOSub()
    {
        return $this->oSub;
    }

    /**
     * @param mixed $oSub
     * @return Question
     */
    public function setOSub($oSub)
    {
        $this->oSub = $oSub;
        return $this;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param string $table
     * @return Question
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }
    /**
     * @return mixed
     */


    public function closeQuestion(){
        $requete = $this->db->prepare('update Question set question_close = :question_close where question_id = :question_id') ;
        return $requete->execute (array(
            ':question_close'=>$this->getBQuestionClose(),
            ':question_id'=>$this->getIQuestionId(),
        ));
    }

    public function getQuestionFull(){
        $requete = $this->db->prepare('
        select 
         q.question_id ,
         q.question_libel , 
         q.question_date ,
         q.question_close ,
         u.usr_pseudo ,  
         c.choix_id ,
         c.choix_libel ,
         r.reponse_id ,
         r.reponse_votes , 
         r.reponse_subcode ,
         z.zone_id ,
         z.zone_libel , 
         s.sub_libel ,
         s.sub_id
        from 
         Question q 
        inner join Choix c 
          on c.question_id = q.question_id
        inner join Reponse r
          on r.choix_id = c.choix_id
        inner join User u 
          on u.usr_id = q.usr_id
        inner join Subdivision s 
          on s.sub_id = q.sub_id
        inner join Zone z 
          on z.zone_id = s.zone_id
        where 
           q.question_id = :question_id
        and 
           q.question_active = :question_active
         ') ;
        $requete->execute (array(
            ':question_id'=>$this->getIQuestionId(),
            ':question_active'=>self::$active
        ));
        $results = $requete->fetchAll();
        if(empty($results)){
            return false;
        }
        else {
            foreach ($results as $result){
                $oQuestion = new Question();
                require_once './Model/User.php';
                $oUser = new User();
                require_once './Model/Choix.php';
                $oChoix = new Choix();
                require_once './Model/Reponse.php';
                $oReponse = new Reponse();
                require_once './Model/Zone.php';
                $oZone = new Zone();
                require_once './Model/Subdivision.php';
                $oSubdivision = new Subdivision();

                $oUser->setSUsrPseudo($result['usr_pseudo']);

                $oZone->setSZoneLibel($result['zone_libel']);
                $oZone->setIZoneId($result['zone_id']);

                $oSubdivision->setSSubLibel($result['sub_libel']);
                $oSubdivision->setISubId($result['sub_id']);
                $oSubdivision->setoZoneId($oZone);

                $oQuestion->setIQuestionId($result['question_id']);
                $oQuestion->setSQuestionLibel($result['question_libel']);
                $oQuestion->setDQuestionDate(new DateTime($result['question_date']));
                $oQuestion->setBQuestionClose($result['question_close']);
                $oQuestion->setOSub($oSubdivision);
                $oQuestion->setOUsr($oUser);

                $oChoix->setIChoixId($result['choix_id']);
                $oChoix->setSChoixLibel($result['choix_libel']);

                $oReponse->setIReponseId($result['reponse_id']);
                $oReponse->setIReponseVotes($result['reponse_votes']);
                $oReponse->setIReponseSubcode($result['reponse_subcode']);

                $oReponse->setIChoixId($oChoix);
                $oChoix->setIQuestionId($oQuestion);


                array_push($aTabObjectQuestion,$oReponse);


                $aTabObjectQuestion = array($oQuestion,$oChoix,$oReponse);
                return $aTabObjectQuestion;
            }
        }

    }

    public function checkChangeQuestion(){
        $requete = $this->db->prepare('select question_libel from Question where question_id = :question_id') ;
        $requete->execute (array(
            ':question_id'=>$this->getIQuestionId(),
        ));
        $results = $requete->fetchAll();
        if(empty($results)){
            return false;
        }
        else {
            foreach ($results as $result){
                return $result['question_libel'];
            }
        }
    }

    public function createQuestion(){
        $bStatutRequete = false;
        $requete = $this->db->prepare('insert into Question (question_libel , question_date , usr_id , zone_id)values(:question_libel , :question_date , :usr_id , :zone_id)') ;
        if ($requete->execute (array(
            ':question_libel'=>$this->getSQuestionLibel(),
            ':question_date'=>$this->getDQuestionDate()->format('Y-m-d H:i:s'),
            ':usr_id'=>$this->getOUsr()->getIUsrId(),
            ':sub_id'=>$this->getOSub()->getISubId(),
        ))){
            $bStatutRequete = true;
            return $this->db->lastInsertId();
        }
        else {
            return $bStatutRequete;
        }
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
    public function getPaginatedQuestionList($iMaxItems, $iCurrentPage, $iId = null) {
        $values = null;
        $aConfig = array(
            "columns" => '*',
            "table" => $this->table,
        );
        if(!empty($iId)) {
            $aConfig["where"] = "usr_id = :id";
            $values = array("id" => $iId);
        }
        return parent::getPaginatedList($iMaxItems, $iCurrentPage, $aConfig, $values);
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
            ->setoUsr($array["usr_id"])
            ->setoSub($array["sub_id"])
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
            'oUsrId' => $this->oUsrId,
            'oSub' => $this->oSub,
        ];
    }
}