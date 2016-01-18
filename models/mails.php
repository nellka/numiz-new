<?
require('Zend/Mail.php');
//Класс для отправки писем
class mails 
{	
    protected $mail;
    
    function __construct(){
		$this->mail = new Zend_Mail('UTF-8');
		$this->mail->setFrom('administrator@numizmatik.ru', 'Numizmatik.Ru');
	    
	 	
	}     
	//формирование письма о регистрации пользователя
	public function newUserLetter($dataUser){
		$this->mail->addTo($dataUser['email'], $dataUser['userlogin']);	
	   // $mail->setBodyText('My Nice Test Text');	    
	    $this->mail->setSubject("Регистрация в Клубе Нумизмат");	    
	    $mytext = "<br><br><p><b>Ваши данные</b></p>";
	    $mytext .= "<p>E-mail: ".$dataUser['email']."</p>";
		$mytext .= "<p>Пароль: ".$dataUser['userpassword']."</p>";	
		$mytext .= "<p>Подписка на новости: ".($dataUser['subsription']?"Да":"Нет")."</p>";	
		if(isset($dataUser['subsription_shop'])){
		    $mytext .= "<p>Подписка на новости магазина: ".($dataUser['subsription_shop']?"Да":"Нет")."</p>";	
		}
		
		$mytext .= "<br /><p class=txt><b>Уважаемый коллега.</b></p>
<p>Благодарим за регистрацию на нашем портале. Надеемся на наше долгое и взаимовыгодное сотрудничество.</p>
<p>С уважением, администрация Клуба Нумизмат.</p>";					
		$html = $this->createFullHtml($mytext);
		$this->mail->setBodyHtml($html,"Регистрация в Клубе Нумизмат");			   
		$this->mail->send();
	}
	public function orderLetter($email,$data){
	    //письмо о заказе
	}
	
	//формирование письма "забыли пароль"
	public function forgetPwdLetter($dataUser){
		$this->mail->addTo($dataUser['email'], $dataUser['userlogin']);	
	   
	    $this->mail->setSubject("Восстановление пароля в Клубе Нумизмат");	   
	 	$mytext = "<br><br><p><b>Ваши данные</b></p>";
	    $mytext .= "<p>E-mail: ".$dataUser['email']."</p>";
		$mytext .= "<p>Пароль: ".$dataUser['userpassword'];		
		$html = $this->createFullHtml($mytext,"Восстановление пароля в Клубе Нумизмат");
		$this->mail->setBodyHtml($html);	    
        $this->mail->send();
	}
	
	protected function createFullHtml($mytext,$subject=''){
		$message = "<html><body><style type='text/css'><!-- body,p,td	{COLOR: #000000;font-family: arial, helvetica, sans-serif;font-size: 12px;margin-left: 4px;margin-right: 4px;margin-top: 2px;margin-bottom: 2px;}";
			$message .="a:link{font-family:arial;font-weight:bold;color:#006699; TEXT-DECORATION:none; font-size:12px;}"; 
			$message .="a:visited{font-family:arial;font-weight:bold;color:#006699; TEXT-DECORATION:none; font-size:12px;}"; 
$message .="a:active{font-family:arial;font-weight:bold;color:#006699;  TEXT-DECORATION:none; font-size:12px;}";
$message .="a:hover{font-family:arial;font-weight:bold;color:#006699; TEXT-DECORATION:none; font-size:12px;}--></style>";
$message .="<center><table border=0 cellpadding=0 cellspacing=0 width=550><tr bgcolor=#ffcc66 valign=top><td height=30><a href=http://www.numizmatik.ru><font face=Arial size='+2' color='#006699'><b>cl</b></font></a></td>";
$message .="<td rowspan=2 height=30><a href=http://www.numizmatik.ru><font face=Arial size='+4' color='#006699'><b>U</b></font></a></td><td height=30><a href=http://www.numizmatik.ru><font face=Arial size='+2' color='#006699'><b>b</b></font></a></td>";
$message .="<td width='100%'>&nbsp;</td></tr><tr bgcolor=#006699 valign='top'><td height='20' align='center'><a href=http://www.numizmatik.ru>";
$message .="<font face=Arial size='+1' color='#ffcc66'><b>n</b></font></a></td><td height=20><a href=http://www.numizmatik.ru><font face=Arial size='+1' color='#ffcc66'><b>mizmat</b></font></a></td>";
$message .="<td width='100%'>&nbsp;</td></tr></table><br>";
$message .="<table border=0 cellpadding=0 cellspacing=2 width=550><tr><td height=1 width=500 bgcolor='#000000' colspan=3></td></tr>"; 
$message .= "<tr bgcolor=\"#006699\"><td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td>
			<td width=498><p><b><font color=white>$subject</font></b></td>
			<td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td></tr>
			<tr>
			<td width=498 bgcolor=\"#fff8e8\">$mytext<br><br>Клуб Нумизмат - <a href=http://www.numizmatik.ru>www.numizmatik.ru</a><br><br>
			</td>
			</tr>
			<tr><td height='1' width='500' bgcolor='#000000' colspan=3></td></tr></table></center></body></html>";	

		return $message;
	}
}



//функция под вопросом - видимо фиксировало кому посылали письма
/*
//$headers = "From: >\nContent-type: text/html; charset=\"windows-1251\"";
function InsertMail ($recipient, $subject, $SendMessage, $headers, $city)
{
	if (check_email($recipient)==1)
	{
		$sql_insert_mail = "insert into shopcoinssender
		(shopcoinssender, dateinsert, email,
		subject, message, headers, 
		priority, datesend)
		values 
		(0, '".(time())."', '".$recipient."',
		'$subject', '$SendMessage', '$headers', 
		'".($city==1||$city=="Москва"?4:5)."', '0');";
		//echo $sql_insert_mail;
		$result_insert_mail = mysql_query($sql_insert_mail);
		echo mysql_error();
	}
}

*/