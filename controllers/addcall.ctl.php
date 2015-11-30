<?

require_once($cfg['path'] . '/models/calls.php');

$tmp['addcall']['errors'] = array();
$tmp['addcall']['callfio'] = isset($_REQUEST['callfio'])?$_REQUEST['callfio']:"";
$tmp['addcall']['callphone'] = isset($_REQUEST['callphone'])?$_REQUEST['callphone']:"";

$tmp['addcall']['send_status'] = false;

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(!$tmp['addcall']['callfio']){
        $tmp['addcall']['errors'][] = "Вы не указали имя";
    }
    if(!$tmp['addcall']['callphone']){
        $tmp['addcall']['errors'][] = "Введите номер телефона";
    }
    if(!$tmp['addcall']['errors']){
        $calls = new model_addcall($cfg['db']);
        $data = array('fio'=>$tmp['addcall']['callfio'],
                      'phone'=>$tmp['addcall']['callphone'],
                      'date'=>time());
        $calls->addNewRecord($data);
        $tmp['addcall']['send_status'] = true;
    }
}