<?
require $cfg['path'] . '/configs/config_shopcoins.php';
$delivery = request('delivery');
?>
<html>
<head>
<title>Доставка</title>
</head>
<body bottommargin=5 leftmargin=5 rightmargin=5 marginheight=5 topmargin=5 marginwidth=5>
<p class=tboard><b><?=$DeliveryName[$delivery]?></b>
<br><?=$DeliveryProperties[$delivery]?></p>
<center><a href=# onclick=window.close()>Закрыть окно</a></center>
</body>
</html>
<?
die();
?>