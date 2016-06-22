<?php
$aMail["subject"] = "Activation du compte";
$aMail["fromName"] = "R Survey";
$aMail["from"] = "no-reply@r-survey.com";
$aMail["message"] = "
<html>
  <head>
   <title>".$aMail["subject"]."</title>
  </head>
  <body>
    <p><a href='".$_SERVER["HTTP_HOST"].dirname($_SERVER['PHP_SELF'])."/".$sId."/".$sToken."'>Cliquez ici</a> pour activer votre compte.</p>
  </body>
</html>
";