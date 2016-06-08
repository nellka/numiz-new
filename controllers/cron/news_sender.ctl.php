<?
require_once $cfg['path'] . '/models/crons.php';
require_once($cfg['path'] . '/models/mails.php');

$cron_class = new crons($cfg['db']);
$mail_class = new mails();	
			  

//include $DOCUMENT_ROOT."/config.php";
//include $DOCUMENT_ROOT."/funct.php";

$Message = Array();

$templatetopic["news"] = "
<table border=0 cellpadding=0 cellspacing=0 width=650>
<tr bgcolor=#99CCFF valign=top><td style='padding:5px'><b>Новости нумизматики</b></td></tr>
</table>";

$templatetopic["tboard"] = "
<br><table border=0 cellpadding=0 cellspacing=0 width=650>
<tr bgcolor=#99CCFF valign=top><td style='padding:5px'><b>Конференция</b></td></tr>
</table>";

$templatetopic["blacklist"] = "
<table border=0 cellpadding=0 cellspacing=0 width=650>
<tr bgcolor=#99CCFF valign=top><td style='padding:5px'><b>Черный список</b></td></tr>
</table>";

$templatetopic["buycoins"] = "
<table border=0 cellpadding=0 cellspacing=0 width=650>
<tr bgcolor=#99CCFF valign=top><td style='padding:5px'><b>Нужны монеты</b></td></tr>
</table>";

$templatetopic["biblio"] = "
<table border=0 cellpadding=0 cellspacing=0 width=650>
<tr bgcolor=#99CCFF valign=top><td style='padding:5px'><b>Библиотека</b></td></tr>
</table>";

$templatetopic["advertise"] = "
<table cellpadding=3 cellspacing=1 border=0 width=100% align=center>
<tr bgcolor=#99CCFF valign=top><td style='padding:5px'><b>Доска объявлений</b></td></tr>
</table>";

$template["news"] = "
<br><table cellpadding=3 cellspacing=1 border=0 width=100% align=center style='border:1px solid #cccccc;'>
<tr bgcolor=#eeeeee><td width='15%'><b>___Date___</b></td><td><b>___Title___</b></td></tr>
<tr><td colspan=2 style='border-top:1px solid #cccccc;'><div style='float:left'><img src='___Image___' style='max-width: 120px;padding: 10px;'/></div> ___ShortContent___<br><br>
<a href=___Url___ target=_blank>___Url___</a></td></tr>
</table>";

$template["tboard"] = "
<table cellpadding=3 cellspacing=1 border=0 width=100% align=center style='border:1px solid #cccccc;'>
<tr bgcolor=#eeeeee><td width='15%'><b>___Date___</b></td><td><b>___Title___</b></td></tr>
<tr><td colspan=2 style='border-top:1px solid #cccccc;'>___ShortContent___<br><br>
<a href=___Url___ target=_blank>___Url___</a></td></tr>
</table>";

$template["blacklist"] = "
<br><table cellpadding=3 cellspacing=1 border=0 width=100% align=center style='border:1px solid #cccccc;'>
<tr bgcolor=#eeeeee><td width='15%'><b>___Date___</b></td><td><b>___Title___</b></td></tr>
<tr><td colspan=2 style='border:1px solid #cccccc;'>___ShortContent___<br><br>
<a href=___Url___ target=_blank>___Url___</a></td></tr>
</table>";

$template["buycoins"] = "
<table cellpadding=3 cellspacing=1 border=0 width=100% align=center style='border:1px solid #cccccc;'>
<tr bgcolor=#eeeeee><td colspan=2><b>Заявка № ___number___ от ___Date___</b></td></tr>
<tr><td width=\"20%\">Пользователь:</td><td>___Login___ (___star___)</td></tr>
<tr><td>Название:</td><td>___Name___</td></tr>
<tr><td>Год:</td><td>___Year___</td></tr>
<tr><td>Страна:</td><td>___Country___</td></tr>
<tr><td>Цена:</td><td>___Price___</td></tr>
<tr><td>Металл:</td><td>___Metal___</td></tr>
<tr><td>Дополнительно:</td><td>___Details___</td></tr>
<tr><td colspan=2><a href=___Url___ target=_blank>___Url___</a></td></tr>
</table>";

$template["biblio"] = "
<table cellpadding=3 cellspacing=1 border=0 width=100% align=center style='border:1px solid #cccccc;'>
<tr bgcolor=#eeeeee><td width='15%'><b>___Date___</b></td><td><b>___Title___</b></td></tr>
<tr><td colspan=2>___ShortContent___</td></tr>
<tr><td colspan=2><a href=___Url___ target=_blank>___Url___</a></td></tr>
</table>";

$template["advertise"] = "
<table cellpadding=3 cellspacing=1 border=0 width=100% align=center style='border:1px solid #cccccc;'>
<tr bgcolor=#eeeeee><td width='15%'><b>___Date___</b></td><td><b>___Fio___</b></td></tr>
<tr><td colspan=2>___ShortContent___</td></tr>
<tr><td colspan=2><a href=___Url___ target=_blank>___Url___</a></td></tr>
</table>";

