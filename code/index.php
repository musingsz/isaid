<?php
$storage = new SaeStorage();
if( $storage->read( 'upload' , 'install.mark' ) != '1' ) die("Please <a href=\"install.php\">install</a> iSaid first."); 
require("./config.php");
require("./include/dbclass.php");
require("./include/template.php");
require("./include/function.php");
$t=new Template("./");
$config=config();
	$t->set_var("Index",$config[theme]);
	$t->hideblock("Index","ItemPage");
	
	$t->set_var(array(
		"BlogTitle"=>$config['sitename'],
		"BlogPageTitle"=>$config['sitename'],
		"BlogURL"=>$config['home_url'],
		"Info"=>"",
		"description"=>$config['description']));
        
//recent posts
	$row = $db->getarray($db->query("SELECT count(id) FROM ".$db_prefix."article"));
	$pagesize=$config['list_article'];
	$numrows=$row[0];
	//计算总页数
	$pages=intval($numrows/$pagesize);
	if ($numrows%$pagesize)	$pages++;
	$page = htmlspecialchars(trim($_GET['p']));
	if (!($page>0 && $page<=$pages))$page=1;
	//计算记录偏移量
	$offset=$pagesize*($page -1);
	$first=1;
	$prev=$page -1;
	$next=$page +1;
	$last=$pages;
	
	if ($page>1)
		{
			$pagep.="<a href=\"".$config['home_url']."\">首页</a>&nbsp;&nbsp;";
			$pagep.="<a href=\"".$config['home_url']."index".$prev.".html\">前一页</a>";
		}
	if ($page<$pages)
		{
			$pagen.="<a href=\"".$config['home_url']."index".$next.".html\">后一页</a>&nbsp;&nbsp;";
			$pagen.="<a href=\"".$config['home_url']."index".$last.".html\">末页</a>&nbsp;";
		}
	$t->set_var("Pre",$pagecount);
	$t->set_var("Next",$pagecount);
	$query = "SELECT * FROM ".$db_prefix."article ORDER BY date DESC  LIMIT $offset,$pagesize";
	$t->set_block("Index","iSaid","ArticleList");
	$article=$t->get_var("iSaid");
	$article=article($article,$query,$config);
	$t->set_var("ArticleList",$article);
//links  
	$t->set_block("Index","Links","Linklist");
	$links=$t->get_var("Links");
	$links=links($links);
	$t->set_var("Linklist",$links);
//recentcomments
	$t->set_block("Index","NewComments","NewCommentsList");
	$newcomments=$t->get_var("NewComments");
	$newcomments=newcomments($newcomments,$config);
	$t->set_var("NewCommentsList",$newcomments);
//cts
	$t->set_block("Index","Category","CategoryList");
	$Categorys=$t->get_var("Category");
	$Categorys=Category($Categorys,$config);
	$t->set_var("CategoryList",$Categorys);
//tagscloud
	$TagsCloud=tagscloud('12','16',$config);
    $t->set_var("TagsCloud",$TagsCloud);
//Archives
	$t->set_block("Index","Archives","ArchivesList");
	$Archives=$t->get_var("Archives");
	$Archives=archives($Archives,$config);
	$t->set_var("ArchivesList",$Archives);
ob_start("ob_gzhandler");
$t->parse("OUT","Index");
$t->p("OUT");

		
?>