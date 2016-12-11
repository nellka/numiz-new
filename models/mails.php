<?
require('Zend/Mail.php');
//Класс для отправки писем
class mails 
{	
    protected $mail;
	
    function __construct($from = false){
		$this->mail = new Zend_Mail('UTF-8');
		//$this->mail->addHeader("Content-Type:","text/html; charset=UTF-8");
		if(!$from){
			$this->mail->setFrom('administrator@numizmatik.ru', 'Numizmatik.Ru');
		}	 	
	}
	//формирование письма о монете в каталоге
	public function CatalogCoinsLetter($data){
     	$this->mail->setFrom($data['mailfrom'], $data['fiofrom']);
		$this->mail->addTo($data['mailfriend'], $data['mailfriend']);  
		$this->mail->setSubject("Сообщение из Монетной Лавки Клуба Нумизмат");

		$mytext ="<table border='0' cellpadding='0' cellspacing='0' width='650' style='border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;'>";
		$mytext .="<tr><td style=\"border:1px solid #cccccc; background-color:#eeeeee;padding:10px;font-size:14px;font-weight:bold;\">Монетная лавка Клуба Нумизмат</td></tr>";
		$mytext .= "<tr><td style='padding: 10px;'><b><p>Добрый день!</b></p>";
		$mytext .="<p>Ваш товарищ (".$data['fiofrom']." ".$data['mailfrom'].") отправил Вам сообщение с Монетной лавки Клуба Нумизмат. </p>";
		$mytext .="<p>".$data['message']."</p>";
		$mytext .="<p>Для просмотра данного лота перейдите по ссылке: <b><a href='".$data['link']."'>".$data['link']."</a></b></p>";
		$mytext .="<p>Данное сообщение не гарантирует, что данный лот не продан другому покупателю.</p>";
		$mytext .= "</td></tr></table>";
		$html = $this->createFullHtml($mytext);

		$this->mail->setBodyHtml($html);
		$this->mail->send();
	}
	
	//формирование письма о новом видео от пользователя
	public function newVideoLetter($data){
		
		$this->mail->addTo("administrator@numizmatik.ru", "administrator@numizmatik.ru");	
 		
		$this->mail->setSubject("Видеораздел Клуба Нумизмат");	
	    
	    $mytext ="<table border='0' cellpadding='0' cellspacing='0' width='650' style='border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;'>"; 
	    $mytext .="<tr><td style=\"border:1px solid #cccccc; background-color:#eeeeee;padding:10px;font-size:14px;font-weight:bold;\">Видеораздел Клуба Нумизмат</td></tr>";
	 	$mytext .= "<tr><td style='padding: 10px;'>";
	 	$mytext .= "<p>Сообщение от ".$data['userlogin']." <br>Ссылка на видео : <b>".($data['videolink']?$data['videolink']:" нет ")."</b>
			<br><b>Пожелания: </b>".($data['text']?$data['text']:" нет ")."<br><br></p>";	
	    	
		$mytext .= "</td></tr></table>";	
		$html = $this->createFullHtml($mytext);

		$this->mail->setBodyHtml($html);			   
		$this->mail->send();
	}
	
	//формирование письма о регистрации пользователя
	public function newUserLetter($dataUser){
		$this->mail->addTo($dataUser['email'], $dataUser['userlogin']);	
	   // $mail->setBodyText('My Nice Test Text');	    
	    $this->mail->setSubject("Регистрация в Клубе Нумизмат");	
	    
	    $mytext ="<table border='0' cellpadding='0' cellspacing='0' width='650' style='border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;'>"; 
	    $mytext .="<tr><td style=\"border:1px solid #cccccc; background-color:#eeeeee;padding:10px;font-size:14px;font-weight:bold;\">Регистрация в Клубе Нумизмат</td></tr>";
	 	$mytext .= "<tr><td style='padding: 10px;'><b><p>Ваши данные</b></p>";
	    $mytext .= "<p>E-mail: ".$dataUser['email']."</p>";
		$mytext .= "<p>Пароль: ".$dataUser['userpassword']."</p>";	
		$mytext .= "<p>Подписка на новости: ".($dataUser['subsription']?"Да":"Нет")."</p>";	
		if(isset($dataUser['subsription_shop'])){
		    $mytext .= "<p>Подписка на новости магазина: ".($dataUser['subsription_shop']?"Да":"Нет")."</p>";	
		}
		
		$mytext .= "<br/><p class=txt><b>Уважаемый коллега.</b></p>";
        $mytext .= "<p>Благодарим за регистрацию на нашем портале. Надеемся на наше долгое и взаимовыгодное сотрудничество.</p>";
        $mytext .= "<p>С уважением, администрация Клуба Нумизмат.</p>";		
		$mytext .= "</td></tr></table>";	
		$html = $this->createFullHtml($mytext);

		$this->mail->setBodyHtml($html);			   
		$this->mail->send();
	}
	
