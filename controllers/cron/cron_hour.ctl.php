<?
$start = microtime(true);

$timenow = time();
$timenow = mktime(0, 0, 0, date("m", $timenow), date("d", $timenow), date("Y", $timenow));

$sql = "select distinct ratinguser as user from rating where time=".$timenow." and `check`=0;";
$result = $shopcoins_class->getDataSql($sql);
echo $sql;
foreach ($result as $rows) {
	echo "<hr>".$rows['user']." - ".$timenow;
	$sql = "select count(rating) as hit, count(distinct ip) as host from rating 
	where ratinguser=".$rows['user']." and time=".$timenow." and `check`=0;";
	$rows1 = $shopcoins_class->getRowSql($sql);

	$hit = $rows1["hit"];
	$host = $rows1["host"];
	
	$sql_exist = "select * from ratingbydate where date=".$timenow." and ratinguser=".$rows['user'].";";
	echo "<br>".$sql_exist;
	$rows_exist =  $shopcoins_class->getRowSql($sql_exist);
	
	if ($rows_exist){
		$sql = "update ratingbydate set host=host+".$host.", hit=hit+".$hit." 
		where date=".$timenow." and ratinguser=".$rows['user'].";";
	} else {
		$sql = "insert into ratingbydate (host, hit, date, ratinguser)
		values ('".$host."', '".$hit."', '".$timenow."', '".$rows['user']."');";
	}
	echo "<br>".$sql;
	$shopcoins_class->setQuery($sql);
}
$sql = "update rating set `check`=1 where `check`=0;";
$shopcoins_class->setQuery($sql);

/*
if (date("H")=="18")
	include $DOCUMENT_ROOT."/shopcoins/reminder.php";
*/
$end = microtime(true);
echo ($end-$start); 
die();
?>
