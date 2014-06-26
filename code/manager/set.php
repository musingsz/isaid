<?php
session_start();
require("../config.php");
require("../include/dbclass.php");
if($_SESSION['iSaid_user'] && $_SESSION['iSaid_password']){
	$query="SELECT * FROM `".$db_prefix."user` WHERE `username`='$_SESSION[iSaid_user]' AND `password`='$_SESSION[iSaid_password]' LIMIT 0,1";
	if($db->getcount($query)==0){
		exit("please <a href=\"index.php\">login</a> first!");
	}
	$atitle="常规设置";
	if($_POST['setting'] ){
        $query=array();
        $sitename=addslashes($_POST[sitename]);
        $description=addslashes($_POST[description]);
		$query['0'] ="UPDATE `".$db_prefix."config` SET `value`='$sitename' WHERE `name`='sitename'";
		if(strrpos($_POST[home_url],"/")<10) $_POST[home_url].="/";
        $query['1'] ="UPDATE `".$db_prefix."config` SET `value`='$_POST[home_url]' WHERE `name`='home_url'";
        $query['2'] ="UPDATE `".$db_prefix."config` SET `value`='$description' WHERE `name`='description'";
        if(is_numeric($_POST['list_article'])){
        $query['3'] ="UPDATE `".$db_prefix."config` SET `value`='$_POST[list_article]' WHERE `name`='list_article'";
        }
		$query['4'] ="UPDATE `".$db_prefix."config` SET `value`='$_POST[allow_comment]' WHERE `name`='allow_comment'";
	//	$query['5'] ="UPDATE `".$db_prefix."config` SET `value`='$_POST[banwords]' WHERE `name`='banwords'";
        for($i=0;$i<count($query);$i++){
            $db->query($query[$i]);
        }
        $atitle="设置修改成功";
        $info="<font color=\"red\">设置修改成功！</font><br />";
	}
	$query="SELECT name,value FROM `".$db_prefix."config`";
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
<b>常规设置</b>
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
<h3>常规设置</h3>
<br /><?=$info?>
<form  name="setting" method="post" action="<?=$_SERVER['PHP_SELF']?>">
<span>博客名称：</span><span style="color: #999">例如"iSaid"</span>
<br />
<input type="text" name="sitename" value="<?=$config['sitename'];?>" >
<br /><br />
<span>主页地址：</span><span style="color: #999">例如"http://iSaid/"</span>
<br />
<input type="text" name="home_url" value="<?=$config['home_url'];?>" >
<br /><br />
<span>博客简述：</span><span style="color: #999">例如"My iSaid blog"</span>
<br />
<input type="text" name="description" value="<?=$config['description'];?>" >
<br /><br />
<span>分页文章数：</span><span style="color: #999">请输入大于0的整数</span>
<br />
<input type="text" name="list_article" value="<?=$config['list_article'];?>"  maxlength="2" size="2">
<br /><br />
<span>是否允许评论：</span><span style="color: #999">设为"no"则评论功能关闭</span>
<br />
<select name="allow_comment">
      <option value="1" <?php if($config['allow_comment']=="1")echo "selected";?>>yes</option>
      <option value="0" <?php if($config['allow_comment']=="0")echo "selected";?>>no</option>
</select>

<br /><br />
<input type="submit" name="setting" value="更新设置">
<br /><br /><br />
<a href="theme.php">编辑模板文件</a>
</form>
</div>
</div>
<DIV class="footer">powered by <A href="http://isaid.sinaapp.com">iSaid</A> ©<?=date("Y")?>.</DIV>
</BODY>
</HTML>
<?php
exit;
}
else 
{
exit("<script language='javascript'>window.location='index.php';</script>");
}
?>