<?php

class Flatttr{


	public $config;
	public $router;
	public $templates;


	public function __construct($config, $router, $templates){
		$this->config = $config;
		$this->router = $router;
		$this->templates = $templates;

		$this->route();
	}

	private function route(){
		$route = $this->router->route();
		switch ($route[0]) {
			case 'index':
				$this->templates->showCategory('');
				break;
			case 'image':
				$this->templates->showImage($route[2]);
				break;
			case 'category':
				$this->templates->showCategory($route[2]);
				break;
			case 'admin':
				# code...
				break;
			case 'notfound':
				$this->templates->showNotFound();
				break;
			default:
				# code...
				break;
		}
	}

}