<?php require_once './View/admin/filterUsers.php' ?>

<?php foreach ($oPagination->getAData() as $oUser) : ?>
    <div class=" container-card section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">

        <div class="mdl-card mdl-cell mdl-cell--12-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
            <div class="mdl-card__title mdl-card--expand mdl-color--blue-800">
                <h2 class="mdl-card__title-text mdl-typography--font-bold mdl-color-text--white"><?= $oUser->getSUsrPseudo() ?></h2>
            </div>
            <div class="mdl-card__supporting-text">
                Dolore ex deserunt aute fugiat aute nulla ea sunt aliqua nisi cupidatat eu. Nostrud in laboris labore nisi amet do dolor eu fugiat consectetur elit cillum esse.
            </div>
            <div class="mdl-card__actions">
                <a href="./administration/<?= $this->encrypt($oUser->getIUsrId()) ?>/page-1.html" class="mdl-button">Acceder aux sondages</a>
                <button id="<?= $this->encrypt($oUser->getIUsrId()) ?>" class="mdl-button mdl-color-text--red-500 desactivate-survey">Desactiver l'utilisateur</button>

            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php $oPagination->view($sUrlStart, ".html");
