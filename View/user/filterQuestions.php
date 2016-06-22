<form class="mdl-typography--text-center" action="./mes-sondages-filtre.html" method="post">
    <div class="mdl-textfield mdl-js-textfield">
        <input id="sLibel" class="mdl-textfield__input" type="text" name="sLibel" <?php if(!empty($sLibel)) echo "value='".$sLibel."'" ?>>
        <label for="sLibel">Libellé</label>
    </div>
    <div class="mdl-textfield mdl-js-textfield">
        <input id="dDateAfter" class="mdl-textfield__input" type="date" name="dDateAfter" <?php if(!empty($dDateAfer)) echo "value='".$dDateAfer."'" ?>>
        <label for="dDateAfter">Après cette date</label>
    </div>
    <div class="mdl-textfield mdl-js-textfield">
        <input id="dDateBefore" class="mdl-textfield__input" type="date" name="dDateBefore" <?php if(!empty($dDateBefore)) echo "value='".$dDateBefore."'" ?>>
        <label for="dDateBefore">Avant cette date</label>
    </div>
    <div class="mdl-textfield mdl-js-textfield">
        <input class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" type="submit">
    </div>
</form>