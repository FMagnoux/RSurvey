<?php require_once './View/admin/filterQuestions.php' ?>

<?php foreach ($oPagination->getAData() as $oQuestion) : ?>
    <div class=" container-card section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">

        <div class="mdl-card mdl-cell mdl-cell--12-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
            <div class="mdl-card__title mdl-card--expand mdl-color--blue-800">
                <h2 class="mdl-card__title-text mdl-typography--font-bold mdl-color-text--white"><?= $oQuestion->getSQuestionLibel() ?></h2>
            </div>
            <div class="mdl-card__supporting-text">
                Sondage créé par : <a href="./administration/<?= $this->encrypt($oQuestion->getOUsr()->getIUsrId()) ?>/page-1.html" title="Voir les sondages de cet auteur"><?= $oQuestion->getOUsr()->getSUsrPseudo() ?></a>.<br>
                Le sondage est <?= $oQuestion->getBQuestionClose() ? "clos" : "ouvert" ?>.
            </div>
            <div class="mdl-card__actions">
                <a href="./<?= $this->encrypt($oQuestion->getIQuestionId()) ?>" class="mdl-button" target="_blank">Acceder au sondage</a>
                <a href="#" class="mdl-button">Partager le sondage</a>
                <button id="<?= $this->encrypt($oQuestion->getIQuestionId()) ?>" class="mdl-button mdl-color-text--red-500 desactivate-survey">Desactiver le sondage</button>

            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php
$oPagination->view($sUrlStart, ".html");