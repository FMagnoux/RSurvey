<?php

/**
 * Created by PhpStorm.
 * User: licence
 * Date: 10/06/2016
 * Time: 11:45
 */
class ZoneController extends SuperController
{
    private $entity;

    public function __construct() {
        parent::__construct();
        require_once "./Model/Zone.php";
        $this->entity = new Zone();
    }
}