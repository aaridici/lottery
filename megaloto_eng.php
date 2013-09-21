<? 
session_start();

require('config.php');
require("includes/php/db_functions.php");
$check_tickets_left = dbQuery("SELECT id FROM tb_megaloto WHERE status='ok'");
$tickets_left = mysql_num_rows($check_tickets_left);
if($tickets_left<1){
	include('lotodone.php');
}
?>
<html>
<head>
<style type="text/css">
.style11110 {
	border-collapse: collapse;
	border: 1px solid #000000;
}
.style22220 {
	border: 1px solid #000000;
}
.style33330 {
	border: 1px solid #000000;
	background-color: #66FF33;
}
</style>
</head>
<body>
<?
if(isset($_COOKIE["usNick"]) && isset($_COOKIE["usPass"])){
	$lolz=$_COOKIE["usNick"];
	require('config.php');
}else{
	echo"<br><br><center><b>Not Logged In. Please login first.</b></center><br><br>";
exit;
}
?>
<?
	//Purchasing a ticket module
	$price = 0.25;
	$ticketL=$_GET["L"];
	if ($ticketL!="") {
	
		$sql1 = "SELECT * FROM tb_users WHERE username='$lolz'";
		$result1 = mysql_query($sql1);        
		$row1 = mysql_fetch_array($result1);
		$lottery1 = $row1["lottery"];
		if($lottery1>=$price){
		
			$lotteryfinal = $lottery1 - $price;
			$queryU = "UPDATE tb_users SET lottery='$lotteryfinal' WHERE username='$lolz'";
			dbQuery($queryU);
			
			$queryL = "UPDATE tb_megaloto SET status='$lolz' WHERE id='$ticketL'";
			dbQuery($queryL);
		
		}else{
			$error_msg_1="<center><b><br>Sorry, you do not have enough balance to complete this transaction.<br></b></center>";
			echo "$error_msg_1";
		}
	}
?>

<!-- Loto board -->
<table style="width: 320px; height: 320px" class="style11110">
<?php
$num_rows = 8;
$num_cols = 8;
for($i=0; $i<$num_rows; $i++){
	echo("<tr>");
	for($j=0; $j<$num_cols; $j++){
		//Open td tag
		echo('<td style="border:1px solid #000000;');
		
		$pos_num = $i * $num_cols + $j + 1;
		$sql = "SELECT * FROM tb_megaloto WHERE id='".$pos_num."'";
		$result = dbQuery($sql);        
		$row = mysql_fetch_array($result);
		$status = $row["status"];
		if($status=="ok"){
			echo"background-color:#66FF33;";
		}
		else if($status=="$lolz"){
			echo"background-color:#FFFF00;";
		}
		else if($status!="ok"&&$status!="$lolz"){
			echo"background-color:#FF0000;";
		}
		
		//close tag
		echo('">');
		
		if($status=="ok"){
			echo('<font color=black><a href=\"megaloto_eng.php?L='.$pos_num.'\"><center><b>1</b></center></a></font>');
		}else{
			echo("<center><b>".$pos_num."</b></center>"); 
		}
	}
}
?>
</table>

<!-- Winners list -->
<table>
	<tr>
    	<td colspan="3">Winners of the previous drawing</td>
   	</tr>
<?
	$winners = dbQuery("SELECT id FROM tb_winners");
	$winners_exist = mysql_num_rows($winners);
	$initial_5 = $winners_exist - 5;
	
	$winners = dbQuery("SELECT * FROM tb_winners ORDER BY id ASC limit $initial_5,5"); 
	while ($record = mysql_fetch_array($winners)) {
		echo("<tr><td>". $record["rank"] ."</td><td>". $record["username"] ."</td><td>". $record["number"] ."</td></tr>");
	}
?>
</table>
</body>
</html>
