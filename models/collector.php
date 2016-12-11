<?

class model_collectors extends Model_Base{	
	/*переменные
	$collectors, $user, $fio, $email, $url, $city, $city1, $phone, 
	$text, $adress, $interest
	
	
	enteradmin - переменная идентифицирующая администратора
	collectors - номер коллекционера в базе
	user - номер как пользователя
	fio - ФИО
	email - email коллекционера
	url 	- url коллекционера
	city 	- город
	phone 	- телефон
	text 	- описание
	adress 	- адрес
	photo 	- фотография
	interest1 - интерес 1
	interest2 - интерес 2
	…
	table_add - дополнительная таблица
	action - действие
	
	Функции
	Collectors_add - добавление коллекционера в базу
	Collectors_delete - удаление коллекционера из базы
	Collectors_edit - редактирование данных в базе
	Collectors_info - получение данных о коллекционере
	Collectors_showall - показываем всех коллекционеров, удовлетворяющих запросу
	Collectors_form - форма для заполнения
	*/
	/*
	var $enteradmin, $collectors, $user, $fio, $email, $url, $city, $phone, $text, $adress, $photo;
	var $interest;
	var $submit_value, $submit;
	var $str;
	
	function Collectors ($collectors, $user, $fio, $email, $url, $city, $city1, $phone, $text, $adress, $interest)
	{
		
		$this->script = $script;
		$this->interest_form = $interest_form;
		$this->pagenum = $pagenum;
		$this->city_array = $city_array;
		$this->skin_show_all = $skin_show_all;
		$this->skin_collectors_form = $skin_collectors_form;
		$this->enteradmin = $enteradmin;
		$this->collectors = $collectors;
		$this->user = $user;
		$this->fio = $fio;
		$this->email = $email;
		$this->url = $url;
		if ($city!=0)
		{
			$this->city = $city;
		} else {
			$this->city = $city1;
		}
		$this->phone = $phone;
		$this->text = $text;
		$this->adress = $adress;
		$this->interest = $interest;
		$this->table_main = $table_main;
		$this->table_add = $table_add;
		$this->action = $action;
		$this->submit = $submit;
		if ($this->action=="add" or !$this->action)
		{
			$this->submit_value="Добавить";
			$this->action = "add";
		} elseif ($this->action=="edit") {
			$this->submit_value="Редактировать";
		}
		$this->numpages = $numpages;
		$this->onpage = $onpage;
		$this->error = $error;
		$this->error_number = $error_number;
	}
	
	function Collectors_add ()
	{
		
		if (!$this->fio) return -1;
		if (!$this->email or !strpos($this->email, "@") or !strpos($this->email, ".") or strlen($this->email)<4) return -2;
		if (!$this->text) return -3;
		if (!$this->adress) return -4;
		if (!$this->user) return -5;
		//сначала смотрим иль есть такой пользователь
		$sql = "select user from ".$this->table_add." where user='".$this->user."';";
		$result = mysql_query($sql);
		$rows = mysql_fetch_array($result);
		if (!$rows[0]) return -6;
		
		//делаем update таблицы user
		$sql = "update ".$this->table_add." set 
		fio='".$this->strip_string($this->fio)."', email='".$this->strip_string($this->email)."', 
		url='".$this->strip_string($this->url)."', city='".$this->strip_string($this->city)."',
		phone='".$this->strip_string($this->phone)."', text='".$this->strip_string($this->text)."',
		adress='".$this->strip_string($this->adress)."' where user='".$this->user."';";
		$result = mysql_query($sql);
		if (mysql_error()) return -7;
		
		//делаем insert в таблицу collectors
		$sql = "insert into ".$this->table_main." (user, date, interest1, interest2, interest3
		, interest4, interest5, interest6, interest7, interest8, interest9, interest10) values 
		('".$this->user."', '".time()."', '".$this->strip_string($this->interest[1][1])."', 
		'".$this->strip_string($this->interest[2][1])."', '".$this->strip_string($this->interest[3][1])."',
		'".$this->strip_string($this->interest[4][1])."', '".$this->strip_string($this->interest[5][1])."',
		'".$this->strip_string($this->interest[6][1])."', '".$this->strip_string($this->interest[7][1])."',
		'".$this->strip_string($this->interest[8][1])."', '".$this->strip_string($this->interest[9][1])."',
		'".$this->strip_string($this->interest[10][1])."');";
//		echo $sql;
		$result = mysql_query($sql);
		if (mysql_error()) return -8;
		
		//при положительном результате
		return mysql_insert_id();
	}
	
	function Collectors_delete ()
	{
		
		if (!$this->collectors) return -9;
		$sql = "select collectors from ".$this->table_main." 
		where collectors='".$this->collectors."';";
		$result = mysql_query($sql);
		$rows = mysql_fetch_array($result);
		if (!$rows[0]) return -10;
		
		$sql = "delete from ".$this->table_main." where collectors='".$this->collectors."';";
		$result = mysql_query($sql);
		return 1;
	}
	
	function Collectors_edit ()
	{
		
		if (!$this->fio) return -1;
		if (!$this->email or !strpos($this->email, "@") or !strpos($this->email, ".") or strlen($this->email)<4) return -2;
		if (!$this->text) return -3;
		if (!$this->adress) return -4;
		if (!$this->user) return -5;			

		//сначала смотрим иль есть такой пользователь
		$sql = "select user from ".$this->table_add." where user='".$this->user."';";
		$result = mysql_query($sql);
		$rows = mysql_fetch_array($result);
		if (!$rows[0]) return -6;
		
		//photo checking
		$fileSize			= $_FILES['file']['size'];			
		  
		
		$fileUserNameToUpload 	= $_FILES['file']['name'];
		  
		
		$fileNameToUpload 		= $_FILES['file']['tmp_name'];
		  
		
		$fileNameOnServer 		= "tmp/";
		$fileName= "";
		if(NULL <> $fileNameToUpload && ($fileSize >= 200000 || $fileSize = 0)) return -111;
		elseif(NULL <> $fileNameToUpload && ($fileSize < 200000 || $fileSize > 0))
		{			
			$fileType= "";			 
			
			if	(ereg(".[gG][iI][fF]$", $fileUserNameToUpload) == 1) 		{$fileType = "gif";}
			else if(ereg(".[jJ][pP][eE][gG]$", $fileUserNameToUpload) == 1) {$fileType = "jpeg";}
			else if(ereg(".[jJ][pP][gG]$", $fileUserNameToUpload) == 1)		{$fileType = "jpg";}					
			else return -111;
			
			
			if($this->photo)
			{				
//				unlink("tmp/".$this->user.".$fileType");
				unlink("images/".$this->user.".$fileType");
			}			
			$fileNameOnServer .= $this->user.".$fileType";
			if (is_uploaded_file($fileNameToUpload))
			move_uploaded_file($fileNameToUpload, $fileNameOnServer);
			$fileName= $this->user.".$fileType";
			require("../advertise/resizeImageAndSave.php");
			resizeImageAndSave("tmp/", "images/", $fileName, 200, 200);							
		}		
		
		//делаем update таблицы user
		$sql = "update ".$this->table_add." set 
		fio='".$this->strip_string($this->fio)."', email='".$this->strip_string($this->email)."', 
		url='".$this->strip_string($this->url)."', city='".$this->strip_string($this->city)."',
		phone='".$this->strip_string($this->phone)."', text='".$this->strip_string($this->text)."',
		adress='".$this->strip_string($this->adress)."', ".($fileName?"photo='$fileName'":"")."where user='".$this->user."';";
		$result = mysql_query($sql);
//		echo $sql;
		if (mysql_error()) return -7;
		
		//делаем update в таблицу collectors
		$sql = "update ".$this->table_main." 
		set interest1 = '".$this->strip_string($this->interest[1][1])."',
		interest2 = '".$this->strip_string($this->interest[2][1])."', 
		interest3 = '".$this->strip_string($this->interest[3][1])."',
		interest4 = '".$this->strip_string($this->interest[4][1])."', 
		interest5 = '".$this->strip_string($this->interest[5][1])."', 
		interest6 = '".$this->strip_string($this->interest[6][1])."', 
		interest7 = '".$this->strip_string($this->interest[7][1])."', 
		interest8 = '".$this->strip_string($this->interest[8][1])."', 
		interest9 = '".$this->strip_string($this->interest[9][1])."', 
		interest10 = '".$this->strip_string($this->interest[10][1])."' 
		where collectors = '".$this->collectors."';";
		$result = mysql_query($sql);
		if (mysql_error()) return -8;
	}

	function Collectors_info ()
	{
		
		if (!$this->collectors) return -11;
		$sql = "select u.*, c.* from ".$this->table_main." as c left join ".$this->table_add." 
		as u on u.user=c.user where c.collectors='".$this->collectors."';";
		$result = mysql_query($sql);
		$rows = mysql_fetch_array($result);
		if (mysql_error()) return -12;
		if (!$rows[0]) return -13;
		//то что надо возвратить
		$info["collectors"] = $rows["collectors"];
		$info["user"] 		= $rows["user"];
		$info["fio"] 		= $rows["fio"];
		$info["email"] 		= $rows["email"];
		$info["url"] 		= $rows["url"];
		$info["city"] 		= $rows["city"];
		$info["phone"] 		= $rows["phone"];
		$info["text"] 		= $rows["text"];
		$info["adress"] 	= $rows["adress"];
		$info["photo"]		= $rows["photo"];
		for ($i=1; $i<=sizeof($this->interest); $i++)
		{
			$info["interest".$i] = $rows["interest".$i];
			$interest[$i][1] = $rows["interest".$i];
		}

		//и теперь данные в класс забиваем
		$this->collectors 	= $rows["collectors"];
		$this->user 		= $rows["user"];
		$this->fio 			= $rows["fio"];
		$this->email 		= $rows["email"];
		$this->url 			= $rows["url"];
		$this->city 		= $rows["city"];
		$this->phone 		= $rows["phone"];
		$this->text 		= $rows["text"];
		$this->adress 		= $rows["adress"];
		$this->interest 	= $interest;
		$this->photo		= $rows["photo"];
		return $info;
	}	

	function User_info ()
	{
		$sql = "select * from ".$this->table_add." where user='".$this->user."';";
		$result = mysql_query($sql);
		$rows = mysql_fetch_array($result);
		if (!$rows[0]) return -14;
		//то что надо возвратить
		$info["user"] 	= $rows["user"];
		$info["fio"] 	= $rows["fio"];
		$info["email"]	= $rows["email"];
		$info["url"] 	= $rows["url"];
		$info["city"] 	= $rows["city"];
		$info["phone"] 	= $rows["phone"];
		$info["text"] 	= $rows["text"];
		$info["adress"] = $rows["adress"];
		$info["photo"]	= $rows["photo"];

		//и теперь данные в класс забиваем
		$this->user 	= $rows["user"];
		$this->fio 		= $rows["fio"];
		$this->email 	= $rows["email"];
		$this->url 		= $rows["url"];
		$this->city 	= $rows["city"];
		$this->phone 	= $rows["phone"];
		$this->text 	= $rows["text"];
		$this->adress 	= $rows["adress"];
		$this->photo  	= $rows["photo"];
		
		return $info;
	}*/

