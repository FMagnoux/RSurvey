<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 10/06/2016
 * Time: 14:59
 */
require "../Controller/SuperController.php";
require "../Controller/SubdivisionController.php";
require "../Model/SQL.php";
chdir("../");

class QuestionControllerTest extends PHPUnit_Framework_TestCase
{
    public function test_desactivateSubdivision() {
        $_POST["id"] = 1;
        $controller = new SubdivisionController();
        $this->assertTrue($controller->desactivateSubdivision());
    }

    public function test_activateSubdivision() {
        $_POST["id"] = 1;
        $controller = new SubdivisionController();
        $this->assertTrue($controller->activateSubdivision());
    }
}
