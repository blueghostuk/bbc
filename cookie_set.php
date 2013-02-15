<?php
	switch ($_GET['op']){
	case 'remove':
		setcookie ("icons", 	"", 	time()- (365 * 86400));
		return 1;
	break;
	case 'icons':
	default:
		if ($_GET['value'] == 2)
			setcookie ("icons", 	"true", 	time()+ (365 * 86400));
		else
			setcookie ("icons", 	"false", 	time()+ (365 * 86400));
		return 1;
	break;
	}
?>