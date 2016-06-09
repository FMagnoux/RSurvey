<?php
session_start();
if (isset($_GET["ctrl"]) && isset($_GET["action"])) {
    $ctrl = htmlspecialchars($_GET["ctrl"]);
    $action = htmlspecialchars($_GET["action"]);
} else {
    $ctrl = "super";
    $action = "home";
}
require_once "./Model/SQL.php";
require_once "./Controller/SuperController.php";
// Vérifier que le contrôleur existe
if(file_exists("./Controller/" . $ctrl . "Controller.php")) {
    require_once("./Controller/" . $ctrl . "Controller.php");
    $ctrl = $ctrl . "Controller";
    $controller = new $ctrl();
    // Vérifier que l'action existe
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        (new superController())->error();
    }
}
else {
    (new superController())->error();
}