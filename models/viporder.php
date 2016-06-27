<?php

class model_shopcoinsvipclientanswer extends Model_Base {	

	public function getNewViporder(){
	    $select = $this->db->select()
                  ->from($this->table,array('max(viporder)'))                  
                  ->limit(1);
        return $this->db->fetchOne($select)+1;
	}
	
	public function addInOrder($viporder_id,$cid,$admin_id=0){
		$data = array(
                    'user_id' => 0, 
	                'viporder' => $viporder_id, 
	                'shopcoins' => $cid, 
	                'dateinsert' => time(),
	                'admin_id'   =>$admin_id);
		$this->db->insert($this->table,$data);
	}
	//удаляем записи двухнедельной давности
	public function clear(){
		return $this->db->delete($this->table,'dateinsert < ( unix_timestamp(now()) -14 *3600 *24)');
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
	
	public function getAdminInCoins($ids=array()){
	    $idadmin = 0;
	    
	    if(!empty($ids)){
	         $select = $this->db->select()
                  ->from($this->table,array('admin_id'))
                  ->where('shopcoins in('.implode(',',$ids).')')
                  ->order('admin_id desc');
             $idadmin = (int) $this->db->fetchOne($select);             
	    }
	    
	    return $idadmin;
	}
	
}