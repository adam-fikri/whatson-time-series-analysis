<?php
	/*$date = date('M d', strtotime('-1 day', strtotime('today')));
	include_once 'loadFunction.php';
	loadFromDB($date);*/
?>
<!DOCTYPE html>
<html>
<head>
	<title>WHATSON</title>
	<?php include 'links/link-css.php';?>
	<link rel="stylesheet" type="text/css" href="css/topic.css">
</head>
<body>
	<?php include 'template/header.php';?>
	<h1>Topic Analysis</h1>
	<!--p><b>Date: </b><?php echo $date;?></p-->
	<label for="from">From:</label>
	<input type="date" id="from" name="from">
	<label for="to">To:</label>
	<input type="date" id="to" name="to">
	<input type="submit" id="submitDate">

	<?php if(isset($_SESSION['login'])):?>
	<label for="topic-select">Predict Topic:</label>
	<select name="topic-select" id="topic-select">
		<option value="0">Topic 1</option>
		<option value="1">Topic 2</option>
		<option value="2">Topic 3</option>
		<option value="3">Topic 4</option>
		<option value="4">Topic 5</option>
		<option value="5">Topic 6</option>
		<option value="6">Topic 7</option>
		<option value="7">Topic 8</option>
		<option value="8">Topic 9</option>
		<option value="9">Topic 10</option>
	</select>
	<label for="periods">Predict For:</label>
	<input type="number" name="periods" id="periods">
	<select id="periods-extent">
		<option value="1">Hour(s)</option>
		<option value="24">Day(s)</option>
		<option value="168">Week(s)</option>
	</select>
	<button id="predictBtn">Predict</button>
	<button id="backBtn">Back</button>
	<?php endif;?>

	
	<img id="submitLoad" src="images/gear-copy.png" height="25" width="25">


	<canvas id="timeSeries" height="50"></canvas>
	<!--canvas id="predictSeries" height="50"></canvas-->
	
	<div class="grid">
		<div class="grid-items">
			<p class="loading">Loading</p>
		</div>

		<div class="grid-items">
			<?php
				if(isset($_SESSION['login'])){
					//include 'readSavedTopics.php';
					$topicspath = $_SESSION['topicspath'];
					echo '<input id="isLogin" type="hidden" value="yes">';
					echo '<input id="topicspath" type="hidden" value="'.$topicspath.'">';
					echo '<h2>Saved Topics</h2>';
					//echo '<button class="refresh-topics">Refresh</button><br><br>';
					//echo '<div class="saved-topics"></div>';
					include 'readSavedTopics.php';
				}else{
					echo '<input id="isLogin" type="hidden" value="no">';
					echo 'Please <a id="login" href="login.php">login</a> to use this feature.';
				}
			?>
		</div>
	</div>
	<div class="news">
		<p class="loading">Loading</p>
	</div>
	<div class="modal">
		<div class="modal-content"></div>
	</div>

	<?php include 'template/footer.php';?>
	<?php include 'links/link-js.php';?>
	<script type="text/javascript" src="javascript/topic.js"></script>
</body>
</html>