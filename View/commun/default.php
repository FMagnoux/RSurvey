<?php
require_once './View/commun/config.php';
require_once './View/commun/header.php';

if (isset($this->page)) {
    require_once("./View/" . $this->page . ".php");
}

require_once './View/commun/footer.php';