<?php

/**
 * Created by PhpStorm.
 * User: <b>test22</b>Magnoux
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

        $this->assertTrue($userController->filterEmail('test22@gmail.com'));
        $this->assertFalse($userController->filterEmail(''));
    }


    public function test_checkEmailController()
    {
        // Test la présence d'un email en base
        $userController = new UserController();
        $_POST['sUsrMail'] = 'test22@gmail.com';

        // Activer le test si l'adresse ci dessus n'est pas créer en base OK dans les deux cas
        // $this->assertFalse($userController->checkEmail());


        $_POST['sUsrMail'] = 'FloDavRomUPMC@gmail.com';

        $this->assertTrue($userController->checkEmail());
    }

    public function test_createUser(){

        // Test l'insertion avec tout les champs
        $_POST['sUsrMail'] = 'test22@gmail.com';
        $_POST['sUsrPseudo'] = "<b>test22</b>";
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
        $_POST['sUsrPseudo'] = "<b>test22</b>";
        $_POST['sUsrPassword'] = "azerty";
        $_POST['sUsrConfirmPassword'] = "azerty";

        $userController = new UserController();

        $this->assertFalse($userController->createUser());

        // Sans Mot de passe

        $_POST['sUsrMail'] = 'test22@gmail.com';
        $_POST['sUsrPseudo'] = "<b>test22</b>";
        $_POST['sUsrPassword'] = "";
        $_POST['sUsrConfirmPassword'] = "azerty";

        $userController = new UserController();

        $this->assertFalse($userController->createUser());

        // Sans Confirm Mot de passe
        $_POST['sUsrMail'] = 'test22@gmail.com';
        $_POST['sUsrPseudo'] = "<b>test22</b>";
        $_POST['sUsrPassword'] = "azerty";
        $_POST['sUsrConfirmPassword'] = "";

        $userController = new UserController();

        $this->assertFalse($userController->createUser());
    }

    public function test_loginUser(){
        $_POST['sUsrMail'] = "test22@gmail.com";
        $_POST['sUsrPassword'] = "azerty";

        $userController = new UserController();

        $this->assertTrue($userController->loginUser());

        $_POST['sUsrMail'] = "";
        $_POST['sUsrPassword'] = "azerty";

        $this->assertFalse($userController->loginUser());

        $_POST['sUsrMail'] = "test22@gmail.com";
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
        $_POST["sMail"] = "test22@gmail.com";
        $this->assertTrue($userController->forgottenPassword());
    }

    public function test_generateNewPassword() {
        $_POST["submit"] = true;
        $_POST['sUsrPassword'] = "1234Ab";
        $_POST['sUsrConfirmPassword'] = "1234Ab";
        $_GET["id"] = "DJqVwV%2Byjf9gNY0qj91f%2BA%3D%3D";
        $_GET["token"] = "66a8f7a5add59f1714ca5f6ee1127fa7";
        $userController = new UserController();
        $this->assertTrue($userController->generateNewPassword());
        $this->assertFalse($userController->generateNewPassword());
    }

    public function test_confirmUser() {
        $userController = new UserController();
        $_GET["id"] = "pR3qdtqFhypyK%2BHkBaod3Q%3D%3D";
        $_GET["token"] = "367b9dfbd935c4cd063c8829dda8cb36";
        $this->assertTrue($userController->confirmUser());
        $this->assertFalse($userController->confirmUser());
    }

    public function test_crypt() {
        $string = "7";
        $userController = new UserController();

        $crypt = $userController->encrypt($string);
        $decrypt = $userController->decrypt($crypt);

        var_dump($crypt);
        var_dump($decrypt);
    }

}
