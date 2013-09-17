<?php
        //prints resized image with header
        
	include "SimpleImage.php";
	
	$img = new SimpleImage('filename' => 'forest.png');
	
	$img->resizeTo(300, 200)->output();
?>
