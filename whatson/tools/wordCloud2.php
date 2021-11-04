<?php
	session_start();
	echo '<p class="loading">Loading....</p>';

	if(!isset($_POST['topicsPath'])){
		echo 'Need topics path';
		echo "<script> $('.loading').remove(); </script>";
		return;
	}

	if(!isset($_POST['title'])){
		echo 'Need title';
		echo "<script> $('.loading').remove(); </script>";
		return;
	}

	if(!isset($_POST['tag'])){
		echo 'Need tag';
		echo "<script> $('.loading').remove(); </script>";
		return;
	}

	if(!isset($_POST['var'])){
		echo 'Need var';
		echo "<script> $('.loading').remove(); </script>";
		return;
	}

	echo "<script> $('.loading').remove(); </script>";

	//$modelPath = json_encode(array('name' => '../'.$_POST['topicsPath'].'/topics.pickle'));
	$modelPath = '../'.$_POST['topicsPath'].'/topics.pickle';
	$intprPath = '../'.$_POST['topicsPath'].'/interprate.txt';
	$title = $_POST['title'];
	$tag = $_POST['tag'];
	$var = $_POST['var'];

	$intpr = array();
	$result = exec('python ../python/load_topic.py '.$modelPath);
	$topics = json_decode($result,true);

	$showDivTag = "showDiv".$tag."()";

	$fh = fopen($intprPath, 'r');
	while(!feof($fh)){
		array_push($intpr, fgets($fh));
	}
	fclose($fh);

	//print_r($topics);
	drawWordCloud($title,$tag,$topics,$intpr);
?>

<?php
	function drawWordCloud($title=null, $tag = null, $topics=null, $interpretation=null){

		echo '<h2>'.htmlspecialchars($title).'</h2>';

		/*if($needSaveBtn == 'y' && isset($_SESSION['login'])){
			echo '<label for="save-name">Save as: </label>';
			echo '<input placeholder="Save topics as" value="Today\'s Topics" type="text" class="save-name" name="save-name">';
			echo '<button class="saveTopics"> Save </button>';
			echo '<br>';
		}*/

		echo '<button class="cloudPrev '.$tag.'"> << </button>
			<button class="cloudNext '.$tag.'"> >> </button>';

		for ($i=0; $i <count($topics) ; $i++) { 
			echo '<div class="'.$tag.'Topic">';
			echo '<p><b>Topic '.($i+1).': '.htmlspecialchars($interpretation[$i]).'</b></p>';
			/*if($needSaveBtn == 'y' && isset($_SESSION['login'])){
				echo '<label for="intpr">Interpretation:</label>
					<input placeholder="Put your interpretation here." value="None" type="text" class="intpr" name="intpr">';
			}*/

			$topic = $topics[$i];
			shuffle($topic);
			echo '<ul class="list">';
			$freqs = array_map(function($value) {
    			return $value['freq'];
			}, $topic);
			if(max($freqs)*900 < 30){
				$rate = 30/max($freqs);	
			}else{
				$rate = 60/max($freqs);
			}

			for($j=0; $j <count($topic); $j++){
				$fontSize= $topic[$j]['freq'] * $rate;
				if($fontSize < 12){
					$fontSize = 12;
				}

				/*if($fontSize > 65){
					$fontSize = 65;
				}*/
				echo '<li class="term '.$tag.'" data-freq="'.$topic[$j]['freq'].'" data-term="'.$topic[$j]['term'].'" style="font-size:'.$fontSize.'px;"></li>';
			}

			echo '</ul></div>';
		}
	}
?>

<script type="text/javascript">
	<?php echo 'var '.$var.'=0;'?>

	function <?php echo $showDivTag;?>{
		$('.'+'<?php echo $tag;?>'+'Topic').hide();
		$('.'+'<?php echo $tag;?>'+'Topic:eq('+<?php echo $var;?>+')').show();
	}

	<?php echo $showDivTag; ?>;
	$('.loading').remove();

	$('.cloudNext.'+'<?php echo $tag;?>').on('click',function(e){
		e.preventDefault();

		//alert('clicked');
		if(<?php echo $var?> == 9){
			<?php echo $var.'=0;'?>
		}else{
			<?php echo $var.'++;'?>
		}

		<?php echo $showDivTag; ?>;
	});

	$('.cloudPrev.'+'<?php echo $tag;?>').on('click',function(e){
		e.preventDefault();

		//alert('clicked');
		if(<?php echo $var?> == 0){
			<?php echo $var.'=9;'?>
		}else{
			<?php echo $var.'--;'?>
		}

		<?php echo $showDivTag; ?>;
	});

	$('.term.'+'<?php echo $tag;?>').on('click', function(e){
		e.preventDefault();
		//alert('Frequency: '+$(this).attr('data-freq'));
		$('.modal-content').load('tools/tweetModal.php',{
			searchKey: $(this).attr('data-term')
		});

		$('.modal').show();
		$('.modal-content').slideDown('slow');
	});
</script>