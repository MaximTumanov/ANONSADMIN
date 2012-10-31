<?php
/**
* класс для создание каптчи-изображения
* @example <img align="left" src="path_to_this_file.php?text=текст_который_будет_на_изображении" />
*/
class Captcha{
	/**
	* var int $w ширина картинки в px
	*/
	private $w = 100;
	/**
	* var int $h высота картинки в px
	*/
	private $h = 43;
	/**
	* var string $text текст, который будет помещен на каптчу
	*/
	public $text = 1234;
	/**
	* var string $font шрифт для текста
	*/	
	private $font = 'font_1.ttf';
	
	public function __construct()
	{
		$text = $_GET['text'];
		
		$a    = substr($text, 0, 1);
		$b    = substr($text, 1, 1);
		$c    = substr($text, 2, 1);
		$d    = substr($text, 3, 1);
		
		$img = imagecreatetruecolor($this->w, $this->h);
		$white = imagecolorallocate($img, 255, 255, 255);
		$black = imagecolorallocate($img, 0, 0, 0);
		$gray  = imagecolorallocate($img, 51, 51, 51);
		
		imagefilledrectangle($img, 0, 0, $this->w, $this->h, $gray);
		
		$red    = imagecolorallocate($img, 250, 140, 140);
		$blue   = imagecolorallocate($img, 64, 73, 140);
		$yellow = imagecolorallocate($img, 242, 211, 97);
			
		imagettftext($img, 16, rand(0, 15),  10,  32, $white, $this->font, $a );
		imagettftext($img, 20, rand(0, 25),  35, 32, $white, $this->font, $b );
		imagettftext($img, 18, rand(10, 25), 55, 32, $white, $this->font, $c );
		imagettftext($img, 16, rand(0, -15), 75, 32, $white, $this->font, $d );		
		
		imageline($img, 10, 10, 30, 50, $yellow);
		imageline($img, 20, 18, 37, 52, $red);
		imageline($img, 38, 46, 80, 55, $red);
		imageline($img, 70, 34, 10, 43, $blue);
		imageline($img, ($this->w - 5), 0,  10, 20, $blue);
		imageline($img, 50, 34, 0,  80, $blue);
		imageline($img, ($this->w - 5), 15, 5,  30, $red);
		imageline($img, ($this->w - 5), 30, 20, 43, $yellow);
		imageline($img, 30, 0,  ($this->w - 5), 43, $yellow);
		

		header('Content-type: image/jpeg');
		imagejpeg($img, '', 100);
		imagedestroy($img);
	}
}
new Captcha;
?>