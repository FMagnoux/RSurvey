<?php foreach ($oPagination->getAData() as $oQuestion) : ?>
    <p><a href="./<?= $this->encrypt($oQuestion->getIQuestionId()) ?>" title="Accéder au sondage"><?= $oQuestion->getSQuestionLibel() ?></a><br>
    Le sondage est <?= $oQuestion->getBQuestionClose() ? "clos" : "ouvert" ?>.</p>
<?php endforeach;

$oPagination->view("./mes-sondages/page-", ".html");
