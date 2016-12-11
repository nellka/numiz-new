<?php

$start = microtime(true);
$timenow = time();
$timenow = mktime(0, 0, 0, date("m", $timenow), date("d", $timenow), date("Y", $timenow));

//$timenow += 86400;
$sql = "delete from rating where time<".$timenow.";";
$message .= "\n".$sql;
$result = $shopcoins_class->setQuery($sql);

$sql = "select distinct ratinguser as user from ratinguser order by ratinguser;";
$result1 = $shopcoins_class->getDataSql($sql);

foreach ($result1 as $rows ){

	$sql = "insert into ratingbydate (date, host, hit, ratinguser) values (".$timenow.", 0, 0, {$rows['user']});";
	$shopcoins_class->setQuery($sql);
	$message .= "\n".$sql;
}

//$recipient = "bodka@megasoft.ru";
//$subject = "������� � ����� ��������";
//$headers = "From: Numizmatik.Ru<administrator@numizmatik.ru>\nContent-type: text/plain; //charset=\"windows-1251\"";
//mail($recipient, $subject, $script.$message, $headers);

echo (microtime(true) - $start);

die();

?>
