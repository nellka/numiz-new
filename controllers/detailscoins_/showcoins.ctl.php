<?
//$MainFolderMenuX=1;
//$MainFolderMenuY=19;
if ($HTTP_SERVER_VARS["HTTP_USER_AGENT"] == "Web Downloader/6.8"
or $HTTP_SERVER_VARS["HTTP_USER_AGENT"] == "Web Downloader/7.2"
or substr_count($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"coona")
or substr_count($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"Rufus")
or $REMOTE_ADDR=="67.159.4.134" 
or $REMOTE_ADDR=="217.172.29.30"
or substr_count($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"Robot")
)
{
	echo "<h1>Скачивание информации запрещено!!!Если Вы считаете что это ошибка, свяжитесь с администратором по телефону 8-926-236-31-92.</h1>";
	//mail("bodka@rt.mipt.ru", "Много", "==".$cookiesuserlogin."==".$REMOTE_ADDR." ==".$order, "From: Numizmatik.Ru<administrator@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"");
	exit;
}

include $_SERVER["DOCUMENT_ROOT"]."/config.php";
include "config.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/funct.php";

if (!in_array($_COOKIE['cookiesuser'],$ArrayUsers)) {
	echo "<h1>Not found</h1>";
	exit;	
}
if (!$pagenum)
	$pagenum = 1;

if ($cookiesfio)
{
	$user = $cookiesuser;
}

if ($group)
{
	
	unset($arraygroupauction);
	$group = intval($group);
	$sql = "select * from `group` where `group`='$group' or groupparent='$group';";
	$result = mysql_query($sql);
	while ($rows = mysql_fetch_array($result)) {
		
		$arraygroupauction[] = $rows['group'];
		
		if ($group==$rows['group'])
			$GroupName = $rows["name"];
	}
}

//	$sql = "select sum(shopcoinswriteusernum.amount) as sumamount, user.`userlogin` from shopcoinswriteusernum,`user` 
//		where shopcoinswriteusernum.`user`=`user`.`user` and shopcoinswriteusernum.check=1 and shopcoinswriteusernum.dateinsert>'$datestartcomment' group by shopcoinswriteusernum.user order by sumamount desc limit 3;";
//	$result = mysql_query($sql);
	//echo $sql;
		
/*
if (!$page)
	$MainText .= "
	<br><table border=0 cellpadding=0 cellspacing=0 width=90% align=center>
	<tr>
	<td width=99% class=tboard align=right>&nbsp;";
	if (mysql_num_rows($result)>0) {
	
		$i = 1;
		$MainText .= "<b>Бонусы* с ".date('H:i d-m-Y',$datestartcomment)." по ".date('H:i d-m-Y',$datestartcomment+7*24*60*60)." :</b><br>";
		while ($rows = mysql_fetch_array($result)) {
		
			$MainText .= "".$i." место <b><font color=navy>".$rows['userlogin']."</font><font color=red> ".$ArrayBonus[$i]." руб.</font>&nbsp;&nbsp;</b><br>";
			$i++;
		}
		$MainText .= "*Данные бонусы можно использовать только для покупки в магазине \"Клуба Нумизмат\"";
	}
	$MainText .= "</td>
	</tr>
	</table><br>";
*/
if (!$page)
{
	
	if ($pagenum>$maxpage)
		$pagenum = $maxpage;
	//счетчик
	
	$AmountCoinsRedaction = 0;
	
	$sql_t = "select * from shopcoinswriteusernum where `user`='$user' and `check` in(0,1);";
	$result_t = mysql_query($sql_t);
	while($rows_t = mysql_fetch_array($result_t)) {
	
		if ($rows_t['check']==0)
			$ArrayTemp[] = $rows_t['shopcoinswrite'];
		else
			$AmountCoinsRedaction ++;
	}
	
	$sql = "Select count(*) from shopcoinswrite where `check`=1 and (reservetime<'".time()."' or `user`='$user') ".(sizeof($ArrayTemp)?"and shopcoinswrite not in(".implode(",",$ArrayTemp).")":"").";";
	//echo $sql."<br>";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);$countpubs=$row[0];
	
	$onpage=16;
	$numpages=15;$pagenum=round($pagenum);
	$pages=ceil($countpubs/$onpage);
	
	if (!$pages) 
		$pages=1;
	
	if ($pagenum<1) 
		$pagenum=1; 
	
	if ($pagenum>$pages) 
		$pagenum=$pages;
	
	$i=1;
	
	$frompage=floor(($pagenum-1)/$numpages)*$numpages+1;
	$topage=ceil($pagenum/$numpages)*$numpages;
	
	if ($topage>$pages) 
		$topage=$pages;
	
	if ($pagenum>2*$numpages) 
		$page_string .= "<a href='$script?pagenum=1'>[в начало]</a> | ";
	
	if ($frompage>$numpages) 
		$page_string .= "<a href='$script?pagenum=".($frompage-1)."'><<пред</a> | ";
	
	for ($i=$frompage;$i<=$topage;$i++) {
		if ($i==$pagenum) 
			$page_string .= "<b>$i</b>";
		elseif ($i>$maxpage) 
			$page_string .= "<font color=gray><b>$i</b></font>";
		else 
			$page_string .= "<a href='$script?pagenum=$i'>$i</a>";
			
		if ($i<$topage) $page_string .= " | ";
	}
	      
	if ($pages>$topage) {
		
		if ($topage>=$maxpage)
			$page_string .= " | <font color=gray><b>далее>></b></font>";
		else
			$page_string .= " | <a href='$script?pagenum=$i'>далее>></a>";
	}
	
	$MainText .= "<p class=txt><b>Страницы: </b>".$page_string."</p>";
}
	
