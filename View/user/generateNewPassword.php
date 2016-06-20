<?php
if(!empty($success)) echo $success;
else {
    if(!empty($error)) echo $error; ?>
        <form method="post">
            <input type="password" name="sUsrPassword">
            <input type="password" name="sUsrConfirmPassword">
            <input type="submit" name="submit">
        </form>
<?php } ?>