<?php
	/*$date = date('M d', strtotime('-1 day', strtotime('today')));//get date automatically
	$month = date('M', strtotime('-1 day', strtotime('today')));
	include_once 'loadFunction.php';
	loadFromDB($date);
	exec('python python/sector.py');
	$fileName = 'data/monthly_tweets/tweets-'.$month.'.csv';
	$freqFile = 'data/monthly_tweets/sector_freq/'.$month.'.csv';
	loadFromDB($month, $fileName);
	exec('python python/monthly_sector.py '.$fileName.' '.$freqFile);*/
?>
<!DOCTYPE html>
<html>
<head>
	<title>WHATSON</title>
	<?php include 'links/link-css.php';?>
	<link rel="stylesheet" type="text/css" href="css/sector.css">
</head>
<body>
	<?php include 'template/header.php';?>
	<h1>Sector Identification</h1>
	<!--p><b>Date: </b><?php echo $date;?></p-->
	<label for="from">From:</label>
	<input type="date" id="from" name="from">
	<label for="to">To:</label>
	<input type="date" id="to" name="to">
	<input type="submit" id="submitDate">
	<img id="submitLoad" src="images/gear-copy.png" height="25" width="25">
	<button id="show-monthly">Show Monthly Sector</button>

	<canvas id="timeSeries" height="50"></canvas>
	<canvas id="percent" height="50"></canvas>
	<canvas id="monthlyTimeSeries" height="50"></canvas>

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

		<div class="grid-items">
			<p class="loading">Loading</p>
		</div>

		<div class="grid-items">
			<p class="loading">Loading</p>
		</div>

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
	<div class="modal">
		<div class="modal-content"></div>
	</div>

	<?php include 'template/footer.php';?>

	<?php include 'links/link-js.php';?>
	<script type="text/javascript" src="javascript/sector.js"></script>
</body>
</html>