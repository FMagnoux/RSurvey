<?php
require_once './View/admin/config.php';
require_once './View/admin/header.php';

if (isset($this->page)) {
    require_once("./View/" . $this->page . ".php");
}

require_once './View/admin/footer.php';