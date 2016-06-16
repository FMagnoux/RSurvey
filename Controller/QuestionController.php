<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 12:21
 */
class QuestionController extends SuperController
{
    private $oEntity;
    private $iPagination = 10;

    const SUCCESS = "success";
    const ERROR = "error";

    const ERROR_EMPTYQUESTION = "Vous n'avez pas renseigné de question.";
    const ERROR_QUESTIONLENGHT = "La question renseignée dépasse les 100 caractères.";
    const ERROR_INTERNAL = "Une erreur interne a été détectée , merci de contacter l'administrateur.";
    const ERROR_EMPTYZONE = "Vous n'avez pas renseigné de zone pour ce sondage.";

    const ERROR_EMPTYCHOIX = "Deux choix sont requis pour créer un sondage";

    const ERROR_QUESTIONKO = "Aucun résultat n'a été trouvé.";
    const ERROR_DATE = "Les dates spécifiées n'ont pas un bon format.";

    const SUCCESS_CLOSEQUESTION = "Le sondage est maintenant terminé.";
    const SUCCESS_UPDATEQUESTION = "Le sondage a été mis à jour.";

    private static $changeyes = 1;
    private static $nocreate = -1;

    public function __construct() {
        parent::__construct();
        require_once "./Model/Question.php";
        $this->oEntity = new Question();
    }

    public function getNextPreviousQuestion(){

        $oQuestion = $this->oEntity->getNextPreviousQuestion($_POST['next']);

        require_once "./Controller/ChoixController.php";
        require_once "./Controller/ReponseController.php";
        require_once "./Controller/UserController.php";

        $oChoixController = new ChoixController();
        $oReponseController = new ReponseController();

        $oUserController = new UserController();
        $oUser = $oUserController->getUser($oQuestion->getOUsr());
        $aChoix = $oChoixController->getChoixQuestion($oQuestion->getIQuestionId());
        $aReponse = $oReponseController->getReponseQuestion($aChoix);

        $oQuestion->setOUsr($oUser);

        $returnjson = array($oQuestion,$aChoix,$aReponse);
        echo json_encode($returnjson);
        return;

    }

    /**
     * @return bool|string
     */
    public function updateQuestion(){
        require_once "./Controller/ChoixController.php";

        // Vérifie le format de la question
        if($this->checkQuestion()) {
            // Vérifie que la question est changée
            if($this->checkChangeQuestion()){
                // Met à jour la question
                if(!$this->oEntity->changeQuestion()){
                    $returnjson = array(self::ERROR,self::ERROR_INTERNAL);
                    echo json_encode($returnjson);
                }
                $aChoix = array();
                for ($i=0;$i<count($_POST['aChoix']);$i++){
                    $oChoix = new Choix();
                    if(!empty($_POST['aChoix'][$i]['sChoixLibel']) && !empty($_POST['aChoix'][$i]['iIdChoix'])){
                        $oChoix->setSChoixLibel($_POST['aChoix'][$i]['sChoixLibel']);
                        $oChoix->setIChoixId($_POST['aChoix'][$i]['iIdChoix']);

                        array_push($aChoix,$oChoix);
                    }
                    else {
                        $returnjson = array(self::ERROR,self::ERROR_EMPTYCHOIX);
                        return json_encode($returnjson);
                    }
                }
                $oChoixController = new ChoixController();
                return $oChoixController->updateChoix($aChoix,$_POST['iIdQuestion']);
            }
        }
        else {
            return $this->checkQuestion();
        }
    }

    /**
     * @return bool
     */
    public function checkChangeQuestion()
    {
        $Question = $this->oEntity->checkChangeQuestion();
        if($Question == $_POST['sQuestionLibel']){
            return false;
        }
        else {
            return true;
        }
    }

    /**
     * @return string
     */
    public function closeQuestion(){
        $this->oEntity->setIQuestionId($_POST['iIdQuestion']);
        $this->oEntity->setBQuestionClose(1);
        if($this->oEntity->closeQuestion()){
            $returnjson = array(self::SUCCESS,self::SUCCESS_CLOSEQUESTION);
            return json_encode($returnjson);
        }
        else {
            $returnjson = array(self::ERROR,self::ERROR_INTERNAL);
            return json_encode($returnjson);
        }
    }

    /**
     *
     */
    public function showQuestion() {
        $this->page = "user/survey";
        return $this->view();
    }

    /**
     *
     */
    public function getQuestionFull(){
        $this->setJsonData();
        $id = $this->decrypt($_GET['iIdQuestion']);
        $id = intval($id);
        if($id <= 0) {
            $returnjson = array(self::ERROR,self::ERROR_QUESTIONKO);
            echo json_encode($returnjson);
            return false;
        }
        $this->oEntity->setIQuestionId($id);
        $aTabQuestion =  $this->oEntity->getQuestion();
        if(!$aTabQuestion){
            $returnjson = array(self::ERROR,self::ERROR_QUESTIONKO);
            echo json_encode($returnjson);
            return false;
        }

        //return $this->view(array("aTabQuestion" => $aTabQuestion));

        require_once "./Controller/ChoixController.php";
        require_once "./Controller/ReponseController.php";
        require_once "./Controller/UserController.php";

        $oChoixController = new ChoixController();
        $oReponseController = new ReponseController();
        $oUserController = new UserController();

        $this->oEntity->setIQuestionId($id);

        $oQuestion =  $this->oEntity->getQuestion();
        $oUser = $oUserController->getUser($oQuestion->getOUsr());
        $aChoix = $oChoixController->getChoixQuestion($oQuestion->getIQuestionId());
        foreach ($aChoix as $oChoix){
            $oChoix->setAReponse($oReponseController->getReponseQuestion($oChoix->getIChoixId()));
        }

        $oQuestion->setOUsr($oUser);
        $returnjson = array($oQuestion,$aChoix);
        echo json_encode($returnjson);
        return;
    }

