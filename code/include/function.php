<?php
	function config(){
		global $db,$db_prefix;
		$query="SELECT name,value FROM `".$db_prefix."config`";
		$res = $db->query($query);
		$config=array();
		while($row = $db->getarray($res)){
			$config[$row[name]]=$row['value'];
		}
		return $config;
	}
	function links($links){ 
		global $db,$db_prefix;
		$query = "SELECT * FROM ".$db_prefix."links ORDER BY vip DESC  LIMIT 0,9";
		$res = $db->query($query);
		$f=new Template();
		$f->set_var("links",$links);
        while($row = $db->getarray($res)){
        	$f->set_var(array("LinkTitle"=>$row['name'],
				"LinkUrl"=>$row['url'],
				"LinkInfo"=>$row['info']));
        	$f->parse("LinkList","links",true);
        }
        $links=$f->get("LinkList");
		return $links;
	}
		function commentsnum($aid){
		global $db,$db_prefix;
		$n = $db->getarray($db->query("SELECT count(*) FROM ".$db_prefix."comment WHERE aid=".$aid));
		return $n[0];
	}
	function Category($Categorys,$config){
		global $db,$db_prefix;
		$query = "SELECT * FROM ".$db_prefix."sort ORDER BY id ASC";
		$res = $db->query($query);
		$f=new Template();
		$f->set_var("Categorys",$Categorys);
		while($row = $db->getarray($res)){
        	$f->set_var(array("SortTitle"=>$row['title'],
				"SortUrl"=>$config[home_url]."sort/".$row['name'],
				"SortInfo"=>$row['info']));
        	$f->parse("CategoryList","Categorys",true);
        }
	     $Categorys=$f->get("CategoryList");
		return $Categorys;
	}
	function article($posts,$query,$config,$full=false){
		global $db,$db_prefix;
		$res = $db->query($query);
		$f=new Template();
		$f->set_var("posts",$posts);
		if($db->getrow($res) == 0){
			$f->set_var("ArticleList","<p>尚无相关文章被发表.</p>");
			}
		else{
			while($row = $db->getarray($res)){
				
				$sortquery = "SELECT * FROM ".$db_prefix."sort WHERE id=$row[sort]";
				$res_s = $db->query($sortquery);
				$row_s = $db->getarray($res_s);
				$sort="<a href=\"".$config['home_url']."sort/".$row_s['name']."\">".$row_s['title']."</a>";
				$n = commentsnum($row['id']);
				$link=$config['home_url'].$row['name'].".html";
				$date=date("l, F j, Y",$row['date']);
				$dateheader=date("Y年m月d日",$row['date']);
				$title=htmlspecialchars(stripslashes($row['title']));
				$resume=stripslashes($row['content']);
				if($full)$resume.=$row['more'];
				$tags = explode(",",$row['keywords']);
				$keywords="";
				for($i=0;$i<count($tags);$i++){
					$keywords.=" <a href=\"".$config['home_url']."tag/".urlencode($tags[$i])."/\">".$tags[$i]."</a> ";
					
					}
				$f->set_var(array("BlogItemTitle"=>$title,
				"BlogSort"=>$sort,
				"BlogItemDateTime"=>$date,
				"BlogLables"=>$keywords,
				"BlogItemBody"=>$resume,
				"BlogDateHeaderDate"=>$dateheader,
				"BlogItemAuthorNickname"=>$row['author'],
				"BlogItemPermalinkUrl"=>$link,
				"BlogItemCommentCount"=>$n));
				$f->parse("ArticleList","posts",true);
			}}
        $posts=$f->get("ArticleList");
		return $posts;
	}
		function newcomments($comments,$config){ 
		global $db,$db_prefix;
		$query="SELECT * FROM ".$db_prefix."comment ORDER BY id DESC LIMIT 0,7";
		$res = $db->query($query);
		$f=new Template();
		$f->set_var("comments",$comments);
		if($db->getrow($res) != 0){
			while($row= $db->getarray($res)){
                $numberOfCharacters = 60;
                $text = $row['content'];
                if(strlen($text)>$numberOfCharacters){
                    $text=substr($text,0,$numberOfCharacters);
                    $text=utf8_trim($text)."...";
                    }
				$author = explode("@",$row['email']);
				$query_cml = "SELECT name FROM ".$db_prefix."article WHERE id=".$row['aid']." ORDER BY id DESC LIMIT 0,1 ";
				$res_cml = $db->query($query_cml);
				$res_cml=$db->getarray($res_cml);
				$itemlink=$config['home_url'].$res_cml['name'].".html#c".$row[id];
				$f->set_var(array("ItemUrl"=>$itemlink,
								"CommentAuthor"=>$author[0],
								"CommentBody"=>$text));
        		$f->parse("NewCommentsList","comments",true);
				}
			}
		else{
			$f->set_var("NewCommentsList","暂时还没有评论.");
			}
        $comments=$f->get("NewCommentsList");
		return $comments;
	}
	function tagscloud($minsize="12",$maxsize="15",$config){
		global $db,$db_prefix;
		$query="SELECT keyword,usenum FROM ".$db_prefix."tags ORDER BY usenum DESC LIMIT 0,50";
		$res = $db->query($query);
		$str="";
		if($db->getrow($res) == 0){
			$str="目前还没有标签";
			}
		else{
			while($row= $db->getarray($res)){
			$str.="<a href=\"".$config['home_url']."tag/".urlencode($row['keyword'])."/\">".$row['keyword']."</a> ";
			}
		}
		return $str;
	}
	function comments($comments,$id){
		global $db,$db_prefix;
		$aid = $db->getarray($db->query("SELECT id FROM ".$db_prefix."article WHERE name='".$id."' LIMIT 0,1"));
		$aid=$aid[0];
		$query="SELECT * FROM ".$db_prefix."comment WHERE aid=$aid ORDER BY date ASC";
        $res=$db->query($query);
        $f=new Template();
		$f->set_var("comments",$comments);
		if($db->getrow($res) != 0){
			while($row= $db->getarray($res)){
				$str=nl2br($row['content']);
				$date=date("l, F j, Y",$row['date']);
				$author = explode("@",$row['email']);
				$userpage=$row['userpage'];
				if($userpage==""||$userpage=="http://"){
				$author=$author[0];
				}
				else{
				$author="<a href=\"".$userpage."\">".$author[0]."</a>";
				}
				$f->set_var(array("BlogCommentNumber"=>$row['id'],
				"BlogCommentDateTime"=>$date,
				"BlogCommentAuthor"=>$author,
				"BlogCommentBody"=>$str));
			$f->parse("clist","comments",true);
		}}
		else{
		$f->set_var("clist","");
		}
		return $f->get("clist");
	}
	function archives($as,$config){
		global $db,$db_prefix;
		$query="SELECT from_unixtime(date,'%Y'),from_unixtime(date,'%m') FROM `".$db_prefix."article` GROUP BY from_unixtime(date,'%Y %m') ORDER BY date DESC LIMIT 0 ,12";
		$res = $db->query($query);
		$f=new Template();
		$f->set_var("as",$as);
        while($row = $db->getarray($res)){
        	$f->set_var(array("BlogArchiveName"=>$row['0']."年".$row['1']."月",
				"BlogArchiveURL"=>$config[home_url].$row[0]."_".$row[1].".archive"));
        	$f->parse("asList","as",true);
        }
        $links=$f->get("asList");
		return $links;

	}
	
	function sorts(){}
function utf8_trim($str) {
    $len = strlen($str);
    for ($i=strlen($str)-1; $i>=0; $i-=1){
        $ch = ord($str[$i]);
        if (($ch & 128)==0) return(substr($str,0,$i));
        if (($ch & 192)==192) return(substr($str,0,$i));
    }
    return($str);
}
?>