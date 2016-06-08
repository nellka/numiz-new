<?
//exit;
include $DOCUMENT_ROOT."/config.php";
include $DOCUMENT_ROOT."/funct.php";

//echo mktime(0,0,0,5,7,2006);

//exit;

$Message = Array();

$templatetopic["news"] = "
<br><table cellpadding=3 cellspacing=1 border=0 width=100% align=center>
<tr bgcolor=#99CCFF valign=top><td><b>Новости нумизматики</b></td></tr>
</table>";

$templatetopic["tboard"] = "
<br><table cellpadding=3 cellspacing=1 border=0 width=100% align=center>
<tr bgcolor=#99CCFF valign=top><td><b>Конференция</b></td></tr>
</table>";

$templatetopic["blacklist"] = "
<br><table cellpadding=3 cellspacing=1 border=0 width=100% align=center>
<tr bgcolor=#99CCFF valign=top><td><b>Черный список</b></td></tr>
</table>";

$templatetopic["buycoins"] = "
<br><table cellpadding=3 cellspacing=1 border=0 width=100% align=center>
<tr bgcolor=#99CCFF valign=top><td><b>Нужны монеты</b></td></tr>
</table>";

$templatetopic["biblio"] = "
<br><table cellpadding=3 cellspacing=1 border=0 width=100% align=center>
<tr bgcolor=#99CCFF valign=top><td><b>Библиотека</b></td></tr>
</table>";

$templatetopic["advertise"] = "
<br><table cellpadding=3 cellspacing=1 border=0 width=100% align=center>
<tr bgcolor=#99CCFF valign=top><td><b>Доска объявлений</b></td></tr>
</table>";

$template["news"] = "
<br><table cellpadding=3 cellspacing=1 border=0 width=100% align=center>
<tr bgcolor=#ffcc66><td width=\"15%\"><b>___Date___</b></td><td><b>___Title___</b></td></tr>
<tr bgcolor=#EBE4D4><td colspan=2>___ShortContent___</td></tr>
<tr bgcolor=#EBE4D4><td colspan=2><a href=___Url___ target=_blank>___Url___</a></td></tr>
</table>";

$template["tboard"] = "
<br><table cellpadding=3 cellspacing=1 border=0 width=100% align=center>
<tr bgcolor=#ffcc66><td width=\"15%\"><b>___Date___</b></td><td><b>___Title___</b></td></tr>
<tr bgcolor=#EBE4D4><td colspan=2>___ShortContent___</td></tr>
<tr bgcolor=#EBE4D4><td colspan=2><a href=___Url___ target=_blank>___Url___</a></td></tr>
</table>";

$template["blacklist"] = "
<br><table cellpadding=3 cellspacing=1 border=0 width=100% align=center>
<tr bgcolor=#ffcc66><td width=\"15%\"><b>___Date___</b></td><td><b>___Title___</b></td></tr>
<tr bgcolor=#EBE4D4><td colspan=2>___ShortContent___</td></tr>
<tr bgcolor=#EBE4D4><td colspan=2><a href=___Url___ target=_blank>___Url___</a></td></tr>
</table>";

$template["buycoins"] = "
<br><table cellpadding=3 cellspacing=1 border=0 width=100% align=center>
<tr bgcolor=#ffcc66><td colspan=2><b>Заявка № ___number___ от ___Date___</b></td></tr>
<tr bgcolor=#EBE4D4><td width=\"20%\">Пользователь:</td><td>___Login___ (___star___)</td></tr>
<tr bgcolor=#EBE4D4><td>Название:</td><td>___Name___</td></tr>
<tr bgcolor=#EBE4D4><td>Год:</td><td>___Year___</td></tr>
<tr bgcolor=#EBE4D4><td>Страна:</td><td>___Country___</td></tr>
<tr bgcolor=#EBE4D4><td>Цена:</td><td>___Price___</td></tr>
<tr bgcolor=#EBE4D4><td>Металл:</td><td>___Metal___</td></tr>
<tr bgcolor=#EBE4D4><td>Дополнительно:</td><td>___Details___</td></tr>
<tr bgcolor=#EBE4D4><td colspan=2><a href=___Url___ target=_blank>___Url___</a></td></tr>
</table>";