//=================================================

unset($orderby);

if (!$orderby) {
	
	$orderby = "order by shopcoinswrite desc ";
	$sort = 1;
}

include $_SERVER["DOCUMENT_ROOT"]."/keywords.php";

$title = "Клуб Нумизмат | Описание монет ";
	
	
	//$Meta = GetMeta ("shopcoins", "keywords", "details", $where, "order by shopcoins.dateinsert desc, shopcoins.price desc", ($pagenum-1)*$onpage, $onpage);
	$keywords = $_Keywords; //"Монеты, ".$Meta[0];
	$description = $_Descriptionauction; //$Meta[1];



include $_SERVER["DOCUMENT_ROOT"]."/top.php"; 

$arraytitle = explode("|", $title);
$tmptitle = $arraytitle[1]." | ".$arraytitle[2];

table_top ("100%", 0, $tmptitle, 1);

echo "<table border=0 cellpadding=0 cellspacing=0 width=99%>
<tr><td class=tboard>  Вы авторизовались как: <strong>$cookiesuserlogin</strong>
<br>  Вы обработали <b>$AmountCoinsRedaction</b> монет(ы)
<br>  На обработке еще <strong>$countpubs</strong> монет(ы)".$MainText."</td><td></td></tr>
</table>";

unset ($MainText);
/*
if ($theme or $yearstart or $metal or $search)
{
	echo "
	<script language=javascript>
	SearchByDetails();
	</script>";
}*/

if (!$page)
{
	
	if (!$pagestart) 
		$pagestart=0;
	
	if (!$onpage)
		$onpage = 16;
	
	$limit = " limit ".($pagenum-1)*$onpage.",$onpage";
	
	$sql_main = "select * from shopcoinswrite where `check`=1 and (reservetime<'".time()."' or `user`='$user') ".(sizeof($ArrayTemp)?"and shopcoinswrite not in(".implode(",",$ArrayTemp).")":"")."
	$orderby 
	$limit;";
	
	//	group by auction.auction 
	
	//if ($cookiesuser == 16015)
		//echo "<br>For nommail@mail.ru: <br>".$sql_auction;
	//echo $sql_main."<br>";
	$result_main = mysql_query($sql_main);
	
	unset ($rows);
	
	$ArrayWrite = array();
/*	
	if ($cookiesuser) {
	
		$sql_tmp = "select * from shopcoinswriteuser where `user`='$cookiesuser' and `check`=1;";
		$result_tmp = mysql_query($sql_tmp);
		while ($rows_tmp = mysql_fetch_array($result_tmp)) {
		
			$ArrayWrite[] = $rows_tmp['shopcoinswrite'];
		}
	}
*/
	//$result = mysql_query($sql);
	//if (!mysql_num_rows($result))
	if (mysql_num_rows($result_main)==0)
	{
		echo "<br><p class=txt><b><font color=red>Извините, нет монет, выставленных для описания.</font></b><br><br>";
	} else {
		echo "<div id=ShowShopcoins></div>
		<table border=0 cellpadding=3 cellspacing=1 width=99% bgcolor=#ffffff>";
		$i=0;
		//while ($rows = mysql_fetch_array($result))
		while ($rows = mysql_fetch_array($result_main))
		{
			
			if ($i%2==0) 
				echo "<tr bgcolor=#ffffff><td valign=top>";
			else
				echo "<td valign=top>";
			
			echo "<a href=# onClick=\"window.open('addcomment.php?coins=".$rows['shopcoinswrite']."&pagenum=".$pagenum."','Catalog','status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=750');return false;\"><img src=./images/".$rows['image']." border=1 style='border-color:black'></a> 
			<a href=# onClick=\"window.open('addcomment.php?coins=".$rows['shopcoinswrite']."&pagenum=".$pagenum."','Catalog','status=no,menubar=no,scrollbars=yes,resizable=no,width=670,height=650');return false;\"><img src=../images/corz12.gif border=0 ".(in_array($rows['shopcoinswrite'],$ArrayWrite)?"style=\"FILTER: Alpha(Opacity=30,FinishOpacity=30,Style=3) gray\"":"")."></a>";
			
			$i++;
		}
		
		if ($i%2==0) 
			echo "</tr>";
		else
			echo "</td><td>&nbsp;</td></tr>";
			
		echo "</table>";
		echo $page_print."<br>";
		
		echo "<p class=txt><b>Страницы: </b>".$page_string."</p>";
	}
}

include $in."socialzakladki.php";

table_down ("", "100%");

include $in."bottomsmall.php";
?>