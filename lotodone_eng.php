<?
require('db_functions.php');

if($tickets_left=="0"){ 
	$dice_options = array();
	
	for($i=1; $i<65; $i++){
		$dice_options[] = $i;
	}
	
	$num_winners = 5;
	$winnings = array(4.00, 2.00, 1.50, 1.00, 0.50);
	
	for($i=0; $i<$num_winners; $i++){
		$dice_value = $dice_options[rand(0,64)];
		$sql = "SELECT * FROM tb_megaloto WHERE id='".$dice_value."'";
		$result = dbQuery($sql);        
		$row = mysql_fetch_array($result);
		$user = $row["status"];
		
		$query = "INSERT INTO tb_winners (username, rank, number) VALUES('$user','1','$dice_value')";
		dbQuery($query);
		
		$sql = "SELECT * FROM tb_users WHERE username='$user'";
		$result = dbQuery($sql);        
		$row = mysql_fetch_array($result);
		$money = $row["money"];
		$bonus = $row["bonus"];
		$new = $money + $winnings[$i];
		$bonusnew = $bonus + $winnings[$i];
		
		$query = "UPDATE tb_users SET money='$new', bonus='$bonusnew' WHERE username='$user'";
		dbQuery($query);
		
	}
	
	$queryf = "UPDATE tb_megaloto SET status='ok'";
	dbQuery($queryf);
}
?>