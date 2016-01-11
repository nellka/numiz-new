<?
require_once($cfg['path'] . '/models/user.php');
require_once($cfg['path'] . '/models/mails.php');
require_once('Zend/Validate/EmailAddress.php');
require_once('Zend/Validate/StringLength.php');

require_once($cfg['path'] . '/helpers/constants.php');

$tpl['user']['errors'] = array();
$tpl['user']['send_status'] = false;
$tpl['user']['email'] =request('email');
$tpl['user']['subscr'] = isset($_REQUEST['subscr'])?1:0; 
$tpl['user']['subscr_shop'] = isset($_REQUEST['subscr_shop'])?1:0; 
$tpl['user']['user_exist'] = request('user_exist'); 
$tpl['user']['password'] = request('password'); 
$datatype = request('datatype'); 
IF($datatype=='json'){
    $validator = new Zend_Validate_EmailAddress(); 
    $mail_class = new mails();
    
    if (!$tpl['user']['user_exist']){    
        //генерирум рандомный пароль для регистрации
        $password = generateString(8);     
        if (!$validator->isValid($tpl['user']['email'])) {
            $tpl['user']['errors'] = "Неверный Email";
        }  else if($userData = $user_class->getRowByParams(array('email'=>$tpl['user']['email']))){
            $tpl['user']['errors'][] = "Пользователь с таким Email уже существует в системе";
        } else {
             $data = array('email'=>$tpl['user']['email'],
                           'userpassword' => $password,
                           'userlogin'=>$tpl['user']['email']);                                                
             //регистрируем
             $userId = $user_class->addNewUser($data);
             //подписываемся на новости
             $data_subscribe = array();
             if($tpl['user']['subscr']){
                 $data_subscribe['news']=1;
             }
             if($tpl['user']['subscr_shop']){
                 $data_subscribe['shopcoins']=1;
             }
             if($data_subscribe) $user_class->addsubscribe($userId,$data_subscribe);          
             
              //отправляется письмо о регистрации     
             $data['subsription'] = $tpl['user']['subscr'];
             $data['subsription_shop']   = $tpl['user']['subscr_shop'];       
             $mail_class->newUserLetter($data);                
             //регистрируем
             $tpl['user']['send_status'] = true;                    
             $res = $user_class->loginUser($tpl['user']['email'],$password) ;   
             //var_dump($tpl['user']['email'],$password,$res);    
            //.die();             
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
        		 //подписываемся на новости
                 $data_subscribe = array();
                 if($tpl['user']['subscr']){
                     $data_subscribe['news']=1;
                 }
                 if($tpl['user']['subscr_shop']){
                     $data_subscribe['shopcoins']=1;
                 }
                 if($data_subscribe) $user_class->addsubscribe($tpl['user']['user_id'],$data_subscribe);      
            }
        }
    }  
}
?>	
