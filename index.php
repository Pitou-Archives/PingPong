<?php
define('NAME', 'PingPong');
define('POST', $_SERVER['REQUEST_METHOD'] == 'POST');
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']), true);
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']), true);
define('SYSTEM', ROOT.'system/');
define('APP', ROOT.'app/');
define('MODELS', APP.'models/');
define('VIEWS', APP.'views/');
define('CONTROLLERS', APP.'controllers/');
define('ASSETS', WEBROOT.'assets/');
define('CSS', ASSETS.'css/');
define('JS', ASSETS.'js/');
define('FONTS', ASSETS.'fonts/');
define('IMG', ASSETS.'img/');

require_once SYSTEM.'Controller.php';
require_once SYSTEM.'Database.php';
require_once SYSTEM.'Model.php';
require_once SYSTEM.'Entry.php';
require_once SYSTEM.'Utils.php';

$controller = new Controller();

$req = (isset($_GET['arg']) ) ? explode('/', $_GET['arg']) : array('home');
if (file_exists(CONTROLLERS.$req[0].'.php') ) {
	$module = $req[0];
}
else {
	$controller->error404();
	exit();
}

$action = (isset($req[1]) ) ? $req[1] : '';
$arg = array();
for ($i = 1; $i < count($req); $i++) {
	$arg[] = $req[$i];
}

$bdd = new Database();
$data = array();

require_once CONTROLLERS.$module.'.php';