	public function oneClickLetter($mytext){
		$this->mail->addTo('administrator@numizmatik.ru', 'administrator@numizmatik.ru');
		$this->mail->addTo('bodka@mail.ru', 'bodka@mail.ru');
		$this->mail->setSubject("Монетная лавка - покупка в один клик | Клуб Нумизмат");

		$html = $this->createFullHtml($mytext);

		$this->mail->setBodyHtml($html);
		$this->mail->send();
	}
	
	public function ratingMail($data_mail){
		$this->mail->addTo($data_mail['email'], $data_mail['email']);
		$this->mail->setSubject("Рейтинг в Клубе Нумизмат");
		$mytext ="<table border='0' cellpadding='0' cellspacing='0' width='650' style='border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;'>"; 
	    $mytext .="<tr><td style=\"border:1px solid #cccccc; background-color:#eeeeee;padding:10px;font-size:14px;font-weight:bold;\">Рейтинг в Клубе Нумизмат</td></tr>";
	 	$mytext .= "<tr><td style='padding: 10px;'>";
		//отсылаем письмо пользователю
		//var_dump("http://www.numizmatik.ru/rating/index.php?ratinguser={$data_mail['my_id']}");
		$mytext .= "Уважаемый пользователь!<br>
			Вы успешно зарегистрировались в рейтинге Клуба Нумизмат.<br>
			Спасибо Вам за предоставленные данные. В ближайшее время Ваш сайт будет проверен нашим гидом
			и если он будет соответствовать всем критериям, он будет добавлен в рейтинг.<br>
			Для участия в рейтинге Вам необходимо вставить наш счетчик.<br>
			Для этого необходимо вставить следущие таги в код Вашей странички<br>
			<textarea cols=40 rows=10>
			<a href=\"http://www.numizmatik.ru/rating/index.php?ratinguser={$data_mail['my_id']}\"><br>
			<img src=\"http://www.numizmatik.ru/rating/rating.php?ratinguser={$data_mail['my_id']}\" border=0 width=88 height=31 alt=\"Клуб Нумизмат | TOP 100\"></a>
			</textarea><br><br>
			Ваши данные при регистрации:	<br>		
			Ваш E-mail: {$data_mail['email']}<br>
			Название сайта: {$data_mail['name']}<br>
			URL сайта: {$data_mail['url']}<br>
			Категория сайта: {$data_mail['ratinggroup']}<br>
			Описание: {$data_mail['text']}";	
		$mytext .= "</td></tr></table>";			
		$html = $this->createFullHtml($mytext);
		$this->mail->setBodyHtml($html);
		$this->mail->send();
	}
	
	public function ratingMailSupport($data_mail){
		$email_admin = "bodka@mail.ru";
		//administrator@numizmatik.ru
		$this->mail->addTo($email_admin, $email_admin);
		$this->mail->setSubject("Рейтинг в Клубе Нумизмат");
		$mytext ="<table border='0' cellpadding='0' cellspacing='0' width='650' style='border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;'>"; 
	    $mytext .="<tr><td style=\"border:1px solid #cccccc; background-color:#eeeeee;padding:10px;font-size:14px;font-weight:bold;\">Рейтинг в Клубе Нумизмат</td></tr>";
	 	$mytext .= "<tr><td style='padding: 10px;'>";
		$mytext .="<br><b>Уважаемые господа!</b><br>
			<br>Нужно проверить данные о рейтинговании нового зарегестрированного сайта<br>
			<br><b>Название сайта:</b> {$data_mail['name']}<br>
			<br><b>Url сайта:</b> <a href={$data_mail['url']}>{$data_mail['url']}</a><br>
			<br><b>Категория сайта:</b> {$data_mail['ratinggroup']}<br>
			<br><b>Описание:</b> {$data_mail['text']}<br>
			<br><br>Если сайт удовлетворяет всем условиям нажмите <a href=http://www.numizmatik.ru/admin/ratinguser_admin.php?page=edit&ratinguser={$data_mail['my_id']}&check=1>сюда</a>:<br>
			<br>Если сайт <b>не удовлетворяет</b> всем условиям нажмите <a href=http://www.numizmatik.ru/admin/rating/ratinguser_admin.php?page=delete&ratinguser={$data_mail['my_id']}&check=0>сюда</a>";		
		$mytext .= "</td></tr></table>";	
		$html = $this->createFullHtml($mytext);
		$this->mail->setBodyHtml($html);
		$this->mail->send();
	}
	
	public function orderLetter($dataUser,$mytext){
	    //письмо о заказе
		$this->mail->addTo($dataUser['email'], $dataUser['userlogin']);
		//$this->mail->addTo('bodka@mail.ru', 'bodka@mail.ru');

		$this->mail->setSubject("Монетная лавка | Клуб Нумизмат");

		$html = $this->createFullHtml($mytext);

		$this->mail->setBodyHtml($html);
		$this->mail->send();
		
		
		//Второе письмо на нужный mail
		//$this->mail->addTo('bodka@mail.ru', 'bodka@mail.ru');
		//$this->mail->setSubject("Монетная лавка | Клуб Нумизмат");
		//$html = $this->createFullHtml($mytext);
		//$this->mail->setBodyHtml($html);
		//$this->mail->send();
	}
	
