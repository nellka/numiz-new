
<?
//#!/usr/local/bin/php
include $DOCUMENT_ROOT."/config.php";
//include_once "/var/www/htdocs/numizmatik.ru/config.php";
$start = microtime();

$MainFolderMenuX=4; 
$MainFolderMenuY=1;

$timenow = time();
$timenow = mktime(0, 0, 0, date("m", $timenow), date("d", $timenow), date("Y", $timenow));

$sql = "select distinct ratinguser as user from rating where time=".$timenow." and `check`=0;";
$result = mysql_query($sql);
echo $sql;
while ($rows = mysql_fetch_array($result))
{
	echo "<hr>".$rows[0]." - ".$timenow;
	$sql = "select count(rating) as hit, count(distinct ip) as host from rating 
	where ratinguser=".$rows[0]." and time=".$timenow." and `check`=0;";
	$result1 = mysql_query($sql);
	$rows1 = mysql_fetch_array($result1);
	$hit = $rows1[0];
	$host = $rows1[1];
	
	$sql_exist = "select * from ratingbydate where date=".$timenow." and ratinguser=".$rows[0].";";
	echo "<br>".$sql_exist;
	$result_exist = mysql_query($sql_exist);
	$rows_exist = mysql_fetch_array($result_exist);
	
	if ($rows_exist[0])
	{
		$sql = "update ratingbydate set host=host+".$host.", hit=hit+".$hit." 
		where date=".$timenow." and ratinguser=".$rows[0].";";
	} else {
		$sql = "insert into ratingbydate 
		(host, hit, date, ratinguser)
		values ('".$host."', '".$hit."', '".$timenow."', '".$rows[0]."');";
	}
	echo "<br>".$sql;
	$result3 = mysql_query($sql);
}
$sql = "update rating set `check`=1 where `check`=0;";
$result = mysql_query($sql);

/*
if (date("H")=="18")
	include $DOCUMENT_ROOT."/shopcoins/reminder.php";
*/
$end = microtime();
echo $end-$start; 
mysql_close($link);
?>
