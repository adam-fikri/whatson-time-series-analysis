<?php
	session_start();
	function saveTopics($saveName,$intpr){
		//$date = date('M-d-Y', strtotime('today'));
		//$newDirName = '../data/saved_topics/'.$date;
		$newDirName = '../'.$_SESSION['topicspath'].'/'.$saveName;
		$newModelName = $newDirName.'/topics.pickle';
		$intprFileName = $newDirName.'/interprate.txt';
		$newGraphPath = $newDirName.'/topics_freq.csv';

		if(file_exists($newDirName)){
			echo 'Topics already saved.';
			return;
			unlink($newDirName);
		}

		mkdir($newDirName);
		copy('../data/saved_topics/topics.pickle', $newModelName);
		copy('../data/topic/topics.csv', $newGraphPath);

		$fh = fopen($intprFileName, 'w');
		for ($i=0; $i <count($intpr) ; $i++) { 
			fwrite($fh, $intpr[$i].PHP_EOL);
		}
		fclose($fh);
		echo 'Topics saved';
	}

	if(!isset($_POST['data'])){
		//print_r($_POST['data']);
		echo 'Interpretation is needed';
		return;
	}

	if(!isset($_POST['saveName'])){
		echo 'Name is needed';
		return;
	}

	//echo $_POST['saveName'];
	$dataArr = array();
	foreach ($_POST['data'] as $data) {
		array_push($dataArr, htmlspecialchars($data));
	}

	saveTopics(htmlspecialchars($_POST['saveName']), $dataArr);
?>