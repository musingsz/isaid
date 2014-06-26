<?php
session_start();
require("../config.php");
require("../include/dbclass.php");
if($_SESSION['iSaid_user'] && $_SESSION['iSaid_password']){
	$query="SELECT * FROM `".$db_prefix."user` WHERE `username`='$_SESSION[iSaid_user]' AND `password`='$_SESSION[iSaid_password]' LIMIT 0,1";
	if($db->getcount($query)==0){
		exit("please <a href=\"index.php\">login</a> first!");
	}
	$query="SELECT name,value FROM `".$db_prefix."config`";
	$res = $db->query($query);
	$config=array();
	while($row = $db->getarray($res)){
		$config[$row[name]]=$row['value'];
	}
	$atitle="附件管理";
if($_GET[op]=='up'){
	if($_FILES["file"][error]>0) {
		$atitle="上传出错啦";
		$info="<font color=\"red\">上传过程遇到了错误,请修正后再试.</font>";
	}
	else{
		$s = new SaeStorage();
		$fn=$_FILES[file][name];
		$reso=$db->query("SELECT * from ".$db_prefix."attachment where `filename`='$fn' LIMIT 0,1");
		if($db->getrow($reso)!=0){
		$fn=time().$fn;
		}
		
		$content = file_get_contents($_FILES["file"]["tmp_name"]);
		$s->write( $up_domain , $fn , $content);
		$date=time();
		$query="INSERT INTO `".$db_prefix."attachment` (`domain`,`filename`,`date`) VALUES('$up_domain','$fn','$date')";
		$res=$db->query($query);
		unlink($_FILES["file"]["tmp_name"]);
		$atitle="上传成功";
		$info="<fond color=\"red\">上传成功，保存路径:".$s->getUrl($up_domain ,$fn)."</font>";
	}
}
	if($_GET['op']=="delete" && is_numeric($_GET['id'])) {
		$resd=$db->query("SELECT * from ".$db_prefix."attachment where `id`='$_GET[id]' LIMIT 0,1");
		$rowd=$db->getarray($resd);
		$s = new SaeStorage();
		$s->delete("$rowd[domain]","$rowd[filename]");
		$db->query("delete from ".$db_prefix."attachment where `id`='$_GET[id]' ");
		$atitle= "附件删除成功";
		$info="<font color=\"red\">附件删除成功！</font>";
	}

	$query="SELECT count(*) FROM ".$db_prefix."attachment";
	$res = $db->query($query);
	$row = $db->getarray($res);
	if($row[0]==0){
		$info="<font color=\"red\">目前还没有附件.</font>";
	}
	$page = $_GET['page'];
	if (!isset($page) || $page==0)$page=1;
	$pagesize=50;
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
<b>附件管理</b>
<br /><br />
<a href="profile.php">用户中心</a>
<br /><br />
<a href="links.php">友情链接</a>
<br /><br />
<a href="<?=$config['home_url']?>" target="new">查看博客</a>
<br /><br />
<a href="index.php?op=logout">退出登陆</a>
<br />
</div>
<div id="right">
	
<h3>已有附件</h3><br />
	<form action="attachment.php?op=up" method="post" enctype="multipart/form-data" name="up" id="up">
  上传文件：
          <input type="file" name="file"  />  <input type="submit" name="Submit" value="上传" />
</form><br />
<br /><?=$info?><br />
<?php	
$query="SELECT * FROM `".$db_prefix."attachment` ORDER BY date DESC LIMIT $offset,$pagesize";
$res=$db->query($query);
if($db->getrow($res)>0){
	$s = new SaeStorage();
	while($row=$db->getarray($res)){
		$urr=$s->getUrl($row[domain],$row[filename]);
?>
<p><?=$row['filename']?>(<a href="<?=$urr;?>" target="new">查看</a>) <a href="<?=$_SERVER['PHP_SELF']?>?op=delete&id=<?=$row['id']?>">删除</a>
<br/><br />
</p>
<?php
	}
echo $pagecount;
}
?>
</div>
</div>
<DIV class="footer">powered by <A href="http://blog.summerfly.cn">iSaid</A> ©<?=date("Y")?> Running On <a href="http://sae.sina.com.cn" target="_blank"><img src="http://sae.sina.com.cn/static/image/poweredby/117X12px.gif" title="Powered by Sina App Engine" /></a></DIV>
</BODY>
</HTML>
<?php
exit;
}
else exit("<script language='javascript'>window.location='index.php';</script>");
?>
