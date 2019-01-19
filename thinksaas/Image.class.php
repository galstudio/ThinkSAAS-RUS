<?php

class Image {
	static public function buildImageVerify($width = 48, $height = 22, $randval = NULL, $verifyName = 'verify') {
		if (!isset($_SESSION)) {
			session_start();
			//session
		}
		$randval = empty($randval) ? ("" . rand(1000, 9999)) : $randval;
		$_SESSION[$verifyName] = $randval;
		$length = 4;
		$width = ($length * 10 + 10) > $width ? $length * 10 + 10 : $width;
		$im = imagecreate($width, $height);
		$r = array(225, 255, 255, 223);
		$g = array(225, 236, 237, 255);
		$b = array(225, 236, 166, 125);
		$key = mt_rand(0, 3);
		$backColor = imagecolorallocate($im, $r[$key], $g[$key], $b[$key]);
		$borderColor = imagecolorallocate($im, 100, 100, 100);
		$pointColor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));

		@imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
		@imagerectangle($im, 0, 0, $width - 1, $height - 1, $borderColor);
		$stringColor = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
		for ($i = 0; $i < 10; $i++) {
			$fontcolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagearc($im, mt_rand(-10, $width), mt_rand(-10, $height), mt_rand(30, 300), mt_rand(20, 200), 55, 44, $fontcolor);
		}
		for ($i = 0; $i < 25; $i++) {
			$fontcolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $pointColor);
		}
		for ($i = 0; $i < $length; $i++) {
			imagestring($im, 5, $i * 10 + 5, mt_rand(1, 8), $randval{$i}, $stringColor);
		}
		self::output($im, 'png');
	}

	static public function thumb($image, $thumbname, $domain = 'public', $maxWidth = 200, $maxHeight = 50, $interlace = true) {
		$info = self::getImageInfo($image);
		if ($info !== false) {
			$srcWidth = $info['width'];
			$srcHeight = $info['height'];
			$type = strtolower($info['type']);
			$interlace = $interlace ? 1 : 0;
			unset($info);
			$scale = min($maxWidth / $srcWidth, $maxHeight / $srcHeight);

			if ($scale >= 1) {
				$width = $srcWidth;
				$height = $srcHeight;
			} else {
				$width = (int)($srcWidth * $scale);
				$height = (int)($srcHeight * $scale);
			}
			//sae
			if (class_exists('SaeStorage')) {
				$saeStorage = new SaeStorage();
				$saeImage = new SaeImage();
				$saeImage -> setData(file_get_contents($image));
				$saeImage -> resize($width, $height);
				$thumbname = str_replace(array('../', './'), '', $thumbname);
				return $saeStorage -> write($domain, $thumbname, $saeImage -> exec());
			}

			$createFun = 'ImageCreateFrom' . ($type == 'jpg' ? 'jpeg' : $type);
			$srcImg = $createFun($image);

			if ($type != 'gif' && function_exists('imagecreatetruecolor')) {
				$thumbImg = imagecreatetruecolor($width, $height);
			} else {
				$thumbImg = imagecreate($width, $height);
			}
			if (function_exists("ImageCopyResampled")) {
				imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);
			} else {
				imagecopyresized($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);
			}
			if ('gif' == $type || 'png' == $type) {
				$background_color = imagecolorallocate($thumbImg, 0, 255, 0);
				imagecolortransparent($thumbImg, $background_color);
			}
			// jpeg
			if ('jpg' == $type || 'jpeg' == $type) {
				imageinterlace($thumbImg, $interlace);
			}
			$dir = dirname($thumbname);
			if (!is_dir($dir)) {
				@mkdir($dir, 0777, true);
			}
			$imageFun = 'image' . ($type == 'jpg' ? 'jpeg' : $type);
			$imageFun($thumbImg, $thumbname);
			imagedestroy($thumbImg);
			imagedestroy($srcImg);
			return $thumbname;
		}
		return false;
	}

	/**
	 * @$image
	 * @$water
	 * @$$waterPos
	 */
	static public function water($image, $water, $waterPos = 9) {
		if (!file_exists($image) || !file_exists($water))
			return false;
		$imageInfo = self::getImageInfo($image);
		$image_w = $imageInfo['width'];
		$image_h = $imageInfo['height'];
		$imageFun = "imagecreatefrom" . $imageInfo['type'];
		$image_im = $imageFun($image);
		$waterInfo = self::getImageInfo($water);
		$w = $water_w = $waterInfo['width'];
		$h = $water_h = $waterInfo['height'];
		$waterFun = "imagecreatefrom" . $waterInfo['type'];
		$water_im = $waterFun($water);

		switch ($waterPos) {
			case 0 :
				$posX = rand(0, ($image_w - $w));
				$posY = rand(0, ($image_h - $h));
				break;
			case 1 :
				//1
				$posX = 0;
				$posY = 0;
				break;
			case 2 :
				//2
				$posX = ($image_w - $w) / 2;
				$posY = 0;
				break;
			case 3 :
				//3
				$posX = $image_w - $w;
				$posY = 0;
				break;
			case 4 :
				//4
				$posX = 0;
				$posY = ($image_h - $h) / 2;
				break;
			case 5 :
				//5
				$posX = ($image_w - $w) / 2;
				$posY = ($image_h - $h) / 2;
				break;
			case 6 :
				//6
				$posX = $image_w - $w;
				$posY = ($image_h - $h) / 2;
				break;
			case 7 :
				//7
				$posX = 0;
				$posY = $image_h - $h;
				break;
			case 8 :
				//8
				$posX = ($image_w - $w) / 2;
				$posY = $image_h - $h;
				break;
			case 9 :
				//9
				$posX = $image_w - $w;
				$posY = $image_h - $h;
				break;
			default :
				$posX = rand(0, ($image_w - $w));
				$posY = rand(0, ($image_h - $h));
				break;
		}
		imagealphablending($image_im, true);
		imagecopy($image_im, $water_im, $posX, $posY, 0, 0, $water_w, $water_h);
		$bulitImg = "image" . $imageInfo['type'];
		$bulitImg($image_im, $image);
		$waterInfo = $imageInfo = null;
		imagedestroy($image_im);
	}

	static protected function getImageInfo($img) {
		$imageInfo = getimagesize($img);
		if ($imageInfo !== false) {
			$imageType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
			$imageSize = filesize($img);
			$info = array("width" => $imageInfo[0], "height" => $imageInfo[1], "type" => $imageType, "size" => $imageSize, "mime" => $imageInfo['mime']);
			return $info;
		} else {
			return false;
		}
	}

	static protected function output($im, $type = 'png', $filename = '') {
		header("Content-type: image/" . $type);
		$ImageFun = 'image' . $type;
		if (empty($filename)) {
			$ImageFun($im);
		} else {
			$ImageFun($im, $filename);
		}
		imagedestroy($im);
		exit ;
	}

}
?>