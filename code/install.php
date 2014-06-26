<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML><HEAD>
<META http-equiv=Content-Type content="text/html; charset=UTF-8">
<STYLE type=text/css>
A:link {	COLOR: #003366; TEXT-DECORATION: none}
A:visited {	COLOR: #003366; TEXT-DECORATION: none}
A:hover {	TEXT-DECORATION: underline}BODY {	FONT-SIZE: 12px; SCROLLBAR-ARROW-COLOR: #ffffff; SCROLLBAR-BASE-COLOR: #e3e3e3; BACKGROUND-COLOR: #ffffff}
TABLE {	FONT-SIZE: 12px; COLOR: #000000; FONT-FAMILY: Tahoma, Verdana}
TEXTAREA {	FONT-WEIGHT: normal; FONT-SIZE: 12px; COLOR: #000000; FONT-FAMILY: Tahoma, Verdana; BACKGROUND-COLOR: #e3e3e3}
INPUT {	FONT-WEIGHT: normal; FONT-SIZE: 12px; COLOR: #000000; FONT-FAMILY: Tahoma, Verdana; BACKGROUND-COLOR: #e3e3e3}
OBJECT {	FONT-WEIGHT: normal; FONT-SIZE: 12px; COLOR: #000000; FONT-FAMILY: Tahoma, Verdana; BACKGROUND-COLOR: #e3e3e3}
.nav {	FONT-WEIGHT: bold; FONT-SIZE: 12px; FONT-FAMILY: Tahoma, Verdana}
.navtd {	FONT-SIZE: 12px; COLOR: #ffffff; FONT-FAMILY: Tahoma, Verdana; TEXT-DECORATION: none}
.header {	FONT-WEIGHT: bold; FONT-SIZE: 11px; FONT-FAMILY: Tahoma, Verdana; }
.bold {	FONT-WEIGHT: bold}
select	{ font-family: Arial; font-size: 11px;  color: #000000; font-weight: normal; background-color: #E3E3E3 }
</STYLE>
<title>iSaid 安装程序</title>
</HEAD>
<BODY>
<TABLE width="80%" border=0 align="center" cellPadding=0 cellSpacing=0>  
  <TR>
    <TD bgColor=#ffffff>
      <TABLE class=tables cellSpacing=0 cellPadding=0 width="100%" align=center 
      border=0>
        <TR>
          <TD class=header height="20px" colSpan=2>  iSaid 安装程序</TD></TR>
        <TR bgColor=#ffffff>
          <TD colspan="2">
<?php
$storage = new SaeStorage();
if( $storage->read( 'upload' , 'install.mark' ) == '1' ) die("已经安装过本应用,如需重新安装请删除storage中的upload domian并重新建立."); 
 
if($_GET['step']=="2"){
	include("./config.php");
	require("./include/dbclass.php");
	echo "step: 2/5<BR>";
	if($link=@mysql_connect($db_hostname,$db_username,$db_password)){
		echo "数据库连接成功！<BR>";
		if(mysql_select_db($db_database,$link))echo "MySQL服务启用.<BR>";
		else {
			echo "<FONT COLOR=\"#FF0000\">数据库不存在，请启用MySQL服务后继续运行本程序.</font><BR>";
			exit();
		}
	}else {
		echo "<FONT COLOR=\"#FF0000\">数据库连接失败！</font>请启用MySQL服务后继续运行本程序.<BR>";
		exit();
	}
$theme="please input the blog theme";
$sql="DROP TABLE IF EXISTS `<%prefix%>config`; CREATE TABLE `<%prefix%>config` ( `name` varchar(255) NOT NULL default '', `value` text NOT NULL default '', PRIMARY KEY (`name`) ) TYPE=MyISAM; INSERT INTO `<%prefix%>config` VALUES ('sitename', 'isaid'); INSERT INTO `<%prefix%>config` VALUES ('home_url', 'http://isaid.sinaapp.com/'); INSERT INTO `<%prefix%>config` VALUES ('allow_comment', '1'); INSERT INTO `<%prefix%>config` VALUES ('banwords', ''); INSERT INTO `<%prefix%>config` VALUES ('list_article', '9'); INSERT INTO `<%prefix%>config` VALUES ('description', 'i said i can'); INSERT INTO `<%prefix%>config` VALUES ('theme', '$theme'); INSERT INTO `<%prefix%>config` VALUES ('uploaddomain', '$up_domain');DROP TABLE IF EXISTS `<%prefix%>comment`; CREATE TABLE `<%prefix%>comment` ( `id` int(10) NOT NULL auto_increment, `aid` int(10) NOT NULL default '0', `email` varchar(50) NOT NULL default '',`userpage` varchar(70) NOT NULL default '', `content` text NOT NULL, `date` int(11) NOT NULL default '0', `ip` varchar(15) default NULL, PRIMARY KEY (`id`) ) TYPE=MyISAM; DROP TABLE IF EXISTS `<%prefix%>error`; CREATE TABLE `<%prefix%>error` ( `id` int(10) NOT NULL auto_increment, `username` varchar(30) NOT NULL default '', `password` varchar(50) NOT NULL default '', `ip` varchar(15) NOT NULL default '', `date` datetime NOT NULL default '0000-00-00 00:00:00',PRIMARY KEY (`id`) ) TYPE=MyISAM; DROP TABLE IF EXISTS `<%prefix%>links`; CREATE TABLE `<%prefix%>links` ( `id` int(10) NOT NULL auto_increment, `vip` int(10) NOT NULL default '0', `url` varchar(100) NOT NULL default '', `name` varchar(100) NOT NULL default '', `info` varchar(255) default NULL, PRIMARY KEY (`id`) ) TYPE=MyISAM; DROP TABLE IF EXISTS `<%prefix%>sort`; CREATE TABLE `<%prefix%>sort` ( `id` int(10) NOT NULL auto_increment, `title` varchar(100) NOT NULL default '',`name` varchar(100) NOT NULL default '',`info` varchar(255) default NULL, PRIMARY KEY (`id`) ) TYPE=MyISAM;INSERT INTO `<%prefix%>sort` (`title`,`name`,`info`) VALUES('unsorted','unsorted','unsorted') ; DROP TABLE IF EXISTS `<%prefix%>article`; CREATE TABLE `<%prefix%>article` ( `id` int(10) NOT NULL auto_increment, `sort` int(10) NOT NULL default '0', `title` varchar(100) NOT NULL default '', `content` text NOT NULL, `more` text, `date` int(11) NOT NULL default '0',`name` varchar(100) NOT NULL default '',`keywords` varchar(100) ,`author` varchar(100) NOT NULL default '', PRIMARY KEY (`id`) ) TYPE=MyISAM;DROP TABLE IF EXISTS `<%prefix%>user`;CREATE TABLE `<%prefix%>user` ( `id` int(10) NOT NULL auto_increment,  `username` varchar(30) NOT NULL default '',  `nickname` varchar(30) NOT NULL default '',  `password` varchar(50) NOT NULL default '',  PRIMARY KEY  (`id`)) TYPE=MyISAM; DROP TABLE IF EXISTS `<%prefix%>attachment`;CREATE TABLE `<%prefix%>attachment` ( `id` int(10) NOT NULL auto_increment,`domain` varchar(50) NOT NULL default '',`filename` varchar(255), `date` int(11) NOT NULL default '0',  PRIMARY KEY (`id`)) TYPE=MyISAM;DROP TABLE IF EXISTS `<%prefix%>tags`; CREATE TABLE `<%prefix%>tags` ( `id` int(10) NOT NULL auto_increment,  `keyword` varchar(50) NOT NULL default '',  `aids` varchar(255) NOT NULL default '', `usenum` int(11) NOT NULL default '0',  PRIMARY KEY (`id`)) TYPE=MyISAM";
	//base64_decode($sql
$sql=str_replace("<%prefix%>",$db_prefix,$sql);
$sql = explode(";",$sql);
$j=0;
for($i=0;$i<count($sql);$i++){
    if($sql[$i]!=""){
        mysql_query($sql[$i]);
        $j++;
    }
    }
    $sqlinfo = "共运行了 ".$j." 条sql语句<BR>";
	if ($j!="0"){
		echo $sqlinfo."数据导入成功！<BR><BR><A HREF=\"".$_SERVER['PHP_SELF']."?step=3&op=install\">下一步</A>";
	$theme=file_get_contents("http://isaid.sinaapp.com/theme.txt");
	$theme=addslashes($theme);
	$query ="UPDATE `".$db_prefix."config` SET `value`='$theme' WHERE `name`='theme'";
	$db->query($query);
		exit();
	}
	else exit("<FONT COLOR=\"#FF0000\">数据库导入失败！</font><BR>");
}
if($_GET['step']=="3"  && $_GET['op']=="install"){
	if($_POST['username'] && $_POST['password']){
		include("./config.php");
		$pass=md5($_POST['password']);
		$link=mysql_connect($db_hostname,$db_username,$db_password) or die(mysql_error());
		mysql_select_db($db_database) or dir(mysql_error());
		$sql="INSERT INTO `".$db_prefix."user` (`id`,`username`, `nickname`, `password`)
		 VALUES ('1','".$_POST[username]."','".$_POST[nickname]."','".$pass."')";

		mysql_query($sql,$link)or die(mysql_error());
		if(mysql_affected_rows()){
			echo "安装完成！进入<A HREF=\"manager/index.php\"><B>控制面板</B></A><BR>";
			$storage->write( 'upload' , 'install.mark' , '1' );
			exit();
		}
		else exit("<FONT COLOR=\"#FF0000\">添加用户失败！！！</font><BR>请检查配置文件后重试！<BR>");
	}
        ?>
<FORM METHOD=POST ACTION="<?=$_SERVER['PHP_SELF']?>?step=3&op=install">
添加管理员<BR>
登陆名:  <INPUT TYPE="text" NAME="username" VALUE="<?=$_POST['username']?>"><BR>
昵　称:  <INPUT TYPE="text" NAME="nickname" VALUE="<?=$_POST['nickname']?>"><BR>
密　码:  <INPUT TYPE="text" NAME="password" VALUE="<?=$_POST['password']?>"><BR>
<BR>
    <INPUT TYPE="submit" value="下一步">
</FORM>
 <?php 
}
else{
?>
<table width="100%"  border="0" cellspacing="1" cellpadding="5">
  <tr>
    <td colspan="2"><?php
  file_exists("manager")?$info.="管理文件夹manager存在。<BR>":$info.="<FONT SIZE=\"2\" COLOR=\"red\"><B>管理文件夹manager不存在！</B></FONT><BR>";
  file_exists("config.php")?$info.="配置文件config.php存在。<BR>":$info.="<FONT SIZE=\"2\" COLOR=\"red\"><B>配置文件不存在！</B></FONT><BR>";
echo $info;
?></td>
    </tr>
  <tr>
    <td colspan="2"><span class="bold">请确保config文件配置正确,mysql以及storage初始化成功.已经创建名为 upload 的用于存储上传博客附件的storage domain.</span></td>
    </tr>
  <tr bgcolor="#efefef">
    <td> </td>
    <td><a href="<?=$_SERVER['PHP_SELF']?>?step=2">下一步</a></td>
  </tr>
</table>
<?php
}
?>
</TD>
        </TR>
        </TABLE></TD></TR></TABLE></BODY></HTML>