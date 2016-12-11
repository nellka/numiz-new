<?php

class model_shopcoinsbyseries extends Model_Base {		
    
    public function getSeriesDataById($id,$status=true){
        
        if(!(int) $id) return false;
        
		$select  =  $this->db->select()
                  ->from($this->table)
                  ->where('id=?',$id) ;
                  
       if($status){
           $select->where('status=?',1);
       }
       
	   return $this->db->fetchRow($select);
	}
	
	public function getSeries($status=true){        
       
		$select  =  $this->db->select()
                  ->from($this->table);
                  
       if($status){
           $select->where('status=?',1);
       }
       
	   return $this->db->fetchAll($select);
	}
	public function getAllSeries($status=true){        
       
		$select  =  $this->db->select()
                  ->from($this->table)                  
                  ->order('position Asc');
                  
       if($status){
           $select->where('status=?',1);
       }
       
	   return $this->db->fetchAll($select);
	}
	
	public function getSeriesByCountry($group_id, $status=true){        
       
		$select  =  $this->db->select()
                  ->from($this->table)                  
                  ->where('countrygroup=?',$group_id)
                  ->order('position Asc');
                  
       if($status){
           $select->where('status=?',1);
       }
       
	   return $this->db->fetchAll($select);
	}
	
	private function parserByWhere($sql){
		
	    $parser_by_where = explode('where',$sql);
	    if(isset($parser_by_where[1])) return $parser_by_where[1];
	    $where  = $parser_by_where[0];
	    
	    //$where = str_replace('group','s.group',$where);
	    //$where = str_replace('`','',$where);
	    
	    //var_dump($where);
	    return $where;
	}
	
	private function getWhereParam($sql){
	    $WhereParam = array('name'=>false,
	                        'details'=>false);
	                        
	    if(strpos($sql,"name")!==false){
	        $WhereParam['name'] = true;
	    }
	    
	    if(strpos($sql,"details")!==false){
	        $WhereParam['details'] = true;
	    }
	    
	    return $WhereParam;
	}
	
	public function getCoinsBySeries($sql,$page=1, $items_for_page=30,$orderby='',$params=array()){
	    $where = $this->parserByWhere($sql);
	    $where = str_replace("`group`","s.group",$where);
	    $WhereParams = $this->getWhereParam($where);

	    $select = $this->db->select()
		               ->from(array('s'=>'shopcoins_search'))
		               ->join(array('group'),'s.group=group.group',array('gname'=>'group.name'));
		               
        if (isset($WhereParams['name'])) {
        	$select->joinLeft(array('shopcoins_search_name'),'shopcoins_search_name.id=nominal_id');
        }
        if (isset($WhereParams['details'])) {
        	$select->joinLeft(array('shopcoins_search_details'),'shopcoins=catalog');
        }
        if (isset($params['groups'])&&$params['groups']) {
        	$select->where("s.group in (".implode(",",$params['groups']).")");
        }
        if (isset($params['nominals'])&&$params['nominals']) {
        	$select->where("nominal_id in (".implode(",",$params['nominals']).")");
        }
        if (isset($params['metals'])&&$params['metals']) {
        	$select->where("metal_id in (".implode(",",$params['metals']).")");
        }
        if (isset($params['years'])&&$params['years']) {
        	$select->where("year in (".implode(",",$params['years']).")");
        }
        if (isset($params['conditions'])&&$params['conditions']) {
        	$select->where("condition_id in (".implode(",",$params['conditions']).")");
        }
        $select->where('s.check=1');
        
        if($where){
            $select->where($where);
        }       
        if($items_for_page!='all'){
	        $select->limitPage($page, $items_for_page);
	    } 
	    if($orderby) $select->order($orderby);
		echo "<!--".$select->__toString()."-->";
	    return $this->db->fetchAll($select);  
	} 
	
	public function getCountCoinsBySeries($sql,$params=array()){
	    $where = $this->parserByWhere($sql);
	    $where = str_replace("`group`","s.group",$where);
	    $WhereParams = $this->getWhereParam($where);	   

	    $select = $this->db->select()
		               ->from(array('s'=>'shopcoins_search'),array('count(*)'));
		               
        if (isset($WhereParams['name'])) {
        	$select->joinLeft(array('shopcoins_search_name'),'shopcoins_search_name.id=nominal_id',array());
        }
        if (isset($WhereParams['details'])) {
        	$select->joinLeft(array('shopcoins_search_details'),'shopcoins=catalog',array());
        }
        if (isset($params['groups'])&&$params['groups']) {
        	$select->where("s.group in (".implode(",",$params['groups']).")");
        }
        if (isset($params['nominals'])&&$params['nominals']) {
        	$select->where("nominal_id in (".implode(",",$params['nominals']).")");
        }
        if (isset($params['metals'])&&$params['metals']) {
        	$select->where("metal_id in (".implode(",",$params['metals']).")");
        }
        if (isset($params['years'])&&$params['years']) {
        	$select->where("year in (".implode(",",$params['years']).")");
        }
        if (isset($params['conditions'])&&$params['conditions']) {
        	$select->where("condition_id in (".implode(",",$params['conditions']).")");
        }
        $select->where('s.check=1');
        if($where){
            $select->where($where);
        }       
        
	    return $this->db->fetchOne($select);  
	} 
	
	public function getGroups($status=true){    
	   $select  =  $this->db->select()
                  ->from($this->table,array('distinct(countrygroup)'))
                  ->join(array('group'),'countrygroup = group.group');
                  
       if($status){
           $select->where('status=?',1);
       }
       
	   return $this->db->fetchAll($select);
	} 	
	
