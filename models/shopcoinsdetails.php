<?php

class model_shopcoins_details extends Model_Base {	

	public function getItem($id){
	    $select = $this->db->select()
                  ->from('shopcoins_details')                  
                  ->where('catalog=?',$id);
        return $this->db->fetchRow($select);
	}	
	
	 public function migrateDetails(){
        $select = $this->db->select()
                  ->from('shopcoins',array('shopcoins','details'))
                  ->joinLeft('shopcoins_details','shopcoins.shopcoins=shopcoins_details.catalog',array('catalog'))
                  ->where("(trim(shopcoins.details) > '' and  shopcoins.details is not null and shopcoins.details<>'&nbsp;' and shopcoins.details<>'<br>&nbsp;') and catalog is null")
                  ->limit(10000);
         foreach ($this->db->fetchAll($select) as $row){
             
             $data = array('catalog'=>$row['shopcoins'],
                           'details'=>$row['details']);
                           var_dump($data);
                           echo "<br>";
             $this->db->insert('shopcoins_details',$data);              
         }          
        
    }
    
     public function migrateKeywords(){
        $select = $this->db->select()
                  ->from('shopcoins',array('shopcoins','keywords'))
                  ->joinLeft('shopcoins_keywords','shopcoins.shopcoins=shopcoins_keywords.catalog',array('catalog'))
                  ->where("(trim(shopcoins.keywords) > '' and shopcoins.keywords is not null and shopcoins.keywords<>'&nbsp;' and shopcoins.keywords<>'<br>&nbsp;') and catalog is null")
                  ->limit(10000);
 
         foreach ($this->db->fetchAll($select) as $row){
             var_dump($row["keywords"]);
             echo "<br>";
             $data = array('catalog'=>$row['shopcoins'],
                            'keywords'=>$row['keywords']);
             $this->db->insert('shopcoins_keywords',$data);            
         }        
    }
    
    public function search($SearchTempStr=array()){	   
	    $select = $this->db->select()               
 	 	          ->from('shopcoins_details');
 		if($SearchTempStr){
 			$select->where("details like '%".implode("%' or details like '%",$SearchTempStr)."%'");
 		} 	else return array();

 		$data = array();
 		foreach ($this->db->fetchAll($select) as $row){
 		    $data[$row['catalog']] = $row['details'];
 		}
        return $data;   
	}
}