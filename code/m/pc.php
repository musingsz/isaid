<?php
require("../config.php");
require("../include/dbclass.php");
$query="SELECT name,value FROM `".$db_prefix."config`";
	$res = $db->query($query);
	$config=array();
	while($row = $db->getarray($res)){
		$config[$row[name]]=$row['value'];
	}
function msg($info,$link,$st){
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>

<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=$st;?>手机版-发表评论</title>
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
<h1>发表评论</h1>
	<div><?=$info?></div>
	<hr/>
<div id="nav"><p><a href="<?=$link?>m/do.php">管理登陆</a></p><p><a href="<?=$link?>m/">返回首页</a></p></div>
<div id="ft">&copy; <?=$link;?></div>
</body>
</html>
<?php
}
if (!is_numeric($_POST['aid']) || $_POST['aid']=="0" || empty($_POST['email']) || empty($_POST['content'])) {
	$info = "参数错误！<br />请检查评论表单是否完整填写？";
	exit(msg($info,$config['home_url'],$config['sitename']));
}

if(!ereg("^([a-zA-Z0-9_\-\.])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+",$_POST['email']) ){
	$info = "评论发表错误！<br /><strong>请检查评论表单！</strong><br />email地址是否正确？";
	exit(msg($info,$config['home_url'],$config['sitename']));
}

else{
        if($config['allow_comment']=="0"){
		$info = "评论发表错误！<br />管理员禁止了评论功能.";
		exit(msg($info,$config['home_url'],$config['sitename']));
	}
	$banwords = preg_replace("/^\|\|/","",$config['banwords']);
	$banwords = preg_replace("/\|\|$/","",$banwords);
	$banwords  = explode("||",$banwords);
	$_POST['addr'] = $_SERVER['REMOTE_ADDR'];

	if(isset($banwords) && $banwords['0']!=""){
		$ban   = array_values($banwords);
		$str   = array_values($_POST);
		for($i=0; $i<count($ban);$i++){
			for($j=0; $j<count($str);$j++){
				if(eregi($ban[$i],$str[$j])){
					$info = "评论发表错误！<br />您的评论包含管理员禁止的字符<br /> 或<br /> 你的IP/email地址已经被管理员禁止。";
					exit(msg($info,$config['home_url'],$config['sitename']));
				}
			}
		}
	}

}
$query = "SELECT id FROM ".$db_prefix."article WHERE id=".$_POST['aid'];
$res   = $db->query($query);
$row   = $db->getarray($res);
if($db->getrow($res)==0){
	$info = "评论发表错误！<br />该主题并不存在！";
	exit(msg($info,$config['home_url'],$config['sitename']));
}
else{
	$_POST['content']=trim(strip_tags($_POST['content'],"<b><i><font>"));
	$_POST['email']=htmlspecialchars($_POST['email']);
	$userpage=htmlspecialchars(trim($_POST['userpage']));
	$date=time();
			if(!(preg_match("/^http/", $userpage)) && $userpage!="")	$userpage="http://".$userpage;
	$query="INSERT INTO `".$db_prefix."comment`	 ( `aid`  , `email` , `userpage` , `content` , `date`,`ip` ) 	VALUES('$_POST[aid]', '$_POST[email]','$userpage', '$_POST[content]','$date','$_SERVER[REMOTE_ADDR]')";
	$db->query($query);
header("Location:".$_SERVER['HTTP_REFERER']);
}
?>