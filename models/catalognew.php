<?php

class model_catalognew extends Model_Base
{	

    public $materialtype; 
    public static $detectorsID=1000;
        
    public function __construct($db){
	    parent::__construct($db);	   
	    $this->materialtype = 1;	   
	}	
	
	
    public function setMaterialtype($type=1){  
        $this->materialtype = $type;     
	}    
	public function getSeo($materialtype=0,$group_data=array(),$nominal_data=array()){
        
    	$select = $this->db->select()
                  ->from('catalog_seotext')
                  ->where('active=1')
                  ->limit(1);
        if($materialtype){        	
        	$select->where('materialtype=?',$materialtype);
        }
       
    	if($group_data&&$nominal_data){
        	$select->where('group_id in ('.implode(",",$group_data).') and nominal_id in ('.implode(",",$nominal_data).')');
        	$data = $this->db->fetchRow($select);
        	if($data) return $data;
        }
        
        if($group_data){
        	$select->where('group_id in ('.implode(",",$group_data).') and nominal_id=0');        	
        } else {
        	$select->where('group_id =0 and nominal_id=0');        	
        }
        
        $data = $this->db->fetchRow($select);
        
        
        return $data;
	}
	
	public function getItem($id,$with_group=false){	
		/* $sql = "select c.*, g.name as gname,  as  
		from catalognew as c, `group` as g, metal 
		where 
		c.catalog='$catalog' 
		and g.`group` = c.`group` 
		and c.metal = metal.metal 
		limit 1;";*/
		 	
	    if(!(int)$id) return false;   
	       	
	    $select = $this->db->select()
                  ->from('catalognew')
                  ->where('catalognew.catalog=?',$id)
                  //->join(array('metal'),'catalognew.metal=metal.metal',array('metal'=>'metal.name','metal_id'=>'metal.metal'))
                  ->limit(1);  
    	if($with_group){
    		 $select->join(array('group'),'catalognew.group=group.group',array('gname'=>'group.name','ggroup'=>'group.groupparent'));
        }
        
        return $this->db->fetchRow($select);
	}	
    
