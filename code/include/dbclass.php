<?php
class dbClass{
	var $username;
	var $password;
	var $database;
	var $hostname;
	var $link;
	var $result;
	function dbClass($username,$password,$database,$hostname="localhost"){
		$this->username=$username;
		$this->password=$password;
		$this->database=$database;
		$this->hostname=$hostname;
	}

	function connect(){
		$this->link=@mysql_connect($this->hostname,$this->username,$this->password) or die("Sorry,can not connect to database");
		mysql_query("set names utf8");
		return $this->link;
	}

	function select(){
		mysql_select_db($this->database,$this->link);
	}



	function query($sql){
		if($this->result=mysql_query($sql,$this->link)) return $this->result;
		else {
	//	echo "SQL语句错误： <font color=red>$sql</font> <BR><BR>错误信息： ".mysql_error();
		//echo "SQL语句错误! ";
			return false;
		}
	}
	function safeQuery($sql){
		if($this->result=mysql_query($sql,$this->link)) return $this->result;
		else {
			return false;
		}
	}

	function getarray($result){
		return @mysql_fetch_array($result);
	}

	function getfirst($sql){
		return @mysql_fetch_array($this->query($sql));
	}

	function getcount($sql){
		return @mysql_num_rows($this->query($sql));
	}
    function getrow($res){
		return @mysql_num_rows($res);
	}

	function update($sql){
		return $this->query($sql);
	}

	function insert($sql){
		return $this->query($sql);
	}

	function getid(){
		return mysql_insert_id();
	}

	function affected_rows() {
		return @mysql_affected_rows();
	}
}

$db=new dbClass("$db_username","$db_password","$db_database","$db_hostname");
$db->connect();
$db->select();
?>