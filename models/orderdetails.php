<?php
class model_orderdetails extends Model_Base 
{	
	
	public function getMySum($shopcoinsorder){
		//получаем сумму по заказу
	  $select = $this->db->select()
		               ->from($this->table,array('sum(orderdetails.amount*shopcoins.price)'))
		               ->join(array('shopcoins'),'orderdetails.catalog=shopcoins.shopcoins')
		               ->where('orderdetails.order=?',$shopcoinsorder); 
		
       return (integer)$this->db->fetchOne($select);       
	}
	
	public function getCounter($shopcoinsorder){
		$select = $this->db->select()
		               ->from($this->table,array('count(catalog)'))
		               ->where($this->table.'.order=?',$shopcoinsorder)
		               ->where('status=0'); 		
       return $this->db->fetchOne($select);       
	}	
	
	public function getMinDate($shopcoinsorder){		
		$select = $this->db->select()
		               ->from($this->table,array('min(date)'))
		               ->where($this->table.'.order=?',$shopcoinsorder)
		               ->where('status=0'); 		
       return $this->db->fetchOne($select);       
	}	
	
	
}