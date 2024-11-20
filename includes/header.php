<?php require_once 'core.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<title>Stock Management System</title>
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/css/style.css?v=<?=time()?>">
  <link rel="stylesheet" href="assets/jquery-ui/jquery-ui.min.css">
	<script src="assets/jquery/jquery.min.js"></script>  
  <script src="assets/jquery-ui/jquery-ui.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
	<nav class="navbar navbar-default navbar-static-top">
		<div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
	    <a class="navbar-brand" href="#" style="padding:0px;">
          <img src="assets/images/mylogo.png" alt="">
      </a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
      	<li id="navDashboard"><a href="index.php"><i class="glyphicon glyphicon-list-alt"></i>  Dashboard</a></li>        
      	<?php if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
          <li id="navProduct">
            <a href="product.php"> <i class="glyphicon glyphicon-hdd"></i> Products </a>
          </li> 
        <?php } ?>		      
        <li class="dropdown" id="navSetting">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> 
            <i class="glyphicon glyphicon-user"></i> <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li id="topNavLogout"><a href="logout.php"> <i class="glyphicon glyphicon-log-out"></i> Logout</a></li>            
            </ul>
        </li>                   
      </ul>
    </div>
  </div>
	</nav>
	<div class="container">