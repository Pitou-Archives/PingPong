<?php
class Controller {
	public function callModel($name) {
		$file = MODELS.$name.'.php';
		if (file_exists($file) ) {
			require_once $file;
			return new $name(0);
		}
		else {
			$this->error500();
			exit();
		}
	}

	public function renderView($path, $data = array(), $layout = true) {
		$file = VIEWS.$path.'.php';
		if (file_exists($file) ) {
			$appView = $file;
			extract($data);
			if ($layout) {
				require_once VIEWS.'mainView.php';
			}
			else {
				require_once $appView;
			}
		}
		else {
			$this->error500();
		}
	}

	public function error401() {
		header('HTTP/1.1 401 Authorization Required');
		$this->renderView('error/401');
	}

	public function error403() {
		header('HTTP/1.1 403 Forbidden');
		$this->renderView('error/403');
	}

	public function error404() {
		header('HTTP/1.1 404 Not Found');
		$this->renderView('error/404');
	}

	public function error500() {
		header('HTTP/1.1 500 Internal Server Error');
		$this->renderView('error/500');
	}
}