<?php
	if(!isset($_POST['topic'])){
		echo 'Topic has not been set';
		return;
	}

	if(!isset($_POST['periods'])){
		echo 'Periods has not been set';
		return;
	}

	$topic = "top".$_POST['topic'];
	$periods = $_POST['periods'];

	exec('python python/forecast.py '.$topic.' '.$periods);
?>