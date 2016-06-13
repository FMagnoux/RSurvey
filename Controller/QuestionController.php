<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 12:21
 */
require_once ("../Controller/ChoixController.php");
class QuestionController extends SuperController
{
    private $oEntity;

    const SUCCESS = "success";
    const ERROR = "error";

    const ERROR_EMPTYQUESTION = "Vous n'avez pas renseigné de question.";
    const ERROR_QUESTIONLENGHT = "La question renseignée dépasse les 100 caractères.";
    const ERROR_INTERNAL = "Une erreur interne a été détectée , merci de contacter l'administrateur.";
    const ERROR_EMPTYZONE = "Vous n'avez pas renseigné de zone pour ce sondage.";

    const ERROR_EMPTYCHOIX = "Deux choix sont requis pour créer un sondage";

    const SUCESS_CLOSEQUESTION = "Le sondage est maintenant terminé.";

    public function __construct() {
        parent::__construct();
        require_once "./Model/Question.php";
        $this->oEntity = new Question();
    }

    public function closeQuestion(){
        $this->oEntity->setIQuestionId($_POST['iIdQuestion']);
        $this->oEntity->setBQuestionClose(1);
        if($this->oEntity->closeQuestion()){
            $returnjson = array(self::SUCCESS,self::SUCESS_CLOSEQUESTION);
            return json_encode($returnjson);
        }
        else {
            $returnjson = array(self::ERROR,self::ERROR_INTERNAL);
            return json_encode($returnjson);
        }
    }
    public function createQuestion(){
        if($this->checkQuestion()){
            if(count($_POST['aQuestionChoix']) >= 2){
                $oChoixController = new ChoixController();
                if($oChoixController->checkTabChoix($_POST['aQuestionChoix'])){
                    $this->oEntity->setSQuestionLibel($_POST['sQuestionLibel'])
                        ->setDQuestionDate(new DateTime("NOW"))
                        ->setoUsrId($_SESSION['iIdUser'])
                        ->setoZoneId($_POST['iIdZone']);
                    $bLastQuestion = $this->oEntity->createQuestion();
                    if(!$bLastQuestion){
                        $returnjson = array(self::ERROR,self::ERROR_INTERNAL);
                        return json_encode($returnjson);
                    }
                    else {
                        if($oChoixController->createChoix($_POST['aQuestionChoix'],$bLastQuestion)){
                            return true;
                        }
                        else {
                            return false;
                        }
                    }

                }
                else {
                    return $oChoixController->checkTabChoix($_POST['aQuestionChoix']);
                }
            }
            else {
                $returnjson = array(self::ERROR,self::ERROR_EMPTYCHOIX);
                return json_encode($returnjson);
            }


        }
        else {
            return $this->checkQuestion();
        }


    }

    public function checkQuestion(){
        if(isset($_POST['sQuestionLibel']) && !empty($_POST['sQuestionLibel'])){
            if($this->checkLenQuestion($_POST['sQuestionLibel'])){
                return true;
            }
            else {
                return $this->checkLenQuestion($_POST['sQuestionLibel']);
            }
        }
        else {
            $returnjson = array(self::ERROR,self::ERROR_EMPTYQUESTION);
            return json_encode($returnjson);
        }
    }

    public function checkLenQuestion($sQuestionLibel){
        if(strlen($sQuestionLibel)>100){
            $returnjson = array(self::ERROR,self::ERROR_QUESTIONLENGHT);
            return json_encode($returnjson);
        }
        else {
            return true;
        }
    }

    public function checkZoneId(){
        if(isset($_POST['iIdZone']) && !empty($_POST['iIdZone'])){
            return true;
        }
        else {
            $returnjson = array(self::ERROR,self::ERROR_EMPTYZONE);
            return json_encode($returnjson);
        }
    }

    public function create() {
        $this->page = "user/create";
        $this->view();
    }

    /**
     * Liste de questions paginées
     */
    public function listQuestions() {
        $this->setJsonData();
        echo json_encode($this->oEntity->getPaginatedQuestionList(10, isset($_GET["page"]) ? $_GET["page"] : 1));
    }

    /**
     * Liste des questions d'un utilisateur
     * @return bool
     */
    public function listQuestionsByIdUser() {
        $iId = $this->checkGetId();
        if($iId == 0) return false;
        $this->setJsonData();
        echo json_encode($this->oEntity->getPaginatedQuestionList(10, isset($_GET["page"]) ? $_GET["page"] : 1, $iId));
    }

    /**
     * Désactiver la question d'un user
     * @return bool
     */
    public function desactivateQuestion() {
        $id = $this->checkId();
        if($id == 0) return false;
        return $this->oEntity->desactivateQuestion($id);
        $iId = $this->checkPostId();
        if($iId == 0) return false;
        return $this->oEntity->desactivateQuestion($iId);
    }
}