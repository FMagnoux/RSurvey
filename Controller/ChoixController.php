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

    const ERROR_EMPTYCHOIX = "Un des choix n'est pas renseigné.";
    const ERROR_LENCHOIX = "Un des choix est trop long.";
    const ERROR_INTERNAL = "Une erreur interne a été détectée , merci de contacter l'administrateur.";


    public function __construct() {
        parent::__construct();
        require_once "./Model/Choix.php";
        $this->oEntity = new Choix();
    }

    public function createChoix($aQuestionChoix , $iIdQuestion)
    {
        $bRequete = false;
        foreach ($aQuestionChoix as $sQuestionChoix){
            $this->oEntity->setSChoixLibel($sQuestionChoix)
                ->setIQuestionId($iIdQuestion);
            if(!$this->oEntity->createChoix()){
                $returnjson = array(self::ERROR,self::ERROR_INTERNAL);
                return json_encode($returnjson);
            }
            else {
                $bRequete = true;
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