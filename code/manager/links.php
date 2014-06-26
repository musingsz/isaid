<?php
session_start();
require("../config.php");
require("../include/dbclass.php");
if($_SESSION['iSaid_user'] && $_SESSION['iSaid_password']){
	$query="SELECT * FROM `".$db_prefix."user` WHERE `username`='$_SESSION[iSaid_user]' AND `password`='$_SESSION[iSaid_password]' LIMIT 0,1";
	if($db->getcount($query)==0){
		exit("please <a href=\"index.php\">login</a> first!");
	}
	$atitle="链接管理";
if($_GET['op']=="add" && $_POST['name']&& $_POST['url']) {
		$name=addslashes($_POST["name"]);
		$info=addslashes($_POST["info"]);
		$query="INSERT INTO `".$db_prefix."links` (`vip`,`url`,`name`,`info`) VALUES('$_POST[vip]','$_POST[url]','$name','$info')";
		if($db->insert($query)){
			$atitle="链接添加成功";
			$info="<font color=\"red\">链接添加成功！</font>";
		}
		else{
			$atitle="链接添加失败";
			$info="<font color=\"red\">链接添加失败！</font>";
		}
	}
	else if($_GET['op']=="delete" && is_numeric($_GET['id'])) {
		$sql_del="DELETE FROM `".$db_prefix."links` WHERE `id`='$_GET[id]' ";
		$db->query($sql_del);
		$atitle= "链接删除成功";
		$info="<font color=\"red\">链接删除成功！</font>";
	}
	else if(is_numeric($_GET['id']) && $_GET['op']=="edit" && $_POST['name']&& $_POST['url']){
		$name=addslashes($_POST["name"]);
		$info=addslashes($_POST["info"]);
		$query="UPDATE `".$db_prefix."links` SET `name`='$name', `url`='$_POST[url]', `vip`='$_POST[vip]',`info`='$info' WHERE `id`='$_GET[id]'";
		if($db->update($query)){
		$atitle= "链接更新成功";
		$flag=1;
		$info="<font color=\"red\">链接更新成功！</font>";
		}

	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xml:lang="zh" xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<TITLE>iSaid Manager Center <?=$atitle?></TITLE>
<META content="blog,iSaid,web" name="keywords">
<META content="这里是iSaid博客系统控制管理中心." name="description">
<LINK media=screen href="./css/screen.css" type="text/css" rel="stylesheet">
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
<a href="profile.php">用户中心</a>
<br /><br />
<b>友情链接</b>
<br /><br />
<a href="index.php?op=logout">退出登陆</a>
<br />
</div>
<div id="right">
<h3>链接管理</h3>
<br /><?=$info?>
<?php
$query="SELECT * FROM `".$db_prefix."links` ORDER BY id ASC";
$res=$db->query($query);
if($db->getrow($res)>0){
	while($row=$db->getarray($res)){
?>
<p><?=$row['name']?>(<?=$row['url']?> // <?=$row['vip']?>) <a href="<?=$_SERVER['PHP_SELF']?>?op=edit&id=<?=$row['id']?>">编辑</a> <a href="<?=$_SERVER['PHP_SELF']?>?op=delete&id=<?=$row['id']?>">删除</a>
<br />
<span style="color: #999"><?=$row['info']?></span> 
<br/><br />
</p>
<?php
	}
}
	else{
	echo "还没有链接么？那就把<a href=\"http://blog.summerfly.cn\">http://blog.summerfly.cn</a>加上吧！<br />";
	}
if(is_numeric($_GET['id']) && $_GET['op']=="edit"&&$flag!=1){
$query="SELECT * FROM `".$db_prefix."links` where `id`= '$_GET[id]'";
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
<span>地址：</span>
<br />
<input type="text" name="url" value="<?=$row[url]?>" >
<br /><br />
<span>权重：</span><span style="color: #999">请输入大于0的整数(用于链接排序,数值越大排位越靠前)</span>
<br />
<input type="text" name="vip" value="<?=$row['vip'];?>"  maxlength="2" size="2">
<br /><br />
<span>名称：</span>
<br />
<input type="text" name="name" value="<?=$row[name]?>" >
<br /><br />
<span>说明：</span>
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
