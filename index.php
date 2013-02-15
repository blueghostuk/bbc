<?php
	//redirect
	if ($_COOKIE['type'] && !$_GET['sidebar']){
		if ($_COOKIE['type'] == 'sidebar')
			header("Location: http://bbc.blueghost.co.uk/site4.php");
		else
			header("Location: http://bbc.blueghost.co.uk/site4.php");
	}else{
		header("Location: http://bbc.blueghost.co.uk/site4.php");
	}
?>