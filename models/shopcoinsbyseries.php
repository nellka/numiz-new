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
	    return $parser_by_where[0];
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
	
	public function getCoinsBySeries($sql,$page=1, $items_for_page=30,$orderby=''){
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
	
	public function getCountCoinsBySeries($sql){
	    $where = $this->parserByWhere($sql);	   
	    $WhereParams = $this->getWhereParam($where);

	    $select = $this->db->select()
		               ->from(array('s'=>'shopcoins_search'),array('count(*)'));
		               
        if (isset($WhereParams['name'])) {
        	$select->joinLeft(array('shopcoins_search_name'),'shopcoins_search_name.id=nominal_id',array());
        }
        if (isset($WhereParams['details'])) {
        	$select->joinLeft(array('shopcoins_search_details'),'shopcoins=catalog',array());
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
}