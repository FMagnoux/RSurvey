<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 09/06/2016
 * Time: 12:21
 */
class QuestionController extends SuperController
{
    private $oEntity;

    public function __construct() {
        parent::__construct();
        require_once "./Model/Question.php";
        $this->oEntity = new Question();
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
        echo json_encode($this->oEntity->getPaginatedQuestionList(10, isset($_GET["page"]) ? $_GET["page"] : 1));
    }

    /**
     * Liste des questions d'un utilisateur
     * @return bool
     */
    public function listQuestionsByIdUser() {
        $id = $this->checkGetId();
        if($id == 0) return false;
        $this->setJsonData();
        echo json_encode($this->oEntity->getPaginatedQuestionList(10, isset($_GET["page"]) ? $_GET["page"] : 1, $id));
    }

    /**
     * Désactiver la question d'un user
     * @return bool
     */
    public function desactivateQuestion() {
        $id = $this->checkPostId();
        if($id == 0) return false;
        return $this->oEntity->desactivateQuestion($id);
    }
}