<?php
	/*$date = date('M d', strtotime('-1 day', strtotime('today')));//get date automatically
	include_once 'loadFunction.php';
	loadFromDB($date);
	exec('python python/sentiment.py');*/

?>

<!DOCTYPE html>
<html>
<head>
	<title>WHATSON</title>
	<?php include 'links/link-css.php';?>
	<link rel="stylesheet" type="text/css" href="css/sentiment.css">
</head>
<body>
	<?php include 'template/header.php';?>
	<h1>Sentiment Analysis</h1>
	<!--p><b>Date: </b><?php echo $date;?></p-->
	<label for="from">From:</label>
	<input type="date" id="from" name="from">
	<label for="to">To:</label>
	<input type="date" id="to" name="to">
	<input type="submit" id="submitDate">
	<img id="submitLoad" src="images/gear-copy.png" height="25" width="25">
	
	<canvas id="timeSeries" height="50"></canvas>
	<canvas id="percent" height="50"></canvas>
	<div class="grid">
		<div class="grid-items">
			<p class="loading">Loading</p>
		</div>

		<div class="grid-items">
			<p class="loading">Loading</p>
		</div>

		<div class="grid-items">
			<p class="loading">Loading</p>
		</div>
	</div>
	<div class="top-tweets">
		<p class="loading">Loading</p>
	</div>
	<div class="modal">
		<div class="modal-content"></div>
	</div>

	<?php include 'template/footer.php';?>

	<?php include 'links/link-js.php';?>
	<script type="text/javascript" src="javascript/sentiment.js"></script>
</body>
</html>