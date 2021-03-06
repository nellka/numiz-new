<?php

class model_shopcoins extends Model_Base
{	
    public $id;
    public $user_id;
    public $materialtype; 
    protected $categoty_type; 

    const NEWCOINS = 1;
	const REVALUATION = 3;
	const SEARCH = 5;
    const BASE = 0;  
    public static  $reservetime = 18000; 
    const SECTION_NEWCOINS=100;
    const SECTION_REVALUATION=200;
        
    public function __construct($db,$user_id=0,$nocheck=0){
	    parent::__construct($db);
	    $this->user_id = $user_id;
	    $this->shortshow = 0;
	    $this->nocheck = $nocheck;
	    $this->materialtype = 1;
	    $this->mycoins = 0;
	    $this->categoty_type = self::BASE;
	    $this->arraynewcoins = Array(1=>date('Y')-2,2=>date('Y')-1,3=>date('Y'));
	}
	
	public function getShortShow(){       
        return $this->shortshow;
	}
	
	public function setShortShow($s){       
        $this->shortshow = $s;
        if($s){

            $this->db->delete('myfullcoinslist','dateinsert<'.(time()-3600));            
            $select  =  $this->db->select()
                  ->from('myfullcoinslist',array('count(user_id)'))                  
                  ->where('user_id=?',$this->user_id);
		    $count = $this->db->fetchOne($select);
		    //если нет записей, то получаем все купленные ранее		    
		    if(!$count){		        
		        try{
    		        $this->getMyShopCoinsIDs();
    		        $select = $this->db->select()
    			               ->from('mycoins',array('shopcoins'))
    			               ->joinLeft(array('c'=>'catalogshopcoinsrelation'),'c.shopcoins = mycoins.shopcoins',array('catalog'))
    			               ->where('user =?',$this->user_id);
        	         $mycoins = $this->db->fetchAll($select);
        	         $ids = array();
        	         $catalogIds = array();
        	         //echo mktime(true)."<br>";
        	         
        	         foreach ($mycoins as $coins){    	            
        	             $ids[] = $coins['shopcoins'];
        	             if($coins['catalog']&&!in_array($coins['catalog'],$catalogIds)){
        	                 $catalogIds[] = $coins['catalog'];
        	                
        	             }    	              
        	         }      	        
        	         
        	         $l = 500;
        	         while (count($catalogIds)>0) {
        	            //echo mktime(true)."<br>";
        	         	$output =  array_slice($catalogIds, 0, $l);  
        	         	$catalogIds = array_slice($catalogIds, $l);  
        	         	
        	         	$select = $this->db->select()
            			               ->from(array('c'=>'catalogshopcoinsrelation'),array('shopcoins'))
            			               ->join(array('s'=>'shopcoins_search'),'s.shopcoins=c.shopcoins',array())
            			               ->where('catalog in('.implode(',',$output).')');
            			               
    			         $shopcoins = $this->db->fetchAll($select); 
    			        
    			         //echo mktime(true)." - start foreach<br>";
    			         $new_ids = array_map(function($val){return $val['shopcoins'];},$shopcoins);
    			         $ids = array_merge($ids, $new_ids);
    			        /*foreach ($shopcoins as $coin) {
        		              if(!in_array($coin['shopcoins'],$ids)){
        		                  $ids[] = $coin['shopcoins'];
        		           *   }			              
    			        }*/
        	         	
        	         	//echo mktime(true)." - end foreach<br>";
        	         }	  	         
        	         
        	         $time = time();   
        	         $added = array();  
        	         
        	         $i = 0;
    
    	            $sqltop = 'insert into myfullcoinslist values '  ; 
    	            $sql = array();
    	            //echo mktime(true)." - end foreach<br>";     
    	          	foreach ($ids as $key=>$id){		       	    
    		       	     if(!in_array($id,$added)){
    		       	         $added[] = $id;
        		       	     /*$data = array('user_id'    => $this->user_id,
        		       	                   'shopcoins'  => $id,
        		       	                   'dateinsert' =>$time);
        		       	     $this->db->insert('myfullcoinslist',$data);*/
    		       	         $sql[] ="(".$this->user_id.",$id,$time) "; 		       	         
    		       	         $i++;
    		       	         if($i>5000){ 
    		       	             $result_sql = $sqltop.implode(",",$sql);
                                 $this->db->query($result_sql);
    		       	             $sql = array();  
    		       	             $i=0;   	             
    		       	         }    		       	     
    		       	     } 
        	         }
        	         if($sql){
                        $result_sql = $sqltop.implode(",",$sql);
                        $this->db->query($result_sql);
        	         }	
		        } catch (Exception $e)	{
		            var_dump($e->getMessage());
		            die();
		        }       	 
		    }		
        }
	}
	
	public function getMycoins(){       
        return $this->mycoins;
	}
	
	public function setMycoins($mycoins=0){ 
	    $this->getMyShopCoinsIDs();      
        return $this->mycoins = $mycoins;
	}
	
	function lockTablesForOrder(){
        $this->db->getConnection()->exec('LOCK TABLE shopcoins WRITE,shopcoins_search WRITE,shopcoins_search_details WRITE, helpshopcoinsorder WRITE,shopcoinsend WRITE, catalogshopcoinssubscribe WRITE;');
    }
    
    public function getCategoryType(){       
        return $this->categoty_type;
	}
	
    public function setMaterialtype($type=1){  
        $this->materialtype = $type;     
	}    

    public function setCategoryType($type){  
        $this->categoty_type = $type;     
	}    
	
	public function countByParams($sql){
	   // $this->_getSelect($select) );	    
	    // = "Select count(*) from shopcoins $where;";
	    //echo $sql."<br>";
    	//$result=mysql_query($sql);
    	return $this->db->fetchOne($sql);
	}
		
	public function showedWith($id,$rows99){
		$rows99['details'] = '';
		$select  =  $this->db->select()
                  ->from('shopcoins_details',array('details'))                  
                  ->where('catalog=?',$rows99['shopcoins']);
		$details = $this->db->fetchOne($select);
		if($details){
			$rows99['details'] = $details;
			$rows99['details'] = str_replace('\\', '',$rows99['details'] );
		}
        $select = $this->db->select()
	                      ->from(array('s'=>'shopcoins'),array('*',
                                    'pgroup'=>"if(s.group=".$rows99['group'].",10,0)",
                                    'pname' =>"if(s.nominal_id='".$rows99['nominal_id']."',2,0)",
                                    'pmetal_id'=>"if(s.metal_id='".$rows99['metal_id']."',3,0)",
                                    'pyear' =>"if(abs(s.year-".intval($rows99['year']).")<=10,4,0)",
                                    'pdetails'=>"if((shopcoins_details.details='".$rows99['details']."' and trim(shopcoins_details.details)<>''),1,0)",
                                    "ptheme"=>"if(s.theme & ".$rows99['theme'].",1,0)",
                                    "pmaterialtype"=>"if(s.materialtype='".$rows99['materialtype']."',2,0)",
                                    //"concat(s.name,s.group)"
						  ))
	                      ->join(array('group'),'s.group=group.group',array('gname'=>'group.name'))
	                      ->join(array('shopcoins_details'),'s.shopcoins=shopcoins_details.catalog')
	                      ->where("s.check=1 and s.shopcoins<>?",$id)
	                      ->where("s.parent<>?",$rows99['parent'])
	                      ->where("s.price>=?",intval($rows99['price']/3))
	                      ->where("s.price<=?",intval($rows99['price']*3))
	                      ->group('s.parent')
	                      ->order('(pgroup+pname+pyear++pmetal_id+pdetails+ptheme+pmaterialtype) desc')
	                      ->limit(3);
		if($this->user_id==352480){
			//var_dump($rows99);
			//echo $select->__toString();
		}
	   return $this->db->fetchAll($select);
	}
	public function is_already_described($coin_id){
	    $select = $this->db->select()
	                      ->from('user_describe_log',array('coin_id'))
	                      ->where('coin_id =?',$coin_id);  	
	    return   $this->db->fetchOne($select)?1:0;              
	  
    }
    
