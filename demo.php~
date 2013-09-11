<?php
	include "SimpleImage.php";
	
	$img = new SimpleImage('filename' => 'forest.png');
	
	header('Content-Type: '.$img->loadFile()->getType());
	
	$img->resizeTo(300, 200)->output();
?>
