<?
require("./config.php");
require("./include/dbclass.php");
    $query="SELECT name,value FROM `".$db_prefix."config`";
	$res = $db->query($query);
	$config=array();
	while($row = $db->getarray($res)){
		$config[$row[name]]=$row['value'];
	}
header("Content-type:application/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
$query="SELECT * FROM ".$db_prefix."article ORDER BY date DESC";
$res = $db->query($query);
$res_d = $db->query("SELECT date FROM ".$db_prefix."article ORDER BY date DESC LIMIT 0,1");
$row_d = $db->getarray($res_d);
$newdate = str_replace(" ","T",date("Y-m-d H:i:s",$row_d['0']))."-05:00";
$home = $config['home_url'];
$homeIndex = $config['home_url'];
if($_GET['v']=="2"){
	print <<< eot
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:admin="http://webns.net/mvcb/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
<channel>
<title>$config[sitename]</title>
<link>$home</link>
<description>$config[sitename]</description>
<dc:language>zh-cn</dc:language>
<dc:creator>$config[sitename]</dc:creator>
<dc:date>$newdate</dc:date>
<sy:updatePeriod>hourly</sy:updatePeriod>
<sy:updateFrequency>1</sy:updateFrequency>
eot;
	while($row = $db->getarray($res)){
		$str=$row[content].$row[more];
		$URL = $config[home_url].$row['name'].".html";
		$date_rss = str_replace(" ","T",date("Y-m-d H:i:s",$row[date]))."-05:00";
		$str = stripslashes(strip_tags($str,"<br><a><img>"));
		$str = str_replace("&nbsp;","",$str);
		$title = strip_tags($row[title]);
		print <<< eot
<item>
<title>$title</title>
<link>$URL</link>
<description><![CDATA[
$str $more
]]></description>
<guid isPermaLink="false">$URL</guid>
<dc:date>$date_rss</dc:date>
<dc:creator>$row[author]</dc:creator>
</item>
eot;
}
$t=date("y");
print <<< eot
eot;
echo "</channel></rss>";
}

else{
	print <<< eot
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:admin="http://webns.net/mvcb/" xmlns:cc="http://web.resource.org/cc/" xmlns="http://purl.org/rss/1.0/">
<channel rdf:about="$home">
<title>$config[sitename]</title>
<link>$home</link>
<description>$config[description]</description>
<dc:language>zh-cn</dc:language>
<dc:date>$newdate</dc:date>
<items>
<rdf:Seq>
<rdf:li resource="$home" /> 
<rdf:li resource="$homeIndex" /> 
</rdf:Seq>
</items>
</channel>
eot;
	while($row = $db->getarray($res)){
		$str=$row[content].$row[more];
		$URL = $config['home_url'].$row['name'].".html";
		$date_rss = str_replace(" ","T",date("Y-m-d H:i:s",$row[date]))."-05:00";
		$str = stripslashes($str);
		$str = strip_tags($str,"<br><a><img>");
		$str = str_replace("&nbsp;","",$str);
		$title = strip_tags($row[title]);
		print <<< eot
<item rdf:about="$URL">
<title>$title</title> 
<link>$URL</link> 
<description>
<![CDATA[
$str $more
]]></description>
<dc:creator>$row[author]</dc:creator>
<dc:date>$date_rss</dc:date>
</item>
eot;
}
$t=date("y");
print <<< eot
eot;
echo "</rdf:RDF>";
}
?>