<!doctype html>
<html lang="fr">
<head>
    <base href="/RSurvey/">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="<?= isset($aMetaDescription[$this->page]) ? $aMetaDescription[$this->page] : "R Survey" ?>">
    <link rel="stylesheet" href="ressources/css/fonts.css">
    <link rel="stylesheet" href="ressources/css/material.min.css">
    <link rel="stylesheet" href="ressources/css/jquery-modal-mdl.css">
    <link rel="stylesheet" href="ressources/css/main.css">
    <link rel="stylesheet" href="ressources/css/admin.css">

    <link rel="shortcut icon" type="image/x-icon" href="ressources/media/img/logov1.ico" />
    <link rel="icon" type="image/x-icon" href="ressources/media/img/logov1.ico" />
    <title><?= isset($aMetaTitles[$this->page]) ? $aMetaTitles[$this->page] : "R Survey" ?></title>
</head>
<body>

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
                <a id="disconnect" class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold"
                   href="disconnect.html">Se Déconnecter</a>

                <a class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold" href="#contact">Contact</a>
            </nav>
            <label class="mdl-button mdl-js-button mdl-button--icon">
                <i class="material-icons mdl-color-text--grey-600 mdl-typography--font-bold">language</i>
            </label>
        </div>
        <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--white mdl-shadow--12dp">
            <a href="./administration.html" class="mdl-layout__tab <?php if($this->page == "admin/listQuestions") echo "is-active" ?> mdl-color-text--black">Tous les sondages</a>
            <a href="./administration-users.html" class="mdl-layout__tab <?php if($this->page == "admin/listUsers") echo "is-active" ?> mdl-color-text--black">Tous les utilisateurs</a>
            <a href="./administration-zones.html" class="mdl-layout__tab <?php if($this->page == "admin/listZones") echo "is-active" ?> mdl-color-text--black">Toutes les zones</a>

        </div>
        </header>
    </div>
    <div class="mdl-layout__drawer">
        <span class="mdl-layout-title">Administration</span>
        <nav class="mdl-navigation">
            <a class="mdl-navigation__link is-active" href="#scroll-tab-1">Ecrire un sondage</a>
            <a class="mdl-navigation__link" href="#scroll-tab-2">Se connecter</a>
            <a class="mdl-navigation__link" href="">Contact</a>
        </nav>
    </div>
    <div class="mdl-layout__content">
        <div class="page-content">
