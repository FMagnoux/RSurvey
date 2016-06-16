<?php
if(!empty($error)) echo $error;
if(!empty($success)) echo $success;
?>

<form method="post">
    <input type="password" name="sUsrPassword">
    <input type="password" name="sUsrConfirmPassword">
    <input type="submit" name="submit">
</form>
