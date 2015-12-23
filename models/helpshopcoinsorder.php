<?php

/*Класс для получения информации о пользователе*/
class model_helpshopcoinsorder extends Model_Base 
{	 
    public function countAllByParams($row_name,$params=array()){
        $select = $this->db->select()
		               ->from($this->table,array('count(*)'));
		foreach ($params as $key=>$value) {
		    $select->where("$key=?",$value) ;
		}         
    	return $this->db->fetchOne($select);       
    }
	
    public function countReserved($id,$reservetime){	
         $select = $this->db->select()
		               ->from($this->table,array('count(*)'))
		               ->where('shopcoins=?',$id)
		               ->where('reserve >?',$reservetime);
		
		return $this->db->fetchOne($select);             
	 }
	 
	 public function getReserved($id,$reservetime,$limit=0){
	     $select = $this->db->select()
		               ->from($this->table)
		               ->where('shopcoins=?',$id)
		               ->where('reserve >?',$reservetime);
		if($limit) $select->limit($limit);
		return $this->db->fetchAll($select);             
	 }
}