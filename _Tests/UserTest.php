<?php

/**
 * Created by PhpStorm.
 * User: <b>test1</b>Magnoux
 * Date: 09/06/2016
 * Time: 15:10
 */
require "../Controller/SuperController.php";
require "../Controller/UserController.php";
require "../Model/SQL.php";
chdir("../");

class UserTest extends PHPUnit_Framework_TestCase
{
    public function test_filterEmailController()
    {
        // Test le retour du filtre PHP pour l'email
        $userController = new UserController();

        $this->assertTrue($userController->filterEmail('test1@gmail.com'));
        $this->assertFalse($userController->filterEmail(''));
    }


    public function test_checkEmailController()
    {
        // Test la présence d'un email en base
        $userController = new UserController();
        $_POST['sUsrMail'] = 'test1@gmail.com';

        // Activer le test si l'adresse ci dessus n'est pas créer en base OK dans les deux cas
        // $this->assertFalse($userController->checkEmail());


        $_POST['sUsrMail'] = 'FloDavRomUPMC@gmail.com';

        $this->assertTrue($userController->checkEmail());
    }

    public function test_createUser(){

        // Test l'insertion avec tout les champs
        $_POST['sUsrMail'] = 'test1@gmail.com';
        $_POST['sUsrPseudo'] = "<b>test1</b>";
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
        $_POST['sUsrPseudo'] = "<b>test1</b>";
        $_POST['sUsrPassword'] = "azerty";
        $_POST['sUsrConfirmPassword'] = "azerty";

        $userController = new UserController();

        $this->assertFalse($userController->createUser());

        // Sans Mot de passe

        $_POST['sUsrMail'] = 'test1@gmail.com';
        $_POST['sUsrPseudo'] = "<b>test1</b>";
        $_POST['sUsrPassword'] = "";
        $_POST['sUsrConfirmPassword'] = "azerty";

        $userController = new UserController();

        $this->assertFalse($userController->createUser());

        // Sans Confirm Mot de passe
        $_POST['sUsrMail'] = 'test1@gmail.com';
        $_POST['sUsrPseudo'] = "<b>test1</b>";
        $_POST['sUsrPassword'] = "azerty";
        $_POST['sUsrConfirmPassword'] = "";

        $userController = new UserController();

        $this->assertFalse($userController->createUser());
    }

    public function test_loginUser(){
        $_POST['sUsrMail'] = "test1@gmail.com";
        $_POST['sUsrPassword'] = "azerty";

        $userController = new UserController();

        $this->assertTrue($userController->loginUser());

        $_POST['sUsrMail'] = "";
        $_POST['sUsrPassword'] = "azerty";

        $this->assertFalse($userController->loginUser());

        $_POST['sUsrMail'] = "test1@gmail.com";
        $_POST['sUsrPassword'] = "";

        $this->assertFalse($userController->loginUser());


    }

    public function test_desactivateUser() {
        $userController = new UserController();
        $_POST["id"] = 1;
        $this->assertTrue($userController->desactivateUser());
    }

    public function test_activateUser() {
        $userController = new UserController();
        $_POST["id"] = 1;
        $this->assertTrue($userController->activateUser());
    }

    public function test_forgottenPassword() {
        $userController = new UserController();
        $_POST["sMail"] = "f.romain2009@gmail.com";
        $this->assertTrue($userController->forgottenPassword());
    }

    public function test_generateNewPassword() {
        $_POST["submit"] = true;
        $_POST['sUsrPassword'] = "546Aa";
        $_POST['sUsrConfirmPassword'] = "546Aa";
        $_GET["token"] = "85e13c46273aaad5cb984145149e57ec";
        $_GET["id"] = "B0Jla3Ts0uMx8fAZzjniiQ%3D%3D";
        $userController = new UserController();
        $this->assertTrue($userController->generateNewPassword());
    }

    public function test_crypt() {
        $string = "2";

        $userController = new UserController();

        $crypt = $userController->encrypt($string);
        $decrypt = $userController->decrypt($crypt);

        var_dump($crypt);
        var_dump($decrypt);
    }

}
