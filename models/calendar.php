<?php
class model_calendar extends Model_Base
{	
	    
	public function getDates(){
	    $select = $this->db->select()
	               ->from($this->table)
	               ->where("(date_from  <= CURDATE() AND date_to >= CURDATE()) OR (UNIX_TIMESTAMP(show_from) > 0 AND show_from <= CURDATE())")
	               ->order('date_from');	  
    	return $this->db->fetchAll($select);
	} 
	
	public function getMetro(){
	      $select = $this->db->select()
                  ->from('metro')
                  ->group('name')
                  ->order('position');
	       return $this->db->fetchAll($select);
	 }
}

?>