//выбираем даты последних рассылок
$rows_info = $cron_class->getLastSubscribesystem();

$newsdate = $rows_info["news"];
$tboarddate = $rows_info["tboard"];
$blacklistdate = $rows_info["blacklist"];
$buycoinsdate = $rows_info["buycoins"];
$bibliodate = $rows_info["biblio"];
$advertisedate = $rows_info["advertise"];


$sql = "select * from news where date > '$newsdate' and `check`=1 order by date desc limit 10;";

$result = $cron_class->getDataSql($sql);

if (count($result) > 3){
	foreach ($result as $rows){
		unset ($str);
		unset ($text);
		
		preg_match('#(<img\s(?>(?!src=)[^>])*?src=")(.*?)("[^>]*>)#',$rows['text'],$res);
	    $img = $res[2];
	    
		$str = str_replace("___Date___", date("Y-m-d", $rows["date"]), $template["news"]);
		$str = str_replace("___Title___", $rows["name"], $str);
		$str = str_replace("___Image___", $img, $str);
		
		$text = mb_substr(strip_tags($rows["text"]), 0, 400,'utf-8');
		while(substr_count($text,"<<<"))
			$text=substr($text,0,strpos($text,"<<<")).substr(strstr($text,">>>"),3);
		
		$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
		
		$str = str_replace("___ShortContent___", $text, $str);
		$namehref = contentHelper::strtolower_ru($rows["name"])."_n".$rows["news"].".html";
		
		$str = str_replace("___Url___", "http://news.numizmatik.ru/".$namehref, $str);
		
		$Message["news"] .= $str;
		//echo $str;
	}

	$newsdate = time();
}

$sql = "select * from tboard where date > '$tboarddate' and `check`=1 and parent='0' order by date desc limit 10;";

$result = $cron_class->getDataSql($sql);

