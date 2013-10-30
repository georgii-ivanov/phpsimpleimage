<?php
        //prints resized image with header
        
	include "SimpleImage.php";
	
	// resize image example
	
	$img = new SimpleImage();
	
	$img->loadFile('forest.png')->resizeTo(300, 200)->output();
	
	//drawing example
	
	$drawing = new SimpleDrawing();
	
	$drawing->loadFile('forest.png')->resizeTo(300, 200)->setColor(array(255,0,0))->line(0, 0, 300, 200)->output();
?>
