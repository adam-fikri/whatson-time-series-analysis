<?php
	include_once '../formatTweets.php';

	if(isset($_POST['searchKey'])){
		echo '<p class="loading">Loading...</p>';
		//echo $_POST['searchKey'];
		$searchKey = $_POST['searchKey'];

		echo '<div class="modal-header">
				<p class="modal-title">Popular Tweets</p>
				<p><b>Search key: </b>'.$searchKey.'</p>
			</div>';

		$tweets=searchTweets($searchKey, 100,'&result_type=popular');
		date_default_timezone_set('Asia/Kuala_Lumpur');
		//print_r($tweets);
		echo '<div class="modal-body">';
		foreach ($tweets as $tweet) {
			echo '<div class="tweet">';
			echo '<img alt="profile pic" width="auto" height="auto" src="'.$tweet['profile_img'].'">';
			echo '<p><b>'.$tweet['name'].'</b> <span class="grey">@'.$tweet['screen_name'].'</span></p>';
			echo '<p>'.$tweet['full_text'].'</p>';
			echo '<p><span class="grey">'.date('h:i A M d, Y', strtotime($tweet['created_at'])).'</span></p>';
			echo '</div>';
		}
		echo '</div>';

		echo '<div class="modal-footer">
				Total tweets: '.count($tweets).
			'</div>';
		echo "<script> $('.loading').remove(); </script>";
	}


	if(isset($_GET['mode']) && ($_GET['mode']=='test')){
		//testing mode
		$tweets=searchTweets('covid', 5,'&result_type=popular');
		print_r($tweets);
	}
?>