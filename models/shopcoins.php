<?php


class model_shopcoins extends Model_Base
{	
    public $id;
    public $user_id;
    public $last_name; 
       	
	public function __construct($db,$user_id=0,$nocheck=0){
	    parent::__construct($db);
	    $this->user_id = $user_id;
	    $this->nocheck = $nocheck;
	  //  $this->db->query("SET names 'cp1251'");
	}
	public function countByParams($where){
	   // $this->_getSelect($select) );	    
	    $sql = "Select count(*) from shopcoins $where;";
	    //echo $sql."<br>";
    	//$result=mysql_query($sql);
    	$result = $this->db->fetchOne($sql);
    	return $result;
	}
	
	public function countAll(){
        $select = $this->db->select()
		               ->from($this->table,array('count(*)'))
		               ->where('shopcoins.check=1');
    	return $this->db->fetchOne($select);       
	}
	
    public function getMaxPrice($materialtype){
         $select = $this->db->select()
		               ->from($this->table,array('max(price)'));
		 $select = $this->byAdmin( $select);
    	 if($materialtype == 2){
    	       $select->where('shopcoins.amount > 0');     	       
    	} 
	     $select->where("(shopcoins.materialtype=? or shopcoins.materialtypecross & pow(2,?))",$materialtype); 
	     return $this->db->fetchOne($select);
    }
    
	public function getCoinsParents($ids){
         $select = $this->db->select()
		               ->from($this->table)
		               ->where("parent in (".implode(",", $ids).")")
		               ->order('parent');
		 $select = $this->byAdmin( $select);		 
    	 return $this->db->fetchAll($select);
	}
	
    
	public function countAllByParams($materialtype,$WhereParams=array(),$searchid='',$yearsearch=''){
	    $searchname = '';
	    $select = $this->db->select()
		               ->from($this->table,array('count(*)'));
 
		if (isset($WhereParams['pricestart'])) {
        	$select->where("shopcoins.`price` >= ?",floatval($WhereParams['pricestart']));
        }
        if (isset($WhereParams['priceend'])) {        	
        	$select->where("shopcoins.`price` <=?",floatval($WhereParams['priceend']));
        }
        if (isset($WhereParams['theme'])) {
        	 $whereTheme = array();
            foreach ($WhereParams['theme'] as $theme){
                $whereTheme[] = "(shopcoins.theme='".pow(2,$theme)."' or shopcoins.theme & ".pow(2,$theme).">0)";
            }
            if($whereTheme) {
                $select->where("(".implode(' or ',$whereTheme).')');
            }
        }
        if (isset($WhereParams['searchname'])) {   
             $searchname =   $WhereParams['searchname'];   	
             $select->where("shopcoins.name=?",$WhereParams['searchname']);	
        }
        
        if (isset($WhereParams['series'])){
        	$select->where("shopcoins.series = ?",intval($WhereParams['series']));
        }
        if (isset($WhereParams['year'])) {
        	$where_year = array();
        	//var_dump($WhereParams['year']);
        	foreach ($WhereParams['year'] as $year_int){
        		if($year_int[0]>0&&$year_int[1]>0&&$year_int[1]<date('Y',time())){
        			$where_year[] = "(`year` >={$year_int[0]} and `year` <={$year_int[1]})";
        		} elseif ($year_int[0]>0){
        			$where_year[] = "(`year` >={$year_int[0]})";
        		} else {
        			$where_year[] = "(`year` <={$year_int[1]})";
        		}        		
        	}
        	$select->where("(".implode(" or ",$where_year).")");
        }

        if (isset($WhereParams['group'])) {
        	$select->where("shopcoins.`group` in (".implode(",",$WhereParams['group']).")");
        }
        if (isset($WhereParams['metal'])) {
            $metals = array();
            foreach ($WhereParams['metal'] as $metal){
                $metal = str_replace("'","",$metal);
                $metals[] = "'".$metal."'";
            }        	
        	$select->where("shopcoins.metal in (".implode(",",$metals).")");

        }
        if (isset($WhereParams['condition'])) {        	
        	$condition = array();
            foreach ($WhereParams['condition'] as $condition){
                $condition = str_replace("'","",$condition);
                $conditions[] = "'".$condition."'";
            }        	
        	$select->where("shopcoins.condition in (".implode(",",$conditions).")");
        }
 
	    $select = $this->byAdmin($select); 
	          
	    if($materialtype == 2){
	       $select->where('shopcoins.amount > 0'); 	       
	   } 
	   
	   if ($searchid) {	
	       
       } /*elseif($search){
	       //$where = " where ( ".($show50?"or shopcoins.check=50":"").") and ((shopcoins.materialtype in (2,4,7,8,3,5,9)) or (shopcoins.materialtype in(1,10) and shopcoins.amountparent>0) or shopcoins.number='$search' or shopcoins.number2='$search')";
	   } */else {
	       if($materialtype == 2){
    	       $select->where('shopcoins.amount > 0');     	       
    	   } 
    	   if ($materialtype==1 || $materialtype==10){
    	       if(!$yearsearch&&!$searchname){
    	            $select->where("(shopcoins.materialtype='".$materialtype."' and shopcoins.amountparent > 0) or shopcoins.materialtypecross & pow(2,".$materialtype.")".(isset($WhereParams['group'])?" or shopcoins.materialtype='8' or shopcoins.materialtypecross & pow(2,8)":"")); 		
    	       } else {
    	           $select->where("shopcoins.materialtype='".$materialtype."' or shopcoins.materialtypecross & pow(2,".$materialtype.")".($group?" or shopcoins.materialtype='8' or shopcoins.materialtypecross & pow(2,8)":"")); 
    	       }
	            
    	   } else {
    	        $select->where("(shopcoins.materialtype=? or shopcoins.materialtypecross & pow(2,?))",$materialtype); 
    	   }
	   } 
	 echo $select->__toString();
       return $this->db->fetchOne($select);       
	}
	public function getPopular($limit=4){ 
	   $select = $this->db->select()
                      ->from('shopcoins')
                      ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name'))
                      ->where("shopcoins.check=1")
                      ->order('rand()')
                      ->limit($limit);
       return $this->db->fetchAll($select);
	}  
	public function getNew($limit=4){ 
	   $select = $this->db->select()
                      ->from('shopcoins')
                      ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name'))
                      ->where("shopcoins.check=1")
                      ->order('shopcoins desc')
                      ->limit($limit);
       return $this->db->fetchAll($select);
	}  
	
