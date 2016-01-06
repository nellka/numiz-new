<?
require_once($cfg['path'] . '/models/user.php');
require_once($cfg['path'] . '/models/mails.php');
require_once('Zend/Validate/EmailAddress.php');
require_once('Zend/Validate/StringLength.php');

require_once($cfg['path'] . '/helpers/constants.php');

$tpl['user']['errors'] = array();
$tpl['user']['send_status'] = false;

$validator = new Zend_Validate_EmailAddress(); 
$mail_class = new mails();
 var_dump($_REQUEST);
switch ($tpl['task']){    
    case 'registration':{
        $tpl['user']['password'] = request('password');
        $tpl['user']['email'] =request('email');
        $tpl['user']['password_repeat'] = request('password_repeat');
        
        $inttostring = rand(1,99);
        
        $tpl['user']['inttostring'] = Constants::inttostring($inttostring);      
           
        $tpl['user']['inttostringm'] = md5("Numizmatik".$inttostring);      
        //проверяем что пароль не меньше пяти символов
        $pwdValdator =  new Zend_Validate_StringLength(array('min' => 5));
        $user_inttostring = request('inttostring');
        $user_inttostringm = request('inttostringm');
              //  var_dump($user_inttostring,$user_inttostringm ,$inttostring ,$tpl['user']['inttostringm']);
        if($_SERVER['REQUEST_METHOD'] == 'POST'){  
            $tpl['user']['subscr'] = request('subscr');              
            if (!$validator->isValid($tpl['user']['email'])) {
                // email прошел валидацию
                $tpl['user']['errors'][] = "Неверный Email";
            }  else if(!$pwdValdator->isValid($tpl['user']['password'])){
                 $tpl['user']['errors'][] = "Слишком короткий пароль";
            } else if($tpl['user']['password']!=$tpl['user']['password_repeat']){
                 $tpl['user']['errors'][] = "Пароль и подтверждение пароля не совпадают";
            } elseif(md5("Numizmatik".$user_inttostring)!=$user_inttostringm) {
                 $tpl['user']['errors'][] = "Неверный код подтверждения";               
            }  else if($userData = $user_class->getRowByParams(array('email'=>$tpl['user']['email']))){
                 $tpl['user']['errors'][] = "Пользователя с таким Email уже существует в системе";
            }  else {
                 $data = array('email'=>$tpl['user']['email'],
                               'userpassword' => $tpl['user']['password'],
                               'userlogin'=>$tpl['user']['email']);   
                                             
                 $userId = $user_class->addNewUser($data,$tpl['user']['subscr']);
                 //регистрируем
                 $tpl['user']['send_status'] = true; 
                 //отправляется письмо о регистрации  
                 $data['subsription'] = $tpl['user']['subscr'];         
                 $mail_class->newUserLetter($data);                
                 $user_class->loginUser($tpl['user']['email'],$tpl['user']['password']) ;
                 $user_base_data = $user_class->getUserBaseData();
			     $tpl['user'] = array_merge($tpl['user'],$user_base_data);
                            
            }    
        } else {
            $tpl['user']['subscr'] = 1;
        }
        break;
    }
    case 'subsription':{
    	//генерирум рандомный пароль для регистрации
        $password = generateString(8);
        $subsriptionMail = request('subsription-mail');        
            
        if($_SERVER['REQUEST_METHOD'] == 'POST'){  
         
            if (!$validator->isValid($subsriptionMail)) {
                $tpl['subsription']['errors'] = "Неверный Email";
            }  else {
                 $data = array('email'=>$subsriptionMail,
                               'userpassword' => $password,
                               'userlogin'=>$subsriptionMail);                                                
                 //регистрируем
                 $userId = $user_class->addNewUser($data,1);      
                 $tpl['subsription']['errors'] = "Спасибо за подписку на наш ресурс!";                 
                  //отправляется письмо о регистрации     
                 $data['subsription'] = 1;         
                 $mail_class->newUserLetter($data);                     
            }    
        } 
        break;
    }
    case 'remind':{
        $tpl['user']['email'] = isset($_REQUEST['email'])?$_REQUEST['email']:"";
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // email прошел валидацию
            if (!$validator->isValid($tpl['user']['email'])) {                
                $tpl['user']['errors'][] = "Неверный Email";
            } else if(!$userData = $user_class->getRowByParams(array('email'=>$tpl['user']['email']))){
                 $tpl['user']['errors'][] = "Пользователя с таким Email не существует в системе";
            } else {
                //посылаем письмо
                $mail_class->forgetPwdLetter($userData);    
                $tpl['user']['send_status'] = true;
            }        
        }
        break;
    }
    case 'login':{

    	if($tpl['user']['is_logined']){
    		$tpl['user']['errors'][] = "Вы уже авторизованы на сайте!";  
    	} else{
	        $tpl['user']['password'] = request('password');
	        $tpl['user']['username'] =request('username');
	        $tpl['user']['remember_me'] = request('remember_me');
	        if($_SERVER['REQUEST_METHOD'] == 'POST'){
	            $is_logined = $user_class->loginUser($tpl['user']['username'],$tpl['user']['password']);
	            if(!$is_logined) {
	            	$tpl['user']['errors'][] = "Неверные логин/пароль";           
	            } else {
	            	$tpl['user']['send_status'] = true;
	            	$user_base_data = $user_class->getUserBaseData();
					$tpl['user'] = array_merge($tpl['user'],$user_base_data);
	            }
	        } 
    	}
        break;
    }   
}
//в будещем планируется подгружать через виджет, надо подумать как
if(in_array($tpl['task'],array('login','registration'))&&$tpl['user']['send_status']){
	
	$html_for_ajax ='<p>Здравствуйте, <b>'.$tpl['user']['username'].'</b>!</p>'.
	'<p><a href="http://numizmatik.ru/user/profile.php" title="Просмотр/редактирование личных данных/настроек">Ваш профайл</a></p>'.
	'<p><form action="'.$cfg['site_dir'].'" method="POST">'.
	'<input type="hidden" value="1" name="logout" id="logout">'.
	'<input type="submit" class="yell_b"  value="Выход">'.
	'</form>';
}
?>	
