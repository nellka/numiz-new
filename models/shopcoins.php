<?php


class model_shopcoins extends Model_Base
{	
    public $id;
    public $user_id;
    public $last_name; 
    public static  $reservetime = 18000; 
       	
	public function __construct($db,$user_id=0,$nocheck=0){
	    parent::__construct($db);
	    $this->user_id = $user_id;
	    $this->nocheck = $nocheck;
	    $this->arraynewcoins = Array(1=>date('Y')-2,2=>date('Y')-1,3=>date('Y'));
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
		
	public function showedWith($id,$rows99){
        $select = $this->db->select()
	                      ->from('shopcoins',array('*',
                                    'pgroup'=>"if(shopcoins.group=".$rows99['group'].",10,0)",
                                    'pname' =>"if(shopcoins.name='".$rows99['name']."',2,0)",
                                    'pmetal'=>"if(shopcoins.metal='".$rows99['metal']."',3,0)",
                                    'pyear' =>"if(abs(shopcoins.year-".intval($rows99['year']).")<=10,4,0)",
                                    'pdetails'=>"if((shopcoins.details='".$rows99['details']."' and trim(shopcoins.details)<>''),1,0)",
                                    "ptheme"=>"if(shopcoins.theme & ".$rows99['theme'].",1,0)", 
                                    "pmaterialtype"=>"if(shopcoins.materialtype='".$rows99['materialtype']."',2,0)",
                                    "concat(shopcoins.name,shopcoins.group)"))
	                      ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name'))
	                      ->where("shopcoins.check=1 and shopcoins.shopcoins<>?",$id)
	                      ->where("shopcoins.parent<>?",$rows99['parent'])
	                      ->where("shopcoins.price>=?",intval($rows99['price']/3))
	                      ->where("shopcoins.price<=?",intval($rows99['price']*3))
	                      ->group('shopcoins.parent')
	                      ->order('(pgroup+pname+pyear++pmetal+pdetails+ptheme+pmaterialtype) desc')
	                      ->limit(3);  	
	   return $this->db->fetchAll($select);
	}
	public function is_already_described($coin_id){
	    $select = $this->db->select()
	                      ->from('user_describe_log',array('coin_id'))
	                      ->where('coin_id =?',$coin_id);  	
	    return   $this->db->fetchOne($select)?1:0;              
	  
    }
	
	public function getCoinsrecicle($id){
	    $select = $this->db->select()
	                      ->from('shopcoinsrecicle')
	                      ->where('shopcoins=?',$id)
	                      ->limit(1);  		              
    	return $this->db->fetchRow($select);
	}
	
	
	public  function getRelated($id){
	    $select = $this->db->select()
                  ->from('shopcoins')
                  ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name','ggroup'=>'group.groupparent'))
                  ->join(array('productrelation'),'productrelation.shopcoins_relation=shopcoins.shopcoins')
                  ->where('shopcoins.shopcoins=?',$id)
                  ->where('shopcoins.`check`=1')
                  ->limit(10)
                  ->order(array("materialtype", "group.name", "shopcoins.name"));  
        return $this->db->fetchAll($select);              
	}
	
	public  function getRelatedByGroup($id,$name,$item_id){
		/*$sql_pi = "select shopcoins.*,g.name as gname from shopcoins, `group` as g where shopcoins.`check`=1 and shopcoins.`group` = `g`.`group` 
		and shopcoins.`group` = '".$rows_main['group']."' and trim(shopcoins.`name`) = trim('".$rows_main['name']."') limit 10;";
		*/
	
	    $select = $this->db->select()
                  ->from('shopcoins')
                  ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name','ggroup'=>'group.groupparent'))
                  ->where('shopcoins.group=?',$id)
                  ->where('shopcoins.shopcoins<>?',$item_id)
                   ->where('trim(shopcoins.`name`) =?',trim($name))
                  ->where('shopcoins.`check`=1')
                  ->limit(10);  
                  
        return $this->db->fetchAll($select);              
	}
	
	public function getItem($id,$with_group=false){		       	
	    $select = $this->db->select()
                  ->from('shopcoins')
                  ->where('shopcoins.shopcoins=?',$id)
                  ->limit(1);  
    	if($with_group){
    		 $select->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name','ggroup'=>'group.groupparent'));
        }
        return $this->db->fetchRow($select);
	}
	
	// check if user is in allowed group to buy coin
	public function isInRerservedGroup($id) {
		if($this->user_id){
			 $select = $this->db->select()
	                  ->from('shopcoinsreserved',array('user'))
	                  ->where('shopcoins=?',$id)
	                  ->where('user=?',$this->user_id);  		
			return $this->db->fetchOne($select)?true:false;
		}
		return false;
	}

	public function getBuyStatus($id,$can_see=false,$ourcoinsorder=array(),$shopcoinsorder=0){
		$item = $this->getItem($id);
		$reservedForSomeUser = false;		
		$reservedForSomeGroup =  $item['timereserved'] > time() ; // group, lower priority than personal
		$isInRerservedGroup = null;
		$buy_status = 0;
		$reserved_status = 0;
		if($item["check"] == 0) {		
			return array('buy_status'=>9,'reserved_status'=>0);			
		}
		
		if($this->user_id && $reservedForSomeGroup && !$reservedForSomeUser) {
			$isInRerservedGroup = $this->isInRerservedGroup($id);
		}
		
		$reserveamount = 0;
	    $statusshopcoins = 0;
		$reserveuser = 0;
		$reservealluser = 0;
		//ввожу 			
	    //для наборов монет, цветных, банкнотов, мелочи и наборов надо проверять что зарезервировано
		if (in_array($item["materialtype"],array(7,8,6,2,4))) {			
			//получаем уже зарезервированные монеты
			$result_amount = $this->getReserved($id,self::$reservetime);

			foreach ($result_amount as $rows_amount){				
				if ( $rows_amount["reserve"] > 0 && ( time() - (int) $rows_amount["reserve"] < self::$reservetime ) ) { 					
					$reservedForSomeUser = ( $rows_amount["reserve"] > 0 && ( time() - (int) $rows_amount["reserve"] < self::$reservetime ) );
					
					if ($reservealluser < $rows_amount["reserve"]) $reservealluser=$rows_amount["reserve"];
					
					$reserveamount++;
					
					if ($rows_amount["reserve"] > 0 and $rows_amount["reserveorder"] == $shopcoinsorder) {
					
						if ($reserveuser < $rows_amount["reserve"]) 
							$reserveuser=$rows_amount["reserve"];
						
						$statusshopcoins = 1;
					}
				}
			}
			
			$statusopt = 0;			

			if ($item['price1'] && $item['amount1'] && ($item['amount'] -$reserveamount)>=$rows['amount1']) {
				$statusopt = 1;		
			} elseif (($item['amount'] - $reserveamount)>1)	{
				$statusopt = 1;	
			}	
			if (!$reserveuser && $reservealluser) $reserveuser=$reservealluser;		
			
			if ($statusshopcoins) {
			    $buy_status = 2;
			} elseif ( ($reservedForSomeUser || (!$this->user_id && $reservedForSomeGroup) || (false === $isInRerservedGroup)) && $reserveamount>=$rows["amount"]){
			     $buy_status = 3;
			} elseif($statusopt>0) {				
				if (sizeof($ourcoinsorder) and in_array($rows["shopcoins"], $ourcoinsorder)) {
					$buy_status = 8;
				} elseif ($can_see) {
				    $buy_status = 6;
				}						                
			} elseif ($can_see&&$item["check"] == 1 || $item["check"] == 50 || ($this->user_id==811 && $item["check"] >3)){
				//var_dump($tpl['shop']['MyShowArray'][$i]['buy_status']);
				$buy_status = 7;
			}		
		} else {				
			$reservinfo = '';
			$reservedForSomeUser = ( $item["reserve"] > 0 && ( time() - (int) $item["reserve"] < self::$reservetime )); //personal
			
			if (time() - (int) $item["reserve"] < self::$reservetime  and $item["reserveorder"] == $shopcoinsorder){
				$buy_status = 2;
			} elseif ( $reservedForSomeUser || (!$can_see && $reservedForSomeGroup) || (false === $isInRerservedGroup) ) {
				$buy_status = 3;
				
				if ($item['doubletimereserve'] > time() && $can_see>0 && $this->user_id== $item['userreserve']) {
					$buy_status = 4;
				} elseif($item['timereserved']>$item['reserve'] && $isInRerservedGroup && $item['reserve']>0 && $item['doubletimereserve'] < time()) {
					if ($can_see && ($item["check"] == 1 || $item["check"] == 50 || ($this->user_id==811 && $item["check"] >3))){
						$buy_status = 5;
					}						
				} elseif ($item['timereserved']<$item['reserve'] && $item['doubletimereserve'] < time() && $this->user_id) {
					$buy_status = 5;						
				}
			} elseif ($item['doubletimereserve'] > time() && $this->user_id != $item['userreserve']){
				$buy_status = 3;
			} elseif ($can_see&&$item["check"] == 1 || $item["check"] == 50 || ($this->user_id==811 && $item["check"] >3)){
			    $buy_status = 7;
			}			 
		}
		
		if ($reservedForSomeUser) {
			$reserved_status = 1;
			if (time() - (int) $item["reserve"] >= (self::$reservetime)  || $item["reserveorder"] != $shopcoinsorder  && $item['relationcatalog']>0 && $this->user_id)
				$reserved_status = 2;
		} elseif($reservedForSomeGroup) {
			if($isInRerservedGroup) {
				$reserved_status = 3;
			}	else {
				$reserved_status = 4;
				if ( $item['relationcatalog']>0 && $this->user_id)
					$reserved_status = 5;
			}			
		} elseif ($item['doubletimereserve'] > time() && $this->user_id != $item['userreserve']) {
			$reserved_status = 6;
			if ($item['relationcatalog']>0 && $this->user_id) $reserved_status = 7;
		}
		if ($item['doubletimereserve'] > time() && $this->user_id == $item['userreserve'] && $this->user_id){
			if ( $reservedForSomeUser || (!$this->user_id && $reservedForSomeGroup) || (false === $isInRerservedGroup) )
					$reserved_status = 8;
				else
					$reserved_status = 9;		
		}			
		return array('buy_status'=>$buy_status,'reserved_status'=>$reserved_status/*,'reservedForSomeUser'=>$reservedForSomeUser,'reservedForSomeGroup'=>$reservedForSomeGroup*/);			
	}
	public  function getMyOrderdetails($shopcoinsorder){
		$select = $this->db->select()
                      ->from('orderdetails')
                     ->where("`order`=?",$shopcoinsorder)
                     ->where("date > ?",time() - self::$reservetime);
       return $this->db->fetchAll($select);      
	}
	
	//получаем все монеты по родителю - для клика на "подобные"
	public function coinsWithParentDetails($id,$materialtype,$page, $items_for_page){	
	     $select = $this->db->select()                  
                  ->join(array('g'=>'group'),'s.group=g.group',array('gname'=>'g.name'));   
                     
	    if ($materialtype==7 || $materialtype==8 || $materialtype==6 || $materialtype==4 || $materialtype==2){        
		   $select->from(array('s'=>'shopcoins'))
		          ->where('s.shopcoins=?',$id);
	    } else {
			$select->from(array('s'=>'shopcoins'),array('*','sr'=>'if(s.realization=0,0,1)'));
			$select->where('s.parent=?',$id);
			$select->order(array("s.check asc","s.dateinsert desc","s.shopcoins"));
			$select->limitPage($page, $items_for_page);
	    }	    	   	
	   
		$select = $this->byAdmin($select,'s');   
		//echo  $select->__toString();
    	return $this->db->fetchAll($select);
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
	
	//число товаров для показа на странице типа show
    public function countForShow($materialtype,$parent){
        if ($materialtype==7 || $materialtype==8 || $materialtype==6 || $materialtype==4 || $materialtype==2){ 
           $select = $this->db->select()
                       ->from($this->table,array('amount'=>'if (amount>10,10,amount)'))
                       ->where('shopcoins="?" or parent="?"',$parent);         
        } else {
            $select = $this->db->select()
                       ->from($this->table,array('amount'=>'count(*)'))
                       ->where('parent="?"',$parent);     
        }
        
        $select = $this->byAdmin($select); 
			  // echo $select->__toString();
		return $this->db->fetchOne($select);
    }
    
    //число товаров для вывода на страницах каталога магазина
	public function countAllByParams($materialtype,$WhereParams=array(),$searchid='',$yearsearch=''){
	    $searchname = '';
	    $select = $this->db->select()
		               ->from($this->table,array('count(*)'));
 
		if(isset($WhereParams["newcoins"])) {
			 $select->where('shopcoins.materialtype in(1,4,7,8) and shopcoins.year in('.implode(",",$this->arraynewcoins).')');  
			
		} else {
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
	   } */else {/*
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
    	   }*/
	   } 
//echo $select->__toString();
       return $this->db->fetchOne($select);       
	}
	public function getPopular($limit=4,$params = array()){ 
	   $select = $this->db->select()
                      ->from('shopcoins')
                      ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name'))
                      ->where("shopcoins.check=1")
                      ->order('rand()')
                      ->limit($limit);
       foreach ($params as $key=>$value){
       		$select->where("$key=?",$value);
       }
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
	   
	    if(isset($WhereParams["newcoins"])) {
			 $select->where('shopcoins.materialtype in(1,4,7,8) and shopcoins.year in('.implode(",",$this->arraynewcoins).')');  
			
		} else {
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
		}                  
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
	  
	  // echo $select->__toString();
       return $this->db->fetchAll($select);
	} 
	//получаем уже зарезервированные монеты
    public function getReserved($id){
         $select = $this->db->select()
                      ->from('helpshopcoinsorder')
                     ->where("shopcoins=?",$id)
                     ->where("reserve > ?",time() - self::$reservetime);
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
           
            die('$is_revaluation');
       } elseif ($is_new){           
            $select->where("shopcoins.materialtype in(1,4,7,8)")
                   ->where("shopcoins.year in(".implode(",",$this->arraynewcoins).")");	
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
	
	protected function byAdmin($select,$alias='shopcoins'){
	    if($this->user_id==811||$this->user_id==309236) {
	       if(!$this->nocheck){
	           $select->where("$alias.check=1 or ($alias.check>3 and $alias.check<20)");
	       } else {
	           $select->where("$alias.check>3 and $alias.check<20");
	       }
	   }  else {
	       $select->where("$alias.check=1");
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
	
	public function getMarktmp($id){   
	     $select = $this->db->select()
	                      ->from('catalogshopcoinsrelation')
	                      ->where('shopcoins=?',$id);	
	    return  $this->db->fetchRow($select);
	}
	
	public function addShopcoinsmark($data){   
	    return  $this->db->insert('shopcoinsmark',$data);
	}
	public function addShopcoinsreview($data){   
	    return  $this->db->insert('shopcoinsreview',$data);
	}
	//получаем пользователей и оценки
	public function getMarks($id){    
        $matkitem = array();
		$matkitem['id'] =$id;
				
	    $rows_marktmp = $this->getMarktmp($id);
	    
	    $select = $this->db->select()
	                      ->from('shopcoinsmark')
	                      ->where('shopcoinsmark.check=1')
	                      ->group('shopcoinsmark');
	    if($rows_marktmp&&$rows_marktmp['catalog']){
	        $select->where("shopcoinsmark.catalog='{$rows_marktmp['catalog']}' or shopcoins='$id'");
	    } else {
	        $select->where('shopcoins=?',$id);
	    }	   
	    $result_mark = $this->db->fetchAll($select);
	    
	    $usermarkis = 0;
    	$markusers = 0;
    	$marksum = 0;
    	foreach ($result_mark as $rows_mark) {
    		$markusers ++;
    		$marksum += $rows_mark['mark'];
    		if ($rows_mark['user'] ==$this->user_id)
    			$usermarkis++;
    	}    	    	
		
        $matkitem['marksum'] = $marksum;
        $matkitem['markusers'] =$markusers;
        $matkitem['usermarkis'] =$usermarkis;        
    	return $matkitem;
	
	}
	
	//обзоры монеты	
	public function getReviews($id,$user_id=0){
	    $reviewitem = array();
		$reviewitem['id'] =$id;
	    $rows_marktmp = $this->getMarktmp($id);

	    $select = $this->db->select()
                      ->from('shopcoinsreview')
                      ->join(array('user'),'user.user=shopcoinsreview.user',array('fio'=>'user.fio'))
                      ->where("trim(shopcoinsreview.review)<>'' and shopcoinsreview.user=user.user and shopcoinsreview.check=1")
                      ->group('shopcoinsreview')
                      ->order('shopcoinsreview.dateinsert desc');  	
	    if($rows_marktmp&&$rows_marktmp['catalog']){
	        $select->where("catalog='{$rows_marktmp['catalog']}' or shopcoins='$id'");
	    } else {
	        $select->where('shopcoins=?',$id);
	    }    
        if($user_id){
	         $select->where('shopcoinsreview.user=?',$user_id);
	    }
	    $result_review = $this->db->fetchAll($select);

    	$userreviewis = 0;
    	$reviewusers = array();
    	$nnnn = 0;
    	foreach ($result_review as $rows_review) {	
    		if ($nnnn <10)
    			$reviewusers[$nnnn] = $rows_review;
    		
    		if ($rows_review['user'] == $this->user_id)
    			$userreviewis++;
    			
    		$nnnn++;
    	}
        $reviewitem['userreviewis'] = $userreviewis;	
        $reviewitem['reviewusers'] = $reviewusers;  
	    
    	return $reviewitem;
	}
	public function getAdditionalReviews(){
		$sql = "select `order`.order as shopcoins, user.fio, `user`.phone as uphone, `user`.email, `order`.*,date as dateinsert ,  	ReminderComment as review, if(`order`.mark=2 and `order`.markadmincheck=0,2,if(`order`.mark=2 and `order`.markadmincheck=2,1,0)) as omark 
		         from `order`, user 
		         where `order`.`check` = '1' and (trim( ReminderComment ) <> '' or mark=2) 
		         and order.user = user.user and date>'1049918400' and `order`.`mark`='1' 
		         order by omark desc, `order`.ReminderCommentDate desc, `order`.date desc limit 0,20";
		
		
		return  $this->db->fetchAll($sql);  
	}
	public function getSeries($s_id){
		$select = $this->db->select()
              ->from('shopcoinsseries',array('name'))
              ->where("shopcoinsseries=?",$s_id);
        return  $this->db->fetchOne($select);    
	
	}
	
	public function getSeries($s_id){
		$select = $this->db->select()
              ->from('shopcoinsseries',array('name'))
              ->where("shopcoinsseries=?",$s_id);
        return  $this->db->fetchOne($select);    
	
	}
	
	public function getCatalognewName($group){
    	$sql = "select trim(catalognew.name) from catalognew,`group` where catalognew.group=group.group and lower(trim(`group`.name))=lower('$group') group by trim(catalognew.name);";
    	return  $this->db->fetchRow($sql);   
	}
	
	
}
?>