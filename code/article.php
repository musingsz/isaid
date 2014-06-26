<?php
require("./config.php");
require("./include/dbclass.php");
require("./include/template.php");
require("./include/function.php");
$t=new Template("./");
	$config=config();
	$t->set_var("Index",$config[theme]);

	$t->set_var(array(
		"BlogTitle"=>$config['sitename'],
		"BlogURL"=>$config['home_url'],
		"Info"=>"",
		"description"=>$config['description']));
	
	//posts

	$id = htmlspecialchars(trim($_GET['id']));
    $query = "SELECT * FROM ".$db_prefix."article WHERE `name`='$id' ORDER BY date DESC  LIMIT 0,1";
    $rowt = $db->getarray($db->query($query));
	$t->set_block("Index","iSaid","Article");
	$article=$t->get_var("iSaid");
	$article=article($article,$query,$config,true);
	$t->set_var("Article",$article);
	
	//comments
    if($rowt==""){
    $t->set_var("BlogPageTitle","你丫肯定记错了,根本没有这篇文章.");
    	$t->hideblock("Index","ItemPage");
    }
    else{
	$t->showblock("Index","ItemPage");
	$form='<form action="/i.said" method="post" name="cpost" id="cpost">
	<input name="aid" type="hidden" id="aid" value="<$id$>">
	邮件或昵称(*):<br /><input type="text" name="author" tabindex="1" id="author" /><br />
	网 站:<br /><input type="text" name="userpage" tabindex="1" id="userpage" /><br />
	评论内容:<br /><textarea rows="4" cols="45" name="content" tabindex="2" id="icomment"></textarea><br />
<input name="publish" type="submit" id="publish" tabindex="5" value="Publish" />
          </form>';
	$t->set_var("Form",$form);
	$rowp= $db->getarray($db->query("SELECT `id`,`title`,`name`  FROM `".$db_prefix."article` WHERE `id` < '".$rowt['id']."' ORDER BY id DESC LIMIT 0,1"));
	$rown= $db->getarray($db->query("SELECT `id`,`title`,`name`  FROM `".$db_prefix."article` WHERE `id` > '".$rowt['id']."'  ORDER BY id ASC LIMIT 0,1"));
		if($rowp['id']){
		$pre="<a href=\"".$config['home_url'].$rowp['name'].".html\">&lt;&lt".stripslashes(htmlspecialchars($rowp['title']))."</a> ";
		}
		if($rown['id']){
		$next="  <a href=\"".$config['home_url'].$rown['name'].".html\">".stripslashes(htmlspecialchars($rown['title']))."&gt;&gt;</a>";
		}
		$n = commentsnum($rowt['id']);
    $t->set_var(array("BlogPageTitle"=>$rowt['title'],
    			"Pre"=>$pre,
    			"Next"=>$next,
    			"BlogItemCommentCount"=>$n,
    			"id"=>$rowt['id'],
    			));
	$t->set_block("Index","BlogItemComments","comments");
	$comments=$t->get_var(BlogItemComments);
	$comments=comments($comments,$id);
    $t->set_var("comments",$comments);
    }
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