<?
require_once('Zend/Validate/Alpha.php');
require_once($cfg['path'] . '/models/calls.php');

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
        $calls = new model_addcall($cfg['db']);
        if($calls->isCallExist($tpl['addcall']['callphone'])){
            $tpl['addcall']['errors'][] = "Сегодня Вы уже оставляли заявку на обратный звонок!";
        } else {
            $data = array('fio' => $tpl['addcall']['callfio'],
                'phone' => $tpl['addcall']['callphone'],
                'date' => time());
            $calls->addNewRecord($data);
            $tpl['addcall']['send_status'] = true;
        }
    }
}