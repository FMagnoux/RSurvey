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
    public function test_desactivateQuestion() {
        $_POST["id"] = 1;
        $questionController = new QuestionController();
        $questionController->desactivateQuestion();
        $this->assertTrue($questionController->desactivateQuestion());
    }

    public function test_listQuestionsByIdUser() {
        $_GET["id"] = 2;
        $questionController = new QuestionController();
        $questionController->listQuestionsByIdUser();
    }
}
