<?php
	session_start();
	if(isset($_SESSION["customerEmail"])) {
		echo $_SESSION["customerEmail"];
	} else {
		echo "";
	}
?>