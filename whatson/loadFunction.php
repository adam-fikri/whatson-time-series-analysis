<?php 
	
	function getDatesArray($from, $until){
		if($from==$until) return $from;

		$dates = array();
		array_push($dates, $from);
		while($from!=$until){
			$date = date('M d', strtotime('+1 day', strtotime($from)));
			array_push($dates, $date);
			$from = $date;
		}
		return implode('|', $dates);
	}

	function writeLog($filePath, $recordDate=false, $date=null){
		$fh = fopen($filePath, 'w');
		if($recordDate == true){
			fwrite($fh, $date.PHP_EOL);
		}
		fwrite($fh, date('Y-m-d h:i:sa').PHP_EOL);
		fclose($fh);
		return;
	}

	function readLog($filePath,$includeDate = true){
		$result;
		$fh = fopen($filePath, 'r');
		if($includeDate == true){
			$result =  array('logDate' => fgets($fh), 'logTime' => fgets($fh));
		}else{
			$result = fgets($fh);
		}
		fclose($fh);
		return $result;
	}

	function timeDiff($start,$end='now'){
		return strtotime($end) - strtotime($start);
	}

	function loadFromAPI($screenNames, $date){
		include 'formatTweets.php';
		$tweets = getFromUsers2($screenNames,$date);

		if(file_exists('data/tweetsInit.csv')){
			unlink('data/tweetsInit.csv');
		}

		//convert to csv
		$fh  = fopen('data/tweetsInit.csv', 'w');
    	$headers = array('created_at', 'full_text' , 'screen_name');
    	fputcsv($fh, $headers);
    	foreach ($tweets as $tweet) {
       		fputcsv($fh, $tweet);
    	}
    	fclose($fh);
	}

	function loadFromDB($pattern, $fileName='data/tweets.csv'){//working
		include 'config/conn.php';
		$sql = "SELECT * FROM tweets WHERE created_at REGEXP '$pattern'";

		$tweets = array();

		if($stmt = $conn->prepare($sql)){
			$stmt->execute();
    		$result = $stmt->get_result();
    		$rows = $result->fetch_all(MYSQLI_ASSOC);

	    	foreach ($rows as $row) {
            	$tweet = array('created_at'=>$row['created_at'], 'full_text'=>$row['full_text'], 'screen_name'=>$row['screen_name']);
            	array_push($tweets, $tweet);
    		}
		}

		if(file_exists($fileName)){
			unlink($fileName);
		}

		//convert to csv
		$fh  = fopen($fileName, 'w');
    	$headers = array('created_at', 'full_text' , 'screen_name');
    	fputcsv($fh, $headers);
    	foreach ($tweets as $tweet) {
       		fputcsv($fh, $tweet);
    	}
    	fclose($fh);
	}

	if(isset($_POST['init'])){
		$date = date('M d', strtotime('-1 day', strtotime('today')));
		$month = date('M', strtotime('-1 day', strtotime('today')));
		$fileName = 'data/monthly_tweets/tweets-'.$month.'.csv';
		$freqFile = 'data/monthly_tweets/sector_freq/'.$month.'.csv';
		loadFromDB($date);

		if($_POST['source']=='sentiment'){
			exec('python python/sentiment.py');
		}

		if($_POST['source']=='sector'){
			exec('python python/sector.py');
			//loadFromDB($month, $fileName);
			//exec('python python/monthly_sector.py '.$fileName.' '.$freqFile);
		}

		if($_POST['source']=='sector_month'){
			loadFromDB($month, $fileName);
			exec('python python/monthly_sector.py '.$fileName.' '.$freqFile);
		}

		/*if($_POST['source']=='sector']){
			//echo 'Hi';
			//$month = date('M', strtotime('-1 day', strtotime('today')));
			exec('python python/sector.py');
			/*$fileName = 'data/monthly_tweets/tweets-'.$month.'.csv';
			$freqFile = 'data/monthly_tweets/sector_freq/'.$month.'.csv';
			loadFromDB($month, $fileName);
			exec('python python/monthly_sector.py '.$fileName.' '.$freqFile);
		}*/

		return;
	}

	if(isset($_POST['source'])){
		$from = date('M d', strtotime($_POST['from']));
		$to = date('M d', strtotime($_POST['to']));
		//echo $from;
		$patt = getDatesArray($from, $to);
		loadFromDB($patt);

		if($_POST['source']=='sentiment'){
			exec('python python/sentiment.py');
		}

		if($_POST['source']=='sector'){
			exec('python python/sector.py');
		}

		return;
	}
?>