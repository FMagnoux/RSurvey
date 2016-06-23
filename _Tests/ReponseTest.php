<?php

/**
 * Created by PhpStorm.
 * User: licence
 * Date: 20/06/2016
 * Time: 15:24
 */
require "../Controller/SuperController.php";
require "../Controller/ReponseController.php";
require "../Model/SQL.php";
chdir("../");

class ReponseTest extends PHPUnit_Framework_TestCase
{
    public function test_answerQuestion() {
        $_POST['iIdChoix'] = 7;
        $_POST['iSubCode'] = 77;
        $reponseController = new ReponseController();
        $this->assertTrue($reponseController->answerQuestion());
        $this->assertFalse($reponseController->answerQuestion());
    }
}