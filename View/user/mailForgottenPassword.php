<?php
$aMail["subject"] = "Activation du compte";
$aMail["fromName"] = "R Survey";
$aMail["from"] = "no-reply@r-survey.com";
$aMail["message"] = "<p><a href='http://r-survey.com/mot-de-passe-oublie/".$sId."/".$sToken."'>Cliquez ici</a> pour réinitialiser votre mot de passe.</p>";