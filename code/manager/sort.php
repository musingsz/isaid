<?php
session_start();
require("../config.php");
require("../include/dbclass.php");
if($_SESSION['iSaid_user'] && $_SESSION['iSaid_password']){
	$query="SELECT * FROM `".$db_prefix."user` WHERE `username`='$_SESSION[iSaid_user]' AND `password`='$_SESSION[iSaid_password]' LIMIT 0,1";
	if($db->getcount($query)==0){
		exit("please <a href=\"index.php\">login</a> first!");
	}
	$atitle="编辑分类";
if($_GET['op']=="add" && $_POST['name']&& $_POST['title']) {
			$name=addslashes($_POST["name"]);
			$info=addslashes($_POST["info"]);
			$title=addslashes($_POST["title"]);
		$query="INSERT INTO `".$db_prefix."sort` (`title`,`name`,`info`) VALUES('$title','$name','$info')";
		if($db->insert($query)){
			$atitle="分类添加成功";
			$info="<font color=\"red\">分类添加成功！</font>";
		}
		else{
			$atitle="分类添加失败";
			$info="<font color=\"red\">分类添加失败！</font>";
		}
	}
	else if($_GET['op']=="delete" && is_numeric($_GET['id']) && $_GET['id']!=1) {
		$sql="select id from ".$db_prefix."article where `sort` ='$_GET[id]'";
		$res=$db->query($sql);
		while($row=$db->getarray($res)){
			$db->query("delete from ".$db_prefix."comment where aid=$row[0]");
			$sql2="DELETE FROM ".$db_prefix."article WHERE `id`='$row[0]' ";
			$db->query($sql2);
		}
		$sql_del="DELETE FROM `".$db_prefix."sort` WHERE `id`='$_GET[id]' ";
		$db->query($sql_del);
		$atitle= "分类删除成功";
		$info="<font color=\"red\">分类删除成功！</font>";
	}
	else if(is_numeric($_GET['id']) && $_GET['op']=="edit" && $_POST['name']&& $_POST['title']){
			$name=addslashes($_POST["name"]);
			$info=addslashes($_POST["info"]);
			$title=addslashes($_POST["title"]);
		$query="UPDATE `".$db_prefix."sort` SET `title`='$title', `name`='$name', `info`='$info' WHERE `id`='$_GET[id]'";
		if($db->update($query)){
		$atitle= "分类更新成功";
		$flag=1;
		$info="<font color=\"red\">分类更新成功！</font>";
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
<a href="marticle.php">发表新日志</a>
<br /><br />
<a href="articlelist.php">日志列表</a>
<br /><br />
<a href="commentlist.php">查看评论</a>
<br /><br />
<b>编辑分类</b>
<br /><br />
<a href="attachment.php">附件管理</a>
<br /><br />
<a href="profile.php">用户中心</a>
<br /><br />
<a href="links.php">友情链接</a>
<br /><br />
<a href="index.php?op=logout">退出登陆</a>
<br />
</div>
<div id="right">
<h3>管理分类</h3>
<br /><?=$info?>
<?php
$query="SELECT * FROM `".$db_prefix."sort` ORDER BY id ASC";
$res=$db->query($query);
if($db->getrow($res)>0){
	while($row=$db->getarray($res)){
?>
<p><?=$row['title']?>(<?=$row['name']?>) <a href="<?=$_SERVER['PHP_SELF']?>?op=edit&id=<?=$row['id']?>">编辑</a> <a href="<?=$_SERVER['PHP_SELF']?>?op=delete&id=<?=$row['id']?>">删除</a>
<br/>
<span style="color: #999"><?=$row['info']?></span>
</p>
<?php
	}
}else{
echo "<p>貌似还没有分类！</p>";
}
if(is_numeric($_GET['id']) && $_GET['op']=="edit"&&$flag!=1){
$query="SELECT * FROM `".$db_prefix."sort` where `id`= '$_GET[id]'";
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
<span>分类名称：</span>
<br />
<input type="text" name="title" value="<?=$row[title]?>" >
<br /><br />
<span>索引地址：</span><span style="color: #999">(用于生成分类链接,必须为数字或字母)</span>
<br />
<input type="text" name="name" value="<?=$row[name]?>" >
<br /><br />
<span>分类描述：</span>
<br />
<input type="text" name="info" value="<?=$row[info]?>" >
<br /><br />
<input type="submit" name="setting" value="确定">
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