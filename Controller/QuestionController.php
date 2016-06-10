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
        echo json_encode($this->entity->getPaginatedQuestionList(10, isset($_GET["page"]) ? $_GET["page"] : 1));
    }
    
    public function desactivateQuestion() {
        $id = $this->checkId();
        if($id == 0) return false;
        return $this->entity->desactivateQuestion($id);
    }
}