	public function getItemsByParams($materialtype,$WhereParams=array(),$yearsearch='', $page=1, $items_for_page=30,$orderby='',$searchid=''){
        if(isset($WhereParams['coinssearch'])) $coinssearch = $WhereParams['coinssearch'];
	   $searchname = 0;
	    //если нет ничего в поиске
	    //часть данных не инициализирую на первом этапе
	   $select = $this->db->select()
	                      ->from('shopcoins')
	                      ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name'));
	   
	                      
	     if (isset($WhereParams['pricestart'])) {
        	$select->where("shopcoins.`price` >= ?",floatval($WhereParams['pricestart']));
        }
        if (isset($WhereParams['priceend'])) {        	
        	$select->where("shopcoins.`price` <=?",floatval($WhereParams['priceend']));
        }
        if (isset($WhereParams['searchname'])) {   
             $searchname =   $WhereParams['searchname'];   	
             $select->where("shopcoins.name=?",$WhereParams['searchname']);	
        }

        if (isset($WhereParams['theme'])) {
            $whereTheme = array();
            foreach ($WhereParams['theme'] as $theme){
                $whereTheme[] = "(shopcoins.theme='".pow(2,$theme)."' or shopcoins.theme & ".pow(2,$theme).">0)";
            }
            if($whereTheme) {
                $select->where("(".implode(' or ',$whereTheme).')');
            }        	
        }
        
        if (isset($WhereParams['series'])){
        	$select->where("shopcoins.series = ?",intval($WhereParams['series']));
        }
        if (isset($WhereParams['group'])) {
        	$select->where("shopcoins.`group` in (".implode(",",$WhereParams['group']).")");
        }
        if (isset($WhereParams['year'])) {
        	$where_year = array();
        	//var_dump($WhereParams['year']);
        	foreach ($WhereParams['year'] as $year_int){
        		if($year_int[0]>0&&$year_int[1]>0&&$year_int[1]<date('Y',time())){
        			$where_year[] = "(`year` >={$year_int[0]} and `year` <={$year_int[1]})";
        		} elseif ($year_int[0]>0){
        			$where_year[] = "(`year` >={$year_int[0]})";
        		} else {
        			$where_year[] = "(`year` <={$year_int[1]})";
        		}        		
        	}
        	$select->where("(".implode(" or ",$where_year).")");
        }
        
        if (isset($WhereParams['metal'])) {
        	$metals = array();
            foreach ($WhereParams['metal'] as $metal){
                $metal = str_replace("'","",$metal);
                $metals[] = "'".$metal."'";
            }        	
        	$select->where("shopcoins.metal in (".implode(",",$metals).")");
        }
        if (isset($WhereParams['condition'])) {
        	$condition = array();
            foreach ($WhereParams['condition'] as $condition){
                $condition = str_replace("'","",$condition);
                $conditions[] = "'".$condition."'";
            }        	
        	$select->where("shopcoins.condition in (".implode(",",$conditions).")");
        }


	   $select = $this->byAdmin($select); 
	         
	   if($materialtype == 2){
	       $select->where('shopcoins.amount > 0');	       
	   }      

	   if ($materialtype==1 || $materialtype==10){
	       if(!$searchid&&!$yearsearch&&!$searchname){
	            $select->where("(shopcoins.materialtype='".$materialtype."' and shopcoins.amountparent > 0) or shopcoins.materialtypecross & pow(2,".$materialtype.")".(isset($WhereParams['group'])?" or shopcoins.materialtype='8' or shopcoins.materialtypecross & pow(2,8)":"")); 		
	       } else {
	           $select->where("shopcoins.materialtype='".$materialtype."' or shopcoins.materialtypecross & pow(2,".$materialtype.")".($group?" or shopcoins.materialtype='8' or shopcoins.materialtypecross & pow(2,8)":"")); 
	       }
	            
	   } else {
	        $select->where("(shopcoins.materialtype=? or shopcoins.materialtypecross & pow(2,?))",$materialtype); 
	   }
	   
	   if ($searchname) {
        	$searchname = str_replace("'","",$searchname);
        	$select->where('shopcoins.name=?',$searchname);
       }	
       if ($yearsearch>0) {
        	$searchname = str_replace("'","",$searchname);
        	$select->where('hopcoins.year=?',$yearsearch);
       }	
	//  var_dump($orderby);
	 if(isset($WhereParams['group'])&&!$page){
	        /* if($sortname){
	           if($coinssearch){
	                $select->order("shopcoins.shopcoins=".intval($coinssearch)." desc, groupparent asc,param2,param1,".$dateinsert_orderby." desc");
	           } else {
	                $select->order("groupparent asc,param2,param1,".$dateinsert_orderby." desc");
	           }
	       } else {
	            if($coinssearch){
	                $select->order("shopcoins.shopcoins=".intval($coinssearch)." desc, groupparent asc,".$dateinsert_orderby." desc,price desc, param2,param1");
	            } else {
	                $select->order("groupparent asc,".$dateinsert_orderby." desc,price desc, param2,param1" );
	            }      
	       }*/	      
	   } else {
	       $select->order($orderby);
	   }
	   if($items_for_page!='all'){
	        $select->limitPage($page, $items_for_page);
	   }  
	  
	   echo $select->__toString();
       return $this->db->fetchAll($select);
	} 
	//получаем уже зарезервированные монеты
    public function getReserved($id,$reservetime){
         $select = $this->db->select()
                      ->from('helpshopcoinsorder')
                     ->where("shopcoins=?",$id)
                     ->where("reserve > ?",time() - $reservetime);
       return $this->db->fetchAll($select);       
    }
	
    
	public function addSearchStatistic(){
	    
	    $sql_key = "select count(*) from keywords where word='".lowstring($search)."' and page='$script';";
		$result_key = mysql_query($sql_key);
		$rows_key = mysql_fetch_array($result_key);
		if ($rows_key[0]==0)
		{
			$sql_key2 = "insert into keywords values (0, '".lowstring(strip_string($search))."', '$script', 1, $amountsearch);";
		} else {
			$sql_key2 = "update keywords set counter=counter+1 where word='".lowstring($search)."' and page='$script';";
		}
		$result_key2 = mysql_query($sql_key2);
//		echo $sql_key2."=sql_key2<br>";
		$sql_tmp = "select * from searchkeywords where keywords='".lowstring($search)."' and page='$script';";
		$result_tmp = mysql_query($sql_tmp);
		$rows_tmp = mysql_fetch_array($result_tmp);
		if ($rows_tmp[0]==0)
		{
			$sql_tmp2 = "insert into searchkeywords values (0, '$maxcoefficient', '$sumcoefficient','".lowstring(strip_string($search))."', '$script', 1, '$amountsearch', '$timenow');";
		} else {
			$sql_tmp2 = "update searchkeywords set counter=counter+1, maxcoefficient='$maxcoefficient', sumcoefficient='$sumcoefficient', amount='$amountsearch' where keywords='".lowstring($search)."' and page='$script';";
		}
		$result_tmp2 = mysql_query($sql_tmp2);
		//echo $sql_tmp2."=sql_tmp2<br>";
			//����� ������
	}
	
