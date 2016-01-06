<?
require_once($cfg['path'] . '/models/user.php');
require_once($cfg['path'] . '/models/mails.php');
require_once('Zend/Validate/EmailAddress.php');
require_once('Zend/Validate/StringLength.php');

require_once($cfg['path'] . '/helpers/constants.php');

$tpl['user']['errors'] = array();
$tpl['user']['send_status'] = false;
$tpl['user']['email'] =request('email');
$tpl['user']['subscr'] = request('subscr'); 
$tpl['user']['subscr_shop'] = request('subscr_shop'); 
$tpl['user']['user_exist'] = request('user_exist'); 
$tpl['user']['password'] = request('password'); 
$datatype = request('datatype'); 
IF($datatype=='json'){
$validator = new Zend_Validate_EmailAddress(); 
$mail_class = new mails();

if (!$tpl['user']['user_exist']){    
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
		if($tpl['user']['is_logined']){
			$tpl['user']['send_status'] = true;  
		} else{
	        $is_logined = $user_class->loginUser($tpl['user']['email'],$tpl['user']['password']);
	        if(!$is_logined) {
	        	$tpl['user']['errors'][] = "Неверные логин/пароль";           
	        } else {
	        	$tpl['user']['send_status'] = true;
	        	$user_base_data = $user_class->getUserBaseData();
				$tpl['user'] = array_merge($tpl['user'],$user_base_data);
	        }
		}
    }   

}
?>	
