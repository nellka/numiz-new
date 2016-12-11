<?
$loginsms = "numizmatikru";
$passwordsms = "05numi201204";
$sender_idsms = "Numizmatik";

$paymentsms[1] = "Наложенный платеж. В ближайшее время он будет отправлен. Спасибо";
$paymentsms[2] = "Оплата Наличные";
$paymentsms[3] = "Оплата WebMoney. Не задерживайте с оплатой";
$paymentsms[4] = "Оплата YandexMoney. Не задерживайте с оплатой";
//$SumName[5] = "Сбербанк - 2,5% (мин. 30 руб.)";
//$SumName[5] = "Коммерческий Банк - 1% (ПЛАТЕЖИ БОЛЬШЕ НЕ ПРИНИМАЮТСЯ. СЧЕТ ЗАКРЫТ)";
//$SumName[7] = "Платежная система Contact";
//$SumName[6] = "Rapida - 1,5% (мин. 7 руб.) - удобно";
$paymentsms[6] = "Оплата Любой банк (в том числе Сбербанк). Не задерживайте с оплатой";
//if ($cookiesuser==811)
$paymentsms[8] = "Оплата Кредитные карты VISA,MasterCard. Не задерживайте с оплатой";

$arraytextsms = array();

$arraytextsms2[1] = " Встреча м. ___1___. С Вами свяжутся по телефону в этот день. Спасибо";																				//ring
$arraytextsms2[3] = " Доставка м. ___1___. С Вами свяжутся по телефону в этот день. Спасибо";																				//to office
$arraytextsms2[4] = " Отправка почтой ___1___";																			//to post
$arraytextsms2[6] = " Отправка ЕМС";																					//EMS
$arraytextsms2[7] = " ___1___ Москва, м.Тверская ул. Тверская 12 строение 8";						//in office 

$arraytextsms[1] = "Заказ ___1___ на сумму ___2___ руб принят к обработке.___3___ . Numizmatik.Ru";										//new
$arraytextsms[2] = "Заказ ___1___ на сумму ___2___ руб собран. Дата получения ___3___. Ждем Вас в нашем салоне. Club Нумизмат";		//salon
$arraytextsms[3] = "Заказ ___1___ на сумму ___2___ руб. Место встречи ___3___. Курьер ___4___. Numizmatik.Ru";						//cross
$arraytextsms[4] = "Заказ ___1___ на сумму ___2___ руб. К оплате ___3___ руб. Club Нумизмат";											//payment
$arraytextsms[5] = "Оплата заказа ___1___ получена. Заказ передан на сборку. Вскоре будет отправлен. Numizmatik.Ru";											//ispayment
$arraytextsms[6] = "Дата получения заказа ___1___ истекла и он будет расформирован. Свяжитесь с нами, если заказ Вам еще нужен. Numizmatik.Ru";					//remember
$arraytextsms[7] = "Заказ ___1___ отправлен почтой России. Номер отправления ___2___. Отследить можно на сайте russianpost.ru. Спасибо. С уважением Numizmatik.Ru";												//send
$arraytextsms[8] = "Заказ ___1___ был отправлен Вам ___2___. Просьба сообщить о его получении. Club Нумизмат";						//received

function strtolower_rusms($text) 
{ 
	$text = trim($text);
	$text = strip_tags($text);
	//$alfavitlover = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю','ї'); 
	//$alfavitupper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю','Ї'); 
	$ruskey = Array('ї','а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я','А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я','\'');
	$engkey = Array ("i","a", "b", "v", "g", "d", "e", "yo", "g", "z", "i", "iy", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "h", "c", "ch", "sh", "sch", "`", "y", "`", "e", "yu", "ya","A", "B", "V", "G", "D", "Ye", "Yo", "G", "Z", "I", "Iy", "K", "L", "M", "N", "O", "P", "R", "S", "T", "U", "F", "H", "C", "Ch", "Sh", "Sch", "`", "Y", "`", "E", "Yu", "Ya","");
	//$text = str_replace($alfavitupper,$alfavitlover,strtolower($text)); 
   	return str_replace($ruskey,$engkey,$text);
}

