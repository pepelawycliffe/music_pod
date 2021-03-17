<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM uploads where id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	if($k=='title')
		$k = 'mtitle';
	$$k = $v;
}
include 'new_music.php';
?>