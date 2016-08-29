<?
require_once $cfg['path'] . '/models/crons.php';

$lastday = 3;
$limitprice = 200;

$subject = "Клуб Нумизмат |  Вы хотели сделать заказ?";
$headers = "From: Numizmatik.Ru<administrator@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";

$cron_class = new crons($cfg['db']);

$timenow = mktime(0,0,0, date("m", time()), date("d", time()), date("Y", time()));

$sql = "select shopcoins_search.*,user.fio,user.email,user.user from `order`, orderdetails,shopcoins_search,`user` where orderdetails.order=order.order and order.check=0 and order.user!=811 and order.user=user.user and user.userstatus=0 and user.sumlimit=0 and order.date>".($timenow-($lastday+1)*24*3600)." and order.date<".($timenow-$lastday*24*3600)." 
and (shopcoins_search.shopcoins=orderdetails.catalog or (shopcoins_search.parent=orderdetails.catalog and shopcoins_search.materialtype in (7,8))) and shopcoins_search.check=1 and order.user>0 and orderdetails.status=0 and shopcoins_search.price>=$limitprice
and shopcoins_search.shopcoins not in(select expouserid.shopcoins from expouserid,calling where calling.result=2 and calling.calling=expouserid.calling and expouserid.check!=2 and expouserid.user=order.user and expouserid.dateinsert>".($timenow - 60*24*3600).") 
and user.email like '%@%' 
 order by user.user,user.email ;";

$result = $cron_class->getDataSql($sql);

$fio = '';
$user = 0;
$email = '';
$mailmessage = '';
$arrayshopcoins = array();
$content ='';

foreach ($result as $rows) {
	if ($email != $rows['email']) {
	
		if ($email !='') {		
			$href = '';
			
			if (sizeof($arrayshopcoins)>0) {			
				$href = "<a href=\"http://www.numizmatik.ru/shopcoins/index.php?page=orderdetails&member=$user&recoins=".implode("d",$arrayshopcoins)."\" target=_blanck>положить все в корзину одним кликом</a>";
				$mailmessage.= "</table>";	
			}
			
			$mailmessage = str_replace("___hrefplace___",$href,$mailmessage)."<br>".$href;
			$subject = "Клуб Нумизмат | Монетная лавка";
			$mailmessage = MakeMessage ( $mailmessage);
						
			$cron_class->SaveMailDb ($email, $subject, $mailmessage, $headers,1);
						
			$mailmessage = '';
			$content ='';
			$arrayshopcoins = array();
		}
		$email = $rows['email'];
		$user = $rows['user'];
		$mailmessage .= "<br><b>Добрый день, уважаемый (ая) ".$rows["fio"]."</b><br>
		<br>Вы интересовались данными позициями, но возможно что-то помешало Вам сделать заказ. Вы можете ___hrefplace___ либо перейти в магазин по нижеприведенным ссылкам, посмотреть и оформить заказ, если данные позиции Вас еще интересуют.";
		
		$mailmessage .= "<table border=0 cellpadding=0 cellspacing=0 width=630 style='border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;'>
				<tr style='background-color:#eeeeee;'>";
        $mailmessage .= "<td  style='border:1px solid #cccccc;padding:10px;'></td>";
        $mailmessage .= "<td style='border:1px solid #cccccc;padding:10px;'>Группа (страна)</td>";
        $mailmessage .= "<td  style='border:1px solid #cccccc;padding:10px;'>Наименование</td>";
        $mailmessage .= "<td style='border:1px solid #cccccc;padding:10px;'>Год</td>";  			    
        $mailmessage .= "<td style='border:1px solid #cccccc;padding:10px;'>Металл</td>"; 
        $mailmessage .= "<td  style='border:1px solid #cccccc;padding:10px;'>Цена</td> ";
        $mailmessage .= "</tr>";	
	}
	
	$rows_content = $shopcoins_class->getItem($rows["shopcoins"],true);

	$mailmessage .= '<tr><td  style="border:1px solid #cccccc;padding:10px;"><img style="max-width:100px;border:1px solid #cccccc;" src="http://www.numizmatik.ru/shopcoins/images/'.$rows_content["image_small"].'"/></td>';
    $mailmessage .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$rows_content["gname"].'</td>';
    $mailmessage .= '<td  style="border:1px solid #cccccc;padding:10px;"><a href="http://www.numizmatik.ru/shopcoins/show.php?catalog='.$rows_content["shopcoins"].'" target=_blank>'.$rows_content["name"].'</a></td>';    	
	$mailmessage .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$rows_content["year"].'</td>';
	
	$mailmessage .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$tpl['metalls'][$rows_content["metal_id"]].'</td>';
    $mailmessage .= '<td  style="border:1px solid #cccccc;padding:10px;">'.round($rows_content['price'],2).' руб.</td>';
    $mailmessage .= '</tr>';
            
	/*$mailmessage .= "<br><br><font color=blue><b>".$rows["gname"]."</b></font>, <b>".$rows["name"]."</b>, <font color=red><b>".$rows["price"]." руб.</b></font> ".
			"<br><a href=http://www.numizmatik.ru/shopcoins/index.php?page=show&catalog=".$rows["shopcoins"]." target=_blank>http://www.numizmatik.ru/shopcoins/index.php?page=show&catalog=".$rows["shopcoins"]."</a>";*/
	
	
	$arrayshopcoins[] = $rows["shopcoins"];
}



if ($email !='') {
	
	$href = '';
	if (sizeof($arrayshopcoins)>0) {
	
		$href = "<a href='http://www.numizmatik.ru/shopcoins/index.php?page=orderdetails&member=$user&recoins=".implode("d",$arrayshopcoins)."' target=_blanck>положить все в корзину одним кликом</a>";
	}
	$mailmessage.= "</table>";	
	$mailmessage = str_replace("___hrefplace___",$href,$mailmessage)."<br>".$href;	
	//$mailmessage = $templatetopic["news"].$Message["news"];
	$subject = "Клуб Нумизмат | Монетная лавка";
	$mailmessage = MakeMessage ($mailmessage);
	$cron_class->SaveMailDb ($email, $subject, $mailmessage, $headers,1);
	$mailmessage = '';
}


function MakeMessage ($mailmessage)
{

	$mailtext ="<table border='0' cellpadding='0' cellspacing='0' width='650' style='border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;'>"; 
	$mailtext .="<tr><td style='border:1px solid #cccccc; background-color:#eeeeee;padding:10px;font-size:14px;font-weight:bold;'>Монетная лавка</td></tr>";
	$mailtext .= "<tr><td style='padding: 10px;'>";
	$mailtext .= $mailmessage;
	$mailtext .= "</td></tr>";
    $mailtext .= "<tr><td style='padding: 10px;'>";
    $mailtext .= "С уважением, Клуб Нумизмат.<p>Монетная лавка - <a href=http://www.numizmatik.ru/shopcoins/>http://www.numizmatik.ru/shopcoins/</a>";
    $mailtext .= "<p>Е-mail: <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a></p>";		
	$mailtext .= "</td></tr></table>";	

	return $mailtext;	
}
die('done');
?>