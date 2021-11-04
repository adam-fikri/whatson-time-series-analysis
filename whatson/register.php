<!DOCTYPE html>
<html>
<head>
	<title>WHATSON</title>
	<?php include 'links/link-css.php';?>
	<link rel="stylesheet" type="text/css" href="css/register.css">
</head>
<body>
	<?php include 'template/header.php';?>
	<?php
		include 'config/conn.php';
		if(isset($_POST['regSubmit'])){
			$username = htmlspecialchars($_POST['username']);
			$name = htmlspecialchars($_POST['name']);
			$password = htmlspecialchars($_POST['password']);
			$topicsPath = 'users/'.$username;

			if(file_exists($topicsPath)){
				unlink($topicsPath);
			}

			mkdir($topicsPath);

			$sql = 'INSERT INTO users SET username=?, name=?, password=?, topicspath=?';

			if($stmt = $conn->prepare($sql)){
				$stmt->bind_param('ssss', $username, $name, $password, $topicsPath);
				$stmt->execute();
				//session_start();

				$_SESSION['login'] = 'y';
				$_SESSION['name'] = $name;
				$_SESSION['username'] = $username;
				$_SESSION['topicspath'] = $topicsPath;
				header("location: about.php");
			}
		}
	?>
	<p>Welcome new user!<br><a id="login" href="login.php">Have an account? Go to login.</a></p>
	<form method="post" action="register.php">
		<label for="username">Username: </label>
		<input type="text" name="username" id="username">
		<label for="name">Name: </label>
		<input type="text" name="name" id="name">
		<label for="password">Password:</label>
		<input type="text" name="password" id="password">
		<input type="submit" name="regSubmit" id="regSubmit">
	</form>
	<?php include 'links/link-js.php';?>
</body>
</html>