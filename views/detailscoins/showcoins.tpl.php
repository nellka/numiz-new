<div class="bordered">
<div class="table" style="width: 100%;">
<h1 class=left>Описание монет</h1>
<table border=0 cellpadding=0 cellspacing=0 width=300 style="float:right">
<tr><td class=tboard>  Вы авторизовались как: <strong><?=$tpl['user']['username']?></strong>
<br>  Вы обработали <b><?=$AmountCoinsRedaction?></b> монет(ы)
<br>  На обработке еще <strong><?=$countpubs?></strong> монет(ы)</td><td></td></tr>
</table>
</div>

<?
$ArrayWrite = array();

if (!$result_main){
	echo "<br><p class=txt><b><font color=red>Извините, нет монет, выставленных для описания.</font></b><br><br>";
} else {?>
<div class="table"></div>
	<div class="right">
	<?=$tpl['paginator']->printPager();?>
	</div>
	<div id=ShowShopcoins></div>
	<table border=0 cellpadding=3 cellspacing=1 width=99% bgcolor=#ffffff>
	<?
	$i=0;
	foreach ($result_main as $rows ){		
		if ($i%2==0) 
			echo "<tr bgcolor=#ffffff><td valign=top>";
		else
			echo "<td valign=top>";
		?>		
		<a href=# onClick="showWindow('<?=$cfg['site_dir']?>/detailscoins/addcomment.php?coins=<?=$rows['shopcoinswrite']?>&ajax=1&pagenum=<?=$pagenum?>','1000');return false;"><img src="<?=$cfg['site_dir']?>detailscoins/images/<?=$rows['image']?>" border=1 style='border-color:black'></a> 
		<a href=# onClick="showWindow('<?=$cfg['site_dir']?>/detailscoins/addcomment.php?coins=<?=$rows['shopcoinswrite']?>&ajax=1&pagenum=<?=$pagenum?>','1000');return false;"><img src="<?=$cfg['site_dir']?>/images/corz12.gif" border=0 ></a>
		<?
		$i++;
	}
	
	if ($i%2==0) 
		echo "</tr>";
	else
		echo "</td><td>&nbsp;</td></tr>";
		
	echo "</table>";?>
	<div style="display:table" ></div>
	<div class="right">
	<?=$tpl['paginator']->printPager();?>
	</div>

<?}?>
<script>
function showWindow(href){
    $(".ui-icon.ui-icon-closethick").trigger("click");
	//console.log($('#MainBascet'));
     $('#MainBascet').dialog({
        modal: true,
        position: ['center',150],  
             
        open: function (){   
             $(this).load(href);
        },
        width: 1050
    });
     return false;   
} 
</script>