    //число товаров для вывода на страницах каталога магазина
    //,$searchid='',$yearsearch=''
	public function countAllByParams($WhereParams=array()){	
		$select = $this->db->select();
		
		$select->from(array('s'=>'catalognew'),array('count(*)'))
		      ->where("s.materialtype=?",$this->materialtype)
		      ->where("s.agreement >= 0"); 
		
		           
		$select = $this->byParams($select,$WhereParams);
		
        if (isset($WhereParams['nominals'])) {        	
        	$select->where("s.nominal_id in (".implode(",",$WhereParams['nominals']).")");        	
        }
        
        /*$searchname = '';        
		if (isset($WhereParams['searchname'])) {   
        	 $nominalIds = $this->searchInTable('shopcoins_search_name',array($WhereParams['searchname']));   

             if($nominalIds) {
             	$select->where("s.nominal_id in (".implode(",",array_keys($nominalIds)).")");	
             } else {
             	$select->where("s.nominal_id ='hgfh'");	
             }
        }
       
       */
		if (isset($WhereParams['year_p'])) {
			$where_year = array();
			//var_dump($WhereParams['year']);
			foreach ($WhereParams['year_p'] as $year_int){
				if($year_int[0]>0&&$year_int[1]>0&&$year_int[1]<date('Y',time())){
					$where_year[] = "(`yearstart` >={$year_int[0]} and `yearstart` <={$year_int[1]})";
				}elseif($year_int[0]==0&&$year_int[1]>0&&$year_int[1]<date('Y',time())){
					$where_year[] = "(`yearstart` >0 and `yearstart` <={$year_int[1]})";
				} elseif ($year_int[0]>0){
					$where_year[] = "(`yearstart` >={$year_int[0]})";
				} else {
					$where_year[] = "(`yearstart` <={$year_int[1]})";
				}
			}
			$select->where("(".implode(" or ",$where_year).")");
		}

		if (isset($WhereParams['year'])) {
			$select->where("s.yearstart in (".implode(",",$WhereParams['year']).")");
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
        if (isset($WhereParams['group'])&&$WhereParams['group']) {
        	$select->where("s.`group` in (".implode(",",$WhereParams['group']).")");
        }
        if (isset($WhereParams['metal'])) {              	
        	$select->where("s.metal in (".implode(",",$WhereParams['metal']).")");
        }
        if(($this->materialtype==1||$this->materialtype==8)&&isset($WhereParams['group'])&&$WhereParams['group']){
		    $select->join(array('catalogpositionname'),'catalogpositionname.catalogpositionname=s.catalogpositionname',array());
		}  	    
       /* if (isset($WhereParams['condition'])) {             	
        	$select->where("s.condition_id in (".implode(",",$WhereParams['condition']).")");
        }  */  	   
	  // if($this->user_id==352480){
        	echo "<!--".$select->__toString()."-->";
       // }	
       return $this->db->fetchOne($select);       
	}
		//,$searchid=''
	public function getItemsByParams($WhereParams=array(),$page=1, $items_for_page=30,$orderby=''){
	   /*
	    $sql_catalog = "select catalognew.*, group.name as gname, metal.name as mname ".($CounterSQL?",".$CounterSQL:"")." ".($group&&!$page&&$materialtype!=1&&$materialtype!=8?$groupselect:"")." 
	from catalognew, `group`, metal $fromtable ".(($materialtype==1||$materialtype==8)&&$group?",catalogpositionname":"")." 
	$where and catalognew.group=group.group  ".(($materialtype==1||$materialtype==8)&&$group?"and catalogpositionname.catalogpositionname=catalognew.catalogpositionname":"")."
	and catalognew.metal = metal.metal 
	".($group&&!$page&&$materialtype!=1&&$materialtype!=8?" order by group.groupparent,catalognew.group, param2,param1,catalognew.yearstart,catalognew.dateinsert desc":(($materialtype==1||$materialtype==8)&&$group?"order by group.groupparent,catalognew.group, catalogpositionname.position,catalognew.yearstart,catalognew.dateinsert desc":$orderby))." 
	$limit;";	    
	    */	    
		
		$select = $this->db->select();	
		
		$select->from(array('s'=>'catalognew'))
	      ->where("s.materialtype=?",$this->materialtype)
	      ->where("s.agreement >= 0"); 
		
		$select = $this->byParams($select,$WhereParams);		
		$select->joinleft(array('group'),'s.group=group.group',array('gname'=>'group.name'));
		//$select->joinleft(array('metal'),'s.metal=metal.metal',array('mname'=>'metal.name','metal_id'=>'metal.metal'));
		if(($this->materialtype==1||$this->materialtype==8)&&isset($WhereParams['group'])&&$WhereParams['group']){
		    $select->join(array('catalogpositionname'),'catalogpositionname.catalogpositionname=s.catalogpositionname',array());
		}  	              

	    /*
		
		 
	  //  var_dump($orderby);
       if(isset($WhereParams['coinssearch'])) $coinssearch = $WhereParams['coinssearch'];
	    //если нет ничего в поиске
	    //часть данных не инициализирую на первом этапе
	    $select = $this->db->select()
	                      ->from(array('s'=>'shopcoins'))
	                      ->join(array('group'),'s.group=group.group',array('gname'=>'group.name'))
	                      ->join(array('sn'=>'shopcoins_name'),'s.nominal_id=sn.id',array('name'=>'sn.name'));
	   
	    if(!isset($WhereParams['catalognewstr'])&&!$this->mycoins){
	    	$select = $this->db->select()
	                      ->from(array('s'=>'shopcoins_search'))
	                      ->join(array('group'),'s.group=group.group',array('gname'=>'group.name'))
	                      ->join(array('sn'=>'shopcoins_name'),'s.nominal_id=sn.id',array('name'=>'sn.name'));
	                      
			$select = $this->setMaterialtypeSelect($select,$WhereParams,'s');
			$select = $this->byAdmin($select,'s'); 	
			$select = $this->byShortShow($select,'s');         
        } elseif ($this->mycoins){
            $select = $this->db->select()
	                      ->from(array('s'=>'mycoins'))
	                      ->join(array('group'),'s.group=group.group',array('gname'=>'group.name'))
	                      ->join(array('sn'=>'shopcoins_name'),'s.nominal_id=sn.id',array('name'=>'sn.name'))
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
        */
	    
	    if (isset($WhereParams['nominals'])) {
        	$select->where("s.nominal_id in (".implode(",",$WhereParams['nominals']).")");
        }
        
        if (isset($WhereParams['group'])&&$WhereParams['group']) {
        	$select->where("s.`group` in (".implode(",",$WhereParams['group']).")");
        }
        if (isset($WhereParams['metal'])) {              	
        	$select->where("s.metal in (".implode(",",$WhereParams['metal']).")");
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

        if (isset($WhereParams['year_p'])) {
        	$where_year = array();
        	//var_dump($WhereParams['year']);
        	foreach ($WhereParams['year_p'] as $year_int){
        		if($year_int[0]>0&&$year_int[1]>0&&$year_int[1]<date('Y',time())){
        			$where_year[] = "(`yearstart` >={$year_int[0]} and `yearstart` <={$year_int[1]})";
        		}elseif($year_int[0]==0&&$year_int[1]>0&&$year_int[1]<date('Y',time())){
        			$where_year[] = "(`yearstart` >0 and `yearstart` <={$year_int[1]})";
        		} elseif ($year_int[0]>0){
        			$where_year[] = "(`yearstart` >={$year_int[0]})";
        		} else {
        			$where_year[] = "(`yearstart` <={$year_int[1]})";
        		}        		
        	}
        	$select->where("(".implode(" or ",$where_year).")");
        }

		if (isset($WhereParams['year'])) {
			$select->where("s.yearstart in (".implode(",",$WhereParams['year']).")");
		}

	   $select->order($orderby);

	   if($items_for_page!='all'){
	        $select->limitPage($page, $items_for_page);
	   }  
	
        //echo "<br><br>".$select->__toString()."<br><br>";


       return $this->db->fetchAll($select);
	} 	
	
	public function getShopcoinssubbscribe($user_id=0,$CatalogArray=array()){
	    /*$sql_shopcoinssubbscribe = "select * from 
		catalogshopcoinssubscribe 
		where user='$user' and catalog in (".implode(",", $CatalogArray).");";*/
	    $user_id = (int) $user_id;

	    if(!$user_id||!$CatalogArray) return array();
	    
	    $select = $this->db->select()	    
	                      ->from(array('s'=>'catalogshopcoinssubscribe'))
	                      ->where('user=?',$user_id)
	                      ->where("catalog in (".implode(",", $CatalogArray).")");
	   //echo $select->__toString()."<br>";
	   return    $this->db->fetchAll($select);	   
	}
	
	public function getAgreementCatalog($catalog= 0){
	    $catalog = (int) $catalog;
	    
	    if(!$catalog) return false;
	    
	    
	    $sql = "select cataloghistory.*, user.userlogin, user.star, user.user 
		from cataloghistory, catalogtransaction, user 
		where catalogtransaction.catalog='$catalog' 
		and catalogtransaction.transaction = cataloghistory.transaction
		and catalogtransaction.user = user.user
		and cataloghistory.agreementdate='0'
		and cataloghistory.field = 'all'
		limit 1";
	    
	    return $this->getRowSql($sql);
	}
	
	public function addYearPeriod($data = array()){
	    $this->db->insert('catalogyear',$data);
	    return   $this->db->lastInsertId('catalogyear'); 
	}
	
	public function addCatalogtransaction($catalog,$user_id){
        $user_id = (int) $user_id;
        $catalog = (int) $catalog;

        if(!$user_id||!$catalog) return false;
        
		$data = array('user'=>$user_id,
	                  'catalog' =>$catalog,
	                  'date'=>time());
      
	    $this->db->insert('catalogtransaction',$data);
	    return   $this->db->lastInsertId('catalogtransaction'); 
	}
	
	public function getReviews($catalog= 0){
	    $catalog = (int) $catalog;
	    
	    if(!$catalog) return false;
	
		$sql = "select catalogreview.*, user.userlogin, user.email, user.star, user.user
		from catalogreview,user where 
		catalog='$catalog' 
		and catalogreview.user = user.user 
		order by catalogreview.date desc
		limit 10;";
		return $this->getDataSql($sql);
	}
	
	public function getUsersInWork($catalog = 0){
	    $catalog = (int) $catalog;
	    
	    if(!$catalog) return false;
		//показываем всех пользователей, кто работал над монетой
		$sql = "select distinct user.* from catalogtransaction, user 
		where catalogtransaction.catalog='$catalog'
		and catalogtransaction.user = user.user limit 20;";
		return $this->getDataSql($sql);
	}
	
	public function addCataloghistoryagreement($cataloghistory,$agree,$user_id){
	
        $key = (int) $key;
        $user_id = (int) $user_id;
        $agree = (int) $agree;

        if(!$user_id||!$cataloghistory) return false;
		$data = array('user'=>$user_id,
	                  'cataloghistory' =>$cataloghistory,
	                  'agree'=>$agree);
      
	    $this->db->insert('cataloghistoryagreement',$data);
	    return   $this->db->lastInsertId('cataloghistoryagreement'); 
	}
    
	public function getCataloghistoryagreement($key,$user_id){
	    $key = (int) $key;
	    $user_id = (int) $user_id;
	    
	    if(!$key||!$user_id) return 0;
	    $select = $this->db->select()	    
	                      ->from(array('cataloghistoryagreement'))
	                      ->where('user=?',$user_id)
	                      ->where('cataloghistory=?',$key);

	    return $this->db->fetchRow( $select); 
	}
	
	public function getUserFromHistory($key){
	   if(!$key) return 0;
	   
	   $sql = "select catalogtransaction.user
				from cataloghistory, catalogtransaction
				where cataloghistory.cataloghistory = '$key'
				and cataloghistory.transaction = catalogtransaction.transaction;";
	   return $this->getRowSql($sql);
	   
	}
				
				
	public function getUsersInCataloghistory($catalog){
	    $catalog = (int) $catalog;
	    
	    if(!$catalog) return false;
	    
		$sql = "select distinct user.* 
		from catalogtransaction, cataloghistory, cataloghistoryagreement, user 
		where catalogtransaction.catalog = '$catalog'
		and cataloghistory.transaction = catalogtransaction.transaction
		and cataloghistoryagreement.cataloghistory=cataloghistory.cataloghistory
		and cataloghistoryagreement.user = user.user limit 20";
		
		return $this->getDataSql($sql);
	}
	
	public function getPrices($catalog=0){
	    $catalog = (int) $catalog;
	    
	    if(!$catalog) return false;
	    
    	$sql = "SELECT shopcoins.* ,group.name as gname
    	FROM  `catalogshopcoinsrelation` ,  `shopcoins` ,`group`
    	WHERE catalogshopcoinsrelation.catalog = '$catalog'
    	AND shopcoins.group = group.group 
    	AND catalogshopcoinsrelation.shopcoins = shopcoins.shopcoins order by shopcoins.dateinsert desc";
    	
    	return $this->getDataSql($sql);
	}	
	
	public function getOffers($catalog=0){
	    $catalog = (int) $catalog;
	    
	    if(!$catalog) return array();
		
		//теперь показываем предложения
		$sql = "select cataloghistory.*, user.userlogin, user.user 
		from cataloghistory, catalogtransaction, user 
		where catalogtransaction.catalog='$catalog' 
		and catalogtransaction.transaction = cataloghistory.transaction
		and catalogtransaction.user = user.user
		and cataloghistory.agreementdate='0'
		and cataloghistory.field <> 'all'
		order by cataloghistory.date desc;";
		return $this->getDataSql($sql);
	}
	
	public function getResultcicle($catalog=0){
	    $catalog = (int) $catalog;
	    
	    if(!$catalog) return array();
	    
	    $select = $this->db->select()	    
	                      ->from(array('catalogrecicle'))
	                      ->where('catalog=?',$catalog);

	   return    $this->db->fetchRow($select);	   
	}
	
	public function getCatalognewmycatalog($user_id=0,$CatalogArray=array()){
	    /*$$sql_shopcoinssubbscribe = "select * from 
		catalognewmycatalog 
		where user='$user' and catalog in (".implode(",", $CatalogArray).");";";;*/
	    $user_id = (int) $user_id;
	    
	    if(!$user_id||!$CatalogArray) return array();
	    
	    $select = $this->db->select()	    
	                      ->from(array('s'=>'catalognewmycatalog'))
	                      ->where('user=?',$user_id)
	                      ->where("catalog in (".implode(",", $CatalogArray).")");
	   //echo $select->__toString()."<br>";
	   return    $this->db->fetchAll($select);	   
	}
	
	public function getCatalogyear($catalog = 0){
	    $catalog = (int) $catalog;
	    
	    if(!$catalog) return array();
	    
	   $sql_year = "select * from catalogyear 
            	where catalog='$catalog' order by yearstart ";
            	
       $select = $this->db->select()	    
	                      ->from(array('catalogyear'))
	                      ->where('catalog=?',$catalog)
	                      ->order("yearstart");
	   return    $this->db->fetchAll($select);	   
	}
	
	public function getCatalognewmycatalogItem($user_id=0,$catalog=0){

	    $user_id = (int) $user_id;
	    $catalog = (int) $catalog;
	    if(!$user_id||!$catalog) return array();
	    
	    $select = $this->db->select()	    
	                      ->from(array('s'=>'catalognewmycatalog'))
	                      ->where('user=?',$user_id)
	                      ->where("catalog=?", $catalog);

	   return    $this->db->fetchRow($select);	   
	}
	
	public function addMycatalogItem($user_id=0,$catalog=0){
	    $user_id = (int) $user_id;
	    $catalog = (int) $catalog;

	    if(!$user_id||!$catalog) return 0;
	    
	    $data = array('user'=>$user_id,
	                  'type'=>0,
	                  'dateinsert' =>time(),
	                  'catalog'=>$catalog);
	                  
	    $this->db->insert('catalognewmycatalog',$data);
	    return   $this->db->lastInsertId('catalognewmycatalog');         
	   
	}
	
	public function getMyCatalogshopcoinssubscribeItem($user_id=0,$catalog=0){
	    $user_id = (int) $user_id;
	    $catalog = (int) $catalog;

	    if(!$user_id||!$catalog) return 0;
	    
	   $select = $this->db->select()	    
	                      ->from(array('s'=>'catalogshopcoinssubscribe'))
	                      ->where('user=?',$user_id)
	                      ->where("catalog=?", $catalog);
	   return    $this->db->fetchRow($select);	  
	}
	
	public function addMycatalogshopcoinssubscribeItem($user_id=0,$catalog=0,$amountacsessory=1){
	    $user_id = (int) $user_id;
	    $catalog = (int) $catalog;

	    if(!$user_id||!$catalog) return 0;	    
		
	    $data = array('user'=>$user_id,
	                  'datesend'=>0, 
	                  'amountdatesend'=>0, 
	                  'buy'=>0,
	                  'amount'=>$amountacsessory,
	                  'dateinsert' =>time(),	                  
	                  'catalog'=>$catalog);
	                  
	    $this->db->insert('catalogshopcoinssubscribe',$data);
	    return   $this->db->lastInsertId('catalogshopcoinssubscribe');         
	   
	}
	
	public function changemycatalog($user_id=0,$catalog,$typechange,$detailschange){
	    $user_id = (int) $user_id;
	    $catalog = (int) $catalog;

	    if(!$user_id||!$catalog) return 0;	    
		
	    $data = array('user'=>$user_id,
	                  'type'=>(int) $typechange, 
	                  'detailschange'=>strip_tags($detailschange));
	                  
	    $this->db->update('catalognewmycatalog',$data,"user=$user_id and catalognewmycatalog=$catalog");
	    return  true;    
	}
	
	public function deleteMyCatalogshopcoinssubscribeItem($user_id=0,$catalog=0){
	    $user_id = (int) $user_id;
	    $catalog = (int) $catalog;

	    if(!$user_id||!$catalog) return false;	    
		
	    $this->db->delete('catalogshopcoinssubscribe',"user=$user_id and catalog=$catalog");
	    return   true;         
	}
	public function deleteCatalognewmycatalogItem($user_id=0,$catalog=0){
	    $user_id = (int) $user_id;
	    $catalog = (int) $catalog;

	    if(!$user_id||!$catalog) return false;	    
		
	    $this->db->delete('catalognewmycatalog',"user=$user_id and catalog=$catalog");
	    return   true;         
	}
	
	public function editMycatalogshopcoinssubscribeItem($user_id=0,$catalog=0,$amountacsessory=1){
	    $user_id = (int) $user_id;
	    $catalog = (int) $catalog;

	    if(!$user_id||!$catalog) return 0;	
	    	    
	    $data = array('amount'=>$amountacsessory,
	                  'dateinsert' =>time());
	                  
	    $this->db->update('catalogshopcoinssubscribe',$data,"`user`='$user_id' and catalog='$catalog'");
	    return true;         	   
	}
	
	
	
	public function getCatalogshopcoinsrelation($CatalogArray=array()){
	    /*$$sql_shopcoins = "select catalogshopcoinsrelation.* 
		from catalogshopcoinsrelation, shopcoins
		where catalogshopcoinsrelation.catalog in (".implode(",", $CatalogArray).") 
		and shopcoins.shopcoins = catalogshopcoinsrelation.shopcoins
		and shopcoins.`check`=1 and shopcoins.dateinsert>0 and shopcoins.dateorder=0";
        //echo $sql_shopcoins;*/

	    if(!$CatalogArray) return array();
	    
	    $select = $this->db->select()	    
	                      ->from(array('catalogshopcoinsrelation'))
	                      ->join(array('s'=>'shopcoins'),'s.shopcoins = catalogshopcoinsrelation.shopcoins',array())
	                      ->where('s.check=1 and s.dateinsert>0 and s.dateorder=0')
	                      ->where("catalogshopcoinsrelation.catalog in (".implode(",", $CatalogArray).")");
	   //echo $select->__toString()."<br>";
	   return    $this->db->fetchAll($select);	   
	}
	 
	//группы для выборки
	
	public function getGroups($WhereParams=array()){	
		
		$select = $this->db->select();			
		
		$select->from(array('s'=>'catalognew'),array('distinct(s.group)'))
	      ->where("s.materialtype=?",$this->materialtype)
	      ->where("s.agreement >= 0"); 
		
		if(($this->materialtype==1||$this->materialtype==8)){
		    $select->join(array('catalogpositionname'),'catalogpositionname.catalogpositionname=s.catalogpositionname',array());
		}  	          
		$select = $this->byParams($select,$WhereParams);	
            //echo $select ->__toString();
        return $this->db->fetchAll($select);       
	} 
	
	public function getOtherMaterialData($group,$materialtype=1){

	    $data = array();
	    if(!$group||!$materialtype) return $data;
	    $sql = "select * from shopcoins where shopcoins.check=1 and shopcoins.dateinsert<>0 and `group` = '$group'	group by shopcoins.parent order by rand() limit 5";
	   
    	return $this->getDataSql($sql);	    
	}
	
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
	    
        //echo "<br>".$select->__toString()."<br>";
        
        return $this->db->fetchAll($select);     
	}
	
	public function isDetectors(){
		if($this->materialtype==self::$detectorsID){
			return true;
		}
		return false;
	}
	
	protected function byParams($select,$WhereParams=array()){	
		
		if(isset($WhereParams['catalogshopcoinssubscribe'])&&$WhereParams['catalogshopcoinssubscribe']) {    
		    $select->join(array('catalogshopcoinssubscribe'),'catalogshopcoinssubscribe.catalog = s.catalog',array());
		    $select->where("catalogshopcoinssubscribe.user=?",$WhereParams['catalogshopcoinssubscribe']); 		
		}
		
        if(isset($WhereParams['usercatalogsubscribe'])&&$WhereParams['usercatalogsubscribe']) {     
            $select->join(array('catalogsubscribe'),'catalogsubscribe.catalog = s.catalog',array());
		    $select->where("catalogsubscribe.user=?",$WhereParams['usercatalogsubscribe']); 		
        }   
          
	if(isset($WhereParams['catalognewmycatalog_usermycatalog'])||isset($WhereParams['catalognewmycatalog_usermycatalogchange'])) {     
            $select->join(array('catalognewmycatalog'),'catalognewmycatalog.catalog = s.catalog',array());
            
            if(isset($WhereParams['catalognewmycatalog_usermycatalog'])&&$WhereParams['catalognewmycatalog_usermycatalog']){
		$select->where("catalognewmycatalog.user=?",$WhereParams['catalognewmycatalog_usermycatalog']); 	
            }	
            
            if(isset($WhereParams['catalognewmycatalog_usermycatalogchange'])&&$WhereParams['catalognewmycatalog_usermycatalogchange']){
		$select->where("catalognewmycatalog.type=1 and catalognewmycatalog.user=?",$WhereParams['catalognewmycatalog_usermycatalogchange']); 	
            }	
       }    
             
	   return  $select;    
	}
	
	public function getMyGroupSubscribe($user_id,$group=0){
	    $user_id = (int) $user_id;
        $group = (int) $group;
        
	    if(!$user_id||!$group) return 0;	    		

	    $select = $this->db->select()
	           ->from(array('catalogsubscribe'))
		      ->where("user=?",$user_id)
		      ->where("`group`=?",$group);       
         
		return   $this->db->fetchOne($select);         
	}
	
	public function getMyCatalogSubscribe($user_id,$catalog=0){
	    $user_id = (int) $user_id;
	    $catalog = (int) $catalog;
        
	    if(!$user_id||!$catalog) return 0;	    
	    		
        $select = $this->db->select()
              ->from(array('catalogsubscribe'))
		      ->where("user=?",$user_id)
		      ->where("catalog=?",$catalog);       
	    
	   return   $this->db->fetchOne($select);   
	}
	
	public function addCatalogsubscribe($user_id,$catalog=0,$group=0){
	    $user_id = (int) $user_id;
	    $catalog = (int) $catalog;
        $group = (int) $group;
        
	    if(!$user_id) return 0;	    		

	    $data = array('user'=>$user_id,
	                  'date'=>time(), 
	                  'group'=>$group, 	                  
	                  'catalog'=>$catalog);
	                  
	    $this->db->insert('catalogsubscribe',$data);
	    return   $this->db->lastInsertId('catalogsubscribe');         
	}
	
	public function getParrentGroupsIds($group_id,$WhereParams){
	    $GroupArray = array();
	    if((int)$group_id){
	        
	        $select = $this->db->select();   		
    		$select->from(array('s'=>'catalognew'),array('distinct(s.group)'))
    	      ->where("s.materialtype=?",$this->materialtype)
    	      ->where("s.agreement >= 0"); 
    		
    		$select->join(array('group'),'s.group=group.group',array('gname'=>'group.name'));             
    		$select = $this->byParams($select,$WhereParams);    	   
	
	        $select->where('groupparent=?',(int)$group_id);
	        
            $groups = $this->db->fetchAll($select);
             
    	    
        	foreach ($groups as $rows_group){
        		$GroupArray[] = $rows_group['group'];
        	}
        	
	    } 
	    return $GroupArray;
	}
	
	//получаем металы для выборки
	public function getMetalls($all=false,$groups=array(),$nominals=array(),$WhereParams=array()){	  	   
	    
	    $select = $this->db->select();		
		
	    $select->from(array('s'=>'catalognew'),array('distinct(s.metal)'))
	      ->where("s.materialtype=?",$this->materialtype)
	      ->where("s.agreement >= 0"); 
		
		
		//$select->join(array('group'),'s.group=group.group',array());  
		$select->join(array('shopcoins_metal'),'s.metal=shopcoins_metal.id',array('name'=>'shopcoins_metal.name')); 
		           
		$select = $this->byParams($select,$WhereParams);    	   
    		
	    $select->where('s.metal>0 ')             
	            ->order('shopcoins_metal.name asc');	                  
	    
	   if($groups){
           $select->where("s.group in (".implode(",",$groups).")");
       }     
        
       if($nominals){	       	
          // $select->where("nominal_id in (".implode(",",$nominals).")");
	   }	   
	   //echo $select->__toString();
	   
	   return $this->db->fetchAll($select);       
	}
	
	 public function getMinYear($groups=array(),$nominals=array()){
        $select = $this->db->select();
			
		
		$select->from(array('s'=>'catalognew'),array('min(yearstart)'))
		      ->where("s.materialtype=?",$this->materialtype)
		      ->where("s.agreement >= 0")
		      ->where('yearstart>0');         
         
    	 if($groups){
    	      $select->where("s.group in (".implode(",",$groups).")");
    	 }
    	 
         
    	 if($nominals){	       	
             // $select->where("nominal_id in (".implode(",",$nominals).")");
    	 }
    	 
	     return $this->db->fetchOne($select);
    }
    
    public function getMaxYear($groups=array(),$nominals=array()){
         $select = $this->db->select();
			
		
		 $select->from(array('s'=>'catalognew'),array('max(yearstart)'))
		      ->where("s.materialtype=?",$this->materialtype)
		      ->where("s.agreement >= 0")
		      ->where('yearstart>0');         
         
    	 if($groups){
    	      $select->where("s.group in (".implode(",",$groups).")");
    	 }
    	 
         
    	 if($nominals){	       	
             // $select->where("nominal_id in (".implode(",",$nominals).")");
    	 }

	     return $this->db->fetchOne($select);
    }
    
    public function getYears($nominals=array(),$groups=array()){
	    
	  $WhereParams['group'] = true;
	  $select = $this->db->select()
	  		  ->from(array('s'=>'catalognew'),array('year'=>'distinct(yearstart)'))		 	
		      ->where("s.materialtype=?",$this->materialtype)
		      ->where("s.agreement >= 0")
		      ->order('s.yearstart desc');              
	   
	   if($nominals){	       	
            $select->where("nominal_id in (".implode(",",$nominals).")");
	   }   
       
       if($groups){
           $select->where("`group` in (".implode(",",$groups).")");
       }
       
	   return $this->db->fetchAll($select);       
	}
	
	public function getNominals($groups=array()){
	    if(!$groups) return array();
	    	    
	    $select = $this->db->select();
		 
		$select->from(array('s'=>'catalognew'),array('distinct(nominal_id)'))
		 		->join(array('sn'=>'shopcoins_name'), 'nominal_id=sn.id',array('name'))
		      ->where("s.materialtype=?",$this->materialtype)
		      ->where("s.agreement >= 0")
		      ->where("s.group in (".implode(",",$groups).")")
		      ->order('s.name asc');         
         
       if(($this->materialtype==1||$this->materialtype==8)){
		    $select->join(array('catalogpositionname'),'catalogpositionname.catalogpositionname=s.catalogpositionname',array());
	   } 	   
        //echo "<!--".$select->__toString()."-->" ;      
       $result = $this->db->fetchAll($select);
       
       foreach ($result as &$row){
    	    $number = (int) $row['name'];
    	    $string = str_replace($number,'<<<>>>',$row['name']);
    	    $n = number_format($number, 0, ',', '.');
    	    $row['name'] = str_replace('<<<>>>',$n, $string);        	    
       }  
    	   
	   return $result;    
	}
	
	public function addCatalognewItem($data=array()){
        $this->db->insert('catalognew',$data);	    
	    return   $this->db->lastInsertId('catalognew');  
    }
    
	public function addReview($catalog=0,$user_id=0,$details=''){
	    $user_id = (int) $user_id;
	    $catalog = (int) $catalog;	    

	    if(!$user_id||!$catalog||!$details) return false;	    		

	    $data = array('catalog'=>$catalog,
	                  'user'=>$user_id,
	                  'date'=>time(), 
	                  'catalogreviewtype'=>0, 	
	                  'details' =>strip_tags($details)                 
	                  );
	                  
	    $this->db->insert('catalogreview',$data);
	    
	    return   $this->db->lastInsertId('catalogreview');  
	    
	}
		
    /*public function getConditions($all=false,$groups=array(),$nominals=array(),$bydate=0){
	    $select = $this->db->select();
					
		$select->from(array('s'=>'catalognew'),array('distinct(s.condition)'))
	      ->where("s.materialtype=?",$this->materialtype)
	      ->where("s.agreement >= 0"); 		
		
		$select->join(array('group'),'s.group=group.group',array());  
		           
		$select = $this->byParams($select,$WhereParams);    	   
    		
	    $select->where('s.condition>0');                
	            
	    if($groups){
           $select->where("`group` in (".implode(",",$groups).")");
        } 
        
        
        if($nominals){	       	
            //$select->where("nominal_id in (".implode(",",$nominals).")");
	    }
    	    
	    if($this->user_id==352480){
        	//echo $select->__toString();
        }
	   	return $this->db->fetchAll($select);    
	}*/
    
    public function CatalogHistoryAgreement ($catalog,$cataloghistory,$useradd) {
    	//global $catalog, $cataloghistory, $agreevalue, $user, $averagestar, $useradd;
    	$cataloghistory = (int) $cataloghistory;
    	$catalog = (int) $catalog;
    	
    	if(!$cataloghistory||!$catalog) return false;	    	
    	
    	//за три правильных, если нет отрицательных ставим agreementdate и делаем update в catalognew, также увеличиваем рейтинг пользователей на 1 балл
    	$sql = "select sum(if(cataloghistoryagreement.agree=2,1,0)) as no, 
    	sum(if(cataloghistoryagreement.agree=2,user.star,0)) as nostar,
    	sum(if(cataloghistoryagreement.agree=1,1,0)) as yes,
    	sum(if(cataloghistoryagreement.agree=1,user.star,0)) as yesstar
    	from cataloghistoryagreement, user
    	where cataloghistory='$cataloghistory'
    	and cataloghistoryagreement.user=user.user;";    	
    	
    	$rows = $this->getRowSql($sql);
    	
    	//при добавлении
    	if ((($rows["yes"]-$rows["no"]) >= 3 and ($rows["yesstar"] - $rows["nostar"]) >= 3*$averagestar)or ($admin and $agreevalue==1)){
    		//делаем update catalognew
    		
            $select = $this->db->select()	    
                      ->from(array('cataloghistory'))
                      ->where('cataloghistory=?',$cataloghistory);
            
            $rows = $this->db->fetchRow( $select);    
            
            if ($rows["field"] == "yearstart"){
                $this->db->delete('catalogyear',"catalog='$catalog'");                   
                $tmp = explode(",", $rows["fieldnowvalue"]);
                
                foreach ($tmp as $key=>$value){    				
    				$tmp1 = explode("-",$value);    				
    				if ($tmp1[0]>0 or $tmp1[1]>0) {
    					if (!$yearstart and $tmp1[0])
    						$yearstart = $tmp1[0];
    					if (!$yearstart and $tmp1[1])
    						$yearstart = $tmp1[1];
    					
    					if (!$tmp1[0] and $tmp1[1])
    						$tmp1[0] = $tmp1[1];
    					
    					if ($tmp1[0] and !$tmp1[1])
    						$tmp1[1] = $tmp1[0];
    					
    					
    					$data = array('yearstart'=>$tmp1[0],
                	                  'yearend'=>$tmp1[1],
                	                  'catalog'=>$catalog);
                	                  
                	    $this->db->insert('catalogyear',$data);
                	    
                	    $data = array('yearstart'=>$yearstart);
    					
                	    $this->db->update('catalognew',$data,"catalog='$catalog'");             	                  
    				}
    			}
    		} elseif ($rows["field"] == "image_big_url") {
    		    $data = array('image_big_url'=>$rows["fieldnowvalue"],
    		                  'image_small_url' => str_replace("i/", "is/", $rows["fieldnowvalue"]));    					
                $this->db->update('catalognew',$data,"catalog='$catalog'");                
    		} elseif ($rows["field"] == "all") {
    		    $data = array('agreement'=>1);    					
                $this->db->update('catalognew',$data,"catalog='$catalog'");             
    		} else {
    		    $data = array($rows["field"] =>$rows["fieldnowvalue"]);    					
                $this->db->update('catalognew',$data,"catalog='$catalog'");     
            }
            
    		$select = $this->db->select()	    
                      ->from(array('cataloghistoryagreement'),array('user'))
                      ->where('cataloghistoryagreement.agree=1')                      
                      ->where('cataloghistory=?',$cataloghistory);
    		//увеличиваем всем рейтинг
    		$result_user = $this->db->fetchAll($select);
    		
    		$UserArray = array();
    		foreach ($result_user as $rows){
    			$UserArray[] = $rows["user"];
    		}
    		
    		if (sizeof($UserArray)) {
    			$sql_update_star = "update user set star=star+1 where user in (".implode(",", $UserArray).");";
    			$this->db->query($sql_update_star);
    		}
    		
    		//добавившему +2
    		if (intval($useradd)){
    			$sql_update_star = "update user set star=star+2 where user ='".(int)$useradd."';";
    			$this->db->query($sql_update_star);
    		}
    		
    		//проставляем agreementdate
    		$data = array('agreementdate'=>time());    					
            $this->db->update('cataloghistory',$data,"catalog='$catalog'");  
                		
    	} elseif (($rows["no"]-$rows["yes"]) >= 3 and ($rows["nostar"] - $rows["yesstar"]) >= 3*$averagestar)  	{
    	    //за три нет - ставим agreementdate, увеличиваем рейтинг этих пользователей, уменьшаем рейтинг пользователя, который добавил
    		//делаем update catalognew
    		$select = $this->db->select()	    
                      ->from(array('cataloghistory'))
                      ->where('cataloghistory=?',$cataloghistory);
            
            $rows = $this->db->fetchRow( $select);             
           
    		if ($rows["field"] == "all") {
    		    $data = array('agreement'=>'-1');    					
                $this->db->update('catalognew',$data,"catalog='$catalog'");                
    		}
    		
    		//увеличиваем всем рейтинг
    		$sql_user = "select user from cataloghistoryagreement where cataloghistory='$cataloghistory' and cataloghistoryagreement.agree=2;";
    		
    		$select = $this->db->select()	    
                      ->from(array('cataloghistoryagreement'),array('user'))
                      ->where('cataloghistoryagreement.agree=2')
                      ->where('cataloghistory=?',$cataloghistory);
    		//увеличиваем всем рейтинг
    		$result_user = $this->db->fetchAll($select);
    		
    		$UserArray = array();
    		foreach ($result_user as $rows){
    			$UserArray[] = $rows["user"];
    		}   		
    		
    		if (sizeof($UserArray)) {
    			$sql_update_star = "update user set star=star+1 where user in (".implode(",", $UserArray).");";
    			$this->db->query($sql_update_star);
    		}
    		
    		//добавившему -2
    		if ($useradd) {
    			$sql_update_star = "update `user` set star = (if (star>6,star-2,star)) where `user`='$useradd';";
    			$this->db->query($sql_update_star);
    		}
    		
    		//проставляем agreementdate
    		$data = array('agreementdate'=>time());    					
            $this->db->update('cataloghistory',$data,"catalog='$catalog'");  
    	}
    	
    	return true;
    }
    
    public function migrateNominals(){

	     $select = $this->db->select()
                  ->from('catalognew',array('distinct(name)'))
                  ->where('name<>"" and nominal_id=0')
                  ->order('name asc')
                  ->limit(2000);
                  //echo $select->__toString();
         foreach ($this->db->fetchAll($select) as $row){
             $select  = $this->db->select()
                                ->from('shopcoins_name',array('id'))
                                ->where('name=?',$row['name']);
             $n_id = $this->db->fetchOne($select);
             if(!$n_id){
             	echo $row['name']. " not found<br>";
                 //$data = array('name'=>$row['name']);
                 //$this->db->insert('shopcoins_name',$data);
                 //$n_id = $this->db->lastInsertId('shopcoins_name');
             }
             $data = array('nominal_id'=>$n_id);
             $this->db->update('catalognew',$data,"name='".$row['name']."'");             
         }                 
	}
	
	public function getCatalogtransaction(){
	    $select = $this->db->select()
                  ->from('catalogtransaction',array('max(transaction)'));
        return $this->db->fetchOne($select);
	}
	
	public function addCataloghistoryField($transaction,$field='',$value='',$oldvalue=''){
	   $transaction = (int)$transaction;	   
       
	    $data = array('transaction'=>$transaction,
	                  'date'=>time(),
	                  'agreementdate' =>0,
	                  'field'=>$field, 	                
	                  'fieldoldvalue' => $oldvalue,
	                  'fieldnowvalue'=>$value );
	                  
	    $this->db->insert('cataloghistory',$data);
	    
	    return   $this->db->lastInsertId('cataloghistory');  
	}
	
	public function saveImageData($data){
	    /*	$sql = "insert into  values 
    	('0', '$size[0]', '$size[1]',
    	'$FolderIn.$Image', '$k', '$TypeImage[1]');";
    	$result = mysql_query($sql);*/
	   // $this->db->insert('ttt',$data);
	    return true;
	}
}

?>