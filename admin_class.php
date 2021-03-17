<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
			$qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where email = '".$email."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function login2(){
		extract($_POST);
			$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM users where email = '".$email."' and password = '".md5($password)."'  and type= 2 ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}
	function save_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($cpass) && !empty($password)){
					$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'../assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function signup(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','month','day','year')) && !is_numeric($k)){
				if($k =='password'){
					if(empty($v))
						continue;
					$v = md5($v);

				}
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(isset($email)){
			$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
			if($check > 0){
				return 2;
				exit;
			}
		}
		if(isset($_FILES['pp']) && $_FILES['pp']['tmp_name'] != ''){
			$fnamep = strtotime(date('y-m-d H:i')).'_'.$_FILES['pp']['name'];
			$move = move_uploaded_file($_FILES['pp']['tmp_name'],'assets/uploads/'. $fnamep);
			$data .= ", profile_pic = '$fnamep' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");

		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			if(empty($id))
				$id = $this->db->insert_id;
			foreach ($_POST as $key => $value) {
				if(!in_array($key, array('id','cpass','password')) && !is_numeric($key))
					if($k = 'pp'){
						$k ='profile_pic';
					}
					if($k = 'cover'){
						$k ='cover_pic';
					}
					$_SESSION['login_'.$key] = $value;
			}
					$_SESSION['login_id'] = $id;
					if(isset($_FILES['pp']) &&$_FILES['pp']['tmp_name'] != '')
						$_SESSION['login_profile_pic'] = $fnamep;
					if(!isset($type))
						$_SESSION['login_type'] = 2;
			return 1;
		}
	}

	function update_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','table')) && !is_numeric($k)){
				if($k =='password')
					$v = md5($v);
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if($_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			foreach ($_POST as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function save_genre(){
		extract($_POST);
		$data = "";

		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cover')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
			}

		if(isset($_FILES['cover']) && $_FILES['cover']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", cover_photo = '$fname' ";
		}
		if(empty($id)){
			if(empty($_FILES['cover']['tmp_name']))
			$data .= ", cover_photo = 'default_cover.jpg' ";
			$save = $this->db->query("INSERT INTO genres set $data");
		}else{
			$save = $this->db->query("UPDATE genres set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_genre(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM genres where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_music(){
		extract($_POST);
		$data = "";

		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cover','audio','item_code')) && !is_numeric($k)){
				if($k == 'description')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
			}
			$data .=",user_id = '{$_SESSION['login_id']}' ";
		if(isset($_FILES['cover']) && $_FILES['cover']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", cover_image = '$fname' ";
		}
		if(isset($_FILES['audio']) && $_FILES['audio']['tmp_name'] != ''){
			$audio = strtotime(date('y-m-d H:i')).'_'.$_FILES['audio']['name'];
			$move = move_uploaded_file($_FILES['audio']['tmp_name'],'assets/uploads/'. $audio);
			$data .= ", upath = '$audio' ";
		}
		if(empty($id)){
			if(empty($_FILES['cover']['tmp_name']))
			$data .= ", cover_image = 'default_cover.jpg' ";
			$save = $this->db->query("INSERT INTO uploads set $data");
		}else{
			$save = $this->db->query("UPDATE uploads set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_music(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM uploads where id = $id");
		if($delete){
			return 1;
		}
	}
	function get_details(){
		extract($_POST);
		$get = $this->db->query("SELECT * FROM uploads where id = $id")->fetch_array();
		$data = array("cover_image"=>$get['cover_image'],"title"=>$get['title'],"artist"=>$get['artist']);
		return json_encode($data);
	}
	function save_playlist(){
		extract($_POST);
		$data = "";

		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cover')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
			}
			$data .=",user_id = '{$_SESSION['login_id']}' ";
			if(isset($_FILES['cover']) && $_FILES['cover']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", cover_image = '$fname' ";
		}
		if(empty($id)){
			if(empty($_FILES['cover']['tmp_name']))
			$data .= ", cover_image = 'play.jpg' ";
			$save = $this->db->query("INSERT INTO playlist set $data");
		}else{
			$save = $this->db->query("UPDATE playlist set $data where id = $id");
		}
		if($save){
			if(empty($id))
			$id = $this->db->insert_id;
			return $id;
		}
	}
	function delete_playlist(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM playlist where id = $id");
		if($delete){
			return 1;
		}
	}
	function find_music(){
		extract($_POST);
		$get = $this->db->query("SELECT id,title,upath,artist,cover_image FROM uploads where title like '%$search%' or artist like '%$search%' ");
		$data = array();
		while($row = $get->fetch_assoc()){
			$data[] = $row;
		}
		return json_encode($data);
	}
	function save_playlist_items(){
		extract($_POST);
		$ids=array();
		foreach($music_id as $k => $v){
			$data = " playlist_id = $playlist_id ";
			$data .= ", music_id = {$music_id[$k]} ";
			$check = $this->db->query("SELECT * FROM playlist_items where playlist_id = $playlist_id and  music_id = {$music_id[$k]}")->num_rows;
			if($check <= 0){
				if($save[] = $this->db->query("INSERT INTO playlist_items set $data ")){
					$ids[]=$music_id[$k];
				}
			}else{
				$save[] = 1;
			}

		}
		if(isset($save)){
			$this->db->query("DELETE FROM playlist_items where playlist_id = $playlist_id and music_id not in (".implode(',',$music_id).") ");
			return 1;
		}
	}
}