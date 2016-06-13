<!doctype html>
<html lang="fr">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
      <meta name="description" content="">
      <link rel="stylesheet" href="./ressources/css/fonts.css">
      <link rel="stylesheet" href="./ressources/css/sweetalert_custom.css">
      <link rel="stylesheet" href="./ressources/css/material.min.css">
      <link rel="stylesheet" href="./ressources/css/main.css">
      <link rel="stylesheet" href="./ressources/css/home.css">

      <link rel="shortcut icon" type="image/x-icon" href="./ressources/media/img/logov1.ico" />
      <link rel="icon" type="image/x-icon" href="./ressources/media/img/logov1.ico" />
      <title>R Survey</title>
   </head>
   <body>
      <!-- Always shows a header, even in smaller screens. -->
      <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
         <header class="mdl-layout__header mdl-shadow--12dp">
            <div class="mdl-layout__header-row mdl-color--white">
               <!-- Title -->
               <img class="imgLogo" src="./ressources/media/img/logov1.svg" alt="Logo">
               <span class="mdl-layout-title mdl-color-text--grey-600 mdl-typography--font-bold">RSurvey</span>
               <!-- Add spacer, to align navigation to the right -->
               <div class="mdl-layout-spacer"></div>
               <!-- Navigation. We hide it in small screens. -->
               <nav class="mdl-navigation mdl-layout--large-screen-only">
                  <a id="newSurvey" class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold" href="">Ecrire un sondage</a>
                  <a id="login" class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold" href="">Se Connecter</a>
                  <a class="mdl-navigation__link mdl-color-text--grey-600 mdl-typography--font-bold" href="#contact">Contact</a>
               </nav>
               <label class="mdl-button mdl-js-button mdl-button--icon">
               <i class="material-icons mdl-color-text--grey-600 mdl-typography--font-bold">language</i>
               </label>
            </div>
         </header>