	//получаем металы для выборки
	public function getMetalls($materialtype=1){
	    $select = $this->db->select()
	                      ->from('shopcoins',array('metal'=>'distinct(metal)'))
	                      ->where('metal<>""')
	                      ->where("(shopcoins.materialtype=? or shopcoins.materialtypecross & pow(2,?))",$materialtype)                      
	                      ->order('metal desc');
	   $select = $this->byAdmin($select); 	
	   return $this->db->fetchAll($select);       
	}
	//группы для выборки
	public function getGroups($materialtype=1,$is_revaluation=0,$is_new=0){	   
		//	".$WhereSearch;	    
	    $select = $this->db->select()
	                      ->from('shopcoins',array('group'=>'distinct(`group`)'))	                      
	                      //->where('group<>""')
	                      ->where('shopcoins.dateinsert>0')
	                       ->order('group desc');
	   $select = $this->byAdmin($select); 
       if($materialtype){
           $select->where("(shopcoins.materialtype=? or shopcoins.materialtypecross & pow(2,?))",$materialtype)                         ->order('group desc');
       } elseif ($is_revaluation){
            
       } elseif ($is_new){
            $arraynewcoins = Array(1=>date('Y')-2,2=>date('Y')-1,3=>date('Y'));
            $select->where("shopcoins.materialtype in(1,4,7,8)")
                   ->where("shopcoins.year in(".implode(",",$arraynewcoins).")");	
       }
       return $this->db->fetchAll($select);       
	}
	//получаем данные о группах
	public function getGroupsDetails($ids = array(),$is_parent = false){	
	    $select = $this->db->select()
	                      ->from('group')	 
	                      ->order('name asc');
	    if($ids) {
	        $select->where("`group` in (".implode(",", $ids).")");
	    } else {
	        $select->where("`group`=0");
	    }
	    if($is_parent)  $select->where("groupparent='0'");
        return $this->db->fetchAll($select);     
	}
	
