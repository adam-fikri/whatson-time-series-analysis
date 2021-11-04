<!DOCTYPE html>
<html>
<head>
	<title>WHATSON|InsertTweets</title>
	<style>
		body{
			background-color: #93c572;
			color: white;
		}
	</style>
</head>
<body>
	<?php
		//include all files needed
		include_once 'config/conn.php'; 
		include_once 'formatTweets.php';

		$screenNames = array(
								'YahooNews', 'cnni', 'nytimes', 'FoxNews', 'NBCNews',//news
								//news all ok
								'espn', 'SkySportsNews', 'NBCSports', 'BBCSport', 'SkySports',//sports
								//nbcsports x byk (3kali)
								'ScienceNews', 'ReutersScience', 'TechCrunch', 'newscientist', 'VentureBeat',//science and technology
								//cnntech x active (4 kali)
								//reuterscience x byk
								'CNNPolitics', 'ABCPolitics', 'BBCPolitics', 'nytpolitics', 'bpolitics',//politics
								//abclolitic x byk(3kali)
								'enews', 'IGN', 'nbc', 'screenrant', 'EW',//entertainment
								//nbc x byk (2kali)
								'BTN_News', 'CNNTravel', 'travel_biz_news', 'USNewsTravel',//travel
								//cnn travel x active (4kali)
								//btnnew x byk
								'business', 'TheEconomist', 'economics', 'markets', 'FinancialTimes',//business
								//business ok but tambah lg
								'healthmagazine', 'NPRHealth', 'Reuters_Health', 'USNewsHealth', 'bbchealth'//healthcare
								//5 acc each domain (yg active & byk follower)
								// add fetch by keywords (500)
							);

		$countScreenNames = array();

		//$date = 'Jan 31';
		$date = date('M d', strtotime('-1 day', strtotime('today')));//gt date automatically

		echo 'Date: <b>' .$date. '</b><br><br>';

		//$tweets = getFromUsers($screenNames, $since, $until, $dayBefore);
		$tweets = getFromUsers2($screenNames,$date);

		foreach ($tweets as $tweet) {

			$sql = 'INSERT INTO tweets SET created_at=?, full_text=?, screen_name=?';

			if($stmt = $conn->prepare($sql)){
				$stmt->bind_param('sss',$tweet['created_at'], $tweet['full_text'], $tweet['screen_name']);

				$stmt->execute();

				echo '<b>'.$tweet['screen_name'].'</b>';
				echo '<br>';
				echo $tweet['created_at'];
				echo '<br>';
				echo $tweet['full_text'];
				echo '<br><br>';
			}

			array_push($countScreenNames, $tweet['screen_name']);
		}
		echo 'Number of tweets: '.count($tweets).'<br>';
		print_r(array_count_values($countScreenNames));
	?>
</body>
</html>