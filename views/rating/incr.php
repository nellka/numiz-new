<html>
<meta http-equiv="Cache-Control" content="no-cache">
<body>
<center>
<br><br><a href=http://www.numizmatik.ru/rating/index.php?ratinguser=<? echo $ratinguser;?> target=_blank>
<img src="http://www.numizmatik.ru/rating/rating.php?ratinguser=<? echo $ratinguser;?>" border=0></a><br>
<?

include $DOCUMENT_ROOT."/config.php";

$MainFolderMenuX=4; 
$MainFolderMenuY=1;

$sql = "Select ratinguser from ratinguser where check=1;";
$result = mysql_query($sql);
if (mysql_num_rows($result)>0)
{
	while ($rows = mysql_fetch_array($result))
	{
		echo " | <a href=$script?ratinguser=$rows[0]>$rows[0]</a> | ";
	}
}

?>
</center>
</body>
</html>