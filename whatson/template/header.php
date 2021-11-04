<?php
	session_start();
?>
<header id="header">
	<section id="bird-fly">
		<div id="logo"></div>
		<div class="bird bird1"></div>
		<div class="bird bird2"></div>
	</section>
	<div id="menu">
		<span></span>
		<a href="sector.php" class="page">Sector Identification</a>
		<span></span>
		<a href="sentiment.php" class="page">Sentiment Analysis</a>
		<span></span>
		<a href="topic.php" class="page">Topic Analysis</a>
		<span></span>
		<a href="about.php" class="page">About</a>
		<span></span>
		<?php
			if(!isset($_SESSION['login'])){
				echo '<a href="login.php" class="page">Login/Register</a>';
			}else{
				echo '<a href="logout.php" class="page">Logout</a>';
			}
		?>
		<span></span>
	</div>
</header>
<?php
	if(isset($_SESSION['login'])){
		echo '<p id="acc-name">Hi, '.$_SESSION['name'].'!</p>';
	}

	//echo '<p id="acc-name">Hi, Test!</p>';//testing
	//echo $_SESSION['login'];
?>