<?php
session_start();
require("../config.php");
require("./css/pinyin.php");
require("../include/dbclass.php");
if($_SESSION['iSaid_user'] && $_SESSION['iSaid_password']){
	$query="SELECT * FROM `".$db_prefix."user` WHERE `username`='$_SESSION[iSaid_user]' AND `password`='$_SESSION[iSaid_password]' LIMIT 0,1";
	if($db->getcount($query)==0){
		exit("please <a href=\"index.php\">login</a> first!");
	}
	$atitle="添加日志";
	$op="add";
if($_POST['title'] && $_POST['sort'] && $_POST['content'] && $_GET['op']=="add"){
		$str=addslashes($_POST['title']);
   //     $_POST['content']=ereg_replace("\n\r|\n","<br />",$_POST['content']);
		$article = explode("<more>",$_POST['content']);
            $content=addslashes($article[0]);
   //     $content=ereg_replace("\n","<br />",$content);
            $more=addslashes($article[1]);
        $date=time();
		if($_POST[name]==""){$name=pinyin($_POST[title],"utf8");}
		else{$name=$_POST[name];}
		$query="INSERT INTO `".$db_prefix."article` (`title`,`content`,`more`,`sort`,`date`,`name`,`keywords`,`author`) VALUES( '$str', '$content', '$more','$_POST[sort]','$date','$name','$_POST[keywords]','$_SESSION[iSaid_nickname]')";
		$res=$db->query($query);
		$n=$db->getid();
		if($n){
			$atitle="添加文章成功";
			$info="<font color=\"red\">添加文章成功！</font>";
			}
		else {
			$atitle="添加文章失败";
			$info="<font color=\"red\">添加文章失败！</font>";
		}
		$query="SELECT * FROM `".$db_prefix."article` WHERE `name`='$name'";
		if($db->getcount($query)!=1){
		$query="UPDATE `".$db_prefix."article` SET `name`='".$name.$n."' WHERE id = ".$n;
		$db->query($query);		
		}
		if($_POST['keywords']){
		$tags = explode(",",$_POST['keywords']);
			for($i=0;$i<count($tags);$i++){
   				 if($tags[$i]!=""){
   				 	$query="SELECT * FROM `".$db_prefix."tags` WHERE `keyword`='$tags[$i]'";
   				 	if($db->getcount($query)==0){
    					$sql="INSERT INTO `".$db_prefix."tags` (`keyword`,`aids`,`usenum`) VALUES('$tags[$i]','$n','1')";
   	 					$db->query($sql);    }
   	 				else{
   	 					$row=$db->getarray($db->query($query));
   	 					$usenum=$row[usenum]+1;
   	 					$sql="UPDATE `".$db_prefix."tags` SET `aids`='".$row[aids].",".$n."',`usenum`='$usenum' WHERE id = ".$row[id];
   	 				}
   				 }	
		}}
	}
	if($_POST['title'] && $_POST['sort'] && $_POST['content'] && $_GET['op']=="edit" && is_numeric($_GET['id']))
	{	$str=addslashes($_POST['title']);
        //$_POST['content']=ereg_replace("\n\r|\n","<br />",$_POST['content']);
		$article = explode("<more>",$_POST['content']);
            $content=addslashes($article[0]);
          //  $content=ereg_replace("\n","<br />",$content);
            $more=addslashes($article[1]);
       if($_POST['name']==""){
        	$name=pinyin($_POST['title'],utf);
        	}
		else{
			$name=$_POST['name'];
			}
		$query="UPDATE `".$db_prefix."article` SET `title`='$str',`content`='$content',`more`='$more',`sort`='$_POST[sort]',`name`='$name',`keywords`='$_POST[keywords]'  WHERE id = ".$_GET[id];
		$res=$db->query($query);
		$info="<font color=\"red\">修改文章成功！</font>";
		$query="SELECT * FROM `".$db_prefix."article` WHERE `name`='$name'";
		if($db->getcount($query)!=1){
		$query="UPDATE `".$db_prefix."article` SET `name`='".$name.$_GET[id]."' WHERE id = ".$_GET[id];
		$db->query($query);		
		}
	}
		if($_GET[op]=="edit"&& is_numeric($_GET['id'])){
	$query="SELECT * FROM ".$db_prefix."article where `id`='$_GET[id]'";
	$res=$db->query($query);
	$erow=$db->getarray($res);
	$atitle="修改日志";
	$op="edit&id=".$_GET[id];
	}
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xml:lang="zh" xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<TITLE>iSaid Manager Center <?=$atitle?></TITLE>
<META content="blog,iSaid,web" name="keywords">
<META content="这里是iSaid博客系统控制管理中心." name="description">
<LINK media=screen href="css/screen.css" type="text/css" rel="stylesheet">
  <style type="text/css" rel="stylesheet">
    form {
        margin: 0;
    }
    .editor {
        margin-top: 5px;
        margin-bottom: 5px;
    }
  </style>
  <script type="text/javascript" charset="utf-8" src="css/kindeditor.js"></script>
  <script type="text/javascript">
    KE.show({
        id : 'content'
    });
  </script>
</HEAD>
<BODY>
<div id="container">
<div id="left">
<h2>iSaid</h2>
<br />
<a href="set.php">常规设置</a>
<br /><br />
<?php if($_GET[op]=="edit"){
	echo "<a href=\"marticle.php\">写新日志</a>";
}else{
	echo "<b>写新日志</b>";
}	?>
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
<h3>文章编辑</h3>
<br /><?=$info?>
<form  name="setting" method="post" action="<?=$_SERVER['PHP_SELF']?>?op=<?=$op;?>">
<span>标题：</span>
<br />
<input type="text" name="title" value="<?=$erow['title'];?>" >
<br /><br />
<span>文章内容：</span><span style="color: #999">源代码模式下可添加标签&lt;more&gt;分开,&lt;more&gt;以后的部分将仅在文章页面和rss中显示</span>
<br /><div class="editor">
<textarea name="content" id="content" style="width:700px;height:250px;visibility:hidden;"><?php echo $erow['content']."<more>".$erow[more];?></textarea></div>
<span>分类：</span>
<br />
<select name="sort">
	<option value="1">==请选择分类==</option>
<?php	
	$query="SELECT * FROM ".$db_prefix."sort ORDER BY id";
	$res=$db->query($query);
	while($row=$db->getarray($res)){
		?>
  <option value="<?=$row['id']?>" <?php if($row['id']==$erow['sort']) echo "selected";?>><?=$row['title']?></option>
	<?php
	}
	?>
</select>
<br /><br />
<span>缩略名：</span><span style="color: #999">标题的英文翻译或拼音</span>
<br />
<input type="text" name="name" value="<?=$erow['name'];?>" >
<br /><br />
<span>关键词：</span><span style="color: #999">多个关键词请用半角,隔开</span>
<br />
<input type="text" name="keywords" value="<?=$erow['keywords'];?>" >
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
