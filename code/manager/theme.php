<?php
session_start();
require("../config.php");
require("../include/dbclass.php");
if($_SESSION['iSaid_user'] && $_SESSION['iSaid_password']){
	$query="SELECT * FROM `".$db_prefix."user` WHERE `username`='$_SESSION[iSaid_user]' AND `password`='$_SESSION[iSaid_password]' LIMIT 0,1";
	if($db->getcount($query)==0){
		exit("please <a href=\"index.php\">login</a> first!");
	}
	$atitle="模板编辑";
	$_POST[theme]=addslashes($_POST["theme"]);
	if($_POST['setting'] ){
        //$query=array();
		$query ="UPDATE `".$db_prefix."config` SET `value`='$_POST[theme]' WHERE `name`='theme'";

            $db->query($query);
 
        $atitle="模板编辑成功";
        $info="<font color=\"red\">模板编辑成功！</font><br />";
	}
	if($_GET['op']=="huifu"){
	$theme=file_get_contents("http://isaid.sinaapp.com/theme.txt");
	$theme=addslashes($theme);
	$query ="UPDATE `".$db_prefix."config` SET `value`='$theme' WHERE `name`='theme'";
	$db->query($query);
	$atitle="模板恢复成功";
        $info="<font color=\"red\">模板恢复成功！</font><br />";
	}
	$query="SELECT name,value FROM `".$db_prefix."config` WHERE `name`='theme'";
	$res = $db->query($query);
	$config=array();
	while($row = $db->getarray($res)){
		$config[$row['name']]=$row['value'];
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
<b>编辑模板</b>
<br /><br />
<a href="marticle.php?op=add">写新日志</a>
<br /><br />
<a href="articlelist.php">文章列表</a>
<br /><br />
<a href="commentlist.php">评论管理</a>
<br /><br />
<a href="sort.php">编辑分类</a>
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
<h3>编辑模板</h3>
<br /><?=$info?>
<form  name="setting" method="post" action="<?=$_SERVER['PHP_SELF']?>">

<span>请谨慎编辑：</span><span style="color: #999">请谨慎使用,否则会造成博客不能访问.</span><a href="/manager/theme.php?op=huifu">恢复原始模板</a>
<br />
<textarea name="theme" cols="65" rows="20"><?=$config['theme'];?></textarea>
<br /><br />
<input type="submit" name="setting" value="更新模板">
</form>
</div>
</div>
<DIV class="footer">powered by <A href="http://isaid.sinaapp.com">iSaid</A> ©<?=date("Y")?>. Running On <a href="http://sae.sina.com.cn" target="_blank"><img src="http://sae.sina.com.cn/static/image/poweredby/117X12px.gif" title="Powered by Sina App Engine" /></a>
</DIV>
</BODY>
</HTML>
<?php
exit;
}
else exit("<script language='javascript'>window.location='index.php';</script>");
?>
