<form action="./administration-filtre-users.html" method="post">
    <input type="text" name="sPseudo" placeholder="Pseudo" <?php if(!empty($sPseudo)) echo "value='".$sPseudo."'" ?>>
    <input type="submit">
</form>