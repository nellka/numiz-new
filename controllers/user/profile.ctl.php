<? 
if(!$tpl['user']['user_id']){
	header("location: http://www.numizmatik.ru/user/login.php");
	die('');
}

require_once('Zend/Validate/EmailAddress.php');
require_once('Zend/Validate/StringLength.php');
require_once($cfg['path'].'/helpers/imageMini.php');

$secretcod = "hdyeiosmcjdushfgzxb5867hgj12znzm";
$city_array = Array ("Другой", "Москва", "Санкт-Петербург", "Нижний Новгород", "Тольятти", "Калининград", "Владивосток", "Самара", "Новосибирск", "Минск", "Киев");
$type_inshops = Array (1 => "Монеты", 2 => "Боны", 3 => "Ордена и медали", 4 => "Антиквариат", 5 => "Другое (например книги)");

$month2 = array (
				1 => "января",
				2 => "февраля",
				3 => "марта",
				4 => "апреля",
				5 => "мая",
				6 => "июня",
				7 => "июля",
				8 => "августа",
				9 => "сентября",
				10 => "октября",
				11 => "ноября",
				12 => "декабря",
				);
$tpl['user']['_Title'] = "Клуб Нумизмат | Регистрация пользователя";
$tpl['user']['_Keywords'] = ", нумизматика, фалеристика, бонистика, монеты, каталог, император, государь, ордена, медаль, значки, Петр, антиквариат, коллекция, Николай II, Екатерина II, Романовы, копейка, рубль, деньга, серебро, золото, медь, империя, ОБМЕН ВАЛЮТЫ, рефераты, Великая отечественная война";
$tpl['user']['_Description'] = ", Интернет портал включает большой каталог монет царской России с подробным описанием, возможность создать свой нумизматический интернет магазин, доска обьявлений, статьи и публикации по нумизматике и истории России, адреса магазинов торгующих антиквариатом, ссылки на другие сайты.";
$r_url = $cfg['site_dir'].'user/profile.php';
$action = request('action');
$pageinfo = request('pageinfo');

$url = str_replace(array("http://","'"),"",request('url'));
$fio = str_replace("'","",request('fio'));
$email = str_replace("'","",request('email'));
$city1 = str_replace("'","",request('city1'));
$phone = str_replace("'","",request('phone'));
$text = str_replace("'","",request('text'));
$adress = str_replace("'","",request('adress'));

$tpl['user']['data'] = $user_class->getUserData();
$CollectorInfo = $user_class->getCollectorInfo();

$tpl['user']["collectors"]['id'] = 0;
$tpl['user']["collectors"]['interests'] = array();
if($CollectorInfo) {
	$tpl['user']["collectors"]['id'] = $CollectorInfo['collectors'];
	for ($i=1; $i<=sizeof($interests); $i++){
		if($CollectorInfo['interest'.$i]) $tpl['user']["collectors"]['interests'][] = $i;		 	
	}
}

