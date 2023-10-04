<?php

namespace App\Components\Admin;
use DB;
class ImageManager {
    private static $instance = null;
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new ImageManager();
		}
		return self::$instance;
	}

	/* public function getImageByImageId($main_image_id, $type, $path)
	{
		if($main_image_id > 0)
		{
			$path = $path. DIRECTORY_SEPARATOR .$main_image_id;
			return $imageObj = $this->getImageById($main_image_id, $type);
		}
		return 'public' . DIRECTORY_SEPARATOR . 'noimage' . DIRECTORY_SEPARATOR . $this->getImageTypeDir($type) . DIRECTORY_SEPARATOR . 'noimage.png';
    } */
	
	public function uploadImage($image, $path, $id = null, $type = 'product')
	{
		$rand = rand(1, 150).rand(700, 9999);
		$extension = $image->getClientOriginalExtension();
		$filename = $rand.'.'.$extension;
		if($type == 'product')
		{
			$imageSizes = $this->setSizes($type);
			//echo '<pre>'; print_r($imageSizes); die;
			foreach($imageSizes as $key => $size)
			{
				//$imagesendfolderpath = $path. DIRECTORY_SEPARATOR .$id. DIRECTORY_SEPARATOR .$this->getImageTypeDir($key);
				$imagesendfolderpath = $path. DIRECTORY_SEPARATOR .$id;
				$this->setSingleImage($this->getImageTypeDir($key).'_'.$filename, $image, $imagesendfolderpath, $size);
			}
			return $filename;
		}
		return false;
	}
	
	public function setSingleImage($filename, $imageObj, $path, $size = array(), $quality = 100) {
		/* if(!\File::isDirectory($path)){
			\File::makeDirectory($path, 0777, true, true);
		} */
		//    "message": "GD Library extension not available with this PHP installation.", ;extension=gd to extension=gd

		if($size[1] == FILE_ORIGINAL)
		{
			$image_resize = \Intervention\Image\ImageManagerStatic::make($imageObj->getRealPath())->encode();
			$image_resize->save($path .DIRECTORY_SEPARATOR .$filename,100);
			return $filename;
		}
		$image_resize = \Intervention\Image\ImageManagerStatic::make($imageObj->getRealPath())->resize($size[0], $size[1])->encode();
		$image_resize->save($path .DIRECTORY_SEPARATOR .$filename,80);
		return $filename;
	}
	
	public function getImageTypeDir($type) {
		
		switch($type) {
			case FILE_PRODUCT_HOME_LIST:
				return 'home';
			break;
			
			case FILE_PRODUCT_DETAIL:
				return 'detail';
			break;
			
			case FILE_PRODUCT_CAROUSEL:
				return 'caro';
			break;
			
			case FILE_PRODUCT_CART:
				return 'cart';
			break;
			
			case FILE_ORIGINAL:
				return 'orig';
			break;
		}
	}
	
	public function setSizes($type = 'product') {
		$types = array();		
		// array(width, height)
		$types[FILE_ORIGINAL] = array(FILE_ORIGINAL, FILE_ORIGINAL);
		if($type == 'product' || $type == 'noimage')
		{
			$types[FILE_PRODUCT_HOME_LIST] = array(170, 270);
			$types[FILE_PRODUCT_CAROUSEL] = array(162, 131);
			$types[FILE_PRODUCT_DETAIL] = array(600, 500);
			$types[FILE_PRODUCT_CART] = array(48, 48);
		}
		return $types;
	}
	
	public function uploadNoImage($image)
	{
		$filename = 'noimage.'.$image->getClientOriginalExtension();
		$imageSizes = $this->setSizes('noimage');
		$isUpload = false;
		foreach($imageSizes as $key => $size)
		{
			$isUpload = true;
			$imagesendfolderpath = 'noimage' . DIRECTORY_SEPARATOR .$this->getImageTypeDir($key);
			$filename = $this->setSingleImage($filename, $image, $imagesendfolderpath, $size);
		}
		return $isUpload;
	}
}
