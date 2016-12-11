<?
//exit("is exist!");
include_once $DOCUMENT_ROOT."/config.php";
set_time_limit(0);
?><html>
<head>
	<title><?=$title?></title>
	<meta name="keywords" content="<?=$keywords?>">
	<meta name="description" content="<?=$description?>">
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<link rel=stylesheet type=text/css href=<?=$in?>bodka.css>
<link rel="SHORTCUT ICON" href="<?=$in?>favicon.ico"> 

	<style>
	.xl49
	{mso-style-parent:style0;
	text-align:center;
	border-top:none;
	border-right:.5pt solid windowtext;
	border-bottom:.5pt solid windowtext;
	border-left:none;
	background:#EBE4D4;
	mso-pattern:auto none;}
	</style>


<?

$arraymetal = array (

""=>1,
"золото 900/1000"=>3,
"золото 900/1000 - серебро 900/1000"=>2,
"золото 900/1000 - серебро 925/1000"=>2,
"золото 999/1000"=>3,
"латунь"=>1,
"латунь, мельхиор"=>1,
"латунь-медь, никель"=>1,
"латунь/медь, никель"=>1,
"латунь/медь,никель"=>1,
"латунь/мельхиор"=>1,
"медь,никель"=>1,
"медь,цинк/медь, никель"=>1,
"медь,цинк/медь,никель"=>1,
"нейзильбер"=>1,
"палладий 999/1000"=>5,
"платина 999/1000"=>4,
"серебро 500/1000"=>2,
"серебро 900/1000"=>2,
"серебро 900/1000 - золото 900/1000"=>2,
"серебро 925/1000"=>2,
"серебро 925/1000 - золото 999/1000"=>2,
"серебро 925/1000/золото 999/1000"=>2,
"серебро 999/1000"=>2

);

$arrayname = array (

"1 000 рублей"=>21,
"1 рубль"=>10,
"10 000 рублей"=>22,
"10 рублей"=>14,
"100 рублей"=>18,
"150 рублей"=>19,
"2 рубля"=>11,
"20 рублей"=>15,
"200 рублей"=>20,
"25 000 рублей"=>23,
"25 рублей"=>16,
"3 рубля"=>12,
"5 рублей"=>13,
"50 000 рублей"=>24,
"50 рублей"=>17

);

$arraycondition = array (

"АЦ"=>2,
"б/а"=>2,
"пруф"=>3,
"пруф-лайк"=>3

);
	$sql = "select max(a_pricecoins) from a_pricecoins;";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	$start = $rows[0];
	
	$sql = "select max(parent) from a_pricecoins;";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	$parent = $rows[0];

	$sql = "select * from RussianCoinsCatalog;";
	$result = mysql_query($sql);
	while ($rows = mysql_fetch_array($result)) {
	
		$details = $rows['Name'];
		$metal = $arraymetal[trim($rows['Metall'])];
		$year = 1991+$rows['RussianCoinsYear'];
		if ($metal==1)
			$group = 124;
		elseif ($metal==2)
			$group = 125;
		else
			$group = 126;
		$name = $arrayname[trim($rows['Value'])];
		$parent++;
		for ($i=1;$i<=4;$i++) {
		
			$start++;
			$sql_ins = "insert into `a_pricecoins` (`a_pricecoins`,`a_name`,`a_group`,`year`,`a_condition`,`details`,`check`,`a_metal`,`rassiancoins`,`parent`)
			values ($start,'$name','$group','$year','$i','$details','1','$metal','".$rows['RussianCoinsCatalog']."','$parent');";
			mysql_query($sql_ins);
			
		}
	}
	
	
?>

</body>
</html>