function isphone($phone) {

	global $loginsms,$passwordsms;
	
	$phone_c = ""; 
	$tmp_phone = explode(",",$phone);
	foreach ($tmp_phone as $key3=>$value3) {
	
		$text3 = preg_replace("/([^0-9])/","",$value3);
		if (strlen($text3)==10) {
		
			$phone_c .= ($phone_c?", ":"")."+7".$text3;
		}
		elseif (strlen($text3)==11) {
		
			if (substr($text3,0,1) == "7")
				$phone_c .= ($phone_c?", ":"")."+".$text3;
			elseif (substr($text3,0,1) == "8")
				$phone_c .= ($phone_c?", ":"")."+7".substr($text3,1);
			else
				$phone_c .= ($phone_c?", ":"")."+".$text3;
		}
		else {
		
			$phone_c .= $text3;
		}
		break;
	}
	
	if (($phone_c[0]=="+" && $phone_c[1] == "7" && $phone_c[2] == 9 && strlen($phone_c)==12) || ($phone_c[0]=="8" && $phone_c[1] == "9" && strlen($phone_c) == 11) || ($phone_c[0]=="9" && strlen($phone_c) == 10) || ($phone_c[0] == "7" && $phone_c[1] == 9 && strlen($phone_c)==11)) {
		$phone2 = $phone_c;
	}
	else
		return false;
	
	$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>".
	"<def xmlns=\"http://Giper.mobi/schema/PhoneDEF\">".
		"<login>" . $loginsms . "</login>".
		"<pwd>" . $passwordsms . "</pwd>".
		"<phone>" . $phone2 . "</phone>".
	"</def>";
	//echo $xml;
	try {
		$url = "http://2kengu.ru/api/def";
	
		$result = post_content( $url, $xml );
		$html = $result['content'];
		//return $html;
		$patern="#<[\s]*status[\s]*>([^<]*)<[\s]*/status[\s]*>#i";
		preg_match($patern, $html, $matches);
		$status = $matches[1];
		//echo $status;
		if ($status == '0')
			return $phone2;
		else
			return false; 
		    	
	} catch(Exception $e) {
		return false;
	}
	
	//return $phone2;
} 

function sendsms2 ($type,$phone,$param1='',$param2='',$param3='',$param4='') {
	
	global $arraytextsms,$sender_idsms,$loginsms,$passwordsms,$transactionsms;
	
	$transactionId = "12345667a";
	
	if ($param1 == '' || !$phone)
		return false;
	
	$ttt = isphone($phone);
	
	if ($ttt === false)
		return false;
	
	if ($type==0)
		$sms_text = $param1;
	elseif ($arraytextsms[$type]) {
	
		$sms_text = str_replace("___1___",$param1,$arraytextsms[$type]);
		$sms_text = str_replace("___2___",$param2,$sms_text);
		$sms_text = str_replace("___3___",$param3,$sms_text);
		$sms_text = str_replace("___4___",$param4,$sms_text);
		
	}
	else
		return false;
		
	$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>".
		"<message>".
			"<login>" . $loginsms . "</login>".
			"<pwd>" . $passwordsms . "</pwd>".
			"<id>" . $transactionsms . "</id>".
			"<sender>" . $sender_idsms . "</sender>".
			"<text>" . strtolower_rusms($sms_text) . "</text>".
			// "<time>20101118214600</time>".		// Можно указать время отправки этого сообщения
			"<phones>".
			"<phone>" . $ttt . "</phone>".
			// "<phone>79091234567</phone>".		// Можно указать дополнительные номера телефонов
			"</phones>".
//			"<test>1</test>".					// Если раскомментировать эту строку, то СМС не будет отправлено фактически и не будет протарифицированно 
		"</message>";
	
	//echo htmlspecialchars($xml);
		
	try {
		$url = "http://2kengu.ru/api/message";
	
		$result = post_content( $url, $xml );
		$html = $result['content'];
		
		$patern="#<[\s]*status[\s]*>([^<]*)<[\s]*/status[\s]*>#i";
		preg_match($patern, $html, $matches);
		$status = $matches[1];
		//echo htmlspecialchars($html);
		if ($status == '0' || $status == '11')
			return array(0=>$status,1=>$ttt,2=>strtolower_rusms($sms_text));
		else
			return $status; 
				
	} catch(Exception $e) {
		return false;
	}	
	
}

function post_content ($url,$postdata) {
	$uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

	$ch = curl_init( $url );
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_ENCODING, "");
	curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt($ch, CURLOPT_COOKIEJAR, "c://coo.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE,"c://coo.txt");

	$content = curl_exec( $ch );
	$err     = curl_errno( $ch );
	$errmsg  = curl_error( $ch );
	$header  = curl_getinfo( $ch );
	curl_close( $ch );

	$header['errno']   = $err;
	$header['errmsg']  = $errmsg;
	$header['content'] = $content;
	return $header;
}	
?>