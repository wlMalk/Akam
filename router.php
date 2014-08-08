<?php
class Router{

	public $requestURI;
	public $config;

	public function __construct($requestURI, $config){
		$this->requestURI = $requestURI;
		$this->config = $config;
	}

	public function route(){

		$this->requestURI = trim(substr($this->requestURI, strlen($this->config['projectPath'].$this->config['publicPath'])), '/');
		$path = explode('/', $this->requestURI);
		$type = '';

		$counts = count($path);
		if($path[0] == '')
			$type = 'index';
		else if($path[0] == $this->config['adminPrefix'])
			$type = 'admin';
		else if(preg_match("/.png|.jpg|.jpeg|.bmp|.gif/i", $path[$counts-1])) 
			$type = 'image';
		else if(strpos($path[$counts-1],'.') === false) 
			$type = 'category';
		else
			$type = 'notfound';

		foreach($path as $item){
			if(empty($item) && ($type == 'image' || $type == 'category')){
				$type = 'notfound';
				break;
			}
		}

		return array($type, $path, $this->requestURI);
	}

}