	//��������� ������� � ���������
    public function setCoinDescription($data,$id){
    	$this->updateRow($data,"shopcoins = $id");
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
	
	public  function getRelatedByGroup($id,$name='',$item_id=0){
		/*$sql_pi = "select shopcoins.*,g.name as gname from shopcoins, `group` as g where shopcoins.`check`=1 and shopcoins.`group` = `g`.`group` 
		and shopcoins.`group` = '".$rows_main['group']."' and trim(shopcoins.`name`) = trim('".$rows_main['name']."') limit 10;";
		*/
	
	    $select = $this->db->select()
                  ->from('shopcoins')
                  ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name','ggroup'=>'group.groupparent'))
                  ->where('shopcoins.group=?',$id)                
                  ->where('shopcoins.`check`=1')
                  ->limit(10)
                  ->order(array('year desc', 'dateinsert desc'));  
        if($item_id)  $select->where('shopcoins.shopcoins<>?',$item_id); 
          
        if($name)  $select->where('trim(shopcoins.`name`) =?',trim($name)); 
          
        return $this->db->fetchAll($select);              
	}
	
	public function getItem($id,$with_group=false){		
	    if(!(int)$id) return false;      	
	    $select = $this->db->select()
                  ->from('shopcoins')
                  ->where('shopcoins.shopcoins=?',$id)
                  ->limit(1);  
    	if($with_group){
    		 $select->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name','ggroup'=>'group.groupparent'));
        }
        if($this->user_id==352480){
        	//echo $select->__toString();
        }
        return $this->db->fetchRow($select);
	}
	
	public function getCompareItem($id,$data,$by_year=true){		
	    if(!(int)$id) return false;      	
	    $select = $this->db->select()
                  ->from('shopcoins')
                  ->where('shopcoins.shopcoins<>?',$id)
                  ->where('shopcoins.group=?',$data['group'])
                  ->where('shopcoins.nominal_id=?',$data['nominal_id'])
                  ->where('shopcoins.check= 1')
                  ->limit(5)  
    	          ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name','ggroup'=>'group.groupparent'))
    	          ->order('price asc');
       
        if($by_year&&$data['year']){
               $select->where('shopcoins.year=?',$data['year']) ;  
        }
        
        return $this->db->fetchAll($select);
	}
	
	public function getGroupItem($id){	
		if(!$dataGroup = $this->cache->load("group_".$id)){       	
		    $select = $this->db->select()
	                  ->from('group')
	                  ->where('group.group=?',$id)
	                  ->limit(1);  
	        $dataGroup =  $this->db->fetchRow($select);
	        if($this->user_id==352480){
	        	//echo $select->__toString();
	        }
	        $this->cache->save($dataGroup, "group_".$id);
		}
		
		return $dataGroup;
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

	public function getItemAmount($id,$can_see=false,$ourcoinsorder=array(),$shopcoinsorder=0){
		$item = $this->getItem($id);
		if($item["check"] == 0) {		
			return 0;			
		}
		
		$reservedForSomeUser = false;		
		$reservedForSomeGroup =  $item['timereserved'] > time() ; // group, lower priority than personal
		$isInRerservedGroup = null;
		
		if($this->user_id && $reservedForSomeGroup){
			$isInRerservedGroup = $this->isInRerservedGroup($id);
		}

		$reserveamount = 0;
			
	    //для наборов монет, цветных, банкнотов, мелочи и наборов надо проверять что зарезервировано
		if (in_array($item["materialtype"],array(7,8,6,2,4))) {			
			//получаем уже зарезервированные монеты
			$result_amount = $this->getReserved($id,self::$reservetime);

			foreach ($result_amount as $rows_amount){			
				$reserveamount++;				
				if ($rows_amount["reserve"] > 0 and $rows_amount["reserveorder"] == $shopcoinsorder) {
					$reserveamount--;
				}
			}	
					
            return $item['amount'] -$reserveamount;		
		} else {				
			$reservinfo = '';
			$reservedForSomeUser = ( $item["reserve"] > 0 && ( time() - (int) $item["reserve"] < self::$reservetime )); //personal			
			if (time() - (int) $item["reserve"] < self::$reservetime  and $item["reserveorder"] == $shopcoinsorder){
				return $item['amount'];
			} elseif ( $reservedForSomeUser || (!$can_see && $reservedForSomeGroup) || (false === $isInRerservedGroup) ) {
				return 0;
			} elseif ($item['doubletimereserve'] > time() && $this->user_id != $item['userreserve']){
				return 0;
			} elseif ($can_see&&$item["check"] == 1 || $item["check"] == 50 || ($this->user_id==811 && $item["check"] >3)){
			    return $item['amount'];
			}			 
		}	
	}
	
	public function getBuyStatus($id,$can_see=false,$ourcoinsorder=array(),$shopcoinsorder=0,$item = array()){
		
		if(!count($item)) $item = $this->getItem($id);
		
		$reservedForSomeUser = false;		
		$reservedForSomeGroup =  $item['timereserved'] > time() ; // group, lower priority than personal
		$isInRerservedGroup = null;
		$buy_status = 0;
		$reserved_status = 0;
		
		if($item["check"] == 0) {		
			return array('buy_status'=>9,'reserved_status'=>0);			
		}
		
		if($this->user_id && $reservedForSomeGroup) {
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
			    			
				$reservedForSomeUser = true;
				
				if ($reservealluser < $rows_amount["reserve"]) $reservealluser=$rows_amount["reserve"];
				
				$reserveamount++;
				
				if ($rows_amount["reserve"] > 0 and $rows_amount["reserveorder"] == $shopcoinsorder) {
				
					if ($reserveuser < $rows_amount["reserve"]) 
						$reserveuser=$rows_amount["reserve"];
					
					$statusshopcoins = 1;
				}
			}
			
			$statusopt = 0;			

			if ($item['price1'] && $item['amount1'] && ($item['amount'] -$reserveamount)>=$item['amount1']) {
				$statusopt = 1;		
			} elseif (($item['amount'] - $reserveamount)>1)	{
				$statusopt = 1;	
			}	
			
			if (!$reserveuser && $reservealluser) $reserveuser = $reservealluser;
					
			     	
			if ($statusshopcoins) {
			    $buy_status = 2;
			} elseif ( ($reservedForSomeUser || (!$this->user_id && $reservedForSomeGroup) || (false === $isInRerservedGroup)) && $reserveamount>=$item["amount"]){
			     $buy_status = 3;
			} elseif($statusopt>0) {				
				if (sizeof($ourcoinsorder) and in_array($item["shopcoins"], $ourcoinsorder)) {
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
			//} elseif ($can_see&&$item["check"] == 1 || $item["check"] == 50 || ($this->user_id==811 && $item["check"] >3)){
			} elseif ($can_see&&$item["check"] == 1 || $item["check"] == 50 || $item["check"] >3){
			    if (($item['amount'] - $reserveamount)>1)	{
    				$buy_status = 6;
    			} else {
			         $buy_status = 7;
    			}
			}			 
		}
		       
       if ($reservedForSomeUser) {
       	
           if($item["amount"]>$reserveamount){
              $reserved_status = 0;
           } else $reserved_status = 1;	

			if ($item["reserveorder"] != $shopcoinsorder  && $item['relationcatalog']>0 && $this->user_id){				
			    $reserved_status = 2;
			    if($buy_status==6&&($item["amount"]>$reserveamount)) $reserved_status = 0;
			} elseif ($item["reserveorder"] != $shopcoinsorder  && $item['relationcatalog']>0 && !$this->user_id){
				$reserved_status = 1;
			}
      
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
		if($this->user_id==352480){
        	//var_dump(array('buy_status'=>$buy_status,'reserved_status'=>$reserved_status/*,'reservedForSomeUser'=>$reservedForSomeUser,'reservedForSomeGroup'=>$reservedForSomeGroup*/));
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
	public function coinsParents($ParentArray){	
	    /*SELECT shopcoins.*, if((shopcoins.realization=0 or shopcoins.dateinsert>".($timenow-4*24*3600)."),0,1) as param FROM shopcoins WHERE (shopcoins.check =1 or shopcoins.`check`>3) and parent in (".implode(",", $ParentArray).") GROUP BY shopcoins.parent order by shopcoins.`check` asc, param asc, shopcoins.dateinsert desc*/
	     $select = $this->db->select()   
	                        ->from(array('s'=>'shopcoins'),array('*','param'=>'if(s.realization=0 or s.dateinsert>'.(time()-4*24*3600).',0,1)'));
		 $select->where("parent in (".implode(",", $ParentArray).")");
		 $select->where('s.check =1 or s.check>3');
		 $select->order(array("s.check asc","param asc","s.dateinsert desc"));
		 $select->group('s.parent');    
		 
		 return $this->db->fetchAll($select);
	}
	public function countChilds($ParentID){	
	    /*select count(*) from shopcoins where (`check`='1' or `check`>3) and parent='".$rows["parent"]."';*/
	    $select = $this->db->select()   
	                      ->from(array('s'=>'shopcoins'),array('count(*)'))
		                  ->where("s.parent =?",$ParentID);
		$select->where('s.check =1 or s.check>3');
		
		return $this->db->fetchOne($select);
	}
	
	
	
	public function countAll(){
        $select = $this->db->select()
		               ->from($this->table,array('count(*)'))
		               ->where('shopcoins.check=1');
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
	
	public function findByIds($id_array= array()){
         $select = $this->db->select()
		               ->from($this->table)
		               ->where("shopcoins in (".implode(",", $id_array).")");		 
    	 return $this->db->fetchAll($select);
	}
	
	public function getLastProducts($id_array= array()){
         $select = $this->db->select()
		               ->from($this->table)
		               ->join(array('g'=>'group'),'shopcoins.group=g.group',array('gname'=>'g.name'))  
		               ->where("shopcoins in (".implode(",", $id_array).")")
		               ->where('shopcoins.check=1')
		               ->limit(10);		 
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
    
    protected function setMaterialtypeSelect($select,$WhereParams = array(),$alias='shopcoins'){
        if($this->getCategoryType()==self::NEWCOINS ) {
			 $select->where("$alias.materialtype in(1,4,7,8) and ".$alias.'.year in('.implode(",",$this->arraynewcoins).')'); 	
		} elseif($this->getCategoryType()==self::REVALUATION ){
		       $select->where(" $alias.datereprice>0 and $alias.dateinsert>0");
		} else {
			if($this->materialtype == 2){
    	       $select->where("$alias.amount > 0");     	       
    	   } 
    	   if ($this->materialtype==10){
    	   		//$select->where("($alias.materialtype='".$this->materialtype."' and $alias.amountparent > 0)  or $alias.materialtype='8'"); 		
                $select->where("($alias.materialtype='".$this->materialtype."' and $alias.amountparent > 0)"); 	
    	   } elseif ($this->materialtype==1){
    	     	$select->where("($alias.materialtype='".$this->materialtype."' and $alias.amountparent > 0) or $alias.materialtypecross =18  or $alias.materialtype='8'"); 		
    	   }  elseif ($this->materialtype==7){
    	     	$select->where("$alias.materialtype='".$this->materialtype."' or $alias.materialtypecross =144 or $alias.materialtypecross = 128 "); 

    	   } elseif ($this->materialtype==4){
    	     	$select->where("$alias.materialtype='".$this->materialtype."' or $alias.materialtypecross =144"); 

    	   } else {
    	        $select->where("$alias.materialtype=? ",$this->materialtype); 
    	   }
		}
		//$select->where("$alias.group<>790"); 
		return $select;
    }

    public function myCoinsRequest(){
    	/*
    	select shopcoins.*
    from `shopcoins`, catalogshopcoinsrelation, catalogshopcoinssubscribe
    WHERE shopcoins.materialtype = '1' and shopcoins.`check`='1'
    and catalogshopcoinsrelation.shopcoins = shopcoins.shopcoins
    and catalogshopcoinssubscribe.catalog = catalogshopcoinsrelation.catalog
    and catalogshopcoinssubscribe.user = '".$cookiesuser."'
    order by shopcoins.dateinsert desc
    limit 200
    	*/
    	if(!$myCoins = $this->cache->load("myCoins_".$this->user_id)){
    	 	$select = $this->db->select()
		               ->from($this->table,array('shopcoins'))
		               ->join(array('catalogshopcoinsrelation'),'catalogshopcoinsrelation.shopcoins=shopcoins.shopcoins',array())
		               ->join(array('catalogshopcoinssubscribe'),'catalogshopcoinsrelation.catalog=catalogshopcoinssubscribe.catalog',array())
		               ->where('catalogshopcoinssubscribe.user =?',$this->user_id)
		               ->where('shopcoins.materialtype = 1 and shopcoins.check=1');
		    $myCoins = $this->db->fetchAll($select);   
		    $this->cache->save($myCoins, "myCoins_".$this->user_id);   	    
    	}
    	
		return $myCoins;
    }
   

	 
    public function getMyShopCoinsIDs(){
        
    	/*$sql = "select s.shopcoins from shopcoins as s, 
    	`order` as o, orderdetails as od where s.materialtype".($materialtype==1||$materialtype==8?" in(1,8)":"=$materialtype")." and s.shopcoins=od.catalog and od.order=o.order and o.check=1 and od.status=0 and o.user=".intval($cookiesuser).";";
    	*/
    	//$table_name = $this->user_id."_".date('Y-m-d',time());

		$select = $this->db->select()
			               ->from('mycoins',array('count(*)'))
			               ->where('user =?',$this->user_id);
    	$is_mycoins_exist = $this->db->fetchOne($select);

        if(!$is_mycoins_exist){
    	 	$select = $this->db->select()
		               ->from(array('s'=>$this->table),array('shopcoins'))
		               ->join(array('od'=>'orderdetails'),'s.shopcoins=od.catalog',array())
		               ->join(array('o'=>'order'),'od.order=o.order',array())
		               ->where('o.user =?',$this->user_id)
		               //->where('s.materialtype in (1,8)')
		               ;           
		    $myCoins = $this->db->fetchAll($select);
        	
        	$shopcoins_id = array();
        	foreach ($myCoins as $v){
        		$shopcoins_id[] = $v['shopcoins'];
        	}       
        	
            if($shopcoins_id){	
            	 $sql = "INSERT INTO  mycoins
                SELECT  ".$this->user_id." AS user, shopcoins, price,  `group` , YEAR, dateinsert,  `check` , number, materialtype, parent, materialtypecross, nominal_id, metal_id, condition_id,amount,amountparent,datereprice,dateinsert,theme,novelty 
                FROM  `shopcoins` WHERE shopcoins in (".implode(",",$shopcoins_id).");";        	
                $this->db->query($sql);   
            }     
        }
		return $myCoins;
    }
    
    //число товаров для вывода на страницах каталога магазина
	public function countAllByParams($WhereParams=array()){	
		//var_dump($WhereParams['catalognewstr']);
	    $searchname = '';
	    $select = $this->db->select()
		               ->from(array('s'=>'shopcoins_search'),array('count(*)'));
        
		if(!isset($WhereParams['catalognewstr'])&&!$this->mycoins){
			$select = $this->setMaterialtypeSelect($select,$WhereParams,'s');
			$select = $this->byAdmin($select,'s'); 	 
			$select = $this->byShortShow($select,'s');   
        } elseif ($this->mycoins){
            //мои монеты
            $select = $this->db->select()
		               ->from(array('s'=>'mycoins'),array('count(*)'))
				       ->where("s.user=?",$this->user_id);

        } else {
        	//получаем мои монеты
        	$select->where('materialtype = 1');
        	//$select = $this->setMaterialtypeSelect($select,$WhereParams);
        	$myCoinsRequest = $this->myCoinsRequest();
        	if(!$myCoinsRequest) return false;
        	$shopcoins_id = array();
        	foreach ($myCoinsRequest as $v){
        		$shopcoins_id[] = $v['shopcoins'];
        	}       
        	$select->where('shopcoins in ('.implode(",",$shopcoins_id).')');        	
        }
	
			
		if (isset($WhereParams['pricestart'])) {
        	$select->where("s.`price` >= ?",floatval($WhereParams['pricestart']));
        }
        if (isset($WhereParams['price'])&&$WhereParams['price']) {
        	$select->where("s.price in (".implode(",",$WhereParams['price']).")");
        }
        if (isset($WhereParams['bydate'])) {
            $select = $this->getByDate($select,$WhereParams['bydate']);
        }
        
        if (isset($WhereParams['nominals'])) {        	
        	$select->where("s.nominal_id in (".implode(",",$WhereParams['nominals']).")");        	
        }
        if (isset($WhereParams['priceend'])) {        	
        	$select->where("s.`price` <=?",floatval($WhereParams['priceend']));
        }

        if (isset($WhereParams['theme'])) {
        	 $whereTheme = array();
            foreach ($WhereParams['theme'] as $theme){
                $whereTheme[] = "(s.theme='".pow(2,$theme)."' or s.theme & ".pow(2,$theme).">0)";
            }
            if($whereTheme) {
                $select->where("(".implode(' or ',$whereTheme).')');
            }
        }
        if (isset($WhereParams['searchname'])) {   
        	 $nominalIds = $this->searchInTable('shopcoins_search_name',array($WhereParams['searchname']));   

             if($nominalIds) {
             	$select->where("s.nominal_id in (".implode(",",array_keys($nominalIds)).")");	
             } else {
             	$select->where("s.nominal_id ='hgfh'");	
             }
        }
        
        if (isset($WhereParams['series'])){
        	$select->where("s.series in (".implode(",",$WhereParams['series']).")");
        }
        if (isset($WhereParams['year_p'])) {
        	$where_year = array();
        	//var_dump($WhereParams['year']);
        	foreach ($WhereParams['year_p'] as $year_int){
        		if($year_int[0]>0&&$year_int[1]>0&&$year_int[1]<date('Y',time())){
        			$where_year[] = "(`year` >={$year_int[0]} and `year` <={$year_int[1]})";
        		}elseif($year_int[0]==0&&$year_int[1]>0&&$year_int[1]<date('Y',time())){
        			$where_year[] = "(`year` >0 and `year` <={$year_int[1]})";
        		} elseif ($year_int[0]>0){
        			$where_year[] = "(`year` >={$year_int[0]})";
        		} else {
        			$where_year[] = "(`year` <={$year_int[1]})";
        		}        		
        	}
        	//var_dump($where_year);
        	$select->where("(".implode(" or ",$where_year).")");
        }

        if (isset($WhereParams['year'])) {        	
        	$select->where("s.year in (".implode(",",$WhereParams['year']).")");
        }

        if (isset($WhereParams['group'])) {
        	$select->where("s.`group` in (".implode(",",$WhereParams['group']).")");
        }
        if (isset($WhereParams['metal'])) {              	
        	$select->where("s.metal_id in (".implode(",",$WhereParams['metal']).")");
        }
        if (isset($WhereParams['condition'])) {             	
        	$select->where("s.condition_id in (".implode(",",$WhereParams['condition']).")");
        }    	   
	   if($this->user_id==352480){
        	//echo $select->__toString();
        }	
       return $this->db->fetchOne($select);       
	}
	
	public function getPopular($limit=4,$params = array()){ 
	   $select = $this->db->select()
                      ->from(array('shopcoins'=>'shopcoins_search'))
                      ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name'))
                      ->where("shopcoins.check=1")
                      ->order('rand()')
                      ->limit($limit);
                      
       foreach ($params as $key=>$value){
       		$select->where("$key=?",$value);
       }
       return $this->db->fetchAll($select);
	} 
	 
	public function getSale($limit=4,$params = array()){ 
	   $select = $this->db->select()
                      ->from(array('shopcoins'=>'shopcoins_search'))
                      ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name'))
                      ->where("shopcoins.check=1")
                      ->where("shopcoins.datereprice>0 and shopcoins.dateinsert>0")
                      ->order('rand()')
                      ->limit($limit);
                      
       foreach ($params as $key=>$value){
       		$select->where("$key=?",$value);
       }
       return $this->db->fetchAll($select);
	}  
	
	public function getNew($limit=4){ 
	   $select = $this->db->select()
                      ->from(array('shopcoins'=>'shopcoins_search'))
                      ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name'))
                      ->where("shopcoins.check=1")
                      ->order(array('novelty desc','shopcoins desc'))
                      ->limit($limit);
       return $this->db->fetchAll($select);
	}  
	public function getNoveltyCoins(){
		  $select = $this->db->select()     
		 		  ->from('shopcoins')
	              ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name'))
                   ->where('novelty>0')
                   ->where('shopcoins.check=1')
                  ->order('novelty desc')                 
                  ->limit(3); 
          $select = $this->setMaterialtypeSelect($select);
          return   $this->db->fetchAll($select);
	}
	
	public function getFirstCoins($orderby=array(),$limit=1){
		  $select = $this->db->select()     
		 		 ->from('shopcoins')
	             ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name'))
                 ->where('novelty=0')
                  ->order('novelty desc')                 
                  ->limit($limit); 
          $select = $this->setMaterialtypeSelect($select);
          $select->order($orderby);
          $select->where("shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)");
          
          return   $this->db->fetchAll($select);
	}
	
	protected function getByDate($select,$bydate=0){
	    if($bydate){
	        $time_bydate = time()- ($bydate-0)*24*3600;             
        	$select->where("s.`dateinsert` >= ?",mktime(0,0,0,date('m',$time_bydate),date('d',$time_bydate),date('Y',$time_bydate)));	       
	    }
	    
	    return $select;
	}

	public function getItemsByParams($WhereParams=array(),$page=1, $items_for_page=30,$orderby=''){
		foreach ($orderby as &$order){	
			$order = str_replace('shopcoins.name','sn.name',$order);	 		
		 	$order = str_replace('shopcoins.','s.',$order);
		 	
		}
		 
	  //  var_dump($orderby);
       if(isset($WhereParams['coinssearch'])) $coinssearch = $WhereParams['coinssearch'];
	    //если нет ничего в поиске
	    //часть данных не инициализирую на первом этапе
	    $select = $this->db->select()
	                      ->from(array('s'=>'shopcoins'))
	                      ->join(array('group'),'s.group=group.group',array('gname'=>'group.name'))
	                      ->joinLeft(array('sn'=>'shopcoins_name'),'s.nominal_id=sn.id',array('name'=>'sn.name'))
	                      ;
	   
	    if(!isset($WhereParams['catalognewstr'])&&!$this->mycoins){
	    	$select = $this->db->select()	                      
	                      ->join(array('group'),'s.group=group.group',array('gname'=>'group.name'))
	                      ->joinLeft(array('sn'=>'shopcoins_name'),'s.nominal_id=sn.id',array('name'=>'sn.name'))
	                      ;
	        //if($this->user_id==352480&&$WhereParams['viewed_novelty']){
	        if($WhereParams['viewed_novelty']){
		        $select->from(array('s'=>'shopcoins_search'),array('shopcoins','price','group','year','date','check','number','materialtype','parent','materialtypecross','nominal_id','metal_id','condition_id','amount','amountparent','datereprice','dateinsert','dateorder','theme','novelty'=>'IF(shopcoins in('.implode(',',$WhereParams['viewed_novelty']).'), 0, novelty)'));
		    } else {		    	 
		      	$select->from(array('s'=>'shopcoins_search'));
      		}
	    
          
			$select = $this->setMaterialtypeSelect($select,$WhereParams,'s');
			$select = $this->byAdmin($select,'s'); 	
			$select = $this->byShortShow($select,'s');         
        } elseif ($this->mycoins){
            $select = $this->db->select()
	                      ->from(array('s'=>'mycoins'))
	                      ->join(array('group'),'s.group=group.group',array('gname'=>'group.name'))
	                      ->joinLeft(array('sn'=>'shopcoins_name'),'s.nominal_id=sn.id',array('name'=>'sn.name'))
				          ->where("s.user=?",$this->user_id);

        } else {
        	//получаем мои монеты
        	$select->where('materialtype = 1');
        	//$select = $this->setMaterialtypeSelect($select,$WhereParams);
        	$myCoinsRequest = $this->myCoinsRequest();
        	if(!$myCoinsRequest) return false;
        	$shopcoins_id = array();
        	foreach ($myCoinsRequest as $v){
        		$shopcoins_id[] = $v['shopcoins'];
        	}       
        	$select->where('shopcoins in ('.implode(",",$shopcoins_id).')');        	
        }        
	                     
	    if (isset($WhereParams['pricestart'])) {
        	$select->where("s.`price` >= ?",floatval($WhereParams['pricestart']));
        }
        
        if (isset($WhereParams['price'])&&$WhereParams['price']) {
        	$select->where("s.price in (".implode(",",$WhereParams['price']).")");
        }
        
        if (isset($WhereParams['priceend'])) {        	
        	$select->where("s.`price` <=?",floatval($WhereParams['priceend']));
        }
        
        if (isset($WhereParams['bydate'])) {
            $select = $this->getByDate($select,$WhereParams['bydate']);
        }
        
        if (isset($WhereParams['searchname'])) { 
        	$searchname = str_replace("'","",$WhereParams['searchname']);
        	$select->where('sn.name=?',$searchname);
        	  
            // $nominalIds = $this->searchInTable('shopcoins_search_name',array($WhereParams['searchname']));   
        	
            // $searchname =  $WhereParams['searchname'];   	
            // $select->where("s.nominal_id in (".implode(",",array_keys($nominalIds)).")");	
        }

        if (isset($WhereParams['theme'])) {
            $whereTheme = array();
            foreach ($WhereParams['theme'] as $theme){
                $whereTheme[] = "(s.theme='".pow(2,$theme)."' or s.theme & ".pow(2,$theme).">0)";
            }
            if($whereTheme) {
                $select->where("(".implode(' or ',$whereTheme).')');
            }        	
        }
        if (isset($WhereParams['nominals'])) {
        	$select->where("s.nominal_id in (".implode(",",$WhereParams['nominals']).")");
        }
        if (isset($WhereParams['series'])){
        	$select->where("s.series in (".implode(",",$WhereParams['series']).")");
        }
        if (isset($WhereParams['group'])) {
        	$select->where("s.`group` in (".implode(",",$WhereParams['group']).")");
        }
        if (isset($WhereParams['year_p'])) {
        	$where_year = array();
        	//var_dump($WhereParams['year']);
        	foreach ($WhereParams['year_p'] as $year_int){
        		if($year_int[0]>0&&$year_int[1]>0&&$year_int[1]<date('Y',time())){
        			$where_year[] = "(`year` >={$year_int[0]} and `year` <={$year_int[1]})";
        		}elseif($year_int[0]==0&&$year_int[1]>0&&$year_int[1]<date('Y',time())){
        			$where_year[] = "(`year` >0 and `year` <={$year_int[1]})";
        		} elseif ($year_int[0]>0){
        			$where_year[] = "(`year` >={$year_int[0]})";
        		} else {
        			$where_year[] = "(`year` <={$year_int[1]})";
        		}        		
        	}
        	$select->where("(".implode(" or ",$where_year).")");
        }

        if (isset($WhereParams['year'])) {        	
        	$select->where("s.year in (".implode(",",$WhereParams['year']).")");
        }

        if (isset($WhereParams['metal'])) {              	
        	$select->where("s.metal_id in (".implode(",",$WhereParams['metal']).")");
        }
        
        if (isset($WhereParams['condition'])) {             	
        	$select->where("s.condition_id in (".implode(",",$WhereParams['condition']).")");
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
	      // var_dump($orderby);
	     //  die();
	       $select->order($orderby);
	   }
	   if($items_for_page!='all'){
	        $select->limitPage($page, $items_for_page);
	   }  
	  if($this->user_id==352480){
        	echo $select->__toString()."<br>";
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
	    /*
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
		//echo $sql_tmp2."=sql_tmp2<br>";*/
			//����� ������
	}
	
	
	//группы для выборки
	public function getGroups($bydate=0){	
	    
	   if($this->mycoins) {
	       $select = $this->db->select()
	                      ->from(array('s'=>'mycoins'),array('group'=>'distinct(`group`)'))
	                      ->where('s.dateinsert>0')
	                     // ->where('s.group<>"790"')
			              ->where("s.user=?",$this->user_id)
	                       ->order('group desc');

	   } else {
	       $select = $this->db->select()
	                      ->from(array('s'=>'shopcoins_search'),array('group'=>'distinct(`group`)'))
	                       ->order('group desc');	   
	   	 	$select = $this->byAdmin($select,'s'); 
	   		$select=$this->setMaterialtypeSelect($select,array(),'s');
	   }
	   
	   if ($bydate){
            $select = $this->getByDate($select,$bydate);
       }
       
       if($this->user_id==352480){
        	//echo $select->__toString();
       }     
       return $this->db->fetchAll($select);       
	}
	//получаем данные о группах
	public function getGroupsDetails($ids = array(),$is_parent = false,$en=false){	
	    $select = $this->db->select()
	                      ->from('group');
	    if($ids) {
	        $select->where("`group` in (".implode(",", $ids).")");
	    } else {
	        $select->where("`group`=0");
	    }
	    if($is_parent)  $select->where("groupparent='0'");
	    
	    if($en){
	        $select->order('name_en asc');
	    } else 	$select->order('name asc');

        return $this->db->fetchAll($select);     
	}
	

	public function getGroupIdsToOrder($ids){	
	    $select = $this->db->select()
	                      ->from('group',array('group'))	
	                      ->where("`group` in (".implode(",", $ids).") or `groupparent` in(".implode(",",$ids).")");	 
        return $this->db->fetchAll($select);     
	}
	
	protected function byAdmin($select,$alias='shopcoins'){		
		if($this->shortshow){
			$select->where("$alias.check=1 or ($alias.check>3 and $alias.check<20)");
		} elseif($this->user_id==811) {
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
	
	protected function byShortShow($select,$alias='shopcoins'){
	    //$select->where("$alias.check=1 or ($alias.check>3 and $alias.check<20)");
	    if($this->shortshow){
	        $select->joinLeft(array('mf'=>'myfullcoinslist'),"$alias.shopcoins=mf.shopcoins and mf.user_id=".$this->user_id,array());
	        $select->where('mf.shopcoins IS NULL');
	    }	
	    return $select;
	}
	
	public function setDetails($id,$details){
		$data = array('catalog'=>$id,
                      'details'=>$details);
        $this->db->insert('shopcoins_details',$data);  
        return true;
	}

	public function getNominalId($title){
		$title = trim($title);
		if(!$title) return 0;
		
		$select = $this->db->select()
	                      ->from('shopcoins_name',array('id'))	                    
	                      ->where("name =?",$title);
	    $nominal_id =  $this->db->fetchOne($select); 
	    
	    if(!$nominal_id)    {
	    	 $data = array('name'=>$title);
             $this->db->insert('shopcoins_name',$data);
             $nominal_id = $this->db->lastInsertId('shopcoins_name');
	    }        
		return  $nominal_id;
	}
	
	public function getNominal($id){
		$select = $this->db->select()
	                      ->from('shopcoins_name',array('name'))	                    
	                      ->where("id =?",$id);
		return  $this->db->fetchOne($select);
	}
	
	public function getNominals($groups=array(),$bydate=0){
		
	    if(!$groups&&($this->getCategoryType()!=self::NEWCOINS)) return array();
	    	    
	   if($this->mycoins) {
	        $select = $this->db->select()
	                      ->from(array('s'=>'mycoins'),array('distinct(nominal_id)'))
	                      ->join(array('group'),'s.group=group.group',array())
	                      ->join('shopcoins_name', 'nominal_id=shopcoins_name.id',array('name'))	                      
						  ->where("s.user=?",$this->user_id)
	                      ->order('shopcoins_name.name asc');

	   } else {
	       $select = $this->db->select()
	                      ->from(array('s'=>'shopcoins_search'),array('distinct(nominal_id)'))
	                     // ->join(array('group'),'shopcoins.group=group.group',array())
	                      ->join(array('sn'=>'shopcoins_search_name'), 'nominal_id=sn.id',array('name'))	                      
	                      ->order('sn.name asc');  
	       $WhereParams['group']  = true;            
	       $select = $this->setMaterialtypeSelect($select,$WhereParams,'s');
	       $select = $this->byAdmin($select,'s'); 	 
	   }
	   if($this->mycoins&&$groups) {
	   		$select->where("group.group in (".implode(",",$groups).")");
	   } elseif($groups) $select->where("s.group in (".implode(",",$groups).")");
	   
	   if ($bydate){
            $select = $this->getByDate($select,$bydate);
       }
       
       $result = $this->db->fetchAll($select);
       
       foreach ($result as &$row){
    	    $number = (int) $row['name'];
    	    $string = str_replace($number,'<<<>>>',$row['name']);
    	    $n = number_format($number, 0, ',', '.');
    	    $row['name'] = str_replace('<<<>>>',$n, $string);        	    
       }  
    	       
	   return $result;    
	}
	
	public function getConditions($all=false,$groups=array(),$nominals=array(),$bydate=0){
	    $WhereParams['group'] = true;
	    
		$select = $this->db->select()
	                      ->from(array('s'=>'shopcoins_search'),array('distinct(condition_id)'))
	                      ->join('shopcoins_condition', 'condition_id=shopcoins_condition.id',array('name'))
	                      ->where('condition_id>0')
	                      ->order('shopcoins_condition.sortby asc') ; 
	   if($this->mycoins) {
	       $select = $this->db->select()
	                      ->from(array('s'=>'mycoins'),array('distinct(condition_id)'))
	                      ->join('shopcoins_condition', 'condition_id=shopcoins_condition.id',array('name'))
	                      ->where('condition_id>0')
						   ->where("s.user=?",$this->user_id)
	                      ->order('shopcoins_condition.sortby asc') ; 

        } elseif(!$all){
            $select=$this->setMaterialtypeSelect($select,$WhereParams,'s');      	   
        	$select = $this->byAdmin($select,'s'); 	   
        }	
        
	    if($groups){
           $select->where("`group` in (".implode(",",$groups).")");
        } 
        
        if ($bydate){
            $select = $this->getByDate($select,$bydate);
       }
        if($nominals){	       	
            $select->where("nominal_id in (".implode(",",$nominals).")");
	    }
    	    
	    if($this->user_id==352480){
        	//echo $select->__toString();
        }
	   	return $this->db->fetchAll($select);    
	}
	public function getMetalList(){
	    $select = $this->db->select()
	                      ->from(array('shopcoins_metal'))
	                      ->where('name<>""')             
	                      ->order('shopcoins_metal.name asc');	 
	    return $this->db->fetchAll($select);           
	}
	//получаем металы для выборки
	public function getMetalls($all=false,$groups=array(),$nominals=array(),$bydate=0){
	    
	    $WhereParams['group'] = true;
	    
	    $select = $this->db->select()
	                      ->from(array('s'=>'shopcoins_search'),array('distinct(metal_id)'))
	                      ->join('shopcoins_metal','shopcoins_metal.id=metal_id',array("name"))
	                      ->where('metal_id>0 and shopcoins_metal.name<>""')             
	                      ->order('shopcoins_metal.name asc');	                      
	    if($this->mycoins) {
	        $select = $this->db->select()
	                      ->from(array('s'=>'mycoins'),array('distinct(metal_id)'))
	                      ->join('shopcoins_metal','shopcoins_metal.id=metal_id',array("name"))
	                      ->where('metal_id>0 and shopcoins_metal.name<>""')
						  ->where("s.user=?",$this->user_id)
	                      ->order('shopcoins_metal.name asc');	   

	   } elseif(!$all){
    	   $select=$this->setMaterialtypeSelect($select,$WhereParams,'s');
    	   $select = $this->byAdmin($select,'s');    	   
	   }
	   
	   if($groups){
           $select->where("`group` in (".implode(",",$groups).")");
       }
       
       if ($bydate){
            $select = $this->getByDate($select,$bydate);
       }
        
       if($nominals){	       	
           $select->where("nominal_id in (".implode(",",$nominals).")");
	   }	   
	   
	   return $this->db->fetchAll($select);       
	}
	
	public function getYears($nominals=array(),$groups=array(),$bydate=0){
	    
	   $WhereParams['group'] = true;
	   
	   if($this->mycoins) {
	        $select = $this->db->select()
	                      ->from(array('s'=>'mycoins'),array('distinct(year)'))
						  ->where("s.user=?",$this->user_id)
	                      ->order('year desc');

	   } else {
    	   $select = $this->db->select()
    	                      ->from(array('s'=>'shopcoins_search'),array('year'=>'distinct(year)'))
    	                      ->order('year desc');   
    	   $select=$this->setMaterialtypeSelect($select,$WhereParams,'s'); 
    	   $select = $this->byAdmin($select,'s'); 
	   }
	   
	   if($nominals){	       	
            $select->where("nominal_id in (".implode(",",$nominals).")");
	   }
	   
	   if ($bydate){
            $select = $this->getByDate($select,$bydate);
       }
       
       if($groups){
           $select->where("`group` in (".implode(",",$groups).")");
       }
        if($this->user_id==352480){
        	//echo $select->__toString();
        }
	   return $this->db->fetchAll($select);       
	}
	
	public function getPrices($nominals=array(),$groups=array()){
	    
	   $WhereParams['group'] = true;
	   
	   if($this->mycoins) {
	        $select = $this->db->select()
	                      ->from(array('s'=>'mycoins'),array('distinct(price)'))
						  ->where("s.user=?",$this->user_id)
	                      ->order('price asc');

	   } else {
    	   $select = $this->db->select()
    	                      ->from(array('s'=>'shopcoins_search'),array('price'=>'distinct(price)'))
    	                      ->order('price asc');   
    	   $select=$this->setMaterialtypeSelect($select,$WhereParams,'s'); 
    	   $select = $this->byAdmin($select,'s'); 
	   }
	   
	   if($nominals){	       	
            $select->where("nominal_id in (".implode(",",$nominals).")");
	   }  
       
       if($groups){
           $select->where("`group` in (".implode(",",$groups).")");
       }      
	   return $this->db->fetchAll($select);       
	}
	//серии
	public function getFilterSeries($groups){
		$select = $this->db->select()
		      ->from('shopcoins',array('distinct(series)'))
              ->join('shopcoinsseries','shopcoins.series=shopcoinsseries',array('name'))
              ->where('series>0');
        if($groups){
           $select->where("shopcoins.group in (".implode(",",$groups).")");
        }
        $select=$this->setMaterialtypeSelect($select); 
	    $select = $this->byAdmin($select); 
	    if($this->user_id==352480){
        	echo $select->__toString();
        }
        return $this->db->fetchAll($select);    
	
	}
	public function getMaxPrice($groups=array(),$nominals=array(),$bydate=0){     
        if($this->mycoins) {
            $select = $this->db->select()
                          ->from(array('s'=>'mycoins'),array('max(price)'))
							->where("s.user=?",$this->user_id);

        } else {   
            $select = $this->db->select()
                       ->from(array('s'=>'shopcoins_search'),array('max(price)'));
            $select = $this->byAdmin( $select,'s');    	
            $select=$this->setMaterialtypeSelect($select,array(),'s');
        }
    	 if($groups){
    	      $select->where("`group` in (".implode(",",$groups).")");
    	 }
    	 if($nominals){	       	
             $select->where("nominal_id in (".implode(",",$nominals).")");
    	 }
    	 
    	 if ($bydate){
             $select = $this->getByDate($select,$bydate);
         }
         
    	 if($this->user_id==352480){
        	//echo $select->__toString();
        }
	     return $this->db->fetchOne($select);
    }
    
    public function getMinPrice($groups=array(),$nominals=array(),$bydate=0){
         if($this->mycoins) {
            $select = $this->db->select()
                          ->from(array('s'=>'mycoins'),array('min(price)'))
						   ->where("s.user=?",$this->user_id);

         } else {   
             $select = $this->db->select()
    		               ->from(array('s'=>'shopcoins_search'),array('min(price)'));
    		 $select = $this->byAdmin( $select,'s');    	
        	 $select = $this->setMaterialtypeSelect($select,array(),'s');
         }
    	 if($groups){
    	      $select->where("`group` in (".implode(",",$groups).")");
    	 }
    	 if($nominals){	       	
              $select->where("nominal_id in (".implode(",",$nominals).")");
    	 }
    	 if ($bydate){
            $select = $this->getByDate($select,$bydate);
         }
    	 if($this->user_id==352480){
        	//echo $select->__toString();
        }
	     return $this->db->fetchOne($select);
    }
    
    public function getMinYear($groups=array(),$nominals=array(),$bydate=0){
         if($this->mycoins) {
            $select = $this->db->select()
                          ->from(array('s'=>'mycoins'),array('min(year)'))
    		               ->where('year>0')
							->where("s.user=?",$this->user_id);

         } else {   
             $select = $this->db->select()
    		               ->from(array('s'=>'shopcoins_search'),array('min(year)'))
    		               ->where('year>0');
    		 $select = $this->byAdmin( $select,'s');    	
        	 $select = $this->setMaterialtypeSelect($select,array(),'s');
         }
         
    	 if($groups){
    	      $select->where("`group` in (".implode(",",$groups).")");
    	 }
    	 if ($bydate){
            $select = $this->getByDate($select,$bydate);
         }
         
    	 if($nominals){	       	
              $select->where("nominal_id in (".implode(",",$nominals).")");
    	 }
    	 
    	 if($this->user_id==352480){
        	//echo $select->__toString();
        }
	     return $this->db->fetchOne($select);
    }
    
    public function getMaxYear($groups=array(),$nominals=array(),$bydate=0){
         if($this->mycoins) {
            $select = $this->db->select()
                          ->from(array('s'=>'mycoins'),array('max(year)'))
						  ->where("s.user=?",$this->user_id);

         } else {   
             $select = $this->db->select()
    		               ->from(array('s'=>'shopcoins_search'),array('max(year)'));
    		 $select = $this->byAdmin( $select,'s');    	
        	 $select = $this->setMaterialtypeSelect($select,array(),'s');
         }
         
         if ($bydate){
            $select = $this->getByDate($select,$bydate);
         }
         
    	 if($groups){
    	      $select->where("`group` in (".implode(",",$groups).")");
    	 }
    	 if($nominals){	       	
              $select->where("nominal_id in (".implode(",",$nominals).")");
    	 }
    	 
    	 if($this->user_id==352480){
        	//echo $select->__toString();
        }
	     return $this->db->fetchOne($select);
    }
    
	public function getMarktmp($id){   
    	 if(!(int) $id) return false;
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
        
        if($id) {
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
        }       
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
		/*$sql = "select `order`.order as shopcoins, user.fio, `user`.phone as uphone, `user`.email, `order`.*,date as dateinsert ,  	ReminderComment as review, if(`order`.mark=2 and `order`.markadmincheck=0,2,if(`order`.mark=2 and `order`.markadmincheck=2,1,0)) as omark 
		         from `order`, user 
		         where `order`.`check` = '1' and (trim( ReminderComment ) <> '' or mark=2) 
		         and order.user = user.user and date>'1049918400' and `order`.`mark`='1' 
		         order by omark desc, `order`.ReminderCommentDate desc, `order`.date desc limit 0,8";*/
		$revieves = array();
		$sql = "select user.fio, `user`.phone as uphone, `user`.email, `order`.order as shopcoins,date as dateinsert ,  	ReminderComment as review, if(`order`.mark=2 and `order`.markadmincheck=0,2,if(`order`.mark=2 and `order`.markadmincheck=2,1,0)) as omark from `order`, user where `order`.`check` = '1' and (trim( ReminderComment ) <> '' or mark=2) and order.user = user.user and date>'1049918400' and `order`.`mark`='1' order by omark desc, `order`.ReminderCommentDate desc, `order`.date desc limit 0,8";
        $good = $this->db->fetchAll($sql);  
		foreach ($good as $row){
		    $revieves[] = $row;
		}
		$sql = "select user.fio, `user`.phone as uphone, `user`.email, `order`.order as shopcoins,date as dateinsert ,  	ReminderComment as review, if(`order`.mark=2 and `order`.markadmincheck=0,2,if(`order`.mark=2 and `order`.markadmincheck=2,1,0)) as omark from `order`, user where `order`.`check` = '1' and (trim( ReminderComment ) <> '' or mark=2) and order.user = user.user and date>'1049918400' and `order`.`mark`='2' order by omark desc, `order`.ReminderCommentDate desc, `order`.date desc limit 0,2;";
		$bad = $this->db->fetchAll($sql);  
		foreach ($bad as $row){
		    $revieves[] = $row;
		}
		return  $revieves;
	}
	//Возможные группы для описать монету
	public function getGroupsForDescribe(){
	    $select = $this->db->select()
              ->from('group')
              ->where("type='shopcoins'")
              ->where("`group` not in (667,937,983,997,1014,1015,1062,1063,1097,1106)")
              ->group('name');
        return  $this->db->fetchAll($select); 
	}
	//серии
	public function getSeries($s_id){
		$select = $this->db->select()
              ->from('shopcoinsseries',array('name'))
              ->where("shopcoinsseries=?",$s_id);
        return       $this->db->fetchOne($select);    
	
	}
	
	//следующая монета
	public function getNext($id,$materialtype){
		$select = $this->db->select()
              ->from($this->table)
              ->where('shopcoins >?',$id)
               ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name','ggroup'=>'group.groupparent'))
              ->limit(1)
              ->order('shopcoins ASC')
            ;              
		if($materialtype == 2){
	       $select->where('shopcoins.amount > 0');	       
	   	}      
	
	   if ($materialtype==1 ){
	       $select->where("(shopcoins.materialtype=1 and shopcoins.amountparent > 0) or shopcoins.materialtypecross =18"); 		            
	   } elseif ($materialtype==10){
	       $select->where("(shopcoins.materialtype=10 and shopcoins.amountparent > 0)"); 		            
	   } elseif ($materialtype==7){
	       $select->where("(shopcoins.materialtype=7  or shopcoins.materialtypecross =144 or  shopcoins.materialtypecross =128)"); 		            
	   } else {
	        $select->where("shopcoins.materialtype=?",$materialtype); 
	   }
	  
       return $this->db->fetchRow($select);  	
	}
	//предыдущая монета
	public function getPrevios($id,$materialtype){
		$select = $this->db->select()
              ->from($this->table)
              ->where('shopcoins <?',$id)
              ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name','ggroup'=>'group.groupparent'))
              ->limit(1)
              ->order('shopcoins DESC');              
		if($materialtype == 2){
	       $select->where('shopcoins.amount > 0');	       
	   	}      
	
	   if ($materialtype==1 ){
	       $select->where("(shopcoins.materialtype=1 and shopcoins.amountparent > 0) or shopcoins.materialtypecross =18"); 		            
	   } elseif ($materialtype==10){
	       $select->where("(shopcoins.materialtype=10 and shopcoins.amountparent > 0)"); 		            
	   } elseif ($materialtype==7){
	       $select->where("(shopcoins.materialtype=7  or shopcoins.materialtypecross =144 or  shopcoins.materialtypecross =128)"); 		            
	   } else {
	        $select->where("shopcoins.materialtype=?",$materialtype); 
	   }
       return $this->db->fetchRow($select);  	
	}
	//для номиналов
	public function getCatalognew($id){
	    
	    $select = $this->db->select()
              ->from('catalognew',array('distinct(name)'))
              ->where('`group`=?',$id)
              ->order('name DESC');    

        return $this->db->fetchAll($select);  	     
	}
	
	//для номиналов
	public function searchGroups($SearchTempStr){
	  
	    $select = $this->db->select()               
 	 	             ->from(array('g'=>'shopcoins_search_group'),array('distinct(g.name)','g.group','g.groupparent'));  

 		if($SearchTempStr){
 			$select->where("(g.name like '%".implode("%' or g.name like '%",$SearchTempStr)."%') or (g.name_en like '%".implode("%' or g.name_en like '%",$SearchTempStr)."%')");
 		}
 		if($this->user_id==352480){
        	//echo $select->__toString();
        }
 		//echo $select->__toString();
 		//$this->db->query("SET names 'utf8'");
        return $this->db->fetchAll($select);   
	}
	
	public function searchTable($table,$SearchTempStr=array()){	   
	    $select = $this->db->select()               
 	 	          ->from($table);
 		if($SearchTempStr){
 			$select->where("name like '%".implode("%' or name like '%",$SearchTempStr)."%'");
 		} 	
 		if($this->user_id==352480){
        	//echo $select->__toString();
        }
 		$ids = array();	
 		foreach ($this->db->fetchAll($select) as $row){
 		    $ids[] = $row['id'];
 		}
        return $ids;   
	}
	
	public function searchInTable($table,$SearchTempStr=array()){	   
	    $select = $this->db->select()               
 	 	          ->from($table);
 		if($SearchTempStr){
 			$select->where("name like '%".implode("%' or name like '%",$SearchTempStr)."%'");
 		} 	
		if($this->user_id==352480){
        	//echo $select->__toString();
        }
 		$data = array();
 		foreach ($this->db->fetchAll($select) as $row){
 		    $data[$row['id']] = $row['name'];
 		}
        return $data;   
	}
	
	public function searchParrentGroups($groups){
	
	    $select = $this->db->select()
               ->from(array('g'=>'shopcoins_search_group'),array('DISTINCT(g.group)'));    
		$select->where("g.groupparent in ('".implode(',',$groups)."')");
        return 	 $this->db->fetchAll($select);   
	}
	
	public function group_id_from_name($group_name){
		$select = $this->db->select()
              ->from('group',array('group.group'))
              ->where('group.name=?',$group_name)
              ->where("type='shopcoins'");    
        return $this->db->fetchOne($select); 	
	}
	public function relatedByOrder($orderData=array(),$groupIn = array()){	
        $wherein = array();
		foreach ($orderData as $key=>$value) {	
			switch ($value['materialtype']) {		
				case 1:
					$wherein[] = "(((shopcoins.materialtype in(1,10,12) and shopcoins.amountparent>0) or shopcoins.materialtype in(4,7,8,9)) and shopcoins.metal_id = '".$value['metal_id']."' and ABS(shopcoins.year-".$value['year'].") <= 10 )";
					break;
				case 2:
					$wherein[] = "(shopcoins.materialtype=2 and ABS(shopcoins.year-".$value['year'].") <= 10 )";
					break;
				case 3:
					$wherein[] = "(shopcoins.materialtype=3)";
					break;
				case 6:
					$wherein[] = "(shopcoins.materialtype=6)";
					break;
				case 4:
					$wherein[] = "(((shopcoins.materialtype in(1,10,12) and shopcoins.amountparent>0) or shopcoins.materialtype in(4,7,8,9)) and shopcoins.metal_id = '".$value['metal_id']."' and ABS(shopcoins.year-".$value['year'].") <= 10 )";
					break;
				case 5:
					$wherein[] = "(shopcoins.materialtype=3)";
					break;
				case 7:
					$wherein[] = "(((shopcoins.materialtype in(1,10,12) and shopcoins.amountparent>0) or shopcoins.materialtype in(4,7,8,9)) and shopcoins.metal_id = '".$value['metal_id']."' and ABS(shopcoins.year-".$value['year'].") <= 10 )";
					break;
				case 8:
					$wherein[] = "(((shopcoins.materialtype in(1,10,12) and shopcoins.amountparent>0) or shopcoins.materialtype in(4,7,8,9)) and shopcoins.metal_id = '".$value['metal_id']."' and ABS(shopcoins.year-".$value['year'].") <= 10 )";
					break;
				case 9:
					$wherein[] = "(((shopcoins.materialtype in(1,10,12) and shopcoins.amountparent>0) or shopcoins.materialtype in(4,7,8,9)) and shopcoins.metal_id = '".$value['metal_id']."' and ABS(shopcoins.year-".$value['year'].") <= 10 )";
					break;
				case 10:
					$wherein[] = "(((shopcoins.materialtype in(1,10,12) and shopcoins.amountparent>0) or shopcoins.materialtype in(4,7,8,9)) and shopcoins.metal_id = '".$value['metal_id']."' and ABS(shopcoins.year-".$value['year'].") <= 10 )";
					break;
				case 12:
					$wherein[] = "(((shopcoins.materialtype in(1,10,12) and shopcoins.amountparent>0) or shopcoins.materialtype in(4,7,8,9)) and shopcoins.metal_id = '".$value['metal_id']."' and ABS(shopcoins.year-".$value['year'].") <= 10 )";
					break;
				
			}
			$ArrayIn[] = $value['catalog'];
		}
		
		  $select = $this->db->select()
                  ->from('shopcoins')
                  ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name'))
                //  ->where('shopcoins.group=?',$id)
                  ->where("shopcoins.shopcoins not in(".implode(",",$ArrayIn).")")                  
                  ->where('shopcoins.`check`=1')
                  ->group('shopcoins.shopcoins')
                  ->limit(4)
                  ->order("rand()");
        if($wherein) $select->where("(".implode(" or ",$wherein).")") ;
        if($groupIn) $select->where("shopcoins.group in (".implode(",", $groupIn).")");      
        return $this->db->fetchAll($select);  	
	}
	
	public function migrateNominals(){

	     $select = $this->db->select()
                  ->from('shopcoins',array('distinct(name)'))
                  ->where('name<>"" and nominal_id=0')
                  ->limit(2000);
                  echo $select->__toString();
         foreach ($this->db->fetchAll($select) as $row){
             $select  = $this->db->select()
                                ->from('shopcoins_name',array('id'))
                                ->where('name=?',$row['name']);
             $n_id = $this->db->fetchOne($select);
             if(!$n_id){
                 $data = array('name'=>$row['name']);
                 $this->db->insert('shopcoins_name',$data);
                 $n_id = $this->db->lastInsertId('shopcoins_name');
             }
             $data = array('nominal_id'=>$n_id);
             $this->db->update('shopcoins',$data,"name='".$row['name']."'");             
         }                 
	}
	
	public function migrateMetal(){
	  
	    $select = $this->db->select()
                  ->from('shopcoins_old',array('distinct(metal)'))
                  ->where('metal<>"" and metal_id=0');
                                   
         foreach ($this->db->fetchAll($select) as $row){
            
             $select  = $this->db->select()
                                ->from('shopcoins_metal',array('id'))
                                ->where('name=?',$row['metal']);
             $n_id = $this->db->fetchOne($select);
             if(!$n_id){
                 $data = array('name'=>$row['metal']);
                 $this->db->insert('shopcoins_metal',$data);
                 $n_id = $this->db->lastInsertId('shopcoins_metal');
             }
             
             $select  = $this->db->select()
                                ->from('shopcoins_old',array('shopcoins'))
                                ->where('metal_id=0 and metal=?',$row['metal']);
           
             $ids = $this->db->fetchAll($select); 
             $ids_metal = array();
             foreach ( $ids as $id){
                 $ids_metal[] = $id['shopcoins'];
             }
             
             $data = array('metal_id'=>$n_id);
             
             $this->db->update('shopcoins',$data,"shopcoins in (".implode(',',$ids_metal).")"); 
             $this->db->update('shopcoins_old',$data,"shopcoins in (".implode(',',$ids_metal).")");             
       
         }        
	    
	}
	
    public function migrateCondition(){
        $select = $this->db->select()
                  ->from('shopcoins_old',array('distinct(`condition`)'))
                  ->where('`condition`<>"" and condition_id=0');
         foreach ($this->db->fetchAll($select) as $row){
             $select  = $this->db->select()
                                ->from('shopcoins_condition',array('id'))
                                ->where('name=?',$row['condition']);
             $n_id = $this->db->fetchOne($select);
             if(!$n_id){
                 $data = array('name'=>$row['condition']);
                 $this->db->insert('shopcoins_condition',$data);
                 $n_id = $this->db->lastInsertId('shopcoins_condition');
             }
             $select  = $this->db->select()
                                ->from('shopcoins_old',array('shopcoins'))
                                ->where('condition_id=0 and `condition`=?',$row['condition']);
                                //echo $select->__toString();
             $ids =  $this->db->fetchAll($select); 
            
             $ids_metal = array();
             foreach ( $ids as $id){
                 $ids_metal[] = $id['shopcoins'];
             }
             var_dump($ids_metal);
             $data = array('condition_id'=>$n_id);
//var_dump($data);
             $this->db->update('shopcoins',$data,"shopcoins in (".implode(',',$ids_metal).")"); 
             $this->db->update('shopcoins_old',$data,"shopcoins in (".implode(',',$ids_metal).")");          
             
            // die('condition');        
             /*
             $data = array('condition_id'=>$n_id);
             $this->db->update('shopcoins',$data,"`condition`='".$row['condition']."'");   */          
         }                 
        
    }
    
    public function migrateTheme(){
        
    }	
    
    public function getSeo($materialtype=0,$group=0,$nominal=0,$metal=0,$year=0){
        
    	//var_dump($materialtype,$group_data,$nominal_data);
    	$select = $this->db->select()
                  ->from('shopcoinsseotext')
                  ->where('active=1')
                  ->limit(1)
                 // ->order('rand()')
                  ;
        if($materialtype){
        	
        	if($materialtype=='newcoins'){
        		$select->where('materialtype=?',self::SECTION_NEWCOINS );
        	} else if($materialtype=='revaluation'){
        		$select->where('materialtype=?',self::SECTION_REVALUATION );
        	} else $select->where('materialtype=?',$materialtype);
        }
        $select->where('group_id = ?',$group);   
        $select->where('nominal_id = ?',$nominal); 
        $select->where('metal_id = ?',$metal);    
       // $select->where('year = ?',$year);     	
        /*
    	if($group_data&&$nominal_data){
        	//$select->where('group_id in ('.implode(",",$group_data).') and nominal_id in ('.implode(",",$nominal_data).')');
        	//$data = $this->db->fetchRow($select);
        	//if($data) return $data;
        } else if($group_data){
        	$select->where('group_id in ('.implode(",",$group_data).') and nominal_id=0');        	
        } else {
        	$select->where('group_id =0 and nominal_id=0');        	
        }
        
        if($metal_data) {
        	$select->where('metal_id in ('.implode(",",$metal_data).')');
        } else $select->where('metal_id =0');
           */      	
        $data = $this->db->fetchRow($select);
       
        if($this->user_id==811){
        	//echo "<br><br>".$select->__toString()."<br><br>";
        	//die('jjjj');
        }
        return $data;
	}
	
	public function keywordtexts($arraykeyword=array()){
	    if($arraykeyword){
    	    $sql = "select *,match(`keywords`,`name`,`text`) against('".implode(" ",$arraykeyword)."' in boolean mode) as `coefficient` from shopcoinsbiblio where match(`keywords`,`name`,`text`) against('".implode(" ",$arraykeyword)."' in boolean mode) order by `coefficient` desc, shopcoinsbiblio asc limit 3;";
    	
    	    return $this->getDataSql($sql);
    	}
    	return array();
	}
	
	public function getOtherMaterialData($group,$materialtype=1){
	    $data = array();
	    if(!$group||!$materialtype) return $data;
	    $sql = "select  *,group.name as gname from shopcoins,`group`  where shopcoins.group=group.group and shopcoins.check=1 and shopcoins.dateinsert<>0 and shopcoins.dateorder=0 and shopcoins.materialtype <> '".$materialtype."' and group.group = '$group'	group by shopcoins.parent order by rand() limit 5";
    	
    	return $this->getDataSql($sql);	    
	}
	
	public function deteteFromTemp($id){
		if((int)$id){
		    if($this->user_id==352480){
            	echo "deteteFromTemp";
            }
			//var_dump($id,'shopcoins='.$id);
			$this->deleteRow('shopcoins_search', '`shopcoins`="'.$id.'"');
			$this->db->delete('shopcoins_search_details', 'catalog="'.$id.'"');
		}	       
	}
	
	public function getParrentGroupsIds($group_id){
	    $GroupArray = array();
	    if((int)$group_id){
	        if($this->mycoins) {
    	       $select = $this->db->select()
    	                      ->from(array('s'=>'mycoins'),array('group'=>'distinct(s.group)'))
    	                      ->join(array('group'),'s.group=group.group',array('gname'=>'group.name'))                    
    	                      ->where('s.dateinsert>0')
    	                      //->where('s.group<>"790"')
				              ->where("s.user=?",$this->user_id)
    	                       ->order('group.name asc');	   	   		
    	   } else {
    	       $select = $this->db->select()
    	                      ->from(array('s'=>'shopcoins_search'),array('group'=>'distinct(s.group)'))
    	                       ->join(array('group'),'s.group=group.group',array('gname'=>'group.name'))
    	                       ->order('group.name asc');	   
    	   	 	$select = $this->byAdmin($select,'s'); 
    	   		$select=$this->setMaterialtypeSelect($select,array(),'s');
    	   }
	
	        $select->where('groupparent=?',(int)$group_id);
	        
            $groups = $this->db->fetchAll($select);
             
    	    
        	foreach ($groups as $rows_group){
        		$GroupArray[] = $rows_group['group'];
        	}
        	
	    } 
	    return $GroupArray;
	}
	
	public function setUserViporder($viporder=0){
		if(!$this->user_id||!(int)$viporder) return ;
		
		$select = $this->db->select()
           ->from('user_vipopders',array('viporder'))
           ->where("user_id=?",$this->user_id)
           ->where("viporder=?",(int)$viporder);	
           
         if(!$this->db->fetchOne($select)){
         	$data = array('user_id'=>$this->user_id,
         	              'viporder'=>(int)$viporder,
         	              'dateinsert'=>time());
         	$this->db->insert('user_vipopders',$data);             
         }            
	}
}

?>