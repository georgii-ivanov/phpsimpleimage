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
    const RGB_COLOR = 0;
    const HSV_COLOR = 1;
    
    private $color = array(0,0,0);
    private $mode = SimpleDrawing::RGB_COLOR; //future support, use convert method
    
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
        list($red, $green, $blue) = $this->color;
        return imagecolorallocate($this->image, $red, $green, $blue);
    }
    
    /**
     * Convert hsv to rgb color
     * 
     * @param type $color
     * @return type
     */
    
    public function hsv2rgb($color) 
    {
        list($h, $s, $v) = $color;
        $s /= 256.0;
        if ($s == 0.0) return array($v,$v,$v);
        $h /= (256.0 / 6.0);
        $i = floor($h);
        $f = $h - $i;
        $p = (integer)($v * (1.0 - $s));
        $q = (integer)($v * (1.0 - $s * $f));
        $t = (integer)($v * (1.0 - $s * (1.0 - $f)));
        switch($i) {
        case 0: return array($v,$t,$p);
        case 1: return array($q,$v,$p);
        case 2: return array($p,$v,$t);
        case 3: return array($p,$q,$v);
        case 4: return array($t,$p,$v);
        default: return array($v,$p,$q);
        }
    }
    
    /**
     * Convert rgb to hsv color mode
     * 
     * @param type $color
     * @return type
     */
    
    public function rgb2hsv($color) 
    {
        list($r,$g,$b)=$color; 
        $v=max($r,$g,$b); 
        $t=min($r,$g,$b); 
        $s=($v==0)?0:($v-$t)/$v; 
        if ($s==0) 
         $h=-1; 
        else { 
         $a=$v-$t; 
         $cr=($v-$r)/$a; 
         $cg=($v-$g)/$a; 
         $cb=($v-$b)/$a; 
         $h=($r==$v)?$cb-$cg:(($g==$v)?2+$cr-$cb:(($b==$v)?$h=4+$cg-$cr:0)); 
         $h=60*$h; 
         $h=($h<0)?$h+360:$h; 
        } 
        return array($h,$s,$v); 
    }
    
    /**
     * Set current color
     * 
     * @param type $color
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
