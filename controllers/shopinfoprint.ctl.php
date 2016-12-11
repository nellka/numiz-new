<?
require $cfg['path'] . '/configs/config_shopcoins.php';

?>
<html>
<head>
<title>Доставка и оплата</title>
<link rel=stylesheet type=text/css href='<?=$cfg['site_dir']?>css/main.css'>
</head>
<body bottommargin=5 leftmargin=5 rightmargin=5 marginheight=5 topmargin=5 marginwidth=5 onload="window.print()">
<?
require $cfg['path'] . '/views/static_pages/shopinfo.tpl.php';
?>
</body>
</html>
<?
die();
?>