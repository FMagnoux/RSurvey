<!-- Always shows a header, even in smaller screens. -->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <div class="mdl-layout__header mdl-shadow--12dp">
        <div class="mdl-layout__header-row mdl-color--white">
            <!-- Title -->
            <img class="imgLogo" src="ressources/media/img/logov1.svg" alt="Logo">
            <span class="mdl-layout-title mdl-color-text--grey-600 mdl-typography--font-bold">RSurvey</span>
            <!-- Add spacer, to align navigation to the right -->
            <div class="mdl-layout-spacer"></div>
            <!-- Navigation. We hide it in small screens. -->
            <nav class="mdl-navigation mdl-layout--large-screen-only">
                <a id="newSurvey" class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold" href="">Ecrire un sondage</a>
                <a id="login" class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold" href="">Se Connecter</a>
                <a id="signup" class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold" href="">Créer un compte</a>

                <a class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold" href="">Contact</a>
            </nav>
            <label class="mdl-button mdl-js-button mdl-button--icon">
                <i class="material-icons mdl-color-text--grey-600 mdl-typography--font-bold">language</i>
            </label>
        </div>
    </div>
    <div class="mdl-layout__drawer">
        <span class="mdl-layout-title">Menu</span>
        <nav class="mdl-navigation">
            <a class="mdl-navigation__link" href="">Ecrire un sondage</a>
            <a class="mdl-navigation__link" href="">Se connecter</a>
            <a class="mdl-navigation__link" href="">Contact</a>
        </nav>
    </div>
    <div class="mdl-layout__content">
        <div class="page-content">
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--1-col mdl-cell--hide-tablet"></div>
                <div class="mdl-cell mdl-cell--10-col">
                    <div id="containerCenter" class="card-wide mdl-card mdl-shadow--6dp">
                        <div class="mdl-card__title mdl-color--blue-800">
                            <h2 class="mdl-card__title-text mdl-typography--font-bold">Chocolatine ou Pain au chocolat ?</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div id="centermap"></div>
                        </div>
                        <div class="mdl-card__actions mdl-card--border">
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Un Bouton
                            </a>
                        </div>
                        <div class="mdl-card__menu">
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect mdl-color-text--white">Voir les réponses</a>
                        </div>
                    </div>
                </div>
                <div class="mdl-cell mdl-cell--1-col mdl-cell--hide-tablet"></div>
            </div>
        </div>
        <button id="fab" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored"><i class="material-icons">share</i></button>
        <footer class="mdl-mini-footer">
            <div class="mdl-mini-footer__left-section">
                <div class="mdl-logo">Rsurvey</div>
                <ul class="mdl-mini-footer__link-list">
                    <li><a href="#">Aide</a></li>
                    <li><a href="#">Mentions légales</a></li>
                </ul>
            </div>
        </footer>
    </div>
</div>