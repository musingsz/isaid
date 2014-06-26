<?php
require("../config.php");
require("../include/dbclass.php");
    $query="SELECT name,value FROM `".$db_prefix."config`";
	$res = $db->query($query);
	$config=array();
	while($row = $db->getarray($res)){
		$config[$row[name]]=$row['value'];
	}
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=$config['sitename']?> - 手机版</title>
<link rel="alternate" type="application/rss+xml" title="test RSS Feed" href="/rss" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<style type="text/css">
body,ul,ol,form{margin:0 0;padding:0 0}
ul,ol{list-style:none}
h1,h2,h3,div,li,p{margin:0 0;padding:5px 2px;font-size:medium}
li,.s{border-bottom:1px solid #ccc}
h1{background:#7acdea;color:#FFFFFF;}
h2{color:#7acdea}
.n{border:1px solid #ffed00;background:#fffcaa}
.t,.a,.stamp,#ft{color:#999;font-size:small}
img{max-width:200px;max-height:300px;}
</style>
</head>
<body>
<h1>欢迎访问<?=$config['sitename']?> 手机版</h1>
	<ul>
<?php
	$query="SELECT count(id) FROM ".$db_prefix."article";
	$res = $db->query($query);
	$row = $db->getarray($res);
	$page = htmlspecialchars(trim($_GET['page']));
	if (!isset($page) || $page==0)$page=1;
	$pagesize=7;
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
		//new posts
		$res_top = $db->query("SELECT * FROM ".$db_prefix."article ORDER BY id DESC LIMIT $offset,$pagesize");
		if($db->getrow($res_top) != 0){
		while($row_top = $db->getarray($res_top)){
                $text = stripslashes(htmlspecialchars($row_top['title']));
				echo "<li><span class=\"stamp\">".date("Y年m月d日 H:i:s",$row_top['date'])."</span><br /><a href=\"".$config[home_url]."m/".$row_top['name']."\">".$text."</a></li>";
						}
		}
?>
</ul>
<?=$pagecount;?><br />	
<div id="nav"><p><a href="do.php">管理登陆</a></p></div>
<div id="ft">
&copy; <?=$config[home_url]?></div>
</body>
</html>