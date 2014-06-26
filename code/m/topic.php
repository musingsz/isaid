<?php
require("../config.php");
require("../include/dbclass.php");
    $query="SELECT name,value FROM `".$db_prefix."config`";
	$res = $db->query($query);
	$config=array();
	while($row = $db->getarray($res)){
		$config[$row[name]]=$row['value'];
	}
if($_GET['id']){
$id = htmlspecialchars(trim($_GET['id']));
	$res_id = $db->query("SELECT * FROM ".$db_prefix."article WHERE name='$id' LIMIT 0,1");
if($db->getrow($res_id) == 0){
    header("Location:".$config[home_url]."m/");
    exit();
}else{
	$row_id = $db->getarray($res_id);
	$msg="<div><span class=\"stamp\">".date('Y年m月d日 H:i:s',$row_id['date'])." 由 ".$row_id['author']." 发表 </span><br />".$row_id['content'].$row_id['more']."</div>";
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=$row_id['title']?> - <?=$config[sitename]?>手机版</title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
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
<h1><?=$row_id['title']?> <a href="<?=$config[home_url]?>m/" accesskey="0">返回首页</a></h1>

<?=$msg?>
	<div>
	<h2>评论：</h2>
		<ul>
<?php
		$query_comment="SELECT * FROM ".$db_prefix."comment WHERE aid=$row_id[id] ORDER BY date ASC";
		$res_comment=$db->query($query_comment);
		if($db->getrow($res_comment) == 0){
		echo "<li>目前暂无关于此文的回复</li>";}
		else{	
			while($row_comment = mysql_fetch_assoc($res_comment)){
				$str_content=nl2br($row_comment['content']);
				$email = explode("@",$row_comment['email']);
		echo "<li><span class=\"stamp\">".$email[0]." / ".date('Y年m月d日 H:i',$row_comment['date'])."</span><br />".$str_content."</li>";
		}
			}	
?></ul>
<form action="/m/pc.php" method="post" name="cpost">
	<h2>Your Opinion?</h2>
	<input name="aid" type="hidden" id="aid" value="<?=$row_id['id']?>">
	<div>邮件(*):<br /><input type="text" name="email" size="25" tabindex="1" id="email" /></div>
	<div>网 站:<br /><input type="text" name="userpage" size="25" tabindex="1" id="userpage" /></div>
	<div>评论内容:<br /><textarea rows="3" cols="20" name="content" tabindex="2" id="comment"></textarea></div>
<input name="publish" type="submit" id="publish" tabindex="5" accesskey="p" value="Publish" />
          </form></div>
<div id="nav">
<p><a href="<?=$config['home_url']?>m/" accesskey="0">返回首页</a></p>
<p><a href="<?=$config['home_url']?>m/do.php">管理登陆</a></p></div>

<div id="ft">
&copy; <?=$config['home_url']?></div>
</body>
</html>
<?php
}}
?>