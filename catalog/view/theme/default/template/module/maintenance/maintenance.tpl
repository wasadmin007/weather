<!DOCTYPE html>
<html>
<head>
<title>We are under maintenance. Please try again later.</title>
<link href="<?php echo $this->data['css']; ?>" type="text/css" rel="stylesheet">
</head>
<body>
<div class="wrapper">
<?php
if($this->data['logo'] != ''){ ?>
	<a href="index.php"><img src="<?php echo $this->data['logo']; ?>" title="<?php echo $this->data['store']; ?>" alt="<?php echo $this->data['store']; ?>" border="0" /></a>
<?php } ?>
<br /><br />
<?php
echo $message;
?>
</div>
</body>
</html>