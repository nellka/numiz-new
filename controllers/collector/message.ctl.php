<? 

$user_to = (int) request('user_to');
$collectors_message = (int) request('collectors_message');
$topic  = request('topic');
$details = request('details'); 
//$user_to = '352480';
require_once($cfg['path'].'/models/collector.php');
require_once($cfg['path'].'/models/mails.php');

$mail_class = new Mails();

$collectors_class = new model_collectors($db_class);

$tpl['collector']['errors'] = array();

$error = false;

if (!$tpl['user']['user_id']){
	$tpl['collector']['no_auth'] = true;
	$error = true;
}

if(!$error&&$collectors_message){
	//получаем автора
	$message_data = $user_class->Message_show($collectors_message);
	if(!$message_data){
		$tpl['collector']['mess_error'] = "Сообщения не существует";
		$error = true;
	} else if($message_data["user_to"]!=$tpl['user']['user_id']){
		$tpl['collector']['mess_error'] = "Вы пытаетесь ответить на сообщение, адресованное другому пользователю";
		$error = true;
	} else {		
		$user_to = $message_data["user_from"];
	}

	if(!$error&&$_SERVER['REQUEST_METHOD']!='POST'){
		$topic ="RE: ".$message_data["topic"];
		$details ="\n\n\n\n-------------Сообщение-------------\n".$message_data["details"];
	}
}

if(!$user_to){
	$tpl['collector']['no_user_to'] = true;
} elseif(!$error) {
	$user = $user_from  = $tpl['user']['user_id'];    
    $chat = (int) request('chat');
    
    $action="send";
    $tpl['collector']['user_to'] = $user_class->getUserDataByID($user_to);
    
	if(!$tpl['collector']['user_to']){
		$tpl['collector']['errors'][] = "Такого пользователя не существует";
	}
		/*-1 - не заполнено ФИО
		-2 - не заполнен email
		-3 - 
		-4 - не заполнен адрес
		-5 - нет пользователя
		-6 - нет пользователя в базе
		-7 - ошибка в базе данных при записи информации о пользователе
		-8 - ошибка в базе данных при записи информации как коллекционера	*/

    if ($_SERVER['REQUEST_METHOD']!='POST'){
		//echo $myclass->Message_form();
	} else {
		if (!$user_to) $tpl['collector']['errors'][] = "Не указан пользователь";
		if (!$topic) $tpl['collector']['errors'][] = "Не заполнена тема";
		if (!$details) $tpl['collector']['errors'][] = "Не заполнен текст";
		
		if(!$tpl['collector']['errors']){
			$data = array('user_from'=>$user_from, 
						  'user_to'=>$user_to,
						  'topic'=>$topic,
						  'details'=>$details, 
						  'date'=>time());
			$collectors_class->addMessage($data);	
			$message = "<p>Уважаемый пользователь Клуба Нумизмат.
			<br><b>Пользователь: </b>".$tpl['user']['fio']."
			<br>отправил Вам сообщение
			<br><br><b>Текст сообщения:</b>
			<br>".str_replace(chr(13), "<br>", $details)."
			<br><br>Это сообщение сохранено в Вашем профайле на сервере.
			<br><br>Клуб Нумизмат - <a href=http://www.numizmatik.ru>www.numizmatik.ru</a>";
					
			$mail_class = new mails();
			
			$mail_class->commonMail($tpl['collector']['user_to']['email'],"Клуб Нумизмат",$message);	
					
			$tpl['collector']['send_status'] = "<br><b>Ваше сообщение успешно отправлено</b><br>";
		}	
	}	
}



?>