$template["biblio"] = "
<br><table cellpadding=3 cellspacing=1 border=0 width=100% align=center>
<tr bgcolor=#ffcc66><td width=\"15%\"><b>___Date___</b></td><td><b>___Title___</b></td></tr>
<tr bgcolor=#EBE4D4><td colspan=2>___ShortContent___</td></tr>
<tr bgcolor=#EBE4D4><td colspan=2><a href=___Url___ target=_blank>___Url___</a></td></tr>
</table>";

$template["advertise"] = "
<br><table cellpadding=3 cellspacing=1 border=0 width=100% align=center>
<tr bgcolor=#ffcc66><td width=\"15%\"><b>___Date___</b></td><td><b>___Fio___</b></td></tr>
<tr bgcolor=#EBE4D4><td colspan=2>___ShortContent___</td></tr>
<tr bgcolor=#EBE4D4><td colspan=2><a href=___Url___ target=_blank>___Url___</a></td></tr>
</table>";

//выбираем даты последних рассылок
$sql = "select * from subscribesystem order by date desc limit 1;";
$result = mysql_query($sql);
$rows_info = mysql_fetch_array($result);

echo $sql;
die('kkk');
$newsdate = $rows_info["news"];
$tboarddate = $rows_info["tboard"];
$blacklistdate = $rows_info["blacklist"];
$buycoinsdate = $rows_info["buycoins"];
$bibliodate = $rows_info["biblio"];
$advertisedate = $rows_info["advertise"];

$sql = "select * from news where date > '$newsdate' and `check`=1 order by date desc limit 10;";
$result = mysql_query($sql);
if (mysql_num_rows($result) > 3)
{
	while ($rows = mysql_fetch_array($result))
	{
		unset ($str);
		unset ($text);
		$str = str_replace("___Date___", date("Y-m-d", $rows["date"]), $template["news"]);
		$str = str_replace("___Title___", $rows["name"], $str);
		
		$text = substr(strip_tags($rows["text"]), 0, 400);
		while(substr_count($text,"<<<"))
			$text=substr($text,0,strpos($text,"<<<")).substr(strstr($text,">>>"),3);
		
		$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
		
		$str = str_replace("___ShortContent___", $text, $str);
		$namehref = strtolower_ru($rows["name"])."_n".$rows["news"].".html";
		
		$str = str_replace("___Url___", "http://news.numizmatik.ru/".$namehref, $str);
		
		$Message["news"] .= $str;
		//echo $str;
	}

	$newsdate = time();
}

$sql = "select * from tboard where date > '$tboarddate' and `check`=1 and parent='0' order by date desc limit 10;";
$result = mysql_query($sql);
if (mysql_num_rows($result) > 3)
{
	while ($rows = mysql_fetch_array($result))
	{
		unset ($str);
		unset ($text);
		$str = str_replace("___Date___", date("Y-m-d", $rows["date"]), $template["tboard"]);
		$str = str_replace("___Title___", $rows["title"], $str);
		
		$text = substr(strip_tags($rows["description"]), 0, 400);
		while(substr_count($text,"<<<"))
			$text=substr($text,0,strpos($text,"<<<")).substr(strstr($text,">>>"),3);
		$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
		$str = str_replace("___ShortContent___", $text, $str);
		$str = str_replace("___Url___", "http://www.numizmatik.ru/tboard/read.php?tboard=".$rows["tboard"], $str);
		
		$Message["tboard"] .= $str;
	}
	$tboarddate = time();
}

