<?php
function connect(){
	$link = mysql_connect('**mysql_server_address_here**', '**username**', '**password**'); 
	if (!$link) { 
		die('Could not connect: ' . mysql_error()); 
	}
	mysql_select_db('kinect');
	if(isset($_SESSION)){
		$_SESSION["connected"] = true;
	}
	return $link;
}
function dbQuery($queryStr){
	if(!isConnected()){
		connect();
	}
	$result = mysql_query($queryStr) or die("Error: ".mysql_error()."<br/>Query: ".$queryStr);
	return $result;
}
function sterilize($_string){
	if(!function_exists("connect")){
		require("lib/php/db_functions.php");
	}
	connect();
	return mysql_real_escape_string($_string);
}
function isConnected(){
	if(isset($_SESSION)){
		if(isset($_SESSION["connected"])){
			if($_SESSION["connected"]){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}else{
		return false;
	}
}