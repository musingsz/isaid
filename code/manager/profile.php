<?php
session_start();
require("../config.php");
require("../include/dbclass.php");
if($_SESSION['iSaid_user'] && $_SESSION['iSaid_password']){
	$query="SELECT * FROM `".$db_prefix."user` WHERE `username`='$_SESSION[iSaid_user]' AND `password`='$_SESSION[iSaid_password]' LIMIT 0,1";
	if($db->getcount($query)==0){
		exit("please <a href=\"index.php\">login</a> first!");
	}
	$atitle="用户管理";
if($_GET['op']=="add" && $_POST['username']&& $_POST['password']) {
		$pass=md5($_POST[password]);
		$nickname=addslashes($_POST[nickname]);
		$query="INSERT INTO `".$db_prefix."user` (`username`,`password`,`nickname`) VALUES('$_POST[username]','$pass','$nickname')";
		if($db->insert($query)){
			$atitle="用户添加成功";
			$info="<font color=\"red\">用户添加成功！</font>";
		}
		else{
			$atitle="用户添加失败";
			$info="<font color=\"red\">用户添加失败！</font>";
		}
	}
	else if($_GET['op']=="delete" && is_numeric($_GET['id']) && $_GET['id']!=1) {
		$sql_del="DELETE FROM `".$db_prefix."user` WHERE `id`='$_GET[id]' ";
		$db->query($sql_del);
		$atitle= "用户删除成功";
		$info="<font color=\"red\">用户删除成功！</font>";
	}
	else if(is_numeric($_GET['id']) && $_GET['op']=="edit" && $_POST['username']){
		if($_POST['password']!=""){
		$pass=md5($_POST["password"]);
		}
		else{
		$pass=$_SESSION['iSaid_password'];
		}
		$nickname=addslashes($_POST[nickname]);
		$query="UPDATE `".$db_prefix."user` SET `nickname`='$nickname', `username`='$_POST[username]', `password`='$pass' WHERE `id`='$_GET[id]'";
		if($db->update($query)){
		$atitle= "用户信息更新成功";
		$flag=1;
		$info="<font color=\"red\">用户信息更新成功！</font>";
		}

	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xml:lang="zh" xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<TITLE>iSaid Manager Center <?=$atitle?></TITLE>
<META content="blog,iSaid,web" name="keywords">
<META content="这里是iSaid博客系统控制管理中心." name="description">
<LINK media=screen href="css/screen.css" type="text/css" rel="stylesheet">
</HEAD>
<BODY>
<div id="container">
<div id="left">
<h2>iSaid</h2>
<br />
<a href="set.php">常规设置</a>
<br /><br />
<a href="marticle.php?op=add">发表新日志</a>
<br /><br />
<a href="articlelist.php">日志列表</a>
<br /><br />
<a href="commentlist.php">查看评论</a>
<br /><br />
<a href="sort.php">编辑分类</a>
<br /><br />
<a href="attachment.php">附件管理</a>
<br /><br />
<b>用户中心</b>
<br /><br />
<a href="links.php">友情链接</a>
<br /><br />
<a href="index.php?op=logout">退出登陆</a>
<br />
</div>
<div id="right">
<h3>用户管理</h3>
<br /><?=$info?>
<?php
$query="SELECT * FROM `".$db_prefix."user` ORDER BY id ASC";
$res=$db->query($query);
if($db->getrow($res)>0){
	while($row=$db->getarray($res)){
?>
<p><?=$row['username']?>(<?=$row['nickname']?>) <a href="<?=$_SERVER['PHP_SELF']?>?op=edit&id=<?=$row['id']?>">编辑</a> <a href="<?=$_SERVER['PHP_SELF']?>?op=delete&id=<?=$row['id']?>">删除</a>
<br/><br />
</p>
<?php
	}
}
if(is_numeric($_GET['id']) && $_GET['op']=="edit"&&$flag!=1){
$query="SELECT username,nickname FROM `".$db_prefix."user` where `id`= '$_GET[id]'";
$res=$db->query($query);
$row=$db->getarray($res);
$uri=$_SERVER['PHP_SELF']."?op=edit&id=".$_GET['id'];
}
else{
$uri=$_SERVER['PHP_SELF']."?op=add";
}
	?>
<br /><br />
<form  name="setting" method="post" action="<?=$uri;?>">
<span>用户名：</span>
<br />
<input type="text" name="username" value="<?=$row[username]?>" >
<br /><br />
<span>昵称：</span>
<br />
<input type="text" name="nickname" value="<?=$row[nickname]?>" >
<br /><br />
<span>密码：</span>
<br />
<input type="text" name="password" value="" >
<br /><br />
<input type="submit" name="setting" value="确定"><br /><br />
<font color=red>新增用户将拥有和管理员完全等同的权限,请谨慎为他人新增账号<br />修改密码时请确认输入无误.</font>
</form>
</div>
</div>
<DIV class="footer">powered by <A href="http://isaid.sinaapp.com">iSaid</A> ©<?=date("Y")?> Running On <a href="http://sae.sina.com.cn" target="_blank"><img src="http://sae.sina.com.cn/static/image/poweredby/117X12px.gif" title="Powered by Sina App Engine" /></a></DIV>
</BODY>
</HTML>
<?php
exit;
}
else exit("<script language='javascript'>window.location='index.php';</script>");
?>
