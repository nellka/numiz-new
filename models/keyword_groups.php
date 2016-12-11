<?php

/*Класс для получения информации о пользователе*/
class keyword_groups extends Model_Base 
{		
   protected $db;
   static $CoinsText = array(0=>array("title"=>"Купить <<<metal1_mn_and_metal2_mn>>> <<<materialtype_rodit_m>>> <<<group_roditelniy>>>. Распродажа. Клуб Нумизмат.",
						"details"=>"В нашем каталоге представлена распродажа <<<name1, name2, name3>>> и другие номиналы. Антикризисные цены. Доставляем наложенным платежом, магазин в Москве."),
							  1=>array("title"=>"Заказать почтой дешевые <<<materialtype_mnowestvennoe>>> <<<group_roditelniy>>> <<<from metal1_rod_and_metal2_rod>>> на распродаже. ",
							  		  "details"=>"В каталоге представлены фото и цены на распродажу <<<name1, name2, name3>>> и других номиналов. Заказ отправляем по вторникам, доставка по Москве ежедневно по рабочим дням."),
							   2=>array("title"=>"Интернет магазин Клуб Нумизмат. Каталог распродажа <<<materialtype_rodit_m>>> <<<group_roditelniy>>>.",
							   "details"=>"Распродажа <<<name1, name2, name3>>>. <<<metal1_mn, metal2_mn, metal3_mn>>>. Фото и цены на сайте. Купи в один клик, по Москве доставим завтра, также отправляем почтой наложенным платежом."),
							   3=>array("title"=>"Распродажа <<<materialtype_rodit_m>>> <<<group_roditelniy>>> от магазина Клуб Нумизмат.",
							   "details"=>"Оптом и в розницу. Дешевые <<<name1, name2, name3>>> и другие на распродаже. Из <<<metal1_rod_and_metal2_rod>>>. Отличный подарок для коллекционера. Каталог с ценами и фото здесь. Отправляем наложенным платежом"),
							   4=>array("title"=>"Каталог магазина Клуб Нумизмат. Распродажа <<<materialtype_rodit_m>>> <<<group_roditelniy>>> из <<<metal1_rod_and_metal2_rod>>>",
							   "details"=>"Дешевые <<<materialtype_mnowestvennoe>>> <<<name1, name2, name3>>> и другие. Стоимость и фото на сайте. Доставим почтой и курьером по Москве. Распродажа. Закажи новинки сейчас в один клик. Оптом и в розницу."),
							   5=>array("title"=>"Распродажа <<<materialtype_rodit_m>>> <<<group_roditelniy>>>. От Клуба Нумизмат. Магазин в Москве",
							   "details"=>"Распродажа разных годов и номиналов. Таких как новинки <<<name1, name2, name3>>>. <<<metal1_mn_and_metal2_mn>>>. Оптом и в розницу. "),
							   6=>array("title"=>"Интернет-магазин от Клуба Нумизмат предлагает на распродаже купить <<<metal1_mn_and_metal2_mn>>> <<<materialtype_mnowestvennoe>>> <<<group_roditelniy>>>",
							   "details"=>"Распродажа <<<name1, name2, name3>>>. Есть другие дешевые новинки. Года, цены, фото на сайте. Доставка по Москве и почтой. Отправляем почтой наложенным платежом."),
							   7=>array("title"=>"На распродаже купить новинки дешевых и дорогих <<<materialtype_rodit_m>>> <<<group_roditelniy>>>. <<<from metal1_rod_and_metal2_rod>>>.",
							   "details"=>"Заказать можно в один клик. С доставкой по Москве и почтой по России. Распродажа от Клуба Нумизмат. В каталоге <<<name1, name2, name3>>> и другие. Цена, фото, года на сайте."),
							   8=>array("title"=>"Клуб Нумизмат. <<<materialtype_mnowestvennoe>>> <<<group_roditelniy>>>. Распродажа <<<name1, name2, name3>>>",
							   "details"=>"Купить <<<materialtype_mnowestvennoe>>> <<<from metal1_rod_and_metal2_rod>>>. В отличном состоянии. От дешевых до дорогих. Распродажа. Закажи сегодня, получи курьером по Москве завтра или отправим почтой во вторник. Фото, стоимость в каталоге здесь."),
							   9=>array("title"=>"Распродажа в подарок коллекционеру. <<<metal1_mn, metal2_mn_and_metal2_mn>>> <<<materialtype_mnowestvennoe>>> <<<group_roditelniy>>>.",
							   "details"=>"В каталоге распродажа <<<name1, name2, name3>>> и другие. Цены - от дешевых до дорогих. С доставкой по всей России наложенным платежом. Магазин в Москве. Купи в один клик здесь")
							 );
	
							   
	static $materialtyps = array(
					100=>array("mnowestvennoe"=>"монеты",
								"imenitelniy"=>"монета",
								"roditelnom"=>"монеты",
								"rodit_m"=>"монет",
								"vinitelniy"=>"монету",
								"gender"=>"women"),
								200=>array("mnowestvennoe"=>"монеты",
								"imenitelniy"=>"монета",
								"roditelnom"=>"монеты",
								"rodit_m"=>"монет",
								"vinitelniy"=>"монету",
								"gender"=>"women"),
					     1=>array("mnowestvennoe"=>"монеты",
								"imenitelniy"=>"монета",
								"roditelnom"=>"монеты",
								"rodit_m"=>"монет",
								"vinitelniy"=>"монету",
								"gender"=>"women"),
                         8=>array("mnowestvennoe"=>"монеты",
								"imenitelniy"=>"монета",
								"rodit_m"=>"монет",
								"roditelnom"=>"монеты",
								"vinitelniy"=>"монету",
								"gender"=>"women"),
						11=>array("mnowestvennoe"=>"монеты",
								"imenitelniy"=>"монета",
								"roditelnom"=>"монеты",
								"rodit_m"=>"монет",
								"vinitelniy"=>"монету",
								"gender"=>"women"),
                         6=>array(
		                         "mnowestvennoe"=>"цветные монеты" ,
		                         "imenitelniy"=>"цветная монета" ,
		                         "rodit_m"=>"монет",
		                         "roditelnom"=>"цветной монеты",
		                         "vinitelniy"=>"цветную монету",
								 "gender"=>"women"),
                         10=>array("mnowestvennoe"=>"нотгельды",
		                         "imenitelniy"=>"нотгельд",
		                         "rodit_m"=>"нотгельдов",
		                         "roditelnom"=>"нотгельда",
		                         "vinitelniy"=>"нотгельда",
								 "gender"=>"men"),
                         7=>array("mnowestvennoe"=>"наборы монет",
		                         "imenitelniy"=>"набор монет",
		                         "rodit_m"=>"наборов монет",
		                         "roditelnom"=>"набора монет",
		                         "vinitelniy"=>"набор монет",
								 "gender"=>"men"
                         ),
                         9=>array("mnowestvennoe"=>"лоты монет для начинающих нумизматов",
		                         "imenitelniy"=>"лот монет для начинающих нумизматов",
		                         "rodit_m"=>"лотов монет для начинающих нумизматов",
		                         "roditelnom"=>"лота монет для начинающих нумизматов",
		                         "vinitelniy"=>"лот монет для начинающих нумизматов",
								 "gender"=>"men"
                         ),
                         2=>array("mnowestvennoe"=>"монеты",
								"imenitelniy"=>"монета",
								"roditelnom"=>"монеты",
								"rodit_m"=>"монет",
								"vinitelniy"=>"монету",
								"gender"=>"women"),
                         4=>array("mnowestvennoe"=>"подарочные наборы",
	                         "imenitelniy"=>"подарочный набор",
	                         "roditelnom"=>"подарочного набора",
	                         "rodit_m"=>"подарочных наборов",
	                         "vinitelniy"=>"подарочный набор",
							 "gender"=>"men"),
                         5=>array("mnowestvennoe"=>"книги о монетах",
	                         "imenitelniy"=>"книги о монетах",
	                         "roditelnom"=>"книг о монетах",
	                         "rodit_m"=>"книг о монетах",
	                         "vinitelniy"=>"книги о монетах",
							 "gender"=>"men"),
                         ); 
							 
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
		$key = rand(0,9);
		return self::$CoinsText[$key];
	}
	
	protected function prepareText($text,$row){
		/*$text = str_replace("<<<name>>>",trim($row['name']),$text);		
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
		$text = str_replace("<<<group>>>",$group['name'],$text);*/	
		
				
		if(!$row['group']["name_roditelnom_padege"]) $row['group']["name_roditelnom_padege"] = $row['group']["name"];
		$text = str_replace("<<<group_roditelniy>>>",$row['group']["name_roditelnom_padege"],$text);
		/*if($condition['name']){
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
			
		*/
		$text = str_replace("<<<materialtype_roditelnom>>>",self::$materialtyps[$row['materialtype']]["roditelnom"],$text);		
		$text = str_replace("<<<materialtype_rodit_m>>>",self::$materialtyps[$row['materialtype']]["rodit_m"],$text);
		$text = str_replace("<<<materialtype_imenitelniy>>>",self::$materialtyps[$row['materialtype']]["imenitelniy"],$text);		
		$text = str_replace("<<<materialtype_vinitelniy>>>",self::$materialtyps[$row['materialtype']]["vinitelniy"],$text);		
		$text = str_replace("<<<materialtype_mnowestvennoe>>>",self::$materialtyps[$row['materialtype']]["mnowestvennoe"],$text);
		/*
		$details = explode(".",$row['details']);
		$details = mb_substr($details[0],0,20,"utf8");
		
		$text = str_replace("<<<details_20_do_tochki>>>",$details,$text);
		*/
		
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
	
	public function prepareMetals($text,$metals=array()){
		if(!count($metals)){
			$text = str_replace("<<<metal1_mn_and_metal2_mn>>>","",$text);
			$text = str_replace("<<<metal1_mn, metal2_mn, metal3_mn>>>","",$text);
			$text = str_replace("<<<metal1_rod_and_metal2_rod>>>","",$text);
			$text = str_replace("<<<metal1_mn, metal2_mn_and_metal2_mn>>>","",$text);	
			$text = str_replace("<<<from metal1_rod_and_metal2_rod>>>","",$text);	
					
		} elseif(count($metals)==1) {
			$text = str_replace("<<<metal1_mn_and_metal2_mn>>>", $metals[0]["name_prilagatelnoe_mnogestvennoe_chislo"],$text);
			$text = str_replace("<<<from metal1_rod_and_metal2_rod>>>"," из ".$metals[0]["name_roditelnom"],$text);
			$text = str_replace("<<<metal1_mn, metal2_mn, metal3_mn>>>",$metals[0]["name_prilagatelnoe_mnogestvennoe_chislo"],$text);
			$text = str_replace("<<<metal1_rod_and_metal2_rod>>>",$metals[0]["name_roditelnom"],$text);
			$text = str_replace("<<<metal1_mn, metal2_mn_and_metal2_mn>>>",$metals[0]["name_prilagatelnoe_mnogestvennoe_chislo"],$text);
		} elseif(count($metals)==2) {
			$text = str_replace("<<<metal1_mn_and_metal2_mn>>>", $metals[0]["name_prilagatelnoe_mnogestvennoe_chislo"]." и ".$metals[1]["name_prilagatelnoe_mnogestvennoe_chislo"],$text);
			$text = str_replace("<<<from metal1_rod_and_metal2_rod>>>"," из ".$metals[0]["name_roditelnom"]." и ".$metals[1]["name_roditelnom"],$text);
			
			$text = str_replace("<<<metal1_mn, metal2_mn, metal3_mn>>>",$metals[0]["name_prilagatelnoe_mnogestvennoe_chislo"].", ".$metals[1]["name_prilagatelnoe_mnogestvennoe_chislo"],$text);
			$text = str_replace("<<<metal1_rod_and_metal2_rod>>>",$metals[0]["name_roditelnom"]." и ".$metals[1]["name_roditelnom"],$text);
			$text = str_replace("<<<metal1_mn, metal2_mn_and_metal2_mn>>>",$metals[0]["name_prilagatelnoe_mnogestvennoe_chislo"].", ".$metals[1]["name_prilagatelnoe_mnogestvennoe_chislo"],$text);
		} else {
			$text = str_replace("<<<metal1_mn_and_metal2_mn>>>", $metals[0]["name_prilagatelnoe_mnogestvennoe_chislo"]." и ".$metals[1]["name_prilagatelnoe_mnogestvennoe_chislo"],$text);
			$text = str_replace("<<<from metal1_rod_and_metal2_rod>>>"," из ".$metals[0]["name_roditelnom"]." и ".$metals[1]["name_roditelnom"],$text);
			$text = str_replace("<<<metal1_mn, metal2_mn, metal3_mn>>>",$metals[0]["name_prilagatelnoe_mnogestvennoe_chislo"].", ".$metals[1]["name_prilagatelnoe_mnogestvennoe_chislo"].", ".$metals[2]["name_prilagatelnoe_mnogestvennoe_chislo"],$text);
			$text = str_replace("<<<metal1_rod_and_metal2_rod>>>",$metals[0]["name_roditelnom"]." и ".$metals[1]["name_roditelnom"],$text);
			$text = str_replace("<<<metal1_mn, metal2_mn_and_metal2_mn>>>",$metals[0]["name_prilagatelnoe_mnogestvennoe_chislo"].", ".$metals[1]["name_prilagatelnoe_mnogestvennoe_chislo"]." и ".$metals[2]["name_prilagatelnoe_mnogestvennoe_chislo"],$text);
		
		}	
		
		return $text;
	}
	
	public function prepareNames($text,$names){
		if(!count($names)){
			$text = str_replace("<<<name1, name2, name3>>>",'',$text);
		} else {
			$text = str_replace("<<<name1, name2, name3>>>",implode(", ",$names),$text);
		}			
		return $text;
	}
	
	public function createText($row){		
                
		$template = $this->getTemplate();
		$metals = $this->getMetals($row['materialtype'],$row['group']['group']);
		
		$template['title'] = $this->prepareMetals($template['title'],$metals['metals']);	
		$template["details"] = $this->prepareMetals($template["details"],$metals['metals']);
		$names = array();
		
		$limit = 4-count($metals);
		foreach ($metals['ids'] as $metal){			
			$n = $this->getName($row['materialtype'],$row['group']['group'],$metal,$limit);
			foreach ($n as $r){
				$names[] = $r['n']; 
			} 
		}
		if(!$metals['ids']){
			$n = $this->getName($row['materialtype'],$row['group']['group'],0,3);
			foreach ($n as $r){
				$names[] = $r['n']; 
			} 
		}
		$template['title'] = $this->prepareNames($template['title'],$names);	
		$template["details"] = $this->prepareNames($template["details"],$names);
		
		$text_title = $this->prepareText($template['title'],$row);		
		$text_description = $this->prepareText($template["details"],$row);
		
		/*$metal= $this->getMetal($row['metal_id']);
		$condition = $this->getCondition($row['condition_id']);
		$series = $this->getSeries($row['series']);
		
		*/

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
	
	public function getGroups($materialtype){
		$select = $this->db->select()
		          ->from('shopcoins_search',array('distinct(shopcoins_search.group)'))
                  ->join('group','group.group=shopcoins_search.group',array('*')) ;
                   
                 // ->where("group.group=?",520)                  
                  $select ->order('group.group asc');
                  //->limit(100)
        if($materialtype==100){
        	$select->where("year in(2014,2015,2016)");
        } elseif($materialtype==200){
        	$select->where("datereprice>0 and shopcoins_search.dateinsert>0");
        } else {
        	$select->where("materialtype=?",$materialtype);
        }
        return $this->db->fetchAll($select);
	}
	
	public function getName($materialtype,$group,$metal=0,$limit=1){
		
		$select = $this->db->select()
                  ->from('shopcoins',array('distinct(name) as n'))              
                  ->where('`group`=?',$group)                                   
                  ->order('width desc')
                  ->limit($limit);
		if($metal) $select->where('metal_id=?',$metal);
		if($materialtype==100){
        	$select->where("year in(2014,2015,2016)");
        } elseif($materialtype==200){
        	$select->where("datereprice>0 and shopcoins.dateinsert>0");
        } else {
        	$select->where("materialtype=?",$materialtype);
        }
		//echo $select->__toString();
		return $this->db->fetchAll($select);
	}
	public function getMetals($materialtype,$group){
		
		$select = $this->db->select()
                  ->from('shopcoins_search',array('distinct(metal_id)'))
                  ->join('shopcoins_metal','shopcoins_metal.id=metal_id',array('*'))                  
                  ->where('`group`=?',$group)                  
                  ->order('metal_id desc');
         if($materialtype==100){
        	$select->where("year in(2014,2015,2016)");
        } elseif($materialtype==200){
        	 $select->where("datereprice>0 and shopcoins_search.dateinsert>0");
        } else {
        	$select->where("materialtype=?",$materialtype);
        }
		$metals = $this->db->fetchAll($select);
		
		$metal_result = array();
		$metal_result[0] = array();
		$metal_result[1] = array();
		$metal_result[2] = array();
		$metal_result[3] = array();
		$metal_result[4] = array();
		$metal_result[5] = array();
		
		$i = 0;
		
		foreach ($metals as $row){
			//
			if($row['metal_id']==11){				
				$metal_result[0] = $row;				
				continue;
			}
			if($row['metal_id']==21){				
				$metal_result[1] = $row;				
				continue;
			}
			if($row['metal_id']==20){				
				$metal_result[2] = $row;				
				continue;
			}
			if($row['metal_id']==1){				
				$metal_result[3] = $row;				
				continue;
			}
			if($row['metal_id']==3){				
				$metal_result[4] = $row;				
				continue;
			}
			
			if($row['metal_id']==5){				
				$metal_result[5] = $row;				
				continue;
			}
			
			$metal_result[] = $row;
			$i++;
		}
		
		$i=0;
		$ids = array();
		$metal_result_clier= array();
		foreach ($metal_result as $key=>$row){
			if($i>=3) break;
			if(!count($row)) continue;
			$metal_result_clier[] = $row;
			$ids[] = $row['metal_id'];
			$i++;
		}
		
        return array('metals'=>$metal_result_clier,'ids'=>$ids);
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
	
	public function setMetaDetails($materialtype,$group,$title,$description){
	    $select = $this->db->select()
                  ->from('shopcoinsseotext',array('id','pagetitle','description'))                  
                  ->where('materialtype=?',$materialtype)
                  ->where('group_id=?',$group)
                  ->where('nominal_id=0')
                  ->where('metal_id=0')
                  ->where('year=0') ;
        $meta_data = $this->db->fetchRow($select);
        if(!$meta_data){
        	$data = array('materialtype'=>$materialtype,
        				  'group_id'=>$group,
        			      'description'=>$description,
        			      'pagetitle'=>$title,
        			      'dateinsert'=>time(),
        			      'active'=>1);
        	$this->db->insert('shopcoinsseotext',$data);
        	return true;
        } elseif(!$meta_data['pagetitle']&&!$meta_data['description']) {
        	$data = array('description'=>$description,
        			      'pagetitle'=>$title);
        	$this->db->update('shopcoinsseotext',$data,"id=".$meta_data['id']);
        	
        } elseif(!$meta_data['pagetitle']) {
        	$data = array('pagetitle'=>$title);
        	$this->db->update('shopcoinsseotext',$data,"id=".$meta_data['id']);
        } elseif(!$meta_data['description']) {
        	$data = array('description'=>$description);
        	$this->db->update('shopcoinsseotext',$data,"id=".$meta_data['id']);        	
        }
        
        return false;
	}	
		
	
}
