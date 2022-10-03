<?php

session_start();

require_once 'model/db/db.m.php';
require_once 'model/entities/entity.php';

require_once 'model/entities/admin.m.php';
require_once 'model/entities/feedback.m.php';
require_once 'model/entities/ip.m.php';

require_once 'model/functions.m.php';
require_once 'controller/controller.php';



$aktion = isset($_GET['a'])?$_GET['a']:'home';

$controller = new Controller();

if (method_exists($controller, $aktion)){
        $controller->run($aktion);
}


?>