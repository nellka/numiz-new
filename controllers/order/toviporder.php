<?
require_once $cfg['path'] . '/models/viporder.php';
require_once $cfg['path'] . '/models/user.php';
require $cfg['path'] . '/configs/config_shopcoins.php';

$user_class = new model_user($cfg['db']);
$user_class->setIdentity($user_id);

$user_data =  $user_class->getUserData();

$viporder_class = new model_shopcoinsvipclientanswer($cfg['db']);

$viporder_id = $viporder_class->getNewViporder();

$viporderCoinsIds = array();

foreach ($orderdetails as 	$row ){	
	$viporder_class->addInOrder($viporder_id,$row["catalog"]);	
	$viporderCoinsIds[]	 = $row["catalog"];			
}

require_once($cfg['path'] . '/models/mails.php');
include $cfg['path']."/views/mails/vipmail.tpl.php";
$mail_class = new mails();	
$mail_class->viporderLetter($user_data,$mail_text);	
