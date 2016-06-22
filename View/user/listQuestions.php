<?php require_once './View/user/filterQuestions.php' ?>

<?php foreach ($oPagination->getAData() as $oQuestion) : ?>
<div class=" container-card section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">

        <div class="mdl-card mdl-cell mdl-cell--12-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
            <div class="mdl-card__title mdl-card--expand mdl-color--blue-800">
                <h2 class="mdl-card__title-text mdl-typography--font-bold mdl-color-text--white"><?= $oQuestion->getSQuestionLibel() ?></h2>
</div>
<div class="mdl-card__supporting-text">
    Le sondage est <?= $oQuestion->getBQuestionClose() ? "clos" : "ouvert" ?>.
</div>
<div class="mdl-card__actions">
    <a href="./<?= $this->encrypt($oQuestion->getIQuestionId()) ?>" class="mdl-button" target="_blank">Acceder au sondage</a>
    <a href="#" class="mdl-button">Partager le sondage</a>
    <?php if(!$oQuestion->getBQuestionClose()) : ?><button id="<?= $this->encrypt($oQuestion->getIQuestionId()) ?>" class="close-survey mdl-button mdl-color-text--red-500 desactivate-survey">Clore le sondage</button><?php endif; ?>
</div>
</div>
</div>
<?php endforeach;

$oPagination->view($sUrlStart, ".html");
