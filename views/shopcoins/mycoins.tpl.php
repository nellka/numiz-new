<?
if ($rows['check']=1 && $rows['materialtype']!=1 && $rows['amount']>0) {
			
				echo "<td width=40% valign=top> <br> <a href=#  onclick=\"javascript:ShowShopcoinsStart2();process('showshopcoins.php?shopcoins=".$rows["shopcoins"]."')\" title='Посмотреть подобную позицию в магазине'>Посмотреть подобную позицию в магазине</a>";
			}
			else {
			
				$sql_c = "select `catalog` from catalogshopcoinsrelation where shopcoins=".$rows['shopcoins'].";";
				$result_c = mysql_query($sql_c);
				@$rows_c = mysql_fetch_array($result_c);
				if ($rows_c['catalog']>0) {
				
					$sql_m = "select shopcoins.* from shopcoins,catalogshopcoinsrelation where shopcoins.shopcoins=catalogshopcoinsrelation.shopcoins and shopcoins.`check`=1 and shopcoins.amount>0 and shopcoins.shopcoins!=".$rows['shopcoins']." and catalogshopcoinsrelation.catalog=".$rows_c['catalog']." order by ".$dateinsert_orderby." desc limit 1;";
					$result_m = mysql_query($sql_m);
					@$rows_m = mysql_fetch_array($result_m);
					if ($rows_m[0]>0) {
					
						echo "<td width=40% valign=top> <br><a href=#  onclick=\"javascript:ShowShopcoinsStart2();process('showshopcoins.php?shopcoins=".$rows_m["shopcoins"]."')\" title='Посмотреть подобную позицию в магазине'>Посмотреть подобную позицию в магазине</a>";
					}
				}
			}
?>