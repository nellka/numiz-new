<?php

include $_SERVER["DOCUMENT_ROOT"]."/config.php";

if ($cookiesuserlogin and $cookiesuserpassword)
{
	$sql = "select fio, userlogin, userpassword, `user`
	from `user` where userlogin='$cookiesuserlogin' and userpassword='$cookiesuserpassword';";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	if ($rows["user"]>0)
	{
		$user = $rows["user"];
		
		
	}
	else
	{
		exit;
	}

}
else
{
	exit;
}

if (!$group)
	$error = "error1";
else {

	//$group = urldecode($group);
	$group=iconv("UTF-8", "windows-1251//TRANSLIT", $group); 
	//echo $group;
	$sql = "select trim(catalognew.name) from catalognew,`group` where catalognew.group=group.group and lower(trim(`group`.name))=lower('$group') group by trim(catalognew.name);";
	$result = mysql_query($sql);
	//echo $sql;
	if (mysql_num_rows($result)) {
	
		while ($rows = mysql_fetch_array($result)) {
		
			$ArrayResult[] = $rows[0];
		}
	} 
	else 
		$error = "error2";
}

header('Expires: Wed, 23 Dec 1980 00:30:00 GMT'); // time in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header("Content-type: text/xml; charset=windows-1251");

$xml = "<?xml version=\"1.0\" encoding=\"windows-1251\" standalone=\"yes\"?>
<response>
<scripteval>ShowNameCoins</scripteval>";

if (!$error)
{
	$xml .= "<error>none</error>
	<arrayresult>".implode(",",$ArrayResult)."</arrayresult>";
}
else
{
	$xml .= "<error>".$error."</error>";
}

$xml .= "</response>";
echo $xml;

?>