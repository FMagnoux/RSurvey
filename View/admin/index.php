        <section class="mdl-layout__tab-panel is-active" id="scroll-tab-1">
            <div class="page-content">
                <?php foreach ($oPagination->getAData() as $oQuestion) : ?>
                    <div class=" container-card section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">

                        <div class="mdl-card mdl-cell mdl-cell--12-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
                            <div class="mdl-card__title mdl-card--expand mdl-color--blue-800">
                                <h2 class="mdl-card__title-text mdl-typography--font-bold mdl-color-text--white"><?= $oQuestion->getSQuestionLibel() ?></h2>
                            </div>
                            <div class="mdl-card__supporting-text">
                                Dolore ex deserunt aute fugiat aute nulla ea sunt aliqua nisi cupidatat eu. Nostrud in laboris labore nisi amet do dolor eu fugiat consectetur elit cillum esse.
                            </div>
                            <div class="mdl-card__actions">
                                <a href="./<?= $this->encrypt($oQuestion->getIQuestionId()) ?>.html" class="mdl-button" target="_blank">Acceder au sondage</a>
                                <a href="#" class="mdl-button">Partager le sondage</a>
                                <a href="#" class="mdl-button mdl-color-text--red-500 desactivate-survey">Desactiver le sondage</a>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="mdl-layout__tab-panel" id="scroll-tab-2">
            <div class="page-content">Tab 2</div>
        </section>