<?php
session_start();
if(!isset($_SESSION['orderid']))
	{$_SESSION['orderid']=uniqid();}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Document</title>
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href=css/style.css rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
	<script src="js/jquery-3.1.0.min.js"></script>
</head>
<body>
<?php
	include_once("pages/classes.php"); 
 ?>
	
	
				<ul class="nav navbar-nav">
					<li><a href="index.php?menu=1">Catalog</a></li>
					<li><a href="index.php?menu=2">Register</a></li>
					<li><a href="index.php?menu=3">Admin</a></li>
				</ul>

			
		

	

	<div class="container">
		
		<div class="row">

		</div>
		<div class="row">

		</div>
		<div class="row">
			<?php 
			if (isset($_GET["menu"])) {
				$menu=$_GET["menu"];
				
				if ($menu==1) {
					include_once ("pages/catalog.php");
				}
				if ($menu==2) {
					include_once ("pages/register.php");
				}
				if ($menu==3) {
					include_once ("pages/admin.php");
				}
			}
			?>
		</div>
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="js/bootstrap.js"></script>
<script src="js/jquery-ui.js"></script>
</body>
</html>