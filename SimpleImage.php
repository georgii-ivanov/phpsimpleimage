<?php
/**
* SimpleImage class.
*
* @author Ivanov Georgiy <xercool1@gmail.com>
* @copyright 2013
* @license GPL
* @package component
*/

class SimpleImage 
{
	private $image;
        private $source;
	private $type = IMAGETYPE_JPEG;
	private $quality = 80;
	private $filename = null;
	private $save_alpha = true;
	private $alpha_blending = false;

        /**
         * Initializes object
         * 
         * @param type $config
         */
        
	public function __construct($config = array())
	{
		foreach($config as $k=>$v) {
			$this->$k = $v;
		}
	}
        
        /**
         * Create new image $width x $height
         * 
         * @param type $width
         * @param type $height
         * @return \SimpleImage
         */
	
	public function create($width, $height) 
	{
		$img = imagecreatetruecolor($width, $height); 
		imagecolortransparent($img, imagecolorallocate($img, 0, 0, 0));
		imagealphablending($img, $this->alpha_blending);
		imagesavealpha($img, $this->save_alpha);
		
		$this->image = $img;
		
		return $this;
	}
        
        /**
         * Loads image from file
         * 
         * @return \SimpleImage
         * @throws Exception
         */

	public function loadFile() 
	{
		$filename = $this->filename;
		$image_info = getimagesize($filename);

		$this->filename = $filename;
		if (!file_exists($filename))
			throw new Exception("Image file doesn't exists");
		
		$this->type = $image_info[2];
		$this->image = imagecreatefromstring(file_get_contents($filename));
		
		if (!$this->image)
			throw new Exception("Cannot create image from file. Invalid file data");
		
		imagecolortransparent($this->image, imagecolorallocate($this->image, 0, 0, 0));
		imagealphablending($this->image, $this->alpha_blending);
		imagesavealpha($this->image, $this->save_alpha);
		
		return $this;
	}
        
        /**
         * Loads image from binary code
         * 
         * @return \SimpleImage
         * @throws Exception
         */
        
        public function loadSource()
        {
               $source = $this->source;
               $image_info = getimagesizefromstring($source);
               
               $this->filename = null;
               
               $this->type = $image_info[2];
               
               $this->type = $image_info[2];
	       $this->image = imagecreatefromstring($source);
               
               if (!$this->image)
			throw new Exception("Cannot create image from source. Invalid source");
               
               imagecolortransparent($this->image, imagecolorallocate($this->image, 0, 0, 0));
	       imagealphablending($this->image, $this->alpha_blending);
	       imagesavealpha($this->image, $this->save_alpha);
               
	       return $this;
        }
        
        /**
         * Save image to filename
         * 
         * @return \SimpleImage
         * @throws Exception
         */

	public function save() 
	{
		if( $this->type == IMAGETYPE_JPEG ) {
			imagejpeg($this->image,$this->filename,$this->quality);
		} elseif( $this->type == IMAGETYPE_GIF ) {
			imagegif($this->image,$this->filename); 
		} elseif( $this->type == IMAGETYPE_PNG ) {
			imagepng($this->image,$this->filename);
		} else {
			throw new Exception("Unknown save filetype format");
		}
		
		return $this;
	}
        
        /**
         * Output binary image
         * 
         * @return \SimpleImage
         * @throws Exception
         */

	public function output() 
	{
                header('Content-Type: '.$this->getType());
                
		if( $this->type == IMAGETYPE_JPEG ) {
			imagejpeg($this->image, null, $this->quality);
		} elseif( $this->type == IMAGETYPE_GIF ) {
			imagegif($this->image); 
		} elseif( $this->type == IMAGETYPE_PNG ) {
			imagepng($this->image);
		} else {
			throw new Exception("Unknown output filetype format");
		}
		
		return $this;
	}
        
        /**
         * Get image width
         * 
         * @return int
         */

	public function getWidth() 
	{
		return imagesx($this->image);
	}
        
        /**
         * Get image height
         * 
         * @return int
         */

	public function getHeight() 
	{
		return imagesy($this->image);
	}
        
        /**
         * Get image mime-type
         * 
         * @return string
         */
        
        public function getType() 
        {
                 return image_type_to_mime_type($this->type);
        }

	/**
         * Get image GD object
         * 
         * @return gd resource
         */
        
        public function getGD() 
        {
                 return $this->image;
        }
        
        /**
         * Get image source from GD object without ouput uses output buffering
         * 
         * @return string
         */
        
        public function getSource() 
        {
                 ob_start();
                 $this->output();
                 return ob_end_clean();
        }
        
        /**
         * Resize image by height $height px
         * 
         * @param type $height
         * @return \SimpleImage
         */

	public function resizeToHeight($height) 
	{
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width,$height);
		
		return $this;
	}
        
        /**
         * Resize image by width $width px
         * 
         * @param type $width
         * @return \SimpleImage
         */

	public function resizeToWidth($width) 
	{
		$ratio = $width / $this->getWidth();
		$height = $this->getHeight() * $ratio;
		$this->resize($width,$height);
		
		return $this;
	}
        
        /**
         * Scale image ratio $scale
         * 
         * @param float $scale
         * @return \SimpleImage
         */

	public function scale($scale) 
	{
		$width = $this->getWidth() * $scale/100;
		$height = $this->getHeight() * $scale/100; 
		$this->resize($width,$height);
		
		return $this;
	}
        
        /**
         * Resize image absolutely on $width x $height
         * 
         * @param int $width
         * @param int $height
         * @return \SimpleImage
         */

	public function resize($width,$height) 
	{
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image; 
		
		return $this;
	}
        
        /**
         * Cut rectangle from image from $x x $y px position on $width x $height px
         * 
         * @param int $x
         * @param int $y
         * @param int $width
         * @param int $height
         * @return \SimpleImage
         */

	public function cut($x, $y, $width, $height)
	{
		$new_image = imagecreatetruecolor($width, $height);
		imagecopy($new_image, $this->image, 0, 0, $x, $y, $width, $height);

		$this->image = $new_image;
		
		return $this;
	}
        
        /**
         * Resize image to bbox on $width x $height pixels (optional resize)
         * 
         * @param int $width
         * @param int $height
         * @return \SimpleImage
         */

	public function resizeTo($width, $height){
		$height = $height ? $height : $width;

		if($this->getWidth() > $width){
			$this->resizeToWidth($width);
		}
		if($this->getHeight() > $height){
			$this->resizeToheight($height);
		}
		
		return $this;
	}

        /**
         * Fit image to bbox on $width x $height pixels (optional resize and cut).
         * 
         * @param type $width
         * @param type $height
         * @return \SimpleImage
         */
        
	public function fit($width, $height)
	{
		$ratio = $width/$this->getWidth();
		$b_ratio = $height/$this->getHeight();

		if ($ratio > $b_ratio) {
			$this->resizeToWidth($width);
		} else {
			$this->resizeToHeight($height);
		}

		$this->center($width, $height);
		
		return $this;
	}
        
        /**
         * Cut image from center on $width x $height pixels.
         * 
         * @param int $width
         * @param int $height
         * @return \SimpleImage
         */

	public function center($width, $height)
	{
		$x = ($this->getWidth() / 2) - ($width / 2);
		$y = ($this->getHeight() / 2) - ($height / 2);

		$this->cut($x, $y, $width, $height);
		
		return $this;
	}
        
        /**
         * Get image source and display
         * 
         * @return string
         */
        
        public function __toString() {
                return $this->getSource();
        }
}
?>
