<?php
require("./config.php");
require("./include/dbclass.php");
require("./include/template.php");
require("./include/function.php");
$config=config();
$t=new Template("./");
	$t->set_var("Index",$config[theme]);
	$t->hideblock("Index","ItemPage");
	$t->set_var(array(
		"PreAndNext"=>"",
		"BlogTitle"=>$config['sitename'],
		"BlogURL"=>$config['home_url'],
		"description"=>$config['description']));
if (!is_numeric($_GET['m'])||!is_numeric($_GET['y'])) header("Location:".$config['home_url']);

$y = $_GET['y'];
$m = $_GET['m'];
$t->set_var(array("BlogPageTitle"=>$y."年".$m."月 文章存档 -- ".$config['sitename'],
				"Info"=>$y."年".$m."月 文章存档"));
//recent posts

	$query = "SELECT * FROM ".$db_prefix."article WHERE from_unixtime(date,'%Y') = $y AND from_unixtime(date,'%m') = $m ORDER BY date DESC";
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
	
$t->parse("OUT","Index");
$t->p("OUT");

		
?>