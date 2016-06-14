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
                <a class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold" href="#contact">Contact</a>
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
            <div class="homepage-landing-section mdl-typography--text-center">
                <div class="logo-font homepage-slogan">Thé ou Café ?</div>
                <div class="logo-font homepage-sub-slogan">R Survey vous permet de créer des sondages et d'avoir les résultats région par région.</div>
                <button class=" actionButton mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent mdl-color--green-800"><img src="ressources/media/img/tea.png" style="max-height:31px;margin-top:-12px;margin-right:8px" alt="tea">Je préfère le Thé</button>
                <button class=" actionButton mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent mdl-color--brown-800"><img src="ressources/media/img/coffee.png" style="max-height:31px;margin-top:-12px;margin-right:8px" alt="coffee">Définitivement Café</button>

                <button id="homepage-fab" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored mdl-color--blue-800"><i class="material-icons">expand_more</i></button>
            </div>
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-card__supporting-text mdl-cell--2-offset-desktop ">
                    <h3 class="mdl-typography--display-1-color-contrast">Une nouvelle génération de sondages</h3>
                    <p>
                        Excepteur reprehenderit sint exercitation ipsum consequat qui sit id velit elit. Velit anim eiusmod labore sit amet. Voluptate voluptate irure occaecat deserunt incididunt esse in. Sunt velit aliquip sunt elit ex nulla reprehenderit qui ut eiusmod ipsum do. Duis veniam reprehenderit laborum occaecat id proident nulla veniam. Duis enim deserunt voluptate aute veniam sint pariatur exercitation. Irure mollit est sit labore est deserunt pariatur duis aute laboris cupidatat. Consectetur consequat esse est sit veniam adipisicing ipsum enim irure.
                    </p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
                    <img class="homepage-french-map" src="ressources/media/img/homepage-french-map.png"  alt="Carte de France">
                </div>
                <div class=" container-image-responsive mdl-cell mdl-cell--5-col mdl-cell--8-col-tablet">
                    <img class="homepage-responsive" src=" ressources/media/img/homepage-responsive.png" alt="Responsive">
                </div>
                <div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-card__supporting-text no-padding ">
                    <h3 class="mdl-typography--display-1-color-contrast">Fonctionne sur toutes les plateformes </h3>
                    <p>
                        Excepteur reprehenderit sint exercitation ipsum consequat qui sit id velit elit. Velit anim eiusmod labore sit amet. Voluptate voluptate irure occaecat deserunt incididunt esse in. Sunt velit aliquip sunt elit ex nulla reprehenderit qui ut eiusmod ipsum do. Duis veniam reprehenderit laborum occaecat id proident nulla veniam. Duis enim deserunt voluptate aute veniam sint pariatur exercitation. Irure mollit est sit labore est deserunt pariatur duis aute laboris cupidatat. Consectetur consequat esse est sit veniam adipisicing ipsum enim irure.
                    </p>
                </div>
            </div>
            <div class="mdl-color-text--white homepage-rubber">
                <div class="rubber-font rubber-slogan">Déjà 1522 sondages en circulations</div>
                <button class=" actionButton mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent mdl-color--blue-800">écrire un sondage</button>

            </div>
            <div class="mdl-grid">
                <div id="contact" class="mdl-cell mdl-cell--8-col  mdl-card__supporting-text mdl-cell--2-offset-desktop">
                    <h3 class="mdl-typography--display-1-color-contrast">Une question ?</h3>
                    <p>Nous sommes à votre disposition si vous avez une question.</p>
                    <form action="#">

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="email" name="email">
                            <label class="mdl-textfield__label">Adresse Email</label>
                        </div>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" name="content">
                            <label class="mdl-textfield__label">Message</label>
                        </div>
                        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent mdl-js-ripple-effect">Envoyer</button>

                    </form>

                </div>

                <div class="mdl-cell mdl-cell--2-col"></div>
            </div>
        </div>
        <div class="mdl-mini-footer">
            <div class="mdl-mini-footer__left-section">
                <div class="mdl-logo">Rsurvey</div>
                <ul class="mdl-mini-footer__link-list">
                    <li><a href="#">Aide</a></li>
                    <li><a href="#">Mentions légales</a></li>
                </ul>
            </div>
        </div>
    </div>

</div>
