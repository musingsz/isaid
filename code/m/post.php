<?php
session_start();
require("../config.php");
require("./pinyin.php");
require("../include/dbclass.php");
    $query="SELECT name,value FROM `".$db_prefix."config`";
	$res = $db->query($query);
	$config=array();
	while($row = $db->getarray($res)){
		$config[$row[name]]=$row['value'];
	}
if(isset($_SESSION['iSaid_user']) && $_SESSION['iSaid_password']){
	$query="SELECT * FROM `".$db_prefix."user` WHERE `username`='$_SESSION[iSaid_user]' AND `password`='$_SESSION[iSaid_password]' LIMIT 0,1";
	if($db->getcount($query)==0){
	header("Location:".$config[home_url]."m/do.php");
		exit();
	}


 if($_POST['title'] && $_POST['sort'] && $_POST['content'] && $_GET['op']=="add"){
		$str=addslashes($_POST['title']);
		 $_POST['content']=ereg_replace("\n\r|\n","<br />",$_POST['content']);
		 $article = explode("<more>",$_POST['content']);
            $content=ereg_replace("\r","",$article[0]);
            $content=ereg_replace("\n","<br />",$content);
            $more=ereg_replace("\r","",$article[1]);
        $date=time();
        if($_POST[name]==""){$name=pinyin($_POST[title],utf);}
        else{$name=$_POST[name];}
		$query="INSERT INTO `".$db_prefix."article` (`title`,`content`,`more`,`sort`,`date`,`name`,`keywords`,`author`) VALUES( '$str', '$content', '$more','$_POST[sort]','$date','$name','$_POST[keywords]','$_SESSION[iSaid_nickname]')";
		$res=$db->query($query);
		$n=$db->getid();
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
   	 					$res=$db->query($query);
   	 					$row=$db->getarray($res);
   	 					$usenum=$row[usenum]+1;
   	 					$sql="UPDATE `".$db_prefix."tags` SET `aids`='".$row[aids].$n."',`usenum`='$usenum' WHERE id = ".$row[id];
   	 				}
   				 }	
		}}
		header("Location:".$config[home_url]."m/");
		exit();
	}
else{
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>

<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=$config['sitename']?> 发表日志</title>
<style type="text/css">
body,ul,ol,form{margin:0 0;padding:0 0}
ul,ol{list-style:none}
h1,h2,h3,div,li,p{margin:0 0;padding:2px 2px;font-size:medium}
li,.s{border-bottom:1px solid #ccc}
h1{background:#7acdea;color:#FFFFFF;}
h2{color:#7acdea}
.n{border:1px solid #ffed00;background:#fffcaa}
.t,.a,.stamp,#ft{color:#999;font-size:small}
img{max-width:200px;max-height:300px;}
</style>
</head>
<body>
<h1><?=$config['sitename']?> 发表日志</h1>
<div class="wrap">
<div id="poststuff">
<form name="post" action="post.php?op=add" method="post" id="post">	
	<div>
	<h2>Title</h2>
	<div><input type="text" name="title" size="25" tabindex="1" id="title" /></div>
</div>
<div><select name="sort">
	<option value="1">=请选择分类=</option>
<?php	
	$query="SELECT * FROM ".$db_prefix."sort ORDER BY id";
	$res=$db->query($query);
	while($row=$db->getarray($res)){
		?>
  <option value="<?=$row['id']?>"><?=$row['title']?></option>
	<?php
	}
	?>
</select></div>
	<div>
    <h2>Post</h2>
        <div><textarea rows="4" cols="21" name="content" tabindex="2" id="content"></textarea></div>
</div><div>
	<h2>Other</h2>
	<div>Tags(以,隔开):<br /><input type="text" name="keywords" size="25" tabindex="1" /></div>
	<div>缩略名:<br /><input type="text" name="name" size="25" tabindex="1"  /></div>
</div>
<input name="publish" type="submit" id="publish" tabindex="5" accesskey="p" value="Publish" />
</form></div></div>
		<br/>
<div id="nav">
<p><a href="/m/" accesskey="0">返回首页</a></p>
<p><a href="do.php?op=logout">退出登陆</a></p></div>

<div id="ft">
&copy; <?=$config[home_url]?></div>
</body>
</html>
<?php
}}
else header("Location:".$config[home_url]."m/do.php");
?>