<?php
class search extends model_shopcoins 
{	

	public function __construct($db,$user_id=0,$nocheck=0){
	    parent::__construct($db);	    
	}
	public function countByParams($where){
	   // $this->_getSelect($select) );	    
	    $sql = "Select count(*) from shopcoins $where;";
	    //echo $sql."<br>";
    	//$result=mysql_query($sql);
    	$result = $this->db->fetchOne($sql);
    	return $result;
	}
  
    //число товаров для вывода на страницах каталога магазина
	public function countAllByParams($materialtype,$WhereParams=array(),$searchid='',$yearsearch=''){
	   
       return $this->db->fetchOne($select);       
	}
	
	public function getItemsByParams($materialtype,$WhereParams=array(),$yearsearch='', $page=1, $items_for_page=30,$orderby='',$searchid=''){
	 
       return $this->db->fetchAll($select);
	} 	
}
?>