	public function getData($params,$page=1, $items_for_page=5)
	{
		$select = $this->db->select()
                  ->from($this->table)
                  ->join(array('user'),"user.user=$this->table.user") 
                  ->limitPage($page, $items_for_page)                
                  ->order('user.star desc');       
        
		if($params['city']==-1){
			$select->where('length(user.city)>2');
		} elseif ($params['city']>=1){
			$select->where('user.city=?',$params['city']);
		} 
		
		if($params['interest']){
			$select->where("interest".$params['interest']."=1");
		}
		//echo $select->__toString();
		return $this->db->fetchAll($select);
	}
	
	public function addCollector($data){
		$select = $this->db->select()
                  ->from($this->table,array('collectors'))
                  ->where('user=?',(int)$data['user']);
        if($this->db->fetchOne($select)){
        	unset($data['date']);
        	return $this->updateCollector($data,$data['user']);
        }
        
		$this->db->insert($this->table,$data);
		
		return $this->db->lastInsertId($this->table);
	}
	
	public function updateCollector($data,$user=0){
		if(!(int) $user) return false;
		
		$this->db->update($this->table,$data,"user=$user");
		return true;
	}
	
	public function count_all($params)
	{
		 $select = $this->db->select()
                  ->from($this->table,array('count(*)'))
                  ->join(array('user'),"user.user=$this->table.user");
       
        
		if($params['city']==-1){
			$select->where('length(user.city)>2');
		} elseif ($params['city']>=1){
			$select->where('user.city=?',$params['city']);
		} 
		
		if($params['interest']){
			$select->where("interest".$params['interest']."=1");
		}
		
		return $this->db->fetchOne($select);
	}
	
