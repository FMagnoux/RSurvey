<?php

/**
 * Created by PhpStorm.
 * User: FlorentMagnoux
 * Date: 09/06/2016
 * Time: 14:18
 */
class UserController extends SuperController
{
    private $entity;

    public function __construct() {
        parent::__construct();
        require_once "./Model/User.php";
        $entity = new User();
    }

    public function createUser() {
        
    }


}