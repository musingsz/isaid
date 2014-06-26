<?php
session_start();

require("../config.php");
require("../include/dbclass.php");
    $query="SELECT name,value FROM `".$db_prefix."config`";
	$res = $db->query($query);
	$config=array();
	while($row = $db->getarray($res)){
		$config[$row[name]]=$row['value'];
	}

if($_GET['op']=="logout"){
	session_unset();
	session_destroy();
    header("Location:".$config[home_url]."m/");
	exit();
}
if($_POST['password'] && $_POST['username']){
	$pass = md5($_POST[password]);
	$username = htmlspecialchars(trim($_POST['username']));
	$query="SELECT * FROM `".$db_prefix."user` WHERE `username`='$username' AND `password`='$pass' LIMIT 0,1";
	$res=$db->query($query);
	if($db->getcount($query)!=0){
		$row=$db->getarray($res);
		$_SESSION['iSaid_user'] = $row['username'];
		$_SESSION['iSaid_nickname'] = $row['nickname'];
		$_SESSION['iSaid_password'] = $row['password'];
	}
	else{
		$query="INSERT INTO ".$db_prefix."error (`username`,`password`,`ip`,`date`) VALUES('$_POST[username]','$_POST[password]','$_SERVER[REMOTE_ADDR]',now())";
		$db->query($query);
		session_unset();
		session_destroy();
		header("Location:".$config[home_url]."m/");
		exit();
	}
}
if($_SESSION['iSaid_user'] && $_SESSION['iSaid_password']){
	$query="SELECT * FROM `".$db_prefix."user` WHERE `username`='$_SESSION[iSaid_user]' AND `password`='$_SESSION[iSaid_password]' LIMIT 0,1";
	if($db->getcount($query)==0){
		session_unset();
		session_destroy();
		header("Location:".$config[home_url]."m/");
		exit();
	}
header("Location:".$config[home_url]."m/post.php");
}else{
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
	?>

<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=$config['sitename']?> - 手机版登录</title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<style type="text/css">
body,ul,ol,form{margin:0 0;padding:0 0}
ul,ol{list-style:none}
h1,h2,h3,div,li,p{margin:0 0;padding:6px 2px;font-size:medium}
li,.s{border-bottom:1px solid #ccc}
h1{background:#7acdea;color:#FFFFFF;}
h2{color:#7acdea}
.n{border:1px solid #ffed00;background:#fffcaa}
.t,.a,.stamp,#ft{color:#999;font-size:small}
img{max-width:200px;max-height:300px;}
</style>
</head>
<body>
<h1><?=$config['sitename']?>- 手机版登录</h1>

    <div id="login">

<form name="loginform" id="loginform" action="<?=$_SERVER['PHP_SELF']?>" method="post">
	<p>
		<label>Username:<br />
		<input type="text" name="username" id="user_login" class="input" value="" size="20" tabindex="10" /></label>
	</p>
	<p>
		<label>Password:<br />
		<input type="password" name="password" id="user_pass" class="input" value="" size="20" tabindex="20" /></label>
	</p>
	<p class="submit">
		<input type="submit" name="submit" id="submit" value="Login &raquo;" tabindex="100" />
	</p>
</form>
</div>
<br/>
<div id="nav">
<p><a href="<?=$config[home_url]?>m/" accesskey="0">返回首页</a></p>
</div>

<div id="ft">
&copy; <?=$config[home_url]?></div>
</body>
</html>
<?php
	}
	?>