<?php
include_once('config.php');
require_once('sql.class.php');

try{
		$db = new DataMysql(array('host'=>DB_HOST,'usr'=>DB_USER,'password'=>DB_PASSWORD,'database'=>DB_NAME));
	}
	catch(Exception $e) {
			echo $e->getMessage();
			exit();
	}

?>
