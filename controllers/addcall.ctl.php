<?
require_once('Zend/Validate/Alpha.php');
require_once($cfg['path'] . '/models/calls.php');
require_once($cfg['path'] . '/helpers/workTimeInterval.php');


$writer = new Zend_Log_Writer_Stream($log_calls);
$logger    = new Zend_Log($writer);

$tpl['addcall']['errors'] = array();
$tpl['addcall']['callfio'] = isset($_REQUEST['callfio'])?$_REQUEST['callfio']:"";
$tpl['addcall']['callphone'] = isset($_REQUEST['callphone'])?$_REQUEST['callphone']:"";

$tpl['addcall']['send_status'] = false;

$validator_text = new Zend_Validate_Alpha(array('allowWhiteSpace' => true));


if($_SERVER['REQUEST_METHOD']=='POST'){
    if(!$tpl['addcall']['callfio']){
        $tpl['addcall']['errors'][] = "Вы не указали имя";
    }
    if(!$tpl['addcall']['callphone']){
        $tpl['addcall']['errors'][] = "Введите номер телефона";
    }
    if(!$validator_text->isValid($tpl['addcall']['callfio'])){
    	$tpl['addcall']['errors'][] = "Поле имя должно содержать только буквы!";
    }
    if(!$tpl['addcall']['errors']){
        $calls = new model_addcall($db_class);
        if($calls->isCallExist($tpl['addcall']['callphone'])){
            $tpl['addcall']['errors'][] = "Сегодня Вы уже оставляли заявку на обратный звонок!";
        } else {
            $data = array('fio' => $tpl['addcall']['callfio'],
                'phone' => $tpl['addcall']['callphone'],
                'date' => time());
            $calls->addNewRecord($data);
            $tpl['addcall']['send_status'] = true;

            /*if(workTimeInterval::ocenkaWork()){
                
                $api_key  = $cfg['mango']['api_key'];
                $api_salt = $cfg['mango']['api_salt'];
                $url = $cfg['mango']['url'];

                $phone_int =  preg_replace("/[^0-9]/", '', $tpl['addcall']['callphone']);

                $data = array( "command_id" => "call-to-user",
                               "from"       => array( "extension" => "007",
                                                      "number"    => ""      ),
                                "to_number"  => $phone_int  );

                $json = json_encode($data);

                $sign = hash('sha256', $api_key . $json . $api_salt);
                $postdata = array('vpbx_api_key' => $api_key,
                                  'sign'         => $sign,
                                  'json'         => $json );
               $post = http_build_query($postdata);

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                $response = curl_exec($ch);

                curl_close($ch);
                $logger->log("new request for call in work time",Zend_Log::INFO); 
            }  else {
                $logger->log("new request for call not in work time",Zend_Log::INFO); 
            }*/
        } 
    }
}