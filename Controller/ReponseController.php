<?php

/**
 * Created by PhpStorm.
 * User: FlorentMagnoux
 * Date: 14/06/2016
 * Time: 09:24
 */
class ReponseController extends SuperController
{

    private $oEntity;

    const SUCCESS = "success";
    const ERROR = "error";

    const ERROR_INTERNAL = "Une erreur interne a été détectée , merci de contacter l'administrateur.";
    const ERROR_ALREADYVOTED = "Vous avez déjà voté pour ce sondage.";
    const ERROR_CLOSED = "Ce sondage est clos.";
    const SUCCESS_VOTE = "Votre vote a été enregistré.";

    public function __construct() {
        parent::__construct();
        require_once "./Model/Reponse.php";
        $this->oEntity = new Reponse();
    }

    /**
     * Savoir si un utilisateur a déjà voté
     * @return bool
     */
    private function alreadyVoted($iQuestionId) {
        if(empty($_SESSION["votes"])) {
            return false;
        }
        foreach($_SESSION["votes"] as $a) {
            if($a["iIdQuestion"] == $iQuestionId && $a["iSubCode"] == $this->oEntity->getIReponseSubcode()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Se souvenir du vote d'un utilisateur
     */
    private function rememberVote($iQuestionId) {
        if(empty($_SESSION["votes"])) {
            $_SESSION["votes"] = array();
        }
        array_push($_SESSION["votes"], array("iIdQuestion" => $iQuestionId, "iSubCode" => $this->oEntity->getIReponseSubcode()));
    }

    public function answerQuestion(){
        $this->oEntity->setIChoixId(intval($_POST['iIdChoix']));
        $this->oEntity->setIReponseSubcode($_POST['iSubCode']);
        // Récupérer le numéro de la question
        require_once "./Model/Choix.php";
        $oChoix = new Choix();
        $oQuestion = $oChoix->getIQuestionByIChoixId($this->oEntity->getIChoixId());
        // Vérifier que la question ne soit pas close
        if($oQuestion->getBQuestionClose()) {
            $returnjson = array(self::ERROR,self::ERROR_CLOSED);
            echo json_encode($returnjson);
            return false;
        }
        // Vérifier que l'utilisateur n'ait pas déjà répondu au sondage
        if($this->alreadyVoted($oChoix->getIQuestionId())) {
            $returnjson = array(self::ERROR,self::ERROR_ALREADYVOTED);
            echo json_encode($returnjson);
            return false;
        }
        // Incrémenter la réponse si elle existe
        if($this->oEntity->incrementReponse()) {
            $returnjson = array(self::SUCCESS,self::SUCCESS_VOTE);
            echo json_encode($returnjson);
            $this->rememberVote($oChoix->getIQuestionId());
            return true;
        }
        // Sinon, créer la réponse...
        $this->oEntity->setIReponseVotes(1);
        if($this->oEntity->createReponse()){
            $returnjson = array(self::SUCCESS,self::SUCCESS_VOTE);
            echo json_encode($returnjson);
            $this->rememberVote($oChoix->getIQuestionId());
            return true;
        }
        else {
            $returnjson = array(self::ERROR, self::ERROR_INTERNAL);
            echo json_encode($returnjson);
            return false;
        }
    }

    public function resetVotes($iIdchoix){
        $this->oEntity->setIChoixId($iIdchoix);
        return $this->oEntity->resetVotes();
    }

    public function getReponseQuestion($iIdChoix){
            $this->oEntity->setIChoixId($iIdChoix);
            $aReponse = $this->oEntity->getReponseQuestion();
            return $aReponse;
    }
}
