<form class="mdl-typography--text-center" action="./administration-filtre-users.html" method="post">
    <div class="mdl-textfield mdl-js-textfield">
        <input class="mdl-textfield__input" type="text" name="sPseudo" placeholder="Pseudo" <?php if(!empty($sPseudo)) echo "value='".$sPseudo."'" ?>>
    </div>
    <div class="mdl-textfield mdl-js-textfield">
        <input class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" type="submit">
    </div>
</form>