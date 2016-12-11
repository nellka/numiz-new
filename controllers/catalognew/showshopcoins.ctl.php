<?
include $DOCUMENT_ROOT."/config.php";
//include_once $DOCUMENT_ROOT."/funct.php";
//include "funct.php";
//include "config.php";

$shopcoins = $_GET["shopcoins"];

header('Expires: Wed, 23 Dec 1980 00:30:00 GMT'); // time in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header("Content-type: text/xml; charset=windows-1251");

$xml = "<?xml version=\"1.0\" encoding=\"windows-1251\" standalone=\"yes\"?>
<response>
<scripteval>".($catalog?"ShowShopcoins":"AdminShowShopcoins")."</scripteval>";

if ($catalog or $shopcoins)
{
	if ($catalog)
	{
		$catalog = intval($catalog);
		$sql = "select shopcoins.*, `group`.name as gname
		from catalogshopcoinsrelation, shopcoins, `group`
		where catalogshopcoinsrelation.catalog = '$catalog' 
		and shopcoins.shopcoins = catalogshopcoinsrelation.shopcoins
		and shopcoins.`check`=1 and shopcoins.dateinsert>0 and shopcoins.dateorder=0
		and shopcoins.group = `group`.`group`
		limit 1";
	}
	elseif ($shopcoins)
	{
		$shopcoins = intval($shopcoins);
		$sql = "select shopcoins.*, `group`.name as gname
		from shopcoins, `group`
		where 
		shopcoins.group = group.group
		and shopcoins.shopcoins = '$shopcoins' 
		and shopcoins.dateinsert>0 and shopcoins.dateorder=0
		and shopcoins.group = `group`.`group`
		limit 1";
		//echo $sql;
		//exit;
	}
	
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	
	if (!$rows[0])
	{
		$error[] = 'К сожалению, уже продана!';
	}
	
	if (!sizeof($error))
	{
		$xml .= "<error>none</error>
		<valueid>".$rows["shopcoins"]."</valueid>
		<country>".strip_tags($rows["gname"])."</country>
		<name>".($rows["name"]?strip_tags($rows["name"]):"empty")."</name>
		<number>".($rows["number"]?$rows["number"]:"empty")."</number>
		<price>".($rows["price"]?$rows["price"]:"empty")."</price>
		<metal>".($rows["metal"]?$rows["metal"]:"empty")."</metal>
		<condition>".($rows["condition"]?$rows["condition"]:"empty")."</condition>
		<year>".($rows["year"]?$rows["year"]:"empty")."</year>
		<image>".$rows["image_big"]."</image>";
	}
	else
	{
		$xml .= "<error>".implode(',',$error)."</error>";
	}
}
else
{
	$xml .= "<error>empty</error>";
}

$xml .= "</response>";
echo $xml;
die();
?>