<?php
	echo '<p class="loading">Loading....</p>';

	if(!isset($_POST['needSector'])){
		echo 'bool needSector needed';
		echo "<script> $('.loading').remove(); </script>";
		return;
	}

	if(!isset($_POST['num'])){
		echo 'num needed';
		echo "<script> $('.loading').remove(); </script>";
		return;
	}

	$needSector = $_POST['needSector'];
	$num = $_POST['num'];
	$title = 'Top tweets based on sentiment polarity';

	if($needSector=='t'){
		$title = $title.' and sector';
	}

	$result = exec('python ../python/getTop.py '.$num);
	$topTweets = json_decode($result, true);
	//print_r($tweets);
	$posTweets= $topTweets['pos'];
	$negTweets = $topTweets['neg'];
	$neuTweets = $topTweets['neu'];

	echo '<h2>'.$title.'</h2>';

	echo '<button class="tab-links active" value="pos-tweets">Positive Tweets</button>';
	echo '<button class="tab-links" value="neg-tweets">Negative Tweets</button>';
	echo '<button class="tab-links" value="neu-tweets">Neutral Tweets</button>';

	echo '<table class="tab-content" id="pos-tweets">';
	echo '<tr><th>Text</th><th>Polarity</th>';
	if($needSector=='t'){
		echo '<th>Sector</th>';
	}
	echo '</tr>';
	foreach ($posTweets as $posTweet) {
		echo '<tr>';
		echo '<td>'.$posTweet['full_text'].'</td>';
		echo '<td>'.$posTweet['polarity'].'</td>';
		if($needSector == 't'){
			echo '<td>'.$posTweet['sector'].'</td>';
		}
		echo '</tr>';
	}
	echo '</table>';


	echo '<table class="tab-content" id="neg-tweets">';
	echo '<tr><th>Text</th><th>Polarity</th>';
	if($needSector=='t'){
		echo '<th>Sector</th>';
	}
	echo '</tr>';
	foreach ($negTweets as $negTweet) {
		echo '<tr>';
		echo '<td>'.$negTweet['full_text'].'</td>';
		echo '<td>'.$negTweet['polarity'].'</td>';
		if($needSector == 't'){
			echo '<td>'.$negTweet['sector'].'</td>';
		}
		echo '</tr>';
	}
	echo '</table>';

	echo '<table class="tab-content" id="neu-tweets">';
	echo '<tr><th>Text</th><th>Polarity</th>';
	if($needSector=='t'){
		echo '<th>Sector</th>';
	}
	echo '</tr>';
	foreach ($neuTweets as $neuTweet) {
		echo '<tr>';
		echo '<td>'.$neuTweet['full_text'].'</td>';
		echo '<td>'.$neuTweet['polarity'].'</td>';
		if($needSector == 't'){
			echo '<td>'.$neuTweet['sector'].'</td>';
		}
		echo '</tr>';
	}
	echo '</table>';


	echo "<script> $('.loading').remove(); </script>";
?>
<script type="text/javascript">

	showTabContent('pos-tweets');

	$('.tab-links').on('click',function(e){
		e.preventDefault();
		var content = $(this).val();
		$('.tab-links').removeClass('active');
		$(this).addClass('active');
		showTabContent(content);
	});

	function showTabContent(content){
		$('.tab-content').hide();
		$('#'+content).show();
	}
</script>