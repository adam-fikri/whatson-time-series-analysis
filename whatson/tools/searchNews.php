<?php

	function displayContent($content){
		//print_r($contents);
		$news = json_decode($content,true);
		foreach ($news as $new) {
			echo '<div class="content">';
			echo '<img alt="news pic" width="10%" height="10%" src="'.$new['imageUrl'].'">';
			echo '<a href="'.$new['url'].'"><p><b>'.$new['title'].'</b></p></a>';
			echo '<p>'.$new['description'].'</p>';
			echo '<p><span class="grey">by '.$new['source'].' '.date('h:i A M d, Y', strtotime($new['date'])).'</span></p>';
			echo '</div>';
		}
	}
	echo '<p class="loading">Loading....</p>';
	date_default_timezone_set('Asia/Kuala_Lumpur');

	if(!isset($_POST['searchInput'])){
		echo 'Need input';
		echo "<script> $('.loading').remove(); </script>";
		return;
	}

	$search = $_POST['searchInput'];

	$content = exec('python ../python/searchNews.py '.$search);

	displayContent($content);

	echo "<script> $('.loading').remove(); </script>";
?>