<?php 
require_once 'includes/db_connect.php';
session_start();
if(isset($_SESSION['userId'])) {
	header('location:'.$store_url.'dashboard.php');		
}
$errors = array();
if($_POST) {		
	$username = $_POST['username'];
	$password = $_POST['password'];
	if(empty($username) || empty($password)) {
		if($username == "") {
			$errors[] = "Username is required";
		} 
		if($password == "") {
			$errors[] = "Password is required";
		}
	} else {
		$sql = "SELECT * FROM users WHERE username = '$username'";
		$result = $connect->query($sql);
		if($result->num_rows == 1) {
			$password = md5($password);
			$mainSql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
			$mainResult = $connect->query($mainSql);
			if($mainResult->num_rows == 1) {
				$value = $mainResult->fetch_assoc();
				$user_id = $value['user_id'];
				$_SESSION['userId'] = $user_id;
				header('location:'.$store_url.'dashboard.php');	
			} else{				
				$errors[] = "Incorrect username/password combination";
			}
		} else {		
			$errors[] = "Username doesnot exists";		
		} 
	} 	
} 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Stock Management System</title>
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
  	<link rel="stylesheet" href="assets/css/style.css?v=<?=time()?>">
	<script src="assets/jquery/jquery.min.js"></script>
 	<link rel="stylesheet" href="assets/jquery-ui/jquery-ui.min.css">
  	<script src="assets/jquery-ui/jquery-ui.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="row vertical">
			<div class="col-md-5 col-md-offset-4">
				<div class="login-logo">
					<img src="assets/images/mylogo.png" />
				</div>   
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Sign in</h3>
					</div>
					<div class="panel-body">
						<div class="messages">
							<?php if($errors) {
								foreach ($errors as $key => $value) {
									echo '<div class="alert alert-warning" role="alert">
									<i class="glyphicon glyphicon-exclamation-sign"></i>
									'.$value.'</div>';										
									}
								} ?>
						</div>
						<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="loginForm">
							<fieldset>
							  <div class="form-group">
									<label for="username" class="col-sm-2 control-label">Username</label>
									<div class="col-sm-10">
									  <input type="text" class="form-control" id="username" name="username" placeholder="Username" autocomplete="off" />
									</div>
								</div>
								<div class="form-group">
									<label for="password" class="col-sm-2 control-label">Password</label>
									<div class="col-sm-10">
									  <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off" />
									</div>
								</div>								
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
									  <button type="submit" class="btn btn-default"> <i class="glyphicon glyphicon-log-in"></i> Sign in</button>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>







	