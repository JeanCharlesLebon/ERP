<?php
session_start();
require_once("Router/Router.php");

use Router\Router;

if ($_SERVER["REQUEST_URI"] == "/") { ?>
    <div class="container">
        main page
    </div>
	<?php
}

Router::getInstance()->route($_SERVER["REQUEST_METHOD"], $_SERVER["REQUEST_URI"], $_POST);