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

    const ERROR_DISCONNECTED = "Vous devez être connecté pour voir cette page.";
    const ERROR_EMPTYQUESTION = "Vous n'avez pas renseigné de question.";
    const ERROR_QUESTIONLENGHT = "La question renseignée dépasse les 100 caractères.";
    const ERROR_INTERNAL = "Une erreur interne a été détectée , merci de <a href=\"./#contact\">contacter</a> l'administrateur.";
    const ERROR_EMPTYZONE = "Vous n'avez pas renseigné de zone pour ce sondage.";

    const ERROR_EMPTYCHOIX = "Deux choix sont requis pour créer un sondage";

    const ERROR_QUESTIONKO = "Aucun résultat n'a été trouvé.";
    const ERROR_DATE = "Les dates spécifiées n'ont pas un bon format.";

    const ERROR_EMPTYSUBID = "L'identifiant de la subdivision n'est pas renseigné.";

    const SUCCESS_CLOSEQUESTION = "Le sondage est maintenant terminé.";
    const SUCCESS_UPDATEQUESTION = "Le sondage a été mis à jour.";
    const SUCCESS_CREATEQUESTION = "Le sondage a été crée.";

    const ERROR_DESACTIVATE = "Le sondage n'a pas été désactivé.";
    const SUCCESS_DESACTIVATE = "Le sondage a été désactivé.";

    private static $changeyes = 1;
    private static $nocreate = -1;

    public function __construct() {
        parent::__construct();
        require_once "./Model/Question.php";
        $this->oEntity = new Question();
    }

    public function getNextPreviousQuestion(){
        $next = $_POST['next'];
        $this->oEntity->setDQuestionDate(new DateTime($_POST['dDate']));

        $operateur = "";
        $fonction = "";

        if($next=="true"){
            $operateur = ">";
            $fonction = "MIN";
        }
        else {
            $operateur = "<";
            $fonction = "MAX";
        }

        $oQuestion = $this->oEntity->getNextPreviousQuestion($operateur,$fonction);

        if(!$oQuestion && $next == "true"){
            $operateur = "<";
            $fonction = "MIN";

        }
        else if (!$oQuestion && $next == "false"){
            $operateur = ">";
            $fonction = "MAX";
        }

        $oQuestion = $this->oEntity->getNextPreviousQuestion($operateur,$fonction);

        require_once "./Controller/ChoixController.php";
        require_once "./Controller/ReponseController.php";
        require_once "./Controller/UserController.php";

        $oChoixController = new ChoixController();
        $oReponseController = new ReponseController();
        $oUserController = new UserController();
        $oUser = $oUserController->getUser($oQuestion->getOUsr());
        $aChoix = $oChoixController->getChoixQuestion($oQuestion->getIQuestionId());
        foreach ($aChoix as $oChoix){
            $oChoix->setAReponse($oReponseController->getReponseQuestion($oChoix->getIChoixId()));
        }

        $oQuestion->setOUsr($oUser);
        $oQuestion->setIQuestionId($this->encrypt($oQuestion->getIQuestionId()));
        $returnjson = array($oQuestion,$aChoix);
        echo json_encode($returnjson);
        return;

    }

    /**
     * @return bool|string
     */
    public function updateQuestion(){
        if(!empty($_POST['aChoix'])) {
            $_POST['aChoix'] = json_decode($_POST['aChoix']);
        }
        else {
            $returnjson = array(self::ERROR, self::ERROR_INTERNAL);
            echo json_encode($returnjson);
            return false;
        }
        //var_dump($_POST['aChoix']);
        if(!empty($_POST['aChoix']->aQuestionChoixValues)) {
            foreach ($_POST['aChoix']->aQuestionChoixValues as $key => $a) {
                $_POST['aChoix']->aQuestionChoixValues[$key] = get_object_vars($a);
            }
            $_POST['aChoix'] = $_POST['aChoix']->aQuestionChoixValues;
        }
        require_once "./Controller/ChoixController.php";
        if(!empty($_POST["iIdQuestion"])) {
            $this->oEntity->setIQuestionId(intval($this->decrypt($_POST["iIdQuestion"])));
            if($this->oEntity->getIQuestionId() <= 0) {
                $returnjson = array(self::ERROR,self::ERROR_INTERNAL);
                echo json_encode($returnjson);
                return false;
            }
        }
        else {
            $returnjson = array(self::ERROR,self::ERROR_INTERNAL);
            echo json_encode($returnjson);
            return false;
        }
        // Vérifie le format de la question
        if($this->checkQuestion() && !is_string($this->checkQuestion())) {
            $this->oEntity->setSQuestionLibel($_POST['sQuestionLibel']);
            // Vérifie que la question est changée
            if($this->checkChangeQuestion()) {
                // Met à jour la question
                if (!$this->oEntity->changeQuestion()) {
                    // Message d'erreur si la requête SQL a échoué
                    $returnjson = array(self::ERROR, self::ERROR_INTERNAL);
                    echo json_encode($returnjson);
                    return false;
                }
            }
            $aChoix = array();
            $iNbChoix = count($_POST['aChoix']);
            if($iNbChoix > 3 || $iNbChoix < 2) {
                $returnjson = array(self::ERROR, self::ERROR_INTERNAL);
                echo json_encode($returnjson);
                return false;
            }
            for ($i=0;$i<count($_POST['aChoix']);$i++){
                require_once "./Model/Choix.php";
                $oChoix = new Choix();
                if(!empty($_POST['aChoix'][$i]['sChoixLibel']) && !empty($_POST['aChoix'][$i]['iIdChoix'])){
                    $oChoix->setSChoixLibel($_POST['aChoix'][$i]['sChoixLibel']);
                    $oChoix->setIChoixId($_POST['aChoix'][$i]['iIdChoix']);
                    array_push($aChoix,$oChoix);
                }
                else {
                    $returnjson = array(self::ERROR,self::ERROR_EMPTYCHOIX);
                    echo json_encode($returnjson);
                    return false;
                }
            }
            $oChoixController = new ChoixController();
            return $oChoixController->updateChoix($aChoix,$this->oEntity->getIQuestionId());
        }
        else {
            echo $this->checkQuestion();
            return false;
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
      $id = $this->decrypt($_POST['iIdQuestion']);
      $id = intval($id);
      if($id <= 0) {
          $returnjson = array(self::ERROR,self::ERROR_QUESTIONKO);
          echo json_encode($returnjson);
          return false;
      }

      $this->oEntity->setIQuestionId($id);
        $this->oEntity->setBQuestionClose(1);
        if($this->oEntity->closeQuestion()){
            $returnjson = array(self::SUCCESS,self::SUCCESS_CLOSEQUESTION);
            echo json_encode($returnjson);
            return;
        }
        else {
            $returnjson = array(self::ERROR,self::ERROR_INTERNAL);
            echo json_encode($returnjson);
            return;
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
        $oQuestion->setIQuestionId($this->encrypt($oQuestion->getIQuestionId()));
        if(!$this->checkLogin()){
            $iIdUser = 0;
        }
        else {
            $iIdUser = $_SESSION['iIdUser'];
        }
        $returnjson = array($oQuestion,$aChoix,$iIdUser);
        echo json_encode($returnjson);
        return;
    }

    /**
     * @return bool|string
     */
    public function createQuestion(){
          if(!$this->checkLogin()){
            $aDataPost = array(
              self::ERROR,
              self::ERROR_LOGIN,
              $_POST['sQuestionLibel'],
              $_POST['aQuestionChoix'],
              $_POST['iIdSub']
            );
            echo json_encode($aDataPost);
            return;
        }
        if($this->checkQuestion() && !is_string($this->checkQuestion())){
            if(count($_POST['aQuestionChoix']) > 1 && count($_POST['aQuestionChoix']) <= 3   ){

                require_once "./Controller/ChoixController.php";
                $oChoixController = new ChoixController();
                if($oChoixController->checkTabChoix($_POST['aQuestionChoix'])){

                  require_once "./Model/User.php";

                    $oUser = new User();
                    $oUser->setIUsrId($_SESSION['iIdUser']);

                    if(!isset($_POST['iIdSub']) || empty($_POST['iIdSub'])){
                        $returnjson = array(self::ERROR,self::ERROR_EMPTYSUBID);
                        echo json_encode($returnjson);
                        return;
                    }
                    require_once "./Model/Subdivision.php";

                    $oSub = new Subdivision();
                    $oSub->setISubId($_POST['iIdSub']);

                    $this->oEntity->setSQuestionLibel($_POST['sQuestionLibel'])
                        ->setDQuestionDate(new DateTime("NOW"))
                        ->setOUsr($oUser)
                        ->setOSub($oSub);
                    $bLastQuestion = $this->oEntity->createQuestion();
                    if(!$bLastQuestion){
                        $returnjson = array(self::ERROR,self::ERROR_INTERNAL);
                        echo json_encode($returnjson);
                        return;
                    }
                    else {
                        if($oChoixController->createChoix($_POST['aQuestionChoix'],$bLastQuestion)){
                          $returnjson = array(self::SUCCESS,self::SUCCESS_CREATEQUESTION , $this->encrypt($bLastQuestion));
                          echo json_encode($returnjson);
                            return;
                        }
                        else {
                          $returnjson = array(self::ERROR,self::ERROR_INTERNAL);
                          echo json_encode($returnjson);
                            return;
                        }
                    }

                }
                else {
                    return $oChoixController->checkTabChoix($_POST['aQuestionChoix']);
                }
            }
            else {
                $returnjson = array(self::ERROR,self::ERROR_EMPTYCHOIX);
                echo json_encode($returnjson);
                return;
            }


        }
        else {
            echo $this->checkQuestion();
            return;
        }


    }

    /**
     * @return bool|string
     */
    public function checkQuestion(){
        if(isset($_POST['sQuestionLibel']) && !empty($_POST['sQuestionLibel'])){
            if($this->checkLenQuestion($_POST['sQuestionLibel']) && !is_string($this->checkLenQuestion($_POST['sQuestionLibel'])) ){
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
        if(!$this->isAdmin()) return false;
        $this->page = "admin/listQuestions";
        $this->view(array("oPagination" => $this->oEntity->getPaginatedQuestionList($this->iPagination, $this->checkPage()), "sUrlStartForm" => "./administration-filtre", "sUrlStartPagination" => "./administration", "sUrlEnd" => ".html"));
    }
    
    public function searchQuestions() {
        $this->page = "user/search";
        $this->helperListQuestionsFilter("search", "search", "admin/errorFilterQuestions");
    }

    public function userListQuestionsFilter() {
        $this->page = "user/listQuestions";
        $this->helperListQuestionsFilter("mes-sondages-filtre", "mes-sondages-filtre", "user/errorFilterQuestions", $_SESSION["iIdUser"]);
    }

    public function listQuestionsFilter() {
        if(!$this->isAdmin()) return false;
        $this->page = "admin/listQuestions";
        $this->helperListQuestionsFilter("administration-filtre", "administration-filtre", "admin/errorFilterQuestions");
    }

    /**
     * Faire une recherche sur les questions
     * @return bool
     */
    private function helperListQuestionsFilter($sUrlStartForm, $sUrlStartPagination, $sError, $iIdUser = null) {
        if(!empty($_POST)) extract($_POST);
        else if(!empty($_GET)) extract($_GET);

        if(empty($sPseudo)) {
            $sPseudo = "";
        }
        else {
            $sPseudo = htmlspecialchars($sPseudo);
        }
        if(empty($sLibel)) {
            $sLibel = "";
        }
        else {
            $sLibel = htmlspecialchars($sLibel);
        }
        if(!empty($dDateAfter) && $this->checkDate($dDateAfter)) {
            $dDateAfter = htmlspecialchars($dDateAfter);
        }
        else {
            $dDateAfter = "";
        }
        if(!empty($dDateBefore)&& $this->checkDate($dDateBefore)) {
            $dDateBefore = htmlspecialchars($dDateBefore);
        }
        else {
            $dDateBefore = "";
        }

        $oPagination = $this->oEntity->getPaginatedFilteredQuestionList($this->iPagination, $this->checkPage(), $sPseudo, $sLibel, $dDateAfter, $dDateBefore, $iIdUser);
        if(count($oPagination->getAData()) == 0) {
            $this->page = $sError;
            $this->view(
                array(
                    self::ERROR => self::ERROR_QUESTIONKO,
                    "sPseudo" => $sPseudo,
                    "sLibel" => $sLibel,
                    "dDateAfer" => $dDateAfter,
                    "dDateBefore" => $dDateBefore,
                    "sUrlStartPagination" => "./".$sUrlStartPagination."/pseudo:".$sPseudo."/libel:".$sLibel."/dateAfter:".$dDateAfter."/dateBefore:".$dDateBefore."",
                    "sUrlStartForm" => $sUrlStartForm
                )
            );
            return false;
        }
        $aConfig = array(
            "oPagination" => $oPagination,
            "sPseudo" => $sPseudo,
            "sLibel" => $sLibel,
            "dDateAfer" => $dDateAfter,
            "dDateBefore" => $dDateBefore,
            "sUrlStartPagination" => "./".$sUrlStartPagination."/pseudo:".$sPseudo."/libel:".$sLibel."/dateAfter:".$dDateAfter."/dateBefore:".$dDateBefore."",
            "sUrlStartForm" => $sUrlStartForm
        );
        if(!empty($iIdUser)) {
            $aConfig["sUrlStartPagination"] = "./".$sUrlStartPagination."/libel:".$sLibel."/dateAfter:".$dDateAfter."/dateBefore:".$dDateBefore."";
            $aConfig["sUrlStartForm"] = $sUrlStartForm;
        }
        $this->view($aConfig);
        return true;
    }

    public function adminListQuestionsByIdUser() {
        $this->page = "admin/error";
        if(!$this->isAdmin()) {
            return false;
        }
        $iId = $this->checkGetId();
        if($iId == 0) {
            return null;
        }
        $oPagination = $this->listQuestionsByIdUser($iId);
        if(empty($oPagination)) {
            return $this->view(array(self::ERROR => self::ERROR_QUESTIONKO));
        }
        $this->page = "admin/listQuestions";
        return $this->view(array("oPagination" => $oPagination, "sUrlStartForm" => "./administration-filtre", "sUrlStartPagination" => "./administration/".$_GET["id"], "sUrlEnd" => ".html"));
    }

    public function userListQuestionsByIdUser() {
        $this->page = "user/error";
        if(empty($_SESSION["iIdUser"])) {
            return $this->view(array(self::ERROR => self::ERROR_DISCONNECTED));
        }
        $oPagination = $this->listQuestionsByIdUser($_SESSION["iIdUser"]);
        if(empty($oPagination)) {
            return $this->view(array(self::ERROR => self::ERROR_QUESTIONKO));
        }
        $this->page = "user/listQuestions";
        return $this->view(array("oPagination" => $oPagination, "sUrlStartForm" => "./mes-sondages", "sUrlStartPagination" => "./mes-sondages"));
    }

    /**
     * Liste des questions d'un utilisateur en fonction de son id
     * @return bool
     */
    private function listQuestionsByIdUser($iId) {
        $oPagination = $this->oEntity->getPaginatedQuestionList($this->iPagination, $this->checkPage(), $iId);
        if(count($oPagination->getAData()) == 0) {
            return null;
        }
        return $oPagination;
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
        if(!$this->isAdmin()) return false;
        $iId = $this->checkPostId();
        if($iId == 0 || !$this->oEntity->desactivateQuestion($iId)) {
            echo json_encode(array(self::ERROR, self::ERROR_DESACTIVATE));
            return false;
        }
        echo json_encode(array(self::SUCCESS, self::SUCCESS_DESACTIVATE));
        return true;
    }
}
