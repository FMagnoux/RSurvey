<?php
if(!empty($success)) echo "<p class=\"mdl-typography--text-center\">" . $success . "</p>";
else {
    if(!empty($error)) echo "<p class=\"mdl-typography--text-center\">" . $error . "</p>"; ?>
        <form method="post">
            <input type="password" name="sUsrPassword">
            <input type="password" name="sUsrConfirmPassword">
            <input type="submit" name="submit">
        </form>
<?php } ?>