<?
require $cfg['path'] . '/configs/config_shopcoins.php';
$payment = request('payment');
?>
<html>
<head>
<title>Оплата</title>
</head>
<body>
<p class=tboard><b><?=$SumName[$payment]?></b>
<br><?=$SumProperties[$payment]?></p>

<center><a href=# onclick=window.close()>Закрыть окно</a></center>
</body>
</html>
<?
die();
?>