if ($pageinfo=="collectors" && $_SERVER['REQUEST_METHOD']=="POST")	{
	//загружаем фото если есть
	if($_FILES&&$_FILES['file']){
		$fileSize	= $_FILES['file']['size'];			  
		/*user file name*/
		$fileUserNameToUpload 	= $_FILES['file']['name'];
		  
		/*name of the user file on server in a temp directory*/
		$fileNameToUpload 		= $_FILES['file']['tmp_name'];
		  
		/*final name for a file tu put on the server*/
		$path_on_server = "/var/www/htdocs/numizmatik.ru/collector/";
		
		$fileName= "";
		if($fileNameToUpload && ($fileSize >= 200000 || $fileSize = 0)) {
			$tpl['user']['error_coll'] =" Ошибка при загрузке фотографии";
		}	else {			
	        $stype =  exif_imagetype($fileNameToUpload);
 			/**
			 * Check if the type of a file is gif|jpeg|jpg .
			 */
			if($stype==IMAGETYPE_GIF) {
				$fileType = 'gif';
			} elseif($stype==IMAGETYPE_JPEG) {
				$fileType = 'jpg';
			} else $tpl['user']['error_coll'] =" Ошибка при загрузке фотографии";
			
			/**
			 * If some photo was already uploaded - delete it.
			 */	
			if(!$tpl['user']['error_coll'])	{	
				if($tpl['user']['data']['photo']){				
					unlink($path_on_server."images/".$tpl['user']['data']['photo']);
					unlink($path_on_server."tmp/".$tpl['user']['data']['photo']);
				}		

				$fileNameOnServer = $path_on_server."tmp/".$tpl['user']["user_id"].".$fileType";

				if (is_uploaded_file($fileNameToUpload)){
					$res = move_uploaded_file($fileNameToUpload, $fileNameOnServer);
				}
				$fileName= $tpl['user']["user_id"].".$fileType";
				//require("../advertise/resizeImageAndSave.php");
				//resizeImageAndSave("tmp/", "collector/images/", $fileName, 200, 200);
				$res = imageMini::SaveSmallImage($path_on_server."tmp/", $path_on_server."images/",$fileName, 200, 200,false);	
				//var_dump($res );
				$data_update = array();
				$tpl['user']['data']['photo']=$data_update['photo'] = $fileName;
				//var_dump($fileName);	
				$user_class->updateUser($data_update);
			}					
		}		
	}

	require_once($cfg['path'].'/models/collector.php');
	$collectors_class = new model_collectors($db_class);
	$tpl['user']["collectors"]['interests'] = array();
	$collector_info = array();
	$collector_info['user'] = $tpl['user']['user_id'];
	for ($i=1; $i<=sizeof($interests); $i++){
		if(isset($_REQUEST['interest'.$i])&&$_REQUEST['interest'.$i]){
			$collector_info['interest'.$i] = 1;
			$tpl['user']["collectors"]['interests'][] = $i;
		} else 	$collector_info['interest'.$i] = 0;		
	}
	if(!$tpl['user']["collectors"]['id'] ){
		//add
		$collector_info['date'] = time();
		$tpl['user']["collectors"] = $collectors_class->addCollector($collector_info);
	} else {
		//edit
		$collectors_class->updateCollector($collector_info,$tpl['user']['user_id']);
	}
	
	$tpl['user']['send_status'] = true;
	
} elseif ($pageinfo=="edit" && $_SERVER['REQUEST_METHOD']=="POST")	{
	$validator = new Zend_Validate_EmailAddress(); 
	
	$data_update = array();
	$data_update_vbull = array();
	$email = request('email');
	
	$monthbith= request('monthbith');
	$daybith = request('daybith'); 
	$yearbith = request('yearbith');
	$city = (int)request('city');
	
	$sex = str_replace("'","",request('sex'));
	$emailcheck = (int) request('emailcheck');
	$sms = intval($sms);
		
	if ($email != $tpl['user']['data']['email']) {
		$data_update['keyemail'] =2;
		$data_update['email'] = $tpl['user']['data']['email'] = $email;
		$data_update_vbull['email'] = $email;
		$userWithMail = $user_class->getRowByParams(array('email'=>$email));
		if($userWithMail&&$userWithMail['user']!=$tpl['user']['user_id']){
			$tpl['user']['errors_mail'] = true;
		}		
	}
		
	if (checkdate($monthbith,$daybith,$yearbith)&&($monthbith!=1 || $daybith!=1 || $yearbith!=1970)) {
		$datebithday = mktime(5,0,0,$monthbith,$daybith,$yearbith);
	}	else $datebithday = $tpl['user']['data']['datebithday'];
		
	if ($city1) $city=$city1;	
	if ($url) $url = "http://".$url;
    $tpl['user']['data']['fio'] =  $data_update['fio'] = $fio;
    $tpl['user']['data']['url'] =  $data_update['url'] = $url;
    $tpl['user']['data']['sex'] =  $data_update['sex'] = $sex;
    $tpl['user']['data']['city'] =  $data_update['city'] = $city;
    $tpl['user']['data']['phone'] = $data_update['phone'] = $phone;
    $tpl['user']['data']['text'] = $data_update['text'] = $text;
    $tpl['user']['data']['emailcheck'] =  $data_update['emailcheck'] = $emailcheck;
    $tpl['user']['data']['datebithday'] = $data_update['datebithday'] = $datebithday;
    $tpl['user']['data']['sms'] = $data_update['sms'] = $sms;
    $tpl['user']['data']['adress'] = $data_update['adress'] = $adress;
    
     if (!$validator->isValid($tpl['user']['data']['email'])) {
         $tpl['user']['errors_mail'] = true;
     } else {           
	    $user_class->updateUser($data_update);
	    
	    $data_update_vbull['birthday'] = date('m-d-Y',$rows['datebithday']);
	    $data_update_vbull['birthday_search'] = date('Y-m-d',$rows['datebithday']);
	    
	    $user_class->updateVbullUser($data_update_vbull,$tpl['user']['data']['userlogin']);
		$tpl['user']['send_status'] = true;
	}
		//$tpl['user']['data'] = $user_class->getUserData();
} elseif ($pageinfo=="password"&& $_SERVER['REQUEST_METHOD']=="POST") {
		
	$pwdValdator =  new Zend_Validate_StringLength(array('min' => 5));
	
	$userpassword1 = request('userpassword1');
	$userpassword2 = request('userpassword2');
	$userpassword3 = request('userpassword3');
	if ($userpassword1!=$tpl['user']['data']['userpassword']){
		$tpl['user']['error_pwd'] ="<b>Неверный старый пароль</b>";
	} elseif(!$pwdValdator->isValid($userpassword2)){
        $tpl['user']['error_pwd'] = "<b>Слишком короткий пароль(минимум 5 символов)</b>";
    } elseif ($userpassword2!=$userpassword3){
		$tpl['user']['error_pwd'] ="<b>Неверное сочитание полей новый пароль и Подтверждение нового пароля</b>";
	} else {
		$data_update = array();
		$data_update['userpassword'] = $userpassword2;
		$user_class->updateUser($data_update);
	    $user_class->updateForumPwd($userpassword2,$tpl['user']['data']['userlogin']);	
	    $user_class->loginUser($tpl['user']['data']['userlogin'],$userpassword2) ;
	    $tpl['user']['send_status'] = true;
	} 
}