	public function getGroupsForFilter($where){    
		$where = $this->parserByWhere($where);
		$where = str_replace("`group`","s.group",$where);
	    $WhereParams = $this->getWhereParam($where);
		
		
	  	$select  =  $this->db->select()
                  ->from(ARRAY('s'=>'shopcoins_search'),array('distinct(s.group),group.name'))
                  ->join(array('group'),'s.group = group.group')
                  ->order('group.name asc');
                  
     
       $select->where($where);
       if (isset($WhereParams['name'])) {
        	$select->joinLeft(array('shopcoins_search_name'),'shopcoins_search_name.id=nominal_id',array());
        }
        if (isset($WhereParams['details'])) {
        	$select->joinLeft(array('shopcoins_search_details'),'shopcoins=catalog',array());
        }
        $select->where('s.check=1');
       //echo  $select->__toString();
      
	   return $this->db->fetchAll($select);
	} 	
	
	public function getNominalsForFilter($groups=array(), $where){    
		$where = $this->parserByWhere($where);
		$where = str_replace("`group`","s.group",$where);
	    $WhereParams = $this->getWhereParam($where);
		
	  	$select  =  $this->db->select()
                  ->from(ARRAY('s'=>'shopcoins_search'),array('distinct(s.nominal_id),shopcoins_name.name'))
                  ->join(array('shopcoins_name'),'shopcoins_name.id = s.nominal_id')
                  ->order('shopcoins_name.name asc');
                  
     
       $select->where($where);
       
       if($groups) $select->where("s.group in (".implode(",",$groups).")");
       if (isset($WhereParams['name'])) {
        	$select->joinLeft(array('shopcoins_search_name'),'shopcoins_search_name.id=nominal_id',array());
        }
        if (isset($WhereParams['details'])) {
        	$select->joinLeft(array('shopcoins_search_details'),'shopcoins=catalog',array());
        }
        $select->where('s.check=1');
       //echo  $select->__toString();
      
	   return $this->db->fetchAll($select);
	} 	
	
	public function getMetallsForFilter($groups=array(), $where,$nominals=array()){    
		
		$where = $this->parserByWhere($where);
		$where = str_replace("`group`","s.group",$where);
	    $WhereParams = $this->getWhereParam($where);	
		
	  	$select  =  $this->db->select()
                  ->from(ARRAY('s'=>'shopcoins_search'),array('distinct(s.metal_id),shopcoins_metal.name'))
                  ->join(array('shopcoins_metal'),'shopcoins_metal.id = s.metal_id')
                  ->order('shopcoins_metal.name asc');
                  
     
       $select->where($where);
       
       if($groups) $select->where("s.group in (".implode(",",$groups).")");
       if($nominals) $select->where("s.nominal_id in (".implode(",",$nominals).")");
       
       if (isset($WhereParams['name'])) {
        	$select->joinLeft(array('shopcoins_search_name'),'shopcoins_search_name.id=nominal_id',array());
        }
        if (isset($WhereParams['details'])) {
        	$select->joinLeft(array('shopcoins_search_details'),'shopcoins=catalog',array());
        }
        $select->where('s.check=1');
       //echo  $select->__toString();
      
	   return $this->db->fetchAll($select);
	} 	
	
	public function getConditionsForFilter($groups=array(), $where,$nominals=array()){    
		$where = $this->parserByWhere($where);
		$where = str_replace("`group`","s.group",$where);
	    $WhereParams = $this->getWhereParam($where);		
		
	  	$select  =  $this->db->select()
                  ->from(ARRAY('s'=>'shopcoins_search'),array('distinct(s.condition_id),shopcoins_condition.name'))
                  ->join(array('shopcoins_condition'),'shopcoins_condition.id = s.condition_id')
                  ->order('shopcoins_condition.name asc');
                  
     
       $select->where($where);
       
       if($groups) $select->where("s.group in (".implode(",",$groups).")");
       if($nominals) $select->where("s.nominal_id in (".implode(",",$nominals).")");
       
       if (isset($WhereParams['name'])) {
        	$select->joinLeft(array('shopcoins_search_name'),'shopcoins_search_name.id=nominal_id',array());
        }
        if (isset($WhereParams['details'])) {
        	$select->joinLeft(array('shopcoins_search_details'),'shopcoins=catalog',array());
        }
        $select->where('s.check=1');
       //echo  $select->__toString();
      
	   return $this->db->fetchAll($select);
	} 	
	
	public function getYearsForFilter($groups=array(), $where,$nominals=array()){    
		$where = $this->parserByWhere($where);
		$where = str_replace("`group`","s.group",$where);
	    $WhereParams = $this->getWhereParam($where);	
		
	  	$select  =  $this->db->select()
                  ->from(ARRAY('s'=>'shopcoins_search'),array('distinct(s.year)'))                 
                  ->order('s.year asc');                  
     
       $select->where($where);
       
       if($groups) $select->where("s.group in (".implode(",",$groups).")");
       
       if($nominals) $select->where("s.nominal_id in (".implode(",",$nominals).")");
       
       if (isset($WhereParams['name'])) {
        	$select->joinLeft(array('shopcoins_search_name'),'shopcoins_search_name.id=nominal_id',array());
        }
        if (isset($WhereParams['details'])) {
        	$select->joinLeft(array('shopcoins_search_details'),'shopcoins=catalog',array());
        }
        $select->where('s.check=1');
      // echo  $select->__toString();
      
	   return $this->db->fetchAll($select);
	} 	
}