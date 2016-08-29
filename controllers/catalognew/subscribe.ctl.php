<?
require_once($cfg['path'].'/models/catalognew.php');
$catalognew_class = new model_catalognew($db_class);

$tpl['subscribe']['error'] = '';
$tpl['subscribe']['send_status'] = false;

$group = (int)request('group');
$catalog = (int)request('catalog');

if(!$tpl['user']['user_id']){
    $tpl['catalognew']['subscribe']['error'] = 'Чтобы подписаться на группу необходимо авторизоваться!';
} else {
     $userData = $user_class->getUserData();
     $tpl['subscribe']["email"] = $userData["email"];
     $tpl['subscribe']["userlogin"] = $userData["userlogin"];
}

if(!$tpl['catalognew']['subscribe']['error']){

    if($group&&$catalognew_class->getMyGroupSubscribe($tpl['user']['user_id'],$group)){
        $tpl['catalognew']['subscribe']['error'] = 'Вы уже подписаны на эту группу!';
    }

    if($catalog&&$catalognew_class->getMyCatalogSubscribe($tpl['user']['user_id'],$catalog)){
        $tpl['catalognew']['subscribe']['error'] = 'Вы уже подписаны на этот каталог!';
    }
}



if ($group)	{
	$groupData = $shopcoins_class->getGroupItem($group);
	$GroupName = $groupData["name"];
	
	$tpl['subscribe']['title'] = "Подписка на новые поступления в <br><b><font color=red>каталог</font> группы ".$GroupName."</b>";
	
} elseif ($catalog)	{
    
	$sql = "select catalognew.*, group.name as gname
	from catalognew, `group`
	where catalognew.catalog='$catalog'
	and catalognew.group = group.group;";
	
	$result = $catalognew_class->getItem($catalog,true);
	
	$tpl['subscribe']['title'] = "Подписка на изменения в <b><font color=red>каталоге</font> монеты<br> ".$result["gname"]." | ".$result["name"]."</b>";
}
	

if ($_SERVER['REQUEST_METHOD']=='POST'&&!$tpl['catalognew']['subscribe']['error']){	
	if (!$tpl['catalognew']['subscribe']['error']){
	    $catalognew_class->addCatalogsubscribe($tpl['user']['user_id'],$catalog,$group);			
		$tpl['subscribe']['send_status'] = true;
	}
} 

?>