if (count($result) > 3){
	foreach ($result as $rows){
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
$result = $cron_class->getDataSql($sql);

if (count($result) > 3){
	foreach ($result as $rows){
		unset ($str);
		unset ($text);
		$str = str_replace("___Date___", date("Y-m-d", $rows["date"]), $template["blacklist"]);
		$str = str_replace("___Title___", $rows["title"], $str);
		
		if (strlen($rows["title"])>30) {			
		    $titlebl = mb_substr($rows["title"], 0, 30,'utf-8');			
		} else {			
			$titlebl = $rows["title"];
		}
		
		$text = mb_substr(strip_tags($rows["description"]), 0, 400,'utf-8');
		while(substr_count($text,"<<<"))
			$text=substr($text,0,strpos($text,"<<<")).substr(strstr($text,">>>"),3);
		$text = mb_substr($text, 0, strlen($text) - strpos(strrev($text), '.'),'utf-8');
		$str = str_replace("___ShortContent___", $text, $str);
		$str = str_replace("___Url___", "http://www.numizmatik.ru/blacklist/".contentHelper::strtolower_ru($titlebl)."_n".$rows['blacklist']."_p1.html", $str);
		
		$Message["blacklist"] .= $str;
	}
	$blacklistdate = time();
}

$sql = "select buycoins.*, user.userlogin, user.star from buycoins, user where buycoins.user1=user.user and date1 > '$buycoinsdate' order by date1 desc limit 10;";
$result = $cron_class->getDataSql($sql);

if (count($result) > 3){
	foreach ($result as $rows){
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
$result = $cron_class->getDataSql($sql);

if (count($result) > 3){
	foreach ($result as $rows){
		unset ($str);
		unset ($text);
		$str = str_replace("___Date___", date("Y-m-d", $rows["date"]), $template["news"]);
		$str = str_replace("___Title___", $rows["name"], $str);
		
		$text = substr(strip_tags($rows["text"]), 0, 400);
		while(substr_count($text,"<<<"))
			$text=substr($text,0,strpos($text,"<<<")).substr(strstr($text,">>>"),3);
		$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
		$str = str_replace("___ShortContent___", $text, $str);
		$namehref = contentHelper::strtolower_ru($rows["name"])."_n".$rows["biblio"].".html";
		$str = str_replace("___Url___", "http://www.numizmatik.ru/biblio/".$namehref, $str);
		
		$Message["biblio"] .= $str;
	}
	$bibliodate = time();
}

$sql = "select * from advertise where date > '$advertisedate' and `check`=1 order by date desc limit 10;";
$result = $cron_class->getDataSql($sql);

if (count($result) > 3){
	foreach ($result as $rows){
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
	or $Message["advertise"]) {	
		
	$subject = "Подписка Клуба Нумизмат";
	$headers = "From: Numizmatik.Ru<administrator@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
	
	$sql = "select distinct user.email, subscribe.* 
	from subscribe, user where subscribe.user=user.user and (subscribe.news = '1'	or tboard = '1' 	or blacklist = '1'	or buycoins = '1'	or biblio = '1' 	or advertise = '1'	);";
	
	$result = $cron_class->getDataSql($sql);
	
	foreach ($result as $rows){
		$mailmessage = "";

		if ($rows["typemail"]==1 and $rows["email"]) {
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
			
			
			if ($mailmessage) {
				echo "<br>".$rows["email"];
				$mailmessage = MakeMessage ($rows["mailkey"], $mailmessage);
				$cron_class->saveMailDb($rows["email"], $subject, $mailmessage, $headers);
			}
		} elseif ($rows["email"]) {
			//несколькими письмами
			if ($rows["news"] and $Message["news"])	{
				$mailmessage = $templatetopic["news"].$Message["news"];
				$subject = "Клуб Нумизмат | Новости";
				$mailmessage = MakeMessage ($rows["mailkey"], $mailmessage);
				$cron_class->saveMailDb($rows["email"], $subject, $mailmessage, $headers);
				echo "<br>".$rows["email"];
			}
			
			if ($rows["tboard"] and $Message["tboard"])	{
				$mailmessage = $templatetopic["tboard"].$Message["tboard"];
				$subject = "Клуб Нумизмат | Конференция";
				$mailmessage = MakeMessage ($rows["mailkey"], $mailmessage);
				$cron_class->saveMailDb($rows["email"], $subject, $mailmessage, $headers);
				echo "<br>".$rows["email"];
			}
			
			if ($rows["blacklist"] and $Message["blacklist"]) {
				$mailmessage = $templatetopic["blacklist"].$Message["blacklist"];
				$subject = "Клуб Нумизмат | Черный список";
				$mailmessage = MakeMessage ($rows["mailkey"], $mailmessage);
				$cron_class->saveMailDb($rows["email"], $subject, $mailmessage, $headers);
				echo "<br>".$rows["email"];
			}
			
			if ($rows["biblio"] and $Message["biblio"]) {
				$mailmessage = $templatetopic["biblio"].$Message["biblio"];
				$subject = "Клуб Нумизмат | Библиотека";
				$mailmessage = MakeMessage ($rows["mailkey"], $mailmessage);
				$cron_class->saveMailDb($rows["email"], $subject, $mailmessage, $headers);
				echo "<br>".$rows["email"];
			}
			
			if ($rows["buycoins"] and $Message["buycoins"]) {
				$mailmessage = $templatetopic["buycoins"].$Message["buycoins"];
				$subject = "Клуб Нумизмат | Нужны монеты";
				$mailmessage = MakeMessage ($rows["mailkey"], $mailmessage);
				$cron_class->saveMailDb($rows["email"], $subject, $mailmessage, $headers);
				echo "<br>".$rows["email"];
			}
			
			if ($rows["advertise"] and $Message["advertise"]) {
				$mailmessage = $templatetopic["advertise"].$Message["advertise"];
				$subject = "Клуб Нумизмат | Объявления";
				$mailmessage = MakeMessage ($rows["mailkey"], $mailmessage);
				$cron_class->saveMailDb($rows["email"], $subject, $mailmessage, $headers);
				echo "<br>".$rows["email"];
			}
		}
	}
	
	$cron_class->addSubscribesystem($newsdate,$tboarddate,$blacklistdate,$buycoinsdate,$bibliodate,$advertisedate);
}

die('done');
function MakeMessage($mailkey, $mailmessage){	
    
    $mailtext ="<table border='0' cellpadding='0' cellspacing='0' width='650' style='border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;'>"; 
	$mailtext .="<tr><td style='border:1px solid #cccccc; background-color:#eeeeee;padding:10px;font-size:14px;font-weight:bold;'>Подписка Клуба Нумизмат</td></tr>";
	$mailtext .= "<tr><td style='padding: 10px;'><a href=http://www.numizmatik.ru/subscribe/index.php?mailkey=".$mailkey.">Изменить параметры подписки</a><br>http://www.numizmatik.ru/subscribe/index.php?mailkey=".$mailkey."</td></tr>"; 
	$mailtext .= "<tr><td style='padding: 10px;'>";
	$mailtext .= $mailmessage;
	$mailtext .= "</td></tr>";
	$mailtext .= "<tr><td style='padding: 10px;'><a href=http://www.numizmatik.ru/subscribe/index.php?mailkey=".$mailkey.">Изменить параметры подписки</a><br>http://www.numizmatik.ru/subscribe/index.php?mailkey=".$mailkey."</td></tr>"; 
	$mailtext .= "</td></tr>";
    $mailtext .= "<tr><td style='padding: 10px;'>";
   // $mailtext .= "<p>Клуб Нумизмат - <a href=http://www.numizmatik.ru>www.numizmatik.ru</a></p>";
    $mailtext .= "<p>Е-mail: <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a></p>";		
	$mailtext .= "</td></tr></table>";	

	return $mailtext;
}
?>