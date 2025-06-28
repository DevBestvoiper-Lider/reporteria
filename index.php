<?php

// Establecer la zona horaria al principio del script
date_default_timezone_set('America/Bogota');

require_once 'controllers/dashboard.controllers.php';
require_once 'controllers/usuarios.controllers.php';
require_once 'controllers/plantilla.controllers.php';
require_once 'controllers/miaccount.controllers.php';
require_once 'controllers/reportcdr.controllers.php';
require_once 'controllers/payhistory.controllers.php';
require_once 'controllers/consumption.controllers.php';
require_once 'controllers/reportconsumo.controllers.php';
require_once 'controllers/firewall.controllers.php';

require_once 'models/firewall.models.php';
require_once 'models/dashboard.models.php';
require_once 'models/usuarios.models.php';
require_once 'models/miaccount.models.php';
require_once 'models/reportcdr.models.php';
require_once 'models/payhistory.models.php';
require_once 'models/consumption.models.php';
require_once 'models/reportconsumo.models.php';

$plantilla = new ControllersPlantilla();

$plantilla -> ctrPlantilla();

//  phpinfo();
