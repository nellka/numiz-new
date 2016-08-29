<?
try{
require_once($cfg['path'].'/models/catalognew.php');
require_once($cfg['path'].'/helpers/imageMini.php');
require_once($cfg['path'].'/configs/config_catalognew.php');
$catalognew_class = new model_catalognew($db_class);

$catalog = (int) request('catalog');
$group = (int) request('group');
$nominal_id = request('nominal_id');
$condition = request('condition');
$year_p = request('year_p');
$metal = request('metal');

$amount = request('amount');
$reverselegend = request('reverselegend');      
$herd = request('herd');
$averslegend = request('averslegend');
$mint = request('int');
$designer = request('designer');
$officialdate = request('officialdate');

$probe = request('probe');
$procent = request('procent');
$amount = request('amount');
$size = request('size');
$thick = request('thick');
$weight = request('weight');
$details = request('details');
$submitaftererror = request('submitaftererror');
$errors = Array();
$tpl['addcoinsExist'] = Array();

if (!$tpl['user']['user_id']){
	$tpl['catalognew']['error']['no_auth'] = true;
} else {     
    if($catalog){
        $data_base = $catalognew_class->getItem($catalog);
        if ($data_base["image_small_url"]) $image_small_url = $data_base["image_small_url"];
	    if ($data_base["image_big_url"]) $image_big_url = $data_base["image_big_url"];   
    	
    	//выбираем пероды чеканки
        if($_SERVER['REQUEST_METHOD']!='POST'){
            $group  = $data_base['group']	;
            
            $metal  = $data_base['metal_id']	;
            $nominal_id = $data_base['nominal_id'];
            $name = $data_base['name'];
            $condition = $data_base['condition'];
            $amount = $data_base['amount'];
            $reverselegend = $data_base['reverselegend'];        
            $herd = $data_base["herd"];
        	$averslegend = $data_base["averslegend"];
        	$mint = $data_base["mint"];
        	$designer = $data_base["designer"];
        	$officialdate = ($data_base["officialdate"]>0)?$data["officialdate"]:'';
        	
        	$probe = $data_base["probe"];
        	$procent = $data_base["procent"];
        	$amount = $data_base["amount"];
        	$size = $data_base["size"];
        	$thick = $data_base["thick"];
        	$weight = $data_base["weight"];
        	$details = $data_base["details"];       	
        	
        	$result_year = $result_year = $catalognew_class->getCatalogyear($catalog);
        	
        	$year_p = 1;
        	foreach ($result_year as $rows_year){
        		${"yearstart".$year_p} = $rows_year["yearstart"];
        		${"yearend".$year_p} = $rows_year["yearend"];    		
        		$year_p++;
        	}
        	
        	$year_p--;
        	
        	//тематики
    	
        	$theme = $rows["theme"];
        	$strtheme = decbin($rows["theme"]);
        	$strthemelen = strlen($strtheme);
        	$chars = preg_split('//', $strtheme, -1, PREG_SPLIT_NO_EMPTY);
        	for ($k=0; $k<$strthemelen; $k++)
        	{
        		if ($chars[$k]==1)
        			${"theme".($strthemelen-1-$k)} = "on";
        	}     
        }	
    } elseif($_SERVER['REQUEST_METHOD']!='POST'){
        $year_p = 1;
        $yearstart1 = '';
        $yearend1 = '';   
        $submitaftererror = 0; 
    }

    //получаем страны
    $sql = "select group.group from `group` where type='shopcoins' and `group` not in (667,937,983,997,1014,1015,1062,1063,1097,1106);";
    $result = $catalognew_class->getDataSql($sql);
    $Group = array();  
    $GroupArray = array(); 
    $group407 = 0;
    
    foreach ($result as $rows) {    	
    	$Group[] = $rows["group"];
    }

    $sql = "select groupparent,name,group.group from `group` where `group` ".(sizeof($Group)>0?"in (".implode(",", $Group).")":" = 0 ")." order by name;";
    
    $result = $catalognew_class->getDataSql($sql);
   
    foreach ($result as $rows) {
    	if ($rows["groupparent"]>0) {
    		$Group[] = $rows["groupparent"];
    		
    		if (!is_array($GroupArray[$rows["groupparent"]]))
    			$GroupArray[$rows["groupparent"]] = Array();
    	
    		$GroupArray[$rows["groupparent"]][$rows["group"]] = $rows["name"];
    	}
    }    

	$sql = "select group.group,group.name from `group` where groupparent='0' ".(sizeof($Group)>0?"and `group`.`group` in (".implode(",",$Group).")":" and group.group = 0 ")."order by group.name;";
    
	$tpl['groups_parent'] = array();
	
    $i = 1;
	foreach ($catalognew_class->getDataSql($sql) as $row){
	    if($row['group']==407){
	       
	        $tpl['groups_parent'][0] = $row;
	        continue;
	    }
	
	    $tpl['groups_parent'][$i] = $row;
	    $i++;
	}

	ksort($tpl['groups_parent']);
		
	//номиналы
	$tpl['nominals'] = array();
	if ($group){
        $sql = "select distinct(nominal_id),name from catalognew where `group`='$group' order by name;";
        $tpl['nominals'] =	$catalognew_class->getDataSql($sql);      
    }
      	
   	$sql = "select * from `metal` order by name;";
   	
    $tpl['metals']  = $catalognew_class->getDataSql($sql);      
        
    if ($_SERVER['REQUEST_METHOD']=='POST')  {     
        for ($i=1; $i<=$year_p; $i++){
    		${"yearstart".$i} = (int)request("yearstart".$i);
    		
    		if(${"yearstart".$i}){    		
                ${"yearend".$i} =  (int)request("yearend".$i);   
                if (${"yearend".$i}){
        			if (${"yearstart".$i} > ${"yearend".$i}) $errors[] = "Неправильно указан период чеканки №".$i;
                }           
            }   
        } 	
    	//тематики
    	$themesum = 0;
        	
    	for($i=1; $i<=sizeof($ThemeArray); $i++){
    	    $th = request("theme".$i);        	
    	    if($th) {
    	        ${"theme".$i} = $th;
    	        $themesum += pow(2,$i);
    	    }
    	}        	
    	
    	
    	if (!$group) $errors[] = "Выберите страну";
    	
    	if (!$nominal_id) {
    	    $errors[] = "Укажите номинал";
    	} else {
    	    $name = $catalognew_class->getOneSql("select name from shopcoins_name where id=$nominal_id");      
    	}
    	
    	if (empty($_FILES['image']['tmp_name'])&&!$catalog) $errors[] = "Выберите изображение";
    	
        if ($catalog&&!$errors) {               
        	//теперь копируем и уменьшаем наше изображение до нужных нам размеров
        	if (!empty($_FILES['image']['tmp_name'])) {
        		$max_size = 500000; // максим. размер в БАЙТАХ
        		
        		$tmp_image = getimagesize($_FILES['image']['tmp_name'] );
        	
        		if  ($_FILES['image']['size'] > $max_size) { 
        			$error[] = "Неверный размер! Файл $image_name НЕ загружен."; 
        		} else {       		    
        			
        			$last_transaction = $catalognew_class->getCatalogtransaction();
        			$imagemaxtransaction = ($last_transaction+1).".jpg";
        			$imageid = ($last_transaction + 1)."_".rand(1,10000);        			
        			$tmp = explode(".", strrev(strtolower($_FILES['image']['name'])));
        			
        			$type = strrev($tmp[0]);

        			if ((strtolower($type)=="jpg" || strtolower($type=="gif") || strtolower($type)=="jpeg") && $tmp_image[0] && $tmp_image[1] && $tmp_image[2]>=1 && $tmp_image[2]<=2) {
        				$res = copy($_FILES['image']['tmp_name'], $cfg['oldpath']."/catalognew/files/".$imageid.".".$type);
        				unlink($_FILES['image']['tmp_name']);
        				$image = $imageid.".".$type;        					
        				//теперь делаем обработку для 
        				
                        $file_data = imageMini::SaveSmallImage ($cfg['oldpath']."catalognew/files/", $cfg['oldpath']."catalognew/i/", $image, 606, 606);
                        $catalognew_class->saveImageData($file_data);      
                        $file_data = imageMini::SaveSmallImage ($cfg['oldpath']."catalognew/files/", $cfg['oldpath']."catalognew/is/", $image, 210, 210);
                        $catalognew_class->saveImageData($file_data);    
                        $file_data = imageMini::SaveSmallImage ($cfg['oldpath']."catalognew/files/", $cfg['oldpath']."catalognew/iss/", $image, 80, 80);
                        $catalognew_class->saveImageData($file_data);    
			
        			} else {
        				$errors[] = "Неправильный тип изображения";
        			}
        		}
        	}
        	
           
            //при изменении монеты надо записать в талблицу
            if (!sizeof($errors)){         
               
                //теперь, что же мы будем изменять
            	$ChangeField = Array();
            	if ($data_base['group'] != $group) $ChangeField[] = "group";
            	if ($data_base['name'] != $name) $ChangeField[] = "name";     
            	//if ($data_base['nominal_id'] != $nominal_id) $ChangeField[] = "nominal_id";       	
            	if ($image) $ChangeField[] = "image_big_url";
            	if ($data_base['metal'] != $metal) $ChangeField[] = "metal";
            	if ($data_base['probe'] != $probe) $ChangeField[] = "probe";
            	if ($data_base['procent'] != $procent) $ChangeField[] = "procent";
            	if ($data_base['amount'] != $amount) $ChangeField[] = "amount";
            	if ($data_base['size'] != $size) $ChangeField[] = "size";
            	if ($data_base['thick'] != $thick) $ChangeField[] = "thick";
            	if ($data_base['weight'] != $weight) $ChangeField[] = "weight";
            	
            	$theme = $themesum;
            	
            	if ($data_base['theme'] != $theme) $ChangeField[] = "theme";        	
            	if ($data_base['condition'] != $condition) $ChangeField[] = "condition";
            	if ($data_base['herd'] != $herd) $ChangeField[] = "herd";
            	if ($data_base['averslegend'] != $averslegend) $ChangeField[] = "averslegend";
            	if ($data_base['reverselegend ']!= $reverselegend) $ChangeField[] = "reverselegend";
            	if ($data_base['mint'] != $mint) $ChangeField[] = "mint";
            	if ($data_base['designer'] != $designer) $ChangeField[] = "designer";
            	if ($data_base['officialdate'] != $officialdate) $ChangeField[] = "officialdate";
            	if ($data_base['details'] != $details) $ChangeField[] = "details";
            	
            	$result_year = $result_year = $catalognew_class->getCatalogyear($catalog);
        	
            	$i = 1;
            	
            	foreach ($result_year as $rows_year){
            		$data_base["yearstart".$i] = $rows_year["yearstart"];
            		
            		if($data_base["yearstart".$i]!=${"yearstart".$i}) {
            		    $ChangeField[] = "yearstart";
            		}
            		
            		if($data_base["yearstart".$i]){
            		     $data_base["yearend".$i] = (int)$rows_year["yearend"];              		  
            		     if($data_base["yearend".$i]!=(int)${"yearend".$i}) {
                		    $ChangeField[] = "yearstart";
                		}
            		}  		
            		$i++;
            	}  
            	
            	$data_base['year_p'] = $i-1;
            	
            	if($data_base['year_p']!=$year_p){
            	    $ChangeField[] = "yearstart";
            	}
           /*
            	
            	$yearstartArray = Array();
            	
            	for ($i=1; $i<=100; $i++)
            	{
            		if (${"yearstart".$i} and ${"yearend".$i})
            		{
            			if (${"yearend".$i} > ${"yearstart".$i})
            				$yearstartArray[] = ${"yearstart".$i}."-".${"yearend".$i};
            			else
            				$yearstartArray[] = ${"yearstart".$i};
            		}
            	}
            	
            	$yearstart = implode(",",$yearstartArray);
            	
            	
            	$oldyearstartArray = Array();
            	
            	for ($i=1; $i<=100; $i++)
            	{
            		if (${"oldyearstart".$i} and ${"oldyearend".$i})
            		{
            			if (${"oldyearend".$i} > ${"oldyearstart".$i})
            				$oldyearstartArray[] = ${"oldyearstart".$i}."-".${"oldyearend".$i};
            			else
            				$oldyearstartArray[] = ${"oldyearstart".$i};
            		}
            	}
            	
            	$oldyearstart = implode(",",$oldyearstartArray);
            	//$oldyearstart = $oldyearstart1."-".$oldyearend1.",".$oldyearstart2."-".$oldyearend2.",".$oldyearstart3."-".$oldyearend3.",".$oldyearstart4."-".$oldyearend4;
            	*/            	
            	if (sizeof($ChangeField)>0) {
            	    
            		//добавляем в таблицу catalogtransaction
            		//$transaction = $catalognew_class->addCatalogtransaction($catalog,$tpl['user']['user_id']);
            		
            		foreach ($ChangeField as $key=>$value) 	{            			
            			$catalognew_class->addCataloghistoryField($transaction,$value,${$value},$data_base[$value]);            			
            		}
            		
            		$errors[] = "Изменения данных о монете были успешно записаны. Эта информация доступна для других пользователей. Спасибо.";            		
            	}
            }
        } elseif (!sizeof($errors) and !$submitaftererror){
           
    	    //проверка, что такая существует проверка идет как по стране, номиналу, году - только при добавлении
    		if ($yearstart1 or $yearend1 or $yearstart2 or $yearend2 or $yearstart3 or $yearend3 or $yearstart4 or $yearend4){
    			$sql = "select catalognew.* from catalogyear 
    			left join catalognew on catalogyear.catalog=catalognew.catalog
    			where  catalognew.group='".$group."'
    			and catalognew.nominal_id='".$nominal_id."'";
    			
    			$sqlyear = Array();
    			for ($i=1; $i<=100; $i++)
    			{
    				if (intval(${"yearstart".$i}) and !intval(${"yearend".$i}))
    					$sqlyear[] ="(catalogyear.yearstart > '".${"yearstart".$i}."' and catalogyear.yearend<'".${"yearstart".$i}."')";
    			
    				if (intval(${"yearstart".$i}) and intval(${"yearend".$i}) and (${"yearstart".$i} < ${"yearend".$i}))
    					$sqlyear[] ="(
    					(catalogyear.yearstart > '".${"yearstart".$i}."' and catalogyear.yearstart < '".${"yearend".$i}."')
    					or (catalogyear.yearstart < '".${"yearstart".$i}."' and catalogyear.yearend > '".${"yearend".$i}."')
    					or (catalogyear.yearstart < '".${"yearstart".$i}."' and catalogyear.yearend < '".${"yearend".$i}."')
    					)";
    			}
    			
    			if (sizeof($sqlyear)) {
    				$sql .= "and (".implode(" or ", $sqlyear).") ";
    			}    			
    			$sql .= " group by catalog";
    			
    		}  else  {
    			$sql = "select catalognew.*, group.name as gname, metal.name as mname
    			from catalognew, `group`, metal
    			where catalognew.`group`='".intval($group)."'
    			and catalognew.nominal_id='".$nominal_id."'
    			and catalognew.group=group.group  and catalognew.metal = metal.metal;";
    		}
    		
    		echo $sql;
    		$tpl['addcoinsExist'] = $catalognew_class->getDataSql($sql);
    		       
    		//есть подобные
    		if ($tpl['addcoinsExist']) {
    		    $submitaftererror = 1;
    			$errors[] = "<b>Уважаемый пользователь. Ниже представлены монета(ы), которая возможно соответствует Вашей монете, которую Вы хотите добавить в каталог. 
    			<br>Если такова присутствует в списке, пожайлуста,воздержитесь от создания новой записи в каталоге.</b>
    			<p align=center><input type=submit name=submitaftererror class=formtxt value='Нет, такой в этом списке не существует. Добавить мою монету'></p>";
    			
    			
    		}

        	
        	//теперь копируем и уменьшаем наше изображение до нужных нам размеров
        	if (!empty($_FILES['image']['tmp_name']) and !sizeof($error)) 	{
        		$max_size = 500000;   // максим. размер в БАЙТАХ        	        		
        		
        		if  ($_FILES['image']['size'] > $max_size) { 
        			$error[] = "Неверный размер! Файл $image_name НЕ загружен."; 
        		} else {       	        			
        			$last_transaction = $catalognew_class->getCatalogtransaction();
        			$imagemaxtransaction = ($last_transaction+1).".jpg";
        			$imageid = ($last_transaction + 1)."_".rand(1,10000);        			
        			$tmp = explode(".", strrev(strtolower($_FILES['image']['name'])));
        			
        			$type = strrev($tmp[0]);

        			if ($type=="jpg" || $type=="gif" || $type=="png" || $type=="pjpg" || $type=="jpeg"){
        			    $res = copy($_FILES['image']['tmp_name'], $cfg['oldpath']."/catalognew/files/".$imageid.".".$type);
        				unlink($_FILES['image']['tmp_name']);
        				$image = $imageid.".".$type;        					
        				//теперь делаем обработку для 
        				
                        $file_data = imageMini::SaveSmallImage ($cfg['oldpath']."catalognew/files/", $cfg['oldpath']."catalognew/i/", $image, 606, 606);
                        $catalognew_class->saveImageData($file_data);      
                        $file_data = imageMini::SaveSmallImage ($cfg['oldpath']."catalognew/files/", $cfg['oldpath']."catalognew/is/", $image, 210, 210);
                        $catalognew_class->saveImageData($file_data);    
                        $file_data = imageMini::SaveSmallImage ($cfg['oldpath']."catalognew/files/", $cfg['oldpath']."catalognew/iss/", $image, 80, 80);
                        $catalognew_class->saveImageData($file_data);    
			
        			} else {
        				$errors[] = "Неправильный тип изображения";
        			}
        		}
        	}
        	
        	if (!sizeof($errors)) {
        	    die('делаем insert');
        	
        		$name = strip_string ($name);
        		$probe = strip_string ($probe);
        		$procent = strip_string ($procent);
        		$amount = strip_string ($amount);
        		$size = strip_string ($size);
        		$thick = strip_string ($thick);
        		$weight = strip_string ($weight);
        		
        		//теперь тематики
        		$themesum = 0;
        		for($i=1; $i<=sizeof($ThemeArray); $i++)
        		{
        			if (${"theme".$i})
        				$themesum += pow(2,$i);
        		}
        		
        		$condition = strip_string ($condition);
        		$herd = strip_string ($herd);
        		$averslegend = strip_string ($averslegend);
        		$reverselegend = strip_string ($reverselegend);
        		$mint = strip_string ($mint);
        		$designer = strip_string ($designer);
        		$officialdate = strip_string ($officialdate);
        		$details = strip_string ($details);
        		
        		for ($i=0; $i<=100; $i++)
        		{
        			if (!$yearstart and intval(${"yearstart".$i}))
        				$yearstart = intval(${"yearstart".$i});
        		}
        		
        		
        		$image_small_url = "is/".$imagemaxtransaction;
        		$image_big_url = "i/".$imagemaxtransaction;
        		
        		//добавляем в каталог
        		$sql = "insert into catalognew
        		(catalog, `group`, name, 
        		yearstart, metal, probe,
        		procent, amount, size,
        		thick, weight, theme,
        		`condition`, herd, averslegend,
        		reverselegend, 
        		mint, designer, officialdate, image_small_url, 
        		image_big_url, details, dateinsert, 
        		user, agreement)
        		values
        		('0', '$group', '$name', 
        		'$yearstart', '".($metal>0?$metal:1)."', '$probe',
        		'$procent', '$amount', '$size',
        		'$thick', '$weight', '$themesum',
        		'$condition', '$herd', '$averslegend',
        		'$reverselegend', 
        		'$mint', '$designer', '$officialdate', '$image_small_url', 
        		'$image_big_url', '$details', '".time()."', 
        		'$user', '0');";
        		$result = mysql_query($sql);
        		$catalog = mysql_insert_id();
        		$submitaftererror = 0;
        		
        		//добавляем года
        		for ($i=1; $i<100; $i++)
        		{
        			if (${"yearstart".$i})
        			{
        				$sql = "insert catalogyear
        				(catalog, yearstart, yearend)
        				values
        				('$catalog', '".${"yearstart".$i}."', '".${"yearend".$i}."');";
        				$result = mysql_query($sql);
        			}
        		}
        		
        		//добавляем в таблицу catalogtransaction
        		$sql = "insert into catalogtransaction
        		(transaction, catalog, user, date)
        		values
        		('0', '$catalog', '$user', '".time()."');";
        		$result = mysql_query($sql);
        		$transaction = mysql_insert_id();
        		
        		//теперь добавляем в cataloghistory
        		$sql = "insert into cataloghistory
        		(cataloghistory, transaction, date,
        		agreementdate, field)
        		values
        		('0', '$transaction', '".time()."',
        		'0', 'all');";
        		$result = mysql_query($sql);
        
        		echo "<script>function Form1(str){}</script>
        		<br><center><b class=tboard>Данные о монете были успешно добавлены. Эта информация доступна для просмотра. Спасибо.
        		<br><a href=$script>Добавить монеты</a>
        		<br><a href=# onclick='javascript:window.opener.location=\"index.php?page=show&catalog=".$catalog."\";window.close();'>Закрыть окно</a></b></center>
        		</body>
        		</html>";
        		exit;
        	}

        }
    }
}
} catch (Exception $e){
    var_dump($e->getMessage());
}