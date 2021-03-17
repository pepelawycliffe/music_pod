<?php
ob_start();
date_default_timezone_set("Asia/Manila");

$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}

if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'update_user'){
	$save = $crud->update_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'save_genre'){
	$save = $crud->save_genre();
	if($save)
		echo $save;
}
if($action == 'delete_genre'){
	$delete = $crud->delete_genre();
	if($delete)
		echo $delete;
}
if($action == 'save_music'){
	$save = $crud->save_music();
	if($save)
		echo $save;
}
if($action == 'delete_music'){
	$delete = $crud->delete_music();
	if($delete)
		echo $delete;
}
if($action == 'get_details'){
	$get = $crud->get_details();
	if($get)
		echo $get;
}
if($action == 'save_playlist'){
	$save = $crud->save_playlist();
	if($save)
		echo $save;
}
if($action == 'delete_playlist'){
	$delete = $crud->delete_playlist();
	if($delete)
		echo $delete;
}
if($action == 'find_music'){
	$get = $crud->find_music();
	if($get)
		echo $get;
}

if($action == 'save_playlist_items'){
	$save = $crud->save_playlist_items();
	if($save)
		echo $save;
}
ob_end_flush();
?>
