<?php
/**
* Class Thumbnail
* редактирвоание изображений налету
* @package 
* @subpackage System Plugin
*/
class Thumbnail
{
    private $w;
    private $h;
    private $filename;
    private $crop;
    private $gray;
    
    private $_mime_settings;
    private $_fsave_allowed;
    private $_tname_tpl         = '%s_%sx%s';
    private $_gray_tname_tpl    = 'gray_%s_%sx%s';
    private $_default_width     = 150;
    private $_default_height    = 150;
    private $_jpeg_quality      = 80;  
    
    public function __construct()
    {       
        $this->w = abs((int)@$_GET['w']);
        $this->h = abs((int)@$_GET['h']);
		
        if (!$this->w && !$this->h) {

            $this->w = $this->_default_width;
            $this->h = $this->_default_height;
        }
        $this->filename = @$_GET['name'];
        $this->crop     = isset($_GET['c']) || isset($_GET['tc']);
        $this->gray     = isset($_GET['g']);
        
        $this->_fsave_allowed = ( isset($_GET['ns']) ) ? false : true;
        
        $this->_mime_settings = array(
            'image/gif'  => array(
                'ext'       => '.gif',
                'create'    => 'imagecreatefromgif',
                'save'      => array(&$this, '_gif_save'),
            ),
            'image/jpeg'  => array(
                'ext'       => '.jpg',
                'create'    => 'imagecreatefromjpeg',
                'save'      => array(&$this, '_jpeg_save'),
            ),
            'image/pjpeg'  => array(
                'ext'       => '.jpg',
                'create'    => 'imagecreatefromjpeg',
                'save'      => array(&$this, '_jpeg_save'),
            ),
            'image/png'  => array(
                'ext'       => '.png',
                'create'    => 'imagecreatefrompng',
                'save'      => array(&$this, '_png_save'),
            ),
        );
        
       	$this->_run();
        
    }
    private function _run()
    {
        if (!file_exists($this->filename) || !is_file($this->filename)) exit;
        $info = getimagesize($this->filename);
        if (!$info || !isset($this->_mime_settings[$info['mime']])) {
            exit;
        }
        $settings =& $this->_mime_settings[$info['mime']];
        
        $orig_width  = $info[0];
        $orig_height = $info[1];
        $dst_x = $dst_y = 0;
        
        if (!$this->w) {
            //   
            $new_width  = $this->w = floor($orig_width * $this->h / $orig_height);
            $new_height = $this->h;
        }
        elseif (!$this->h) {
            //   
            $new_width  = $this->w;
            $new_height = $this->h = floor($orig_height * $this->w / $orig_width);
        }
        elseif ($this->crop) {
            //   
            $scaleW = $this->w / $orig_width;
            $scaleH = $this->h / $orig_height;
            $scale = max($scaleW, $scaleH);
            $new_width  = floor($orig_width * $scale);
            $new_height = floor($orig_height * $scale);
            $dst_x = floor(($this->w - $new_width) / 2);
            $dst_y = floor(($this->h - $new_height) / 2);
        }
        else {
            //   
            $scaleW = $this->w / $orig_width;
            $scaleH = $this->h / $orig_height;
            $scale = min($scaleW, $scaleH);
            $new_width  = $this->w = floor($orig_width * $scale);
            $new_height = $this->h = floor($orig_height * $scale);
        }

        $orig_img = call_user_func($settings['create'], $this->filename);
        $tmp_img  = imagecreatetruecolor($this->w, $this->h);     
        $grey = imagecreate($this->w, $this->h);        
		$weiss = ImageColorAllocate($tmp_img, 255, 255, 255);
		ImageFilledRectangle($tmp_img, 0, 0, $this->w, $this->h, $weiss);

		
               
        $thumbFilename = dirname($this->filename) . '/' 
            . sprintf($this->_tname_tpl, basename($this->filename, $settings['ext']), $this->w, $this->h)
            . $settings['ext']
        ;
		
        if( $this->gray ):
        
	        $thumbFilenameGray = dirname($this->filename) . '/' 
	            . sprintf($this->_gray_tname_tpl, basename($this->filename, $settings['ext']), $this->w, $this->h)
	            . $settings['ext']
	        ;
        
	        if (file_exists($thumbFilenameGray) && filemtime($thumbFilenameGray) >= filemtime($this->filename)) {
	            header('Content-type: ' . $info['mime']);
	            readfile($thumbFilenameGray);
	        }else{
		        for ($c=0; $c<256; $c++):
					$palette[$c] = imagecolorallocate($grey, $c, $c, $c);
				endfor;
				for ($y=0; $y<$orig_height; $y++):
				
					for ($x=0; $x<$orig_width; $x++):
					$rgb = imagecolorat($orig_img, $x, $y);
					$r = ($rgb >> 16) & 0xFF;
					$g = ($rgb >> 8) & 0xFF;
					$b = $rgb & 0xFF;
					$gs = ($r*0.299)+($g*0.587)+($b*0.114);
					imagesetpixel($grey, $x, $y, $palette[$gs]);
					endfor;
					
				endfor;	

		        imagecopyresampled(
		            $grey, $orig_img, 
		            $dst_x, $dst_y, 
		            0, 0, 
		            $new_width, $new_height, 
		            $orig_width, $orig_height
		        );	
		        		        
				header('Content-type: ' . $info['mime']);
		        call_user_func($settings['save'], $grey, $thumbFilenameGray);
		        imagedestroy($grey);
	        }       
	        
        else:
        
	        if (file_exists($thumbFilename) && filemtime($thumbFilename) >= filemtime($this->filename)) {
	            header('Content-type: ' . $info['mime']);
	            readfile($thumbFilename);
	        }else{
	        	
	        	if ($info['mime'] == 'image/png') {
					imagealphablending($tmp_img, false);	
	        	}
	        	
		         imagecopyresampled(
		            $tmp_img, $orig_img, 
		            $dst_x, $dst_y, 
		            0, 0, 
		            $new_width, $new_height, 
		            $orig_width, $orig_height
		        ); 

	        	if ($info['mime'] == 'image/png') {
					imagesavealpha($tmp_img, true);     		
	        	}		        
		        
				header('Content-type: ' . $info['mime']);
		        call_user_func($settings['save'], $tmp_img, $thumbFilename);
		        imagedestroy($tmp_img);
	        }
        
        endif;
		imagedestroy($orig_img);  
        
    }
    
    private function _gif_save($img, $filename = false)
    {
        if ($filename !== false && $this->_fsave_allowed):
			imagegif($img, $filename);
		endif;
		imagegif($img);
    }
    
    private function _jpeg_save($img, $filename = false)
    {
        if ($filename !== false && $this->_fsave_allowed):
			imagejpeg($img, $filename, $this->_jpeg_quality);
		endif;
		imagejpeg($img);
    }
    
    private function _png_save($img, $filename = false)
    {

		//$white = imagecolorallocate($img, 255, 255, 255);
		//imagecolortransparent($img, $white);    	
    	
        if ($filename !== false && $this->_fsave_allowed):
        	imagepng($img, $filename);
		endif;
		imagepng($img);
    }    
}
new Thumbnail;

?>