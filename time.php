<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<ol>
  <li>2005-07-19T19:00:00Z/PT01H00M</li>
  <li>2005-07-19T19:00:00</li>
</ol>
<p>&nbsp;</p>
<ol>
  <li><?php echo strtotime("2005-07-19T19:00:00Z/PT01H00M");?></li>
  <li><?php echo strtotime("2005-07-19T19:00:00");?></li>
</ol>
<ol>
  <li><?php echo date("l jS F Y H:i", strtotime("2005-07-19T19:00:00Z/PT01H00M"));?></li>
  <li><?php echo date("l jS F Y H:i", strtotime("2005-07-19T19:00:00"));?></li>
</ol>
</body>
</html>
