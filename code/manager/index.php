<?php
session_start();
if (!file_exists("../config.php")){
	exit("<BR/>config.php is not exists.Please upload it and relogin later.<br />Or <A HREF=\"../install.php\">install the iSaid blog system.</A>");
}
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
	echo "<script language='javascript'>window.location='".$_SERVER['PHP_SELF']."';</script>";
	exit();
}
if($_POST['password'] && $_POST['username']){
	$username=htmlspecialchars($_POST['username']);
	$pass = md5($_POST[password]);
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
		$errortitle="登陆失败";
		$errorinfo="<font color=\"red\">错误的用户名或密码</font>";
	}
}
if($_SESSION['iSaid_user'] && $_SESSION['iSaid_password']){
	$query="SELECT * FROM `".$db_prefix."user` WHERE `username`='$_SESSION[iSaid_user]' AND `password`='$_SESSION[iSaid_password]' LIMIT 0,1";
	if($db->getcount($query)==0){
		session_unset();
		session_destroy();
		$errortitle="登陆失败";
		$errorinfo="<font color=\"red\">错误的用户名或密码</font>";
	}
	//登陆后，判断install.php是否存在
	//if(file_exists("../install.php")){
	//	exit("<B>Please delete install.php! </B>");
	//}
header("Location:set.php");
}else{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xml:lang="zh" xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<TITLE>iSaid Manager Center <?=$errortitle?></TITLE>
<META content="blog,iSaid,web" name="keywords">
<META content="这里是iSaid博客系统控制管理中心." name="description">
<LINK media=screen href="./css/screen.css" type="text/css" rel="stylesheet">
<SCRIPT src="./css/utilities.js" type="text/javascript"></SCRIPT>
<SCRIPT src="./css/effect.js" type="text/javascript"></SCRIPT></HEAD>
<BODY>
<DIV class="content">
<DIV class="summary"><?=$config['description']?></DIV>
<DIV class="title">
<H1 class="fn n" id="j"><span class="i">i</span><span class="talk">Said</SPAN> <br />Manager Center</H1>
</DIV>
<HR>
<DIV class="login">
	<?=$errorinfo?>
<form name="login" method="post" action="<?=$_SERVER['PHP_SELF']?>">
  <label for="login">用户名：</label><br />
  <input id="login" name="username" tabindex="1" type="text" /><br />
  <br />
  <label for="password">密码：</label><br />
  <input id="password" name="password" tabindex="2" type="password" /><br />
  <br />
  <input name="commit" type="submit" value="进入管理" tabindex="3" />
<br />
<br /><FONT COLOR="#00CCCC">注意:请输入正确的用户名和密码，无效的登录将会被系统记录！</FONT>
<br></form>
<br />返回博客主页:<a href="<?=$config['home_url']?>"><?=$config['sitename']?></a>
</DIV></div>
<HR>
<DIV class="footer">powered by <A href="http://isaid.sinaapp.com">iSaid</A> &copy<?=date("Y")?> .Running On <a href="http://sae.sina.com.cn" target="_blank"><img src="http://sae.sina.com.cn/static/image/poweredby/117X12px.gif" title="Powered by Sina App Engine" /></a></DIV></BODY></HTML>
<?php
	}?>
