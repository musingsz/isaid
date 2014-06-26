<?php
session_start();
require("../../config.php");
require("../../include/dbclass.php");
if($_SESSION['iSaid_user'] && $_SESSION['iSaid_password']){
	$query="SELECT * FROM `".$db_prefix."user` WHERE `username`='$_SESSION[iSaid_user]' AND `password`='$_SESSION[iSaid_password]' LIMIT 0,1";
	if($db->getcount($query)==0){
		alert("你丫还没有登陆吧?");
	}
	$ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
	$max_size = 10000000;
	if (empty($_FILES) === false) {
	$fn = $_FILES['imgFile']['name'];
	$tn = $_FILES['imgFile']['tmp_name'];
	if (!$fn) {
		alert("请选择文件。");
	}
	$temp_arr = explode(".", $fn);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
	//检查扩展名
	if (in_array($file_ext, $ext_arr) === false) {
		alert("上传文件扩展名是不允许的扩展名。");
	}
	$s = new SaeStorage();
		$reso=$db->query("SELECT * from ".$db_prefix."attachment where `filename`='$fn' LIMIT 0,1");
		if($db->getrow($reso)!=0){
		$fn=time().$fn;
		}
	$content = file_get_contents($tn);
		$s->write( $up_domain , $fn , $content);
		$date=time();
		$query="INSERT INTO `".$db_prefix."attachment` (`domain`,`filename`,`date`) VALUES('$up_domain','$fn','$date')";
		$res=$db->query($query);
		unlink($tn);
	$file_url = $s->getUrl($up_domain ,$fn);
	header('Content-type: text/html; charset=UTF-8');
	echo json_encode(array('error' => 0, 'url' => $file_url));
	exit;
	}

function alert($msg) {
	header('Content-type: text/html; charset=UTF-8');
	echo json_encode(array('error' => 1, 'message' => $msg));
	exit;
}
}
?>