<?php

/**
 * Created by PhpStorm.
 * User: FlorentMagnoux
 * Date: 13/06/2016
 * Time: 11:18
 */
class ChoixController extends SuperController
{
    private $oEntity;

    const ERROR = "error";
    const SUCCESS = "success";

    const ERROR_EMPTYCHOIX = "Un des choix n'est pas renseigné.";
    const ERROR_LENCHOIX = "Un des choix est trop long.";
    const ERROR_INTERNAL = "Une erreur interne a été détectée , merci de <a href=\"./#contact\">contacter</a> l'administrateur.";

    const SUCCESS_UPDATE = "Le sondage à été modifié.";

    public function __construct() {
        parent::__construct();
        require_once "./Model/Choix.php";
        $this->oEntity = new Choix();
    }

    public function desactiveChoix($aChoix){
            $this->oEntity->desactiveChoix($aChoix);
    }
    
    private function browseResultChoix($iSearch, $aResultChoix) {
        foreach($aResultChoix as $key => $a) {
            if($a->getIChoixId() == $iSearch) {
                return $key;
            }
        }
        return -1;
    }
    
    public function updateChoix($aChoix,$iIdQuestion){
        if(count($aChoix) <= 1 || count($aChoix) > 3   ){
            $returnjson = array(self::ERROR, self::ERROR_INTERNAL);
            echo json_encode($returnjson);
            return false;
        }
        
        // Recupere les choix du sondage en base
        $this->oEntity->setIQuestionId($iIdQuestion);
        $aResultChoix = $this->oEntity->getChoixQuestion();
        if(empty($aResultChoix)) {
            $returnjson = array(self::ERROR, self::ERROR_INTERNAL);
            echo json_encode($returnjson);
            return false;
        }
        $aChoixToCreate = array();
        $aChoixMatched = array();
        
        // Vérifie l'existence des choix entre la base et les POST
        for($i = 0 ; $i<count($aChoix);$i++) {

            // Si il match
            $iMatched = $this->browseResultChoix($aChoix[$i]->getIChoixId(), $aResultChoix);
            if($iMatched >= 0){
                // Se souvenir des keys qui sont matchés
                array_push($aChoixMatched, $iMatched);

                require_once "./Controller/ReponseController.php";
                $oReponseController = new ReponseController();

                $this->oEntity->setSChoixLibel($aChoix[$i]->getSChoixLibel());
                $this->oEntity->setIChoixId($aChoix[$i]->getIChoixId());
                if(!$this->oEntity->updateChoix() || !$oReponseController->resetVotes($this->oEntity->getIChoixId())){
                    $returnjson = array(self::ERROR, self::ERROR_INTERNAL);
                    echo json_encode($returnjson);
                    return false;
                }
            }
            // Si il ne match pas on crée la ligne en base
            else {
                $this->oEntity = $this->oEntity->setIQuestionId($iIdQuestion)
                ->setSChoixLibel($aChoix[$i]->getSChoixLibel());

                array_push($aChoixToCreate, $this->oEntity->getSChoixLibel());
                
                if(!$aChoix[$i]->desactiveChoix()) {
                    $returnjson = array(self::ERROR, self::ERROR_INTERNAL);
                    echo json_encode($returnjson);
                    return false;
                }
            }
        }

        $sResult = $this->createChoix($aChoixToCreate, $iIdQuestion);
        if(!empty($sResult) && is_string($sResult)) {
            $returnjson = array(self::ERROR, self::ERROR_INTERNAL);
            echo json_encode($returnjson);
            return false;
        }

        // Si le formulaire en POST a moins d'items que celui en base, ça veut dire que l'utilisateur a supprimé un choix
        if(count($aChoix) < count($aResultChoix)) {
            // Supprimer les keys matchés une à une pour n'avoir que celui le choix en trop
            foreach ($aChoixMatched as $a) {
                unset($aResultChoix[$a]);
            }
            // Désactiver le choix qui reste
            foreach($aResultChoix as $a) {
                if(!$a->desactiveChoix()) {
                    $returnjson = array(self::ERROR, self::ERROR_INTERNAL);
                    echo json_encode($returnjson);
                    return false;
                }
            }
        }
        
        /*for ($j = 0;$j<count($aResultChoix);$j++){
            if(!array_key_exists($aResultChoix[$i]->getIChoixId(),$aChoix)){
                $this->oEntity->setIChoixId($aResultChoix[$i]->getIChoixId());
                if(!$this->oEntity->desactiveChoix()){
                    $returnjson = array(self::ERROR, self::ERROR_INTERNAL);
                    return json_encode($returnjson);
                }
            }
        }*/
        $returnjson = array(self::SUCCESS, self::SUCCESS_UPDATE);
        echo json_encode($returnjson);
        return true;
    }


    public function getChoixQuestion($iIdQuestion){
        $this->oEntity->setIQuestionId($iIdQuestion);
        return $this->oEntity->getChoixQuestion();
    }

    public function createChoix($aQuestionChoix , $iIdQuestion)
    {
        $bRequete = false;
        foreach ($aQuestionChoix as $sQuestionChoix){
            $this->oEntity->setSChoixLibel($sQuestionChoix)
                ->setIQuestionId($iIdQuestion);
            if($this->checkLenChoix($sQuestionChoix)) {

                if (!$this->oEntity->createChoix()) {
                    $returnjson = array(self::ERROR, self::ERROR_INTERNAL);
                    return json_encode($returnjson);

                } else {
                    $bRequete = true;
                }
            }
            else {
                return $this->checkLenChoix($sQuestionChoix);
            }
        }
        return $bRequete;
    }

    public function checkTabChoix($aChoixQuestion){
        $bFlagChoix = false;
        foreach ($aChoixQuestion as $sChoixQuestion){
            if($this->checkChoix($sChoixQuestion)){
               $bFlagChoix = true;
            }
            else {
                return $this->checkChoix($sChoixQuestion);
                break;
            }
        }
        return $bFlagChoix;
    }

    public function checkLenChoix($sChoix){
        if(strlen($sChoix)>30){
            $returnjson = array(self::ERROR,self::ERROR_LENCHOIX);
            return json_encode($returnjson);
        }
        else {
            return true;
        }
    }
    public function checkChoix($sChoix){
        if(isset($sChoix) && !empty($sChoix)){
            return true;
        }
        else {
            $returnjson = array(self::ERROR,self::ERROR_EMPTYCHOIX);
            return json_encode($returnjson);
        }

    }


}