	public function viporderLetter($dataUser,$mytext){
	    //письмо о заказе
		$this->mail->addTo($dataUser['email'], $dataUser['userlogin']);
		//$this->mail->addTo('bodka@mail.ru', 'bodka@mail.ru');
		// $mail->setBodyText('My Nice Test Text');
		$this->mail->setSubject("Заявка на монету в каталоге. Монетная лавка.");

		$html = $this->createFullHtml($mytext);

		$this->mail->setBodyHtml($html);
		$this->mail->send();	
		
	}
	
	public function subscriptionLetter($email,$subject,$mytext){	    
		$this->mail->addTo($email, $email);		
		$this->mail->setSubject($subject);
		$html = $this->createFullHtml($mytext);
		$this->mail->setBodyHtml($html);
		$this->mail->send();	
		
	}
	
	public function commonMail($email,$subject,$text=""){
		
		
		$this->mail->addTo($email, $email);		
		$this->mail->setSubject($subject);
		
		$mytext ="<table border='0' cellpadding='0' cellspacing='0' width='650' style='border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;'>"; 
	    $mytext .="<tr><td style=\"border:1px solid #cccccc; background-color:#eeeeee;padding:10px;font-size:14px;font-weight:bold;\">$subject</td></tr>";
	 	$mytext .= "<tr><td style='padding: 10px;'>";
		//отсылаем письмо пользователю
		//var_dump("http://www.numizmatik.ru/rating/index.php?ratinguser={$data_mail['my_id']}");
		$mytext .= $text;
		$mytext .= "</td></tr></table>";
		
		$html = $this->createFullHtml($mytext);
		$this->mail->setBodyHtml($html);
		$this->mail->send();	
	}
	
	//формирование письма "забыли пароль"
	public function forgetPwdLetter($dataUser){
		$this->mail->addTo($dataUser['email'], $dataUser['userlogin']);	
	   
	    $this->mail->setSubject("Восстановление пароля в Клубе Нумизмат");	 
	    $mytext ="<table border='0' cellpadding='0' cellspacing='0' width='650' style='border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;'>"; 
	    $mytext .="<tr><td style=\"border:1px solid #cccccc; background-color:#eeeeee;padding:10px;font-size:14px;font-weight:bold;\">Восстановление пароля в Клубе Нумизмат</td></tr>";
	 	$mytext .= "<tr><td style='padding: 10px;'><b><p>Ваши данные</b></p>";
	    $mytext .= "<p>E-mail: ".$dataUser['email']."</p>";
		$mytext .= "<p>Пароль: ".$dataUser['userpassword'];		
	    $mytext .= "</td></tr></table>";
		$html = $this->createFullHtml($mytext,"Восстановление пароля в Клубе Нумизмат");
		$this->mail->setBodyHtml($html);	    
        $this->mail->send();
	}
	
	public function createFullHtml($mytext,$subject=''){
		$message = "<html><head><style type='text/css'> body{font-family:arial;font-size:14px;font-weight:bold;}.maintd{font-size:14px;font-weight:bold;border:1px solid #cccccc;padding:10px;}.bordertd{border:1px solid #cccccc; background-color:#eeeeee;padding:10px;font-size:14px;font-weight:bold;}";
		$message .="a:link{font-family:arial;font-weight:bold;color:#006699; TEXT-DECORATION:none; font-size:14px;}"; 
		//$message .="a:visited{font-family:arial;font-weight:bold;color:#006699; TEXT-DECORATION:none; font-size:12px;}"; 
        //$message .="a:active{font-family:arial;font-weight:bold;color:#006699;  TEXT-DECORATION:none; font-size:12px;}";
        //$message .="a:hover{font-family:arial;font-weight:bold;color:#006699; TEXT-DECORATION:none; font-size:12px;}
        $message .="</style></head><body>";
        $message .="<center><table border='0' cellpadding='0' cellspacing='0' width='650'><tr>";
        $message .="<td><img src='http://numizmatik1.ru/new/images/logo_small.jpg'></td>";
        $message .="<td style='font-size:20px;font-weight:bold;line-height:26px;'>";
        $message .="	8-800-333-14-77 (по России бесплатно)<br>";
        $message .="	+7-903-006-00-44 (Москва)<br>";
        $message .="	+7-812-925-53-22 (Санкт-Петербург) <br>";
        $message .="</td></tr></table>";
        $message .="$mytext";
        $message .="<br><br>С уважением, клуб \"Нумизмат\" - <a target='_blank' href='http://www.numizmatik.ru'>www.numizmatik.ru</a></center></body></html>";	

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