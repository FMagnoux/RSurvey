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
    const ERROR_INTERNAL = "Une erreur interne a été détectée , merci de contacter l'administrateur.";

    const SUCCESS_UPDATE = "Le sondage à été modifié.";

    public function __construct() {
        parent::__construct();
        require_once "./Model/Choix.php";
        $this->oEntity = new Choix();
    }

    public function desactiveChoix($aChoix){
            $this->oEntity->desactiveChoix($aChoix);
    }


    public function updateChoix($aChoix,$iIdQuestion){

        // Recupere les choix du sondage en base
        $this->oEntity->setIQuestionId($iIdQuestion);
        $aResultChoix = $this->oEntity->getChoixQuestion();
        
        // Vérifie l'existence des choix entre la base et les POST
        for($i = 0 ; $i<count($aChoix);$i++) {

            // Si il match
            if(array_key_exists($aChoix[$i]->getIChoixId(),$aResultChoix)){
                require_once "./Controller/ReponseController.php";
                $oReponseController = new ReponseController();

                $this->oEntity->setSChoixLibel($aChoix[$i]->getSChoixLibel());
                $this->oEntity->setIChoixId($aChoix[$i]->getIChoixId());
                if(!$this->oEntity->updateChoix() || !$oReponseController->resetVotes($this->oEntity->getIChoixId())){
                    $returnjson = array(self::ERROR, self::ERROR_INTERNAL);
                    return json_encode($returnjson);
                }
            }
            // Si il ne match pas on crée la ligne en base
            else {
                $this->oEntity = $this->oEntity->setIQuestionId($iIdQuestion)
                ->setSChoixLibel($aChoix[$i]->getSChoixLibel());
                
                if(!$this->oEntity->createChoix()){
                    $returnjson = array(self::ERROR, self::ERROR_INTERNAL);
                    return json_encode($returnjson);
                }
            }
        }
        
        for ($j = 0;$j<count($aResultChoix);$j++){
            if(!array_key_exists($aResultChoix[$i]->getIChoixId(),$aChoix)){
                $this->oEntity->setIChoixId($aResultChoix[$i]->getIChoixId());
                if(!$this->oEntity->desactiveChoix()){
                    $returnjson = array(self::ERROR, self::ERROR_INTERNAL);
                    return json_encode($returnjson);
                }
            }
        }
        $returnjson = array(self::SUCCESS, self::SUCCESS_UPDATE);
        return json_encode($returnjson);
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