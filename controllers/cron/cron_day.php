
<?
//#!/usr/local/bin/php
//include_once "/var/www/htdocs/numizmatik.ru/config.php";
include $DOCUMENT_ROOT."/config.php";

$MainFolderMenuX=4; 
$MainFolderMenuY=1;

$start = microtime();
$timenow = time();
$timenow = mktime(0, 0, 0, date("m", $timenow), date("d", $timenow), date("Y", $timenow));

//$timenow += 86400;
$sql = "delete from rating where time<".$timenow.";";
$message .= "\n".$sql;
$result = mysql_query($sql);

$sql = "select distinct ratinguser as user from ratinguser order by ratinguser;";
$result1 = mysql_query($sql);
while ($rows = mysql_fetch_array($result1))
{
	$sql = "insert into ratingbydate (date, host, hit, ratinguser) 
	values (".$timenow.", 0, 0, $rows[0]);";
	$result = mysql_query($sql);
	$message .= "\n".$sql;
}

//$recipient = "bodka@megasoft.ru";
//$subject = "Рейтинг в Клубе Нумизмат";
//$headers = "From: Numizmatik.Ru<administrator@numizmatik.ru>\nContent-type: text/plain; //charset=\"windows-1251\"";
//mail($recipient, $subject, $script.$message, $headers);

echo microtime() - $start;

//include $DOCUMENT_ROOT."/cron/keywords.php";
mysql_close($link);

?>
