<?php

/*Класс для получения информации о пользователе*/
class keywords extends Model_Base 
{		
   protected $db;
   static $CoinsText = array(0=>array("title"=>"Купить <<metal_prilagatelnoe>> <<<materialtype_vinitelniy>>> недорого <<<group_roditelniy>>> <<<name>>> <<<year_god>>>",
						"details"=>"<<<NOVELTY>>> Цена <<<price>>> рублей. Закажите сегодня – доставка по Москве завтра. Интернет магазин Клуб Нумизмат. <<<details>>> <<<condition2_condition>>> <<<series>>>. <<<numizmatik_theme>>>"),
							  1=>array("title"=>"<<<NOVELTY>>> <<<materialtype_IMENITELNIY>>> <<<group_roditelniy>>> <<<name>>> <<<year>>> <<<from_metal_roditelnom>>>. Цена всего <<<price>>> рублей.",
							  		  "details"=>"<<<details>>> Доставим почтой в любой уголок России наложенным платежом. Магазин в центре Москвы. <<<condition2_condition>>> <<<theme>>>. <<<numizmatik_series>>> Купить в один клик. Розница и опт."),
							   2=>array("title"=>"Продажа <<<materialtype_roditelnom>>> <<<group_roditelniy>>> <<<name>>> от Интернет магазина Клуб Нумизмат. Розница и опт. ",
							   "details"=>"<<<NOVELTY>>> Покупка <<<group_roditelniy>>> <<<materialtype_roditelnom>>> <<<name>>> <<<year_god>>> из <<<metal_roditelnom>>> за <<<price>>> рублей. <<<details>>> Закажите уже сегодня. <<<condition_condition>>> <<<t_theme>>> <<<materialtype_series>>>"),
							   3=>array("title"=>"Интернет магазин Клуб Нумизмат. Купить <<<materialtype_vinitelniy>>> недорого <<<name>>> <<<group_roditelniy>>> из <<<metal_roditelnom>>> <<<year_god>>>.",
							   "details"=>"<<<NOVELTY>>> Стоимость всего <<<price>>> рублей. Магазин в Москве. Доставка почтой по РФ наложенным платежом. <<<condition2_condition>>> <<<series>>>
<<<details>>> <<<t_theme>>>"),
							   4=>array("title"=>"Заказать дешево <<<materialtype_vinitelniy>>> <<<group>>> <<<name>>> <<<year_god>>> <<<from_metal_roditelnom>>>. Возможно оптом.",
							   "details"=>"<<<NOVELTY>>> В магазине Клуб Нумизмат с доставкой по Москве. Стоимость <<<price>>> рублей. <<<condition2_condition>>> <<<series>>> <<<details>>> <<<theme>>>."),
							   5=>array("title"=>"<<<NOVELTY>>> <<metal_prilagatelnoe>> <<<materialtype_IMENITELNIY>>> <<<name>>> <<<group>>> <<<year_god>>>. Продажа <<<materialtype_mnowestvennoe>>> в розницу и оптом.",
							   "details"=>"В подарок нумизмату. Цена <<<price>>> рублей. Закажи сегодня. Есть другие дешевые <<<materialtype_mnowestvennoe>>> в коллекцию. <<<details>>> <<<theme>>>. <<<condition_condition>>> <<<from_series>>>"),
							   6=>array("title"=>"<<<NOVELTY>>> Купить <<<materialtype_vinitelniy>>> <<<group_roditelniy>>> <<<name>>> <<<from_metal_roditelnom>>> <<<year_god>>>.",
							   "details"=>"<<<details>>> <<<condition2_condition>>> Стоимость <<<price>>> рубля. Закажи сегодня - доставка по РФ наложенным платежом. Магазин в центре Москвы. <<<materialtype_theme>>> <<<series>>> Возможен опт."),
							   7=>array("title"=>"<<<series>>> Купить <<<name>>> <<<year_god>>> <<<group>>>. <<<details_20_do_tochki>>>. В розницу и оптом.",
							   "details"=>"<<<NOVELTY>>> <<<condition_ubileyniy>>> <<<materialtype_IMENITELNIY>>> <<<in_condition>>> стоимостью <<<price>>> рублей. Купить в один клик. Пополняйте свою коллекцию вместе с нами. <<<metal>>> <<<theme>>>."),
							   8=>array("title"=>"Магазин монет и банкнот от Клуба Нумизмат. <<<NOVELTY_->>> <<<materialtype_IMENITELNIY>>> <<<group_roditelniy>>> <<<name>>> <<<year_god>>>. ",
							   "details"=>"<<<from_metal_roditelnom>>>. Цена всего <<<price>>> рублей. <<<nd_details>>> Отвезем на почту во вторник, доставим по Москве завтра. Оптом и в розницу. <<<series>>> <<<ts_theme>>>"),
							   9=>array("title"=>"Заказать в один клик <<metal_prilagatelnoe>> <<<materialtype_vinitelniy>>> <<<name>>> <<<group_roditelniy>>>. <<<NOVELTY>>>",
							   "details"=>"<<<year_god>>> по цене <<<price>>> рублей с доставкой по России или самовывозом из центра Москвы. Купить сейчас. <<<condition_condition>>> <<<details>>> <<<from_theme>>> <<<series>>> Магазин 2 минуты от метро Чеховская."),
							   10=>array("title"=>"Новинка. Купить <<<materialtype_vinitelniy>>> для истинного нумизмата. <<<group>>>.",
							   "details"=>"<<<name>>>. <<<from_metal_roditelnom>>>. Стоимостью всего <<<price>>> рублей. <<<year_g>>>. В <<<condition2>>> состоянии. Работаем с душой для Вас. Клуб Нумизмат. <<<details>>> <<<series>>> <<<theme>>>"));

	
							   
	static $materialtyps = array(
					     1=>array("mnowestvennoe"=>"Монеты",
								"imenitelniy"=>"Монета",
								"roditelnom"=>"монеты",
								"vinitelniy"=>"монету",
								"gender"=>"women"),
                         8=>array("mnowestvennoe"=>"Монеты",
								"imenitelniy"=>"Монета",
								"roditelnom"=>"монеты",
								"vinitelniy"=>"монету",
								"gender"=>"women"),
                         6=>array(
		                         "mnowestvennoe"=>"Цветные монеты" ,
		                         "imenitelniy"=>"Цветная монета" ,
		                         "roditelnom"=>"цветной монеты",
		                         "vinitelniy"=>"цветную монету",
								 "gender"=>"women"),
                         10=>array("mnowestvennoe"=>"Нотгельды",
		                         "imenitelniy"=>"Нотгельд",
		                         "roditelnom"=>"нотгельда",
		                         "vinitelniy"=>"нотгельда",
								 "gender"=>"men"),
                         7=>array("mnowestvennoe"=>"Наборы монет",
		                         "imenitelniy"=>"Набор монет",
		                         "roditelnom"=>"набора монет",
		                         "vinitelniy"=>"набор монет",
								 "gender"=>"men"
                         ),
                         9=>array("mnowestvennoe"=>"Лоты монет для начинающих нумизматов",
		                         "imenitelniy"=>"Лот монет для начинающих нумизматов",
		                         "roditelnom"=>"лота монет для начинающих нумизматов",
		                         "vinitelniy"=>"лот монет для начинающих нумизматов",
								 "gender"=>"men"
                         ),
                         2=>array("mnowestvennoe"=>"Монеты",
								"imenitelniy"=>"Монета",
								"roditelnom"=>"монеты",
								"vinitelniy"=>"монету",
								"gender"=>"women"),
                         4=>array("mnowestvennoe"=>"Подарочные наборы",
	                         "imenitelniy"=>"Подарочный набор",
	                         "roditelnom"=>"подарочного набора",
	                         "vinitelniy"=>"подарочный набор",
							 "gender"=>"men"),
                         5=>array("mnowestvennoe"=>"Книги о монетах",
	                         "imenitelniy"=>"Книги о монетах",
	                         "roditelnom"=>"книг о монетах",
	                         "vinitelniy"=>"книги о монетах",
							 "gender"=>"men"),
                         11=>array("mnowestvennoe"=>"Монеты",
								"imenitelniy"=>"Монета",
								"roditelnom"=>"монеты",
								"vinitelniy"=>"монету",
								"gender"=>"women")); 
							 
	 static $condition2 = array(""=>"",
	                           "Proof"=>"Зеркальное",
	                           "Proof-"=> "Зеркальное", 
	                           "UNC" => "Идеальное", 
	                           "UNC-"=>"почти идеальное",
	                           "XF" => "почти идеальное", 
	                           "XF-"=>"хорошее",
	                           "VF"=>"хорошее");
	static $ThemeArray = Array (
			13	=> 	"Авиация",
			4	=> 	"Бракосочетание, коронация",
			16	=> 	"Выдающиеся личности",
			6	=> 	"География",
			23	=> 	"Евросоюз",
			2	=> 	"Животные",
			18	=> 	"Знаки зодиака",
			5	=> 	"История",
			9	=> 	"Корабли, лодки",
			3	=> 	"Королева-мать",
			15	=> 	"Космос",
			24	=> 	"Миллениум",
			10	=> 	"Неправильная форма",
			1	=> 	"Обращение",
			20	=> 	"Олимпийские игры, спорт",
			25	=> 	"ООН",
			7	=> 	"Памятники архитектуры",
			11	=> 	"Позолота",
			14	=> 	"Редкости на рынке",
			17	=> 	"Транспорт",
			8	=> 	"ФАО",
			19	=> 	"Флора",
			21	=> 	"Футбол",
			22	=> 	"Хоккей",
			12	=> 	"Цветные"
			);				  
    function __construct($db){
        $db['driver_options']  = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8"');
		$this->db = Zend_Db::factory('PDO_MYSQL', $db);
	 	$this->db->query("SET names 'utf8'");	 	
	}   
	
	protected  function getTemplate(){
		$key = rand(0,10);
		return self::$CoinsText[$key];
	}
	
	protected function prepareText($text,$row,$group,$metal,$condition,$series){
		$text = str_replace("<<<name>>>",trim($row['name']),$text);		
		$text = str_replace("<<<price>>>",round(trim($row['price'])),$text);
		
		if($row['year']){
		    $text = str_replace("<<<year_god>>>",$row['year']." года",$text);
		    $text = str_replace("<<<year>>>",trim($row['year']),$text);
		    $text = str_replace("<<<year_g>>>",trim($row['year'])." год.",$text);		    
		} else {
		    $text = str_replace("<<<year_god>>>","",$text);
		    $text = str_replace("<<<year>>>","",$text);
		    $text = str_replace("<<<year_g>>>","",$text);	
		}
		//{$novelity} = если dateinsert не прошло более 6 месяцев или же novelity=1
		if(($row['dateinsert']>(time()-6*30*3600*24))||$row['novelty']){
			str_replace("<<<NOVELTY>>>",'Новинка.',$text);
			$text = str_replace("<<<NOVELTY_->>>",'Новинка - ',$text);
		} else {
		    $text = str_replace("<<<NOVELTY>>>",'',$text);
		    $text = str_replace("<<<NOVELTY_->>>",'',$text);		     
		}
		
		$text = str_replace("<<<NOVELTY>>>",'',$text);
		if(trim($row['details'])){
            $text = str_replace("<<<details>>>",$row['details'],$text);	
            $text = str_replace("<<<nd_details>>>","Нумизматическое описание ".$row['details'],$text);
		} else {
		    $text = str_replace("<<<details>>>","",$text);	
		    $text = str_replace("<<<nd_details>>>","",$text);		    
		}		
		
		$text = str_replace("<<<metal>>>",$metal['name']?("Металл – ".$metal['name']."."):"",$text);		
		$text = str_replace("<<<group>>>",$group['name'],$text);
		$text = str_replace("<<<group_roditelniy>>>",$group["name_roditelnom_padege"],$text);
		if($condition['name']){
		    $text = str_replace("<<<condition>>>",$condition['name'],$text);
		    $text = str_replace("<<<in_condition>>>","в состоянии ". $condition['name'],$text);
		    $text = str_replace("<<<condition_condition>>>","Состояние ".$condition['name'].".",$text);
		} else {
		    $text = str_replace("<<<condition>>>","",$text);
		    $text = str_replace("<<<condition_condition>>>","",$text);
		    $text = str_replace("<<<in_condition>>>","",$text);
		}
		
		if(self::$condition2[$condition['name']]){
		    $text = str_replace("<<<condition2>>>",self::$condition2[$condition['name']],$text);
		    $text = str_replace("<<<condition2_condition>>>","Состояние ".self::$condition2[$condition['name']].".",$text);	
		} else {
		    $text = str_replace("<<<condition2>>>","",$text);
		    $text = str_replace("<<<condition2_condition>>>","",$text);	
		}
		
		
		
		$gender = self::$materialtyps[$row['materialtype']]["gender"];
			
		if(in_array($condition['name'],array("Proof", "Proof-", "UNC", "UNC-"))){
			$text = str_replace("<<<condition_ubileyniy>>>",($gender=="men")?"Юбилейный":"Юбилейная",$text);	
		} else $text = str_replace("<<<condition_ubileyniy>>>","",$text);	
		
		
		if($metal["name_roditelnom"]){
		    $text = str_replace("<<<metal_roditelnom>>>",$metal["name_roditelnom"],$text);
		    $text = str_replace("<<<from_metal_roditelnom>>>","из ".$metal["name_roditelnom"],$text);
		} else {
		    $text = str_replace("<<<metal_roditelnom>>>","",$text);
		    $text = str_replace("<<<from_metal_roditelnom>>>","",$text);
		}
		
		
		if($gender=="men"){
			$text = str_replace("<<metal_prilagatelnoe>>",$metal["name_prilagatelnoe_mugskogo_roda"],$text);
		} else $text = str_replace("<<metal_prilagatelnoe>>",$metal["name_prilagatelnoe_genskogo_roda"],$text);

		$them = $this->getTheme($row["theme"]);
		$s = $series["name"];
		
		if($series["name"]){
			
			$text = str_replace("<<<numizmatik_series>>>","Нумизматическая серия – $s.",$text);
			$text = str_replace("<<<series>>>","$s.",$text);
			$text = str_replace("<<<from_series>>>","Из серии – $s.",$text);
			$text = str_replace("<<<materialtype_series>>>","Серия <<<materialtype_roditelnom>>> – $s",$text);
			
		} else {
			$text = str_replace("<<<numizmatik_series>>>","",$text);
			$text = str_replace("<<<series>>>","",$text);
			$text = str_replace("<<<from_series>>>","",$text);
			$text = str_replace("<<<materialtype_series>>>","",$text);
		}
		
				
		$text = str_replace("<<<theme>>>",($them?"Нумизматическая тематика – $them":""),$text);
		$text = str_replace("<<<numizmatik_theme>>>",($them?"Тематика – $them":""),$text);
		$text = str_replace("<<<t_theme>>>",($them?"Тема – $them.":""),$text);
		$text = str_replace("<<<ts_theme>>>",($them?"Тематики – $them.":""),$text);
		
		$text = str_replace("<<<from_theme>>>",($them?"Из тематики – $them.":""),$text);
		$text = str_replace("<<<materialtype_theme>>>",($them?"Тема <<<materialtype_roditelnom>>> – $them.":""),$text);
			
		$text = str_replace("<<<materialtype_IMENITELNIY>>>",self::$materialtyps[$row['materialtype']]["imenitelniy"],$text);
		$text = str_replace("<<<materialtype_roditelnom>>>",self::$materialtyps[$row['materialtype']]["roditelnom"],$text);
		$text = str_replace("<<<materialtype_vinitelniy>>>",self::$materialtyps[$row['materialtype']]["vinitelniy"],$text);
		$text = str_replace("<<<materialtype_mnowestvennoe>>>",self::$materialtyps[$row['materialtype']]["mnowestvennoe"],$text);
		
		$details = explode(".",$row['details']);
		$details = mb_substr($details[0],0,20,"utf8");
		
		$text = str_replace("<<<details_20_do_tochki>>>",$details,$text);
		
		
		//$materialtyps
		/* */
		return $this->clearText($text);
	}
	protected function clearText($text){
		$text = trim($text);
		
		$text = str_replace(array(" ."),".",$text);
		$text = str_replace(array(". .","\n","..","...",". . .",". ..",".. .",".. .",". ."),".",$text);
		
		$text = trim($text);
		if(substr($text,0,1)=='.') $text = substr($text,1);
		return $text;
	}
	public function getTheme($theme){
		$shopcoinstheme = array();
		$strtheme = decbin($theme);
		$strthemelen = strlen($strtheme);
		
		$chars = preg_split('//', $strtheme, -1, PREG_SPLIT_NO_EMPTY);
		for ($k=0; $k<$strthemelen; $k++) {
			if ($chars[$k]==1)
			{
				$shopcoinstheme[] = self::$ThemeArray[($strthemelen-1-$k)];
				if (!in_array(($strthemelen-1-$k), $ShopcoinsThemeArray))
					$ShopcoinsThemeArray[] = ($strthemelen-1-$k);
			}
		}
		return implode(",",$shopcoinstheme);
	}
	
	public function getSeries($id){
		if(!$id) return array();
		
		$select = $this->db->select()
                  ->from('shopcoinsseries')                  
                  ->where('shopcoinsseries=?',$id);;
                  
		return $this->db->fetchRow($select);
	}
	
	public function createText($row){		
                
		$template = $this->getTemplate();
		$group = $this->getGroup($row['group']);
		$metal= $this->getMetal($row['metal_id']);
		$condition = $this->getCondition($row['condition_id']);
		$series = $this->getSeries($row['series']);
		$text_title = $this->prepareText($template['title'],$row,$group,$metal,$condition,$series);
		$text_description = $this->prepareText($template["details"],$row,$group,$metal,$condition,$series);

		return array(ucfirst(trim($text_title)),ucfirst(trim($text_description)));
	} 
	
	public function getCoins($limit=8000){
		$select = $this->db->select()
                  ->from('shopcoins')
                  ->joinLeft('shopcoins_meta','shopcoins_meta.shopcoins=shopcoins.shopcoins',array())                  
                  ->where('(`check` =1 OR  `check` >=4) and materialtype <> 3 and shopcoins_meta.shopcoins is null')
                  ->limit($limit) 
                                
                  //->order('rand()')
                  ->order('shopcoins.shopcoins desc') ;
                  echo $select->__toString();
        return $this->db->fetchAll($select);
	}
	public function getCoinsByID($id){
		$select = $this->db->select()
                  ->from('shopcoins')                  
                  ->where('shopcoins=?',$id)
                  ->limit($limit)                  
                  ->order('rand()')
                 // ->order('shopcoins desc')
                 ;
        return $this->db->fetchAll($select);
	}
	
	public function getGroup($id){
		$select = $this->db->select()
                  ->from('group')                  
                  ->where('`group`=?',$id);
        return $this->db->fetchRow($select);
	}
	public function getCondition($id){
		$select = $this->db->select()
                  ->from('shopcoins_condition')                  
                  ->where('id=?',$id);
        return $this->db->fetchRow($select);
	}
	public function getMetal($id){
		$select = $this->db->select()
                  ->from('shopcoins_metal')                  
                  ->where('id=?',$id);
        return $this->db->fetchRow($select);
	}
	
	public function getDetails($id){
	    $select = $this->db->select()
                  ->from('shopcoins_details')                  
                  ->where('catalog=?',$id);
        return $this->db->fetchRow($select);
	}
	
	public function setMetaDetails($id,$title,$description){
	    $select = $this->db->select()
                  ->from('shopcoins_meta',array('id'))                  
                  ->where('shopcoins=?',$id);
        if(!$this->db->fetchOne($select)){
        	$data = array('shopcoins'=>$id,
        			      'description'=>$description,
        			      'pagetitle'=>$title);
        	$this->db->insert('shopcoins_meta',$data);
        	return true;
        }
        return false;
	}	
		
	
}
