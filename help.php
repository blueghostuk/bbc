<?php 
	if (!$_GET['help_page'])
		require('help/index.php');
	else
		require('help/'.$_GET['help_page'].'.php');
	if (!$changeWidth)
	{
		$x 	= 600;
		$y	= 600;
	}
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Help</title>
<link href="help.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	window.resizeTo(<?php echo $x;?>,<?php echo $y;?>);
</script>
</head>

<body>
<div id="site">
	<div id="title"><?php echo $title;?></div>
	<div id="content"><?php echo $content;?></div>
	<div id="links">
		<div id="l_title">Other Links</div>
		<div id="l_links"><?php echo $links;?></div>
	</div>
</div>
</body>
</html>
