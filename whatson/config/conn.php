<?php
  $conn = new mysqli("localhost","root","","fyp");

  if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
 ?>