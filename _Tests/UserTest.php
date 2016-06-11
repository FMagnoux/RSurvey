<?php

/**
 * Created by PhpStorm.
 * User: FlorentMagnoux
 * Date: 09/06/2016
 * Time: 15:10
 */
require_once ("../Controller/UserController.php");
require_once ("../Controller/SuperController.php");

class UserTest extends PHPUnit_Framework_TestCase
{
    public function test_filterEmailController()
    {
        $userController = new UserController();

        $this->assertTrue($userController->filterEmail('TESTRFD@gmail.com'));
        $this->assertFalse($userController->filterEmail(''));
    }


    public function test_checkEmailController()
    {
        $userController = new UserController();
        $_POST['sUsrMail'] = 'TESTRFD@gmail.com';

        //$this->assertFalse($userController->checkEmail());


        $_POST['sUsrMail'] = 'FloDavRomUPMC@gmail.com';

        $this->assertTrue($userController->checkEmail());
    }

    public function test_createUser(){

        // Avec tout les champs
        $_POST['sUsrMail'] = 'TESTRFD@gmail.com';
        $_POST['sUsrPseudo'] = "Florent";
        $_POST['sUsrPassword'] = "azerty";
        $_POST['sUsrConfirmPassword'] = "azerty";

        $userController = new UserController();

        $this->assertTrue($userController->createUser());

        // Sans Pseudo
        $_POST['sUsrMail'] = 'abcd@gmail.com';
        $_POST['sUsrPseudo'] = "";
        $_POST['sUsrPassword'] = "azerty";
        $_POST['sUsrConfirmPassword'] = "azerty";

        $userController = new UserController();

        $this->assertFalse($userController->createUser());

        // Sans Email
        $_POST['sUsrMail'] = '';
        $_POST['sUsrPseudo'] = "Florent";
        $_POST['sUsrPassword'] = "azerty";
        $_POST['sUsrConfirmPassword'] = "azerty";

        $userController = new UserController();

        $this->assertFalse($userController->createUser());

        // Sans Mot de passe

        $_POST['sUsrMail'] = 'TESTRFD@gmail.com';
        $_POST['sUsrPseudo'] = "Florent";
        $_POST['sUsrPassword'] = "";
        $_POST['sUsrConfirmPassword'] = "azerty";

        $userController = new UserController();

        $this->assertFalse($userController->createUser());

        // Sans Confirm Mot de passe

        $_POST['sUsrMail'] = 'TESTRFD@gmail.com';
        $_POST['sUsrPseudo'] = "Florent";
        $_POST['sUsrPassword'] = "azerty";
        $_POST['sUsrConfirmPassword'] = "";

        $userController = new UserController();

        $this->assertFalse($userController->createUser());
    }

}
