<?	
if($tpl['shop']['errors']){?>
	<font color="red"><?=implode("<br>",$tpl['shop']['errors'])?></font>
<?} else {
include('pager.tpl.php');
 /*?>

<div id='ShowShopcoinsm'></div>
<form action=?pageinfo=cookies&typeorder=1&pagenum=<?=$tpl['pagenum']?><?=($tpl['search']?"&search=".urlencode($tpl['search']):"").($tpl['searchid']?"&searchid=".$tpl['searchid']:"")?>"&group=<?=$tpl['group']?><?=($tpl['pricestart']?"&pricestart=".$tpl['pricestart']:"").($tpl['priceend']?"&priceend=".$tpl['priceend']:"")?>" method=post name=mainform>
<input type=hidden name=shopcoinsorder value=''>
<input type=hidden name=shopcoinsorderamount value=''>
<input type=hidden name=materialtype value=''>

<?*/?>
<div id='products' style="width:800px;float:left;">
<?
$i=1;
foreach ($tpl['shop']['MyShowArray'] as $key=>$rows){	
	$textoneclick = '';	
	if (($rows['materialtype']==2 || $rows['materialtype']==4 || $rows['materialtype']==7 || $rows['materialtype']==8 || $rows['materialtype']==6) && $rows['amount']>10) 
		$rows['amount'] = 10;	
		
	if(in_array($rows["materialtype"],array(7,4))){
		echo "<div class='blockshop_spisok'>";
		include('items/item_nabor.tpl.php');
		echo "</div>";
	} else {
		echo "<div class='blockshop'>";
		include('items/item.tpl.php');
		echo "</div>";
	}	
	$i++;	
}

/*
if (sizeof($ShopcoinsThemeArray) or sizeof($ShopcoinsGroupArray))
{
	$sql = "select shopcoinsbiblio.shopcoinsbiblio from shopcoinsbiblio, shopcoinsbibliorelationgroup 
	where (
	".(sizeof($ShopcoinsGroupArray)?"(shopcoinsbibliorelationgroup.value in (".implode(",", $ShopcoinsGroupArray).") and type='group')":"")." 
	".(sizeof($ShopcoinsThemeArray)?" or (shopcoinsbibliorelationgroup.value in (".implode(",", $ShopcoinsThemeArray).")  and type='theme') ":"")." 
	)
	and shopcoinsbiblio.shopcoinsbiblio = shopcoinsbibliorelationgroup.shopcoinsbiblio
	group by shopcoinsbiblio.shopcoinsbiblio";
	
	//if ($REMOTE_ADDR=="194.85.82.223")
		//echo $sql;
}

echo "</div>";
echo $page_print."<br>";*/
}
?>