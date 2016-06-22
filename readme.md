Pour installer le projet :
- Créez une base de données nommée "rsurvey" et utilisez le script sql le plus récent présent dans le dossier dump du projet.
- Allez dans les fichiers View/commun/header.php et View/admin/header.php. Trouvez la ligne <base href="/RSurvey/"> et adaptez le chemin à votre environnement.
- Allez dans le fichier .htaccess. Trouvez la ligne ErrorDocument 404 /RSurvey/index.php?ctrl=Super&action=error et adaptez le chemin à votre environnement.

Si vous installez le projet en localhost, la fonction permettant d'envoyer des e-mails ne fonctionnera pas. Ainsi vous ne pourrez pas activer un compte utilisateur ou générer un nouveau mot de passe en cas d'oubli. Il sera alors seulement possible d'activer un compte en procédant manuellement :
Pour activer un compte utilisateur, trouvez votre utilisateur dans la table User et mettez son champ usr_active à 1.

Lien du projet : http://romainfrancois.com/RSurvey