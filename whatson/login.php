<?php
	include 'config/conn.php';
	//echo $_SERVER['PHP_SELF'];
?>
<!DOCTYPE html>
<html>
<head>
	<title>WHATSON</title>
	<?php include 'links/link-css.php';?>
	<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
	<?php include 'template/header.php';?>
	<?php
		include 'config/conn.php';

		if(isset($_POST['loginSubmit'])){
			$username = htmlspecialchars($_POST['username']);
			$password = htmlspecialchars($_POST['password']);

			$sql = 'SELECT * FROM users WHERE username=?';

			if($stmt= $conn->prepare($sql)){
				$stmt->bind_param('s', $username);

				$stmt->execute();
      			$result = $stmt->get_result();

      			if(mysqli_num_rows($result)!=0){
      				$user = $result->fetch_assoc();
      				if($password!=$user['password']){
      					echo 'Password ot match';
      				}else{
      					$_SESSION['login'] = 'y';
						$_SESSION['name'] = $user['name'];
						$_SESSION['username'] = $user['username'];
						$_SESSION['topicspath'] = $user['topicspath'];
						header("location: about.php");
      				}
      			}else{
      				echo 'User not exists';
      			}
			}
		}
	?>
	<p>Welcome back!<br><a id="register" href="register.php">Don't have an account? Register here.</a></p>
	<form method="post" action="login.php">
		<label for="username">Username: </label>
		<input type="text" name="username" id="username">
		<label for="password">Password:</label>
		<input type="password" name="password" id="password">
		<input type="submit" name="loginSubmit" id="loginSubmit">
	</form>
	<?php include 'links/link-js.php';?>
</body>
</html>