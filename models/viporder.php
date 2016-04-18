<?php

class model_shopcoinsvipclientanswer extends Model_Base {	

	public function getNewViporder(){
	    $select = $this->db->select()
                  ->from($this->table,array('max(viporder)'))                  
                  ->limit(1);
        return $this->db->fetchOne($select)+1;
	}
	
	public function addInOrder($viporder_id,$cid){
		$data = array(
                    'user_id' => 0, 
	                'viporder' => $viporder_id, 
	                'shopcoins' => $cid, 
	                'dateinsert' => time());
		$this->db->insert($this->table,$data);
	}
	
	public function getCoins($id){
		 $select = $this->db->select()
                  ->from($this->table)
                  ->join(array('shopcoins'),"shopcoins.shopcoins=$this->table.shopcoins")
                  ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name','ggroup'=>'group.groupparent'))                  
                  ->where('viporder=?',$id)
                  ->order('shopcoins.dateinsert desc');
        return $this->db->fetchAll($select);
	}
	
}