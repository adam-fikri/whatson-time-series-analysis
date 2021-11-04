<?php
	session_start();
	echo '<p class="loading">Loading....</p>';

	if(!isset($_POST['filePath'])){
		echo 'Need file path';
		echo "<script> $('.loading').remove(); </script>";
		return;
	}

	if(!file_exists($_POST['filePath'])){
		echo 'File path not exists';
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

	if(!isset($_POST['needSaveBtn'])){
		echo 'Please declare needSaveBtn.';
		echo "<script> $('.loading').remove(); </script>";
		return;
	}

	$filePath = $_POST['filePath'];
	$title = $_POST['title'];
	$tag = $_POST['tag'];
	$var = $_POST['var'];
	$needSaveBtn = $_POST['needSaveBtn'];

	$showDivTag = "showDiv".$tag."()";

	$result = exec('python ../python/topic_modeling.py '.$filePath.' '.$needSaveBtn);
	if($needSaveBtn == 'y'){
		rename('topics.pickle', '../data/saved_topics/topics.pickle');
	}
	$topics = json_decode($result,true);

	drawWordCloud($title,$tag, $topics, $needSaveBtn);
?>

<?php
	function drawWordCloud($title=null, $tag = null, $topics=null, $needSaveBtn='n'){

		echo '<h2>'.$title.'</h2>';

		if($needSaveBtn == 'y' && isset($_SESSION['login'])){
			echo '<label for="save-name">Save as: </label>';
			echo '<input placeholder="Save topics as" value="Today\'s_Topics" type="text" class="save-name" name="save-name">';
			echo '<button class="saveTopics"> Save </button>';
			echo '<br>';
		}

		echo '<button class="cloudPrev '.$tag.'"> << </button>
			<button class="cloudNext '.$tag.'"> >> </button>';

		for ($i=0; $i <count($topics) ; $i++) { 
			echo '<div class="'.$tag.'Topic">';
			echo '<p><b>Topic '.($i+1).'</b></p>';
			if($needSaveBtn == 'y' && isset($_SESSION['login'])){
				echo '<label for="intpr">Interpretation:</label>
					<input placeholder="Put your interpretation here." value="None" type="text" class="intpr" name="intpr">';
			}

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

	$('.saveTopics').on('click', function(e){
		e.preventDefault();
		let input = $('.intpr');
		let saveName = $('.save-name').val();
		let data = [];
		for(var i = 0; i<input.length; i++){
			//console.log($(input[i]).val());
			data.push($(input[i]).val());
		}
		$.ajax({
			type: 'POST',
			url: 'tools/saveTopics.php',
			data: {data: data, saveName: saveName}
		}).done(function(msg){
			alert(msg);
		});
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