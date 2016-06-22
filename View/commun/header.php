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
   <?php $this->insertFiles($aCss, $aCssFileNames, "<link rel=\"stylesheet\" href=\"./ressources/css/", ".css\">"); ?>

   <link rel="shortcut icon" type="image/x-icon" href="ressources/media/img/logov1.ico" />
   <link rel="icon" type="image/x-icon" href="ressources/media/img/logov1.ico" />
   <title><?= isset($aMetaTitles[$this->page]) ? $aMetaTitles[$this->page] : "R Survey" ?></title>
</head>
<body>
<!-- Always shows a header, even in smaller screens. -->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
   <div class="mdl-layout__header mdl-shadow--12dp">
      <div class="mdl-layout__header-row mdl-color--white">
         <!-- Title -->
         <a href="./" title="Retour à l'accueil"><img class="imgLogo" src="ressources/media/img/logov1.svg" alt="Logo"></a>
         <span class="mdl-layout-title mdl-color-text--grey-600 mdl-typography--font-bold">RSurvey</span>
         <!-- Add spacer, to align navigation to the right -->
         <div class="mdl-layout-spacer"></div>
         <!-- Navigation. We hide it in small screens. -->
         <nav class="mdl-navigation mdl-layout--large-screen-only">
            <a id="newSurvey" class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold" href="">Ecrire un sondage</a>
            <?php
            if(isset($_SESSION['iIdUser']) || !empty($_SESSION['iIdUser']) ) {
               ?>
               <a id="disconnect" class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold"
                  href="disconnect.html">Se Déconnecter</a>
               <a id="updateUser" class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold" href="">Modifier son compte</a>
               <a class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold" href="./mes-sondages.html">Mes sondages</a>
               <?php
            }
            else {
               ?>
               <a id="login" class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold" href="">Se Connecter</a>
                  <a id="signup" class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold" href="">Créer un compte</a>

               <?php
            }
            if(!empty($_SESSION['iIdRole']) && $_SESSION['iIdRole'] < 2 ) { ?>
               <a class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold" href="administration.html">Espace administrateur</a>
            <?php } ?>

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
         <?php
         if(isset($_SESSION['iIdUser']) || !empty($_SESSION['iIdUser']) ) {
            ?>
            <a id="disconnect" class="mdl-navigation__link" href="disconnect.html">Se déconnecter</a>

            <?php
         } else {
            ?>
            <a id="login" class="mdl-navigation__link" href="">Se connecter</a>
            <?php
         }
         ?>
         <a class="mdl-navigation__link" href="">Contact</a>
      </nav>
   </div>
   <div class="mdl-layout__content">
      <div class="page-content">