    /**
     * @return bool|string
     */
    public function createQuestion(){
        if($this->checkQuestion()){
            if(count($_POST['aQuestionChoix']) >= 2){
                $oChoixController = new ChoixController();
                if($oChoixController->checkTabChoix($_POST['aQuestionChoix'])){

                    $oUser = new User();
                    $oUser->setIUsrId($_SESSION['iIdUser']);

                    $oSub = new Subdivision();
                    $oSub->setISubId($_POST['iIdSub']);

                    $this->oEntity->setSQuestionLibel($_POST['sQuestionLibel'])
                        ->setDQuestionDate(new DateTime("NOW"))
                        ->setOUsr($oUser)
                        ->setOSub($oSub);
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

    /**
     * @return bool|string
     */
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

    /**
     * @param $sQuestionLibel
     * @return bool|string
     */
    public function checkLenQuestion($sQuestionLibel){
        if(strlen($sQuestionLibel)>100){
            $returnjson = array(self::ERROR,self::ERROR_QUESTIONLENGHT);
            return json_encode($returnjson);
        }
        else {
            return true;
        }
    }

    /**
     * @return bool|string
     */
    public function checkZoneId(){
        if(isset($_POST['iIdZone']) && !empty($_POST['iIdZone'])){
            return true;
        }
        else {
            $returnjson = array(self::ERROR,self::ERROR_EMPTYZONE);
            return json_encode($returnjson);
        }
    }

    /**
     *
     */
    public function create() {
        $this->page = "user/create";
        $this->view();
    }

    /**
     * Liste de questions paginées
     */
    public function listQuestions() {
        $this->page = "admin/index";
        $this->view(array("oPagination" =>$this->oEntity->getPaginatedQuestionList($this->iPagination, $this->checkPage())));
    }

    /**
     * Liste des questions d'un utilisateur en fonction de son id
     * @return bool
     */
    public function listQuestionsByIdUser() {
        $this->setJsonData();
        $iId = $this->checkGetId();
        if($iId == 0) return false;
        $this->setJsonData();
        echo json_encode($this->oEntity->getPaginatedQuestionList($this->iPagination, $this->checkPage(), $iId));
    }

    /**
     * Liste des questions d'un utilisateur en fonction de son pseudo
     * @return bool
     */
    public function listQuestionsByPseudoUser() {
        if(empty($_GET["sPseudo"])) {
            echo json_encode(array(self::ERROR, self::ERROR_QUESTIONKO));
            return false;
        }
        $this->setJsonData();
        echo json_encode($this->oEntity->getPaginatedQuestionListByPseudo($this->iPagination, $this->checkPage(), htmlspecialchars($_GET["sPseudo"])));
        return true;
    }

    /**
     * Liste des questions en fonction de leur libellé
     * @return bool
     */
    public function listQuestionsByLibel() {
        if(empty($_GET["sLibel"])) {
            echo json_encode(array(self::ERROR, self::ERROR_QUESTIONKO));
            return false;
        }
        $this->setJsonData();
        echo json_encode($this->oEntity->getPaginatedQuestionListByLibel($this->iPagination, $this->checkPage(), htmlspecialchars($_GET["sLibel"])));
        return true;
    }

    /**
     * Liste des questions en fonction d'une intervalle de date
     * @return bool
     */
    public function listQuestionsByDate() {
        if(empty($_GET["dDateAfter"]) && empty($_GET["dDateBefore"])) {
            echo json_encode(array(self::ERROR, self::ERROR_DATE));
            return false;
        }
        $this->setJsonData();
        // Rechercher les questions dont la date est supérieure à la date précisée
        if(!empty($_GET["dDateAfter"]) && empty($_GET["dDateBefore"]) && $this->checkDate($_GET["dDateAfter"])) {
            echo json_encode($this->oEntity->getPaginatedQuestionListByDate($this->iPagination, $this->checkPage(), htmlspecialchars($_GET["dDateAfter"]), ">="));
        }
        // Rechercher les questions dont la date est inférieure à la date précisée
        else if(empty($_GET["dDateAfter"]) && !empty($_GET["dDateBefore"]) && $this->checkDate($_GET["dDateBefore"])) {
            echo json_encode($this->oEntity->getPaginatedQuestionListByDate($this->iPagination, $this->checkPage(), htmlspecialchars($_GET["dDateBefore"]), "<="));
        }
        // Rechercher les questions entre une intervalle de date
        else if($this->checkDate($_GET["dDateAfter"]) && $this->checkDate($_GET["dDateBefore"])) {
            echo json_encode($this->oEntity->getPaginatedQuestionListByDateInterval($this->iPagination, $this->checkPage(), htmlspecialchars($_GET["dDateAfter"]), htmlspecialchars($_GET["dDateBefore"])));
        }
        else {
            echo json_encode(array(self::ERROR, self::ERROR_DATE));
            return false;
        }
        return true;
    }
    
    private function checkDate($aDate) {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$aDate)) {
            return true;
        }
            return false;
    }

    /**
     * Désactiver la question d'un user
     * @return bool
     */
    public function desactivateQuestion() {
        $iId = $this->checkPostId();
        if($iId == 0) return false;
        return $this->oEntity->desactivateQuestion($iId);
    }
}