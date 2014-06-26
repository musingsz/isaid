<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML><HEAD>
<META http-equiv=content-type content="text/html; charset=UTF-8">
	<TITLE>发表评论</TITLE>
	</HEAD>
<BODY>
<?php
require("./config.php");
require("./include/dbclass.php");
require("./include/function.php");
$config=config();
if (!is_numeric($_POST['aid']) || $_POST['aid']=="0" || trim($_POST['author'])=="" || trim($_POST['content'])=="") {
	echo "参数错误！请检查评论表单是否完整填写？<a href=javascript:history.go(-1)>快速返回</a>\n</BODY></HTML>";
	exit();
}
//if(trim($_POST['content'])==""){
//echo "参数错误！请检查评论表单是否完整填写？";
//	exit();
//}


if($config['allow_comment']=="0"){
	echo "评论发表错误！管理员禁止了评论功能.<a href=javascript:history.go(-1)>快速返回</a>\n</BODY></HTML>";
	exit();
	}

/*if($config['banwords']!=""){
	$banwords = preg_replace("/^\|\|/","",$config['banwords']);
	$banwords = preg_replace("/\|\|$/","",$banwords);
	$banwords  = explode("||",$banwords);
	$_POST['addr'] = $_SERVER['REMOTE_ADDR'];
		$ban   = array_values($banwords);
		$str   = array_values($_POST);
		for($i=0; $i<count($ban);$i++){
			for($j=0; $j<count($str);$j++){
				if(eregi($ban[$i],$str[$j])){
					echo "评论发表错误！您的评论包含管理员禁止的字符或你已经被管理员禁止.";
					exit();
				}
			}
		}
}*/

$query = "SELECT id FROM ".$db_prefix."article WHERE id=".$_POST['aid'];
$res   = $db->query($query);
$row   = $db->getarray($res);
if($db->getrow($res)==0){
	echo "评论发表错误！该主题并不存在.<a href=javascript:history.go(-1)>快速返回</a>\n</BODY></HTML>";
	exit();
}
else{
	$_POST['content']=addslashes(strip_tags($_POST['content'],"<b><i>"));
	$author=addslashes(htmlspecialchars($_POST['author']));
	$userpage=htmlspecialchars(trim($_POST['userpage']));
	$date=time();
			if( stripos($userpage,"http")!=0 && $userpage!="")	$userpage="http://".$userpage;
			if(!strpos($author,"@")) $author=$author."@anonymous.isaid";
	$query="INSERT INTO `".$db_prefix."comment`	 ( `aid`  , `email` , `userpage` , `content` , `date`,`ip` ) 	VALUES('$_POST[aid]', '$author','$userpage', '$_POST[content]','$date','$_SERVER[REMOTE_ADDR]')";
	$db->query($query);
	$urll=$_SERVER['HTTP_REFERER'];
echo "<script language='javascript'>window.location='$urll';</script>\n</BODY></HTML>";
exit();
}

?>

	