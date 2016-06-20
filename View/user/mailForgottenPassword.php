<?php
$aMail["subject"] = "Mot de passe oublié";
$aMail["fromName"] = "R Survey";
$aMail["from"] = "no-reply@r-survey.com";
$aMail["message"] = "
<html>
  <head>
   <title>".$aMail["subject"]."</title>
  </head>
  <body>
    <p><a href='http://r-survey.com/mot-de-passe-oublie/".$sId."/".$sToken."'>Cliquez ici</a> pour réinitialiser votre mot de passe.</p>
  </body>
</html>
";