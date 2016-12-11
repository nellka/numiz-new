<? 
require_once $cfg['path'] . '/models/subscribe.php';

$shopcoins = (bool)request('shopcoins');
$news = (bool)request('news');
$tboard = (bool)request('tboard');
$blacklist = (bool)request('blacklist');
$buycoins = (bool)request('buycoins');
$biblio = (bool)request('biblio');
$advertise = (bool)request('advertise');
$typemail = request('typemail');

$tpl['subscribe']['_Description'] = 'Последние новости нумизматики.'.$Meta[1];
$tpl['subscribe']['_Title'] = "Интернет портал включает большой каталог монет царской России с подробным описанием, возможность создать свой нумизматический интернет магазин, доска обьявлений, статьи и публикации по нумизматике и истории России, адреса магазинов торгующих антиквариатом, ссылки на другие сайты.";
$tpl['subscribe']['_Keywords'] = "Пресса, журналист, журнал, газеты, телевидение, Новости, нумизматика, фалеристика, бонистика, монеты, каталог, император, государь, ордена, медаль, значки, Петр, антиквариат, коллекция, Николай II, Екатерина II, Романовы, копейка, рубль, деньга, серебро, золото, медь, империя, ОБМЕН ВАЛЮТЫ, рефераты, Великая отечественная война";

$tpl['subscribe']['error'] = false;
$tpl['subscribe']['saved'] = false; 

if(!$tpl['user']['user_id']){    
    $tpl['subscribe']['error'] =  true; 
} else {
    $subscribe_class = new model_subscribe($db_class,$tpl['user']['user_id']);
    $user_data =  $user_class->getUserData();
    $mailkey = md5("Numizmatik_Ru".$user_data['user'].$user_data['userpassword']);
    
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $tpl['subscribe']['data'] = array("shopcoins" => (int)$shopcoins,
                                          "news"      => (int)$news,
                                          "tboard"    => (int)$tboard,
                                          "blacklist" => (int)$blacklist,
                                          "buycoins"  => (int)$buycoins,
                                          "biblio"    => (int)$biblio,
                                          "advertise" => (int)$advertise,
                                          "mailkey"   => $mailkey,
                                          "typemail"  => (int)$typemail);
        $subscribe_class->updateSubscribes($tpl['subscribe']['data']); 
        $tpl['subscribe']['saved'] = true;                                
    } else {
        $tpl['subscribe']['data'] = $subscribe_class->getSubscribes();
    }

/*
$MainFolderMenuX=4; 
$MainFolderMenuY=19;

$shopcoins = $_POST["shopcoins"];

if ($mailkey)
{
	$sql = "select * from subscribe where mailkey ='$mailkey';";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	
	if ($rows[0])
	{
		$sql_user = "select * from `user` where `user`='".$rows["user"]."';";
		$result_user = mysql_query($sql_user);
		$rows_user = mysql_fetch_array($result_user);
		$login = $rows_user["userlogin"];
		$password = $rows_user["userpassword"];
	}
	else
	{
		$error = "Неправильно скопирована ссылка с письма";
	}

	//показываем на что подписаны
	
*/
}
?>