$sql = "select * from blacklist where date > '$blacklistdate' and `check`=1 and parent='0' order by date desc limit 10;";
$result = mysql_query($sql);
if (mysql_num_rows($result) > 3)
{
	while ($rows = mysql_fetch_array($result))
	{
		unset ($str);
		unset ($text);
		$str = str_replace("___Date___", date("Y-m-d", $rows["date"]), $template["blacklist"]);
		$str = str_replace("___Title___", $rows["title"], $str);
		
		if (strlen($rows["title"])>30)
		{
			$titlebl = substr($rows["title"], 0, 30);
			
		} else {
			
			$titlebl = $rows["title"];
		}
		
		$text = substr(strip_tags($rows["description"]), 0, 400);
		while(substr_count($text,"<<<"))
			$text=substr($text,0,strpos($text,"<<<")).substr(strstr($text,">>>"),3);
		$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
		$str = str_replace("___ShortContent___", $text, $str);
		$str = str_replace("___Url___", "http://www.numizmatik.ru/blacklist/".strtolower_ru($titlebl)."_n".$rows['blacklist']."_p1.html", $str);
		
		$Message["blacklist"] .= $str;
	}
	$blacklistdate = time();
}

$sql = "select buycoins.*, user.userlogin, user.star 
from buycoins, user 
where buycoins.user1=user.user and date1 > '$buycoinsdate' order by date1 desc limit 10;";
$result = mysql_query($sql);
if (mysql_num_rows($result) > 3)
{
	while ($rows = mysql_fetch_array($result))
	{
		unset ($str);
		unset ($text);
		$str = str_replace("___Date___", date("Y-m-d", $rows["date1"]), $template["buycoins"]);
		$str = str_replace("___number___", $rows["buycoins"], $str);
		$str = str_replace("___Login___", $rows["userlogin"], $str);
		$str = str_replace("___star___", $rows["star"], $str);
		$str = str_replace("___Name___", $rows["name1"], $str);
		$str = str_replace("___Year___", $rows["year1"], $str);
		$str = str_replace("___Country___", $rows["country1"], $str);
		$str = str_replace("___Price___", $rows["price1"], $str);
		$str = str_replace("___Metal___", $rows["metal1"], $str);
		
		$str = str_replace("___Details___", strip_tags($rows["details1"]), $str);
		$str = str_replace("___Url___", "http://www.numizmatik.ru/buycoins/index.php", $str);
		
		$Message["buycoins"] .= $str;
	}
	$buycoinsdate = time();
}

$sql = "select * from biblio where date > '$bibliodate' and `check`=1 order by date desc limit 10;";
$result = mysql_query($sql);
if (mysql_num_rows($result) > 3)
{
	while ($rows = mysql_fetch_array($result))
	{
		unset ($str);
		unset ($text);
		$str = str_replace("___Date___", date("Y-m-d", $rows["date"]), $template["news"]);
		$str = str_replace("___Title___", $rows["name"], $str);
		
		$text = substr(strip_tags($rows["text"]), 0, 400);
		while(substr_count($text,"<<<"))
			$text=substr($text,0,strpos($text,"<<<")).substr(strstr($text,">>>"),3);
		$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
		$str = str_replace("___ShortContent___", $text, $str);
		$namehref = strtolower_ru($rows["name"])."_n".$rows["biblio"].".html";
		$str = str_replace("___Url___", "http://www.numizmatik.ru/biblio/".$namehref, $str);
		
		$Message["biblio"] .= $str;
	}
	$bibliodate = time();
}