	public function addMessage($data){
		$this->db->insert('collectors_message',$data);
		
		return $this->db->lastInsertId('collectors_message');
	}
	/*
	function Collectors_form ()
	{
		$form = str_replace("___collector___", $this->collectors, $this->skin_collectors_form);
		$form = str_replace("___user___", $this->user, $form);
		$form = str_replace("___fio___", $this->fio, $form);
		$form = str_replace("___photo___", $this->photo?"<tr bgcolor=#EBE4D4 valign=top><td class=\"tboard\"><b>Фотография </b></td><td><img src=\"./images/".$this->photo."\" width=\"200\" /></td></tr>":"", $form);
		$form = str_replace("___upload_photo___", "<tr bgcolor=#EBE4D4 valign=top><td class=\"tboard\"><b>Загрузить фотографию(JPG, GIF не более 200 кб)</b></td><td><input type=\"file\" name=\"file\" class=\"formtxt\" /></td></tr>", $form);
		
		if (ereg("^\-{0,1}[0-9]{1,}$", $this->city))
		{
			$class_city = "<select name=city class=formtxt>";
			for ($i=0; $i<count($this->city_array); $i++)
			{
				if ($i != $this->city)
				{
					$class_city .= "<option value=$i>".$this->city_array[$i];
				} else {
					$class_city .= "<option value=$i selected>".$this->city_array[$i];
				}
			}
			$class_city .= "</select>";
			$form = str_replace("___city___", $class_city, $form);
			$form = str_replace("___city1___", "", $form);
		} else {
			$class_city = "<select name=city class=formtxt>";
			for ($i=0; $i<count($this->city_array); $i++)
			{
				if ($i != $this->city)
				{
					$class_city .= "<option value=$i>".$this->city_array[$i];
				} else {
					$class_city .= "<option value=$i selected>".$this->city_array[$i];
				}
			}
			$class_city .= "</select>";
			$form = str_replace("___city___", $class_city, $form);
			$form = str_replace("___city1___", $this->city, $form);
		}
		$form = str_replace("___phone___", $this->phone, $form);
		$form = str_replace("___email___", $this->email, $form);
		$form = str_replace("___url___", $this->url, $form);
		$form = str_replace("___text___", $this->text, $form);
		$form = str_replace("___adress___", $this->adress, $form);
		$form = str_replace("___submit_value___", $this->submit_value, $form);
		if ($this->error_number)
		{
			$form = str_replace("___error___", $this->error["collectors"][$this->error_number], $form);
		} else {
			$form = str_replace("___error___", "", $form);
		}
		for ($i=1; $i<=sizeof($this->interest); $i++)
		{
			if ($this->interest[$i][1]==1)
			{
				$form = str_replace("___value_interest".$i."___", "checked", $form);
			} else {
				$form = str_replace("___value_interest".$i."___", "", $form);
			}
		}
		$form = str_replace("___action___", $this->action, $form);
		return $form;
	}
	
	function strip_string ($str) {
		$str = trim($str);
		$str = strip_tags($str);
		$str = str_replace("'", "`", $str);
		$str = str_replace("\\", "\\\\", $str);
		return $str;
	}*/
	
}

?>