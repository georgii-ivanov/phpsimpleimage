<?php
/**
* SimpleDrawing class.
*
* @author Ivanov Georgiy <xercool1@gmail.com>
* @copyright 2013
* @license GPL
* @package component
*/

class SimpleDrawing extends SimpleImage
{
    private $color = array(0,0,0,0);
    
    /**
     * Get current color info
     * 
     * @return type
     */
    
    public function getColor()
    {
        return $this->color;
    }
    
    /**
     * Get gd color from color
     * 
     * @return type
     */
    
    public function getGDColor() 
    {
        @list($red, $green, $blue, $alpha) = $this->color;
        return ($alpha == 0) ? 
            imagecolorallocate($this->image, $red, $green, $blue) : 
            imagecolorallocatealpha($this->image, $red, $green, $blue, $alpha);
    }
    
    /**
     * Set current color
     * 
     * @param array $color
     * @return \SimpleDrawing
     */
    
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }
    
    /**
     * Draw line from $x1, $y2 to $x2, $y2
     * 
     * @param type $x1
     * @param type $y1
     * @param type $x2
     * @param type $y2
     * @return \SimpleDrawing
     */
    
    public function line($x1, $y1, $x2, $y2) 
    {
        imageline($this->image, $x1, $y1, $x2, $y2, $this->getGDColor($this->color));
        return $this;
    }
    
    /**
     * Draw Rect from $x1, $y1 on $w, $h
     * 
     * @param type $x1
     * @param type $y1
     * @param type $w
     * @param type $h
     * @return \SimpleDrawing
     */
    
    public function rect($x1, $y1, $w, $h) 
    {
        imagerectangle($this->image, $x1, $y1, $x1+$w, $y1+$h, $this->getGDColor($this->color));
        return $this;
    }
    
    /**
     * Fill rectangle from $x1, $y2 on $w, $h
     * 
     * @param type $x1
     * @param type $y1
     * @param type $w
     * @param type $h
     * @return \SimpleDrawing
     */
    
    public function filledRect($x1, $y1, $w, $h) 
    {
        imagefilledrectangle($this->image, $x1, $y1, $x1+$w, $y1+$h, $this->getGDColor($this->color));
        return $this;
    }
    
    /**
     * 
     * Draw ellipse $x, $y to $w, $h
     * 
     * @param type $x
     * @param type $y
     * @param type $w
     * @param type $h
     * @return \SimpleDrawing
     */
    
    public function ellipse($x, $y, $w, $h)
    {
        imageellipse($this->image, $x, $y, $w, $h, $this->getGDColor($this->color));
        return $this;
    }
    
    /**
     * Draw filled ellispse
     * 
     * @param type $x
     * @param type $y
     * @param type $w
     * @param type $h
     * @return \SimpleDrawing
     */
    
    public function filledEllipse($x, $y, $w, $h)
    {
        imagefilledellipse($this->image, $x, $y, $w, $h, $this->getGDColor($this->color));
        return $this;
    }
    
    /**
     * Draw circle
     * 
     * @param type $x
     * @param type $y
     * @param type $w
     * @return type
     */
    
    public function circle($x, $y, $w)
    {
        return $this->ellipse($x, $y, $w, $w);
    }
    
    /**
     * Draw filled circle
     * 
     * @param type $x
     * @param type $y
     * @param type $w
     * @return type
     */
    
    public function filledCircle($x, $y, $w)
    {
        return $this->filledEllipse($x, $y, $w, $w);
    }
    
    /**
     * Fill image by rectangle
     * 
     * @return type
     */
    
    public function fill($x = 0, $y = 0) 
    {
        return $this->filledRect($x, $y, $this->getWidth(), $this->getHeight());
    }
    
    /**
     * Get pixel color
     * 
     * @return type
     */
    
    public function getPixel($x, $y) 
    {
        return array_values(imagecolorsforindex($this->image, imagecolorat($this->image, $x, $y)));
    }
    
    /**
     * Create new GD image
     * 
     * @param type $width
     * @param type $height
     * @return \SimpleDrawing
     */
    
    public function create($width, $height) 
    {
        $img = imagecreatetruecolor($width, $height); 
        $this->load($img);
        return $this;
    }
}
?>
