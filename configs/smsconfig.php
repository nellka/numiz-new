<?
$loginsms = "numizmatikru";
$passwordsms = "05numi201204";
$sender_idsms = "Numizmatik";

$paymentsms[1] = "���������� ������. � ��������� ����� �� ����� ���������. �������";
$paymentsms[2] = "������ ��������";
$paymentsms[3] = "������ WebMoney. �� ������������ � �������";
$paymentsms[4] = "������ YandexMoney. �� ������������ � �������";
//$SumName[5] = "�������� - 2,5% (���. 30 ���.)";
//$SumName[5] = "������������ ���� - 1% (������� ������ �� �����������. ���� ������)";
//$SumName[7] = "��������� ������� Contact";
//$SumName[6] = "Rapida - 1,5% (���. 7 ���.) - ������";
$paymentsms[6] = "������ ����� ���� (� ��� ����� ��������). �� ������������ � �������";
//if ($cookiesuser==811)
$paymentsms[8] = "������ ��������� ����� VISA,MasterCard. �� ������������ � �������";

$arraytextsms = array();

$arraytextsms2[1] = " ������� �. ___1___. � ���� �������� �� �������� � ���� ����. �������";																				//ring
$arraytextsms2[3] = " �������� �. ___1___. � ���� �������� �� �������� � ���� ����. �������";																				//to office
$arraytextsms2[4] = " �������� ������ ___1___";																			//to post
$arraytextsms2[6] = " �������� ���";																					//EMS
$arraytextsms2[7] = " ___1___ ������, �.�������� ��. �������� 12 �������� 8";						//in office 

$arraytextsms[1] = "����� ___1___ �� ����� ___2___ ��� ������ � ���������.___3___ . Numizmatik.Ru";										//new
$arraytextsms[2] = "����� ___1___ �� ����� ___2___ ��� ������. ���� ��������� ___3___. ���� ��� � ����� ������. Club ��������";		//salon
$arraytextsms[3] = "����� ___1___ �� ����� ___2___ ���. ����� ������� ___3___. ������ ___4___. Numizmatik.Ru";						//cross
$arraytextsms[4] = "����� ___1___ �� ����� ___2___ ���. � ������ ___3___ ���. Club ��������";											//payment
$arraytextsms[5] = "������ ������ ___1___ ��������. ����� ������� �� ������. ������ ����� ���������. Numizmatik.Ru";											//ispayment
$arraytextsms[6] = "���� ��������� ������ ___1___ ������� � �� ����� �������������. ��������� � ����, ���� ����� ��� ��� �����. Numizmatik.Ru";					//remember
$arraytextsms[7] = "����� ___1___ ��������� ������ ������. ����� ����������� ___2___. ��������� ����� �� ����� russianpost.ru. �������. � ��������� Numizmatik.Ru";												//send
$arraytextsms[8] = "����� ___1___ ��� ��������� ��� ___2___. ������� �������� � ��� ���������. Club ��������";						//received

function strtolower_rusms($text) 
{ 
	$text = trim($text);
	$text = strip_tags($text);
	//$alfavitlover = array('�','�','�','�','�','�','�','�', '�','�','�','�','�','�','�','�', '�','�','�','�','�','�','�','�', '�','�','�','�','�','�','�','�','�','�'); 
	//$alfavitupper = array('�','�','�','�','�','�','�','�', '�','�','�','�','�','�','�','�', '�','�','�','�','�','�','�','�', '�','�','�','�','�','�','�','�','�','�'); 
	$ruskey = Array('�','�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�','�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�','\'');
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
			// "<time>20101118214600</time>".		// ����� ������� ����� �������� ����� ���������
			"<phones>".
			"<phone>" . $ttt . "</phone>".
			// "<phone>79091234567</phone>".		// ����� ������� �������������� ������ ���������
			"</phones>".
//			"<test>1</test>".					// ���� ����������������� ��� ������, �� ��� �� ����� ���������� ���������� � �� ����� ������������������ 
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