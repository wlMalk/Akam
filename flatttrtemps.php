<?php

class FlatttrTemps{

	public $config;

	public function __construct($config){
		$this->config = $config;
	}

	public function showImage($imagePath){

		$imageFullPath = $this->getImageFullPath($imagePath);

		if(file_exists($imageFullPath) && is_file($imageFullPath)){
			$imageURL = $this->config['projectURI'].$this->config['dataPath'].'/'.$imagePath;

			if(strpos($imagePath, '/') !== false)
				$imageName = str_replace('ـ', ' ', str_replace('_', ' ', str_replace('-', ' ', trim(strrchr(urldecode($imagePath), '/'), '/'))));
			else
				$imageName = urldecode($imagePath);
			
			$imageName = substr($imageName, 0, strrpos($imageName, '.'));

			$this->renderHeader($imageName);
			$this->renderBreadcrumb($imagePath);

			echo '<div class="col-md-12"><img width="100%" src="'.$imageURL.'"></div>';
			echo '<div class="col-md-12"><a href="'.$imageURL.'"><h2><span class="label label-success">تحميل</span></h2></a></div>';

			$this->renderFooter('شكراً على زيارتكم');

		}else{
			$this->showNotFound();
		}
	}

	public function showCategory($categoryPath){

		$categoryFullPath = $this->getCategoryFullPath($categoryPath);
		if(file_exists($categoryFullPath) && is_dir($categoryFullPath)){
			$categoryContent = $this->getCategoryContent($categoryPath, $categoryFullPath);

			if(empty($categoryPath)){ //if index
				$this->renderHeader("الرئيسية");
				$this->renderBreadcrumb($categoryPath);
				$this->renderIndexBox('أهلاً بكم!', 'اتمنى أن تستمتعوا بصوري');
				$this->renderCategory('', $categoryContent);

			}else{ //if it's not index
				if(strpos($categoryPath, '/') !== false)
					$categoryName = str_replace('ـ', ' ', str_replace('_', ' ', str_replace('-', ' ', trim(strrchr(urldecode($categoryPath), '/'), '/'))));
				else
					$categoryName = urldecode($categoryPath);

				$this->renderHeader($categoryName);
				$this->renderBreadcrumb($categoryPath);
				$this->renderCategory($categoryPath.'/', $categoryContent);
			}
			$this->renderFooter('شكراً على زيارتكم');

		}else{
			$this->showNotFound();
		}
	}

	private function getCategoryContent($categoryPath, $categoryFullPath){
		$imagesArr = array();
		$categoriesArr = array();
		if ($handle = opendir($categoryFullPath)) {
			while (false !== ($entry = readdir($handle))) {
    			if ($entry != "." && $entry != "..") {
    				$entryPath = $categoryFullPath.'/'.$entry;
        			if(is_dir($entryPath))
        				array_push($categoriesArr, $entry);
        			else if(is_file($entryPath) && preg_match("/.png|.jpg|.jpeg|.bmp|.gif/i", $entryPath))
        				array_push($imagesArr, $entry);
    			}
			}
			closedir($handle);
		}
		return array($categoriesArr, $imagesArr);
	}

	private function getCategoryFullPath($categoryPath){
		$categoryFullPath = __dir__.$this->config['publicPath'].$this->config['dataPath'].'/'.$categoryPath;
		return urldecode(mb_convert_encoding($categoryFullPath, 'UTF-8', mb_detect_encoding($categoryFullPath))); //had a problem with arabic names and this fixed it
	}

	private function getImageFullPath($imagePath){
		$imageFullPath = __dir__.$this->config['publicPath'].$this->config['dataPath'].'/'.$imagePath;
		return urldecode(mb_convert_encoding($imageFullPath, 'UTF-8', mb_detect_encoding($imageFullPath)));
	}

	public function showNotFound(){
		$this->renderHeader("غير موجود!");
		echo '404';
		$this->renderFooter('');
	}


	private function renderHeader($title){
		echo "<title>$title</title>";
		echo '</head><body>';
		echo '<div class="page-header container">';
		echo '<div class="page-header"><a href="'.$this->config['projectURI'].'/'.'"><h1><span class="label label-primary">عالم أنس</span></h1></a></div>';

	}

	private function renderCategory($categoryPath, $categoryContent){
		echo '<div class="container col-md-9 col-xs-12">';
		echo '<h3>الصور</h3>';
		if(!empty($categoryContent[1])){
			foreach($categoryContent[1] as $image) {
				echo '<div style="float:right;" class="col-xs-6 col-md-4"><a class="thumbnail" href="'.$this->config['projectURI'].'/'.$categoryPath.$image.'"><img src="'.$this->config['projectURI'].$this->config['dataPath'].'/'.$categoryPath.$image.'"></a></div>';
			}
		}else{
			echo 'لا يوجد صور';
		}
		echo '</div><div class="container col-md-3 col-xs-12">';
		echo '<h3>الأقسام</h3>';
		if(!empty($categoryContent[0])){ //if has categories
			echo '<div class="list-group">';
			foreach($categoryContent[0] as $category) {
				echo '<a class="list-group-item" href="'.$this->config['projectURI'].'/'.$categoryPath.$category.'">'.str_replace('ـ', ' ', str_replace('_', ' ', str_replace('-', ' ', $category))).'</a>';
			}
			echo '</div>';
		}else{
			echo 'لا يوجد أقسام';
		}
		echo '</div>';
	}

	private function renderBreadcrumb($path){
		echo '<ol class="breadcrumb">';
		$path = trim(urldecode($path), '/');
		$urls = $this->config['projectURI'].'/';
		if(empty($path))// if index
			echo '<li><a class="home active" href="'.$urls.'">الرئيسية</a></li>';
		else{
			echo '<li><a class="home" href="'.$urls.'">الرئيسية</a></li>';
			$path = explode('/', $path);
			$counter = 0;
			$len = count($path);
			foreach ($path as $item) {
				$urls .= $item.'/';
				$item = str_replace('ـ', ' ', str_replace('_', ' ', str_replace('-', ' ', $item)));
				if ($counter == $len-1){
					if(strpos($item, '.') !== false)
						echo '<li><a class="active" href="'.$urls.'">'.substr($item, 0, strrpos($item, '.')).'</a></li>';
					else
						echo '<li><a class="active" href="'.$urls.'">'.$item.'</a></li>';
				}else{
					echo '<li><a href="'.$urls.'">'.$item.'</a></li>';
				}
				$counter++;
			}
		}
		echo '</ol>';
	}

	private function renderIndexBox($title, $description){
		echo '<div class="jumbotron">';
  		echo '<h1>'.$title.'</h1>';
		echo '<p>'.$description.'</p>';
		echo '</div>';
	}

	private function renderFooter($footer){
		echo '</div>';
		echo '<div class="container"><h5 class="col-md-12">'.$footer.'</h5></div>';
	}

}