/*
function form_inshops($name, $description, $keywords, $type, $pricelist, $action)
{
	global $cookiesuserlogin, $type_inshops, $script, $in;
	$form .= "<b>Информация о Интернет магазине пользователя:</b>";
	$form .= "<form action=$script?pageinfo=inshop&action=$action method=post enctype='multipart/form-data'>";
	$form .= "<table width=100% cellpadding=3 cellspacing=0 border=0>";
	$form .= "<tr><td class=tboard>Название интернет магазина: (<font color=red>*</font>)</td>
	<td class=tboard><input type=text name=name value='$name' size=40 maxlength=40 class=formtxt></td></tr>";
	$form .= "<tr><td class=tboard>Пользователь:</td>
	<td class=tboard><input type=text value='$cookiesuserlogin' size=40 maxlength=40 class=formtxt disabled></td></tr>";
	$form .= "<tr><td class=tboard valign=top>Описание интернет магазина:</td>
	<td class=tboard><textarea name=descriptioninshops cols=30 rows=5 class=formtxt wrap=soft>$description</textarea></td></tr>";
	$form .= "<tr><td class=tboard valign=top>Ключевые слова интернет магазина:</td>
	<td class=tboard><textarea name=keywordsinshops cols=30 rows=5 class=formtxt wrap=soft>$keywords</textarea></td></tr>";
	$form .= "<tr><td class=tboard>Основной тип интернет магазина:</td><td class=formtxt><select name=type class=formtxt>";
	for ($i=1; $i<=5; $i++)
	{
		if ($i == $type)
		{
			$form .= "<option value=$i selected>".$type_inshops[$i];
		} else {
			$form .= "<option value=$i>".$type_inshops[$i];
		}
	}
	$form .= "</select></td></tr>";
	if ($pricelist=="")
	{
		$form .= "<tr><td class=tboard>Прайс лист: <br>(только <b>EXCEL</b> файлы < 30 кБ)</td>
		<td class=tboard valign=top><input type=\"File\" name=\"pricelist\" class=formtxt></td></tr>";
	} else {
		$form .= "<tr><td class=tboard>Прайс лист:</td>
		<td class=tboard valign=top><a href=".$in."inshops/pricelist/".$pricelist." target='_blank'>
		<img src=".$in."/images/excel.gif border=0 alt='Посмотреть файл'></a>&nbsp;&nbsp;
		<a href=$script?pageinfo=inshop&action=deletepricelist><img src=".$in."/images/delete.gif border=0 alt='Удалить файл'></a>&nbsp;&nbsp;</a>
		</td></tr>";
	}
	$form .= "<tr><td class=tboard align=center colspan=2><input type=submit name=submit value='";
	if ($action=="add")
	{
		$form .= "Создать интернет магазин";
	} elseif ($action=="edit") {
		$form .= "Изменить  информацию о интернет магазине";
	}
	$form .= "' class=formtxt></td></tr>";
	$form .= "</table></form>";
	//if ($action=="edit") $form .= "<p class=txt align=center><a href=inshop.php>Содержимое интернет магазина</a>";
	return $form;
}


					
	} elseif ($pageinfo=="edit" and (!$submit)) {
		
	} else elseif ($pageinfo=="password" and (!$submit)) {
		
		include $in."top.php"; 
		table_top ("100%", 0, "Профайл пользователя:", 1);
		
		
	} elseif ($pageinfo=="inshop" and (!$submit)) {
		
		include $in."top.php"; 
		table_top ("100%", 0, "Профайл пользователя:", 1);
		
		if ($action == "deletepricelist")
		{
			$sql = "select * from inshops where user='$cookiesuser';";
			$result = mysql_query($sql);
			$rows = mysql_fetch_array($result);
			$sql = "select pricelist from inshops where inshops='$rows[0]';";
			$result = mysql_query($sql);
			$rows1 = mysql_fetch_array($result);
			if ($rows1[0]) unlink ($pricelistfolder."/".$rows1[0]);
			$sql = "update inshops set pricelist='' where inshops=$rows[0];";
			$result = mysql_query($sql);
		}
		$sql = "select * from inshops where user='$cookiesuser';";
		$result = mysql_query($sql);
		$rows = mysql_fetch_array($result);
		if ($rows[0])
		{
			echo form_inshops($rows[name], $rows[description], $rows[keywords], $rows[type], $rows[pricelist], "edit");
		} else {
			echo form_inshops($cookiesuserlogin,'', '', 1, '', 'add');
		}
	} elseif ($pageinfo=="inshop" and ($submit) and (($action=="add") or ($action=="edit"))) {
		
		include $in."top.php"; 
		table_top ("100%", 0, "Профайл пользователя:", 1);
		
		$sql = "select * from inshops where user='$cookiesuser';";
		$result = mysql_query($sql);
		$rows = mysql_fetch_array($result);
		if ($rows[0])
		{
			//делаем update полей
			
			if ($pricelist)
			{
				//добавляем прайслист
				$max_size = 30000;                                // максим. размер в БАЙТАХ
				$type_1 = "application/vnd.ms-excel";
				$type_2 = "application/octet-stream";
				$type_3 = "application/x-msexcel";
				// типы файлов, которые
				if  ($pricelist_size > $max_size)
				{
					echo "Неверный размер! Файл $pricelist_name НЕ загружен.<br>"; 
				} elseif  (($pricelist_type != $type_1) and ($pricelist_type != $type_2) and ($pricelist_type != $type_3)) {
					echo "Неверный тип! Файл $pricelist_name ($pricelist_type) НЕ загружен.<br>"; 
				} else {
					$sql = "select pricelist from inshops where inshops='$rows[0]';";
					$result = mysql_query($sql);
					$rows1 = mysql_fetch_array($result);
					if ($rows1[0]) unlink ($pricelistfolder."/".$rows1[0]);
					$type_file="xls";
					copy ("$pricelist", "$pricelistfolder/".($rows[0]).".".$type_file);
					$pricelist = ($rows[0]).".".$type_file;
					$sql1 = ", pricelist='$pricelist' ";
				}
			} else {
				$sql1 = "";
			}
			
			$sql = "update inshops set name='$name', description='$descriptioninshops', 
			keywords='$keywordsinshops', type='$type' $sql1 where inshops=$rows[inshops];";
			$result = mysql_query($sql);
			echo "<b>Ваши данные успешно изменены!</b><br>Автоматический переход на страницу Вашего профайла осуществится через 3 секунды";
			echo "<META HTTP-EQUIV=Refresh CONTENT=3;URL=\"$script\">";
		} else {
			if ($name)
			{
				if ($pricelist)
				{
					//добавляем прайслист
					$max_size = 30000;                                // максим. размер в БАЙТАХ
					$type_1 = "application/vnd.ms-excel";
					$type_2 = "application/octet-stream";				// типы файлов, которые
					$type_3 = "application/x-msexcel";
					if  ($pricelist_size > $max_size)
					{
						echo "Неверный размер! Файл $pricelist_name НЕ загружен."; 
					} elseif  (($pricelist_type != $type_1) and ($pricelist_type != $type_2) and ($pricelist_type != $type_3)) {
						echo "Неверный тип! Файл $pricelist_name ($pricelist_type) НЕ загружен."; 
					} else {
						$sql = "select max(inshops) from inshops;";
						$result = mysql_query($sql);
						$rows = mysql_fetch_array($result);
						$type_file="xls";
						if ($rows!="")
						{
							copy ("$pricelist", "$pricelistfolder/".($rows[0]+1).".".$type_file);
							$pricelist = ($rows[0]+1).".".$type_file;
						} else {
							copy ("$pricelist", "$pricelistfolder/1.".$type_file);
							$pricelist = "1.".$type_file;
						}
					}
				} else {
					$pricelist = "";
				}
				$sql = "insert into inshops (inshops, name, user, description, keywords, type, amount, 
				pricelist, date) values 
				(0, '".strip_string($name)."', $cookiesuser, '".strip_string($descriptioninshops)."',
				'".strip_string($keywordsinshops)."', '$type', 0, '$pricelist',".time().");";
				$result = mysql_query($sql);
				echo "<b>Ваши данные успешно добавлены!</b><br>Автоматический переход на страницу Вашего профайла осуществится через 3 секунды";
				echo "<META HTTP-EQUIV=Refresh CONTENT=3;URL=\"$script\">";
			} else {
				echo "<img src=".$in."images/attention.gif alt='Внимание'> Поле <b>\"Название интернет магазина\"</b> должно быть заполнено<br>";
				echo form_inshops($name, $descriptioninshops, $keywordsinshops, $type, '', "add");
			}
		}
	} elseif ($pageinfo=="adminmessage") {
	
		include $in."top.php"; 
		table_top ("100%", 0, "Профайл пользователя:", 1);
		
		
		$sql = "select * from messageusers where `user`='$cookiesuser' order by `check`, datepost desc, dateread desc limit 20;";
		$result = mysql_query($sql);
		if (mysql_num_rows($result)>0) {
			echo "<b>Сообщения от Администрации сайта</b>";
			
			echo "<br><table width=100% cellpadding=3 cellspacing=0 border=1 align=center>";
			echo "<tr><td class=tboard valign=top><b>Дата</b></td>
				<td class=tboard valign=top><b>текст сообщения</b></td></tr>";
			
			while($rows = mysql_fetch_array($result)) {
				
				echo "<tr><td class=tboard valign=top>".date('d-m-Y',$rows['datepost'])."</td>
				<td class=tboard valign=top>&nbsp;".$rows['message']."</td></tr>";
			
			}
			echo "</table>";
		}
	} 
*/
if ($action=="message_see"){
	$collectors_message = (int) request('collectors_message');
	$tpl['user']['message'] = $user_class->Message_show($collectors_message);
} elseif ($action=="message_delete"){
	$collectors_message = (int) request('collectors_message');
	echo $user_class->Message_delete($collectors_message);
}
				
$tpl['user']['messages'] = $user_class->getMyMessage();

?>                                                                                                                                
