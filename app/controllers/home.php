<?php
$SERVICE = $controller->callModel('service');

// Ajout d'un nouveau service via le formulaire
if (POST) {
	if (preg_match("#[a-zA-Z0-9.-_]+#", $_POST['host']) && is_numeric($_POST['port'])) {
		$time = Utils::time();
		$SERVICE->insertIntoDb(array($_POST['name'], $_POST['host'], $_POST['port'], $time, 0, 1));
	}
	else {
		$data['ERROR_MSG'] = 'Merci de fournir des informations valides.';
	}
}

switch (@$arg[0]) {
	//home/active/:id
	case 'active':
		// Activiation ou désactivation du service :id
		$serv = new Service($arg[1]);
		$serv->activated = ($serv->activated == 1) ? 0 : 1;
		$serv->save();
		header('location:'.WEBROOT);
		exit();
	break;
	
	//home/delete/:id
	case 'delete':
		// Suppression du service :id
		$serv = new Service($arg[1]);
		$serv->delete();
		header('location:'.WEBROOT);
		exit();
	break;

	//home/api
	case 'api':
		/*
		 * Retourne un JSON contenant toutes les infos sur tous les services
		 * NB: Utilisé par la fonction JS updateServicesStatus()
		 */
		$services = $SERVICE->getAll();
		$data = array();
		foreach ($services as $serv) {
			$data[] = new Service($serv['id']);
		}
		echo json_encode($data);
		exit();
	break;
}

// Affichage principal
$data['services'] = $SERVICE->getAll();
$controller->renderView('home/home', $data);