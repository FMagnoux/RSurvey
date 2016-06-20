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
    const SUCCESS_VOTE = "Votre vote a été enregistré.";

    public function __construct() {
        parent::__construct();
        require_once "./Model/Reponse.php";
        $this->oEntity = new Reponse();
    }

    public function answerQuestion(){
        $this->oEntity->setIChoixId($_POST['iIdChoix']);
        $this->oEntity->setIReponseSubcode($_POST['iSubCode']);
        if($this->oEntity->incrementReponse()) {
            $returnjson = array(self::SUCCESS,self::SUCCESS_VOTE);
            echo json_encode($returnjson);
            return true;
        }
        $this->oEntity->setIReponseVotes(1);
        if($this->oEntity->createReponse()){
            $returnjson = array(self::SUCCESS,self::SUCCESS_VOTE);
            echo json_encode($returnjson);
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