$sql = "select * from advertise where date > '$advertisedate' and `check`=1 order by date desc limit 10;";
$result = mysql_query($sql);
if (mysql_num_rows($result) > 3)
{
	while ($rows = mysql_fetch_array($result))
	{
		unset ($str);
		unset ($text);
		$str = str_replace("___Date___", date("Y-m-d", $rows["date"]), $template["advertise"]);
		$str = str_replace("___Fio___", $rows["fio"], $str);
		
		$text = substr(strip_tags($rows["message"]), 0, 400);
		while(substr_count($text,"<<<"))
			$text=substr($text,0,strpos($text,"<<<")).substr(strstr($text,">>>"),3);
		$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
		$str = str_replace("___ShortContent___", $text, $str);
		$str = str_replace("___Url___", "http://www.numizmatik.ru/advertise/index.php", $str);
		
		$Message["advertise"] .= $str;
	}
	$advertisedate = time();
}


//теперь отправляем


if ($Message["news"]
	or $Message["tboard"]
	or $Message["blacklist"]
	or $Message["buycoins"]
	or $Message["biblio"]
	or $Message["advertise"])
{
	
	function SaveMailDb ($recipient, $subject, $SendMessage, $headers)
	{
		$sql_insert = "insert into shopcoinssender
		(shopcoinssender, dateinsert, email,
		subject, message, headers, 
		priority, datesend)
		values 
		(0, '".(time()+600)."', '".$recipient."',
		'$subject', '$SendMessage', '$headers', 
		'5', '0');";
		$result_insert = mysql_query($sql_insert);
	}
	
	$subject = "Подписка Клуба Нумизмат";
	$headers = "From: Numizmatik.Ru<administrator@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
	
	$file = fopen($in."mail/top.html", "r");
	while (!feof ($file)) 
		$message_top .= fgets ($file, 1024);
	fclose($file);


	$file = fopen($in."mail/bottom.html", "r");
	while (!feof ($file)) 
		$message_bottom .= fgets ($file, 1024);
	fclose($file);
	
	function MakeMessage ($mailkey, $mailmessage)
	{
		global $message_top, $message_bottom, $SERVER_NAME;
		$unsubscribetext = "<a href=http://".$SERVER_NAME."/subscribe/index.php?mailkey=".$mailkey.">Изменить параметры подписки</a>
		<br>http://".$SERVER_NAME."/subscribe/index.php?mailkey=".$mailkey;
		$mailmessage = $message_top."<tr bgcolor=\"#006699\"><td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td>
		<td width=498><p><b><font color=white>Подписка Клуба Нумизмат</font></b></td>
		<td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td></tr>
		<tr>
		<td width=498 bgcolor=\"#fff8e8\"><p>
		$unsubscribetext
		<br>
		$mailmessage
		<p>$unsubscribetext
		<br><br>Клуб Нумизмат - <a href=http://www.numizmatik.ru>www.numizmatik.ru</a>
		<br>Е-mail: <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a><br></p>
		</td>
		</tr>".$message_bottom;
		
		//echo $mailmessage;
		
		return $mailmessage;
	}
	
	$sql = "select distinct user.email, subscribe.* 
	from subscribe, user where subscribe.user=user.user 
	and (
	subscribe.news = '1'
	or tboard = '1'
	or blacklist = '1'
	or buycoins = '1'
	or biblio = '1'
	or advertise = '1'
	)
	;";
	$result = mysql_query($sql);
	while ($rows = mysql_fetch_array($result))
	{
		unset ($mailmessage);
		if ($rows["typemail"]==1 and $rows["email"])
		{
			//одним письмом
			
			if ($rows["news"] and $Message["news"])
				$mailmessage .= $templatetopic["news"].$Message["news"];
			
			if ($rows["tboard"] and $Message["tboard"])
				$mailmessage .= $templatetopic["tboard"].$Message["tboard"];
			
			if ($rows["blacklist"] and $Message["blacklist"])
				$mailmessage .= $templatetopic["blacklist"].$Message["blacklist"];
			
			if ($rows["buycoins"] and $Message["buycoins"])
				$mailmessage .= $templatetopic["buycoins"].$Message["buycoins"];
			
			if ($rows["biblio"] and $Message["biblio"])
				$mailmessage .= $templatetopic["biblio"].$Message["biblio"];
			
			if ($rows["advertise"] and $Message["advertise"])
				$mailmessage .= $templatetopic["advertise"].$Message["advertise"];
			
			
			
			if ($mailmessage)
			{
				echo "<br>".$rows["email"];
				$mailmessage = MakeMessage ($rows["mailkey"], $mailmessage);
				//mail($rows["email"], $subject, $mailmessage, $headers);
				SaveMailDb ($rows["email"], $subject, $mailmessage, $headers);
			}
		}
		elseif ($rows["email"])
		{
			//несколькими письмами
			if ($rows["news"] and $Message["news"])
			{
				$mailmessage = $templatetopic["news"].$Message["news"];
				$subject = "Клуб Нумизмат | Новости";
				$mailmessage = MakeMessage ($rows["mailkey"], $mailmessage);
				//mail($rows["email"], $subject, $mailmessage, $headers);
				SaveMailDb ($rows["email"], $subject, $mailmessage, $headers);
				echo "<br>".$rows["email"];
			}
			
			if ($rows["tboard"] and $Message["tboard"])
			{
				$mailmessage = $templatetopic["tboard"].$Message["tboard"];
				$subject = "Клуб Нумизмат | Конференция";
				$mailmessage = MakeMessage ($rows["mailkey"], $mailmessage);
				//mail($rows["email"], $subject, $mailmessage, $headers);
				SaveMailDb ($rows["email"], $subject, $mailmessage, $headers);
				echo "<br>".$rows["email"];
			}
			
			if ($rows["blacklist"] and $Message["blacklist"])
			{
				$mailmessage = $templatetopic["blacklist"].$Message["blacklist"];
				$subject = "Клуб Нумизмат | Черный список";
				$mailmessage = MakeMessage ($rows["mailkey"], $mailmessage);
				SaveMailDb ($rows["email"], $subject, $mailmessage, $headers);
				//mail($rows["email"], $subject, $mailmessage, $headers);
				echo "<br>".$rows["email"];
			}
			
			if ($rows["biblio"] and $Message["biblio"])
			{
				$mailmessage = $templatetopic["biblio"].$Message["biblio"];
				$subject = "Клуб Нумизмат | Библиотека";
				$mailmessage = MakeMessage ($rows["mailkey"], $mailmessage);
				//mail($rows["email"], $subject, $mailmessage, $headers);
				SaveMailDb ($rows["email"], $subject, $mailmessage, $headers);
				echo "<br>".$rows["email"];
			}
			
			if ($rows["buycoins"] and $Message["buycoins"])
			{
				$mailmessage = $templatetopic["buycoins"].$Message["buycoins"];
				$subject = "Клуб Нумизмат | Нужны монеты";
				$mailmessage = MakeMessage ($rows["mailkey"], $mailmessage);
				SaveMailDb ($rows["email"], $subject, $mailmessage, $headers);
				//mail($rows["email"], $subject, $mailmessage, $headers);
				echo "<br>".$rows["email"];
			}
			
			if ($rows["advertise"] and $Message["advertise"])
			{
				$mailmessage = $templatetopic["advertise"].$Message["advertise"];
				$subject = "Клуб Нумизмат | Объявления";
				$mailmessage = MakeMessage ($rows["mailkey"], $mailmessage);
				SaveMailDb ($rows["email"], $subject, $mailmessage, $headers);
				//mail($rows["email"], $subject, $mailmessage, $headers);
				echo "<br>".$rows["email"];
			}
		}
	}
	
	$sql = "insert subscribesystem  
	(subscribesystem, date, news, 
	tboard, blacklist, buycoins,
	biblio, advertise)
	values
	(0, '".$timenow."', '$newsdate', 
	'$tboarddate', '$blacklistdate', '$buycoinsdate', 
	'$bibliodate', '$advertisedate');
	";
	$result = mysql_query($sql);
}
?>