<form action="./administration-filtre.html" method="post">
    <input type="text" name="sPseudo" placeholder="Pseudo" <?php if(!empty($sPseudo)) echo "value='".$sPseudo."'" ?>>
    <input type="text" name="sLibel" placeholder="Libellé" <?php if(!empty($sLibel)) echo "value='".$sLibel."'" ?>>
    <input type="date" name="dDateAfter" placeholder="Après cette date" <?php if(!empty($dDateAfer)) echo "value='".$dDateAfer."'" ?>>
    <input type="date" name="dDateBefore" placeholder="Avant cette date" <?php if(!empty($dDateBefore)) echo "value='".$dDateBefore."'" ?>>
    <input type="submit">
</form>