<?php

/**
 * Created by PhpStorm.
 * User: licence
 * Date: 13/06/2016
 * Time: 16:22
 */

require "../Controller/SuperController.php";
require "../Controller/AdminController.php";
require "../Model/SQL.php";
chdir("../");

class AdminControllerTest extends PHPUnit_Framework_TestCase
{

    public function test_home() {
        $_SESSION["iIdUser"] = 1;
        $adminController = new AdminController();
        $adminController->home();
    }
}