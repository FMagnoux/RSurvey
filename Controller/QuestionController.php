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

    /**
     * Liste de questions paginées
     */
    public function listQuestions() {
        $this->setJsonData();
        echo json_encode($this->entity->getPaginatedList(10, isset($_GET["page"]) ? $_GET["page"] : 1));
    }
    
    public function desactivateQuestion() {
        if(empty($_POST["id"])) {
           return false;
        }
        $id = intval($_POST["id"]);
        return $this->entity->desactivateQuestion($id);
    }
}