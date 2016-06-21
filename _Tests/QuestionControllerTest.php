<?php

/**
 * Created by PhpStorm.
 * User: licence
 * Date: 10/06/2016
 * Time: 11:10
 */

require "../Controller/SuperController.php";
require "../Controller/QuestionController.php";
require "../Model/SQL.php";
chdir("../");

class QuestionControllerTest extends PHPUnit_Framework_TestCase
{
    public function test_desactivateQuestion()
    {
        $_POST["id"] = 1;
        $questionController = new QuestionController();
        $questionController->desactivateQuestion();
        $this->assertTrue($questionController->desactivateQuestion());
    }

    public function test_createQuestion()
    {
        $_POST['aQuestionChoix'] = array("choix1", "choix2");
        $_POST['sQuestionLibel'] = "Pain au chocolat ou chocolatine ?";
        $_POST['iIdZone'] = 1;
        $_SESSION['iIdUser'] = 1;

        $oQuestionController = new QuestionController();
        $this->assertTrue($oQuestionController->createQuestion());
    }
    public function test_listQuestionsByIdUser()
    {
        $_GET["id"] = 2;
        $questionController = new QuestionController();
        //$questionController->listQuestionsByIdUser();
    }
    
    public function test_updateQuestion() {
        $_POST['iIdQuestion'] = "610b6bad1a67c201";
        $_POST['aChoix'] = array(
            array(
                "iIdChoix" => 5,
                "sChoixLibel" => "Trump"
            ),
            array(
                "iIdChoix" => 6,
                "sChoixLibel" => "Clinton"
            ),
            array(
                "iIdChoix" => -1,
                "sChoixLibel" => "Trol"
            )
            /*array(
                "iIdChoix" => 32,
                "sChoixLibel" => "Merde"
            )*/
        );
        $_POST['sQuestionLibel'] = "Update question 5 ?";
        $_POST['iIdZone'] = 1;
        $_SESSION['iIdUser'] = 1;

        $oQuestionController = new QuestionController();
        $this->assertTrue($oQuestionController->updateQuestion());
    }
}

