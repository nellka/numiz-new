<? 
require_once $cfg['path'] . '/models/ratinguser.php';
require_once $cfg['path'] . '/models/mails.php';
require_once($cfg['path'] . '/helpers/constants.php');

$ratinguser_class = new model_ratinguser($db_class);
$mail_class = new mails();	

$tpl['breadcrumbs'][] = array(
	    	'text' => 'Клуб Нумизмат',
	    	'href' => $cfg['site_dir'],
	    	'base_href' =>$cfg['site_dir']
);

$tpl['breadcrumbs'][] = array(
	    	'text' => 'Рейтинг сайтов',
	    	'href' => $cfg['site_dir'].'rating',
	    	'base_href' => $cfg['site_dir'].'rating',
);

$tpl['breadcrumbs'][] = array(
	    	'text' => 'Добавление рейтинга',
	    	'href' => $cfg['site_dir'].'rating/registration.php',
	    	'base_href' => ''
);

$tpl['rating']['_Title'] = "Клуб Нумизмат | регистрация сайта в рейтинге";
$tpl['rating']['_Keywords'] = "Новости, нумизматика, фалеристика, бонистика, монеты, каталог, император, государь, ордена, медаль, значки, Петр, антиквариат, коллекция, Николай II, Екатерина II, Романовы, копейка, рубль, деньга, серебро, золото, медь, империя, ОБМЕН ВАЛЮТЫ, рефераты, Великая отечественная война";
$tpl['rating']['_Description'] = "Интернет портал включает большой каталог монет царской России с подробным описанием, возможность создать свой нумизматический интернет магазин, доска обьявлений, статьи и публикации по нумизматике и истории России, адреса магазинов торгующих антиквариатом, ссылки на другие сайты.";

$sql = "select `group`, name from `group` where `type`='rating';";

$tpl['groups'] = $shopcoins_class->getDataSql($sql);

$group = (int) request('group');
$login = request('login');
$email = request('email');
$name = request('name');
$url = request('url');
$ratingkeywords = request('ratingkeywords');
$text  = request('text');
$password1 = request('password1');

$inttostring = rand(1,99);
        
$tpl['inttostring'] = Constants::inttostring($inttostring);                
$tpl['inttostringm'] = md5("Numizmatik".$inttostring);  

$inttostring = request('inttostring');
$inttostringm = request('inttostringm');

if ($submit){	
	//на всяк случай проверку	

	//if (!$login) $tpl['errors'][] = "Не заполнено поле <b>Логин</b>";
	//if (!$password) $tpl['errors'][] =  "Не заполнено поле <b>Пароль</b>";
	//if (!$password1) $tpl['errors'][] = "Не заполнено поле <b>Пароль еще раз</b>";
	if (!$email) $tpl['errors'][] = "Не заполнено поле <b>E-mail</b>";
	if (!$name) $tpl['errors'][] = "Не заполнено поле <b>Название сайта</b>";
	if (!$url) $tpl['errors'][] = "Не заполнено поле <b>Url сайта</b>";
	if(md5("Numizmatik".$inttostring)!=$inttostringm) $tpl['errors'][] = "Неверный код подтверждения";               
           
	if (!$tpl['errors']){		
		//проверка на логин
		/*$sql = "Select * from ratinguser where login='$login';";
		$result = mysql_query($sql);
		if (mysql_num_rows($result)>0)
		{
			echo "<br>Извините, но такой логин уже занят!";
			$select = print_select();
			echo print_form($select);
			table_down ("1", 470);
			include $DOCUMENT_ROOT."/bottom.php";
			exit;
		}*/
		//конец проверки на логин
		
		//проверка на url есть ли в базе
		//убираем http://
		$url = str_replace("http://","",strtolower($url));
		//убираем www
		$url2 = str_replace("www.","",$url);
		$sql = "Select * from ratinguser where url='http://www.".$url."' or url='http://".$url."' or url='".$url2."' or url='http://".$url2."';";
		//echo $sql;
		
		$result = $shopcoins_class->getRowSql($sql);
		if ($shopcoins_class->getRowSql($sql)) $tpl['errors'][] = "Извините, но такой адрес уже существует в рейтинге!";
			
		//проверяем иль есть http://
		$url = "http://".$url;
		//конец проверки на url
	
		$timenow = time();
		$timenow = mktime(0, 0, 0, date("m", $timenow), date("d", $timenow), date("Y", $timenow));
		if(!$tpl['errors']){
			//добавляем в рейтинг
			$data = array('group'=>$group,
							'login'=>'', 
							'password'=>'',
							'email'=>strip_tags($email), 
							'name'=>strip_tags($name), 
							'url'=>strip_tags($url), 
							'description'=>strip_tags($text),
							'enterdate' => $timenow,
							'keywords' => strip_tags($ratingkeywords));
			
			$my_id =  $ratinguser_class->addUrl($data);			
				
			$sql = "Select Name from `group` where `group`=$group;";
					
			$ratinggroup = $shopcoins_class->getOneSql($sql);
			
			$data_mail = array();
			$data_mail['my_id'] = $my_id;
			$data_mail['email'] = $email;
			$data_mail['name'] = $name;
			$data_mail['url'] = $url;
			$data_mail['ratinggroup'] = $ratinggroup;
			$data_mail['text'] = $text;
			
			$mail_class->ratingMail($data_mail);
			$mail_class = new mails();
			//отсылаем письмо тех-поддержке			
			$mail_class->ratingMailSupport($data_mail);
		}		
	}
}
?>