	protected function byAdmin($select){
	    if($this->user_id==811||$this->user_id==309236) {
	       if(!$this->nocheck){
	           $select->where("shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)");
	       } else {
	           $select->where("shopcoins.check>3 and shopcoins.check<20");
	       }
	   }  else {
	       $select->where("shopcoins.check=1");
	   }  
	   return  $select;    
	}
	public function getConditions($materialtype=1){
		$select = $this->db->select()
	                      ->from('shopcoins',array('distinct(`condition`)'))
	                      ->where("(shopcoins.materialtype=? or shopcoins.materialtypecross & pow(2,?))",$materialtype)
	                      ->where('`condition`>""')
	                      ->order('condition desc');    
	   	$select = $this->byAdmin($select); 	   	
	   	return $this->db->fetchAll($select);    
	}
	
	public function getYears($materialtype=1){
	   $select = $this->db->select()
	                      ->from('shopcoins',array('year'=>'distinct(year)'))
	                      ->where("(shopcoins.materialtype=? or shopcoins.materialtypecross & pow(2,?))",$materialtype)
	                      ->where('year>0')
	                      ->order('year desc');    
	   $select = $this->byAdmin($select); 
	   return $this->db->fetchAll($select);       
	}
	
	
    /*	
	}	
?>
	
	public function countRows($seachArray){
		$where = $this->parseSearchArray($seachArray);
		if($where){
			$where = 'Where '.$where;
		}
		$sql = "select count(*) as number from History_Text
				$where";
		
		$res = $this->db->fetchAll($sql);	
		return $res[0]['number'];
	}
	
	
	public function migrationHistory(){
		$select = $this->db->select()             
					 ->from('HistoryText',array('count(id)'));
		if(!$this->db->fetchOne($select)){
			$sql = "(select FROM_UNIXTIME(his_datetime) as `datetime`, his_text,his_id, editor_id, 0 as editor_name  from History_Text)  union  (select `datetime`, his_text, 0 as  editor_id, 0 as his_id, editor_name	from History_Text_LU) order by `datetime` desc;";
	
			foreach ($this->db->fetchAll($sql) as $row){			
				if($row["editor_name"]){				
					$select = $this->db->select()             
						 ->from('liveuser_users',array('guid'))
						 ->where("handle =?",$row["editor_name"]);
		 			$row['editor_id'] = $this->db->fetchOne($select);
		 			if(!$row['editor_id']) continue;
				}
				
				$insert_array = array('datetime' => $row["datetime"],
								  'his_text'     =>$row["his_text"],
								  'his_id'     =>$row["his_id"],
								  'editor_id'  => $row["editor_id"]);
				$res = $this->db->insert('HistoryText',$insert_array);			
			}
		}	
	}	
	
	
	public function getHistoryText($orders,$ofset,$limit,$seachArray=array()){		
		$where ="";
		if($seachArray){
			$where = $this->parseSearchArray($seachArray);
			if($where){
				$where = 'WHERE '.$where;
			}
		}
		if($orders){
			$orders = "order by ".$orders; 
		}
		if($ofset||$limit) {
			$limitrool= "limit ".(integer)$ofset.",$limit";
		}
		$sql = "select *, handle as editor_name from HistoryText
				left join liveuser_users on guid=editor_id
				$where		
				$orders 				
				$limitrool";
		$result = $this->db->fetchAll($sql,2);
		return $result;

	}
	
// использовлась в старом менеджере до перехода на liveuser
	public function getEditor_OLD($editor_id){
		$sql = "SELECT editor_login FROM Editors
				WHERE editor_id = $editor_id";
	
		$result = $this->db->fetchAll($sql,2);
		return $result[0]['editor_login'];
	}
	
	public function getEditor($editor_id){
		if($editor_id == '0'){
			return 'dis';
		}
		$select = $this->db->select()             
					 ->from('liveuser_users',array('handle'));
   		$select->where("guid =?",$editor_id);
 		$editor_info = $this->db->fetchOne($select);

 		return ($editor_info)?$editor_info:$editor_id;
	}
	
	public  function parseSearchArray($seachArray=array()) { 
                $newSearchArray = array();
		foreach ($seachArray as $key=>$row){
			if(trim($row['value'])){
				$newSearchArray[] = "$key LIKE ".$this->db->quote("%".trim($row['value'])."%");	
			}			
		}		
		return implode(" AND ", $newSearchArray);
	} */
}
?>