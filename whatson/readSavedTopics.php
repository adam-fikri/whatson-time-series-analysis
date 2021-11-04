<?php

	/*if(isset($_POST['topicspath'])){
		$topicspath = $_POST['topicspath'];
	}*/

	$files = scandir($topicspath);
	foreach ($files as $file) {
		if($file!='.' && $file!='..'){
			echo '<button class="topics-directory" data-title="'.$file.'" value="'.$topicspath.'/'.$file.'">'.$file.'</button>';
			//echo '<br>';
		}
	}
?>