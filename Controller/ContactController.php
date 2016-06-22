<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 22/06/2016
 * Time: 10:40
 */
class ContactController extends SuperController
{

    const ERROR = "error";
    const SUCCESS = "success";

    const ERROR_SENDING = "Le mail n'a pas été envoyé. Vérifiez que vous ayez bien remplis les deux champs.";
    const ERROR_MAILFORMAT = "Le format de l'adresse e-mail n'est pas correct.";
    const SUCCESS_SENDING = "Le mail a été envoyé. Merci.";

    public function sendQuestion() {
        if(empty($_POST["sEmail"]) || empty($_POST["sMessage"])) {
            echo json_encode(array(self::ERROR, self::ERROR_SENDING));
            return false;
        }
        if(filter_var($_POST["sEmail"],FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array(self::ERROR, self::ERROR_SENDING));
            return false;
        }
        require_once "./Model/Mail.php";
        $aMail = $this->mailQuestion();
        $aMail["from"] = htmlspecialchars($_POST["sEmail"]);
        $oMail = new Mail(
            $aMail["from"],
            $aMail["from"],
            $aMail["to"],
            $aMail["subject"],
            htmlspecialchars($_POST["sMessage"])
        );
        $oMail->sendMail();
        echo json_encode(array(self::SUCCESS, self::SUCCESS_SENDING));
        return true;
    }
    
    private function mailQuestion() {
        $aMail = "";
        require_once "./View/user/mailQuestion.php";
        return $aMail;
    }
}