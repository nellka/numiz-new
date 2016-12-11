<?

require_once($cfg['path'] . '/helpers/constants.php');
require_once($cfg['path'] . '/models/mails.php');


$inttostring = rand(1,99);
$tpl['user']['inttostring'] = Constants::inttostring($inttostring);   

$videolink = request('videolink');
$text = request('text');
$user_inttostring = request('inttostring');
$user_inttostringm = request('inttostringm');
    
$arraythemevideo = array (

1=>'В мире монет',
2=>'Начинающему нумизмату',
3=>'Из истории монет',
4=>'Нумизматические события',
5=>'Полезное для нумизмата'
);

$theme = (int) request('theme');


$tpl['video']['_Title'] = "Видео для нумизматов".($theme?" - ".$arraythemevideo[$theme]:"");
$tpl['video']['_Keywords'] = "Покупка, скупка монет, монеты, аксессуары, книги, цена, ломбард, золото, серебро, медь, coins, нумизматика, Покупка коллекционных материалов";
$tpl['video']['_Description'] = "Клуб Нумизмат предлагает услугу по покупке, скупке монет. Вы ищете где продать монеты? Обращайтесь к нам! Мы поможем Вам продать монеты! ";

$sql_video = "select * from video where `check`=1 ".($theme>0?"and theme & ".pow(2,$theme):"")." order by dateinsert desc;";

$tpl['video'] = $shopcoins_class->getDataSql($sql_video);

$tpl['video']['error'] = array();

if ($_SERVER['REQUEST_METHOD']=='POST' && $tpl['user']['user_id'] ) {
	
	
        
	if (mb_strlen($videolink,'utf8')<10 && mb_strlen($text,'utf8')<10){
		$tpl['video']['error'][] = "Введите корректные данные в поля формы.";
	} else if(mb_strlen($videolink,'utf8')>=10) {
		
		foreach ($arrayvideo as $key=>$value) {
		
			if (substr_count($videolink,$value)>0){
				$tpl['video']['error'][] = "Такая ссылка уже есть на нашем сайте.";
			}
		}
	}
	
	if(md5("Numizmatik".$user_inttostring)!=$user_inttostringm) {
		$tpl['video']['error'][] = "Неправильное число";
	}
		
	if (!$tpl['video']['error']) {
		$mail_class = new mails();
		
		$data = array('userlogin'=>$tpl['user']['username'],
                       'videolink' => $videolink,
                       'text'=>$text);
                 
		$mail_class->newVideoLetter($data);       		
		
		$sql = "update `user` set star=star+10 where `user`='$cookiesuser';";
		$result = mysql_query($sql);
		
		$tpl['video']['error'][] = "Сообщение успешно отправлено!";
	}
}


?>