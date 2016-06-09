<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 12:21
 */
class QuestionController extends SuperController
{
    private $entity;

    public function __construct() {
        parent::__construct();
        require_once "./Model/Question.php";
        $this->entity = new Question();
    }

    public function create() {
        $this->page = "user/create";
        $this->view();
    }

    public function listQuestions() {
        require_once './Model/Pagination.php';
        $this->entity->getPaginatedList(10, 1);
    }
}