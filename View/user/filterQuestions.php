<form class="mdl-typography--text-center" action="./mes-sondages-filtre.html" method="post">
    <div class="mdl-textfield mdl-js-textfield">
        <input class="mdl-textfield__input" type="text" name="sLibel" placeholder="Libellé" <?php if(!empty($sLibel)) echo "value='".$sLibel."'" ?>>
    </div>
    <div class="mdl-textfield mdl-js-textfield">
        <input class="mdl-textfield__input" type="date" name="dDateAfter" placeholder="Après cette date" <?php if(!empty($dDateAfer)) echo "value='".$dDateAfer."'" ?>>
    </div>
    <div class="mdl-textfield mdl-js-textfield">
        <input class="mdl-textfield__input" type="date" name="dDateBefore" placeholder="Avant cette date" <?php if(!empty($dDateBefore)) echo "value='".$dDateBefore."'" ?>>
    </div>
    <div class="mdl-textfield mdl-js-textfield">
        <input class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" type="submit">
    </div>
</form>