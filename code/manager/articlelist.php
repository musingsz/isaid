<?php
session_start();
require("../config.php");
require("../include/dbclass.php");
if($_SESSION['iSaid_user'] && $_SESSION['iSaid_password']){
	$query="SELECT * FROM `".$db_prefix."user` WHERE `username`='$_SESSION[iSaid_user]' AND `password`='$_SESSION[iSaid_password]' LIMIT 0,1";
	if($db->getcount($query)==0){
		exit("please <a href=\"index.php\">login</a> first!");
	}
	$atitle="日志列表";
	$query="SELECT count(*) FROM ".$db_prefix."article";
	$res = $db->query($query);
	$row = $db->getarray($res);
	if($row[0]==0){
		$atilte="暂时没有文章";
		$info="<font color=\"red\">还没有日志被发表！</font>";
	}
	$page = $_GET['page'];
	if (!isset($page) || $page==0)$page=1;
	$pagesize=20;
	$numrows=$row[0];
	//计算总页数
	$pages=intval($numrows/$pagesize);
	if ($numrows%$pagesize)
	$pages++;
	//计算记录偏移量
	$offset=$pagesize*($page -1);
	$first=1;
	$prev=$page -1;
	$next=$page +1;
	$last=$pages;
	if ($page>1)
		{
			$pagecount.="<a href='".$_SERVER['PHP_SELF']."?page=".$first."'>首页</a>&nbsp;&nbsp;";
			$pagecount.="<a href='".$_SERVER['PHP_SELF']."?page=".$prev."'>前一页</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
	if ($page<$pages)
		{
			$pagecount.="<a href='".$_SERVER['PHP_SELF']."?page=".$next."'>后一页</a>&nbsp;&nbsp;";
			$pagecount.="<a href='".$_SERVER['PHP_SELF']."?page=".$last."'>末页</a>&nbsp;";
		}
	if($_GET['op']=="delete" && is_numeric($_GET['id'])) {
			$db->query("delete from ".$db_prefix."comment where `aid`='$_GET[id]' ");
			$sql2="DELETE FROM ".$db_prefix."article WHERE `id`='$_GET[id]' ";
			$db->query($sql2);
		$atitle= "文章删除成功";
		$info="<font color=\"red\">文章删除成功！</font>";
	}
	$query="SELECT article.id,article.title,article.date,article.name,article.keywords,article.author,sort.title as st FROM ".$db_prefix."article article,".$db_prefix."sort sort WHERE  sort.id=article.sort ORDER BY ID DESC LIMIT $offset,$pagesize";
	$res = $db->query($query);
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
<a href="marticle.php">发表新日志</a>
<br /><br />
<b>日志列表</b>
<br /><br />
<a href="commentlist.php">查看评论</a>
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
<h3>日志列表</h3>
<br /><?=$info?>
<?php
if($db->getrow($res)>0){
	while($row=$db->getarray($res)){
	$qes="SELECT count(*) FROM ".$db_prefix."comment WHERE aid =".$row['id'];
	$res_c=$db->query($qes);
	$row_c=$db->getarray($res_c);
?>
<p><?=$row['title']?>(<?=$row['name']?>:<?=$row_c[0]?>评论) <a href="marticle.php?op=edit&id=<?=$row['id']?>">编辑</a> <a href="<?=$_SERVER['PHP_SELF']?>?op=delete&id=<?=$row['id']?>">删除</a>
<br/>
<span style="color: #999"><?=date("Y-m-d H:i:s",$row['date']);?>由<?=$row[author]?>发表. 分类:<?=$row[st]?>. 标签:<?=$row[keywords]?></span>
</p>
<?php
	}
}	?>
<br /><br />
<?=$pagecount?>
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
